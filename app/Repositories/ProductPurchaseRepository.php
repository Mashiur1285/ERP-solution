<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Deposit;
use App\Contracts\ProductPurchaseContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductPurchaseRepository extends BaseRepository implements ProductPurchaseContract
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
    public function purchaseHistory(): Collection
    {
        $query = "
            SELECT
                suppliers.company_name as supplier_name,
                products.name as product_name,
                products.date as purchase_date,
                variant_data
            FROM products
            INNER JOIN suppliers ON products.supplier_id = suppliers.id
            LEFT JOIN LATERAL jsonb_array_elements(COALESCE(products.metadata->'variants', '[]'::jsonb)) as variant_data ON true
            WHERE products.deleted_at IS NULL
            ORDER BY products.date DESC
        ";

        return collect(DB::select($query))->map(function ($purchase) {
            $variant = json_decode($purchase->variant_data, true) ?: [];
            return [
                'supplier_name' => $purchase->supplier_name,
                'product_name' => $purchase->product_name,
                'variant' => $variant['variant'] ?? 'N/A',
                'bottles_per_box' => $variant['bottles_per_box'] ?? 0,
                'quantity' => $variant['quantity'] ?? 0,
                'free_bottles' => $variant['free_bottles'] ?? 0,
                'unit_price' => $variant['unit_price'] ?? 0,
                'total_value' => ($variant['quantity'] ?? 0) * ($variant['unit_price'] ?? 0),
                'purchase_date' => $purchase->purchase_date,
            ];
        });
    }
}
