<?php

use App\Contracts\ProductPurchaseContract;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ProductPurchaseController;


// Suppliers

Route::get('suppliers/index', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
Route::post('suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
Route::put('suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::get('suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');


// deposit
Route::get('deposits', [DepositController::class, 'index'])->name('deposits.index');
Route::Post('deposits/store', [DepositController::class, 'store'])->name('deposits.store');

//Purchase
Route::get('purchases', [ProductPurchaseController::class, 'index'])->name('purchases.index');
Route::post('products-store', [ProductPurchaseController::class, 'storeProductPurchase'])->name('products.store');
Route::get('/purchases/report', [ProductPurchaseController::class, 'purchaseReport'])->name('purchases.report');
