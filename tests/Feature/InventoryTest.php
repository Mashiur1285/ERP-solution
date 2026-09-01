<?php

use App\Models\Deposit;
use App\Models\Product;
use App\Models\ProductCatalog;
use App\Models\Shop;
use App\Models\Supplier;
use App\Models\User;
use Spatie\Permission\Models\Permission;

// ── Helpers ──────────────────────────────────────────────────────────────────

function seedPermissions(): void
{
    $names = [
        'lift.add', 'lift.view', 'lift.update',
        'sales.add', 'sales.view', 'sales.update',
        'inventory.view',
        'deposit.add', 'deposit.view', 'deposit.update',
    ];
    foreach ($names as $name) {
        Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
    }
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
}

function makeUser(array $permissions = []): User
{
    $user = User::factory()->create();
    $user->givePermissionTo($permissions);
    return $user;
}

function makeSupplier(): Supplier
{
    static $n = 0;
    return Supplier::create([
        'company_name' => 'Supplier ' . ++$n,
        'phone_number' => '01700' . str_pad((string) $n, 6, '0', STR_PAD_LEFT),
        'address'      => 'Test Address',
    ]);
}

function makeShop(): Shop
{
    static $s = 0;
    return Shop::create([
        'shop_name'    => 'Shop ' . ++$s,
        'phone_number' => '01800' . str_pad((string) $s, 6, '0', STR_PAD_LEFT),
    ]);
}

function makeCatalog(Supplier $supplier, ?string $productName = null): ProductCatalog
{
    return ProductCatalog::create([
        'name'             => $productName ?? 'Product-' . uniqid(),
        'supplier_id'      => $supplier->id,
        'is_active'        => true,
        'default_variants' => [],
    ]);
}

function seedDeposit(Supplier $supplier, float $amount = 999_999): Deposit
{
    return Deposit::create([
        'supplier_id'       => $supplier->id,
        'balance_deposited'  => $amount,
        'balance_remaining'  => $amount,
        'deposit_date'       => now()->toDateString(),
        'is_used'            => false,
    ]);
}

function liftPayload(Supplier $supplier, ProductCatalog $catalog, array $variantOverrides = []): array
{
    $variant = array_merge([
        'variant'              => '500ml',
        'number_of_cases'      => 10,
        'case_buying_price'    => 240,
        'bottles_per_case'     => 24,
        'free_bottles_per_case' => 0,
    ], $variantOverrides);

    return [
        'supplier_id' => $supplier->id,
        'lift_date'   => now()->toDateString(),
        'items'       => [[
            'product_catalog_id' => $catalog->id,
            'product_name'       => $catalog->name,
            'variants'           => [$variant],
        ]],
    ];
}

function salePayload(Supplier $supplier, Shop $shop, Product $product, array $itemOverrides = []): array
{
    $item = array_merge([
        'product_id'              => $product->id,
        'variant'                 => '500ml',
        'cases_sold'              => 2,
        'extra_bottles'           => 0,
        'total_bottles_to_sell'   => 48,
        'selling_price_per_bottle' => 15,
        'free_bottles_per_case'   => 0,
    ], $itemOverrides);

    return [
        'shop_id'              => $shop->id,
        'supplier_id'          => $supplier->id,
        'sale_date'            => now()->toDateString(),
        'include_free_bottles' => false,
        'items'                => [$item],
    ];
}

function salesOf(Supplier $supplier): \Illuminate\Database\Eloquent\Builder
{
    return \App\Models\Sale::where('supplier_id', $supplier->id);
}

function getProduct(Supplier $supplier): Product
{
    return Product::where('supplier_id', $supplier->id)->firstOrFail();
}

function getVariant(Product $product, string $variant = '500ml'): array
{
    return collect($product->fresh()->metadata['variants'])->firstWhere('variant', $variant);
}

// ── T01–T06: Lift / Purchase creation ───────────────────────────────────────

it('T01: lift creates a product record with correct purchased quantity', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add']);

    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);
    $variant = getVariant($product);

    // 10 cases × 24 bpc = 240
    expect($variant['current_purchased_quantity'])->toBe(240);
});

it('T02: lift sets current_free_quantity to zero when no free bottles', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add']);

    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $variant = getVariant(getProduct($supplier));
    expect((int) $variant['current_free_quantity'])->toBe(0);
});

it('T03: lift with free bottles sets correct current_free_quantity', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add']);

    // 10 cases × 2 free/case = 20 free bottles
    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['free_bottles_per_case' => 2]))
         ->assertRedirect();

    $variant = getVariant(getProduct($supplier));
    expect((int) $variant['current_free_quantity'])->toBe(20);
});

it('T04: lift stores case_buying_price in metadata', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add']);

    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['case_buying_price' => 360]))
         ->assertRedirect();

    $variant = getVariant(getProduct($supplier));
    expect((float) $variant['case_buying_price'])->toBe(360.0);
});

it('T05: lift total_cost equals number_of_cases times case_buying_price', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add']);

    // 10 cases × 240 = 2400
    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $variant = getVariant(getProduct($supplier));
    expect((float) $variant['total_cost'])->toBe(2400.0);
});

it('T06: draft lift does not create a product record', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add']);

    $payload                = liftPayload($supplier, $catalog);
    $payload['save_as_draft'] = true;

    $this->actingAs($user)
         ->post(route('lifts.store'), $payload)
         ->assertRedirect();

    expect(Product::count())->toBe(0);
});

// ── T07–T11: Sale – inventory deduction ─────────────────────────────────────

it('T07: sale deducts purchased bottles from metadata', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Sell 2 cases = 48 bottles
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 2,
             'total_bottles_to_sell' => 48,
         ]))
         ->assertRedirect();

    // 240 - 48 = 192
    $variant = getVariant($product);
    expect($variant['current_purchased_quantity'])->toBe(192);
});

it('T08: sale without include_free_bottles does not touch free quantity', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['free_bottles_per_case' => 2]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // 2 cases sold without free bottle inclusion
    $payload                       = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 2,
        'total_bottles_to_sell' => 48,
        'free_bottles_per_case' => 2,
    ]);
    $payload['include_free_bottles'] = false;

    $this->actingAs($saleUser)
         ->post(route('sales.store'), $payload)
         ->assertRedirect();

    $variant = getVariant($product);
    expect((int) $variant['current_free_quantity'])->toBe(20); // untouched
});

it('T09: sale with include_free_bottles deducts from both purchased and free', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['free_bottles_per_case' => 2]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // include_free_bottles=true, 2 cases sold
    // purchasedSold = 2 × 24 = 48, freeSold = 2 × 2 = 4
    $payload = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 2,
        'total_bottles_to_sell' => 52,
        'free_bottles_per_case' => 2,
    ]);
    $payload['include_free_bottles'] = true;

    $this->actingAs($saleUser)
         ->post(route('sales.store'), $payload)
         ->assertRedirect();

    // FIFO deducts totalToDeduct (48+4=52) from purchased pool first, then free.
    // Since purchased pool (240) has enough, all 52 come from purchased; free is untouched.
    $variant = getVariant($product);
    expect($variant['current_purchased_quantity'])->toBe(188); // 240 - 52
    expect((int) $variant['current_free_quantity'])->toBe(20); // free pool untouched
});

it('T10: sale with extra bottles deducts extra from purchased quantity', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    // 1 case + 5 extra = 29 bottles sold
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 1,
             'extra_bottles'         => 5,
             'total_bottles_to_sell' => 29,
         ]))
         ->assertRedirect();

    $variant = getVariant($product);
    expect($variant['current_purchased_quantity'])->toBe(211); // 240 - 29
});

it('T11: draft sale does not deduct inventory', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    $payload                = salePayload($supplier, $shop, $product);
    $payload['save_as_draft'] = true;

    $this->actingAs($saleUser)
         ->post(route('sales.store'), $payload)
         ->assertRedirect();

    $variant = getVariant($product);
    expect($variant['current_purchased_quantity'])->toBe(240); // unchanged
});

// ── T12–T14: FIFO multi-batch ────────────────────────────────────────────────

it('T12: two-batch sale deducts entirely from first batch when stock sufficient', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // Batch 1: yesterday — ensures FIFO picks it first
    $p1 = liftPayload($supplier, $catalog, ['number_of_cases' => 10]);
    $p1['lift_date'] = now()->subDay()->toDateString();
    $this->actingAs($liftUser)->post(route('lifts.store'), $p1)->assertRedirect();

    // Batch 2: today
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 5]))
         ->assertRedirect();

    $batches = Product::where('supplier_id', $supplier->id)->orderBy('id')->get();
    $batch1  = $batches->get(0);
    $batch2  = $batches->get(1);

    // Sell 3 cases = 72 bottles — fits entirely in batch 1 (older)
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $batch1, [
             'cases_sold'            => 3,
             'total_bottles_to_sell' => 72,
         ]))
         ->assertRedirect();

    expect(getVariant($batch1)['current_purchased_quantity'])->toBe(168); // 240 - 72
    expect(getVariant($batch2)['current_purchased_quantity'])->toBe(120); // untouched
});

it('T13: FIFO overflow deducts remainder from second batch', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // Batch 1: yesterday (FIFO picks it first), only 1 case = 24 bottles
    $p1 = liftPayload($supplier, $catalog, ['number_of_cases' => 1]);
    $p1['lift_date'] = now()->subDay()->toDateString();
    $this->actingAs($liftUser)->post(route('lifts.store'), $p1)->assertRedirect();

    // Batch 2: today, 10 cases = 240 bottles
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 10]))
         ->assertRedirect();

    $batches = Product::where('supplier_id', $supplier->id)->orderBy('id')->get();
    $batch1  = $batches->get(0);
    $batch2  = $batches->get(1);

    // Sell 2 cases = 48 bottles → batch1 exhausted (24), 24 spills to batch2
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $batch1, [
             'cases_sold'            => 2,
             'total_bottles_to_sell' => 48,
         ]))
         ->assertRedirect();

    expect(getVariant($batch1)['current_purchased_quantity'])->toBe(0);  // fully depleted
    expect(getVariant($batch2)['current_purchased_quantity'])->toBe(216); // 240 - 24
});

it('T14: selling entire stock leaves zero in metadata', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // 5 cases = 120 bottles
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 5]))
         ->assertRedirect();

    $product = getProduct($supplier);

    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 5,
             'total_bottles_to_sell' => 120,
         ]))
         ->assertRedirect();

    $variant = getVariant($product);
    expect($variant['current_purchased_quantity'])->toBe(0);
    expect((int) $variant['current_free_quantity'])->toBe(0);
});

// ── T15–T18: Profit calculation ──────────────────────────────────────────────

it('T15: profit stored is cases times selling minus cases times cost price, no rounding error', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // 10 cases, 24 bpc, cost 240 per case → actual_rate_per_bottle = 240/24 = 10.0 (exact)
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['case_buying_price' => 240]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Sell 3 cases at ৳15/bottle → revenue = 72 × 15 = 1080, cost = 3 × 240 = 720, profit = 360
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'               => 3,
             'total_bottles_to_sell'    => 72,
             'selling_price_per_bottle' => 15,
         ]))
         ->assertRedirect();

    $profit = \App\Models\SaleItem::latest('id')->first()->profit;
    expect((float) $profit)->toBe(360.0);
});

it('T16: profit has no floating-point error when case_buying_price does not divide evenly', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // 24 bpc, cost 250 per case → rate = 250/24 = 10.4167... (repeating)
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['case_buying_price' => 250]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Sell 6 cases at ৳15/bottle → revenue = 144 × 15 = 2160, cost = 6 × 250 = 1500, profit = 660
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'               => 6,
             'total_bottles_to_sell'    => 144,
             'selling_price_per_bottle' => 15,
         ]))
         ->assertRedirect();

    $profit = (float) \App\Models\SaleItem::latest('id')->first()->profit;
    expect($profit)->toBe(660.0);
});

it('T17: profit with extra bottles uses proportional cost for extras', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // 24 bpc, 0 free, cost 240 per case
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Sell 1 case + 6 extra bottles at ৳15/bottle
    // revenue = 30 × 15 = 450
    // cost: 1 × 240 + 6 × (240/24) = 240 + 60 = 300
    // profit = 150
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'               => 1,
             'extra_bottles'            => 6,
             'total_bottles_to_sell'    => 30,
             'selling_price_per_bottle' => 15,
         ]))
         ->assertRedirect();

    $profit = (float) \App\Models\SaleItem::latest('id')->first()->profit;
    expect($profit)->toBe(150.0);
});

it('T18: extra-only sale (0 cases) calculates profit correctly', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // 24 bpc, cost 240 per case → bottle rate = 10
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    // 0 cases, 5 extra bottles at ৳15/bottle
    // revenue = 5 × 15 = 75
    // cost = 0 × 240 + 5 × (240/24) = 50
    // profit = 25
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'               => 0,
             'extra_bottles'            => 5,
             'total_bottles_to_sell'    => 5,
             'selling_price_per_bottle' => 15,
         ]))
         ->assertRedirect();

    $profit = (float) \App\Models\SaleItem::latest('id')->first()->profit;
    expect($profit)->toBe(25.0);
});

it('T18a: selling a case WITHOUT its free bottles charges only the bottles sold, not the full case', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // 24 bpc + 6 free/case, cost 600/case → blended rate = 600 / (24+6) = 20.0 (exact)
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, [
             'case_buying_price'     => 600,
             'free_bottles_per_case' => 6,
         ]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Sell 1 case (24 purchased bottles) at ৳22/bottle, free bottles excluded.
    // revenue = 24 × 22 = 528
    // cost    = 24 × (600/30) = 24 × 20 = 480   (NOT the full case price 600)
    // profit  = 48   (the old per-case formula charged 600 and showed a ৳72 LOSS)
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'               => 1,
             'free_bottles_per_case'    => 6,
             'total_bottles_to_sell'    => 24,
             'selling_price_per_bottle' => 22,
         ]))
         ->assertRedirect();

    $profit = (float) \App\Models\SaleItem::latest('id')->first()->profit;
    expect($profit)->toBe(48.0);
});

it('T18b: selling a case WITH its free bottles still charges the full case cost (no regression)', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // Same lift: 24 bpc + 6 free/case, cost 600/case → blended rate = 20.0
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, [
             'case_buying_price'     => 600,
             'free_bottles_per_case' => 6,
         ]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Sell 1 effective case = 24 purchased + 6 free = 30 bottles at ৳22/bottle.
    // revenue = 30 × 22 = 660
    // cost    = 30 × 20 = 600   (full case cost, unchanged from old behaviour)
    // profit  = 60
    $payload = salePayload($supplier, $shop, $product, [
        'cases_sold'               => 1,
        'free_bottles_per_case'    => 6,
        'total_bottles_to_sell'    => 30,
        'selling_price_per_bottle' => 22,
    ]);
    $payload['include_free_bottles'] = true;

    $this->actingAs($saleUser)
         ->post(route('sales.store'), $payload)
         ->assertRedirect();

    $profit = (float) \App\Models\SaleItem::latest('id')->first()->profit;
    expect($profit)->toBe(60.0);
});

// ── T19–T21: Validation ──────────────────────────────────────────────────────

it('T19: sale with more bottles than available returns validation error', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // Only 5 cases = 120 bottles
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 5]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Try to sell 10 cases = 240 bottles (impossible)
    $response = $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 10,
             'total_bottles_to_sell' => 240,
         ]));

    $response->assertStatus(302); // redirects back with error
    // Inventory unchanged
    expect(getVariant($product)['current_purchased_quantity'])->toBe(120);
});

it('T20: lift with missing variant returns 422', function () {
    seedPermissions();
    $supplier = makeSupplier();
    $user     = makeUser(['lift.add']);

    $payload = [
        'supplier_id' => $supplier->id,
        'lift_date'   => now()->toDateString(),
        'items'       => [[
            'product_catalog_id' => 999999,   // non-existent
            'product_name'       => 'Ghost',
            'variants'           => [],        // empty — fails min:1
        ]],
    ];

    $this->actingAs($user)
         ->post(route('lifts.store'), $payload)
         ->assertStatus(302); // Laravel redirects back with validation errors
});

it('T21: sale with total_bottles_to_sell of zero returns 422', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'total_bottles_to_sell' => 0,
         ]))
         ->assertSessionHasErrors();
});

// ── T22–T23: Lift deletion ───────────────────────────────────────────────────

it('T22: deleting an unsold lift soft-deletes its product', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add', 'lift.update']);

    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $lift    = \App\Models\Lift::first();
    $product = getProduct($supplier);

    $this->actingAs($user)
         ->delete(route('lifts.destroy', $lift->id))
         ->assertRedirect();

    expect(Product::withTrashed()->find($product->id)->deleted_at)->not->toBeNull();
});

it('T23: deleting a lift with sold bottles returns an error response', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add', 'lift.update']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $lift    = \App\Models\Lift::first();
    $product = getProduct($supplier);

    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 2,
             'total_bottles_to_sell' => 48,
         ]))
         ->assertRedirect();

    $this->actingAs($liftUser)
         ->delete(route('lifts.destroy', $lift->id))
         ->assertSessionHas('error');
});

// ── T24–T26: Inventory queries ───────────────────────────────────────────────

it('T24: getVariantInventory returns reduced stock after sale', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 4,
             'total_bottles_to_sell' => 96,
         ]))
         ->assertRedirect();

    $repo   = app(\App\Contracts\ProductPurchaseContract::class);
    $result = $repo->getVariantInventory($product->id, '500ml');

    expect($result['purchased_bottles_available'])->toBe(144); // 240 - 96
});

it('T25: product list hides zero-stock products by default', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add', 'inventory.view']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 1]))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Sell all 24 bottles
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 1,
             'total_bottles_to_sell' => 24,
         ]))
         ->assertRedirect();

    // Soft-delete so it is truly "out of stock" (no active batches)
    $product->delete();

    $response = $this->actingAs($liftUser)->get(route('products.index'));
    $response->assertStatus(200);

    $products = $response->original->getData()['page']['props']['products']['data'] ?? [];
    $names    = array_column($products, 'name');
    expect($names)->not->toContain($catalog->name);
});

it('T26: product list shows zero-stock products when show_out_of_stock toggle is on', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add', 'inventory.view']);

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 1]))
         ->assertRedirect();

    $product = getProduct($supplier);
    $product->delete(); // soft-delete → "out of stock" batch

    $response = $this->actingAs($liftUser)
         ->get(route('products.index', ['show_out_of_stock' => 'true']));

    $response->assertStatus(200);
    $products = $response->original->getData()['page']['props']['products']['data'] ?? [];
    $names    = array_column($products, 'name');
    expect($names)->toContain($catalog->name);
});

// ── T27: Sale update ─────────────────────────────────────────────────────────

it('T27: updating a sale adjusts inventory to reflect the new quantity', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add', 'sales.update']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    // Original sale: 2 cases = 48 bottles
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 2,
             'total_bottles_to_sell' => 48,
         ]))
         ->assertRedirect();

    $sale = \App\Models\Sale::first();
    expect(getVariant($product)['current_purchased_quantity'])->toBe(192);

    // Update sale to 3 cases = 72 bottles
    $this->actingAs($saleUser)
         ->put(route('sales.update', $sale->id), array_merge(
             salePayload($supplier, $shop, $product, [
                 'cases_sold'            => 3,
                 'total_bottles_to_sell' => 72,
             ]),
             ['original_sale_id' => $sale->id]
         ))
         ->assertRedirect();

    expect(getVariant($product)['current_purchased_quantity'])->toBe(168); // 240 - 72
});

// ── T28–T30: Edge cases ──────────────────────────────────────────────────────

it('T28: two variants of the same product deduct independently', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $productName = 'Multi-Variant ' . uniqid();
    $catalog     = makeCatalog($supplier, $productName);
    $liftUser    = makeUser(['lift.add']);
    $saleUser    = makeUser(['sales.add']);
    $shop        = makeShop();

    // Each variant in a lift creates its own Product row in the `products` table.
    // Sending both variants in one lift creates Product #1 (330ml) and Product #2 (500ml).
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), [
             'supplier_id' => $supplier->id,
             'lift_date'   => now()->toDateString(),
             'items'       => [[
                 'product_catalog_id' => $catalog->id,
                 'product_name'       => $catalog->name,
                 'variants'           => [
                     ['variant' => '330ml', 'number_of_cases' => 10, 'case_buying_price' => 200, 'bottles_per_case' => 24, 'free_bottles_per_case' => 0],
                     ['variant' => '500ml', 'number_of_cases' => 10, 'case_buying_price' => 240, 'bottles_per_case' => 24, 'free_bottles_per_case' => 0],
                 ],
             ]],
         ])
         ->assertRedirect();

    $product330 = Product::where('supplier_id', $supplier->id)->orderBy('id')->first();
    $product500 = Product::where('supplier_id', $supplier->id)->orderBy('id')->skip(1)->first();

    // Sell 3 cases of 330ml only
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product330, [
             'variant'               => '330ml',
             'cases_sold'            => 3,
             'total_bottles_to_sell' => 72,
         ]))
         ->assertRedirect();

    // 330ml product: 240 - 72 = 168
    // 500ml product: 240, untouched
    $v330 = getVariant($product330, '330ml');
    $v500 = getVariant($product500, '500ml');

    expect($v330['current_purchased_quantity'])->toBe(168);
    expect($v500['current_purchased_quantity'])->toBe(240);
});

it('T29: lift actual_rate_per_bottle is total_cost divided by total_bottles', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $user    = makeUser(['lift.add']);

    // 10 cases, 24 bpc, 2 free/case → total bottles = 10×24 + 10×2 = 260
    // total cost = 10 × 300 = 3000 → rate = 3000/260 ≈ 11.5385
    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, [
             'number_of_cases'       => 10,
             'case_buying_price'     => 300,
             'bottles_per_case'      => 24,
             'free_bottles_per_case' => 2,
         ]))
         ->assertRedirect();

    $variant = getVariant(getProduct($supplier));
    $expected = round(3000 / 260, 4);
    expect((float) $variant['actual_rate_per_bottle'])->toBe($expected);
});

it('T30: inventory report returns correct aggregated available bottles per product', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    // Two batches: 5 + 5 = 10 cases, 240 bottles total
    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 5]))
         ->assertRedirect();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, ['number_of_cases' => 5]))
         ->assertRedirect();

    $product = Product::orderBy('id')->first();

    // Sell 3 cases = 72 bottles
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 3,
             'total_bottles_to_sell' => 72,
         ]))
         ->assertRedirect();

    $repo   = app(\App\Contracts\ProductPurchaseContract::class);
    $stock  = $repo->getInventoryStock();
    $entry  = $stock->firstWhere('product_name', $catalog->name);

    // 240 total - 72 sold = 168 available
    expect((int) $entry['total_available_bottles'])->toBe(168);
});

// ── T31–T35: Draft sales – editing a draft must update it, not fork a new one ─

it('T31: saving a draft again with draft_id updates that draft instead of creating a new one', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    // First save: 2 cases as a draft
    $payload                   = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 2,
        'total_bottles_to_sell' => 48,
    ]);
    $payload['save_as_draft'] = true;

    $this->actingAs($saleUser)->post(route('sales.store'), $payload)->assertRedirect();

    $draft   = salesOf($supplier)->where('status', 'draft')->firstOrFail();
    $invoice = $draft->invoice_number;

    // Re-open that draft, change it to 3 cases, save again
    $update                    = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 3,
        'total_bottles_to_sell' => 72,
    ]);
    $update['save_as_draft'] = true;
    $update['draft_id']      = $draft->id;

    $this->actingAs($saleUser)->post(route('sales.store'), $update)->assertRedirect();

    expect(salesOf($supplier)->count())->toBe(1);

    $draft = $draft->fresh();
    expect($draft->status)->toBe('draft')
        ->and($draft->invoice_number)->toBe($invoice)   // same invoice, same draft
        ->and((float) $draft->total_amount)->toBe(1080.0); // 72 × 15

    // Items are replaced, never appended
    expect($draft->items)->toHaveCount(1)
        ->and($draft->items->first()->cases_sold)->toBe(3);

    // Still a draft, so still no stock movement
    expect(getVariant($product)['current_purchased_quantity'])->toBe(240);
});

it('T32: opening a draft through the edit route redirects into the draft flow', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add', 'sales.update']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $payload                   = salePayload($supplier, $shop, getProduct($supplier));
    $payload['save_as_draft'] = true;

    $this->actingAs($saleUser)->post(route('sales.store'), $payload)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();

    // Edit must land on the same screen "Continue Sale" uses, which posts back
    // with draft_id and therefore updates this row.
    $this->actingAs($saleUser)
         ->get(route('sales.edit', $draft->id))
         ->assertRedirect(route('sales.index', ['draft' => $draft->id]));
});

it('T33: confirming a draft updates the same sale row and deducts stock once', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    $payload                   = salePayload($supplier, $shop, $product);
    $payload['save_as_draft'] = true;

    $this->actingAs($saleUser)->post(route('sales.store'), $payload)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();

    // Confirm it: same payload, no save_as_draft, carrying the draft id
    $confirm             = salePayload($supplier, $shop, $product);
    $confirm['draft_id'] = $draft->id;

    $this->actingAs($saleUser)->post(route('sales.store'), $confirm)->assertRedirect();

    expect(salesOf($supplier)->count())->toBe(1)
        ->and($draft->fresh()->status)->toBe('in_progress');

    // 240 - 48, deducted exactly once
    expect(getVariant($product)['current_purchased_quantity'])->toBe(192);
});

it('T34: a draft_id pointing at an already-confirmed sale is rejected', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product))
         ->assertRedirect();

    $confirmed = salesOf($supplier)->firstOrFail();
    expect($confirmed->status)->toBe('in_progress');

    // A stale draft id must not be allowed to rewrite a real sale's items,
    // which would silently strand the stock it already deducted.
    $stale             = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 5,
        'total_bottles_to_sell' => 120,
    ]);
    $stale['draft_id'] = $confirmed->id;

    $this->actingAs($saleUser)
         ->post(route('sales.store'), $stale)
         ->assertSessionHasErrors('draft_id');

    expect(salesOf($supplier)->count())->toBe(1)
        ->and($confirmed->fresh()->items->first()->cases_sold)->toBe(2)
        ->and(getVariant($product)['current_purchased_quantity'])->toBe(192);
});

it('T35: updating a draft does not hand back stock the draft never took', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add', 'sales.update']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $product = getProduct($supplier);

    // A real sale first, so the batch has headroom a wrong restore could fill.
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 2,
             'total_bottles_to_sell' => 48,
         ]))
         ->assertRedirect();

    expect(getVariant($product)['current_purchased_quantity'])->toBe(192);

    // Now a draft for 1 case, which deducts nothing.
    $payload                   = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 1,
        'total_bottles_to_sell' => 24,
    ]);
    $payload['save_as_draft'] = true;

    $this->actingAs($saleUser)->post(route('sales.store'), $payload)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();

    $this->actingAs($saleUser)
         ->put(route('sales.update', $draft->id), salePayload($supplier, $shop, $product, [
             'cases_sold'            => 1,
             'total_bottles_to_sell' => 24,
         ]))
         ->assertRedirect();

    // 192 - 24. Restoring the draft's 24 bottles first would leave 192.
    expect(getVariant($product)['current_purchased_quantity'])->toBe(168);
});

// ── T36: the sales report must carry what is still owed ─────────────────────

it('T36: sales report exposes paid and due amounts for a part-paid sale', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add', 'sales.update', 'sales.view']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    // 2 cases x 24 bottles x 15 = 720
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, getProduct($supplier)))
         ->assertRedirect();

    $sale = salesOf($supplier)->firstOrFail();
    expect((float) $sale->total_amount)->toBe(720.0);

    // Pay half, leaving 360 owed.
    $this->actingAs($saleUser)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 360,
        'payment_method' => 'cash',
    ]);

    $this->actingAs($saleUser)
         ->get(route('sales.report'))
         ->assertInertia(function (\Inertia\Testing\AssertableInertia $page) use ($sale) {
             $row = collect($page->toArray()['props']['sales'])
                 ->firstWhere('id', $sale->id);

             expect($row)->not->toBeNull()
                 ->and((float) $row['total_amount'])->toBe(720.0)
                 ->and((float) $row['paid_amount'])->toBe(360.0)
                 ->and((float) $row['due_amount'])->toBe(360.0)
                 ->and($row['status'])->toBe('in_progress');
         });
});

// ── T37: collecting the rest of a due later ─────────────────────────────────

it('T37: a second payment against the same sale clears the remaining due', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add', 'sales.update', 'sales.view']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, getProduct($supplier)))
         ->assertRedirect();

    $sale = salesOf($supplier)->firstOrFail();

    // Day one: shop pays 360 of 720.
    $this->actingAs($saleUser)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 360,
        'payment_method' => 'cash',
    ]);

    $sale->refresh();
    expect((float) $sale->due_amount)->toBe(360.0)
        ->and($sale->status)->toBe('in_progress');

    // The payment screen for this sale offers exactly the outstanding balance.
    $this->actingAs($saleUser)
         ->get(route('sales.payment', $sale->id))
         ->assertInertia(function (\Inertia\Testing\AssertableInertia $page) {
             $s = $page->toArray()['props']['sale'];
             expect((float) $s['total_amount'])->toBe(720.0)
                 ->and((float) $s['paid_amount'])->toBe(360.0)
                 ->and((float) $s['due_amount'])->toBe(360.0);
         });

    // Day two: the collector takes the remaining 360.
    $this->actingAs($saleUser)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 360,
        'payment_method' => 'cash',
    ]);

    $sale->refresh();
    expect((float) $sale->paid_amount)->toBe(720.0)
        ->and((float) $sale->due_amount)->toBe(0.0)
        ->and($sale->status)->toBe('completed')
        ->and($sale->is_paid)->toBeTrue();

    // Both collections are kept as separate rows, not overwritten.
    expect(\Illuminate\Support\Facades\DB::table('payments')->where('sale_id', $sale->id)->count())->toBe(2);
});

// ── T38: due collection reaches the payment screen ──────────────────────────

it('T38: the payment screen for a part-paid sale opens on the outstanding balance', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add', 'sales.update', 'sales.view']);
    $shop     = makeShop();

    $this->actingAs($liftUser)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect();

    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, getProduct($supplier)))
         ->assertRedirect();

    $sale = salesOf($supplier)->firstOrFail();

    $this->actingAs($saleUser)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 500,
        'payment_method' => 'cash',
    ]);

    // What the "Due Collection" button links to must render the sale with the
    // remaining 220 as the payable balance.
    $this->actingAs($saleUser)
         ->get(route('sales.payment', $sale->id))
         ->assertOk()
         ->assertInertia(function (\Inertia\Testing\AssertableInertia $page) {
             expect($page->toArray()['component'])->toBe('SalesManagement/SalesPayment');
             $s = $page->toArray()['props']['sale'];
             expect((float) $s['total_amount'] - (float) $s['paid_amount'])->toBe(220.0)
                 ->and((float) $s['due_amount'])->toBe(220.0);
         });

    // And collecting it there settles the sale.
    $this->actingAs($saleUser)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 220,
        'payment_method' => 'cash',
    ]);

    $sale->refresh();
    expect((float) $sale->due_amount)->toBe(0.0)
        ->and($sale->status)->toBe('completed');
});

// ═══════════════════════════════════════════════════════════════════════════
//  T39–T56: corner cases
// ═══════════════════════════════════════════════════════════════════════════

/** A lifted, sellable product plus the users and shop needed to sell it. */
function saleFixture(array $liftOverrides = []): array
{
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $saleUser = makeUser(['sales.add', 'sales.update', 'sales.view']);
    $shop     = makeShop();

    test()->actingAs($liftUser)
        ->post(route('lifts.store'), liftPayload($supplier, $catalog, $liftOverrides))
        ->assertRedirect();

    return [$supplier, $shop, getProduct($supplier), $saleUser];
}

// ── Payments ────────────────────────────────────────────────────────────────

it('T39: paying more than the total does not record more money than the sale is worth', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product))->assertRedirect();
    $sale = salesOf($supplier)->firstOrFail();

    // Sale is 720; someone fat-fingers 7200.
    $this->actingAs($user)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 7200,
        'payment_method' => 'cash',
    ]);

    $sale->refresh();
    expect((float) $sale->paid_amount)->toBeLessThanOrEqual(720.0)
        ->and((float) $sale->due_amount)->toBe(0.0);
});

it('T40: a zero payment leaves the sale exactly as it was', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product))->assertRedirect();
    $sale = salesOf($supplier)->firstOrFail();

    $this->actingAs($user)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 0,
        'payment_method' => 'cash',
    ]);

    $sale->refresh();
    expect((float) $sale->paid_amount)->toBe(0.0)
        ->and((float) $sale->due_amount)->toBe(720.0)
        ->and($sale->status)->toBe('in_progress');
});

it('T41: a negative payment can never increase what is owed', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product))->assertRedirect();
    $sale = salesOf($supplier)->firstOrFail();

    $this->actingAs($user)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => -500,
        'payment_method' => 'cash',
    ]);

    $sale->refresh();
    // Owing more than the sale is worth is never a valid state.
    expect((float) $sale->due_amount)->toBeLessThanOrEqual(720.0)
        ->and((float) $sale->paid_amount)->toBeGreaterThanOrEqual(0.0);
});

it('T42: uneven instalments settle a sale with no rounding drift', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    // 3 bottles at 33.33 = 99.99
    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'cases_sold'               => 0,
        'extra_bottles'            => 3,
        'total_bottles_to_sell'    => 3,
        'selling_price_per_bottle' => 33.33,
    ]))->assertRedirect();

    $sale = salesOf($supplier)->firstOrFail();
    expect((float) $sale->total_amount)->toBe(99.99);

    foreach ([33.33, 33.33, 33.33] as $instalment) {
        $this->actingAs($user)->post(route('sales.payment.store', $sale->id), [
            'payment_amount' => $instalment,
            'payment_method' => 'cash',
        ]);
    }

    $sale->refresh();
    expect((float) $sale->due_amount)->toBe(0.0)
        ->and((float) $sale->paid_amount)->toBe(99.99)
        ->and($sale->status)->toBe('completed');
});

it('T43: paying an already-settled sale does not push it past fully paid', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product))->assertRedirect();
    $sale = salesOf($supplier)->firstOrFail();

    foreach ([720, 200] as $amount) {
        $this->actingAs($user)->post(route('sales.payment.store', $sale->id), [
            'payment_amount' => $amount,
            'payment_method' => 'cash',
        ]);
    }

    $sale->refresh();
    expect((float) $sale->paid_amount)->toBeLessThanOrEqual(720.0)
        ->and((float) $sale->due_amount)->toBe(0.0)
        ->and($sale->status)->toBe('completed');
});

// ── Drafts ──────────────────────────────────────────────────────────────────

it('T44: a draft may be saved for more stock than exists, since it reserves nothing', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    // Only 240 bottles were lifted; draft 20 cases = 480.
    $payload = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 20,
        'total_bottles_to_sell' => 480,
    ]);
    $payload['save_as_draft'] = true;

    $this->actingAs($user)->post(route('sales.store'), $payload)->assertRedirect();

    expect(salesOf($supplier)->where('status', 'draft')->count())->toBe(1)
        ->and(getVariant($product)['current_purchased_quantity'])->toBe(240);
});

it('T45: confirming a draft that outgrew the stock is rejected and the draft survives', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $payload = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 20,
        'total_bottles_to_sell' => 480,
    ]);
    $payload['save_as_draft'] = true;
    $this->actingAs($user)->post(route('sales.store'), $payload)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();

    $confirm = salePayload($supplier, $shop, $product, [
        'cases_sold'            => 20,
        'total_bottles_to_sell' => 480,
    ]);
    $confirm['draft_id'] = $draft->id;

    $this->actingAs($user)->post(route('sales.store'), $confirm)
         ->assertSessionHasErrors('inventory');

    // Nothing half-applied: still a draft, stock untouched.
    expect($draft->fresh()->status)->toBe('draft')
        ->and(getVariant($product)['current_purchased_quantity'])->toBe(240)
        ->and(salesOf($supplier)->count())->toBe(1);
});

it('T46: deleting a draft leaves inventory untouched', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $payload = salePayload($supplier, $shop, $product);
    $payload['save_as_draft'] = true;
    $this->actingAs($user)->post(route('sales.store'), $payload)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();

    $this->actingAs($user)->delete(route('sales.destroy', $draft->id))->assertRedirect();

    expect(salesOf($supplier)->count())->toBe(0)
        ->and(getVariant($product)['current_purchased_quantity'])->toBe(240);
});

it('T47: shrinking a draft to fewer items drops the removed lines', function () {
    [$supplier, $shop, $product, $user] = saleFixture(['free_bottles_per_case' => 2]);

    $twoLines = salePayload($supplier, $shop, $product);
    $twoLines['save_as_draft'] = true;
    $twoLines['items'][] = array_merge($twoLines['items'][0], [
        'cases_sold'            => 1,
        'total_bottles_to_sell' => 24,
    ]);
    $this->actingAs($user)->post(route('sales.store'), $twoLines)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();
    expect($draft->items)->toHaveCount(2);

    $oneLine = salePayload($supplier, $shop, $product);
    $oneLine['save_as_draft'] = true;
    $oneLine['draft_id'] = $draft->id;
    $this->actingAs($user)->post(route('sales.store'), $oneLine)->assertRedirect();

    expect($draft->fresh()->items)->toHaveCount(1)
        ->and(\App\Models\SaleItem::where('sale_id', $draft->id)->count())->toBe(1);
});

it('T48: a draft cannot be confirmed twice', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $payload = salePayload($supplier, $shop, $product);
    $payload['save_as_draft'] = true;
    $this->actingAs($user)->post(route('sales.store'), $payload)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();

    $confirm = salePayload($supplier, $shop, $product);
    $confirm['draft_id'] = $draft->id;

    $this->actingAs($user)->post(route('sales.store'), $confirm)->assertRedirect();
    // A double submit must not deduct the stock a second time.
    $this->actingAs($user)->post(route('sales.store'), $confirm)->assertSessionHasErrors('draft_id');

    expect(salesOf($supplier)->count())->toBe(1)
        ->and(getVariant($product)['current_purchased_quantity'])->toBe(192);
});

// ── Inventory ───────────────────────────────────────────────────────────────

it('T49: after selling the last bottle any further sale is rejected', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'cases_sold'            => 10,
        'total_bottles_to_sell' => 240,
    ]))->assertRedirect();

    expect(getVariant($product)['current_purchased_quantity'])->toBe(0);

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'cases_sold'            => 0,
        'extra_bottles'         => 1,
        'total_bottles_to_sell' => 1,
    ]))->assertSessionHasErrors('inventory');

    expect(getVariant($product)['current_purchased_quantity'])->toBe(0);
});

it('T50: deleting a sale of a variant that has free bottles restores every bottle', function () {
    // 45 cases x 1 free = 45 free bottles, which is more than one 24-bottle case.
    // That is the threshold where cases_without_free_bottles stops matching the
    // bottles actually lifted, so it is the case the restore has to get right.
    [$supplier, $shop, $product, $user] = saleFixture([
        'number_of_cases'       => 45,
        'free_bottles_per_case' => 1,
    ]);

    $before = getVariant($product)['current_purchased_quantity'];

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'cases_sold'            => 2,
        'total_bottles_to_sell' => 48,
    ]))->assertRedirect();

    $sale = salesOf($supplier)->firstOrFail();
    expect(getVariant($product)['current_purchased_quantity'])->toBe($before - 48);

    $this->actingAs($user)->delete(route('sales.destroy', $sale->id))->assertRedirect();

    expect(getVariant($product)['current_purchased_quantity'])->toBe($before);
});

it('T51: reducing a sale returns the difference to stock', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'cases_sold'            => 5,
        'total_bottles_to_sell' => 120,
    ]))->assertRedirect();

    $sale = salesOf($supplier)->firstOrFail();
    expect(getVariant($product)['current_purchased_quantity'])->toBe(120);

    $this->actingAs($user)->put(route('sales.update', $sale->id), salePayload($supplier, $shop, $product, [
        'cases_sold'            => 2,
        'total_bottles_to_sell' => 48,
    ]))->assertRedirect();

    expect(getVariant($product)['current_purchased_quantity'])->toBe(192);
});

it('T52: a sale of zero cases and zero extra bottles is rejected', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'cases_sold'            => 0,
        'extra_bottles'         => 0,
        'total_bottles_to_sell' => 0,
    ]))->assertSessionHasErrors();

    expect(salesOf($supplier)->count())->toBe(0);
});

it('T53: a sale for a variant that was never lifted is rejected', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'variant' => '2L',
    ]))->assertSessionHasErrors('inventory');

    expect(salesOf($supplier)->count())->toBe(0);
});

// ── Report ──────────────────────────────────────────────────────────────────

it('T54: drafts carry no payment obligation in the report', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $payload = salePayload($supplier, $shop, $product);
    $payload['save_as_draft'] = true;
    $this->actingAs($user)->post(route('sales.store'), $payload)->assertRedirect();

    $draft = salesOf($supplier)->where('status', 'draft')->firstOrFail();

    $this->actingAs($user)->get(route('sales.report'))
         ->assertInertia(function (\Inertia\Testing\AssertableInertia $page) use ($draft) {
             $row = collect($page->toArray()['props']['sales'])->firstWhere('id', $draft->id);
             expect($row)->not->toBeNull()
                 ->and($row['status'])->toBe('draft')
                 // The page decides "no payment state" from the draft status, so
                 // the row must still say it is a draft after the round trip.
                 ->and((float) $row['paid_amount'])->toBe(0.0);
         });
});

it('T55: a fully paid sale reports zero due', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $this->actingAs($user)->post(route('sales.store'), salePayload($supplier, $shop, $product))->assertRedirect();
    $sale = salesOf($supplier)->firstOrFail();

    $this->actingAs($user)->post(route('sales.payment.store', $sale->id), [
        'payment_amount' => 720,
        'payment_method' => 'cash',
    ]);

    $this->actingAs($user)->get(route('sales.report'))
         ->assertInertia(function (\Inertia\Testing\AssertableInertia $page) use ($sale) {
             $row = collect($page->toArray()['props']['sales'])->firstWhere('id', $sale->id);
             expect((float) $row['due_amount'])->toBe(0.0)
                 ->and($row['status'])->toBe('completed');
         });
});

it('T56: the report survives having no sales at all', function () {
    seedPermissions();
    $user = makeUser(['sales.view']);

    $this->actingAs($user)->get(route('sales.report'))
         ->assertOk()
         ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) =>
             expect($page->toArray()['props']['sales'])->toBe([]));
});

// ── T57: custom variant sizes must survive for the next lift ────────────────

it('T57: a custom variant size is remembered on the product catalog after one lift', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    expect($catalog->default_variants)->toBe([]);

    // A size that is not one of the four the picker hardcodes.
    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog, [
             'variant'          => '550ml',
             'bottles_per_case' => 20,
         ]))
         ->assertRedirect();

    $saved = collect($catalog->fresh()->default_variants);
    expect($saved->pluck('variant')->all())->toContain('550ml')
        ->and($saved->firstWhere('variant', '550ml')['bottles_per_case'])->toBe(20);

    // And the endpoint the lift screen searches with hands it back.
    $response = $this->actingAs($user)
        ->getJson('/api/product-catalog/search?supplier_id=' . $supplier->id . '&q=' . urlencode($catalog->name));

    $row = collect($response->json())->firstWhere('id', $catalog->id);
    expect(collect($row['default_variants'])->pluck('variant')->all())->toContain('550ml');
});

it('T58: a second lift keeps the earlier custom size alongside the new one', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog, [
        'variant' => '550ml',
    ]))->assertRedirect();

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog, [
        'variant' => '330ml',
    ]))->assertRedirect();

    expect(collect($catalog->fresh()->default_variants)->pluck('variant')->all())
        ->toContain('550ml')
        ->toContain('330ml');
});

// ── T59–T64: what the lift screen needs to offer a custom size again ────────

it('T59: a custom size keeps its bottles-per-case, not the 24 default', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog, [
        'variant'               => '750ml',
        'bottles_per_case'      => 9,
        'free_bottles_per_case' => 2,
    ]))->assertRedirect();

    $saved = collect($catalog->fresh()->default_variants)->firstWhere('variant', '750ml');

    // The picker pre-fills its checkbox from exactly these two numbers.
    expect($saved['bottles_per_case'])->toBe(9)
        ->and((float) $saved['free_bottles_per_case'])->toBe(2.0);
});

it('T60: lifting the same custom size twice does not duplicate it', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    foreach ([1, 2, 3] as $_) {
        $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog, [
            'variant' => '550ml',
        ]))->assertRedirect();
    }

    $saved = collect($catalog->fresh()->default_variants)->where('variant', '550ml');
    expect($saved)->toHaveCount(1);
});

it('T61: a custom size saved as a draft lift is remembered too', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $payload = liftPayload($supplier, $catalog, ['variant' => '650ml']);
    $payload['save_as_draft'] = true;

    $this->actingAs($user)->post(route('lifts.store'), $payload)->assertRedirect();

    // No stock yet, but the size is now a preset for next time.
    expect(Product::count())->toBe(0)
        ->and(collect($catalog->fresh()->default_variants)->pluck('variant')->all())
        ->toContain('650ml');
});

it('T62: a custom size on one product does not leak onto another', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $cola     = makeCatalog($supplier);
    $water    = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $cola, [
        'variant' => '550ml',
    ]))->assertRedirect();

    expect(collect($cola->fresh()->default_variants)->pluck('variant')->all())->toContain('550ml')
        ->and(collect($water->fresh()->default_variants)->pluck('variant')->all())->not->toContain('550ml');
});

it('T63: several custom sizes on one lift are all remembered', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $payload = liftPayload($supplier, $catalog);
    $payload['items'][0]['variants'] = [
        ['variant' => '330ml', 'number_of_cases' => 5, 'case_buying_price' => 200, 'bottles_per_case' => 24, 'free_bottles_per_case' => 0],
        ['variant' => '550ml', 'number_of_cases' => 4, 'case_buying_price' => 300, 'bottles_per_case' => 18, 'free_bottles_per_case' => 1],
        ['variant' => '5L',    'number_of_cases' => 3, 'case_buying_price' => 480, 'bottles_per_case' => 4,  'free_bottles_per_case' => 0],
    ];

    $this->actingAs($user)->post(route('lifts.store'), $payload)->assertRedirect();

    $names = collect($catalog->fresh()->default_variants)->pluck('variant')->all();
    expect($names)->toContain('330ml')->toContain('550ml')->toContain('5L');
});

it('T64: a custom size survives alongside the standard ones', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog, [
        'variant' => '500ml',
    ]))->assertRedirect();

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog, [
        'variant' => '550ml',
    ]))->assertRedirect();

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog, [
        'variant' => '250ml',
    ]))->assertRedirect();

    // The picker renders the four fixed sizes plus this list, so 550ml must
    // still be here after standard sizes were lifted on top of it.
    expect(collect($catalog->fresh()->default_variants)->pluck('variant')->all())
        ->toContain('550ml')
        ->toContain('500ml')
        ->toContain('250ml');
});

// ── T65–T67: the lift screen's deposit balance ──────────────────────────────

it('T65: the lift page reports the deposit balance already reduced by the lift', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $balanceOf = fn () => collect(
        test()->actingAs($user)->get(route('lifts.index'))
              ->viewData('page')['props']['suppliers']
    )->firstWhere('id', $supplier->id)['remaining_deposit'];

    expect((float) $balanceOf())->toBe(10000.0);

    // 10 cases x 240 = 2400 drawn against the deposit.
    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect(route('lifts.report'));

    expect((float) $balanceOf())->toBe(7600.0);
});

it('T66: a draft lift does not touch the deposit balance', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    $payload = liftPayload($supplier, $catalog);
    $payload['save_as_draft'] = true;

    $this->actingAs($user)->post(route('lifts.store'), $payload)->assertRedirect();

    $balance = collect(
        $this->actingAs($user)->get(route('lifts.index'))
             ->viewData('page')['props']['suppliers']
    )->firstWhere('id', $supplier->id)['remaining_deposit'];

    expect((float) $balance)->toBe(10000.0);
});

it('T67: successive lifts keep drawing the balance down', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add']);

    foreach ([1, 2, 3] as $_) {
        $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    }

    $balance = collect(
        $this->actingAs($user)->get(route('lifts.index'))
             ->viewData('page')['props']['suppliers']
    )->firstWhere('id', $supplier->id)['remaining_deposit'];

    // 10000 - (2400 x 3)
    expect((float) $balance)->toBe(2800.0);
});

// ── T68–T69: the date range the picker emits ────────────────────────────────

it('T68: a single-day range returns only that day, which is what Today/Yesterday send', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $user     = makeUser(['sales.add', 'sales.view']);
    $shop     = makeShop();

    $this->actingAs($liftUser)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    $product = getProduct($supplier);

    foreach ([0 => 'today', 1 => 'yesterday', 5 => 'older'] as $daysAgo => $_) {
        $this->actingAs($user)->post(route('sales.store'), array_merge(
            salePayload($supplier, $shop, $product, ['cases_sold' => 1, 'total_bottles_to_sell' => 24]),
            ['sale_date' => now()->subDays($daysAgo)->toDateString()]
        ))->assertRedirect();
    }

    $yesterday = now()->subDay()->toDateString();

    $rows = $this->actingAs($user)
        ->get(route('sales.report', ['start_date' => $yesterday, 'end_date' => $yesterday]))
        ->viewData('page')['props']['sales'];

    expect($rows)->toHaveCount(1)
        ->and($rows[0]['sale_date'])->toBe($yesterday);
});

it('T69: a multi-day custom range includes both end points', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $liftUser = makeUser(['lift.add']);
    $user     = makeUser(['sales.add', 'sales.view']);
    $shop     = makeShop();

    $this->actingAs($liftUser)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    $product = getProduct($supplier);

    foreach ([1, 2, 3, 9] as $daysAgo) {
        $this->actingAs($user)->post(route('sales.store'), array_merge(
            salePayload($supplier, $shop, $product, ['cases_sold' => 1, 'total_bottles_to_sell' => 24]),
            ['sale_date' => now()->subDays($daysAgo)->toDateString()]
        ))->assertRedirect();
    }

    // Boundary days must be inside the range, not dropped by an exclusive compare.
    $rows = $this->actingAs($user)
        ->get(route('sales.report', [
            'start_date' => now()->subDays(3)->toDateString(),
            'end_date'   => now()->subDays(1)->toDateString(),
        ]))
        ->viewData('page')['props']['sales'];

    expect($rows)->toHaveCount(3);
});

// ── T70–T72: the login screen's contract ────────────────────────────────────

it('T70: the login page renders the Login component', function () {
    $this->get(route('login'))
         ->assertOk()
         ->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) =>
             expect($page->toArray()['component'])->toBe('Auth/Login'));
});

it('T71: a user can sign in with either their email or their username', function () {
    $user = \App\Models\User::factory()->create([
        'name'     => 'collector-01',
        'email'    => 'collector@example.com',
        'password' => bcrypt('secret-pass'),
    ]);

    // The form posts a single "login" field that accepts both.
    $this->post(route('login'), ['login' => 'collector@example.com', 'password' => 'secret-pass'])
         ->assertRedirect();
    expect(auth()->id())->toBe($user->id);

    auth()->logout();

    $this->post(route('login'), ['login' => 'collector-01', 'password' => 'secret-pass'])
         ->assertRedirect();
    expect(auth()->id())->toBe($user->id);
});

it('T72: a wrong password is rejected on the login field', function () {
    \App\Models\User::factory()->create([
        'email'    => 'collector@example.com',
        'password' => bcrypt('secret-pass'),
    ]);

    $this->post(route('login'), ['login' => 'collector@example.com', 'password' => 'wrong'])
         ->assertSessionHasErrors('login');

    expect(auth()->check())->toBeFalse();
});

// ── T73–T75: where each save lands you ──────────────────────────────────────

it('T73: recording a lift lands on the lift report with a confirmation', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view']);

    $this->actingAs($user)
         ->post(route('lifts.store'), liftPayload($supplier, $catalog))
         ->assertRedirect(route('lifts.report'))
         ->assertSessionHas('success', 'Lift recorded successfully');
});

it('T74: saving a lift draft lands on the lift report too', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view']);

    $payload = liftPayload($supplier, $catalog);
    $payload['save_as_draft'] = true;

    $this->actingAs($user)
         ->post(route('lifts.store'), $payload)
         ->assertRedirect(route('lifts.report'))
         ->assertSessionHas('success', 'Lift draft saved successfully');
});

it('T75: saving a sale draft lands on the sales report with a confirmation', function () {
    [$supplier, $shop, $product, $user] = saleFixture();

    $payload = salePayload($supplier, $shop, $product);
    $payload['save_as_draft'] = true;

    $this->actingAs($user)
         ->post(route('sales.store'), $payload)
         ->assertRedirect(route('sales.report'))
         ->assertSessionHas('success', 'Sale draft saved successfully');
});

// ── T76–T79: editing a completed lift must not re-charge the deposit ────────

/** Deposit balance as the lift screen reports it. */
function depositBalance(Supplier $supplier, User $user): float
{
    return (float) collect(
        test()->actingAs($user)->get(route('lifts.index'))
              ->viewData('page')['props']['suppliers']
    )->firstWhere('id', $supplier->id)['remaining_deposit'];
}

it('T76: re-saving a completed lift unchanged does not draw the money twice', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);

    // 10 cases x 240 = 2400
    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    expect(depositBalance($supplier, $user))->toBe(7600.0);

    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();

    // Open it from the list and save again with nothing changed.
    $again = liftPayload($supplier, $catalog);
    $again['draft_id'] = $lift->id;
    $this->actingAs($user)->post(route('lifts.store'), $again)->assertRedirect();

    // A second full charge would leave 5200.
    expect(depositBalance($supplier, $user))->toBe(7600.0)
        ->and(\App\Models\Lift::where('supplier_id', $supplier->id)->count())->toBe(1);
});

it('T77: raising a completed lift draws only the difference', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();

    // 10 -> 12 cases: 2880 total, so only 480 more should be taken.
    $bigger = liftPayload($supplier, $catalog, ['number_of_cases' => 12]);
    $bigger['draft_id'] = $lift->id;
    $this->actingAs($user)->post(route('lifts.store'), $bigger)->assertRedirect();

    expect(depositBalance($supplier, $user))->toBe(7120.0)
        ->and((float) $lift->fresh()->total_amount)->toBe(2880.0);
});

it('T78: lowering a completed lift credits the difference back', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();

    // 10 -> 5 cases: 1200 total, so 1200 goes back.
    $smaller = liftPayload($supplier, $catalog, ['number_of_cases' => 5]);
    $smaller['draft_id'] = $lift->id;
    $this->actingAs($user)->post(route('lifts.store'), $smaller)->assertRedirect();

    expect(depositBalance($supplier, $user))->toBe(8800.0);
});

it('T79: editing a completed lift three times still leaves one correct charge', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();

    foreach ([12, 8, 15] as $cases) {
        $edit = liftPayload($supplier, $catalog, ['number_of_cases' => $cases]);
        $edit['draft_id'] = $lift->id;
        $this->actingAs($user)->post(route('lifts.store'), $edit)->assertRedirect();
    }

    // Only the final state should be charged: 15 x 240 = 3600.
    expect(depositBalance($supplier, $user))->toBe(6400.0)
        ->and((float) $lift->fresh()->total_amount)->toBe(3600.0);
});

// ── T80–T82: editing a completed lift must not double the stock either ──────

it('T80: re-saving a completed lift keeps the stock and the batch count as they were', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    expect(getVariant(getProduct($supplier))['current_purchased_quantity'])->toBe(240)
        ->and(Product::where('supplier_id', $supplier->id)->count())->toBe(1);

    $lift  = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();
    $again = liftPayload($supplier, $catalog);
    $again['draft_id'] = $lift->id;
    $this->actingAs($user)->post(route('lifts.store'), $again)->assertRedirect();

    // 480 would mean the lift was counted twice; a second row would orphan the
    // sale_items that point at the first batch.
    expect(getVariant(getProduct($supplier))['current_purchased_quantity'])->toBe(240)
        ->and(Product::where('supplier_id', $supplier->id)->count())->toBe(1);
});

it('T81: editing a lift that already has sales keeps the sold bottles deducted', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    $product = getProduct($supplier);

    // Sell 2 cases: 240 - 48 = 192 left.
    $this->actingAs($saleUser)
         ->post(route('sales.store'), salePayload($supplier, $shop, $product))
         ->assertRedirect();
    expect(getVariant($product)['current_purchased_quantity'])->toBe(192);

    // Now correct the lift up to 12 cases: 288 lifted, 48 sold, 240 should remain.
    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();
    $edit = liftPayload($supplier, $catalog, ['number_of_cases' => 12]);
    $edit['draft_id'] = $lift->id;
    $this->actingAs($user)->post(route('lifts.store'), $edit)->assertRedirect();

    expect(getVariant($product)['current_purchased_quantity'])->toBe(240)
        ->and(Product::where('supplier_id', $supplier->id)->count())->toBe(1);
});

it('T82: a lift cannot be edited below what has already been sold', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);
    $saleUser = makeUser(['sales.add']);
    $shop     = makeShop();

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    $product = getProduct($supplier);

    // Sell 5 cases = 120 bottles.
    $this->actingAs($saleUser)->post(route('sales.store'), salePayload($supplier, $shop, $product, [
        'cases_sold'            => 5,
        'total_bottles_to_sell' => 120,
    ]))->assertRedirect();

    // Try to shrink the lift to 2 cases (48 bottles) - fewer than the 120 sold.
    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();
    $edit = liftPayload($supplier, $catalog, ['number_of_cases' => 2]);
    $edit['draft_id'] = $lift->id;

    $this->actingAs($user)->post(route('lifts.store'), $edit)->assertSessionHasErrors('lift');

    // Rejected whole: the lift and the stock are untouched.
    expect((float) $lift->fresh()->total_amount)->toBe(2400.0)
        ->and(getVariant($product)['current_purchased_quantity'])->toBe(120)
        ->and(depositBalance($supplier, $user))->toBe(7600.0);
});

// ── T83–T84: editing a lift must not invent a deposit ───────────────────────

it('T83: editing a completed lift creates no extra deposit row', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();
    expect(Deposit::where('supplier_id', $supplier->id)->count())->toBe(1);

    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();

    // The edit screen must not think a top-up is needed, so no
    // deposit_from_here_amount goes with the update.
    $edit = liftPayload($supplier, $catalog, ['number_of_cases' => 12]);
    $edit['draft_id'] = $lift->id;
    $this->actingAs($user)->post(route('lifts.store'), $edit)->assertRedirect();

    expect(Deposit::where('supplier_id', $supplier->id)->count())->toBe(1)
        ->and(depositBalance($supplier, $user))->toBe(7120.0);
});

it('T84: the deposit ledger stays consistent across a lift edit', function () {
    seedPermissions();
    $supplier = makeSupplier();
    seedDeposit($supplier, 10_000);
    $catalog  = makeCatalog($supplier);
    $user     = makeUser(['lift.add', 'lift.view', 'lift.update']);

    $this->actingAs($user)->post(route('lifts.store'), liftPayload($supplier, $catalog))->assertRedirect();

    $lift = \App\Models\Lift::where('supplier_id', $supplier->id)->firstOrFail();
    $edit = liftPayload($supplier, $catalog, ['number_of_cases' => 12]);
    $edit['draft_id'] = $lift->id;
    $this->actingAs($user)->post(route('lifts.store'), $edit)->assertRedirect();

    $deposit = Deposit::where('supplier_id', $supplier->id)->firstOrFail();

    // used + remaining must still add up to what was deposited, and used must
    // equal the lift's current value - not the sum of both saves.
    expect((float) $deposit->balance_used)->toBe(2880.0)
        ->and((float) $deposit->balance_remaining)->toBe(7120.0)
        ->and((float) $deposit->balance_used + (float) $deposit->balance_remaining)
        ->toBe((float) $deposit->balance_deposited);
});
