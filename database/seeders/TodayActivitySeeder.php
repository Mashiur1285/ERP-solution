<?php

namespace Database\Seeders;

use App\Http\Controllers\LiftController;
use App\Http\Controllers\SalesController;
use App\Models\Product;
use App\Models\ProductCatalog;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Supplier;
use App\Services\SmsService;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

/**
 * A day's worth of lifts and sales dated TODAY.
 *
 * Every report opens on the "Today" preset, so a database seeded only with past
 * dates looks empty until the filter is changed. Run this on any day you want
 * the default view to have something in it. Safe to run repeatedly - it just
 * adds another day's activity.
 *
 * Run with:  php artisan db:seed --class=TodayActivitySeeder
 */
class TodayActivitySeeder extends Seeder
{
    public function run(): void
    {
        app()->instance(SmsService::class, new class extends SmsService {
            public function send(string $mobile, string $message): bool
            {
                return false;
            }
        });

        $today = now()->toDateString();

        $this->seedLift($today);
        $this->seedSales($today);

        $this->report($today);
    }

    /** One lift today, so the lift report's default view is not empty either. */
    private function seedLift(string $today): void
    {
        $catalog = ProductCatalog::where('name', 'Globe Lemon')->first();
        if (! $catalog) {
            return;
        }

        $this->callController(LiftController::class, 'store', '/lifts/store', [
            'supplier_id' => $catalog->supplier_id,
            'lift_date'   => $today,
            'notes'       => "Today's sample lift",
            'items'       => [[
                'product_catalog_id' => $catalog->id,
                'product_name'       => $catalog->name,
                'variants'           => [
                    ['variant' => '250ml', 'number_of_cases' => 30, 'case_buying_price' => 486, 'bottles_per_case' => 24, 'free_bottles_per_case' => 1],
                    ['variant' => '550ml', 'number_of_cases' => 15, 'case_buying_price' => 655, 'bottles_per_case' => 18, 'free_bottles_per_case' => 0],
                ],
            ]],
        ]);
    }

    /**
     * A spread across four suppliers so today's list shows several groups, and
     * a mix of paid / part-paid / unpaid / draft so every badge appears.
     */
    private function seedSales(string $today): void
    {
        // [shop name, supplier company, paid ratio (null = draft), lines]
        // line = [product, variant, cases, extra bottles, price per case]
        $plan = [
            ['Bismillah Store',   'Globe Soft Drinks Ltd.',       1.0,  [['Globe Lemon', '250ml', 4, 0, 610]]],
            ['Nasir Enterprise',  'PRAN Beverage Ltd.',           0.0,  [['Pran Up', '250ml', 5, 0, 600]]],
            ['Hazi Store',        'Akij Food & Beverage Ltd.',    0.4,  [['Mojo', '250ml', 6, 0, 620]]],
            ['Barkat Store',      'Partex Beverage Ltd.',         1.0,  [['Partex Mineral Water', '500ml', 8, 0, 310]]],
            ['Sonali Enterprise', 'Globe Soft Drinks Ltd.',       0.55, [['Globe Orange', '500ml', 3, 6, 760]]],
            ['New Model Store',   'Abdul Monem Ltd. (Coca-Cola)', 1.0,  [['Coca-Cola', '250ml', 3, 0, 680]]],
            ['Prio Shop',         'Akij Food & Beverage Ltd.',    0.0,  [['Frutika Mango', '250ml', 4, 0, 700]]],
            ['Alamin Store',      'Partex Beverage Ltd.',         null, [['Partex Cola', '330ml', 5, 0, 700]]],
            ['Chowdhury Store',   'PRAN Beverage Ltd.',           null, [['Pran Drinking Water', '1L', 6, 0, 470]]],
        ];

        foreach ($plan as [$shopName, $company, $paidRatio, $lines]) {
            $shop     = Shop::where('shop_name', $shopName)->first();
            $supplier = Supplier::where('company_name', $company)->first();

            if (! $shop || ! $supplier) {
                continue;
            }

            $isDraft = $paidRatio === null;
            $items   = [];

            foreach ($lines as [$productName, $variant, $cases, $extra, $pricePerCase]) {
                // Each variant is its own product batch, so the oldest batch by
                // date usually holds a different size - look for the one that
                // actually carries this variant.
                $batch = Product::where('name', $productName)
                    ->where('supplier_id', $supplier->id)
                    ->whereNotNull('metadata')
                    ->orderBy('date')
                    ->get()
                    ->first(fn ($p) => collect($p->metadata['variants'] ?? [])
                        ->contains(fn ($v) => ($v['variant'] ?? null) === $variant));

                if (! $batch) {
                    continue;
                }

                $meta = collect($batch->metadata['variants'])->firstWhere('variant', $variant);

                $bpc          = (int) ($meta['bottles_per_case'] ?? 0);
                $free         = (int) ($meta['free_bottles_per_case'] ?? 0);
                $effectiveBpc = $bpc + $free;

                $items[] = [
                    'product_id'               => $batch->id,
                    'variant'                  => $variant,
                    'cases_sold'               => $cases,
                    'extra_bottles'            => $extra,
                    'total_bottles_to_sell'    => ($cases * $effectiveBpc) + $extra,
                    'selling_price_per_bottle' => $effectiveBpc > 0 ? $pricePerCase / $effectiveBpc : 0,
                    'free_bottles_per_case'    => $free,
                ];
            }

            if (empty($items)) {
                continue;
            }

            $this->callController(SalesController::class, 'store', '/sales/store', array_filter([
                'shop_id'              => $shop->id,
                'supplier_id'          => $supplier->id,
                'sale_date'            => $today,
                'include_free_bottles' => true,
                'save_as_draft'        => $isDraft,
                'items'                => $items,
            ], fn ($v) => $v !== false));

            if ($isDraft || $paidRatio <= 0) {
                continue;
            }

            $sale = Sale::where('shop_id', $shop->id)
                ->where('supplier_id', $supplier->id)
                ->orderByDesc('id')
                ->first();

            if ($sale) {
                $this->callController(SalesController::class, 'storePayment', '/sales/payment/store/' . $sale->id, [
                    'payment_amount' => round((float) $sale->total_amount * $paidRatio, 2),
                    'payment_method' => $paidRatio >= 1.0 ? 'cash' : 'bkash',
                ], [$sale->id]);
            }
        }
    }

    private function callController(string $class, string $method, string $uri, array $payload, array $extraArgs = []): void
    {
        $request = Request::create($uri, 'POST', $payload);
        app()->instance('request', $request);

        app()->make($class)->{$method}($request, ...$extraArgs);
    }

    private function report(string $today): void
    {
        $out = $this->command?->getOutput();
        if (! $out) {
            return;
        }

        $sales = Sale::whereDate('sale_date', $today);

        $out->writeln(sprintf('  <info>%-16s</info> %s', 'date', $today));
        $out->writeln(sprintf('  <info>%-16s</info> %d', 'sales today', (clone $sales)->where('status', '!=', 'draft')->count()));
        $out->writeln(sprintf('  <info>%-16s</info> %d', 'drafts today', (clone $sales)->where('status', 'draft')->count()));
        $out->writeln(sprintf('  <info>%-16s</info> %s', 'due today', number_format(
            (float) (clone $sales)->where('status', '!=', 'draft')->sum('due_amount'), 2
        )));
    }
}
