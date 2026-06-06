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
use App\Models\SaleItem;
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

            // A genuine new deposit the user is adding alongside this lift (covers
            // any shortfall when the lifted value grows beyond available balance).
            if (!$saveAsDraft && $request->filled('deposit_from_here_amount')) {
                $depositAmount = round((float) $request->deposit_from_here_amount, 2);

                $this->depositRepository->create([
                    'supplier_id' => $request->supplier_id,
                    'balance_deposited' => $depositAmount,
                    'balance_used' => 0,
                    'balance_remaining' => $depositAmount,
                ]);
            }

            if ($existingLift && $existingLift->status === 'completed') {
                // Editing a completed lift: reconcile IN PLACE so already-sold
                // product batches keep their id (sale_items stay linked, running
                // stock stays correct). A variant may not be reduced below what
                // has already been sold from it.
                $soldByProduct = $this->soldBottlesByProduct($existingLift);
                $this->validateLiftEditAgainstSales($existingLift, $items, $soldByProduct);

                $oldTotal = round((float) $existingLift->total_amount, 2);
                $lift = $this->liftRepository->reconcileCompletedLiftItems(
                    $existingLift,
                    $items,
                    $liftData,
                    $soldByProduct
                );
                $newTotal = round((float) $lift->total_amount, 2);

                // Reconcile the supplier deposit by the net change in lifted value.
                $targetDraw = $saveAsDraft ? 0.0 : $newTotal;
                $delta = round($targetDraw - $oldTotal, 2);
                if ($delta > 0) {
                    $this->depositRepository->applyAmountAgainstSupplierDeposits((int) $request->supplier_id, $delta);
                } elseif ($delta < 0) {
                    $this->depositRepository->creditAmountBackToSupplierDeposits((int) $request->supplier_id, -$delta);
                }
            } else {
                // New lift, or editing a DRAFT (no products yet): safe to (re)create.
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
            }
        });

        return redirect()
            ->route($saveAsDraft ? 'lifts.report' : 'lifts.index')
            ->with('success', $saveAsDraft ? 'Lift draft saved successfully' : 'Lift recorded successfully');
    }

    /**
     * Map of product_id => ['purchased' => int, 'free' => int] of bottles already
     * sold (non-draft) for every batch in a lift.
     */
    protected function soldBottlesByProduct($lift): array
    {
        $map = [];
        foreach ($lift->items as $item) {
            if (!$item->product_id) {
                continue;
            }
            $map[$item->product_id] = $this->soldBottlesForLiftItem($item);
        }
        return $map;
    }

    /**
     * Bottles already sold (non-draft sales) for a single lift item's batch+variant.
     */
    protected function soldBottlesForLiftItem($item): array
    {
        if (!$item->product_id) {
            return ['purchased' => 0, 'free' => 0];
        }

        $row = SaleItem::where('product_id', $item->product_id)
            ->where('variant', $item->variant)
            ->whereHas('sale', fn ($q) => $q->where('status', '!=', 'draft'))
            ->selectRaw('COALESCE(SUM(purchased_bottles_sold), 0) as purchased, COALESCE(SUM(free_bottles_sold), 0) as free')
            ->first();

        return [
            'purchased' => (int) ($row->purchased ?? 0),
            'free' => (int) ($row->free ?? 0),
        ];
    }

    /**
     * Enforce the edit rule: a variant may not be reduced below the quantity
     * already sold from it, and a sold variant may not be removed. Rejects the
     * whole update (no partial save) with a message naming the offending variant.
     */
    protected function validateLiftEditAgainstSales($existingLift, array $items, array $soldByProduct): void
    {
        $incoming = [];
        foreach ($items as $item) {
            foreach ($item['variants'] as $variant) {
                $incoming[$item['product_catalog_id'] . '::' . $variant['variant']] = $variant;
            }
        }

        foreach ($existingLift->items as $item) {
            $sold = $soldByProduct[$item->product_id] ?? ['purchased' => 0, 'free' => 0];
            $soldPurchased = (int) $sold['purchased'];
            $soldFree = (int) $sold['free'];

            if ($soldPurchased <= 0 && $soldFree <= 0) {
                continue; // nothing sold from this batch — any edit is allowed
            }

            $key = $item->product_catalog_id . '::' . $item->variant;

            if (!isset($incoming[$key])) {
                throw ValidationException::withMessages([
                    'lift' => "Cannot remove variant {$item->variant}: {$soldPurchased} bottle(s) have already been sold from it.",
                ]);
            }

            $variant = $incoming[$key];
            $cases = (float) ($variant['number_of_cases'] ?? 0);
            $bottlesPerCase = (float) ($variant['bottles_per_case'] ?? 0);
            $freeBottlesPerCase = (float) ($variant['free_bottles_per_case'] ?? 0);
            $newPurchased = (int) round($cases * $bottlesPerCase);
            $newFree = (int) round($cases * $freeBottlesPerCase);

            if ($newPurchased < $soldPurchased) {
                throw ValidationException::withMessages([
                    'lift' => "Variant {$item->variant}: you entered {$newPurchased} bottle(s), but {$soldPurchased} have already been sold. A lift can't go below what's already sold.",
                ]);
            }

            if ($newFree < $soldFree) {
                throw ValidationException::withMessages([
                    'lift' => "Variant {$item->variant}: you entered {$newFree} free bottle(s), but {$soldFree} free bottle(s) have already been sold.",
                ]);
            }
        }
    }

    /**
     * Whether a lift item's product batch has any real (non-draft) sales.
     * This is the authoritative "has been sold" check — it queries the
     * sale_items table directly instead of inferring from the running stock
     * balance (current_purchased_quantity), which can also be lowered by
     * manual inventory adjustments that are NOT sales.
     */
    protected function liftItemHasSales($item): bool
    {
        if (!$item->product_id) {
            return false;
        }

        return SaleItem::where('product_id', $item->product_id)
            ->where('variant', $item->variant)
            ->whereHas('sale', fn ($q) => $q->where('status', '!=', 'draft'))
            ->where(function ($q) {
                $q->where('purchased_bottles_sold', '>', 0)
                    ->orWhere('free_bottles_sold', '>', 0)
                    ->orWhere('total_bottles_sold', '>', 0);
            })
            ->exists();
    }

    public function destroy(int $id)
    {
        $lift = $this->liftRepository->query()->with(['items.product'])->find($id);

        if (!$lift) {
            return back()->with('error', 'Lift not found.');
        }

        if ($lift->status === 'completed') {
            // Block delete only if real (non-draft) sales exist for these batches.
            foreach ($lift->items as $item) {
                if ($this->liftItemHasSales($item)) {
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
