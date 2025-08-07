<template>
    <div
        class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-xl border border-indigo-200"
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
                                    ? toBengaliNumber(saleSummary.itemCount)
                                    : saleSummary.itemCount
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
                                    ? toBengaliNumber(saleSummary.totalCases)
                                    : saleSummary.totalCases.toLocaleString()
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Bottles -->
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
                                          saleSummary.totalBottlesSold
                                      )
                                    : saleSummary.totalBottlesSold.toLocaleString()
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
                                          saleSummary.totalAmount.toFixed(2)
                                      )
                                    : saleSummary.totalAmount.toLocaleString(
                                          "en-US",
                                          {
                                              minimumFractionDigits: 2,
                                              maximumFractionDigits: 2,
                                          }
                                      )
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Analysis -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
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
                            saleSummary.totalProfit >= 0
                                ? 'text-green-600'
                                : 'text-red-600'
                        "
                    >
                        ৳{{
                            currentLanguage === "bn"
                                ? toBengaliNumber(
                                      saleSummary.totalProfit.toFixed(2)
                                  )
                                : saleSummary.totalProfit.toLocaleString(
                                      "en-US",
                                      {
                                          minimumFractionDigits: 2,
                                          maximumFractionDigits: 2,
                                      }
                                  )
                        }}
                    </p>
                    <p
                        class="text-xs"
                        :class="
                            saleSummary.totalProfit >= 0
                                ? 'text-green-500'
                                : 'text-red-500'
                        "
                    >
                        {{
                            saleSummary.totalProfit >= 0
                                ? t("profit")
                                : t("loss")
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Items Details -->
        <div v-if="saleForm.items.length > 0" class="mt-4">
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
                            {{ item.variant }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(item.cases_sold)
                                    : item.cases_sold
                            }}
                            {{ t("cases") }} × ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          item.selling_price_per_case?.toFixed(
                                              2
                                          ) || "0.00"
                                      )
                                    : item.selling_price_per_case?.toFixed(2) ||
                                      "0.00"
                            }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">
                            ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          getItemTotal(item).toFixed(2)
                                      )
                                    : getItemTotal(item).toFixed(2)
                            }}
                        </p>
                        <p class="text-xs text-purple-600">
                            {{ t("profit") }}: ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(
                                          getItemProfit(item).toFixed(2)
                                      )
                                    : getItemProfit(item).toFixed(2)
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
interface SaleItem {
    product_id: number;
    variant: string;
    cases_sold: number;
    selling_price_per_case: number;
    free_bottles_per_case: number;
    extra_free_bottles: number;
    bottles_per_case: number;
    purchase_rate: number;
}

interface Product {
    product_id: number;
    product_name: string;
    variants: any[];
}

const props = defineProps<{
    saleForm: {
        items: SaleItem[];
    };
    saleSummary: {
        totalCases: number;
        totalAmount: number;
        totalProfit: number;
        totalBottlesSold: number;
        itemCount: number;
    };
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
}>();

// Helper functions
const getItemTotal = (item: SaleItem): number => {
    return (item.cases_sold || 0) * (item.selling_price_per_case || 0);
};

const getItemProfit = (item: SaleItem): number => {
    const sellPrice = getItemTotal(item);

    // Calculate total bottles sold (including free bottles if toggle is on)
    const purchasedBottles =
        (item.cases_sold || 0) * (item.bottles_per_case || 0);
    let freeBottles = 0;

    // This should match the logic from the parent component
    // We need to access the includeFreeBottles state somehow
    // For now, check if free bottle fields have values
    if (item.free_bottles_per_case || item.extra_free_bottles) {
        freeBottles =
            (item.cases_sold || 0) * (item.free_bottles_per_case || 0) +
            (item.extra_free_bottles || 0);
    }

    const totalBottlesSold = purchasedBottles + freeBottles;
    const purchaseCost = totalBottlesSold * (item.purchase_rate || 0);

    return sellPrice - purchaseCost;
};

const getProductName = (productId: number): string => {
    // This would typically come from a products prop or be fetched
    // For now, return a placeholder
    return `Product ${productId}`;
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
