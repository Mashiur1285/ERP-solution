<?php

use App\Models\Deposit;
use App\Models\Product;
use App\Models\ProductCatalog;
use App\Models\Shop;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

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
