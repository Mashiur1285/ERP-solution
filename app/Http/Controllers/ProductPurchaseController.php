<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ProductPurchaseContract;
use App\Contracts\CategoryContract;
use App\Contracts\BrandContract;
use App\Contracts\DepositContract;
use App\Contracts\SupplierContract;
use App\Http\Requests\storeProductPurchaseRequest;
use Inertia\Inertia;
use App\Models\Supplier;
use App\Models\ProductCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductPurchaseController extends Controller
{
    public function __construct(
        protected ProductPurchaseContract $productPurchaseRepository,
        protected DepositContract $depositRepository,
        protected SupplierContract $supplierRepository,
        protected CategoryContract $categoryRepository,
        protected BrandContract $brandRepository
    ) {
    }

    public function index()
    {
        // Fetch suppliers with their remaining deposits
        $suppliers = $this->supplierRepository->all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'company_name' => $supplier->company_name,
            ];
        });

        // Fetch total remaining deposits per supplier
        $remainingDeposits = $this->depositRepository->totalRemainingDepositsBySupplier()
            ->pluck('total_remaining_deposit', 'supplier_name')
            ->toArray();

        // Merge remaining deposit into suppliers array
        $suppliers = $suppliers->map(function ($supplier) use ($remainingDeposits) {
            return [
                'id' => $supplier['id'],
                'company_name' => $supplier['company_name'],
                'remaining_deposit' => isset($remainingDeposits[$supplier['company_name']])
                    ? round($remainingDeposits[$supplier['company_name']], 2)
                    : 0.00,
            ];
        });

        $categories = $this->categoryRepository->all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });

        // Fetch brands
        $brands = $this->brandRepository->all()->map(function ($brand) {
            return [
                'id' => $brand->id,
                'brand_name' => $brand->brand_name, // Map name to brand_name for frontend
            ];
        });

        return Inertia::render('DepositManagement/Purchase', [
            'suppliers' => $suppliers,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function storeProductPurchase(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'variants' => 'required|array|min:1',
            'product_image' => 'nullable|image|max:2048',
        ]);

        $productName = trim((string) $request->product_name);
        $imagePath = $request->hasFile('product_image')
            ? $request->file('product_image')->store('product-images', 'public')
            : null;

        $productCatalog = ProductCatalog::query()->firstOrNew([
            'name' => $productName,
            'supplier_id' => $request->supplier_id,
        ]);

        $productCatalog->fill([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'default_variants' => collect($request->variants)
                ->map(function ($variant) {
                    return [
                        'variant' => $variant['variant'] ?? '',
                        'bottles_per_case' => (int) ($variant['bottles_per_case'] ?? 0),
                    ];
                })
                ->filter(fn ($variant) => $variant['variant'] !== '')
                ->unique('variant')
                ->values()
                ->all(),
            'is_active' => true,
        ]);

        if ($imagePath) {
            if ($productCatalog->exists && $productCatalog->image_path) {
                Storage::disk('public')->delete($productCatalog->image_path);
            }

            $productCatalog->image_path = $imagePath;
        }

        $productCatalog->save();

        $data = [
            'name' => $productName,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'product_catalog_id' => $productCatalog->id,
            'metadata' => $request->variants,
            'date' => now(),
        ];

        $totalAmountForAllVariants = collect($data['metadata'])->sum('total_cost');

        //format the metadata to variants for the jsonb format
        $data['metadata'] = ['variants' => $data['metadata']];

        $this->productPurchaseRepository->create($data);

        $latestDeposit = $this->depositRepository->findTheLatestDepositForTheSupplier($data['supplier_id']);
        if ($latestDeposit) {

            $this->depositRepository->updateDepositTable($totalAmountForAllVariants, $latestDeposit);
        }
        return redirect()->route('purchases.index')->with('success', 'Purchase added successfully');
    }

    public function purchaseReport()
    {
        // dd($this->productPurchaseRepository->purchaseHistory());
        return Inertia::render('DepositManagement/PurchaseReport', [
            'purchaseHistory' => $this->productPurchaseRepository->purchaseHistory(),
        ]);
    }

    public function inventoryReport(Request $request)
    {
        $snapshotDate = $request->input('snapshot_date', now()->toDateString());

        return Inertia::render('InventoryManagement/InventoryReport', [
            'inventoryStock' => $this->productPurchaseRepository->getInventoryStock($snapshotDate),
            'snapshotDate' => $snapshotDate,
        ]);
    }

    public function productList(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $categories = $this->categoryRepository->all()->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
        ])->values();

        $brands = $this->brandRepository->all()->map(fn ($brand) => [
            'id' => $brand->id,
            'brand_name' => $brand->brand_name,
        ])->values();

        $products = ProductCatalog::query()
            ->with(['supplier', 'category', 'brand', 'products:id,product_catalog_id,metadata'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('name', 'ilike', '%' . $search . '%')
                        ->orWhereHas('supplier', fn ($q) => $q->where('company_name', 'ilike', '%' . $search . '%'))
                        ->orWhereHas('category', fn ($q) => $q->where('name', 'ilike', '%' . $search . '%'))
                        ->orWhereHas('brand', fn ($q) => $q->where('brand_name', 'ilike', '%' . $search . '%'));
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $productIds = $products->getCollection()->pluck('id')->all();

        $purchaseAggregates = collect();
        $inventoryAggregates = collect();

        if (!empty($productIds)) {
            $purchaseAggregates = DB::table('products')
                ->select(
                    'product_catalog_id',
                    DB::raw('COUNT(*) as purchase_batches_count'),
                    DB::raw("
                        SUM(
                            COALESCE(
                                (
                                    SELECT SUM(COALESCE((variant->>'total_cost')::numeric, 0))
                                    FROM jsonb_array_elements(COALESCE(products.metadata->'variants', '[]'::jsonb)) AS variant
                                ),
                                0
                            )
                        ) as total_purchase_amount
                    ")
                )
                ->whereNull('deleted_at')
                ->whereNotNull('product_catalog_id')
                ->whereIn('product_catalog_id', $productIds)
                ->groupBy('product_catalog_id')
                ->get()
                ->keyBy('product_catalog_id');

            $idsList = implode(',', array_map('intval', $productIds));

            $inventoryAggregates = collect(DB::select("
                SELECT
                    p.product_catalog_id,
                    SUM(
                        GREATEST(
                            0,
                            (
                                (COALESCE((variant_data->>'cases_without_free_bottles')::int, 0) * COALESCE((variant_data->>'bottles_per_case')::int, 0))
                                - COALESCE(sa.sold_purchased_bottles, 0)
                            ) + (
                                COALESCE((variant_data->>'total_free_bottles')::int, 0)
                                - COALESCE(sa.sold_free_bottles, 0)
                            )
                        )
                    ) as total_available_bottles,
                    SUM(
                        CASE
                            WHEN COALESCE((variant_data->>'bottles_per_case')::int, 0) > 0 THEN
                                FLOOR(
                                    GREATEST(
                                        0,
                                        (
                                            (COALESCE((variant_data->>'cases_without_free_bottles')::int, 0) * COALESCE((variant_data->>'bottles_per_case')::int, 0))
                                            - COALESCE(sa.sold_purchased_bottles, 0)
                                        ) + (
                                            COALESCE((variant_data->>'total_free_bottles')::int, 0)
                                            - COALESCE(sa.sold_free_bottles, 0)
                                        )
                                    ) / COALESCE(NULLIF((variant_data->>'bottles_per_case')::int, 0), 1)
                                )
                            ELSE 0
                        END
                    ) as total_available_cases
                FROM products p
                LEFT JOIN LATERAL jsonb_array_elements(COALESCE(p.metadata->'variants', '[]'::jsonb)) AS variant_data ON true
                LEFT JOIN (
                    SELECT
                        sale_items.product_id,
                        sale_items.variant,
                        SUM(sale_items.purchased_bottles_sold) as sold_purchased_bottles,
                        SUM(sale_items.free_bottles_sold) as sold_free_bottles
                    FROM sale_items
                    INNER JOIN sales ON sale_items.sale_id = sales.id
                    WHERE sales.status != 'draft'
                    GROUP BY sale_items.product_id, sale_items.variant
                ) sa ON sa.product_id = p.id AND sa.variant = COALESCE(variant_data->>'variant', 'N/A')
                WHERE p.deleted_at IS NULL
                    AND p.product_catalog_id IN ($idsList)
                GROUP BY p.product_catalog_id
            "))->keyBy('product_catalog_id');
        }

        $products->setCollection($products->getCollection()->map(function ($product) use ($purchaseAggregates, $inventoryAggregates) {
            $purchaseSummary = $purchaseAggregates->get($product->id);
            $inventorySummary = $inventoryAggregates->get($product->id);
            $catalogVariants = collect($product->default_variants ?? []);
            $purchaseVariants = $product->products
                ->flatMap(function ($purchase) {
                    return collect(data_get($purchase->metadata, 'variants', []));
                })
                ->map(function ($variant) {
                    return [
                        'variant' => $variant['variant'] ?? '',
                        'bottles_per_case' => (int) ($variant['bottles_per_case'] ?? 0),
                        'free_bottles_per_case' => (float) ($variant['free_bottles_per_case'] ?? 0),
                        'total_purchase_amount' => round((float) ($variant['total_cost'] ?? 0), 2),
                    ];
                });

            $mergedVariants = $catalogVariants
                ->concat($purchaseVariants)
                ->filter(fn ($variant) => !empty($variant['variant']))
                ->groupBy('variant')
                ->map(function ($variants, $variantName) {
                    $first = $variants->first();

                    return [
                        'variant' => $variantName,
                        'bottles_per_case' => (int) ($first['bottles_per_case'] ?? 0),
                        'free_bottles_per_case' => (float) ($first['free_bottles_per_case'] ?? 0),
                        'total_purchase_amount' => round((float) $variants->sum(function ($variant) {
                            return (float) ($variant['total_purchase_amount'] ?? 0);
                        }), 2),
                    ];
                })
                ->values()
                ->all();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->image_path ? '/storage/' . ltrim($product->image_path, '/') : null,
                'supplier_name' => $product->supplier?->company_name ?? '-',
                'category_id' => $product->category_id,
                'category_name' => $product->category?->name ?? '-',
                'brand_id' => $product->brand_id,
                'brand_name' => $product->brand?->brand_name ?? '-',
                'variant_count' => count($mergedVariants),
                'default_variants' => $mergedVariants,
                'purchase_batches_count' => (int) ($purchaseSummary->purchase_batches_count ?? 0),
                'total_purchase_amount' => round((float) ($purchaseSummary->total_purchase_amount ?? 0), 2),
                'stock_cases' => (int) ($inventorySummary->total_available_cases ?? 0),
                'stock_bottles' => (int) ($inventorySummary->total_available_bottles ?? 0),
                'is_active' => (bool) $product->is_active,
                'created_at' => optional($product->created_at)?->toDateString(),
            ];
        }));

        return Inertia::render('InventoryManagement/ProductList', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function updateProductCatalog(Request $request, int $id)
    {
        $product = ProductCatalog::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'required|boolean',
            'product_image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        $product->fill([
            'name' => trim((string) $validated['name']),
            'category_id' => $validated['category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'is_active' => (bool) $validated['is_active'],
        ]);

        if ($request->boolean('remove_image') && $product->image_path) {
            Storage::disk('public')->delete($product->image_path);
            $product->image_path = null;
        }

        if ($request->hasFile('product_image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->image_path = $request->file('product_image')->store('product-images', 'public');
        }

        $product->save();

        return redirect()
            ->route('products.index', $request->only('search', 'page'))
            ->with('success', 'Product catalog updated successfully');
    }
}
