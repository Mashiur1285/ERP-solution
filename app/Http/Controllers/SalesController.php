<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Contracts\ShopContract;
use App\Models\Sale;
use App\Models\Shop;
use App\Contracts\CategoryContract;
use App\Contracts\SalesItemContract;
use App\Contracts\BrandContract;
use App\Contracts\SupplierContract;
use App\Enums\SalesStatus;
use App\Enums\SalesItemsStatus;
use App\Contracts\ProductPurchaseContract;
use App\Contracts\SalesContract;
use App\Http\Requests\StoreSalesRequest;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function __construct(
        protected ShopContract $shopRepository,
        protected ProductPurchaseContract $productPurchaseRepository,
        protected SupplierContract $supplierRepository,
        protected CategoryContract $categoryRepository,
        protected SalesContract $salesRepository,
        protected BrandContract $brandRepository,
        protected SalesItemContract $salesItemRepository,
    ) {
        // You can inject any dependencies here if needed
        // For example, you might want to use the productPurchaseContract in your methods

    }

    public function index()
    {
        $shops = $this->shopRepository->all()->map(function ($shop) {
            return [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name,
            ];
        });

        $products = $this->productPurchaseRepository->all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'supplier_id' => $product->supplier_id,
                'metadata' => $product->metadata,
            ];
        });

        $suppliers = $this->supplierRepository->all()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'company_name' => $supplier->company_name,
            ];
        });


        $categories = $this->categoryRepository->all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });

        $brands = $this->brandRepository->all()->map(function ($brand) {
            return [
                'id' => $brand->id,
                'brand_name' => $brand->brand_name,
            ];
        });
        return Inertia::render('SalesManagement/CreateSale', [
            'shops' => $shops,
            'products' => $products,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store(Request $request)
    {
        $data = [
            'shop_id' => $request->shop_id,
            'supplier_id' => $request->supplier_id,
            'invoice_number' => 'INV-' . mt_rand(100000, 999999),
            'total_amount' => collect($request->items)->sum(function ($item) {
                return $item['total_price'];
            }),
            'subtotal' => collect($request->items)->sum(function ($item) {
                return $item['total_price'];
            }),
            'status' => SalesStatus::PENDING,
            'sale_date' => $request->sale_date,
        ];

        $sale = $this->salesRepository->create($data);
        foreach ($request->items as $item) {
            $product = $this->productPurchaseRepository->find($item['product_id']);

            $variantData = collect($product->metadata['variants'])->firstWhere('variant', $item['variant']);

            if (!$variantData || $item['total_quantity'] > $variantData['quantity']) {
                return redirect()->back()->withErrors(['items' => 'Insufficient inventory for ' . $item['variant']]);
            }
            $itemsData = [
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'supplier_id' => $item['supplier_id'],
                'variant' => $item['variant'],
                'boxes_sold' => $item['boxes_sold'],
                'bottles_per_box' => $item['bottles_per_box'],
                'quantity' => $item['total_quantity'],
                'free_bottles' => $item['free_bottles'],
                'purchase_unit_price' => $item['unit_price'],
                'unit_price' => $item['new_unit_price'],
                'total_price' => $item['total_price'],
                'profit' => $item['profit'],
                'delivery_date' => $request['sale_date'],
                'invoice_number' => $data['invoice_number'],
                'status' => SalesItemsStatus::PENDING,
            ];

            $this->salesItemRepository->create($itemsData);
            // Update product inventory
            $this->productPurchaseRepository->updateInventory($product, $item['variant'], $item['total_quantity']);
        }

        return redirect()->route('sales.index')->with('success', 'Sale created successfully');
    }

}
