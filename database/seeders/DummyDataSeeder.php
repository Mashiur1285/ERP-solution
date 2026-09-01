<?php

namespace Database\Seeders;

use App\Http\Controllers\LiftController;
use App\Http\Controllers\SalesController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductCatalog;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Supplier;
use App\Services\SmsService;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Development-only sample data.
 *
 * Lifts and sales are pushed through the real controllers rather than inserted
 * directly, so the product metadata, deposit ledger, FIFO stock and stored
 * profit all come out exactly as the application would write them. Hand-built
 * rows drift from that logic and then every report looks subtly wrong.
 *
 * Run with:  php artisan db:seed --class=DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // The gateway is unconfigured in dev and already no-ops, but binding a
        // dummy makes it impossible for a seed run to text a real shop owner.
        app()->instance(SmsService::class, new class extends SmsService {
            public function send(string $mobile, string $message): bool
            {
                return false;
            }
        });

        $categories = $this->seedCategories();
        $brands     = $this->seedBrands();
        $suppliers  = $this->seedSuppliers();
        $shops      = $this->seedShops();
        $catalogs   = $this->seedCatalogs($suppliers, $categories, $brands);

        $this->seedLifts($suppliers, $catalogs);
        $this->seedSales($suppliers, $shops);
        $this->seedExpenses();

        $this->report();
    }

    // ── Reference data ───────────────────────────────────────────────────────

    private function seedCategories(): array
    {
        $names = ['Soft Drinks', 'Juice', 'Drinking Water', 'Energy Drink'];
        $out   = [];

        foreach ($names as $name) {
            $out[$name] = Category::firstOrCreate(
                ['name' => $name],
                ['description' => $name . ' products']
            );
        }

        return $out;
    }

    private function seedBrands(): array
    {
        $names = ['Mojo', 'Speed', 'Frutika', 'Pran', 'Coca-Cola', 'Sprite', 'Fanta', 'Mum'];
        $out   = [];

        foreach ($names as $name) {
            $out[$name] = Brand::firstOrCreate(
                ['brand_name' => $name],
                ['description' => $name . ' brand']
            );
        }

        return $out;
    }

    private function seedSuppliers(): array
    {
        $rows = [
            'Akij'  => ['Akij Food & Beverage Ltd.', 'Dhaka Depot', '01711000001', 'Tejgaon I/A, Dhaka'],
            'Pran'  => ['PRAN Beverage Ltd.', 'Narayanganj Depot', '01711000002', 'Ghorashal, Narayanganj'],
            'Coke'  => ['Abdul Monem Ltd. (Coca-Cola)', 'Dhaka Depot', '01711000003', 'Gulshan, Dhaka'],
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

            // Enough balance that every lift below clears without a shortfall.
            if (Deposit::where('supplier_id', $supplier->id)->doesntExist()) {
                Deposit::create([
                    'supplier_id'       => $supplier->id,
                    'balance_deposited' => 1_500_000,
                    'balance_remaining' => 1_500_000,
                    'deposit_date'      => now()->subDays(60)->toDateString(),
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
            ['Bismillah Store', 'Karim Mia', 'Mirpur Road', '01811000001'],
            ['Rahim General Store', 'Rahim Uddin', 'Mirpur Road', '01811000002'],
            ['Nasir Enterprise', 'Nasir Ahmed', 'Gulshan Avenue', '01811000003'],
            ['Bhai Bhai Store', 'Sohel Rana', 'Gulshan Avenue', '01811000004'],
            ['New Model Store', 'Jashim Uddin', 'Dhanmondi 27', '01811000005'],
            ['Chowdhury Store', 'Faruk Chowdhury', 'Dhanmondi 27', '01811000006'],
            ['Uttara Super Shop', 'Mizanur Rahman', 'Uttara Sector 7', '01811000007'],
            ['Alamin Store', 'Alamin Hossain', 'Uttara Sector 7', '01811000008'],
        ];

        $out = [];
        foreach ($rows as [$name, $owner, $road, $phone]) {
            $out[] = Shop::firstOrCreate(
                ['shop_name' => $name],
                [
                    'owner_name'   => $owner,
                    'road'         => $road,
                    'phone_number' => $phone,
                    'shop_address' => $road . ', Dhaka',
                ]
            );
        }

        return $out;
    }

    private function seedCatalogs(array $suppliers, array $categories, array $brands): array
    {
        $rows = [
            ['Mojo',                 'Akij', 'Soft Drinks',    'Mojo'],
            ['Speed Energy Drink',   'Akij', 'Energy Drink',   'Speed'],
            ['Frutika Mango',        'Akij', 'Juice',          'Frutika'],
            ['Pran Up',              'Pran', 'Soft Drinks',    'Pran'],
            ['Pran Mango Juice',     'Pran', 'Juice',          'Pran'],
            ['Pran Drinking Water',  'Pran', 'Drinking Water', 'Mum'],
            ['Coca-Cola',            'Coke', 'Soft Drinks',    'Coca-Cola'],
            ['Sprite',               'Coke', 'Soft Drinks',    'Sprite'],
            ['Fanta Orange',         'Coke', 'Soft Drinks',    'Fanta'],
        ];

        $out = [];
        foreach ($rows as [$name, $supplierKey, $categoryName, $brandName]) {
            $out[$name] = ProductCatalog::firstOrCreate(
                ['name' => $name, 'supplier_id' => $suppliers[$supplierKey]->id],
                [
                    'category_id'      => $categories[$categoryName]->id,
                    'brand_id'         => $brands[$brandName]->id,
                    'is_active'        => true,
                    'default_variants' => [],
                ]
            );
        }

        return $out;
    }

    // ── Lifts (stock in) ─────────────────────────────────────────────────────

    /**
     * Two lifts per supplier on different dates, so products carry more than one
     * batch and the FIFO / weighted-average-cost paths get real data to work on.
     */
    private function seedLifts(array $suppliers, array $catalogs): void
    {
        // [catalog name, variant, cases, case buying price, bottles/case, free/case]
        $firstLift = [
            'Akij' => [
                ['Mojo', '250ml', 40, 480, 24, 1],
                ['Mojo', '500ml', 25, 600, 12, 1],
                ['Speed Energy Drink', '250ml', 30, 720, 24, 0],
                ['Frutika Mango', '250ml', 20, 540, 24, 0],
            ],
            'Pran' => [
                ['Pran Up', '250ml', 35, 460, 24, 2],
                ['Pran Up', '500ml', 20, 580, 12, 1],
                ['Pran Mango Juice', '250ml', 25, 500, 24, 0],
                ['Pran Drinking Water', '500ml', 50, 240, 24, 0],
                ['Pran Drinking Water', '1L', 30, 360, 12, 0],
            ],
            'Coke' => [
                ['Coca-Cola', '250ml', 45, 520, 24, 1],
                ['Coca-Cola', '500ml', 30, 660, 12, 0],
                ['Sprite', '500ml', 25, 650, 12, 0],
                ['Fanta Orange', '500ml', 20, 640, 12, 0],
            ],
        ];

        // Same variants at slightly different prices — this is what makes the
        // weighted-average cost meaningful instead of a single flat rate.
        $secondLift = [
            'Akij' => [
                ['Mojo', '250ml', 30, 504, 24, 1],
                ['Speed Energy Drink', '250ml', 20, 744, 24, 0],
            ],
            'Pran' => [
                ['Pran Up', '250ml', 25, 480, 24, 2],
                ['Pran Drinking Water', '500ml', 40, 252, 24, 0],
            ],
            'Coke' => [
                ['Coca-Cola', '250ml', 35, 546, 24, 1],
                ['Sprite', '500ml', 20, 676, 12, 0],
            ],
        ];

        $this->runLifts($suppliers, $catalogs, $firstLift, now()->subDays(45));
        $this->runLifts($suppliers, $catalogs, $secondLift, now()->subDays(20));
    }

    private function runLifts(array $suppliers, array $catalogs, array $plan, Carbon $date): void
    {
        foreach ($plan as $supplierKey => $lines) {
            $items = [];

            foreach ($lines as [$catalogName, $variant, $cases, $casePrice, $bpc, $free]) {
                $catalog = $catalogs[$catalogName];
                $key     = $catalog->id;

                $items[$key] ??= [
                    'product_catalog_id' => $catalog->id,
                    'product_name'       => $catalog->name,
                    'variants'           => [],
                ];

                $items[$key]['variants'][] = [
                    'variant'               => $variant,
                    'number_of_cases'       => $cases,
                    'case_buying_price'     => $casePrice,
                    'bottles_per_case'      => $bpc,
                    'free_bottles_per_case' => $free,
                ];
            }

            $this->callController(LiftController::class, 'store', '/lifts/store', [
                'supplier_id' => $suppliers[$supplierKey]->id,
                'lift_date'   => $date->toDateString(),
                'notes'       => 'Sample lift seeded for testing',
                'items'       => array_values($items),
            ]);
        }
    }

    // ── Sales (stock out) ────────────────────────────────────────────────────

    /**
     * A spread of finished, part-paid and draft sales, so the report tabs, the
     * due list and the draft flow all have something to show.
     */
    private function seedSales(array $suppliers, array $shops): void
    {
        // [shop index, supplier key, days ago, paid ratio (null = draft), lines]
        // line = [product name, variant, cases, extra bottles, price per case]
        $plan = [
            [0, 'Akij', 40, 1.0,  [['Mojo', '250ml', 5, 0, 620], ['Speed Energy Drink', '250ml', 3, 0, 900]]],
            [1, 'Akij', 38, 0.5,  [['Mojo', '500ml', 4, 6, 760]]],
            [2, 'Coke', 35, 1.0,  [['Coca-Cola', '250ml', 6, 0, 680], ['Sprite', '500ml', 3, 0, 820]]],
            [3, 'Pran', 32, 0.0,  [['Pran Up', '250ml', 5, 0, 600], ['Pran Drinking Water', '500ml', 8, 0, 320]]],
            [4, 'Pran', 28, 1.0,  [['Pran Mango Juice', '250ml', 4, 12, 660]]],
            [5, 'Coke', 25, 0.6,  [['Fanta Orange', '500ml', 3, 0, 800], ['Coca-Cola', '500ml', 4, 0, 840]]],
            [6, 'Akij', 21, 1.0,  [['Frutika Mango', '250ml', 3, 0, 700]]],
            [7, 'Pran', 18, 0.4,  [['Pran Drinking Water', '1L', 6, 0, 470]]],
            [0, 'Coke', 14, 1.0,  [['Coca-Cola', '250ml', 4, 12, 680]]],
            [2, 'Akij', 10, 0.7,  [['Mojo', '250ml', 6, 0, 620], ['Mojo', '500ml', 2, 0, 760]]],
            [4, 'Pran',  7, 0.0,  [['Pran Up', '500ml', 5, 0, 740]]],
            [6, 'Coke',  4, 1.0,  [['Sprite', '500ml', 4, 6, 820]]],

            // Drafts: nothing is deducted from stock until they are confirmed.
            [1, 'Akij',  3, null, [['Mojo', '250ml', 4, 0, 620], ['Speed Energy Drink', '250ml', 2, 0, 900]]],
            [5, 'Pran',  2, null, [['Pran Drinking Water', '500ml', 10, 0, 320]]],
            [7, 'Coke',  1, null, [['Coca-Cola', '500ml', 3, 6, 840]]],
        ];

        foreach ($plan as [$shopIndex, $supplierKey, $daysAgo, $paidRatio, $lines]) {
            $supplier = $suppliers[$supplierKey];
            $shop     = $shops[$shopIndex];
            $isDraft  = $paidRatio === null;
            $items    = [];

            foreach ($lines as [$productName, $variant, $cases, $extra, $pricePerCase]) {
                $batch = Product::where('name', $productName)
                    ->where('supplier_id', $supplier->id)
                    ->whereNotNull('metadata')
                    ->orderBy('date')
                    ->first();

                if (! $batch) {
                    continue;
                }

                $meta = collect($batch->metadata['variants'] ?? [])->firstWhere('variant', $variant);
                if (! $meta) {
                    continue;
                }

                $bpc  = (int) ($meta['bottles_per_case'] ?? 0);
                $free = (int) ($meta['free_bottles_per_case'] ?? 0);

                // Mirrors what CreateSale.vue sends: free bottles are included, so
                // the case price is spread across paid + free bottles.
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

    private function seedExpenses(): void
    {
        $rows = [
            ['Delivery van fuel', 'Fuel', 4800, 30],
            ['Warehouse rent', 'Rent', 25000, 28],
            ['Driver salary', 'Salary', 18000, 28],
            ['Van servicing', 'Maintenance', 6500, 21],
            ['Loading labour', 'Labour', 3200, 14],
            ['Mobile recharge', 'Utility', 1500, 7],
            ['Delivery van fuel', 'Fuel', 5100, 3],
        ];

        foreach ($rows as [$reason, $category, $amount, $daysAgo]) {
            Expense::create([
                'reason'       => $reason,
                'category'     => $category,
                'description'  => $reason . ' - seeded sample',
                'amount'       => $amount,
                'expense_date' => now()->subDays($daysAgo)->toDateString(),
            ]);
        }
    }

    // ── Plumbing ─────────────────────────────────────────────────────────────

    /**
     * Invoke a controller action with a manufactured request. Console has no
     * current request, so one is bound for helpers like redirect() and route().
     */
    private function callController(string $class, string $method, string $uri, array $payload, array $extraArgs = []): void
    {
        $request = Request::create($uri, 'POST', $payload);
        app()->instance('request', $request);

        app()->make($class)->{$method}($request, ...$extraArgs);
    }

    private function report(): void
    {
        $counts = [
            'suppliers' => Supplier::count(),
            'shops'     => Shop::count(),
            'catalogs'  => ProductCatalog::count(),
            'batches'   => Product::count(),
            'sales'     => Sale::where('status', '!=', 'draft')->count(),
            'drafts'    => Sale::where('status', 'draft')->count(),
            'expenses'  => Expense::count(),
        ];

        foreach ($counts as $label => $count) {
            $this->command?->getOutput()->writeln(sprintf('  <info>%-10s</info> %d', $label, $count));
        }
    }
}
