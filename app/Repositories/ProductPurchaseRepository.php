<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Deposit;
use App\Contracts\ProductPurchaseContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
                pc.image_path,
                variant_data
            FROM products
            INNER JOIN suppliers ON products.supplier_id = suppliers.id
            LEFT JOIN product_catalog pc ON products.product_catalog_id = pc.id
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
                'image_url' => $purchase->image_path ? '/storage/' . ltrim($purchase->image_path, '/') : null,
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

    public function getInventoryStock(?string $snapshotDate = null): Collection
    {
        $snapshotDate = $snapshotDate ?: now()->toDateString();

        $salesByBatchAndVariant = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'sale_items.product_id',
                'sale_items.variant',
                DB::raw('SUM(sale_items.purchased_bottles_sold) as sold_purchased_bottles'),
                DB::raw('SUM(sale_items.free_bottles_sold) as sold_free_bottles'),
                DB::raw('SUM(sale_items.total_bottles_sold) as sold_total_bottles')
            )
            ->where('sales.status', '!=', 'draft')
            ->whereDate('sales.sale_date', '<=', $snapshotDate)
            ->groupBy('sale_items.product_id', 'sale_items.variant')
            ->get()
            ->keyBy(fn ($row) => $row->product_id . '::' . $row->variant);

        $query = "
        SELECT
            products.id,
            products.name as product_name,
            products.supplier_id,
            products.date as purchase_date,
            pc.image_path,
            suppliers.company_name as supplier_name,
            variant_data
        FROM products
        INNER JOIN suppliers ON products.supplier_id = suppliers.id
        LEFT JOIN product_catalog pc ON products.product_catalog_id = pc.id
        LEFT JOIN LATERAL jsonb_array_elements(COALESCE(products.metadata->'variants', '[]'::jsonb)) as variant_data ON true
        WHERE products.deleted_at IS NULL
        ";

        // First, collect all individual variant records
        $rawData = collect(DB::select($query))->map(function ($item) use ($salesByBatchAndVariant) {
            $variant = json_decode($item->variant_data, true) ?: [];

            // CORRECTED: Calculate initial purchased bottles correctly
            $purchasedCases = $variant['cases_without_free_bottles'] ?? 0;
            $bottlesPerCase = $variant['bottles_per_case'] ?? 0;
            $initialPurchasedBottles = $purchasedCases * $bottlesPerCase;

            $initialFreeBottles = $variant['total_free_bottles'] ?? 0;

            $salesKey = $item->id . '::' . ($variant['variant'] ?? 'N/A');
            $soldData = $salesByBatchAndVariant->get($salesKey);
            $soldPurchased = (int) round($soldData->sold_purchased_bottles ?? 0);
            $soldFree = (int) round($soldData->sold_free_bottles ?? 0);
            $soldTotal = (int) round($soldData->sold_total_bottles ?? ($soldPurchased + $soldFree));

            $currentPurchased = max(0, $initialPurchasedBottles - $soldPurchased);
            $currentFree = max(0, $initialFreeBottles - $soldFree);

            return [
                'product_id' => $item->id,
                'product_name' => $item->product_name,
                'image_url' => $item->image_path ? '/storage/' . ltrim($item->image_path, '/') : null,
                'supplier_id' => $item->supplier_id,
                'supplier_name' => $item->supplier_name,
                'purchase_date' => $item->purchase_date,
                'variant' => $variant['variant'] ?? 'N/A',
                'purchased_bottles_total' => $initialPurchasedBottles,
                'free_bottles_total' => $initialFreeBottles,
                'purchased_bottles_available' => $currentPurchased,
                'free_bottles_available' => $currentFree,
                'total_bottles_available' => $currentPurchased + $currentFree,
                'total_bottles_sold' => $soldTotal,
                'unit_price' => floatval($variant['actual_rate_per_bottle'] ?? 0),
                'bottles_per_case' => $bottlesPerCase,
                'cases_available' => $bottlesPerCase ? floor(($currentPurchased + $currentFree) / $bottlesPerCase) : 0,
                'purchase_rate' => floatval($variant['actual_rate_per_bottle'] ?? 0),
                'variant_metadata' => $variant,
            ];
        })->filter(function ($item) use ($snapshotDate) {
            if (empty($item['purchase_date'])) {
                return false;
            }

            $purchaseDate = date('Y-m-d', strtotime((string) $item['purchase_date']));
            if ($purchaseDate > $snapshotDate) {
                return false;
            }

            // Exclude variants with no name (N/A fallback from missing variant field)
            if (($item['variant'] ?? 'N/A') === 'N/A') return false;
            return $item['total_bottles_available'] >= 0; // Include 0-stock items so they remain visible
        });

        // Group by product_name and then aggregate by variant
        return $rawData->groupBy('product_name')->map(function ($productGroup, $productName) {
            // Aggregate variants by variant name
            $aggregatedVariants = $productGroup->groupBy('variant')->map(function ($variantGroup, $variantName) {
                // Aggregate metrics for the same variant
                $totalPurchasedBottles = $variantGroup->sum('purchased_bottles_available');
                $totalFreeBottles = $variantGroup->sum('free_bottles_available');
                $totalBottlesAvailable = $variantGroup->sum('total_bottles_available');
                $totalSoldBottles = $variantGroup->sum('total_bottles_sold');
                $totalPurchasedBottlesInitial = $variantGroup->sum('purchased_bottles_total');
                $totalFreeBottlesInitial = $variantGroup->sum('free_bottles_total');
                $bottlesPerCase = $variantGroup->first()['bottles_per_case'] ?? 0;
                $totalCasesAvailable = $bottlesPerCase ? floor($totalBottlesAvailable / $bottlesPerCase) : 0;

                // Use the most recent unit_price (or average if needed)
                $unitPrice = $variantGroup->avg('unit_price');

                // Use the most recent variant_metadata
                $variantMetadata = $variantGroup->first()['variant_metadata'];

                return [
                    'product_id' => $variantGroup->first()['product_id'],
                    'product_name' => $variantGroup->first()['product_name'],
                    'image_url' => $variantGroup->first()['image_url'],
                    'supplier_id' => $variantGroup->first()['supplier_id'],
                    'supplier_name' => $variantGroup->first()['supplier_name'],
                    'purchase_date' => $variantGroup->first()['purchase_date'],
                    'variant' => $variantName,
                    'purchased_bottles_total' => $totalPurchasedBottlesInitial,
                    'free_bottles_total' => $totalFreeBottlesInitial,
                    'purchased_bottles_available' => $totalPurchasedBottles,
                    'free_bottles_available' => $totalFreeBottles,
                    'total_bottles_available' => $totalBottlesAvailable,
                    'total_bottles_sold' => $totalSoldBottles,
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
                'image_url' => $productGroup->first()['image_url'],
                'supplier_id' => $productGroup->first()['supplier_id'],
                'supplier_name' => $productGroup->first()['supplier_name'],
                'purchase_date' => $productGroup->first()['purchase_date'],
                'variants' => $aggregatedVariants,
                'total_bottles_sold' => $aggregatedVariants->sum('total_bottles_sold'),
                'total_available_bottles' => $aggregatedVariants->sum('total_bottles_available'),
                'total_available_cases' => $aggregatedVariants->sum('cases_available'),
            ];
        })->values();
    }

    public function getProductsBySupplier(int $supplierId): Collection
    {
        return $this->getInventoryStock()->where('supplier_id', $supplierId)->values();
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
            'free_bottles_per_case' => $variantData['free_bottles_per_case'] ?? 0,
            'cases_without_free_bottles' => $variantData['cases_without_free_bottles'] ?? 0,
            'cases_with_free_bottles' => $variantData['cases_with_free_bottles'] ?? 0,
            'total_cases' => $variantData['number_of_cases'] ?? 0,
        ];
    }

    public function getTopSellingProducts(int $limit): Collection
    {
        return DB::table('sale_items')
            ->select('products.name', DB::raw('SUM(sale_items.total_bottles_sold) as total_sold'))
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    public function getLowStockProducts(int $threshold): Collection
    {
        return $this->getInventoryStock()->filter(function ($product) use ($threshold) {
            return $product['total_available_bottles'] < $threshold;
        });
    }
}
