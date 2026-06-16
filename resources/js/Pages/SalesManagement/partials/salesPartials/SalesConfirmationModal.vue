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
                    {{ isEditMode ? 'Confirm Sale Update' : t("confirmSale") }}
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
                    {{ isEditMode ? 'Please review the updated sale summary and adjust the payment if necessary.' : t("confirmSalePrompt") }}
                </p>

                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                    <!-- Sale Summary -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div class="text-center p-3 bg-white rounded-lg border border-indigo-100">
                            <p class="text-lg font-bold text-indigo-600">
                                {{ currentLanguage === "bn" ? toBengaliNumber(saleSummary.itemCount || 0) : saleSummary.itemCount || 0 }}
                            </p>
                            <p class="text-xs text-indigo-600 font-medium">{{ t("totalItems") }}</p>
                        </div>

                        <div class="text-center p-3 bg-white rounded-lg border border-blue-100">
                            <p class="text-lg font-bold text-blue-600">
                                {{ currentLanguage === "bn" ? toBengaliNumber(saleSummary.totalCases || 0) : saleSummary.totalCases || 0 }}
                            </p>
                            <p class="text-xs text-blue-600 font-medium">{{ t("totalCases") }}</p>
                        </div>

                        <div class="text-center p-3 bg-white rounded-lg border border-orange-100">
                            <p class="text-lg font-bold text-orange-600">
                                {{ currentLanguage === "bn" ? toBengaliNumber(saleSummary.totalBottlesToSell || 0) : (saleSummary.totalBottlesToSell || 0).toLocaleString() }}
                            </p>
                            <p class="text-xs text-orange-600 font-medium">{{ t("totalBottles") }}</p>
                        </div>

                        <div class="text-center p-3 bg-white rounded-lg border border-green-100">
                            <p class="text-lg font-bold text-green-600">
                                ৳{{ currentLanguage === "bn" ? toBengaliNumber(formatNumber(saleSummary.totalAmount || 0, 2)) : formatNumber(saleSummary.totalAmount || 0, 2) }}
                            </p>
                            <p class="text-xs text-green-600 font-medium">{{ t("totalAmount") }}</p>
                        </div>
                    </div>

                    <!-- Profit Analysis -->
                    <div class="bg-white p-3 rounded-lg border border-purple-100 mb-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <p class="text-sm font-medium text-purple-600">{{ t("expectedProfit") }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold" :class="(saleSummary.totalProfit || 0) >= 0 ? 'text-green-600' : 'text-red-600'">
                                    ৳{{ currentLanguage === "bn" ? toBengaliNumber(formatNumber(saleSummary.totalProfit || 0, 2)) : formatNumber(saleSummary.totalProfit || 0, 2) }}
                                </p>
                                <p class="text-xs" :class="(saleSummary.totalProfit || 0) >= 0 ? 'text-green-500' : 'text-red-500'">
                                    {{ (saleSummary.totalProfit || 0) >= 0 ? t("profit") : t("loss") }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Adjustment Section (Always show in edit mode) -->
                    <div v-if="isEditMode" class="mt-4 p-4 bg-orange-100 rounded-lg border border-orange-200">
                        <h4 class="text-sm font-bold text-orange-800 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Adjust Payment
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-orange-700 mb-1">Amount Paid (৳)</label>
                                <input 
                                    type="number" 
                                    :value="paymentAmount"
                                    @input="e => emit('update:paymentAmount', Number(e.target.value))"
                                    class="w-full px-3 py-2 rounded-lg border-orange-300 focus:ring-orange-500 focus:border-orange-500 text-sm font-bold"
                                    step="0.01"
                                />
                                <p class="text-[10px] text-orange-600 mt-1 font-medium">Update this if the bill total has changed.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-orange-700 mb-1">Method</label>
                                <select 
                                    :value="paymentMethod"
                                    @change="e => emit('update:paymentMethod', e.target.value)"
                                    class="w-full px-3 py-2 rounded-lg border-orange-300 focus:ring-orange-500 focus:border-orange-500 text-sm bg-white"
                                >
                                    <option value="cash">Cash</option>
                                    <option value="bkash">bKash</option>
                                    <option value="nagad">Nagad</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="bank">Bank Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button
                        @click="$emit('close')"
                        class="px-5 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 text-sm font-bold hover:bg-gray-50 hover:border-gray-400 transition-all"
                    >
                        {{ t("cancel") }}
                    </button>
                    <button
                        @click="$emit('confirm')"
                        class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all flex items-center gap-2 shadow-lg shadow-indigo-100"
                        :disabled="isLoading"
                    >
                        <svg v-if="isLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ isLoading ? t("processing") : (isEditMode ? 'Update Sale' : t("confirm")) }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    show: boolean;
    saleSummary: {
        totalCases?: number;
        totalAmount?: number;
        totalProfit?: number;
        totalBottlesToSell?: number;
        itemCount?: number;
    };
    isLoading: boolean;
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
    isEditMode?: boolean;
    paymentAmount: number;
    paymentMethod: string;
}>();

const emit = defineEmits(["close", "confirm", "update:paymentAmount", "update:paymentMethod"]);

const formatNumber = (value: any, decimals: number = 2): string => {
    const num = Number(value) || 0;
    return num.toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
};
</script>

<style scoped>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fadeIn 0.3s ease-out; }
</style>
