<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50"
    >
        <!-- Language Toggle -->
        <div class="flex justify-end space-x-2 mb-4">
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

        <!-- Title -->
        <div
            class="flex flex-col lg:flex-row lg:justify-between items-start lg:items-center mb-8 border-b border-gray-200 pb-4 gap-4"
        >
            <h1
                class="text-2xl lg:text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 lg:w-8 lg:h-8 text-indigo-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h18v6H3V3zm0 8h18v10H3V11zm6 4h6"
                        />
                    </svg>
                </div>
                {{ getTranslation("purchaseReport") }}
            </h1>
        </div>

        <!-- Total Metrics -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-sm border border-indigo-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 7h10v4H7zM3 3h18v6H3V3zm0 8h18v10H3V11z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-700">
                            {{ getTranslation("totalPurchases") }}
                        </p>
                        <p
                            class="text-2xl lg:text-3xl font-bold text-indigo-900"
                        >
                            {{ toBengaliNumber(totalPurchases) }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl shadow-sm border border-orange-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-orange-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-orange-700">
                            {{ getTranslation("totalBoxes") }}
                        </p>
                        <p
                            class="text-2xl lg:text-3xl font-bold text-orange-900"
                        >
                            {{ toBengaliNumber(totalBoxes) }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm border border-purple-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-purple-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-700">
                            {{ getTranslation("totalBottles") }}
                        </p>
                        <p
                            class="text-2xl lg:text-3xl font-bold text-purple-900"
                        >
                            {{ toBengaliNumber(totalBottles) }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-green-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
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
                        <p class="text-sm font-medium text-green-700">
                            {{ getTranslation("totalAmount") }}
                        </p>
                        <p
                            class="text-2xl lg:text-3xl font-bold text-green-900"
                        >
                            ৳{{ toBengaliNumber(totalAmount) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Field -->
        <div class="flex justify-end mb-4">
            <div class="relative w-full sm:w-80">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                >
                    <svg
                        class="w-5 h-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="getTranslation('searchPurchases')"
                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                />
            </div>
        </div>

        <!-- Purchase Table -->
        <div class="bg-white rounded-xl shadow-sm p-3 lg:p-6">
            <div
                v-if="!filteredPurchases.length"
                class="text-center text-gray-500 py-4"
            >
                {{ getTranslation("noPurchases") }}
            </div>
            <div v-else class="w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("supplier") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("product") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell"
                            >
                                {{ getTranslation("variant") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("boxes") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("totalBottles") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("unitPrice") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("totalAmount") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell"
                            >
                                {{ getTranslation("purchaseDate") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="(purchase, index) in filteredPurchases"
                            :key="index"
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-900"
                            >
                                <span
                                    class="truncate max-w-24"
                                    :title="purchase.supplier_name || '-'"
                                >
                                    {{ purchase.supplier_name || "-" }}
                                </span>
                            </td>
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-900"
                            >
                                <span
                                    class="truncate max-w-24"
                                    :title="purchase.product_name || '-'"
                                >
                                    {{ purchase.product_name || "-" }}
                                </span>
                            </td>
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 hidden lg:table-cell"
                            >
                                <span
                                    class="truncate max-w-24"
                                    :title="purchase.variant || 'N/A'"
                                >
                                    {{ purchase.variant || "N/A" }}
                                </span>
                            </td>
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500"
                            >
                                <div class="text-right">
                                    {{
                                        toBengaliNumber(
                                            purchase.quantity /
                                                purchase.bottles_per_box || 0
                                        )
                                    }}
                                </div>
                            </td>
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500"
                            >
                                <div class="text-right">
                                    {{
                                        toBengaliNumber(purchase.quantity || 0)
                                    }}
                                </div>
                            </td>
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500"
                            >
                                <div class="text-right">
                                    ৳{{
                                        toBengaliNumber(
                                            (purchase.unit_price || 0).toFixed(
                                                2
                                            )
                                        )
                                    }}
                                </div>
                            </td>
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500"
                            >
                                <div class="text-right font-medium">
                                    ৳{{
                                        toBengaliNumber(
                                            (purchase.total_value || 0).toFixed(
                                                2
                                            )
                                        )
                                    }}
                                </div>
                            </td>
                            <td
                                class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 hidden md:table-cell"
                            >
                                {{
                                    purchase.purchase_date
                                        ? toBengaliNumber(
                                              formatDate(purchase.purchase_date)
                                          )
                                        : "-"
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface Purchase {
    supplier_name: string;
    product_name: string;
    variant: string;
    bottles_per_box: number;
    quantity: number;
    free_bottles: number;
    unit_price: number;
    total_value: number;
    purchase_date: string;
}

const props = defineProps<{
    purchaseHistory: Purchase[];
    products: { id: number; name: string }[];
    suppliers: { id: number; company_name: string }[];
}>();

defineOptions({
    layout: Layout,
});

const translations = {
    en: {
        languageLabel: "English",
        purchaseReport: "Purchase Report",
        supplier: "Supplier",
        product: "Product",
        variant: "Variant",
        boxes: "Boxes",
        totalBottles: "Total Bottles",
        totalBoxes: "Total Boxes",
        unitPrice: "Unit Price",
        totalAmount: "Total Amount",
        purchaseDate: "Purchase Date",
        totalPurchases: "Total Purchases",
        searchPurchases: "Search with Supplier or Product",
        noPurchases: "No purchases found",
    },
    bn: {
        languageLabel: "বাংলা",
        purchaseReport: "ক্রয় রিপোর্ট",
        supplier: "সরবরাহকারী",
        product: "পণ্য",
        variant: "ভেরিয়েন্ট",
        boxes: "বক্স",
        totalBottles: "মোট বোতল",
        totalBoxes: "মোট বক্স",
        unitPrice: "একক মূল্য",
        totalAmount: "মোট পরিমাণ",
        purchaseDate: "ক্রয়ের তারিখ",
        totalPurchases: "মোট ক্রয়",
        searchPurchases: "সরবরাহকারী বা পণ্য দিয়ে খুঁজুন",
        noPurchases: "কোন ক্রয় পাওয়া যায়নি",
    },
};

const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref("");

const totalPurchases = computed(() => props.purchaseHistory.length);
const totalAmount = computed(() =>
    props.purchaseHistory.reduce(
        (sum, purchase) => sum + Number(purchase.total_value),
        0
    )
);
const totalBottles = computed(() =>
    props.purchaseHistory.reduce(
        (sum, purchase) => sum + Number(purchase.quantity),
        0
    )
);
const totalBoxes = computed(() =>
    props.purchaseHistory.reduce((sum, purchase) => {
        const boxes = purchase.bottles_per_box
            ? purchase.quantity / purchase.bottles_per_box
            : 0;
        return sum + Math.floor(boxes);
    }, 0)
);

const filteredPurchases = computed(() => {
    if (!searchQuery.value) return props.purchaseHistory;
    const query = searchQuery.value.toLowerCase();
    return props.purchaseHistory.filter(
        (purchase) =>
            purchase.supplier_name?.toLowerCase().includes(query) ||
            false ||
            purchase.product_name?.toLowerCase().includes(query) ||
            false
    );
});

function getTranslation(key: string) {
    return translations[currentLanguage.value]?.[key] || key;
}

function getTranslationLabel(key: string, lang: string) {
    return translations[lang]?.[key] || key;
}

function toBengaliNumber(num: number | string) {
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string") return num;
    if (currentLanguage.value !== "bn") return num;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
}

function changeLanguage(lang: string) {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
}

function formatDate(dateString: string) {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;700&display=swap");

body,
html {
    font-family: "Noto Serif Bengali", Arial, sans-serif;
}

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
    animation: fadeIn 1s ease-out;
}

.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.max-w-24 {
    max-width: 6rem;
}

table {
    table-layout: fixed;
}

@media (max-width: 640px) {
    .table-responsive {
        font-size: 0.75rem;
    }

    th,
    td {
        padding: 0.25rem 0.5rem;
    }
}
</style>
