<?php

use App\Contracts\ProductPurchaseContract;
use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ProductPurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ShopController;

// Suppliers
Route::get('suppliers/index', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
Route::post('suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
Route::put('suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::get('suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');


// deposit
Route::get('deposits', [DepositController::class, 'index'])->name('deposits.index');
Route::Post('deposits/store', [DepositController::class, 'store'])->name('deposits.store');

// Category Routes
Route::get('categories/index', [CategoryController::class, 'index'])->name('categories.index');
Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::put('categories/{id}/update', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('categories/{id}/delete', [CategoryController::class, 'destroy'])->name('categories.delete');

//Brands

Route::get('brands/index', [BrandController::class, 'index'])->name('brands.index');
Route::post('brands/store', [BrandController::class, 'store'])->name('brands.store');
Route::put('brands/{id}/update', [BrandController::class, 'update'])->name('brands.update');
Route::delete('brands/{id}/delete', [BrandController::class, 'destroy'])->name('brands.delete');

//Purchase/products
Route::get('purchases', [ProductPurchaseController::class, 'index'])->name('purchases.index');
Route::post('products-store', [ProductPurchaseController::class, 'storeProductPurchase'])->name('products.store');
Route::get('/purchases/report', [ProductPurchaseController::class, 'purchaseReport'])->name('purchases.report');

// Shops
Route::get('shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('shops/create', [ShopController::class, 'create'])->name('shops.create');
Route::Post('shops/store', [ShopController::class, 'store'])->name('shops.store');
Route::get('shops/{id}/edit', [ShopController::class, 'edit'])->name('shops.edit');
Route::Put('shops/{id}', [ShopController::class, 'update'])->name('shops.update');

//Sales
Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
Route::post('sales/store', [SalesController::class, 'store'])->name('sales.store');
Route::get('/sales/report', [SalesController::class, 'report'])->name('sales.report');
