<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ProductPurchaseContract;
use App\Http\Requests\storeProductPurchaseRequest;
use Inertia\Inertia;
use App\Models\Supplier;

class ProductPurchaseController extends Controller
{
    public function __construct(protected ProductPurchaseContract $productPurchaseRepository)
    {
    }

    public function index()
    {
        return Inertia::render('DepositManagement/Purchase', [
            'suppliers' => Supplier::all()->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'company_name' => $supplier->company_name,
                    $supplier->company_name,
                ];
            }),
        ]);
    }

    public function storeProductPurchase(Request $request)
    {
        $data = [
            'name' => $request->product_name,
            'supplier_id' => $request->supplier_id,
            'metadata' => $request->variants,
            'date' => now(),
        ];
        $this->productPurchaseRepository->create($data);

        return redirect()->route('purchases.index')->with('success', 'Purchase added successfully');
    }
}

