<template>
    <div
        class="max-w-3xl mx-auto bg-white p-10 rounded-lg shadow-md border border-gray-100 print:shadow-none print:border-none"
    >
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cash Memo</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Invoice #{{ sale.invoice_number }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-lg font-semibold text-gray-800">
                    {{ sale.shop_name }}
                </p>
                <p class="text-sm text-gray-600">
                    Date: {{ formatDate(payment.payment_date) }}
                </p>
            </div>
        </div>

        <!-- Payment Details Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                Payment Details
            </h2>
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <table class="w-full text-left text-sm">
                    <tbody>
                        <tr class="border-b border-gray-200">
                            <td class="py-3 font-medium text-gray-700">
                                Total Amount
                            </td>
                            <td class="py-3 text-gray-900">
                                {{ formatCurrency(sale.total_amount) }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-3 font-medium text-gray-700">
                                Payment Amount
                            </td>
                            <td class="py-3 text-gray-900">
                                {{ formatCurrency(payment.amount) }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-3 font-medium text-gray-700">
                                Due Amount
                            </td>
                            <td
                                class="py-3"
                                :class="
                                    sale.due_amount > 0
                                        ? 'text-red-600 font-semibold'
                                        : 'text-gray-900'
                                "
                            >
                                {{ formatCurrency(sale.due_amount) }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="py-3 font-medium text-gray-700">
                                Payment Method
                            </td>
                            <td class="py-3 text-gray-900 capitalize">
                                {{ payment.payment_method }}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 font-medium text-gray-700">
                                Advance Balance
                            </td>
                            <td class="py-3 text-gray-900">
                                {{ formatCurrency(payment.advance_balance) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="text-center text-sm text-gray-500 mb-6">
            <p>Thank you for your business!</p>
            <p>Contact us: support@company.com | +123-456-7890</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 print:hidden">
            <button
                @click="printReceipt"
                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 flex items-center"
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
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                    />
                </svg>
                Print Receipt
            </button>
            <button
                @click="goToSales"
                class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200 flex items-center"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back to Sales
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    sale: {
        id: number;
        invoice_number: string;
        total_amount: number;
        paid_amount: number;
        due_amount: number;
        shop_name: string;
    };
    payment: {
        amount: number;
        payment_method: string;
        advance_balance: number;
        payment_date: string;
    };
}>();

const formatCurrency = (value: number) => {
    return `৳${Number(value).toFixed(2)}`;
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const printReceipt = () => {
    window.print();
};

const goToSales = () => {
    router.visit("/sales");
};
</script>

<style scoped>
/* General Styles */
.max-w-3xl {
    font-family: "Inter", sans-serif;
}

table {
    border-collapse: collapse;
    width: 100%;
}

tr {
    border-bottom: 1px solid #e5e7eb;
}

td {
    padding: 12px 0;
}

button:hover svg {
    transform: scale(1.1);
    transition: transform 0.2s ease-in-out;
}

/* Print Styles */
@media print {
    .print\:hidden {
        display: none;
    }
    .max-w-3xl {
        max-width: 100%;
        padding: 20px;
        box-shadow: none;
        border: none;
    }
    .bg-gray-50 {
        background-color: transparent;
        border: none;
    }
}
</style>
