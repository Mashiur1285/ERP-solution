<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Deposit;
use App\Contracts\ProductPurchaseContract;
use Illuminate\Database\Eloquent\Model;
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
        // Existing purchaseHistory method remains unchanged
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

    public function getInventoryStock(): Collection
    {
        $query = "
            SELECT
                products.name as product_name,
                variant_data
            FROM products
            LEFT JOIN LATERAL jsonb_array_elements(COALESCE(products.metadata->'variants', '[]'::jsonb)) as variant_data ON true
            WHERE products.deleted_at IS NULL
        ";

        return collect(DB::select($query))->map(function ($item) {
            $variant = json_decode($item->variant_data, true) ?: [];
            $quantity = $variant['quantity'] ?? 0;
            $bottles_per_box = isset($variant['bottles_per_box']) && is_numeric($variant['bottles_per_box']) && $variant['bottles_per_box'] > 0
                ? $variant['bottles_per_box']
                : null;
            return [
                'product_name' => $item->product_name,
                'variant' => $variant['variant'] ?? 'N/A',
                'quantity' => $quantity,
                'unit_price' => $variant['unit_price'] ?? 0,
                'bottles_per_box' => $bottles_per_box,
                'boxes' => $bottles_per_box ? floor($quantity / $bottles_per_box) : 0,
                'total_value' => $quantity * ($variant['unit_price'] ?? 0),
            ];
        })->groupBy('product_name')->map(function ($group) {
            return [
                'product_name' => $group->first()['product_name'],
                'variants' => $group->map(function ($item) {
                    return [
                        'variant' => $item['variant'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'bottles_per_box' => $item['bottles_per_box'],
                        'boxes' => $item['boxes'],
                        'total_value' => $item['total_value'],
                    ];
                })->values(),
                'total_quantity' => $group->sum('quantity'),
                'total_boxes' => $group->sum('boxes'),
                'total_value' => $group->sum('total_value'),
            ];
        })->values();
    }

    public function updateInventory(Model $product, string $variant, int $quantity): void
    {
        // Existing updateInventory method remains unchanged
        $metadata = $product->metadata ?? ['variants' => []];

        $variantIndex = collect($metadata['variants'])->search(function ($item) use ($variant) {
            return $item['variant'] === $variant;
        });

        if ($variantIndex === false) {
            throw new \Exception("Variant {$variant} not found for product");
        }

        $currentQuantity = $metadata['variants'][$variantIndex]['quantity'];

        if ($currentQuantity < $quantity) {
            throw new \Exception("Insufficient inventory for variant {$variant}. Available: {$currentQuantity}, Requested: {$quantity}");
        }

        $metadata['variants'][$variantIndex]['quantity'] = $currentQuantity - $quantity;

        $product->metadata = $metadata;
        $product->save();
    }
}
