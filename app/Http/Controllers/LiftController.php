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
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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

    public function index(Request $request)
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

        $draft = null;

        if ($request->filled('draft')) {
            $draft = $this->liftRepository->query()
                ->with(['items.productCatalog'])
                ->where('status', 'draft')
                ->find($request->integer('draft'));
        }

        if (!$draft && $request->filled('edit')) {
            $draft = $this->liftRepository->query()
                ->with(['items.productCatalog'])
                ->find($request->integer('edit'));
        }

        return Inertia::render('LiftManagement/Lift', [
            'suppliers' => $suppliers,
            'categories' => $categories,
            'brands' => $brands,
            'draftLift' => $draft,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'draft_id' => 'nullable|exists:lifts,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'lift_date' => 'required|date',
            'deposit_from_here_amount' => 'nullable|numeric|min:0.01',
            'save_as_draft' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_catalog_id' => 'required|exists:product_catalog,id',
            'items.*.product_name' => 'required|string',
            'items.*.variants' => 'required|array|min:1',
            'items.*.variants.*.variant' => 'required|string',
            'items.*.variants.*.number_of_cases' => 'required|numeric|min:0.0001',
            'items.*.variants.*.case_buying_price' => 'required|numeric|min:0.01',
            'items.*.variants.*.bottles_per_case' => 'required|integer|min:1',
            'items.*.variants.*.free_bottles_per_case' => 'required|numeric|min:0',
        ]);

        $saveAsDraft = $request->boolean('save_as_draft');

        $liftData = [
            'supplier_id' => $request->supplier_id,
            'lift_date' => $request->lift_date,
            'notes' => $request->notes,
            'status' => $saveAsDraft ? 'draft' : 'completed',
        ];

        // Keep catalog variants in sync with the variants actually used in lifts.
        collect($request->items)->each(function ($item) {
            $catalog = ProductCatalog::find($item['product_catalog_id']);

            if (!$catalog) {
                return;
            }

            $existingVariants = collect($catalog->default_variants ?? []);
            $incomingVariants = collect($item['variants'] ?? [])->map(function ($variant) {
                return [
                    'variant' => $variant['variant'] ?? '',
                    'bottles_per_case' => (int) ($variant['bottles_per_case'] ?? 0),
                    'free_bottles_per_case' => (float) ($variant['free_bottles_per_case'] ?? 0),
                ];
            });

            $mergedVariants = $existingVariants
                ->concat($incomingVariants)
                ->filter(fn ($variant) => !empty($variant['variant']))
                ->unique('variant')
                ->values()
                ->all();

            if ($mergedVariants !== ($catalog->default_variants ?? [])) {
                $catalog->update([
                    'default_variants' => $mergedVariants,
                ]);
            }
        });

        // Add category_id and brand_id from product_catalog for each item
        $items = collect($request->items)->map(function ($item) {
            $catalog = ProductCatalog::find($item['product_catalog_id']);
            $item['category_id'] = $catalog->category_id;
            $item['brand_id'] = $catalog->brand_id;
            return $item;
        })->toArray();

        DB::transaction(function () use ($request, $liftData, $items, $saveAsDraft) {
            $existingLift = $request->draft_id
                ? $this->liftRepository->query()->with(['items.product'])->find($request->draft_id)
                : null;

            if ($existingLift && $existingLift->status === 'completed') {
                $this->revertCompletedLift($existingLift);
            }

            if (!$saveAsDraft && $request->filled('deposit_from_here_amount')) {
                $depositAmount = round((float) $request->deposit_from_here_amount, 2);

                $this->depositRepository->create([
                    'supplier_id' => $request->supplier_id,
                    'balance_deposited' => $depositAmount,
                    'balance_used' => 0,
                    'balance_remaining' => $depositAmount,
                ]);
            }

            $lift = $this->liftRepository->saveLiftWithItems(
                $liftData,
                $items,
                $request->supplier_id,
                $request->draft_id
            );

            if (!$saveAsDraft) {
                $this->depositRepository->applyAmountAgainstSupplierDeposits(
                    $request->supplier_id,
                    (float) $lift->total_amount
                );
            }
        });

        return redirect()
            ->route($saveAsDraft ? 'lifts.report' : 'lifts.index')
            ->with('success', $saveAsDraft ? 'Lift draft saved successfully' : 'Lift recorded successfully');
    }

    protected function revertCompletedLift($lift): void
    {
        foreach ($lift->items as $item) {
            if (!$item->product_id) {
                continue;
            }

            $product = Product::query()->find($item->product_id);
            if (!$product) {
                continue;
            }

            $variantIndex = collect($product->metadata['variants'] ?? [])->search(function ($variant) use ($item) {
                return ($variant['variant'] ?? null) === $item->variant;
            });

            if ($variantIndex === false) {
                continue;
            }

            $variantData = $product->metadata['variants'][$variantIndex];
            $currentPurchased = (int) ($variantData['current_purchased_quantity'] ?? 0);
            $currentFree = (int) ($variantData['current_free_quantity'] ?? 0);
            $originalPurchased = ((int) $item->number_of_cases) * ((int) $item->bottles_per_case);
            $originalFree = (int) $item->total_free_bottles;

            if ($currentPurchased < $originalPurchased || $currentFree < $originalFree) {
                throw ValidationException::withMessages([
                    'lift' => 'This lift cannot be edited because some bottles from it have already been sold.',
                ]);
            }

            $product->delete();
        }

        $this->depositRepository->creditAmountBackToSupplierDeposits(
            $lift->supplier_id,
            (float) $lift->total_amount
        );
    }

    public function destroy(int $id)
    {
        $lift = $this->liftRepository->query()->with(['items.product'])->find($id);

        if (!$lift) {
            return back()->with('error', 'Lift not found.');
        }

        if ($lift->status === 'completed') {
            // Block delete if any product from this lift has been sold
            foreach ($lift->items as $item) {
                if (!$item->product_id || !$item->product) continue;

                $variantIndex = collect($item->product->metadata['variants'] ?? [])->search(
                    fn($v) => ($v['variant'] ?? null) === $item->variant
                );

                if ($variantIndex === false) continue;

                $variantData = $item->product->metadata['variants'][$variantIndex];
                $currentPurchased = (int) ($variantData['current_purchased_quantity'] ?? 0);
                $currentFree     = (int) ($variantData['current_free_quantity'] ?? 0);
                $originalPurchased = ((int) $item->number_of_cases) * ((int) $item->bottles_per_case);
                $originalFree    = (int) $item->total_free_bottles;

                if ($currentPurchased < $originalPurchased || $currentFree < $originalFree) {
                    return back()->with('error', 'This lift cannot be deleted because some products from it have already been sold.');
                }
            }

            // Safe to delete — soft-delete products and credit deposit back
            foreach ($lift->items as $item) {
                if ($item->product) {
                    $item->product->delete();
                }
            }

            $this->depositRepository->creditAmountBackToSupplierDeposits(
                $lift->supplier_id,
                (float) $lift->total_amount
            );
        }

        $this->liftRepository->delete($id);
        return back()->with('success', 'Lift deleted successfully.');
    }

    public function report(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->toDateString());

        return Inertia::render('LiftManagement/LiftReport', [
            'liftHistory' => $this->liftRepository->liftHistory(),
            'initialStartDate' => $startDate,
            'initialEndDate' => $endDate,
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
                'image_url' => $product->image_path ? '/storage/' . ltrim($product->image_path, '/') : null,
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
            'product_image' => 'nullable|image|max:2048',
        ]);

        $product = ProductCatalog::query()->firstOrNew([
            'name' => trim((string) $request->name),
            'supplier_id' => $request->supplier_id,
        ]);

        $product->fill([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'default_variants' => $request->default_variants ?? [],
            'is_active' => true,
        ]);

        if ($request->hasFile('product_image')) {
            if ($product->exists && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $product->image_path = $request->file('product_image')->store('product-images', 'public');
        }

        $product->save();

        $product->load(['category', 'brand']);

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'image_url' => $product->image_path ? '/storage/' . ltrim($product->image_path, '/') : null,
                'category_id' => $product->category_id,
                'category_name' => $product->category?->name ?? '',
                'brand_id' => $product->brand_id,
                'brand_name' => $product->brand?->brand_name ?? '',
                'default_variants' => $product->default_variants ?? [],
            ],
        ]);
    }
}
