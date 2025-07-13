<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Contracts\ShopContract;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function __construct(protected ShopContract $shopRepository)
    {
        // You can inject any dependencies here if needed
        // For example, you might want to use the productPurchaseContract in your methods

    }
    public function create()
    {
        return Inertia::render('SalesManagement/CreateSale', [
            'shops' => $this->shopRepository->all(), // Assuming this method exists in the contract
        ]);
    }
}
