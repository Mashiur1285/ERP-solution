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
            $total_bottles = $variant['total_bottles'] ?? 0;
            $total_free_bottles = $variant['total_free_bottles'] ?? 0;
            $extra_free_bottles = $variant['extra_free_bottles'] ?? 0;
            $quantity = $total_bottles - $total_free_bottles - $extra_free_bottles; // Purchased bottles
            $bottles_per_case = isset($variant['bottles_per_case']) && is_numeric($variant['bottles_per_case']) && $variant['bottles_per_case'] > 0
                ? $variant['bottles_per_case']
                : null;
            $total_cases = $bottles_per_case ? floor($total_bottles / $bottles_per_case) : 0;
            $purchased_cases = $variant['cases_without_free_bottles'] ?? 0;
            $cases_from_free = $variant['cases_with_free_bottles'] ?? 0;

            return [
                'supplier_name' => $purchase->supplier_name,
                'product_name' => $purchase->product_name,
                'variant' => $variant['variant'] ?? 'N/A',
                'bottles_per_case' => $bottles_per_case ?? 0,
                'quantity' => $quantity,
                'free_bottles' => $total_free_bottles,
                'extra_free_bottles' => $extra_free_bottles,
                'total_bottles' => $total_bottles,
                'purchased_cases' => $purchased_cases,
                'cases_from_free' => $cases_from_free,
                'unit_price' => floatval($variant['actual_rate_per_bottle'] ?? 0),
                'total_value' => floatval($variant['total_cost'] ?? 0),
                'purchase_date' => $purchase->purchase_date,
            ];
        });
    }

    public function getInventoryStock(): Collection
    {
        $query = "
        SELECT
            products.id,
            products.name as product_name,
            products.supplier_id,
            suppliers.company_name as supplier_name,
            variant_data
        FROM products
        INNER JOIN suppliers ON products.supplier_id = suppliers.id
        LEFT JOIN LATERAL jsonb_array_elements(COALESCE(products.metadata->'variants', '[]'::jsonb)) as variant_data ON true
        WHERE products.deleted_at IS NULL
        ";

        // First, collect all individual variant records
        $rawData = collect(DB::select($query))->map(function ($item) {
            $variant = json_decode($item->variant_data, true) ?: [];

            // CORRECTED: Calculate initial purchased bottles correctly
            $purchasedCases = $variant['cases_without_free_bottles'] ?? 0;
            $bottlesPerCase = $variant['bottles_per_case'] ?? 0;
            $initialPurchasedBottles = $purchasedCases * $bottlesPerCase;

            $initialFreeBottles = $variant['total_free_bottles'] ?? 0;

            // Get current inventory (subtract sold quantities)
            $currentPurchased = $variant['current_purchased_quantity'] ?? $initialPurchasedBottles;
            $currentFree = $variant['current_free_quantity'] ?? $initialFreeBottles;

            return [
                'product_id' => $item->id,
                'product_name' => $item->product_name,
                'supplier_id' => $item->supplier_id,
                'supplier_name' => $item->supplier_name,
                'variant' => $variant['variant'] ?? 'N/A',
                'purchased_bottles_available' => $currentPurchased,
                'free_bottles_available' => $currentFree,
                'total_bottles_available' => $currentPurchased + $currentFree,
                'unit_price' => floatval($variant['actual_rate_per_bottle'] ?? 0),
                'bottles_per_case' => $bottlesPerCase,
                'cases_available' => $bottlesPerCase ? floor(($currentPurchased + $currentFree) / $bottlesPerCase) : 0,
                'purchase_rate' => floatval($variant['actual_rate_per_bottle'] ?? 0),
                'variant_metadata' => $variant,
            ];
        })->filter(function ($item) {
            return $item['total_bottles_available'] > 0; // Only show items with available stock
        });

        // Group by product_name and then aggregate by variant
        return $rawData->groupBy('product_name')->map(function ($productGroup, $productName) {
            // Aggregate variants by variant name
            $aggregatedVariants = $productGroup->groupBy('variant')->map(function ($variantGroup, $variantName) {
                // Aggregate metrics for the same variant
                $totalPurchasedBottles = $variantGroup->sum('purchased_bottles_available');
                $totalFreeBottles = $variantGroup->sum('free_bottles_available');
                $totalBottlesAvailable = $variantGroup->sum('total_bottles_available');
                $bottlesPerCase = $variantGroup->first()['bottles_per_case'] ?? 0;
                $totalCasesAvailable = $bottlesPerCase ? floor($totalBottlesAvailable / $bottlesPerCase) : 0;

                // Use the most recent unit_price (or average if needed)
                $unitPrice = $variantGroup->avg('unit_price');

                // Use the most recent variant_metadata
                $variantMetadata = $variantGroup->first()['variant_metadata'];

                return [
                    'product_id' => $variantGroup->first()['product_id'],
                    'product_name' => $variantGroup->first()['product_name'],
                    'supplier_id' => $variantGroup->first()['supplier_id'],
                    'supplier_name' => $variantGroup->first()['supplier_name'],
                    'variant' => $variantName,
                    'purchased_bottles_available' => $totalPurchasedBottles,
                    'free_bottles_available' => $totalFreeBottles,
                    'total_bottles_available' => $totalBottlesAvailable,
                    'unit_price' => $unitPrice,
                    'bottles_per_case' => $bottlesPerCase,
                    'cases_available' => $totalCasesAvailable,
                    'purchase_rate' => $unitPrice,
                    'variant_metadata' => $variantMetadata,
                ];
            })->values();

            return [
                'product_name' => $productName,
                'product_id' => $productGroup->first()['product_id'],
                'supplier_id' => $productGroup->first()['supplier_id'],
                'supplier_name' => $productGroup->first()['supplier_name'],
                'variants' => $aggregatedVariants,
                'total_available_bottles' => $aggregatedVariants->sum('total_bottles_available'),
                'total_available_cases' => $aggregatedVariants->sum('cases_available'),
            ];
        })->values();
    }

    public function getProductsBySupplier(int $supplierId): Collection
    {
        return $this->getInventoryStock()->where('supplier_id', $supplierId);
    }

    public function updateInventory(Model $product, string $variant, int $soldPurchasedBottles, int $soldFreeBottles = 0): void
    {
        $metadata = $product->metadata ?? ['variants' => []];

        $variantIndex = collect($metadata['variants'])->search(function ($item) use ($variant) {
            return $item['variant'] === $variant;
        });

        if ($variantIndex === false) {
            throw new \Exception("Variant {$variant} not found for product");
        }

        $variantData = $metadata['variants'][$variantIndex];

        // CORRECTED: Calculate initial quantities properly
        $purchasedCases = $variantData['cases_without_free_bottles'] ?? 0;
        $bottlesPerCase = $variantData['bottles_per_case'] ?? 0;
        $initialPurchasedBottles = $purchasedCases * $bottlesPerCase;
        $initialFreeBottles = $variantData['total_free_bottles'] ?? 0;

        // Get current available quantities
        $currentPurchased = $variantData['current_purchased_quantity'] ?? $initialPurchasedBottles;
        $currentFree = $variantData['current_free_quantity'] ?? $initialFreeBottles;

        // Validate sufficient inventory
        if ($currentPurchased < $soldPurchasedBottles) {
            throw new \Exception("Insufficient purchased bottles for variant {$variant}. Available: {$currentPurchased}, Requested: {$soldPurchasedBottles}");
        }

        if ($currentFree < $soldFreeBottles) {
            throw new \Exception("Insufficient free bottles for variant {$variant}. Available: {$currentFree}, Requested: {$soldFreeBottles}");
        }

        // Update inventory
        $metadata['variants'][$variantIndex]['current_purchased_quantity'] = $currentPurchased - $soldPurchasedBottles;
        $metadata['variants'][$variantIndex]['current_free_quantity'] = $currentFree - $soldFreeBottles;

        $product->metadata = $metadata;
        $product->save();
    }

    public function getVariantInventory(int $productId, string $variant): ?array
    {
        $product = $this->find($productId);
        if (!$product) {
            return null;
        }

        $variantData = collect($product->metadata['variants'])->firstWhere('variant', $variant);
        if (!$variantData) {
            return null;
        }

        // CORRECTED: Calculate initial quantities properly
        $purchasedCases = $variantData['cases_without_free_bottles'] ?? 0;
        $bottlesPerCase = $variantData['bottles_per_case'] ?? 0;
        $initialPurchasedBottles = $purchasedCases * $bottlesPerCase;
        $initialFreeBottles = $variantData['total_free_bottles'] ?? 0;

        return [
            'purchased_bottles_available' => $variantData['current_purchased_quantity'] ?? $initialPurchasedBottles,
            'free_bottles_available' => $variantData['current_free_quantity'] ?? $initialFreeBottles,
            'bottles_per_case' => $variantData['bottles_per_case'] ?? 0,
            'purchase_rate' => $variantData['actual_rate_per_bottle'] ?? 0,
            'case_buying_price' => $variantData['case_buying_price'] ?? 0,
            'variant_data' => $variantData,
            // Add complete purchase metadata for frontend
            'free_bottles_per_case' => $variantData['free_bottles_per_case'] ?? 0,
            'cases_without_free_bottles' => $variantData['cases_without_free_bottles'] ?? 0,
            'cases_with_free_bottles' => $variantData['cases_with_free_bottles'] ?? 0,
            'total_cases' => $variantData['number_of_cases'] ?? 0,
        ];
    }
}
