<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\ProductPurchaseContract;
use App\Contracts\CategoryContract;
use App\Contracts\BrandContract;
use App\Contracts\DepositContract;
use App\Contracts\SupplierContract;
use App\Http\Requests\storeProductPurchaseRequest;
use Inertia\Inertia;
use App\Models\Supplier;

class ProductPurchaseController extends Controller
{
    public function __construct(
        protected ProductPurchaseContract $productPurchaseRepository,
        protected DepositContract $depositRepository,
        protected SupplierContract $supplierRepository,
        protected CategoryContract $categoryRepository,
        protected BrandContract $brandRepository
    ) {
    }

    public function index()
    {
        // Fetch suppliers with their remaining deposits
        $suppliers = $this->supplierRepository->all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'company_name' => $supplier->company_name,
            ];
        });

        // Fetch total remaining deposits per supplier
        $remainingDeposits = $this->depositRepository->totalRemainingDepositsBySupplier()
            ->pluck('total_remaining_deposit', 'supplier_name')
            ->toArray();

        // Merge remaining deposit into suppliers array
        $suppliers = $suppliers->map(function ($supplier) use ($remainingDeposits) {
            return [
                'id' => $supplier['id'],
                'company_name' => $supplier['company_name'],
                'remaining_deposit' => isset($remainingDeposits[$supplier['company_name']])
                    ? round($remainingDeposits[$supplier['company_name']], 2)
                    : 0.00,
            ];
        });

        $categories = $this->categoryRepository->all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });

        // Fetch brands
        $brands = $this->brandRepository->all()->map(function ($brand) {
            return [
                'id' => $brand->id,
                'brand_name' => $brand->brand_name, // Map name to brand_name for frontend
            ];
        });

        return Inertia::render('DepositManagement/Purchase', [
            'suppliers' => $suppliers,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function storeProductPurchase(storeProductPurchaseRequest $request)
    {
        $data = [
            'name' => $request->product_name,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'metadata' => $request->variants,
            'date' => now(),
        ];

        $totalAmountForAllVariants = collect($data['metadata'])->sum(function ($variant) {
            return $variant['quantity'] * $variant['unit_price'];
        });
        //format the metadata to variants for the jsonb format
        $data['metadata'] = ['variants' => $data['metadata']];

        $this->productPurchaseRepository->create($data);

        $latestDeposit = $this->depositRepository->findTheLatestDepositForTheSupplier($data['supplier_id']);
        if ($latestDeposit) {

            $this->depositRepository->updateDepositTable($totalAmountForAllVariants, $latestDeposit);
        }
        return redirect()->route('purchases.index')->with('success', 'Purchase added successfully');
    }

    public function purchaseReport()
    {
        // dd($this->productPurchaseRepository->purchaseHistory());
        return Inertia::render('DepositManagement/PurchaseReport', [
            'purchaseHistory' => $this->productPurchaseRepository->purchaseHistory(),
        ]);
    }

    public function inventoryReport()
    {
        return Inertia::render('InventoryManagement/InventoryReport', [
            'inventoryStock' => $this->productPurchaseRepository->getInventoryStock(),
        ]);
    }
}

