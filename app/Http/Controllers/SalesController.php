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
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

    public function searchInventoryProducts(Request $request)
    {
        $query = $request->get('q', '');
        $products = $this->productPurchaseRepository->getInventoryStock();

        if ($query) {
            $products = $products->filter(function ($product) use ($query) {
                return stripos($product['product_name'], $query) !== false;
            });
        }

        return response()->json($products->take(15)->values());
    }

    public function getProductsBySupplier(Request $request)
    {
        $supplierId = $request->get('supplier_id');

        if (!$supplierId) {
            return response()->json(['products' => []]);
        }

        $products = $this->productPurchaseRepository->getProductsBySupplier($supplierId);

        return response()->json(['products' => $products->toArray()]);
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
            'items.*.total_bottles_to_sell' => 'required|integer|min:1',
            'items.*.selling_price_per_bottle' => 'required|numeric|min:0',
            'items.*.free_bottles_per_case' => 'nullable|integer|min:0',
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
                // Use reference product to get name + supplier for sibling lookup
                $referenceProduct = $this->productPurchaseRepository->find($item['product_id']);

                // Collect ALL purchase records (batches) for this product name + supplier
                // that contain this variant, ordered oldest-first (FIFO)
                $batches = Product::where('name', $referenceProduct->name)
                    ->where('supplier_id', $referenceProduct->supplier_id)
                    ->whereNotNull('metadata')
                    ->orderBy('date', 'asc')
                    ->get()
                    ->map(function ($p) use ($item) {
                        $variantData = collect($p->metadata['variants'] ?? [])->firstWhere('variant', $item['variant']);
                        if (!$variantData) return null;
                        $purchasedCases = $variantData['cases_without_free_bottles'] ?? 0;
                        $bpc = $variantData['bottles_per_case'] ?? 0;
                        $initialPurchased = $purchasedCases * $bpc;
                        $initialFree = $variantData['total_free_bottles'] ?? 0;
                        return [
                            'product'       => $p,
                            'purchased'     => $variantData['current_purchased_quantity'] ?? $initialPurchased,
                            'free'          => $variantData['current_free_quantity'] ?? $initialFree,
                            'bottles_per_case' => $bpc,
                            'purchase_rate' => floatval($variantData['actual_rate_per_bottle'] ?? 0),
                        ];
                    })
                    ->filter()
                    ->values();

                if ($batches->isEmpty()) {
                    throw ValidationException::withMessages([
                        'inventory' => "Variant {$item['variant']} not found for this product",
                    ]);
                }

                $bottlesPerCase        = $batches->first()['bottles_per_case'];
                $purchaseRatePerBottle = $batches->avg('purchase_rate');
                $totalPurchasedAvailable = $batches->sum('purchased');
                $totalFreeAvailable      = $batches->sum('free');

                $targetBottlesToSell       = $item['total_bottles_to_sell'];
                $actualSellingPricePerBottle = $item['selling_price_per_bottle'];
                $freeBottlesPerCase        = $item['free_bottles_per_case'] ?? 0;

                if ($request->include_free_bottles) {
                    $effectiveBottlesPerCase = $bottlesPerCase + $freeBottlesPerCase;
                    $casesSold               = ceil($targetBottlesToSell / $effectiveBottlesPerCase);
                    $purchasedBottlesSold    = $casesSold * $bottlesPerCase;
                    $freeBottlesSold         = $casesSold * $freeBottlesPerCase;
                    $actualTotalBottlesSold  = $purchasedBottlesSold + $freeBottlesSold;
                    $sellingPricePerCase     = $effectiveBottlesPerCase * $actualSellingPricePerBottle;
                } else {
                    $casesSold               = ceil($targetBottlesToSell / $bottlesPerCase);
                    $purchasedBottlesSold    = $targetBottlesToSell;
                    $freeBottlesSold         = 0;
                    $actualTotalBottlesSold  = $purchasedBottlesSold;
                    $sellingPricePerCase     = $bottlesPerCase * $actualSellingPricePerBottle;
                }

                // Validate against total stock (purchased + free combined)
                $totalAvailable  = $totalPurchasedAvailable + $totalFreeAvailable;
                $totalToDeduct   = $purchasedBottlesSold + $freeBottlesSold;

                if ($totalToDeduct > $totalAvailable) {
                    throw ValidationException::withMessages([
                        'inventory' => "Insufficient stock for {$item['variant']}. Available: {$totalAvailable}, Required: {$totalToDeduct}",
                    ]);
                }

                $totalSalePrice = $targetBottlesToSell * $actualSellingPricePerBottle;
                $purchaseCost   = $actualTotalBottlesSold * $purchaseRatePerBottle;
                $profit         = $totalSalePrice - $purchaseCost;

                $itemsData = [
                    'sale_id'                => $sale->id,
                    'product_id'             => $item['product_id'],
                    'supplier_id'            => $request->supplier_id,
                    'variant'                => $item['variant'],
                    'cases_sold'             => $casesSold,
                    'bottles_per_case'       => $bottlesPerCase,
                    'purchased_bottles_sold' => $purchasedBottlesSold,
                    'free_bottles_sold'      => $freeBottlesSold,
                    'total_bottles_sold'     => $actualTotalBottlesSold,
                    'target_bottles_to_sell' => $targetBottlesToSell,
                    'purchase_unit_price'    => $purchaseRatePerBottle,
                    'selling_price_per_case' => $sellingPricePerCase,
                    'selling_price_per_bottle' => $actualSellingPricePerBottle,
                    'unit_price'             => $actualSellingPricePerBottle,
                    'total_price'            => $totalSalePrice,
                    'profit'                 => $profit,
                    'delivery_date'          => $request->sale_date,
                    'invoice_number'         => $data['invoice_number'],
                    'status'                 => SalesItemsStatus::IN_PROGRESS->value,
                ];

                $this->salesItemRepository->create($itemsData);

                // FIFO deduction: treat purchased+free as one pool, deplete purchased first per batch
                $remainingToDeduct = (int) $totalToDeduct;
                foreach ($batches as $batch) {
                    if ($remainingToDeduct <= 0) break;
                    $batchTotal = (int) $batch['purchased'] + (int) $batch['free'];
                    if ($batchTotal <= 0) continue;

                    $deductFromBatch = min($remainingToDeduct, $batchTotal);
                    // Within this batch: use up purchased first, then free
                    $deductPurchased = min($deductFromBatch, (int) $batch['purchased']);
                    $deductFree      = $deductFromBatch - $deductPurchased;

                    $this->productPurchaseRepository->updateInventory(
                        $batch['product'],
                        $item['variant'],
                        $deductPurchased,
                        $deductFree
                    );
                    $remainingToDeduct -= $deductFromBatch;
                }

                $totalAmount += $totalSalePrice;
                $totalProfit += $profit;
            }

            // Update sale totals
            $this->salesRepository->updateSales([
                'total_amount' => $totalAmount,
                'subtotal' => $totalAmount,
                'due_amount' => $totalAmount,
            ], $sale->id);

            return redirect()->route('sales.payment', $sale->id)->with('success', 'Sale created successfully, please proceed with payment');
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
        if ($request->id) {
            $query->where('id', $request->id);
        }
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
                        'target_bottles_to_sell' => $item->target_bottles_to_sell ?? $item->total_bottles_sold,
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
                'id' => $request->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'shop_id' => $request->shop_id,
                'product_id' => $request->product_id,
                'supplier_id' => $request->supplier_id,
            ],
        ]);
    }
}
