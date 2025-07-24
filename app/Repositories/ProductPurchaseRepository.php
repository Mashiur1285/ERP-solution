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

        // First, collect all individual variant records
        $rawData = collect(DB::select($query))->map(function ($item) {
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
        });

        // Group by product_name first, then by variant within each product
        return $rawData->groupBy('product_name')->map(function ($productGroup, $productName) {
            // Within each product, group by variant name and sum the quantities
            $variantGroups = $productGroup->groupBy('variant')->map(function ($variantGroup, $variantName) {
                // Sum all quantities, boxes, and total_values for the same variant
                $totalQuantity = $variantGroup->sum('quantity');
                $totalBoxes = $variantGroup->sum('boxes');
                $totalValue = $variantGroup->sum('total_value');

                // Use the first item's unit_price and bottles_per_box (assuming they're the same for same variant)
                $firstItem = $variantGroup->first();

                return [
                    'variant' => $variantName,
                    'quantity' => $totalQuantity,
                    'unit_price' => $firstItem['unit_price'],
                    'bottles_per_box' => $firstItem['bottles_per_box'],
                    'boxes' => $totalBoxes,
                    'total_value' => $totalValue,
                ];
            });

            return [
                'product_name' => $productName,
                'variants' => $variantGroups->values(),
                'total_quantity' => $variantGroups->sum('quantity'),
                'total_boxes' => $variantGroups->sum('boxes'),
                'total_value' => $variantGroups->sum('total_value'),
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
