```vue
<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        role="dialog"
        aria-labelledby="modal-title"
    >
        <div
            class="bg-white rounded-xl p-4 max-w-xl w-full mx-4 shadow-xl animate-fade-in"
        >
            <div class="flex items-center justify-between mb-3">
                <h3
                    id="modal-title"
                    class="text-lg font-semibold text-gray-800 flex items-center"
                >
                    <svg
                        class="w-5 h-5 text-indigo-600 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    {{ t("confirmPurchase") }}
                </h3>
                <button
                    @click="$emit('close')"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    aria-label="Close modal"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
            <div class="mb-3">
                <p class="text-gray-600 text-sm mb-2">
                    {{ t("confirmPurchasePrompt") }}
                </p>
                <div
                    class="bg-indigo-50 p-3 rounded-lg border border-indigo-200"
                >
                    <!-- Cost Summary -->
                    <div class="mb-2 border border-gray-200 p-2 rounded-md">
                        <h5 class="text-sm font-semibold text-gray-700 mb-1">
                            {{ t("costSummary") }}
                        </h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div
                                class="flex items-center bg-green-50 p-2 rounded-md border border-green-200"
                            >
                                <svg
                                    class="w-4 h-4 text-green-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("totalCost") }}
                                    </p>
                                    <p class="text-xl font-bold text-green-700">
                                        ৳{{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      totalCost.toFixed(2)
                                                  )
                                                : totalCost.toFixed(2)
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-purple-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("remainingDeposit") }}
                                    </p>
                                    <p
                                        class="text-sm font-bold"
                                        :class="{
                                            'text-green-600':
                                                remainingDepositAfterPurchase >=
                                                0,
                                            'text-red-600':
                                                remainingDepositAfterPurchase <
                                                0,
                                        }"
                                    >
                                        ৳{{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      remainingDepositAfterPurchase.toFixed(
                                                          2
                                                      )
                                                  )
                                                : remainingDepositAfterPurchase.toFixed(
                                                      2
                                                  )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bottles Summary -->
                    <div class="mb-2 border border-gray-200 p-2 rounded-md">
                        <h5 class="text-sm font-semibold text-gray-700 mb-1">
                            {{ t("bottlesBreakdown") }}
                        </h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-indigo-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("totalBottles") }} ({{
                                            t("purchasedPlusFree")
                                        }})
                                    </p>
                                    <p
                                        class="text-sm font-bold text-indigo-600"
                                    >
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(totalBottles)
                                                : totalBottles
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-purple-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("purchasedBottles") }}
                                    </p>
                                    <p
                                        class="text-sm font-bold text-purple-600"
                                    >
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      totalPurchasedBottles
                                                  )
                                                : totalPurchasedBottles
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-teal-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("freeBottles") }}
                                    </p>
                                    <p class="text-sm font-bold text-teal-600">
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      totalFreeBottles
                                                  )
                                                : totalFreeBottles
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-pink-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("extraFreeBottles") }}
                                    </p>
                                    <p class="text-sm font-bold text-pink-600">
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      totalExtraFreeBottles
                                                  )
                                                : totalExtraFreeBottles
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Cases Summary -->
                    <div class="mb-2 border border-gray-200 p-2 rounded-md">
                        <h5 class="text-sm font-semibold text-gray-700 mb-1">
                            {{ t("casesBreakdown") }}
                        </h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-blue-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("totalCases") }}
                                    </p>
                                    <p class="text-sm font-bold text-blue-600">
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(totalCases)
                                                : totalCases
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-blue-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("purchasedCases") }}
                                    </p>
                                    <p class="text-sm font-bold text-blue-600">
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      totalPurchasedCases
                                                  )
                                                : totalPurchasedCases
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-teal-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("casesFromFree") }}
                                    </p>
                                    <p class="text-sm font-bold text-teal-600">
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      totalCasesFromFree
                                                  )
                                                : totalCasesFromFree
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Variants -->
                    <div class="border border-gray-200 p-2 rounded-md">
                        <h5 class="text-sm font-semibold text-gray-700 mb-1">
                            {{ t("purchaseDetails") }}
                        </h5>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 text-orange-600 mr-2"
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
                                <div>
                                    <p class="text-xs text-gray-600">
                                        {{ t("totalVariants") }}
                                    </p>
                                    <p
                                        class="text-sm font-bold text-orange-600"
                                    >
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(totalVariants)
                                                : totalVariants
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-3">
                    <button
                        @click="$emit('close')"
                        class="px-3 py-1.5 border-2 border-gray-300 rounded-lg text-gray-700 text-sm font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-300"
                    >
                        {{ t("cancel") }}
                    </button>
                    <button
                        @click="$emit('confirm')"
                        class="px-3 py-1.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 flex items-center space-x-1"
                        :disabled="
                            isLoading || remainingDepositAfterPurchase < 0
                        "
                    >
                        <svg
                            v-if="isLoading"
                            class="w-4 h-4 animate-spin"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        <span>{{
                            isLoading ? t("processing") : t("confirm")
                        }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    show: boolean;
    totalBottles: number;
    totalVariants: number;
    totalCases: number;
    totalCost: number;
    totalPurchasedBottles: number;
    totalFreeBottles: number;
    totalExtraFreeBottles: number;
    totalPurchasedCases: number;
    totalCasesFromFree: number;
    remainingDepositAfterPurchase: number;
    isLoading: boolean;
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
}>();

defineEmits(["close", "confirm"]);
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>
```
