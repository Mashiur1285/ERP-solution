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

    public function createLiftWithItems(array $liftData, array $items, int $supplierId): Model
    {
        return DB::transaction(function () use ($liftData, $items, $supplierId) {
            // Generate lift number
            $lastLift = Lift::orderBy('id', 'desc')->first();
            $nextId = $lastLift ? $lastLift->id + 1 : 1;
            $liftData['lift_number'] = 'LFT-' . Str::padLeft($nextId, 6, '0');

            $lift = Lift::create($liftData);

            $totalAmount = 0;

            foreach ($items as $item) {
                foreach ($item['variants'] as $variant) {
                    // Calculate variant metrics
                    $numberOfCases = $variant['number_of_cases'];
                    $caseBuyingPrice = $variant['case_buying_price'];
                    $bottlesPerCase = $variant['bottles_per_case'];
                    $freeBottlesPerCase = $variant['free_bottles_per_case'] ?? 0;

                    $purchasedBottles = $numberOfCases * $bottlesPerCase;
                    $totalFreeBottles = $numberOfCases * $freeBottlesPerCase;
                    $totalBottles = $purchasedBottles + $totalFreeBottles;
                    $totalCost = $numberOfCases * $caseBuyingPrice;
                    $actualRatePerBottle = $totalBottles > 0 ? $totalCost / $totalBottles : 0;

                    $casesWithFreeBottles = $bottlesPerCase > 0
                        ? floor($totalFreeBottles / $bottlesPerCase)
                        : 0;
                    $extraFreeBottles = $totalFreeBottles - ($casesWithFreeBottles * $bottlesPerCase);
                    $casesWithoutFreeBottles = $numberOfCases - $casesWithFreeBottles;

                    // Create legacy products row for inventory compatibility
                    $product = Product::create([
                        'name' => $item['product_name'],
                        'supplier_id' => $supplierId,
                        'category_id' => $item['category_id'],
                        'brand_id' => $item['brand_id'],
                        'product_catalog_id' => $item['product_catalog_id'],
                        'metadata' => [
                            'variants' => [[
                                'variant' => $variant['variant'],
                                'number_of_cases' => $numberOfCases,
                                'case_buying_price' => $caseBuyingPrice,
                                'bottles_per_case' => $bottlesPerCase,
                                'free_bottles_per_case' => $freeBottlesPerCase,
                                'total_bottles' => $totalBottles,
                                'total_free_bottles' => $totalFreeBottles,
                                'extra_free_bottles' => $extraFreeBottles,
                                'total_cost' => $totalCost,
                                'actual_rate_per_bottle' => round($actualRatePerBottle, 4),
                                'actual_rate_per_case' => $caseBuyingPrice,
                                'cases_with_free_bottles' => $casesWithFreeBottles,
                                'cases_without_free_bottles' => $casesWithoutFreeBottles,
                                'current_purchased_quantity' => $purchasedBottles,
                                'current_free_quantity' => $totalFreeBottles,
                            ]],
                        ],
                        'date' => $liftData['lift_date'],
                    ]);

                    // Create lift_items row
                    LiftItem::create([
                        'lift_id' => $lift->id,
                        'product_catalog_id' => $item['product_catalog_id'],
                        'product_id' => $product->id,
                        'variant' => $variant['variant'],
                        'number_of_cases' => $numberOfCases,
                        'case_buying_price' => $caseBuyingPrice,
                        'bottles_per_case' => $bottlesPerCase,
                        'free_bottles_per_case' => $freeBottlesPerCase,
                        'total_bottles' => $totalBottles,
                        'total_free_bottles' => $totalFreeBottles,
                        'total_cost' => $totalCost,
                        'actual_rate_per_bottle' => round($actualRatePerBottle, 4),
                        'cases_with_free_bottles' => $casesWithFreeBottles,
                        'cases_without_free_bottles' => $casesWithoutFreeBottles,
                        'extra_free_bottles' => $extraFreeBottles,
                    ]);

                    $totalAmount += $totalCost;
                }
            }

            $lift->update(['total_amount' => $totalAmount]);

            return $lift;
        });
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
