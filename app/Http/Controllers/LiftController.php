<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\LiftContract;
use App\Contracts\ProductCatalogContract;
use App\Contracts\DepositContract;
use App\Contracts\SupplierContract;
use App\Contracts\CategoryContract;
use App\Contracts\BrandContract;
use App\Models\ProductCatalog;
use Inertia\Inertia;

class LiftController extends Controller
{
    public function __construct(
        protected LiftContract $liftRepository,
        protected ProductCatalogContract $productCatalogRepository,
        protected DepositContract $depositRepository,
        protected SupplierContract $supplierRepository,
        protected CategoryContract $categoryRepository,
        protected BrandContract $brandRepository,
    ) {}

    public function index()
    {
        $suppliers = $this->supplierRepository->all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'company_name' => $supplier->company_name,
            ];
        });

        $remainingDeposits = $this->depositRepository->totalRemainingDepositsBySupplier()
            ->pluck('total_remaining_deposit', 'supplier_name')
            ->toArray();

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
            return ['id' => $category->id, 'name' => $category->name];
        });

        $brands = $this->brandRepository->all()->map(function ($brand) {
            return ['id' => $brand->id, 'brand_name' => $brand->brand_name];
        });

        return Inertia::render('LiftManagement/Lift', [
            'suppliers' => $suppliers,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'lift_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_catalog_id' => 'required|exists:product_catalog,id',
            'items.*.product_name' => 'required|string',
            'items.*.variants' => 'required|array|min:1',
            'items.*.variants.*.variant' => 'required|string',
            'items.*.variants.*.number_of_cases' => 'required|integer|min:1',
            'items.*.variants.*.case_buying_price' => 'required|numeric|min:0.01',
            'items.*.variants.*.bottles_per_case' => 'required|integer|min:1',
            'items.*.variants.*.free_bottles_per_case' => 'required|integer|min:0',
        ]);

        $liftData = [
            'supplier_id' => $request->supplier_id,
            'lift_date' => $request->lift_date,
            'notes' => $request->notes,
        ];

        // Add category_id and brand_id from product_catalog for each item
        $items = collect($request->items)->map(function ($item) {
            $catalog = ProductCatalog::find($item['product_catalog_id']);
            $item['category_id'] = $catalog->category_id;
            $item['brand_id'] = $catalog->brand_id;
            return $item;
        })->toArray();

        $lift = $this->liftRepository->createLiftWithItems($liftData, $items, $request->supplier_id);

        // Deduct from deposit
        $latestDeposit = $this->depositRepository->findTheLatestDepositForTheSupplier($request->supplier_id);
        if ($latestDeposit) {
            $this->depositRepository->updateDepositTable($lift->total_amount, $latestDeposit);
        }

        return redirect()->route('lifts.index')->with('success', 'Lift recorded successfully');
    }

    public function report()
    {
        return Inertia::render('LiftManagement/LiftReport', [
            'liftHistory' => $this->liftRepository->liftHistory(),
        ]);
    }

    public function searchProducts(Request $request)
    {
        $supplierId = $request->query('supplier_id');
        $search = $request->query('q', '');

        if (!$supplierId) {
            return response()->json([]);
        }

        $products = $this->productCatalogRepository->searchBySupplier((int) $supplierId, $search);

        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'category_name' => $product->category?->name ?? '',
                'brand_id' => $product->brand_id,
                'brand_name' => $product->brand?->brand_name ?? '',
                'default_variants' => $product->default_variants ?? [],
            ];
        }));
    }

    public function quickStoreProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'default_variants' => 'nullable|array',
        ]);

        $product = $this->productCatalogRepository->create([
            'name' => $request->name,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'default_variants' => $request->default_variants ?? [],
        ]);

        $product->load(['category', 'brand']);

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'category_name' => $product->category?->name ?? '',
                'brand_id' => $product->brand_id,
                'brand_name' => $product->brand?->brand_name ?? '',
                'default_variants' => $product->default_variants ?? [],
            ],
        ]);
    }
}
