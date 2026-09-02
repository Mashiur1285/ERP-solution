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
use Illuminate\Support\Facades\DB;

/**
 * Wipes the business data and lays down one deliberate dataset that puts every
 * awkward inventory case on screen at once, so the numbers can be checked by eye
 * against the dashboard, the product list and the inventory report.
 *
 * Users, roles and permissions are left alone - wiping those would lock you out.
 *
 * Run with:  php artisan db:seed --class=FreshDemoSeeder
 */
class FreshDemoSeeder extends Seeder
{
    /** Business tables only. Auth tables are deliberately absent. */
    private const WIPE = [
        'sale_items', 'sales', 'payments',
        'lift_items', 'lifts',
        'products', 'product_catalog',
        'deposits', 'shops', 'suppliers',
        'categories', 'brands', 'expenses',
    ];

    private array $suppliers = [];
    private array $shops = [];
    private array $catalogs = [];

    public function run(): void
    {
        app()->instance(SmsService::class, new class extends SmsService {
            public function send(string $mobile, string $message): bool
            {
                return false;
            }
        });

        DB::statement('TRUNCATE TABLE ' . implode(', ', self::WIPE) . ' RESTART IDENTITY CASCADE');
        $this->say('wiped business data (users, roles and permissions kept)');

        $this->seedReference();
        $this->seedLifts();
        $this->seedSales();
        $this->seedExpenses();
        $this->verify();
    }

    // ── Reference data ───────────────────────────────────────────────────────

    private function seedReference(): void
    {
        $categories = [];
        foreach (['Soft Drinks', 'Juice', 'Drinking Water', 'Energy Drink'] as $name) {
            $categories[$name] = Category::create(['name' => $name, 'description' => $name]);
        }

        $brands = [];
        foreach (['Mojo', 'Speed', 'Frutika', 'Pran', 'Mum', 'Coca-Cola', 'Sprite', 'Fanta', 'Globe', 'Partex'] as $name) {
            $brands[$name] = Brand::create(['brand_name' => $name, 'description' => $name]);
        }

        $supplierRows = [
            'Akij'   => ['Akij Food & Beverage Ltd.', '01711000001', 500_000],
            'Pran'   => ['PRAN Beverage Ltd.', '01711000002', 500_000],
            'Globe'  => ['Globe Soft Drinks Ltd.', '01711000003', 300_000],
            'Partex' => ['Partex Beverage Ltd.', '01711000004', 200_000],
        ];

        foreach ($supplierRows as $key => [$company, $phone, $deposit]) {
            $supplier = Supplier::create([
                'company_name' => $company,
                'branch_name'  => 'Dhaka Depot',
                'phone_number' => $phone,
                'address'      => 'Dhaka',
                'city'         => 'Dhaka',
                'country'      => 'Bangladesh',
            ]);

            Deposit::create([
                'supplier_id'       => $supplier->id,
                'balance_deposited' => $deposit,
                'balance_remaining' => $deposit,
                'deposit_date'      => now()->subDays(60)->toDateString(),
                'is_used'           => false,
            ]);

            $this->suppliers[$key] = $supplier;
        }

        $shopRows = [
            ['Bismillah Store', 'Karim Mia', 'Mirpur Road'],
            ['Rahim General Store', 'Rahim Uddin', 'Mirpur Road'],
            ['Nasir Enterprise', 'Nasir Ahmed', 'Gulshan Avenue'],
            ['New Model Store', 'Jashim Uddin', 'Gulshan Avenue'],
            ['Barkat Store', 'Barkat Ullah', 'Banasree Main Road'],
            ['Prio Shop', 'Nasrin Akter', 'Banasree Main Road'],
        ];

        foreach ($shopRows as $i => [$name, $owner, $road]) {
            $this->shops[$i] = Shop::create([
                'shop_name'    => $name,
                'owner_name'   => $owner,
                'road'         => $road,
                'phone_number' => '018110000' . ($i + 10),
                'shop_address' => $road . ', Dhaka',
            ]);
        }

        // [key, name, supplier, category, brand]
        $catalogRows = [
            // Two suppliers carrying the same product name - their stock must
            // never be added together on any screen.
            ['coke_akij',  'Coca-Cola',           'Akij',   'Soft Drinks',    'Coca-Cola'],
            ['coke_globe', 'Coca-Cola',           'Globe',  'Soft Drinks',    'Coca-Cola'],

            ['mojo',       'Mojo',                'Akij',   'Soft Drinks',    'Mojo'],
            ['speed',      'Speed Energy Drink',  'Akij',   'Energy Drink',   'Speed'],
            ['frutika',    'Frutika Mango',       'Akij',   'Juice',          'Frutika'],
            ['fanta',      'Fanta Orange',        'Akij',   'Soft Drinks',    'Fanta'],
            ['sprite',     'Sprite',              'Akij',   'Soft Drinks',    'Sprite'],

            ['pranup',     'Pran Up',             'Pran',   'Soft Drinks',    'Pran'],
            ['pranwater',  'Pran Drinking Water', 'Pran',   'Drinking Water', 'Mum'],

            ['lemon',      'Globe Lemon',         'Globe',  'Soft Drinks',    'Globe'],
            ['cola330',    'Partex Cola',         'Partex', 'Soft Drinks',    'Partex'],
            ['water5l',    'Partex Mineral Water','Partex', 'Drinking Water', 'Partex'],
        ];

        foreach ($catalogRows as [$key, $name, $supplierKey, $category, $brand]) {
            $this->catalogs[$key] = ProductCatalog::create([
                'name'             => $name,
                'supplier_id'      => $this->suppliers[$supplierKey]->id,
                'category_id'      => $categories[$category]->id,
                'brand_id'         => $brands[$brand]->id,
                'is_active'        => true,
                'default_variants' => [],
            ]);
        }

        $this->say(sprintf('%d suppliers, %d shops, %d products',
            count($this->suppliers), count($this->shops), count($this->catalogs)));
    }

    // ── Lifts ────────────────────────────────────────────────────────────────

    private function lift(string $catalogKey, int $daysAgo, array $variants): void
    {
        $catalog = $this->catalogs[$catalogKey];

        $this->invokeController(LiftController::class, 'store', '/lifts/store', [
            'supplier_id' => $catalog->supplier_id,
            'lift_date'   => now()->subDays($daysAgo)->toDateString(),
            'notes'       => 'Demo lift',
            'items'       => [[
                'product_catalog_id' => $catalog->id,
                'product_name'       => $catalog->name,
                'variants'           => array_map(fn ($v) => [
                    'variant'               => $v[0],
                    'number_of_cases'       => $v[1],
                    'case_buying_price'     => $v[2],
                    'bottles_per_case'      => $v[3],
                    'free_bottles_per_case' => $v[4],
                ], $variants),
            ]],
        ]);
    }

    private function seedLifts(): void
    {
        // Mojo: the same variant lifted twice at different prices, so a sale that
        // crosses the boundary is charged part at each.
        $this->lift('mojo', 40, [['250ml', 10, 300, 24, 0]]);
        $this->lift('mojo', 15, [['250ml', 10, 500, 24, 0]]);

        // Speed: 30 free bottles against a 24-bottle case - more than a whole
        // case of freebies, which is where the stock maths is easiest to get wrong.
        $this->lift('speed', 35, [['250ml', 30, 720, 24, 1]]);

        // Pran Up carries free bottles; sales below leave some of them behind.
        $this->lift('pranup', 30, [['250ml', 20, 480, 24, 2]]);

        // One product, three variants, three different case sizes.
        $this->lift('pranwater', 28, [
            ['500ml', 40, 240, 24, 0],
            ['1L',    25, 360, 12, 0],
            ['5L',    10, 500,  4, 0],
        ]);

        // Same name, two suppliers, different quantities.
        $this->lift('coke_akij', 26, [['250ml', 20, 520, 24, 1]]);
        $this->lift('coke_globe', 20, [['250ml', 8, 540, 24, 1]]);

        // Custom sizes that are not one of the four presets.
        $this->lift('lemon', 22, [['550ml', 25, 640, 18, 0]]);
        $this->lift('cola330', 18, [['330ml', 30, 520, 20, 2]]);
        $this->lift('water5l', 12, [['5L', 20, 460, 4, 0]]);

        // Lifted and never touched - should read full on every screen.
        $this->lift('frutika', 10, [['250ml', 15, 540, 24, 0]]);

        // Lifted, then sold out completely further down.
        $this->lift('fanta', 8, [['500ml', 5, 640, 12, 0]]);

        // Note: 'sprite' is never lifted. It must show zero everywhere, not vanish.

        $this->say(sprintf('%d lifts, %d product batches',
            \App\Models\Lift::count(), Product::count()));
    }

    // ── Sales ────────────────────────────────────────────────────────────────

    /**
     * @param  array  $lines  [catalog key, variant, cases, extra bottles, price per case]
     */
    private function sale(
        int $shopIndex,
        string $supplierKey,
        int $daysAgo,
        array $lines,
        ?float $paidRatio = 1.0,
        bool $includeFree = true
    ): void {
        $supplier = $this->suppliers[$supplierKey];
        $shop     = $this->shops[$shopIndex];
        $items    = [];

        foreach ($lines as [$catalogKey, $variant, $cases, $extra, $pricePerCase]) {
            $catalog = $this->catalogs[$catalogKey];

            $batch = Product::where('product_catalog_id', $catalog->id)
                ->whereNotNull('metadata')
                ->orderBy('date')
                ->get()
                ->first(fn ($p) => collect($p->metadata['variants'] ?? [])
                    ->contains(fn ($v) => ($v['variant'] ?? null) === $variant));

            if (! $batch) {
                continue;
            }

            $meta = collect($batch->metadata['variants'])->firstWhere('variant', $variant);
            $bpc  = (int) ($meta['bottles_per_case'] ?? 0);
            $free = (int) ($meta['free_bottles_per_case'] ?? 0);

            // Mirrors what the sale screen sends: the case price is spread over the
            // bottles a case actually hands over.
            $pricingBpc = $includeFree ? $bpc + $free : $bpc;
            $bottles    = $includeFree ? ($cases * ($bpc + $free)) + $extra : ($cases * $bpc) + $extra;

            if ($bottles <= 0) {
                continue;
            }

            $items[] = [
                'product_id'               => $batch->id,
                'variant'                  => $variant,
                'cases_sold'               => $cases,
                'extra_bottles'            => $extra,
                'total_bottles_to_sell'    => $bottles,
                'selling_price_per_bottle' => $pricingBpc > 0 ? $pricePerCase / $pricingBpc : 0,
                'free_bottles_per_case'    => $free,
            ];
        }

        if (empty($items)) {
            return;
        }

        // include_free_bottles is required and legitimately false, so the payload
        // is built explicitly rather than filtered - stripping falses drops it.
        $payload = [
            'shop_id'              => $shop->id,
            'supplier_id'          => $supplier->id,
            'sale_date'            => now()->subDays($daysAgo)->toDateString(),
            'include_free_bottles' => $includeFree,
            'items'                => $items,
        ];

        if ($paidRatio === null) {
            $payload['save_as_draft'] = true;
        }

        $this->invokeController(SalesController::class, 'store', '/sales/store', $payload);

        if ($paidRatio === null || $paidRatio <= 0) {
            return;
        }

        $sale = Sale::where('shop_id', $shop->id)
            ->where('supplier_id', $supplier->id)
            ->orderByDesc('id')
            ->first();

        if ($sale) {
            $this->invokeController(SalesController::class, 'storePayment', '/sales/payment/store/' . $sale->id, [
                'payment_amount' => round((float) $sale->total_amount * $paidRatio, 2),
                'payment_method' => $paidRatio >= 1.0 ? 'cash' : 'bkash',
            ], [$sale->id]);
        }
    }

    private function seedSales(): void
    {
        // Mojo: 5 cases out of the cheap batch, then 12 cases which cross into the
        // dearer one - the FIFO costing split.
        $this->sale(0, 'Akij', 30, [['mojo', '250ml', 5, 0, 620]], 1.0);
        $this->sale(1, 'Akij', 12, [['mojo', '250ml', 12, 0, 640]], 0.5);

        // Pran Up sold WITHOUT its free bottles twice, so free stock piles up.
        $this->sale(2, 'Pran', 27, [['pranup', '250ml', 5, 0, 600]], 1.0, false);
        $this->sale(3, 'Pran', 20, [['pranup', '250ml', 4, 0, 600]], 0.0, false);

        // ... and once WITH them, so both paths are represented.
        $this->sale(4, 'Pran', 14, [['pranup', '250ml', 3, 0, 600]], 1.0, true);

        // Extra bottles only, no whole case.
        $this->sale(5, 'Pran', 21, [['pranwater', '500ml', 0, 17, 320]], 1.0);

        // Cases plus loose bottles, and two variants on one invoice.
        $this->sale(0, 'Pran', 9, [
            ['pranwater', '500ml', 6, 5, 320],
            ['pranwater', '1L',    4, 0, 470],
        ], 0.35);

        // Same product name, different suppliers, on the same day.
        $this->sale(2, 'Akij',  7, [['coke_akij', '250ml', 6, 0, 680]], 1.0);
        $this->sale(2, 'Globe', 7, [['coke_globe', '250ml', 3, 0, 700]], 0.0);

        // Custom sizes.
        $this->sale(3, 'Globe', 6, [['lemon', '550ml', 8, 0, 820]], 0.6);
        $this->sale(4, 'Partex', 5, [['cola330', '330ml', 7, 0, 700]], 1.0);
        $this->sale(5, 'Partex', 4, [['water5l', '5L', 6, 2, 620]], 0.0);

        // Fanta: sold out to the last bottle - every screen must read zero.
        $this->sale(1, 'Akij', 3, [['fanta', '500ml', 5, 0, 800]], 1.0);

        // Today, so the reports' default "Today" filter is not empty.
        $this->sale(0, 'Akij',  0, [['speed', '250ml', 4, 0, 900]], 1.0);
        $this->sale(2, 'Pran',  0, [['pranwater', '1L', 5, 0, 470]], 0.4);
        $this->sale(4, 'Globe', 0, [['lemon', '550ml', 3, 6, 820]], 0.0);

        // Drafts: nothing may move for these.
        $this->sale(1, 'Akij',  2, [['mojo', '250ml', 3, 0, 640]], null);
        $this->sale(3, 'Partex', 1, [['cola330', '330ml', 5, 0, 700]], null);
        $this->sale(5, 'Pran',  0, [['pranwater', '5L', 2, 0, 700]], null);

        $this->say(sprintf('%d sales (%d drafts)',
            Sale::where('status', '!=', 'draft')->count(),
            Sale::where('status', 'draft')->count()));
    }

    private function seedExpenses(): void
    {
        $rows = [
            ['Delivery van fuel', 'Fuel', 4800, 25],
            ['Warehouse rent', 'Rent', 25000, 20],
            ['Driver salary', 'Salary', 18000, 20],
            ['Van servicing', 'Maintenance', 6500, 11],
            ['Loading labour', 'Labour', 3200, 6],
            ['Mobile recharge', 'Utility', 1500, 2],
            ['Delivery van fuel', 'Fuel', 5100, 0],
        ];

        foreach ($rows as [$reason, $category, $amount, $daysAgo]) {
            Expense::create([
                'reason'       => $reason,
                'category'     => $category,
                'description'  => $reason,
                'amount'       => $amount,
                'expense_date' => now()->subDays($daysAgo)->toDateString(),
            ]);
        }

        $this->say(sprintf('%d expenses', Expense::count()));
    }

    // ── Check ────────────────────────────────────────────────────────────────

    /** Cost paid in must equal cost charged to sales plus cost still on the shelf. */
    private function verify(): void
    {
        $lifted = round((float) \App\Models\Lift::where('status', 'completed')->sum('total_amount'), 2);

        $charged = round(\App\Models\SaleItem::whereHas('sale', fn ($q) => $q->where('status', '!=', 'draft'))
            ->get()
            ->sum(fn ($i) => (float) $i->total_price - (float) $i->profit), 2);

        $onShelf = round(app(\App\Contracts\ProductPurchaseContract::class)
            ->getInventoryStock()
            ->sum('total_stock_value'), 2);

        $this->say('');
        $this->say(sprintf('lifted %12s', number_format($lifted, 2)));
        $this->say(sprintf('  charged to sales %12s', number_format($charged, 2)));
        $this->say(sprintf('  still on shelf   %12s', number_format($onShelf, 2)));
        $this->say(sprintf('  sum              %12s  %s',
            number_format($charged + $onShelf, 2),
            abs(($charged + $onShelf) - $lifted) < 1.0 ? 'BALANCED' : 'OUT BY ' . number_format($charged + $onShelf - $lifted, 2)));
    }

    // ── Plumbing ─────────────────────────────────────────────────────────────

    private function invokeController(string $class, string $method, string $uri, array $payload, array $extra = []): void
    {
        $request = Request::create($uri, 'POST', $payload);
        app()->instance('request', $request);

        app()->make($class)->{$method}($request, ...$extra);
    }

    private function say(string $line): void
    {
        $this->command?->getOutput()->writeln('  <info>' . $line . '</info>');
    }
}
