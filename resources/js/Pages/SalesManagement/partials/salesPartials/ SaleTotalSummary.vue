<template>
    <div
        class="bg-gradient-to-br from-gray-50 via-white to-gray-50 p-6 rounded-xl border border-indigo-200"
    >
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg
                class="w-6 h-6 text-indigo-600 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"
                />
            </svg>
            {{ t("saleSummary") }}
        </h3>

        <!-- Main Summary Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Items -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg mr-3">
                        <svg
                            class="w-5 h-5 text-orange-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">
                            {{ t("totalItems") }}
                        </p>
                        <p class="text-2xl font-bold text-orange-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          saleSummary.itemCount || 0
                                      )
                                    : saleSummary.itemCount || 0
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Cases -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg
                            class="w-5 h-5 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">
                            {{ t("totalCases") }}
                        </p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          saleSummary.totalCases || 0
                                      )
                                    : (
                                          saleSummary.totalCases || 0
                                      ).toLocaleString()
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Target Bottles to Sell -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                        <svg
                            class="w-5 h-5 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m0 0l-8-4m8 4v10"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">
                            {{ t("totalBottles") }}
                        </p>
                        <p class="text-2xl font-bold text-indigo-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          saleSummary.totalBottlesToSell || 0
                                      )
                                    : (
                                          saleSummary.totalBottlesToSell || 0
                                      ).toLocaleString()
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Amount -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg
                            class="w-5 h-5 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">
                            {{ t("totalAmount") }}
                        </p>
                        <p class="text-2xl font-bold text-green-600">
                            ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          formatNumber(
                                              saleSummary.totalAmount || 0,
                                              2
                                          )
                                      )
                                    : formatNumber(
                                          saleSummary.totalAmount || 0,
                                          2
                                      )
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottles Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Actual vs Target Bottles -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                    {{ t("bottlesBreakdown") }}
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{
                            t("targetBottles")
                        }}</span>
                        <span class="font-medium text-indigo-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          saleSummary.totalBottlesToSell || 0
                                      )
                                    : (
                                          saleSummary.totalBottlesToSell || 0
                                      ).toLocaleString()
                            }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{
                            t("actualBottles")
                        }}</span>
                        <span class="font-medium text-blue-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          saleSummary.totalActualBottles || 0
                                      )
                                    : (
                                          saleSummary.totalActualBottles || 0
                                      ).toLocaleString()
                            }}
                        </span>
                    </div>
                    <div
                        v-if="includeFreeBottles"
                        class="flex justify-between text-sm"
                    >
                        <span class="text-gray-600">{{
                            t("purchasedBottles")
                        }}</span>
                        <span class="font-medium text-blue-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          saleSummary.totalPurchasedBottles || 0
                                      )
                                    : (
                                          saleSummary.totalPurchasedBottles || 0
                                      ).toLocaleString()
                            }}
                        </span>
                    </div>
                    <div
                        v-if="includeFreeBottles"
                        class="flex justify-between text-sm"
                    >
                        <span class="text-gray-600">{{
                            t("freeBottles")
                        }}</span>
                        <span class="font-medium text-green-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          saleSummary.totalFreeBottles || 0
                                      )
                                    : (
                                          saleSummary.totalFreeBottles || 0
                                      ).toLocaleString()
                            }}
                        </span>
                    </div>
                    <hr class="border-gray-200" />
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-800">{{ t("difference") }}</span>
                        <span class="text-purple-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          (saleSummary.totalActualBottles ||
                                              0) -
                                              (saleSummary.totalBottlesToSell ||
                                                  0)
                                      )
                                    : (
                                          (saleSummary.totalActualBottles ||
                                              0) -
                                          (saleSummary.totalBottlesToSell || 0)
                                      ).toLocaleString()
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profit Analysis -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                    {{ t("profitAnalysis") }}
                </h4>
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                            <svg
                                class="w-5 h-5 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">
                                {{ t("totalProfit") }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ t("expectedProfit") }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p
                            class="text-xl font-bold"
                            :class="
                                (saleSummary.totalProfit || 0) >= 0
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                        >
                            ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          formatNumber(
                                              saleSummary.totalProfit || 0,
                                              2
                                          )
                                      )
                                    : formatNumber(
                                          saleSummary.totalProfit || 0,
                                          2
                                      )
                            }}
                        </p>
                        <p
                            class="text-xs"
                            :class="
                                (saleSummary.totalProfit || 0) >= 0
                                    ? 'text-green-500'
                                    : 'text-red-500'
                            "
                        >
                            {{
                                (saleSummary.totalProfit || 0) >= 0
                                    ? t("profit")
                                    : t("loss")
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Details -->
        <div v-if="saleForm.items && saleForm.items.length > 0" class="mt-4">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                {{ t("itemsBreakdown") }}
            </h4>
            <div class="space-y-2">
                <div
                    v-for="(item, index) in saleForm.items"
                    :key="index"
                    class="bg-white p-3 rounded-lg border border-gray-100 flex justify-between items-center"
                >
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">
                            {{ getProductName(item.product_id) }} -
                            {{ item.variant || "N/A" }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(item.cases_to_sell || 0)
                                    : item.cases_to_sell || 0
                            }}
                            {{ t("cases") }} × ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          formatNumber(
                                              item.selling_price_per_case || 0,
                                              2
                                          )
                                      )
                                    : formatNumber(
                                          item.selling_price_per_case || 0,
                                          2
                                      )
                            }}
                        </p>
                        <!-- Show calculated cases info -->
                        <p class="text-xs text-blue-600">
                            {{ getCalculatedCases(item) }} {{ t("cases") }}
                            <span
                                v-if="
                                    includeFreeBottles &&
                                    item.purchase_metadata &&
                                    (item.purchase_metadata
                                        .free_bottles_per_case || 0) > 0
                                "
                            >
                                ({{ t("includes") }}
                                {{
                                    currentLanguage === "bn"
                                        ? toBengaliNumber(
                                              getItemFreeBottles(item)
                                          )
                                        : getItemFreeBottles(item)
                                }}
                                {{ t("freeBottles") }})
                            </span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">
                            ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          formatNumber(getItemTotal(item), 2)
                                      )
                                    : formatNumber(getItemTotal(item), 2)
                            }}
                        </p>
                        <p class="text-xs text-purple-600">
                            {{ t("profit") }}: ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          formatNumber(getItemProfit(item), 2)
                                      )
                                    : formatNumber(getItemProfit(item), 2)
                            }}
                        </p>
                        <p class="text-xs text-indigo-600">
                            {{ t("actualBottles") }}:
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          getItemActualBottles(item)
                                      )
                                    : getItemActualBottles(item)
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface SaleItem {
    product_id: number;
    variant: string;
    cases_to_sell: number;
    selling_price_per_case: number;
    bottles_per_case: number;
    purchase_rate: number;
    purchase_metadata?: {
        free_bottles_per_case?: number;
        case_buying_price?: number;
    };
    // Calculated fields
    total_bottles_to_sell?: number;
    selling_price_per_bottle?: number;
}

interface Product {
    product_id: number;
    product_name: string;
    variants: any[];
}

const props = defineProps<{
    saleForm: {
        items: SaleItem[];
        include_free_bottles: boolean;
    };
    saleSummary: {
        totalCases?: number;
        totalAmount?: number;
        totalProfit?: number;
        totalBottlesToSell?: number;
        totalActualBottles?: number;
        totalPurchasedBottles?: number;
        totalFreeBottles?: number;
        itemCount?: number;
    };
    includeFreeBottles: boolean;
    availableProducts: Product[];
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
}>();

// Helper function to safely format numbers
const formatNumber = (value: any, decimals: number = 2): string => {
    const num = Number(value) || 0;
    return num.toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
};

// Helper functions with null safety - CORRECTED
const getItemTotal = (item: SaleItem): number => {
    if (!item.purchase_metadata || !item.bottles_per_case) return 0;

    // Calculate using the ACTUAL selling price per bottle method
    const cases = Number(item.cases_to_sell) || 0;
    const bottlesPerCase = Number(item.bottles_per_case) || 0;
    const pricePerCase = Number(item.selling_price_per_case) || 0;
    const freeBottlesPerCase =
        Number(item.purchase_metadata.free_bottles_per_case) || 0;

    // ALWAYS calculate actual selling price per bottle from "with free bottles" scenario
    const effectiveBottlesPerCaseWithFree = bottlesPerCase + freeBottlesPerCase;
    const actualSellingPricePerBottle =
        pricePerCase / effectiveBottlesPerCaseWithFree;

    if (props.includeFreeBottles) {
        // WITH free bottles: Total bottles × actual selling price per bottle
        const totalBottles = cases * effectiveBottlesPerCaseWithFree;
        return totalBottles * actualSellingPricePerBottle;
    } else {
        // WITHOUT free bottles: Only purchased bottles × actual selling price per bottle
        const totalBottles = cases * bottlesPerCase;
        return totalBottles * actualSellingPricePerBottle;
    }
};

const getEffectiveSellingPricePerCase = (item: SaleItem): number => {
    if (!item.purchase_metadata || !item.bottles_per_case)
        return Number(item.selling_price_per_case) || 0;

    const bottlesPerCase = Number(item.bottles_per_case) || 0;
    const pricePerCase = Number(item.selling_price_per_case) || 0;
    const freeBottlesPerCase =
        Number(item.purchase_metadata.free_bottles_per_case) || 0;

    // Calculate actual selling price per bottle
    const effectiveBottlesPerCaseWithFree = bottlesPerCase + freeBottlesPerCase;
    const actualSellingPricePerBottle =
        pricePerCase / effectiveBottlesPerCaseWithFree;

    if (props.includeFreeBottles) {
        return pricePerCase; // Use original input price
    } else {
        return actualSellingPricePerBottle * bottlesPerCase; // Calculate for purchased bottles only
    }
};

const getCalculatedCases = (item: SaleItem): number => {
    return Number(item.cases_to_sell) || 0;
};

const getEffectiveBottlesPerCase = (item: SaleItem): number => {
    const bottlesPerCase = Number(item.bottles_per_case) || 0;
    if (!bottlesPerCase) return 0;

    if (props.includeFreeBottles && item.purchase_metadata) {
        const freeBottlesPerCase =
            Number(item.purchase_metadata.free_bottles_per_case) || 0;
        return bottlesPerCase + freeBottlesPerCase;
    }
    return bottlesPerCase;
};

const getCalculatedTotalBottles = (item: SaleItem): number => {
    const cases = Number(item.cases_to_sell) || 0;
    const effectiveBottles = getEffectiveBottlesPerCase(item);
    return cases * effectiveBottles;
};

const getItemProfit = (item: SaleItem): number => {
    if (!item.purchase_metadata || !item.bottles_per_case) return 0;

    const totalSalePrice = getItemTotal(item);
    const actualBottlesSold = getItemActualBottles(item);
    const purchaseRate = Number(item.purchase_rate) || 0;
    const totalPurchaseCost = actualBottlesSold * purchaseRate;

    return totalSalePrice - totalPurchaseCost;
};

const getItemActualBottles = (item: SaleItem): number => {
    return getCalculatedTotalBottles(item);
};

const getItemFreeBottles = (item: SaleItem): number => {
    if (!props.includeFreeBottles || !item.purchase_metadata) return 0;

    const cases = getCalculatedCases(item);
    const freeBottlesPerCase =
        Number(item.purchase_metadata.free_bottles_per_case) || 0;
    return cases * freeBottlesPerCase;
};

const getProductName = (productId: number): string => {
    if (!props.availableProducts || !Array.isArray(props.availableProducts)) {
        return `Product ${productId}`;
    }

    const product = props.availableProducts.find(
        (p) => p && p.product_id === productId
    );
    return product ? product.product_name : `Product ${productId}`;
};
</script>

<style scoped>
.bg-gradient-to-r {
    background: linear-gradient(
        to right,
        var(--tw-gradient-from),
        var(--tw-gradient-to)
    );
}
</style>
