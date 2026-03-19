<template>
    <div class="min-h-screen bg-gray-50 p-4 sm:p-6">
        <!-- Toast -->
        <div v-if="showToast" class="fixed top-6 right-6 px-5 py-4 rounded-lg shadow-lg flex items-center gap-3 z-50"
            :class="toastType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="toastType === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="font-medium text-sm">{{ toastMessage }}</span>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <Link :href="route('sales.report')" class="p-2 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Edit Sale</h1>
                    <p class="text-xs text-gray-400">{{ props.sale.invoice_number }} &middot; {{ props.sale.shop_name }} &middot; {{ props.sale.sale_date }}</p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <p class="text-xs text-gray-400">Total Amount</p>
                <p class="text-lg font-bold text-orange-600">৳{{ formatNumber(newTotal) }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 px-4 py-3">
                <h2 class="text-sm font-semibold text-white">Sale Items — Edit Price Per Case</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Cases</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Btl/Case</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase w-36">Price/Case (৳)</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="(item, index) in editableItems" :key="item.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <span class="w-5 h-5 bg-orange-500 text-white text-xs rounded-full flex items-center justify-center font-bold mx-auto">{{ index + 1 }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800 leading-tight">{{ item.product_name }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-orange-50 text-orange-700 text-xs font-semibold border border-orange-100 mt-0.5">
                                    {{ item.variant }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-700 font-medium">{{ item.cases_sold }}</td>
                            <td class="px-4 py-3 text-center text-gray-700">
                                {{ item.bottles_per_case }}
                                <span v-if="item.free_bottles_sold > 0" class="text-green-600 text-xs ml-1">+{{ Math.round(item.free_bottles_sold / item.cases_sold) }} free</span>
                            </td>
                            <td class="px-4 py-3">
                                <input
                                    v-model.number="item.selling_price_per_case"
                                    type="number" min="0" step="0.01"
                                    class="w-full px-2 py-1.5 rounded-md border border-gray-200 text-sm focus:border-orange-400 focus:ring-1 focus:ring-orange-200 outline-none"
                                />
                                <p class="text-[10px] text-gray-400 mt-0.5">Was: ৳{{ formatNumber(item.original_price_per_case ?? 0) }}</p>
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-orange-600">
                                ৳{{ formatNumber(getItemSubtotal(item)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Edit Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-green-600 to-green-500 px-4 py-3">
                <h2 class="text-sm font-semibold text-white">Payment Details</h2>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Paid Amount (৳)</label>
                    <input
                        v-model.number="editPayment.amount"
                        type="number" min="0" step="0.01"
                        class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 focus:ring-1 focus:ring-green-200 outline-none"
                    />
                    <p class="text-[10px] text-gray-400 mt-0.5">Original paid: ৳{{ formatNumber(props.sale.paid_amount) }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Payment Method</label>
                    <select
                        v-model="editPayment.payment_method"
                        class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 focus:ring-1 focus:ring-green-200 outline-none bg-white"
                    >
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Summary + Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between gap-8">
                        <span class="text-gray-500">Original Total:</span>
                        <span class="font-medium text-gray-700">৳{{ formatNumber(props.sale.total_amount) }}</span>
                    </div>
                    <div class="flex justify-between gap-8 pt-1 border-t border-gray-100">
                        <span class="font-semibold text-gray-700">New Total:</span>
                        <span class="font-bold text-orange-600 text-base">৳{{ formatNumber(newTotal) }}</span>
                    </div>
                    <div class="flex justify-between gap-8">
                        <span class="text-gray-500">Paid Amount:</span>
                        <span class="font-medium text-green-600">৳{{ formatNumber(editPayment.amount) }}</span>
                    </div>
                    <div class="flex justify-between gap-8">
                        <span class="text-gray-500">New Due:</span>
                        <span class="font-semibold" :class="newDue > 0 ? 'text-red-600' : 'text-green-600'">৳{{ formatNumber(newDue) }}</span>
                    </div>
                </div>

                <div class="flex gap-2 sm:flex-col sm:items-end">
                    <Link :href="route('sales.report')" class="px-4 py-2 text-sm border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </Link>
                    <button
                        @click="submitUpdate"
                        :disabled="isLoading"
                        class="px-6 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
                    >
                        <svg v-if="isLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        {{ isLoading ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import Layout from '../../Layout.vue';

defineOptions({ layout: Layout });

interface SaleItem {
    id: number;
    product_name: string;
    variant: string;
    cases_sold: number;
    bottles_per_case: number;
    free_bottles_sold: number;
    total_bottles_sold: number;
    target_bottles_to_sell: number;
    selling_price_per_bottle: number;
    selling_price_per_case: number;
    original_price_per_case?: number;
    total_price: number;
    purchase_unit_price: number;
    profit: number;
}

interface Sale {
    id: number;
    invoice_number: string;
    sale_date: string;
    total_amount: number;
    paid_amount: number;
    due_amount: number;
    shop_name: string;
}

interface Payment {
    id: number;
    amount: number;
    payment_method: string;
    status: string;
}

const props = defineProps<{ sale: Sale; items: SaleItem[]; payment: Payment | null }>();

// Make items editable, store original price for reference
const editableItems = ref<SaleItem[]>(
    props.items.map(item => ({
        ...item,
        original_price_per_case: item.selling_price_per_case,
    }))
);

const editPayment = ref({
    amount: props.payment?.amount ?? props.sale.paid_amount,
    payment_method: props.payment?.payment_method ?? 'cash',
});

const isLoading = ref(false);
const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');

const formatNumber = (v: number | string) =>
    Number(v || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

const getItemSubtotal = (item: SaleItem): number => {
    const effectiveBPC = item.bottles_per_case + (item.cases_sold > 0 ? Math.round(item.free_bottles_sold / item.cases_sold) : 0);
    const pricePerBottle = effectiveBPC > 0 ? item.selling_price_per_case / effectiveBPC : 0;
    return Math.round(item.target_bottles_to_sell * pricePerBottle * 100) / 100;
};

const newTotal = computed(() => editableItems.value.reduce((sum, item) => sum + getItemSubtotal(item), 0));
const newDue = computed(() => Math.max(0, newTotal.value - editPayment.value.amount));

const showToastMsg = (msg: string, type: 'success' | 'error' = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => { showToast.value = false; }, 3000);
};

const submitUpdate = () => {
    isLoading.value = true;
    router.put(`/sales/${props.sale.id}`, {
        items: editableItems.value.map(item => ({
            id: item.id,
            selling_price_per_case: item.selling_price_per_case,
        })),
        payment_amount: editPayment.value.amount,
        payment_method: editPayment.value.payment_method,
    }, {
        onSuccess: () => {
            isLoading.value = false;
            showToastMsg('Sale updated successfully!');
        },
        onError: (errors) => {
            isLoading.value = false;
            const msg = Object.values(errors)[0] as string || 'Update failed';
            showToastMsg(msg, 'error');
        },
    });
};
</script>
