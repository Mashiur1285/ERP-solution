<?php
// Rolled-back simulations for the "edit lift after partial sales" feature.
// Run:  php scripts/test_lift_edit.php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Lift;
use App\Models\Product;
use App\Models\ProductCatalog;
use App\Models\Deposit;
use App\Http\Controllers\LiftController;
use Illuminate\Support\Facades\DB;

$controller = app(LiftController::class);
$repo = app(\App\Contracts\LiftContract::class);

// Reflection helpers for the protected controller methods.
$call = function ($method, ...$args) use ($controller) {
    $m = new ReflectionMethod(LiftController::class, $method);
    $m->setAccessible(true);
    return $m->invoke($controller, ...$args);
};

// Build a store()-shaped $items array for a lift from explicit variant specs.
$buildItems = function (int $catalogId, array $variants) {
    $cat = ProductCatalog::find($catalogId);
    return [[
        'product_catalog_id' => $catalogId,
        'product_name' => $cat->name,
        'category_id' => $cat->category_id,
        'brand_id' => $cat->brand_id,
        'supplier_id' => $cat->supplier_id,
        'variants' => $variants,
    ]];
};

$v = fn ($variant, $cases, $bpc, $price, $free = 0) => [
    'variant' => $variant,
    'number_of_cases' => $cases,
    'bottles_per_case' => $bpc,
    'case_buying_price' => $price,
    'free_bottles_per_case' => $free,
];

$liftData = function (Lift $lift) {
    return [
        'supplier_id' => $lift->supplier_id,
        'lift_date' => $lift->lift_date->toDateString(),
        'notes' => $lift->notes,
        'status' => 'completed',
    ];
};

$runScenario = function (string $name, callable $fn) {
    echo str_repeat('=', 70) . PHP_EOL;
    echo "SCENARIO: $name" . PHP_EOL;
    DB::beginTransaction();
    try {
        $fn();
    } catch (\Throwable $e) {
        echo '  >> THREW: ' . get_class($e) . ' :: ';
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            echo implode(' | ', \Illuminate\Support\Arr::flatten($e->errors()));
        } else {
            echo $e->getMessage();
        }
        echo PHP_EOL;
    } finally {
        DB::rollBack();
        echo '  (rolled back)' . PHP_EOL;
    }
};

$dump = function (int $liftId) {
    $l = Lift::with('items')->find($liftId);
    echo "  lift#{$l->id} total={$l->total_amount}" . PHP_EOL;
    foreach ($l->items as $it) {
        $p = $it->product_id ? Product::find($it->product_id) : null;
        $meta = $p ? collect($p->metadata['variants'])->first() : null;
        echo sprintf(
            "    %s: product_id=%s cases=%s cost=%s | cur_purchased=%s cur_free=%s meta_cost=%s",
            $it->variant,
            $it->product_id,
            $it->number_of_cases,
            $it->total_cost,
            $meta['current_purchased_quantity'] ?? 'n/a',
            $meta['current_free_quantity'] ?? 'n/a',
            $meta['total_cost'] ?? 'n/a'
        ) . PHP_EOL;
    }
    $dep = Deposit::where('supplier_id', $l->supplier_id)->orderBy('id')->get();
    foreach ($dep as $d) {
        echo "    dep#{$d->id} used={$d->balance_used} remaining={$d->balance_remaining}" . PHP_EOL;
    }
};

// --- helper that mirrors the store() completed-edit branch ---
$editCompleted = function (Lift $lift, array $items) use ($call, $repo, $liftData) {
    $sold = $call('soldBottlesByProduct', $lift);
    $call('validateLiftEditAgainstSales', $lift, $items, $sold);
    $oldTotal = round((float) $lift->total_amount, 2);
    $newLift = $repo->reconcileCompletedLiftItems($lift, $items, $liftData($lift), $sold);
    $newTotal = round((float) $newLift->total_amount, 2);
    $delta = round($newTotal - $oldTotal, 2);
    $depRepo = app(\App\Contracts\DepositContract::class);
    if ($delta > 0) $depRepo->applyAmountAgainstSupplierDeposits((int) $lift->supplier_id, $delta);
    elseif ($delta < 0) $depRepo->creditAmountBackToSupplierDeposits((int) $lift->supplier_id, -$delta);
    return $newLift;
};

echo 'BEFORE (lift#3): ';
echo PHP_EOL;
$dump(3);

// 1. Reduce 2000ml below sold (12 sold -> set 1 case = 6 bottles). Expect rejection.
$runScenario('Reduce 2000ml to 1 case (6 < 12 sold) => REJECT', function () use ($editCompleted, $buildItems, $v) {
    $lift = Lift::with('items')->find(3);
    $items = $buildItems(1, [$v('1000ml', 17, 12, 2000), $v('2000ml', 1, 6, 1500)]);
    $editCompleted($lift, $items);
    echo '  >> NO THROW (unexpected!)' . PHP_EOL;
});

// 2. Reduce 2000ml to exactly sold (2 cases = 12 bottles). Expect success, cur_purchased=0.
$runScenario('Reduce 2000ml to 2 cases (==12 sold) => OK, remaining 0', function () use ($editCompleted, $buildItems, $v, $dump) {
    $lift = Lift::with('items')->find(3);
    $items = $buildItems(1, [$v('1000ml', 17, 12, 2000), $v('2000ml', 2, 6, 1500)]);
    $editCompleted($lift, $items);
    $dump(3);
});

// 3. Increase 1000ml 17->25 cases AND change 2000ml price 1500->1800. Expect success + deposit delta.
$runScenario('Increase 1000ml to 25 cases + 2000ml price 1800 => OK', function () use ($editCompleted, $buildItems, $v, $dump) {
    $lift = Lift::with('items')->find(3);
    $items = $buildItems(1, [$v('1000ml', 25, 12, 2000), $v('2000ml', 6, 6, 1800)]);
    $editCompleted($lift, $items);
    $dump(3);
});

// 4. Remove 2000ml (has sales). Expect rejection.
$runScenario('Remove 2000ml (has sales) => REJECT', function () use ($editCompleted, $buildItems, $v) {
    $lift = Lift::with('items')->find(3);
    $items = $buildItems(1, [$v('1000ml', 17, 12, 2000)]);
    $editCompleted($lift, $items);
    echo '  >> NO THROW (unexpected!)' . PHP_EOL;
});

// 5. Add a new 500ml variant to lift#3. Expect success (3 items, new batch).
$runScenario('Add new 500ml variant => OK', function () use ($editCompleted, $buildItems, $v, $dump) {
    $lift = Lift::with('items')->find(3);
    $items = $buildItems(1, [$v('1000ml', 17, 12, 2000), $v('2000ml', 6, 6, 1500), $v('500ml', 10, 24, 600)]);
    $newLift = $editCompleted($lift, $items);
    echo '  items now: ' . $newLift->items()->count() . PHP_EOL;
    $dump(3);
});

// 6. Reduce 1000ml (no sales) to 1 case. Expect success (no floor).
$runScenario('Reduce 1000ml (no sales) to 1 case => OK', function () use ($editCompleted, $buildItems, $v, $dump) {
    $lift = Lift::with('items')->find(3);
    $items = $buildItems(1, [$v('1000ml', 1, 12, 2000), $v('2000ml', 6, 6, 1500)]);
    $editCompleted($lift, $items);
    $dump(3);
});

echo str_repeat('=', 70) . PHP_EOL;
echo 'DONE. AFTER (lift#3 unchanged by rollbacks): ' . PHP_EOL;
$dump(3);
