<?php

namespace Database\Seeders;

use App\Http\Controllers\LiftController;
use App\Http\Controllers\SalesController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Product;
use App\Models\ProductCatalog;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Supplier;
use App\Services\SmsService;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

/**
 * A second helping of sample data, added on top of DummyDataSeeder.
 *
 * It is deliberately shaped to exercise the things that are easy to get wrong:
 * more suppliers so the report has several groups, sale dates shuffled across
 * suppliers so grouping visibly reorders them, custom variant sizes that are
 * not one of the four presets, and a spread of paid / part-paid / unpaid / draft
 * sales. Like DummyDataSeeder it drives the real controllers, so the stock,
 * deposit ledger and profit come out exactly as the app would write them.
 *
 * Run with:  php artisan db:seed --class=ExtraDummyDataSeeder
 */
class ExtraDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        app()->instance(SmsService::class, new class extends SmsService {
            public function send(string $mobile, string $message): bool
            {
                return false;
            }
        });

        $suppliers = $this->seedSuppliers();
        $shops     = $this->seedShops();
        $catalogs  = $this->seedCatalogs($suppliers);

        $this->seedLifts($suppliers, $catalogs);
        $this->seedSales($shops);

        $this->report();
    }

    // ── Reference data ───────────────────────────────────────────────────────

    private function seedSuppliers(): array
    {
        $rows = [
            'Globe'  => ['Globe Soft Drinks Ltd.', 'Tongi Depot', '01711000004', 'Tongi, Gazipur'],
            'Partex' => ['Partex Beverage Ltd.', 'Savar Depot', '01711000005', 'Savar, Dhaka'],
        ];

        $out = [];
        foreach ($rows as $key => [$company, $branch, $phone, $address]) {
            $supplier = Supplier::firstOrCreate(
                ['company_name' => $company],
                [
                    'branch_name'  => $branch,
                    'phone_number' => $phone,
                    'address'      => $address,
                    'city'         => 'Dhaka',
                    'country'      => 'Bangladesh',
                ]
            );

            if (Deposit::where('supplier_id', $supplier->id)->doesntExist()) {
                Deposit::create([
                    'supplier_id'       => $supplier->id,
                    'balance_deposited' => 900_000,
                    'balance_remaining' => 900_000,
                    'deposit_date'      => now()->subDays(50)->toDateString(),
                    'is_used'           => false,
                ]);
            }

            $out[$key] = $supplier;
        }

        return $out;
    }

    private function seedShops(): array
    {
        $rows = [
            ['Hazi Store', 'Abdul Hakim', 'Mohammadpur Ring Road', '01811000009'],
            ['Sonali Enterprise', 'Selim Reza', 'Mohammadpur Ring Road', '01811000010'],
            ['Barkat Store', 'Barkat Ullah', 'Banasree Main Road', '01811000011'],
            ['Prio Shop', 'Nasrin Akter', 'Banasree Main Road', '01811000012'],
        ];

        foreach ($rows as [$name, $owner, $road, $phone]) {
            Shop::firstOrCreate(
                ['shop_name' => $name],
                [
                    'owner_name'   => $owner,
                    'road'         => $road,
                    'phone_number' => $phone,
                    'shop_address' => $road . ', Dhaka',
                ]
            );
        }

        // Sales below draw from every shop, old and new.
        return Shop::orderBy('id')->get()->all();
    }

    private function seedCatalogs(array $suppliers): array
    {
        $category = Category::firstOrCreate(['name' => 'Soft Drinks'], ['description' => 'Soft Drinks products']);
        $water    = Category::firstOrCreate(['name' => 'Drinking Water'], ['description' => 'Drinking Water products']);

        $rows = [
            ['Globe Lemon',          'Globe',  $category, 'Globe'],
            ['Globe Orange',         'Globe',  $category, 'Globe'],
            ['Partex Cola',          'Partex', $category, 'Partex'],
            ['Partex Mineral Water', 'Partex', $water,    'Partex'],
        ];

        $out = [];
        foreach ($rows as [$name, $supplierKey, $cat, $brandName]) {
            $brand = Brand::firstOrCreate(['brand_name' => $brandName], ['description' => $brandName . ' brand']);

            $out[$name] = ProductCatalog::firstOrCreate(
                ['name' => $name, 'supplier_id' => $suppliers[$supplierKey]->id],
                [
                    'category_id'      => $cat->id,
                    'brand_id'         => $brand->id,
                    'is_active'        => true,
                    'default_variants' => [],
                ]
            );
        }

        return $out;
    }

    // ── Lifts ────────────────────────────────────────────────────────────────

    /**
     * Note the 550ml, 330ml and 5L sizes: none of them is one of the four the
     * picker hardcodes, so they only come back next time if the catalog is
     * remembering custom sizes properly.
     */
    private function seedLifts(array $suppliers, array $catalogs): void
    {
        $plan = [
            35 => [
                'Globe'  => [
                    ['Globe Lemon', '250ml', 40, 470, 24, 1],
                    ['Globe Lemon', '550ml', 25, 640, 18, 0],
                    ['Globe Orange', '500ml', 30, 590, 12, 0],
                ],
                'Partex' => [
                    ['Partex Cola', '330ml', 35, 520, 20, 2],
                    ['Partex Mineral Water', '500ml', 60, 230, 24, 0],
                    ['Partex Mineral Water', '5L', 20, 460, 4, 0],
                ],
            ],
            12 => [
                'Globe'  => [
                    ['Globe Lemon', '550ml', 20, 668, 18, 0],
                    ['Globe Orange', '500ml', 22, 612, 12, 0],
                ],
                'Partex' => [
                    ['Partex Cola', '330ml', 28, 546, 20, 2],
                ],
            ],
        ];

        foreach ($plan as $daysAgo => $bySupplier) {
            foreach ($bySupplier as $supplierKey => $lines) {
                $items = [];

                foreach ($lines as [$catalogName, $variant, $cases, $casePrice, $bpc, $free]) {
                    $catalog = $catalogs[$catalogName];
                    $items[$catalog->id] ??= [
                        'product_catalog_id' => $catalog->id,
                        'product_name'       => $catalog->name,
                        'variants'           => [],
                    ];
                    $items[$catalog->id]['variants'][] = [
                        'variant'               => $variant,
                        'number_of_cases'       => $cases,
                        'case_buying_price'     => $casePrice,
                        'bottles_per_case'      => $bpc,
                        'free_bottles_per_case' => $free,
                    ];
                }

                $this->callController(LiftController::class, 'store', '/lifts/store', [
                    'supplier_id' => $suppliers[$supplierKey]->id,
                    'lift_date'   => now()->subDays($daysAgo)->toDateString(),
                    'notes'       => 'Extra sample lift',
                    'items'       => array_values($items),
                ]);
            }
        }
    }

    // ── Sales ────────────────────────────────────────────────────────────────

    /**
     * Dates are deliberately shuffled between suppliers: sorted by date these
     * rows interleave, so the report is only readable if it groups by supplier.
     */
    private function seedSales(array $shops): void
    {
        // [shop index, supplier company, days ago, paid ratio (null = draft), lines]
        $plan = [
            [8,  'Globe Soft Drinks Ltd.', 30, 1.0,  [['Globe Lemon', '250ml', 5, 0, 610]]],
            [0,  'PRAN Beverage Ltd.',     29, 0.35, [['Pran Up', '250ml', 4, 0, 600]]],
            [9,  'Globe Soft Drinks Ltd.', 28, 0.0,  [['Globe Lemon', '550ml', 6, 0, 820]]],
            [10, 'Partex Beverage Ltd.',   27, 1.0,  [['Partex Mineral Water', '500ml', 10, 0, 310]]],
            [2,  'Akij Food & Beverage Ltd.', 26, 0.5, [['Mojo', '250ml', 4, 0, 620]]],
            [11, 'Partex Beverage Ltd.',   24, 0.0,  [['Partex Cola', '330ml', 7, 0, 700]]],
            [8,  'Globe Soft Drinks Ltd.', 22, 1.0,  [['Globe Orange', '500ml', 5, 6, 760]]],
            [3,  'Abdul Monem Ltd. (Coca-Cola)', 20, 0.6, [['Coca-Cola', '250ml', 5, 0, 680]]],
            [10, 'Partex Beverage Ltd.',   18, 1.0,  [['Partex Mineral Water', '5L', 8, 0, 620]]],
            [9,  'Globe Soft Drinks Ltd.', 16, 0.25, [['Globe Lemon', '250ml', 6, 12, 610]]],
            [1,  'PRAN Beverage Ltd.',     14, 1.0,  [['Pran Drinking Water', '500ml', 9, 0, 320]]],
            [11, 'Partex Beverage Ltd.',   11, 0.45, [['Partex Cola', '330ml', 5, 0, 700], ['Partex Mineral Water', '500ml', 6, 0, 310]]],
            [4,  'Akij Food & Beverage Ltd.', 9, 0.0, [['Speed Energy Drink', '250ml', 3, 0, 900]]],
            [8,  'Globe Soft Drinks Ltd.',  6, 1.0,  [['Globe Orange', '500ml', 4, 0, 760]]],
            [10, 'Partex Beverage Ltd.',    4, 0.0,  [['Partex Mineral Water', '500ml', 12, 0, 310]]],

            // Drafts across three different suppliers.
            [9,  'Globe Soft Drinks Ltd.',  3, null, [['Globe Lemon', '550ml', 4, 0, 820]]],
            [11, 'Partex Beverage Ltd.',    2, null, [['Partex Cola', '330ml', 6, 0, 700]]],
            [0,  'Akij Food & Beverage Ltd.', 1, null, [['Mojo', '500ml', 3, 0, 760]]],
        ];

        foreach ($plan as [$shopIndex, $company, $daysAgo, $paidRatio, $lines]) {
            $supplier = Supplier::where('company_name', $company)->first();
            $shop     = $shops[$shopIndex] ?? null;

            if (! $supplier || ! $shop) {
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
                'sale_date'            => now()->subDays($daysAgo)->toDateString(),
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

    // ── Plumbing ─────────────────────────────────────────────────────────────

    private function callController(string $class, string $method, string $uri, array $payload, array $extraArgs = []): void
    {
        $request = Request::create($uri, 'POST', $payload);
        app()->instance('request', $request);

        app()->make($class)->{$method}($request, ...$extraArgs);
    }

    private function report(): void
    {
        $out = $this->command?->getOutput();
        if (! $out) {
            return;
        }

        $out->writeln(sprintf('  <info>%-12s</info> %d', 'suppliers', Supplier::count()));
        $out->writeln(sprintf('  <info>%-12s</info> %d', 'shops', Shop::count()));
        $out->writeln(sprintf('  <info>%-12s</info> %d', 'batches', Product::count()));
        $out->writeln(sprintf('  <info>%-12s</info> %d', 'sales', Sale::where('status', '!=', 'draft')->count()));
        $out->writeln(sprintf('  <info>%-12s</info> %d', 'drafts', Sale::where('status', 'draft')->count()));
        $out->writeln(sprintf('  <info>%-12s</info> %s', 'total due', number_format(
            (float) Sale::where('status', '!=', 'draft')->sum('due_amount'), 2
        )));
    }
}
