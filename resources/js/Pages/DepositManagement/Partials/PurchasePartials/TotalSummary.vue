```vue
<template>
    <div
        v-if="totalCost > 0"
        class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-xl border border-indigo-200"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
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
            {{ t("purchaseSummary") }}
        </h3>

        <!-- Main Summary Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Bottles (Purchased + Free) -->
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
                        <p class="text-xs text-gray-500">
                            {{ t("purchasedPlusFree") }}
                        </p>
                        <p class="text-2xl font-bold text-indigo-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalBottles)
                                    : totalBottles.toLocaleString()
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Variants -->
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
                            {{ t("totalVariants") }}
                        </p>
                        <p class="text-2xl font-bold text-orange-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalVariants)
                                    : totalVariants
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Cases (Purchased + Free) -->
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
                            {{ t("totalNumberOfCases") }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ t("purchasedCases") }} + {{ t("casesFromFree") }}
                        </p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalCases)
                                    : totalCases.toLocaleString()
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Cost -->
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
                            {{ t("totalCost") }}
                        </p>
                        <p class="text-2xl font-bold text-green-600">
                            ৳{{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalCost.toFixed(2))
                                    : totalCost.toLocaleString("en-US", {
                                          minimumFractionDigits: 2,
                                          maximumFractionDigits: 2,
                                      })
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Purchased vs Total Bottles -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                    {{ t("bottlesBreakdown") }}
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{
                            t("purchasedBottles")
                        }}</span>
                        <span class="font-medium text-blue-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalPurchasedBottles)
                                    : totalPurchasedBottles.toLocaleString()
                            }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{
                            t("freeBottles")
                        }}</span>
                        <span class="font-medium text-green-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalFreeBottles)
                                    : totalFreeBottles.toLocaleString()
                            }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{
                            t("extraFreeBottles")
                        }}</span>
                        <span class="font-medium text-teal-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalExtraFreeBottles)
                                    : totalExtraFreeBottles.toLocaleString()
                            }}
                        </span>
                    </div>
                    <hr class="border-gray-200" />
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-800">{{
                            t("totalBottles")
                        }}</span>
                        <span class="text-indigo-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalBottles)
                                    : totalBottles.toLocaleString()
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Purchased vs Total Cases -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border border-gray-100"
            >
                <h4 class="text-sm font-semibold text-gray-700 mb-3">
                    {{ t("casesBreakdown") }}
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{
                            t("purchasedCases")
                        }}</span>
                        <span class="font-medium text-blue-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalPurchasedCases)
                                    : totalPurchasedCases.toLocaleString()
                            }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{
                            t("casesFromFree")
                        }}</span>
                        <span class="font-medium text-green-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalCasesFromFree)
                                    : totalCasesFromFree.toLocaleString()
                            }}
                        </span>
                    </div>
                    <hr class="border-gray-200" />
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-800">{{
                            t("totalNumberOfCases")
                        }}</span>
                        <span class="text-indigo-600">
                            {{
                                currentLanguage === "bn"
                                    ? toBengaliNumber(totalCases)
                                    : totalCases.toLocaleString()
                            }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remaining Deposit -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">
                {{ t("remainingDeposit") }}
            </h4>
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-200 rounded-lg mr-3">
                        <svg
                            class="w-5 h-5 text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">
                            {{ t("remainingDeposit") }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ t("afterPurchase") }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p
                        class="text-xl font-bold"
                        :class="{
                            'text-green-600':
                                remainingDepositAfterPurchase >= 0,
                            'text-red-600': remainingDepositAfterPurchase < 0,
                        }"
                    >
                        ৳{{
                            currentLanguage === "bn"
                                ? toBengaliNumber(
                                      remainingDepositAfterPurchase.toFixed(2)
                                  )
                                : remainingDepositAfterPurchase.toLocaleString(
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
                        :class="{
                            'text-green-500':
                                remainingDepositAfterPurchase >= 0,
                            'text-red-500': remainingDepositAfterPurchase < 0,
                        }"
                    >
                        {{
                            remainingDepositAfterPurchase >= 0
                                ? t("sufficient")
                                : t("insufficient")
                        }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    productForm: {
        supplier_id: string;
        variants: Array<any>;
    };
    totalBottles: number;
    totalVariants: number;
    totalCases: number;
    totalPurchasedCases: number;
    totalPurchasedBottles: number;
    totalFreeBottles: number;
    totalCasesFromFree: number;
    totalExtraFreeBottles: number;
    totalCost: number;
    remainingDepositAfterPurchase: number;
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
}>();
</script>

<style scoped>
.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}
</style>
```
