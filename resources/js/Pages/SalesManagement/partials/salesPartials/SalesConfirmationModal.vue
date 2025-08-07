<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        role="dialog"
        aria-labelledby="modal-title"
    >
        <div
            class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 shadow-xl animate-fade-in"
        >
            <div class="flex items-center justify-between mb-4">
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
                    {{ t("confirmSale") }}
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

            <div class="mb-4">
                <p class="text-gray-600 text-sm mb-4">
                    {{ t("confirmSalePrompt") }}
                </p>

                <div
                    class="bg-indigo-50 p-4 rounded-lg border border-indigo-200"
                >
                    <!-- Sale Summary -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div
                            class="text-center p-3 bg-white rounded-lg border border-indigo-100"
                        >
                            <p class="text-lg font-bold text-indigo-600">
                                {{
                                    currentLanguage === "bn"
                                        ? toBengaliNumber(saleSummary.itemCount)
                                        : saleSummary.itemCount
                                }}
                            </p>
                            <p class="text-xs text-indigo-600 font-medium">
                                {{ t("totalItems") }}
                            </p>
                        </div>

                        <div
                            class="text-center p-3 bg-white rounded-lg border border-blue-100"
                        >
                            <p class="text-lg font-bold text-blue-600">
                                {{
                                    currentLanguage === "bn"
                                        ? toBengaliNumber(
                                              saleSummary.totalCases
                                          )
                                        : saleSummary.totalCases
                                }}
                            </p>
                            <p class="text-xs text-blue-600 font-medium">
                                {{ t("totalCases") }}
                            </p>
                        </div>

                        <div
                            class="text-center p-3 bg-white rounded-lg border border-orange-100"
                        >
                            <p class="text-lg font-bold text-orange-600">
                                {{
                                    currentLanguage === "bn"
                                        ? toBengaliNumber(
                                              saleSummary.totalBottlesSold
                                          )
                                        : saleSummary.totalBottlesSold.toLocaleString()
                                }}
                            </p>
                            <p class="text-xs text-orange-600 font-medium">
                                {{ t("totalBottles") }}
                            </p>
                        </div>

                        <div
                            class="text-center p-3 bg-white rounded-lg border border-green-100"
                        >
                            <p class="text-lg font-bold text-green-600">
                                ৳{{
                                    currentLanguage === "bn"
                                        ? toBengaliNumber(
                                              saleSummary.totalAmount.toFixed(2)
                                          )
                                        : saleSummary.totalAmount.toFixed(2)
                                }}
                            </p>
                            <p class="text-xs text-green-600 font-medium">
                                {{ t("totalAmount") }}
                            </p>
                        </div>
                    </div>

                    <!-- Profit Analysis -->
                    <div
                        class="bg-white p-3 rounded-lg border border-purple-100"
                    >
                        <div class="flex justify-between items-center">
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
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                    />
                                </svg>
                                <div>
                                    <p
                                        class="text-sm font-medium text-purple-600"
                                    >
                                        {{ t("expectedProfit") }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-lg font-bold"
                                    :class="
                                        saleSummary.totalProfit >= 0
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    ৳{{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  saleSummary.totalProfit.toFixed(
                                                      2
                                                  )
                                              )
                                            : saleSummary.totalProfit.toFixed(2)
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
                </div>

                <div class="flex justify-end space-x-3 mt-4">
                    <button
                        @click="$emit('close')"
                        class="px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 text-sm font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-300"
                    >
                        {{ t("cancel") }}
                    </button>
                    <button
                        @click="$emit('confirm')"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 flex items-center space-x-1"
                        :disabled="isLoading"
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
    saleSummary: {
        totalCases: number;
        totalAmount: number;
        totalProfit: number;
        totalBottlesSold: number;
        itemCount: number;
    };
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
