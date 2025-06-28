<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SupplierController;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// });

Route::get('suppliers/store', [SupplierController::class, 'store']);

