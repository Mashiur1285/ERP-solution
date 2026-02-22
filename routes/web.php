<?php

use App\Contracts\ProductPurchaseContract;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductPurchaseController;
use App\Http\Controllers\SalesController; // Fixed: Correct namespace
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LiftController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication and ERP Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Suppliers
    Route::get('suppliers/index', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::get('suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::post('suppliers/quick-store', [SupplierController::class, 'quickStore'])->name('suppliers.quick-store');

    // Deposits
    Route::get('deposits', [DepositController::class, 'index'])->name('deposits.index');
    Route::post('deposits/store', [DepositController::class, 'store'])->name('deposits.store');

    // Categories
    Route::get('categories/index', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{id}/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}/delete', [CategoryController::class, 'destroy'])->name('categories.delete');
    Route::post('categories/quick-store', [CategoryController::class, 'quickStore'])->name('categories.quick-store');

    // Brands
    Route::get('brands/index', [BrandController::class, 'index'])->name('brands.index');
    Route::post('brands/store', [BrandController::class, 'store'])->name('brands.store');
    Route::put('brands/{id}/update', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('brands/{id}/delete', [BrandController::class, 'destroy'])->name('brands.delete');
    Route::post('brands/quick-store', [BrandController::class, 'quickStore'])->name('brands.quick-store');

    // Purchases/Products (legacy)
    Route::get('purchases', [ProductPurchaseController::class, 'index'])->name('purchases.index');
    Route::post('products-store', [ProductPurchaseController::class, 'storeProductPurchase'])->name('products.store');
    Route::get('/purchases/report', [ProductPurchaseController::class, 'purchaseReport'])->name('purchases.report');

    // Lifts (new purchase system)
    Route::get('lifts', [LiftController::class, 'index'])->name('lifts.index');
    Route::post('lifts/store', [LiftController::class, 'store'])->name('lifts.store');
    Route::get('lifts/report', [LiftController::class, 'report'])->name('lifts.report');
    Route::get('/api/product-catalog/search', [LiftController::class, 'searchProducts']);
    Route::post('/api/product-catalog/quick-store', [LiftController::class, 'quickStoreProduct']);

    // Shops
    Route::get('shops', [ShopController::class, 'index'])->name('shops.index');
    Route::get('shops/create', [ShopController::class, 'create'])->name('shops.create');
    Route::post('shops/store', [ShopController::class, 'store'])->name('shops.store');
    Route::get('shops/{id}/edit', [ShopController::class, 'edit'])->name('shops.edit');
    Route::put('shops/{id}', [ShopController::class, 'update'])->name('shops.update');

    // Sales
    Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
    Route::post('sales/store', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/report', [SalesController::class, 'report'])->name('sales.report');
    Route::get('sales/payment/{id}', [SalesController::class, 'payment'])->name('sales.payment');
    Route::post('/sales/payment/store/{id}', [SalesController::class, 'storePayment'])->name('sales.payment.store');
    Route::get('/sales/cash-memo/{id}', [SalesController::class, 'cashMemo'])->name('sales.cash-memo');

    // API Routes for Sales
    Route::get('/api/products-by-supplier', [SalesController::class, 'getProductsBySupplier']);
    Route::get('/api/variant-inventory', [SalesController::class, 'getVariantInventory']);
    Route::get('/api/inventory/search', [SalesController::class, 'searchInventoryProducts']);

    // Inventory Report
    Route::get('/inventory/report', [ProductPurchaseController::class, 'inventoryReport'])->name('inventory.report');

    // Expenses
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('expenses/store', [ExpenseController::class, 'storeExpense'])->name('expenses.store');
    Route::put('expenses/{id}/update', [ExpenseController::class, 'update'])->name('expenses.update');
});

require __DIR__ . '/auth.php';
