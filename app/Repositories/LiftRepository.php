<?php

namespace App\Repositories;

use App\Models\Lift;
use App\Models\LiftItem;
use App\Models\Product;
use App\Contracts\LiftContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LiftRepository extends BaseRepository implements LiftContract
{
    public function __construct(Lift $model)
    {
        parent::__construct($model);
    }

    public function saveLiftWithItems(array $liftData, array $items, int $supplierId, ?int $liftId = null): Model
    {
        return DB::transaction(function () use ($liftData, $items, $supplierId, $liftId) {
            $isDraft = ($liftData['status'] ?? 'completed') === 'draft';

            if ($liftId) {
                $lift = Lift::query()->findOrFail($liftId);
                $lift->update($liftData);
                $lift->items()->delete();
            } else {
                $lastLift = Lift::orderBy('id', 'desc')->first();
                $nextId = $lastLift ? $lastLift->id + 1 : 1;
                $liftData['lift_number'] = 'LFT-' . Str::padLeft($nextId, 6, '0');
                $lift = Lift::create($liftData);
            }

            $totalAmount = 0;

            foreach ($items as $item) {
                foreach ($item['variants'] as $variant) {
                    $m = $this->computeVariantMetrics($variant);

                    $product = null;
                    if (!$isDraft) {
                        // Fresh batch: full stock available, full purchase value.
                        $product = $this->createProductBatch($item, $variant['variant'], $m, $liftData);
                    }

                    LiftItem::create($this->buildLiftItemAttrs($lift->id, $item, $variant['variant'], $m, $product?->id));

                    $totalAmount += $m['total_cost'];
                }
            }

            $lift->update(['total_amount' => $totalAmount]);

            return $lift;
        });
    }

    /**
     * Edit a COMPLETED lift in place, preserving each product batch's id so that
     * already-sold sale_items stay linked and the running stock stays correct.
     *
     * Quantity-below-sold is validated by the caller before this runs. Here we:
     *  - update existing variants in place (recompute remaining = lifted - sold),
     *  - create newly added variants as fresh batches,
     *  - remove variants no longer present (caller guarantees these have no sales).
     *
     * @param array $soldByProduct  map of product_id => ['purchased' => int, 'free' => int]
     */
    public function reconcileCompletedLiftItems(Lift $lift, array $items, array $liftData, array $soldByProduct): Lift
    {
        return DB::transaction(function () use ($lift, $items, $liftData, $soldByProduct) {
            $lift->update($liftData);

            $existing = $lift->items()->get()->keyBy(
                fn ($it) => $it->product_catalog_id . '::' . $it->variant
            );
            $seenKeys = [];

            foreach ($items as $item) {
                foreach ($item['variants'] as $variant) {
                    $key = $item['product_catalog_id'] . '::' . $variant['variant'];
                    $seenKeys[$key] = true;
                    $m = $this->computeVariantMetrics($variant);

                    if ($existing->has($key)) {
                        $liftItem = $existing->get($key);
                        $sold = $soldByProduct[$liftItem->product_id] ?? ['purchased' => 0, 'free' => 0];
                        $soldP = (int) $sold['purchased'];
                        $soldF = (int) $sold['free'];

                        $product = $liftItem->product_id ? Product::find($liftItem->product_id) : null;

                        if ($product) {
                            // Recompute remaining stock and remaining purchase value.
                            $hasSales = $soldP > 0 || $soldF > 0;
                            $currentPurchased = max(0, $m['purchased_bottles'] - $soldP);
                            $currentFree = max(0, $m['total_free_bottles'] - $soldF);
                            $metaTotalCost = $hasSales
                                ? round($currentPurchased * $m['actual_rate_per_bottle'], 2)
                                : $m['total_cost'];

                            $product->update([
                                'name' => $item['product_name'],
                                'category_id' => $item['category_id'],
                                'brand_id' => $item['brand_id'],
                                'product_catalog_id' => $item['product_catalog_id'],
                                'date' => $liftData['lift_date'],
                                'metadata' => [
                                    'variants' => [
                                        $this->buildMetadataVariant($variant['variant'], $m, $currentPurchased, $currentFree, $metaTotalCost),
                                    ],
                                ],
                            ]);
                            $productId = $product->id;
                        } else {
                            // Defensive: lift_item lost its batch — recreate a fresh one.
                            $productId = $this->createProductBatch($item, $variant['variant'], $m, $liftData)->id;
                        }

                        $liftItem->update($this->buildLiftItemAttrs($lift->id, $item, $variant['variant'], $m, $productId));
                    } else {
                        // Newly added variant: fresh batch, full stock.
                        $product = $this->createProductBatch($item, $variant['variant'], $m, $liftData);
                        LiftItem::create($this->buildLiftItemAttrs($lift->id, $item, $variant['variant'], $m, $product->id));
                    }
                }
            }

            // Remove variants no longer present (caller blocked removing sold ones).
            foreach ($existing as $key => $liftItem) {
                if (isset($seenKeys[$key])) {
                    continue;
                }
                if ($liftItem->product_id) {
                    $product = Product::find($liftItem->product_id);
                    if ($product) {
                        $product->delete();
                    }
                }
                $liftItem->delete();
            }

            $newTotal = round((float) LiftItem::where('lift_id', $lift->id)->sum('total_cost'), 2);
            $lift->update(['total_amount' => $newTotal]);

            return $lift->fresh();
        });
    }

    /**
     * Derive all per-variant metrics from the raw lift input.
     */
    protected function computeVariantMetrics(array $variant): array
    {
        $numberOfCases = $variant['number_of_cases'] ?? 0;
        $caseBuyingPrice = $variant['case_buying_price'] ?? 0;
        $bottlesPerCase = $variant['bottles_per_case'] ?? 0;
        $freeBottlesPerCase = $variant['free_bottles_per_case'] ?? 0;

        $purchasedBottles = (int) round($numberOfCases * $bottlesPerCase);
        $totalFreeBottles = (int) round($numberOfCases * $freeBottlesPerCase);
        $totalBottles = $purchasedBottles + $totalFreeBottles;
        $totalCost = $numberOfCases * $caseBuyingPrice;
        $actualRatePerBottle = $totalBottles > 0 ? $totalCost / $totalBottles : 0;

        $casesWithFreeBottles = $bottlesPerCase > 0
            ? (int) floor($totalFreeBottles / $bottlesPerCase)
            : 0;
        $extraFreeBottles = (int) round($totalFreeBottles - ($casesWithFreeBottles * $bottlesPerCase));
        $casesWithoutFreeBottles = (int) round($numberOfCases - $casesWithFreeBottles);

        return [
            'number_of_cases' => $numberOfCases,
            'case_buying_price' => $caseBuyingPrice,
            'bottles_per_case' => $bottlesPerCase,
            'free_bottles_per_case' => $freeBottlesPerCase,
            'purchased_bottles' => $purchasedBottles,
            'total_free_bottles' => $totalFreeBottles,
            'total_bottles' => $totalBottles,
            'total_cost' => $totalCost,
            'actual_rate_per_bottle' => round($actualRatePerBottle, 4),
            'cases_with_free_bottles' => $casesWithFreeBottles,
            'cases_without_free_bottles' => $casesWithoutFreeBottles,
            'extra_free_bottles' => $extraFreeBottles,
        ];
    }

    /**
     * Build the single-variant metadata block for a product batch. The current_*
     * and total_cost values are passed explicitly so callers can express either a
     * fresh batch (full stock) or a partially-sold batch (remaining stock).
     */
    protected function buildMetadataVariant(string $variantName, array $m, $currentPurchased, $currentFree, $totalCost): array
    {
        return [
            'variant' => $variantName,
            'number_of_cases' => $m['number_of_cases'],
            'case_buying_price' => $m['case_buying_price'],
            'bottles_per_case' => $m['bottles_per_case'],
            'free_bottles_per_case' => $m['free_bottles_per_case'],
            'total_bottles' => $m['total_bottles'],
            'total_free_bottles' => $m['total_free_bottles'],
            'extra_free_bottles' => $m['extra_free_bottles'],
            'total_cost' => $totalCost,
            'actual_rate_per_bottle' => $m['actual_rate_per_bottle'],
            'actual_rate_per_case' => $m['case_buying_price'],
            'cases_with_free_bottles' => $m['cases_with_free_bottles'],
            'cases_without_free_bottles' => $m['cases_without_free_bottles'],
            'current_purchased_quantity' => $currentPurchased,
            'current_free_quantity' => $currentFree,
        ];
    }

    /**
     * Create a fresh product batch (full stock available, full purchase value).
     */
    protected function createProductBatch(array $item, string $variantName, array $m, array $liftData): Product
    {
        return Product::create([
            'name' => $item['product_name'],
            'supplier_id' => $item['supplier_id'] ?? $liftData['supplier_id'] ?? null,
            'category_id' => $item['category_id'] ?? null,
            'brand_id' => $item['brand_id'] ?? null,
            'product_catalog_id' => $item['product_catalog_id'],
            'metadata' => [
                'variants' => [
                    $this->buildMetadataVariant($variantName, $m, $m['purchased_bottles'], $m['total_free_bottles'], $m['total_cost']),
                ],
            ],
            'date' => $liftData['lift_date'],
        ]);
    }

    /**
     * The denormalized lift_item snapshot (always the FULL lifted figures, which
     * drive the Lift Report and the supplier deposit ledger).
     */
    protected function buildLiftItemAttrs(int $liftId, array $item, string $variantName, array $m, ?int $productId): array
    {
        return [
            'lift_id' => $liftId,
            'product_catalog_id' => $item['product_catalog_id'],
            'product_id' => $productId,
            'variant' => $variantName,
            'number_of_cases' => $m['number_of_cases'],
            'case_buying_price' => $m['case_buying_price'],
            'bottles_per_case' => $m['bottles_per_case'],
            'free_bottles_per_case' => $m['free_bottles_per_case'],
            'total_bottles' => $m['total_bottles'],
            'total_free_bottles' => $m['total_free_bottles'],
            'total_cost' => $m['total_cost'],
            'actual_rate_per_bottle' => $m['actual_rate_per_bottle'],
            'cases_with_free_bottles' => $m['cases_with_free_bottles'],
            'cases_without_free_bottles' => $m['cases_without_free_bottles'],
            'extra_free_bottles' => $m['extra_free_bottles'],
        ];
    }

    public function liftHistory(?int $supplierId = null): Collection
    {
        $query = Lift::with(['supplier', 'items.productCatalog'])
            ->orderBy('lift_date', 'desc');

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        return $query->get();
    }
}
