<?php

namespace App\Http\Controllers;

use App\Contracts\BrandContract;
use App\Contracts\CategoryContract;
use App\Contracts\ProductPurchaseContract;
use App\Contracts\SalesContract;
use App\Contracts\SalesItemContract;
use App\Contracts\ShopContract;
use App\Contracts\SupplierContract;
use App\Enums\PaymentStatus;
use App\Enums\SalesItemsStatus;
use App\Enums\SalesStatus;
use App\Http\Requests\StoreSalesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function __construct(
        protected ShopContract $shopRepository,
        protected ProductPurchaseContract $productPurchaseRepository,
        protected SupplierContract $supplierRepository,
        protected CategoryContract $categoryRepository,
        protected SalesContract $salesRepository,
        protected BrandContract $brandRepository,
        protected SalesItemContract $salesItemRepository
    ) {
    }

    public function index()
    {
        $shops = $this->shopRepository->all()->map(function ($shop) {
            return [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name,
            ];
        });

        $suppliers = $this->supplierRepository->all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'company_name' => $supplier->company_name,
            ];
        });

        return Inertia::render('SalesManagement/CreateSale', [
            'shops' => $shops,
            'suppliers' => $suppliers,
        ]);
    }

    public function getProductsBySupplier(Request $request)
    {
        $supplierId = $request->get('supplier_id');

        if (!$supplierId) {
            return response()->json(['products' => []]);
        }

        $products = $this->productPurchaseRepository->getProductsBySupplier($supplierId);

        return response()->json(['products' => $products]);
    }

    public function getVariantInventory(Request $request)
    {
        $productId = $request->get('product_id');
        $variant = $request->get('variant');

        if (!$productId || !$variant) {
            return response()->json(['error' => 'Product ID and variant are required'], 400);
        }

        $inventory = $this->productPurchaseRepository->getVariantInventory($productId, $variant);

        if (!$inventory) {
            return response()->json(['error' => 'Variant not found'], 404);
        }

        return response()->json($inventory);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'sale_date' => 'required|date',
            'include_free_bottles' => 'required|boolean',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant' => 'required|string',
            'items.*.cases_sold' => 'required|integer|min:1',
            'items.*.selling_price_per_case' => 'required|numeric|min:0',
            'items.*.free_bottles_per_case' => 'nullable|integer|min:0',
            'items.*.extra_free_bottles' => 'nullable|integer|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $data = [
                'shop_id' => $request->shop_id,
                'supplier_id' => $request->supplier_id,
                'invoice_number' => 'INV-' . Str::padLeft(mt_rand(1, 999999), 6, '0'),
                'total_amount' => 0,
                'subtotal' => 0,
                'status' => SalesStatus::IN_PROGRESS->value,
                'sale_date' => $request->sale_date,
                'due_amount' => 0,
            ];

            $sale = $this->salesRepository->create($data);
            $totalAmount = 0;
            $totalProfit = 0;

            foreach ($request->items as $item) {
                $product = $this->productPurchaseRepository->find($item['product_id']);
                $inventory = $this->productPurchaseRepository->getVariantInventory(
                    $item['product_id'],
                    $item['variant']
                );

                if (!$inventory) {
                    throw new \Exception("Variant {$item['variant']} not found");
                }

                $bottlesPerCase = $inventory['bottles_per_case'];
                $casesSold = $item['cases_sold'];
                $sellingPricePerCase = $item['selling_price_per_case'];
                $purchaseRatePerBottle = $inventory['purchase_rate'];

                // Calculate bottles sold
                $purchasedBottlesSold = $casesSold * $bottlesPerCase;
                $freeBottlesSold = 0;

                if ($request->include_free_bottles) {
                    $freeBottlesPerCase = $item['free_bottles_per_case'] ?? 0;
                    $extraFreeBottles = $item['extra_free_bottles'] ?? 0;
                    $freeBottlesSold = ($casesSold * $freeBottlesPerCase) + $extraFreeBottles;
                }

                $totalBottlesSold = $purchasedBottlesSold + $freeBottlesSold;

                // Validate inventory
                if ($purchasedBottlesSold > $inventory['purchased_bottles_available']) {
                    throw new \Exception("Insufficient purchased bottles for {$item['variant']}. Available: {$inventory['purchased_bottles_available']}, Required: {$purchasedBottlesSold}");
                }

                if ($freeBottlesSold > $inventory['free_bottles_available']) {
                    throw new \Exception("Insufficient free bottles for {$item['variant']}. Available: {$inventory['free_bottles_available']}, Required: {$freeBottlesSold}");
                }

                // Calculate pricing and profit
                $totalSalePrice = $casesSold * $sellingPricePerCase;
                $purchaseCost = $purchasedBottlesSold * $purchaseRatePerBottle; // Only purchased bottles have cost
                $profit = $totalSalePrice - $purchaseCost;

                // Create sale item
                $itemsData = [
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'supplier_id' => $request->supplier_id,
                    'variant' => $item['variant'],
                    'cases_sold' => $casesSold,
                    'bottles_per_case' => $bottlesPerCase,
                    'purchased_bottles_sold' => $purchasedBottlesSold,
                    'free_bottles_sold' => $freeBottlesSold,
                    'total_bottles_sold' => $totalBottlesSold,
                    'purchase_unit_price' => $purchaseRatePerBottle,
                    'selling_price_per_case' => $sellingPricePerCase,
                    'unit_price' => $sellingPricePerCase / $bottlesPerCase, // Selling price per bottle
                    'total_price' => $totalSalePrice,
                    'profit' => $profit,
                    'delivery_date' => $request->sale_date,
                    'invoice_number' => $data['invoice_number'],
                    'status' => SalesItemsStatus::IN_PROGRESS->value,
                ];

                $this->salesItemRepository->create($itemsData);

                // Update inventory
                $this->productPurchaseRepository->updateInventory(
                    $product,
                    $item['variant'],
                    $purchasedBottlesSold,
                    $freeBottlesSold
                );

                $totalAmount += $totalSalePrice;
                $totalProfit += $profit;
            }

            // Update sale totals
            $this->salesRepository->updateSales([
                'total_amount' => $totalAmount,
                'subtotal' => $totalAmount,
                'due_amount' => $totalAmount,
            ], $sale->id);

            return Inertia::render('SalesManagement/SalesPayment', [
                'sale' => [
                    'id' => $sale->id,
                    'invoice_number' => $data['invoice_number'],
                    'total_amount' => $totalAmount,
                    'total_profit' => $totalProfit,
                    'paid_amount' => 0,
                    'due_amount' => $totalAmount,
                    'shop_name' => $this->shopRepository->find($data['shop_id'])->shop_name ?? 'Unknown',
                    'advance_balance' => DB::table('payments')->where('shop_id', $data['shop_id'])->sum('advance_balance'),
                ],
                'flash' => [
                    'sale_id' => $sale->id,
                    'success' => 'Sale created successfully, please proceed with payment',
                ],
            ]);
        });
    }

    public function payment(Request $request, $id)
    {
        $sale = $this->salesRepository->find($id);
        if (!$sale) {
            return redirect()->route('sales.index')->with('error', 'Sale not found');
        }

        $shop = $this->shopRepository->find($sale->shop_id);
        $advanceBalance = DB::table('payments')
            ->where('shop_id', $sale->shop_id)
            ->sum('advance_balance');

        return Inertia::render('SalesManagement/SalesPayment', [
            'sale' => [
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'total_amount' => $sale->total_amount,
                'paid_amount' => $sale->paid_amount,
                'due_amount' => $sale->due_amount,
                'shop_name' => $shop ? $shop->shop_name : 'Unknown',
                'advance_balance' => $advanceBalance,
            ],
        ]);
    }

    public function storePayment(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $sale = $this->salesRepository->find($id);

            if (!$sale) {
                return redirect()->route('sales.index')->with('error', 'Sale not found');
            }

            $paymentAmount = $request->payment_amount;
            $currentAdvanceBalance = DB::table('payments')
                ->where('shop_id', $sale->shop_id)
                ->sum('advance_balance');

            $newPaidAmount = $sale->paid_amount + $paymentAmount;
            $newDueAmount = $sale->due_amount - $paymentAmount;

            $data = [
                'paid_amount' => $newPaidAmount,
                'due_amount' => max(0, $newDueAmount),
                'is_paid' => $newDueAmount <= 0,
                'status' => $newDueAmount <= 0 ? SalesStatus::COMPLETED : SalesStatus::IN_PROGRESS,
            ];

            $this->salesRepository->updateSales($data, $id);

            $paymentData = [
                'shop_id' => $sale->shop_id,
                'sale_id' => $sale->id,
                'amount' => $paymentAmount,
                'payment_method' => $request->payment_method,
                'advance_balance' => $currentAdvanceBalance,
                'status' => $newDueAmount <= 0 ? PaymentStatus::PAID : PaymentStatus::PENDING,
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('payments')->insert($paymentData);

            $shop = $this->shopRepository->find($sale->shop_id);

            return Inertia::render('SalesManagement/CashMemo', [
                'sale' => [
                    'id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'total_amount' => $sale->total_amount ?? 0,
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => max(0, $newDueAmount),
                    'shop_name' => $shop ? $shop->shop_name : 'Unknown',
                    'status' => $data['status'],
                ],
                'payment' => $paymentData,
            ]);
        });
    }

    public function cashMemo(Request $request, $id)
    {
        $sale = $this->salesRepository->find($id);
        if (!$sale) {
            return redirect()->route('sales.index')->with('error', 'Sale not found');
        }

        $shop = $this->shopRepository->find($sale->shop_id);
        $latestPayment = DB::table('payments')
            ->where('sale_id', $sale->id)
            ->orderBy('payment_date', 'desc')
            ->first();

        return Inertia::render('SalesManagement/CashMemo', [
            'sale' => [
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'total_amount' => $sale->total_amount ?? 0,
                'paid_amount' => $sale->paid_amount,
                'due_amount' => $sale->due_amount,
                'shop_name' => $shop ? $shop->shop_name : 'Unknown',
                'status' => $sale->status,
            ],
            'payment' => $latestPayment ? [
                'amount' => $latestPayment->amount,
                'payment_method' => $latestPayment->payment_method,
                'advance_balance' => $latestPayment->advance_balance,
                'payment_date' => $latestPayment->payment_date,
                'status' => $latestPayment->status,
            ] : [
                'amount' => 0,
                'payment_method' => '',
                'advance_balance' => 0,
                'payment_date' => now()->toDateTimeString(),
                'status' => PaymentStatus::PENDING,
            ],
        ]);
    }

    public function report(Request $request)
    {
        $query = $this->salesRepository->query()->with(['shop', 'items.product', 'supplier']);

        // Apply filters
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('sale_date', [$request->start_date, $request->end_date]);
        }
        if ($request->shop_id) {
            $query->where('shop_id', $request->shop_id);
        }
        if ($request->product_id) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $sales = $query->get()->map(function ($sale) {
            return [
                'id' => $sale->id,
                'shop_id' => $sale->shop_id,
                'shop_name' => $sale->shop ? $sale->shop->shop_name : 'Unknown',
                'supplier_id' => $sale->supplier_id,
                'supplier_name' => $sale->supplier ? $sale->supplier->company_name : 'Unknown',
                'invoice_number' => $sale->invoice_number,
                'total_amount' => $sale->total_amount,
                'total_profit' => $sale->items->sum('profit'),
                'sale_date' => $sale->sale_date->format('Y-m-d'),
                'status' => $sale->status,
                'items' => $sale->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product ? $item->product->name : 'Unknown',
                        'variant' => $item->variant,
                        'cases_sold' => $item->cases_sold,
                        'total_bottles_sold' => $item->total_bottles_sold,
                        'purchased_bottles_sold' => $item->purchased_bottles_sold,
                        'free_bottles_sold' => $item->free_bottles_sold,
                        'total_price' => $item->total_price,
                        'profit' => $item->profit,
                    ];
                }),
            ];
        });

        $shops = $this->shopRepository->all()->map(function ($shop) {
            return [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name,
            ];
        });

        $products = $this->productPurchaseRepository->all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
            ];
        });

        $suppliers = $this->supplierRepository->all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'company_name' => $supplier->company_name,
            ];
        });

        return Inertia::render('SalesManagement/SalesReport', [
            'sales' => $sales,
            'shops' => $shops,
            'products' => $products,
            'suppliers' => $suppliers,
            'filters' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'shop_id' => $request->shop_id,
                'product_id' => $request->product_id,
                'supplier_id' => $request->supplier_id,
            ],
        ]);
    }
}
