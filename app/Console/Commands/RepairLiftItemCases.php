<?php

namespace App\Console\Commands;

use App\Models\Lift;
use App\Models\LiftItem;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * One-off repair for lift_items corrupted by the old inventory stock-adjustment
 * logic (adjustVariantStock -> syncLiftItemForBatch), which overwrote the
 * immutable "what was lifted" snapshot with current remaining stock — zeroing
 * earlier batches and decaying the latest batch toward 0.
 *
 * The original lifted figures still survive in products.metadata.variants[]
 * (number_of_cases etc. were never touched by the adjustment), so we restore
 * each lift_item from its linked product's metadata variant, then recompute
 * each affected lift's total_amount.
 *
 * Note on total_cost: metadata.total_cost reflects *remaining* stock value
 * (sales/adjustments mutate it), so it is NOT the lifted cost. We recompute the
 * lifted cost as number_of_cases * case_buying_price, mirroring
 * LiftRepository::computeVariantMetrics().
 */
class RepairLiftItemCases extends Command
{
    protected $signature = 'lifts:repair-cases {--dry-run : Show the changes without writing them}';

    protected $description = 'Restore lift_items snapshots (number_of_cases, etc.) from product metadata after the stock-adjustment corruption.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be written.');
        }

        $repairedItems = 0;
        $affectedLiftIds = [];
        $skipped = 0;

        $run = function () use (&$repairedItems, &$affectedLiftIds, &$skipped, $dryRun) {
            $items = LiftItem::whereNotNull('product_id')->get();

            foreach ($items as $item) {
                $product = Product::withTrashed()->find($item->product_id);
                if (!$product) {
                    $skipped++;
                    $this->line("  - lift_item #{$item->id}: linked product #{$item->product_id} not found, skipped.");
                    continue;
                }

                $variantData = collect($product->metadata['variants'] ?? [])
                    ->first(fn ($v) => (string) ($v['variant'] ?? '') === (string) $item->variant);

                if (!$variantData) {
                    $skipped++;
                    $this->line("  - lift_item #{$item->id}: variant '{$item->variant}' not in product #{$product->id} metadata, skipped.");
                    continue;
                }

                $numberOfCases   = $variantData['number_of_cases'] ?? 0;
                $caseBuyingPrice = $variantData['case_buying_price'] ?? 0;

                // Recomputed immutable lifted cost (do NOT trust metadata.total_cost,
                // which tracks remaining stock value).
                $totalCost = round($numberOfCases * $caseBuyingPrice, 2);

                $restored = [
                    'number_of_cases'            => $numberOfCases,
                    'case_buying_price'          => $caseBuyingPrice,
                    'bottles_per_case'           => $variantData['bottles_per_case'] ?? 0,
                    'free_bottles_per_case'      => $variantData['free_bottles_per_case'] ?? 0,
                    'total_bottles'              => $variantData['total_bottles'] ?? 0,
                    'total_free_bottles'         => $variantData['total_free_bottles'] ?? 0,
                    'extra_free_bottles'         => $variantData['extra_free_bottles'] ?? 0,
                    'cases_with_free_bottles'    => $variantData['cases_with_free_bottles'] ?? 0,
                    'cases_without_free_bottles' => $variantData['cases_without_free_bottles'] ?? 0,
                    'actual_rate_per_bottle'     => $variantData['actual_rate_per_bottle'] ?? 0,
                    'total_cost'                 => $totalCost,
                ];

                // Skip rows that already match (nothing to repair). Compare numerically
                // so decimal-column formatting (e.g. "6.0000" vs 6) isn't seen as a change.
                $changed = false;
                foreach ($restored as $key => $value) {
                    if (abs((float) $item->{$key} - (float) $value) > 0.0001) {
                        $changed = true;
                        break;
                    }
                }
                if (!$changed) {
                    continue;
                }

                $this->line(sprintf(
                    "  - lift_item #%d (lift %d, '%s' %s): cases %s -> %s, total_cost %s -> %s",
                    $item->id,
                    $item->lift_id,
                    $product->name,
                    $item->variant,
                    $item->number_of_cases,
                    $restored['number_of_cases'],
                    $item->total_cost,
                    $restored['total_cost']
                ));

                if (!$dryRun) {
                    $item->update($restored);
                }

                $repairedItems++;
                $affectedLiftIds[$item->lift_id] = $item->lift_id;
            }

            // Recompute each affected lift's total_amount from its restored items.
            foreach ($affectedLiftIds as $liftId) {
                $lift = Lift::find($liftId);
                if (!$lift) {
                    continue;
                }

                $newTotal = round((float) LiftItem::where('lift_id', $liftId)->sum('total_cost'), 2);
                $oldTotal = round((float) $lift->total_amount, 2);

                if ($oldTotal !== $newTotal) {
                    $this->line("  - lift #{$liftId}: total_amount {$oldTotal} -> {$newTotal}");
                    if (!$dryRun) {
                        $lift->update(['total_amount' => $newTotal]);
                    }
                }
            }
        };

        if ($dryRun) {
            $run();
        } else {
            DB::transaction($run);
        }

        $this->newLine();
        $this->info(sprintf(
            '%s %d lift item(s) across %d lift(s). Skipped %d.',
            $dryRun ? 'Would repair' : 'Repaired',
            $repairedItems,
            count($affectedLiftIds),
            $skipped
        ));

        if (!$dryRun && $repairedItems > 0) {
            $this->warn('Reminder: supplier deposit balances may also be off, since the old '
                . 'adjustment credited/debited deposits while corrupting lift totals. Review '
                . 'deposits separately before trusting them.');
        }

        return self::SUCCESS;
    }
}
