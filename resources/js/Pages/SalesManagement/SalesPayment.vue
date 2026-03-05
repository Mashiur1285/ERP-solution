<template>
    <div
        class="max-w-3xl mx-auto bg-white p-4 sm:p-8 rounded-2xl shadow-2xl transform transition-all duration-300 hover:shadow-3xl"
    >
        <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 mb-6 sm:mb-8 text-center">
            Record Payment
        </h1>

        <div class="space-y-8 bg-gray-50 p-8 rounded-xl border border-gray-200">
            <div
                class="space-y-6 bg-white p-6 rounded-lg border border-blue-500 shadow-md"
            >
                <h2
                    class="text-2xl font-semibold text-gray-800 flex items-center"
                >
                    Add Payment
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-lg font-semibold text-gray-800 flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2 text-blue-600"
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
                            Total Amount
                        </label>
                        <input
                            :value="formatCurrency(sale.total_amount)"
                            type="text"
                            class="mt-2 block w-full rounded-lg border-gray-200 bg-gray-100 py-4 px-5 text-lg text-gray-800 font-medium shadow-sm cursor-not-allowed"
                            readonly
                        />
                    </div>
                    <div>
                        <label
                            for="payment_amount"
                            class="block text-lg font-semibold text-gray-800 flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2 text-blue-600"
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
                            Payment Amount*
                        </label>
                        <input
                            v-model.number="paymentForm.amount"
                            id="payment_amount"
                            type="number"
                            min="0"
                            step="0.01"
                            :max="maxPaymentAmount"
                            placeholder="Enter payment amount"
                            class="mt-2 block w-full rounded-lg border-gray-300 bg-white py-4 px-5 text-lg text-gray-900 font-medium shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                            :class="{ 'border-red-500': paymentError }"
                            required
                            @input="calculateDue"
                        />
                        <p class="mt-1 text-sm text-gray-600">
                            Max: {{ formatCurrency(maxPaymentAmount) }}
                        </p>
                        <p
                            v-if="paymentError"
                            class="mt-1 text-sm text-red-600 flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ paymentError }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-lg font-semibold text-gray-800 flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2 text-blue-600"
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
                            Due Amount
                        </label>
                        <input
                            :value="formatCurrency(dueAmount)"
                            type="text"
                            class="mt-2 block w-full rounded-lg border-gray-200 bg-gray-100 py-4 px-5 text-lg text-gray-800 font-medium shadow-sm cursor-not-allowed"
                            :class="{
                                'text-red-600 font-semibold': dueAmount > 0,
                            }"
                            readonly
                        />
                    </div>
                    <div>
                        <label
                            for="payment_method"
                            class="block text-lg font-semibold text-gray-800 flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                />
                            </svg>
                            Payment Method*
                        </label>
                        <select
                            v-model="paymentForm.payment_method"
                            id="payment_method"
                            class="mt-2 block w-full rounded-lg border-gray-300 bg-white py-4 px-5 text-lg text-gray-900 font-medium shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                            required
                            @change="onPaymentMethodChange"
                        >
                            <option value="" disabled>
                                Select payment method
                            </option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="mfs">MFS</option>
                        </select>
                    </div>
                    <div v-if="paymentForm.payment_method === 'mfs'">
                        <label
                            for="mfs_provider"
                            class="block text-lg font-semibold text-gray-800 flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                />
                            </svg>
                            MFS Provider*
                        </label>
                        <select
                            v-model="paymentForm.mfs_provider"
                            id="mfs_provider"
                            class="mt-2 block w-full rounded-lg border-gray-300 bg-white py-4 px-5 text-lg text-gray-900 font-medium shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                            required
                        >
                            <option value="" disabled>
                                Select MFS provider
                            </option>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="rocket">Rocket</option>
                        </select>
                    </div>
                </div>
            </div>

            <div
                class="space-y-6 bg-gray-100 p-6 rounded-lg border border-gray-300 opacity-75"
            >
                <h2 class="text-xl font-medium text-gray-700 flex items-center">
                    <svg
                        class="w-5 h-5 mr-2 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                        />
                    </svg>
                    Additional Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-600 flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-1 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            Invoice Number
                        </label>
                        <input
                            :value="sale.invoice_number"
                            type="text"
                            class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 py-2 px-3 text-sm text-gray-600 shadow-sm cursor-not-allowed"
                            readonly
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-600 flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-1 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4"
                                />
                            </svg>
                            Shop
                        </label>
                        <input
                            :value="sale.shop_name"
                            type="text"
                            class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 py-2 px-3 text-sm text-gray-600 shadow-sm cursor-not-allowed"
                            readonly
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-medium text-gray-600 flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-1 text-gray-500"
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
                            Current Advance Balance
                        </label>
                        <input
                            :value="formatCurrency(sale.advance_balance)"
                            type="text"
                            class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 py-2 px-3 text-sm text-gray-600 shadow-sm cursor-not-allowed"
                            readonly
                        />
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end mt-6 sm:mt-8 gap-3 sm:space-x-4">
                <button
                    @click="openModal"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 transition duration-200 flex items-center"
                    :disabled="
                        !paymentForm.payment_method ||
                        (paymentForm.payment_method === 'mfs' &&
                            !paymentForm.mfs_provider) ||
                        paymentError ||
                        isSubmitting ||
                        paymentForm.amount <= 0
                    "
                >
                    <svg
                        v-if="!isSubmitting"
                        class="w-5 h-5 mr-2"
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
                    <svg
                        v-else
                        class="w-5 h-5 mr-2 animate-spin"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 12a8 8 0 0116 0 8 8 0 01-16 0"
                        />
                    </svg>
                    {{ isSubmitting ? "Submitting..." : "Submit Payment" }}
                </button>
                <button
                    @click="skipPayment"
                    class="px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg shadow-md hover:from-gray-600 hover:to-gray-700 transition duration-200 flex items-center"
                >
                    <svg
                        class="w-5 h-5 mr-2"
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
                    Skip Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        role="dialog"
        aria-labelledby="modal-title"
    >
        <div
            class="bg-white rounded-2xl p-6 max-w-lg w-full mx-4 shadow-xl animate-fade-in"
        >
            <div class="flex items-center justify-between mb-4">
                <h3
                    id="modal-title"
                    class="text-lg font-semibold text-gray-800 flex items-center"
                >
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
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    Confirm Payment
                </h3>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    aria-label="Close modal"
                >
                    <svg
                        class="w-5 h-5"
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
            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Are you sure you want to record this payment?
                </p>
                <div
                    class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-xl border border-indigo-200"
                >
                    <h4 class="text-md font-semibold text-gray-800 mb-3">
                        Payment Summary
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <svg
                                class="w-5 h-5 text-blue-600 mr-2"
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
                                <p class="text-sm text-gray-600">
                                    Payment Amount
                                </p>
                                <p class="font-bold text-blue-600">
                                    ৳{{ paymentForm.amount.toLocaleString() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg
                                class="w-5 h-5 text-orange-600 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">
                                    Payment Method
                                </p>
                                <p class="font-bold text-orange-600">
                                    {{
                                        paymentForm.payment_method === "mfs"
                                            ? paymentForm.mfs_provider
                                            : paymentForm.payment_method
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg
                                class="w-5 h-5 text-indigo-600 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-toolbar="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Due Amount</p>
                                <p
                                    class="font-bold"
                                    :class="
                                        dueAmount >= 0
                                            ? 'text-indigo-600'
                                            : 'text-red-600'
                                    "
                                >
                                    ৳{{ dueAmount.toLocaleString() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg
                                class="w-5 h-5 text-purple-600 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">
                                    Invoice Number
                                </p>
                                <p class="font-bold text-purple-600">
                                    {{ sale.invoice_number }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button
                    @click="closeModal"
                    class="px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-300"
                >
                    Cancel
                </button>
                <button
                    @click="confirmPayment"
                    class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center space-x-2"
                    :disabled="isSubmitting"
                >
                    <svg
                        class="w-5 h-5"
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
                    <span>Confirm Payment</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

defineOptions({
    layout: Layout,
});

const props = defineProps<{
    sale: {
        id: number;
        invoice_number: string;
        total_amount: number;
        paid_amount: number;
        due_amount: number;
        shop_name: string;
        advance_balance: number;
    };
}>();

const paymentForm = ref({
    amount: 0,
    payment_method: "",
    mfs_provider: "",
});

const dueAmount = ref(props.sale.due_amount ?? 0);
const paymentError = ref<string | null>(null);
const isSubmitting = ref(false);
const showModal = ref(false);

const maxPaymentAmount = computed(() => {
    return Number(
        (props.sale.total_amount - (props.sale.paid_amount ?? 0)).toFixed(2)
    );
});

const formatCurrency = (value: number) => `৳${Number(value).toFixed(2)}`;

const calculateDue = () => {
    const total = Number(props.sale.total_amount ?? 0);
    const paid = Number(props.sale.paid_amount ?? 0);
    let payment = Number(paymentForm.value.amount);

    const maxPayment = maxPaymentAmount.value;
    if (payment > maxPayment) {
        paymentForm.value.amount = maxPayment;
        payment = maxPayment;
        paymentError.value = `Payment cannot exceed the due amount of ${formatCurrency(
            maxPayment
        )}.`;
    } else if (payment < 0 || isNaN(payment)) {
        paymentForm.value.amount = 0;
        payment = 0;
        paymentError.value = "Payment amount must be a positive number.";
    } else if (payment === 0) {
        paymentError.value = "Payment amount must be greater than zero.";
    } else {
        paymentError.value = null;
    }

    dueAmount.value = Number((total - (paid + payment)).toFixed(2));
};

const onPaymentMethodChange = () => {
    if (paymentForm.payment_method !== "mfs") {
        paymentForm.value.mfs_provider = "";
    }
};

const openModal = () => {
    if (
        !paymentForm.value.payment_method ||
        (paymentForm.value.payment_method === "mfs" &&
            !paymentForm.value.mfs_provider) ||
        paymentError.value ||
        isSubmitting.value ||
        paymentForm.value.amount <= 0
    ) {
        return;
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const confirmPayment = () => {
    isSubmitting.value = true;
    showModal.value = false;
    const method =
        paymentForm.value.payment_method === "mfs"
            ? paymentForm.value.mfs_provider
            : paymentForm.value.payment_method;
    const amount = Math.min(
        Number(paymentForm.value.amount.toFixed(2)),
        maxPaymentAmount.value
    );

    console.log("Submitting payment:", {
        total_amount: props.sale.total_amount,
        payment_amount: amount,
        due_amount: dueAmount.value,
        payment_method: method,
    });

    router.post(
        `/sales/payment/store/${props.sale.id}`,
        {
            payment_amount: amount,
            payment_method: method,
            due_amount: dueAmount.value,
        },
        {
            onSuccess: () => {
                console.log("Payment submitted successfully");
                router.visit(`/sales/cash-memo/${props.sale.id}`);
            },
            onError: (errors) => {
                console.error("Payment submission errors:", errors);
                alert("Failed to submit payment: " + JSON.stringify(errors));
                isSubmitting.value = false;
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        }
    );
};

const skipPayment = () => {
    router.visit("/sales");
};
</script>

<style scoped>
input,
select {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    line-height: 1.5;
    transition: all 0.2s ease-in-out;
}
input:focus,
select:focus {
    transform: scale(1.02);
}
.shadow-3xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
button:hover svg {
    transform: scale(1.1);
}
.border-red-500 {
    border-color: #ef4444 !important;
}
.animate-pulse {
    animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>
