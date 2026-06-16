<?php

namespace App\Services;

use Illuminate\Support\Collection;

class SaleCostingService
{
    /**
     * Compute the FIFO cost of goods sold for a single sale line.
     *
     * Walks the batches oldest-first (the caller must pass them already ordered
     * by purchase date ascending) and allocates the bottles being sold exactly
     * the way the inventory-deduction loop does, so cost and stock deduction
     * always agree.
     *
     * Per-batch cost of a paid bottle = case_buying_price / bottles_per_case
     * (a full case therefore costs case_buying_price, and extra bottles are
     * priced at that same per-bottle rate). Free bottles handed to the customer
     * are cost-free because they were free on purchase.
     *
     * @param  Collection  $batches  Each entry: [
     *                                  'purchased' => int,
     *                                  'free' => int,
     *                                  'bottles_per_case' => int,
     *                                  'case_buying_price' => float,
     *                                  'purchase_rate' => float,
     *                                ]
     */
    public function fifoPurchaseCost(
        Collection $batches,
        int $purchasedBottlesSold,
        int $freeBottlesSold,
        bool $includeFreeBottles
    ): float {
        $remainingToDeduct = $purchasedBottlesSold + ($includeFreeBottles ? $freeBottlesSold : 0);
        $cost = 0.0;

        foreach ($batches as $batch) {
            if ($remainingToDeduct <= 0) {
                break;
            }

            $batchPurchased = (int) $batch['purchased'];
            $batchFree      = (int) $batch['free'];
            $bottlesPerCase = (int) $batch['bottles_per_case'];

            if ($includeFreeBottles) {
                $batchTotal = $batchPurchased + $batchFree;
                if ($batchTotal <= 0) {
                    continue;
                }
                $deductFromBatch = min($remainingToDeduct, $batchTotal);
                $deductPurchased = min($deductFromBatch, $batchPurchased);
            } else {
                $deductPurchased = min($remainingToDeduct, $batchPurchased);
                $deductFromBatch = $deductPurchased;
            }

            if ($deductFromBatch <= 0) {
                continue;
            }

            // Only paid bottles carry cost; free bottles add nothing.
            if ($deductPurchased > 0 && $bottlesPerCase > 0) {
                $bottleCost = (float) $batch['case_buying_price'] / $bottlesPerCase;
                $cost += $deductPurchased * $bottleCost;
            }

            $remainingToDeduct -= $deductFromBatch;
        }

        return round($cost, 2);
    }
}
