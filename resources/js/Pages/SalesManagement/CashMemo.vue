<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 max-w-3xl mx-auto rounded-xl shadow-sm print:bg-white print:shadow-none print:border-none"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Language Toggle -->
        <div class="flex justify-end space-x-2 mb-4 print:hidden">
            <button
                @click="changeLanguage('en')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-colors',
                    currentLanguage === 'en'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ getTranslationLabel("languageLabel", "en") }}
            </button>
            <button
                @click="changeLanguage('bn')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-colors',
                    currentLanguage === 'bn'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ getTranslationLabel("languageLabel", "bn") }}
            </button>
        </div>

        <!-- Header Section -->
        <div
            class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4 animate-fade-in"
        >
            <div class="flex items-center">
                <div
                    class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-8 h-8 text-indigo-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                </div>
                <div>
                    <h1
                        class="text-3xl font-semibold text-gray-800 tracking-tight"
                    >
                        {{ getTranslation("cashMemo") }}
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ getTranslation("invoice") }} #{{
                            toBengaliNumber(sale.invoice_number)
                        }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-lg font-semibold text-gray-800">
                    {{ sale.shop_name }}
                </p>
                <p class="text-sm text-gray-600">
                    {{ getTranslation("date") }}:
                    {{ formatDate(payment.payment_date) }}
                </p>
            </div>
        </div>

        <!-- Purchased Items Section -->
        <div v-if="sale.items && sale.items.length > 0" class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 animate-fade-in">
                {{ getTranslation("purchasedItems") }}
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ getTranslation("productName") }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ getTranslation("variant") }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ getTranslation("qty") }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ getTranslation("unitPrice") }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ getTranslation("total") }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, idx) in sale.items" :key="idx" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.product_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.variant }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                {{ toBengaliNumber(item.cases_sold) }} {{ getTranslation("case") }}
                                <span v-if="item.extra_bottles > 0" class="text-xs text-gray-500">
                                    + {{ toBengaliNumber(item.extra_bottles) }} {{ getTranslation("extraBottles") }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">৳{{ toBengaliNumber(item.unit_price) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium">৳{{ toBengaliNumber(item.total_price) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Details Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2
                class="text-xl font-semibold text-gray-800 mb-4 animate-fade-in"
            >
                {{ getTranslation("paymentDetails") }}
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td
                                class="px-6 py-3 font-medium text-gray-700 text-sm"
                            >
                                {{ getTranslation("totalAmount") }}
                            </td>
                            <td
                                class="px-6 py-3 text-gray-900 text-sm text-right"
                            >
                                ৳{{ toBengaliNumber(sale.total_amount) }}
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td
                                class="px-6 py-3 font-medium text-gray-700 text-sm"
                            >
                                {{ getTranslation("paymentAmount") }}
                            </td>
                            <td
                                class="px-6 py-3 text-gray-900 text-sm text-right"
                            >
                                ৳{{ toBengaliNumber(payment.amount) }}
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td
                                class="px-6 py-3 font-medium text-gray-700 text-sm"
                            >
                                {{ getTranslation("dueAmount") }}
                            </td>
                            <td
                                class="px-6 py-3 text-sm text-right"
                                :class="
                                    sale.due_amount > 0
                                        ? 'text-red-600 font-semibold'
                                        : 'text-gray-900'
                                "
                            >
                                ৳{{ toBengaliNumber(sale.due_amount) }}
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td
                                class="px-6 py-3 font-medium text-gray-700 text-sm"
                            >
                                {{ getTranslation("paymentMethod") }}
                            </td>
                            <td
                                class="px-6 py-3 text-gray-900 text-sm text-right capitalize"
                            >
                                {{ payment.payment_method }}
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td
                                class="px-6 py-3 font-medium text-gray-700 text-sm"
                            >
                                {{ getTranslation("saleStatus") }}
                            </td>
                            <td class="px-6 py-3 text-sm text-right">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                    :class="{
                                        'bg-yellow-100 text-yellow-800':
                                            sale.status === 'pending',
                                        'bg-blue-100 text-blue-800':
                                            sale.status === 'in_progress',
                                        'bg-green-100 text-green-800':
                                            sale.status === 'completed',
                                    }"
                                >
                                    {{ sale.status }}
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td
                                class="px-6 py-3 font-medium text-gray-700 text-sm"
                            >
                                {{ getTranslation("paymentStatus") }}
                            </td>
                            <td class="px-6 py-3 text-sm text-right">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                    :class="{
                                        'bg-red-100 text-red-800':
                                            payment.status === 'unpaid',
                                        'bg-yellow-100 text-yellow-800':
                                            payment.status === 'pending',
                                        'bg-green-100 text-green-800':
                                            payment.status === 'paid',
                                    }"
                                >
                                    {{ payment.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="text-center text-sm text-gray-500 mb-6 animate-fade-in">
            <p>{{ getTranslation("thankYou") }}</p>
            <p>{{ getTranslation("contactUs") }}</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 print:hidden">
            <button
                @click="printReceipt"
                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200 flex items-center animate-pulse-slow"
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
                {{ getTranslation("printReceipt") }}
            </button>
            <button
                @click="goToSales"
                class="px-6 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200 flex items-center animate-pulse-slow"
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
                {{ getTranslation("backToSales") }}
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps<{
    sale: {
        id: number;
        invoice_number: string;
        total_amount: number;
        paid_amount: number;
        due_amount: number;
        shop_name: string;
        status: string;
        items?: {
            product_name: string;
            variant: string;
            cases_sold: number;
            extra_bottles: number | null;
            total_bottles_sold: number;
            target_bottles_to_sell: number;
            unit_price: number;
            total_price: number;
        }[];
    };
    payment: {
        amount: number;
        payment_method: string;
        advance_balance: number;
        payment_date: string;
        status: string;
    };
}>();

const translations = {
    en: {
        languageLabel: "English",
        cashMemo: "Cash Memo",
        invoice: "Invoice",
        date: "Date",
        purchasedItems: "Purchased Items",
        productName: "Product Name",
        variant: "Variant",
        qty: "Qty",
        unitPrice: "Unit Price",
        total: "Total",
        paymentDetails: "Payment Details",
        totalAmount: "Total Amount",
        paymentAmount: "Payment Amount",
        dueAmount: "Due Amount",
        paymentMethod: "Payment Method",
        saleStatus: "Sale Status",
        paymentStatus: "Payment Status",
        thankYou: "Thank you for your business!",
        contactUs: "Contact us: support@company.com | +123-456-7890",
        printReceipt: "Print Receipt",
        backToSales: "Back to Sales",
        case: "cases",
        extraBottles: "extra",
    },
    bn: {
        languageLabel: "বাংলা",
        cashMemo: "ক্যাশ মেমো",
        invoice: "চালান",
        date: "তারিখ",
        purchasedItems: "ক্রয়কৃত পণ্য",
        productName: "পণ্যের নাম",
        variant: "ভেরিয়েন্ট",
        qty: "পরিমাণ",
        unitPrice: "একক মূল্য",
        total: "মোট",
        paymentDetails: "পেমেন্ট বিবরণ",
        totalAmount: "মোট পরিমাণ",
        paymentAmount: "পেমেন্ট পরিমাণ",
        dueAmount: "বকেয়া পরিমাণ",
        paymentMethod: "পেমেন্ট পদ্ধতি",
        saleStatus: "বিক্রয় স্থিতি",
        paymentStatus: "পেমেন্ট স্থিতি",
        thankYou: "আপনার ব্যবসার জন্য ধন্যবাদ!",
        contactUs: "যোগাযোগ করুন: support@company.com | +123-456-7890",
        printReceipt: "রসিদ প্রিন্ট করুন",
        backToSales: "বিক্রয়ে ফিরে যান",
        case: "কেস",
        extraBottles: "অতিরিক্ত",
    },
};

const currentLanguage = ref(localStorage.getItem("language") || "en");

const formatDate = (date: string) => {
    return new Date(date).toLocaleString(currentLanguage.value, {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const toBengaliNumber = (numValue: number | string): string => {
    if (numValue === null || numValue === undefined || numValue === "") return "";
    
    // Round decimals to 2 places if it's a number or a numeric string
    let n = Number(numValue);
    if (!isNaN(n) && n % 1 !== 0) {
        numValue = n.toFixed(2);
    } else if (!isNaN(n)) {
        numValue = n.toString();
    }

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return String(numValue).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

const getTranslation = (key: string) => {
    return translations[currentLanguage.value]?.[key] || key;
};

const getTranslationLabel = (key: string, lang: string) => {
    return translations[lang]?.[key] || key;
};

const changeLanguage = (lang: string) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

const printReceipt = () => {
    window.print();
};

const goToSales = () => {
    router.visit("/sales");
};
</script>

<style scoped>
/* Import Kalpurush font from provided CDN */
@import url("https://fonts.maateen.me/kalpurush/font.css");

/* Apply Kalpurush font for Bangla text */
.bangla-font {
    font-family: "Kalpurush", serif;
}

/* Ensure specific elements use Kalpurush for Bangla */
.bangla-font h1,
.bangla-font h2,
.bangla-font p,
.bangla-font td,
.bangla-font span,
.bangla-font button {
    font-family: "Kalpurush", serif;
}

/* Remove previous Noto Serif Bengali import and body/html font */
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

@keyframes pulseSlow {
    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.03);
        opacity: 0.95;
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-pulse-slow {
    animation: pulseSlow 2s infinite;
}

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
}
</style>
