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

        $products = $this->productPurchaseRepository->all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'supplier_id' => $product->supplier_id,
                'metadata' => $product->metadata,
            ];
        });

        $suppliers = $this->supplierRepository->all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'company_name' => $supplier->company_name,
            ];
        });

        $categories = $this->categoryRepository->all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });

        $brands = $this->brandRepository->all()->map(function ($brand) {
            return [
                'id' => $brand->id,
                'brand_name' => $brand->brand_name,
            ];
        });

        return Inertia::render('SalesManagement/CreateSale', [
            'shops' => $shops,
            'products' => $products,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store(StoreSalesRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = [
                'shop_id' => $request->shop_id,
                'supplier_id' => $request->supplier_id ?? null,
                'invoice_number' => 'INV-' . Str::padLeft(mt_rand(1, 999999), 6, '0'),
                'total_amount' => collect($request->items)->sum('total_price'),
                'subtotal' => collect($request->items)->sum('total_price'),
                'status' => SalesStatus::IN_PROGRESS->value,
                'sale_date' => $request->sale_date,
            ];

            $sale = $this->salesRepository->create($data);

            foreach ($request->items as $item) {
                $product = $this->productPurchaseRepository->find($item['product_id']);
                $variantData = collect($product->metadata['variants'])->firstWhere('variant', $item['variant']);

                if (!$variantData || $item['total_quantity'] > $variantData['quantity']) {
                    throw new \Exception('Insufficient inventory for ' . $item['variant']);
                }

                $itemsData = [
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'supplier_id' => $item['supplier_id'],
                    'variant' => $item['variant'],
                    'boxes_sold' => $item['boxes_sold'],
                    'bottles_per_box' => $item['bottles_per_box'],
                    'quantity' => $item['total_quantity'],
                    'free_bottles' => $item['free_bottles'],
                    'purchase_unit_price' => $item['unit_price'],
                    'unit_price' => $item['new_unit_price'],
                    'total_price' => $item['total_price'],
                    'profit' => $item['profit'],
                    'delivery_date' => $request->sale_date,
                    'invoice_number' => $data['invoice_number'],
                    'status' => SalesItemsStatus::IN_PROGRESS->value,
                ];

                $this->salesItemRepository->create($itemsData);
                $this->productPurchaseRepository->updateInventory($product, $item['variant'], $item['total_quantity']);
            }

            return Inertia::render('SalesManagement/SalesPayment', [
                'sale' => [
                    'id' => $sale->id,
                    'invoice_number' => $data['invoice_number'],
                    'total_amount' => $data['total_amount'],
                    'paid_amount' => 0,
                    'due_amount' => $data['total_amount'],
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
            $newDueAmount = abs($sale->due_amount - $request->due_amount);
            $data = [
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                'is_paid' => $newDueAmount <= 0,
                'status' => $newDueAmount <= 0 ? SalesStatus::COMPLETED : SalesStatus::IN_PROGRESS,
            ];

            $this->salesRepository->updateSales($data, $id);

            DB::table('payments')->insert([
                'shop_id' => $sale->shop_id,
                'sale_id' => $sale->id,
                'amount' => $paymentAmount,
                'payment_method' => $request->payment_method,
                'advance_balance' => $currentAdvanceBalance,
                'status' => $newDueAmount <= 0 ? PaymentStatus::PAID : PaymentStatus::PENDING,
                'payment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $shop = $this->shopRepository->find($sale->shop_id);

            return Inertia::render('SalesManagement/CashMemo', [
                'sale' => [
                    'id' => $sale->id,
                    'invoice_number' => $sale->invoice_number,
                    'total_amount' => $sale->total_amount ?? 0,
                    'paid_amount' => $newPaidAmount,
                    'due_amount' => $newDueAmount,
                    'shop_name' => $shop ? $shop->shop_name : 'Unknown',
                ],
                'payment' => [
                    'amount' => $paymentAmount,
                    'payment_method' => $request->payment_method,
                    'advance_balance' => $currentAdvanceBalance,
                    'payment_date' => now()->toDateTimeString(),
                ],
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
        $advanceBalance = DB::table('payments')
            ->where('shop_id', $sale->shop_id)
            ->sum('advance_balance');

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
            ],
            'payment' => [
                'amount' => $latestPayment ? $latestPayment->amount : 0,
                'payment_method' => $latestPayment ? $latestPayment->payment_method : '',
                'advance_balance' => $advanceBalance,
                'payment_date' => $latestPayment ? $latestPayment->payment_date : now()->toDateTimeString(),
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
                        'quantity' => $item->quantity,
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
