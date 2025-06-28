<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SupplierController;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// });

Route::post('suppliers/store', [SupplierController::class, 'store']);
Route::get('suppliers/index', [SupplierController::class, 'index']);
