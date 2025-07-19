```vue
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
                {{ translations.en.languageLabel }}
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
                {{ translations.bn.languageLabel }}
            </button>
        </div>

        <div
            class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4"
        >
            <h1
                class="text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
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
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        />
                    </svg>
                </div>
                {{ t("dashboardTitle") }}
            </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ t("totalSuppliers") }}
                    </h2>
                    <div
                        class="p-2 bg-indigo-100 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.134-1.272.37-1.839m0 0C5.074 15.39 4 13.904 4 12V6a2 2 0 012-2h12a2 2 0 012 2v6c0 1.904-1.074 3.39-2.37 4.161M16 16v-4a2 2 0 00-2-2h-4a2 2 0 00-2 2v4m4 0h2m-2 0h-2m-6 0a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2z"
                            ></path>
                        </svg>
                    </div>
                </div>
                <p
                    class="text-4xl font-bold text-indigo-600 animate-pulse-slow"
                >
                    {{ toBengaliNumber(animatedSuppliersCount) }}
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    {{ t("activeSuppliers") }}
                </p>
                <div class="mt-4 max-h-36 overflow-y-auto space-y-2">
                    <div
                        v-for="(supplier, index) in suppliers.slice(
                            0,
                            Math.max(5, suppliers.length)
                        )"
                        :key="supplier.id"
                        class="bg-gray-50 p-2 rounded flex justify-between items-center"
                    >
                        <span class="font-medium text-gray-700">{{
                            supplier.company_name ||
                            t("supplier") + " " + (index + 1)
                        }}</span>
                        <span class="text-sm text-gray-500"
                            >{{ t("id") }}:
                            {{ toBengaliNumber(supplier.id) }}</span
                        >
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ t("topDepositSuppliers") }}
                    </h2>
                    <div
                        class="p-2 bg-indigo-100 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3">
                    <div
                        v-for="(deposit, index) in topDeposits"
                        :key="index"
                        class="flex flex-col"
                    >
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">{{
                                deposit.supplier_name
                            }}</span>
                            <span class="font-semibold text-indigo-600"
                                >৳{{
                                    toBengaliNumber(animatedDeposits[index])
                                }}</span
                            >
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-full mt-1">
                            <div
                                class="h-2 rounded-full bg-indigo-500 transition-all duration-1000"
                                :style="{
                                    width:
                                        Math.min(animatedWidths[index], 100) +
                                        '%',
                                }"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ t("totalShops") }}
                    </h2>
                    <div
                        class="p-2 bg-indigo-100 rounded-full flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14v12a2 2 0 01-2 2H7a2 2 0 01-2-2V9z"
                            ></path>
                        </svg>
                    </div>
                </div>
                <p
                    class="text-4xl font-bold text-indigo-600 animate-pulse-slow"
                >
                    {{ toBengaliNumber(animatedTotalShops) }}
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    {{ t("registeredShops") }}
                </p>
                <div class="mt-4 max-h-36 overflow-y-auto space-y-2">
                    <div
                        v-for="(shop, index) in shops.slice(
                            0,
                            Math.max(5, shops.length)
                        )"
                        :key="shop.id"
                        class="bg-gray-50 p-2 rounded flex justify-between items-center"
                    >
                        <span class="font-medium text-gray-700">{{
                            shop.shop_name || t("shop") + " " + (index + 1)
                        }}</span>
                        <span class="text-sm text-gray-500"
                            >{{ t("id") }}: {{ toBengaliNumber(shop.id) }}</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <div
                class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl"
            >
                <div class="flex justify-between items-center mb-8">
                    <h2
                        class="text-2xl font-semibold text-gray-800 flex items-center"
                    >
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
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                        </div>
                        {{ selectedMonthYear }} {{ t("salesOverview") }}
                    </h2>
                    <div class="flex space-x-2">
                        <button
                            @click="navigateToPreviousMonth"
                            class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors"
                            :aria-label="t('previousMonth')"
                            :disabled="loading"
                        >
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
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>
                        <button
                            @click="navigateToNextMonth"
                            class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors"
                            :aria-label="t('nextMonth')"
                            :disabled="loading"
                        >
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
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4"
                >
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm flex flex-col items-center justify-between space-y-4"
                    >
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ t("salesBreakdown") }}
                        </h3>
                        <div class="relative w-40 h-40">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="45"
                                    fill="none"
                                    stroke="#e5e7eb"
                                    stroke-width="10"
                                />
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="45"
                                    fill="none"
                                    stroke="#10B981"
                                    stroke-width="10"
                                    :stroke-dasharray="paidCircumference"
                                    stroke-dashoffset="0"
                                    transform="rotate(-90 50 50)"
                                    class="transition-all duration-1000"
                                />
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="45"
                                    fill="none"
                                    stroke="#F59E0B"
                                    stroke-width="10"
                                    :stroke-dasharray="dueCircumference"
                                    :stroke-dashoffset="dueOffset"
                                    transform="rotate(-90 50 50)"
                                    class="transition-all duration-1000"
                                />
                            </svg>
                            <div
                                class="absolute inset-0 flex items-center justify-center"
                            >
                                <span class="text-2xl font-bold text-gray-800"
                                    >{{
                                        toBengaliNumber(totalSalesPercentage)
                                    }}%</span
                                >
                            </div>
                        </div>
                        <div
                            class="flex flex-col space-y-2 text-sm text-gray-600"
                        >
                            <div class="flex items-center">
                                <span
                                    class="w-3 h-3 rounded-full bg-green-500 mr-2"
                                ></span>
                                <span>
                                    {{ t("paid") }}:
                                    <span class="font-semibold"
                                        >{{
                                            toBengaliNumber(paidPercentage)
                                        }}%</span
                                    >
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="w-3 h-3 rounded-full bg-yellow-500 mr-2"
                                ></span>
                                <span>
                                    {{ t("due") }}:
                                    <span class="font-semibold"
                                        >{{
                                            toBengaliNumber(duePercentage)
                                        }}%</span
                                    >
                                </span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4"
                    >
                        <div
                            class="p-2 bg-indigo-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-indigo-600"
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
                            <p class="text-sm text-gray-600">
                                {{ t("totalSales") }}
                            </p>
                            <p
                                class="text-2xl font-bold text-indigo-600 animate-pulse-slow"
                            >
                                ৳{{ toBengaliNumber(animatedTotalSales) }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4"
                    >
                        <div
                            class="p-2 bg-green-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-green-600"
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
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                {{ t("paidAmount") }}
                            </p>
                            <p
                                class="text-2xl font-bold text-green-600 animate-pulse-slow"
                            >
                                ৳{{ toBengaliNumber(animatedPaidAmount) }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm flex items-center space-x-4"
                    >
                        <div
                            class="p-2 bg-yellow-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-yellow-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">
                                {{ t("dueAmount") }}
                            </p>
                            <p
                                class="text-2xl font-bold text-yellow-600 animate-pulse-slow"
                            >
                                ৳{{ toBengaliNumber(animatedDueAmount) }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-white p-6 rounded-xl shadow-sm flex flex-col items-center justify-between space-y-4"
                    >
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ t("profitAndLoss") }}
                        </h3>
                        <div class="flex space-x-4">
                            <div class="relative w-24 h-24">
                                <svg
                                    class="w-full h-full"
                                    viewBox="0 0 100 100"
                                >
                                    <circle
                                        cx="50"
                                        cy="50"
                                        r="45"
                                        fill="none"
                                        stroke="#d1fae5"
                                        stroke-width="10"
                                    />
                                    <circle
                                        cx="50"
                                        cy="50"
                                        r="45"
                                        fill="none"
                                        stroke="#059669"
                                        stroke-width="10"
                                        stroke-linecap="round"
                                        :stroke-dasharray="profitCircumference"
                                        stroke-dashoffset="0"
                                        transform="rotate(-90 50 50)"
                                        class="transition-all duration-1000"
                                    />
                                </svg>
                                <div
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <span
                                        class="text-lg font-bold text-emerald-600"
                                        >{{
                                            toBengaliNumber(profitPercentage)
                                        }}%</span
                                    >
                                </div>
                            </div>
                            <div class="relative w-24 h-24">
                                <svg
                                    class="w-full h-full"
                                    viewBox="0 0 100 100"
                                >
                                    <circle
                                        cx="50"
                                        cy="50"
                                        r="45"
                                        fill="none"
                                        stroke="#fee2e2"
                                        stroke-width="10"
                                    />
                                    <circle
                                        cx="50"
                                        cy="50"
                                        r="45"
                                        fill="none"
                                        stroke="#dc2626"
                                        stroke-width="10"
                                        stroke-linecap="round"
                                        :stroke-dasharray="lossCircumference"
                                        stroke-dashoffset="0"
                                        transform="rotate(-90 50 50)"
                                        class="transition-all duration-1000"
                                    />
                                </svg>
                                <div
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <span
                                        class="text-lg font-bold text-rose-600"
                                        >{{
                                            toBengaliNumber(lossPercentage)
                                        }}%</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <span>{{ t("profit") }}: </span>
                                <span
                                    class="font-semibold text-emerald-600 w-14 text-left inline-block tabular-nums"
                                    >৳{{
                                        toBengaliNumber(animatedProfit)
                                    }}</span
                                >
                            </div>
                            <div class="flex items-center">
                                <span>{{ t("loss") }}: </span>
                                <span
                                    class="font-semibold text-rose-600 w-14 text-left inline-block tabular-nums"
                                    >৳{{ toBengaliNumber(animatedLoss) }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref, onMounted, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps({
    suppliersCount: Number,
    topDeposits: Array,
    suppliers: Array,
    shops: Array,
    monthlySales: Object,
    month: Number,
    year: Number,
});

// Translation object
const translations = {
    en: {
        languageLabel: "English",
        dashboardTitle: "Dashboard",
        totalSuppliers: "Total Suppliers",
        activeSuppliers: "Active suppliers in the system",
        supplier: "Supplier",
        id: "ID",
        topDepositSuppliers: "Top Deposit Suppliers",
        totalShops: "Total Shops",
        registeredShops: "Registered shops in the system",
        shop: "Shop",
        salesOverview: "Sales Overview",
        previousMonth: "Previous month",
        nextMonth: "Next month",
        salesBreakdown: "Sales Breakdown",
        paid: "Paid",
        due: "Due",
        totalSales: "Total Sales",
        paidAmount: "Paid Amount",
        dueAmount: "Due Amount",
        profitAndLoss: "Profit & Loss",
        profit: "Profit",
        loss: "Loss",
    },
    bn: {
        languageLabel: "বাংলা",
        dashboardTitle: "ড্যাশবোর্ড",
        totalSuppliers: "মোট সরবরাহকারী",
        activeSuppliers: "সিস্টেমে সক্রিয় সরবরাহকারী",
        supplier: "সরবরাহকারী",
        id: "আইডি",
        topDepositSuppliers: "শীর্ষ আমানত সরবরাহকারী",
        totalShops: "মোট দোকান",
        registeredShops: "সিস্টেমে নিবন্ধিত দোকান",
        shop: "দোকান",
        salesOverview: "বিক্রয় ওভারভিউ",
        previousMonth: "পূর্ববর্তী মাস",
        nextMonth: "পরবর্তী মাস",
        salesBreakdown: "বিক্রয় বিভাজন",
        paid: "প্রদত্ত",
        due: "বাকি",
        totalSales: "মোট বিক্রয়",
        paidAmount: "প্রদত্ত পরিমাণ",
        dueAmount: "বাকি পরিমাণ",
        profitAndLoss: "লাভ ও ক্ষতি",
        profit: "লাভ",
        loss: "ক্ষতি",
    },
};

// Reactive language state
const currentLanguage = ref(localStorage.getItem("language") || "en");

// Loading state for navigation
const loading = ref(false);

// Translation function
const t = computed(() => (key) => translations[currentLanguage.value][key]);

// Function to convert numbers to Bengali
const toBengaliNumber = (num) => {
    // Handle undefined, null, or non-numeric values
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string") return num;

    // If language is not Bengali, return original number
    if (currentLanguage.value !== "bn") return num;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
};

// Function to change language
const changeLanguage = (lang) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

// Navigation functions
const navigateToPreviousMonth = () => {
    let newMonth = selectedMonth.value - 1;
    let newYear = selectedYear.value;
    if (newMonth < 0) {
        newMonth = 11;
        newYear--;
    }
    selectedMonth.value = newMonth;
    selectedYear.value = newYear;
    navigateToMonth(newMonth, newYear);
};

const navigateToNextMonth = () => {
    let newMonth = selectedMonth.value + 1;
    let newYear = selectedYear.value;
    if (newMonth > 11) {
        newMonth = 0;
        newYear++;
    }
    selectedMonth.value = newMonth;
    selectedYear.value = newYear;
    navigateToMonth(newMonth, newYear);
};

const navigateToMonth = (month, year) => {
    loading.value = true;
    router.visit(`/dashboard?month=${month + 1}&year=${year}`, {
        preserveScroll: true,
        onSuccess: (page) => {
            // Manually update animated values with new props
            const { total_sales, paid_amount, due_amount, profit, loss } =
                page.props.monthlySales;
            const duration = 1000;
            animateNumber(animatedTotalSales, total_sales, duration);
            animateNumber(animatedPaidAmount, paid_amount, duration);
            animateNumber(animatedDueAmount, due_amount, duration);
            animateNumber(animatedProfit, profit, duration);
            animateNumber(animatedLoss, loss, duration);
        },
        onError: (errors) => {
            console.error("Navigation error:", errors);
            alert(
                "Failed to load data for the selected month. Please try again."
            );
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

// Animation and other existing logic
const animatedSuppliersCount = ref(0);
const animatedDeposits = ref([]);
const animatedWidths = ref([]);
const animatedTotalSales = ref(0);
const animatedPaidAmount = ref(0);
const animatedDueAmount = ref(0);
const animatedProfit = ref(0);
const animatedLoss = ref(0);
const animatedTotalShops = ref(0);
const selectedMonth = ref(props.month - 1); // Convert to 0-11 for JavaScript Date
const selectedYear = ref(props.year);

// --- Sales Distribution Computed Properties for Pie Chart ---

const paidPercentage = computed(() => {
    if (animatedTotalSales.value === 0) return 0;
    return (
        (animatedPaidAmount.value / animatedTotalSales.value) *
        100
    ).toFixed(0);
});

const duePercentage = computed(() => {
    if (animatedTotalSales.value === 0) return 0;
    return ((animatedDueAmount.value / animatedTotalSales.value) * 100).toFixed(
        0
    );
});

const totalSalesPercentage = computed(() => {
    return (
        parseFloat(paidPercentage.value) + parseFloat(duePercentage.value)
    ).toFixed(0);
});

const radius = 45;
const circumference = 2 * Math.PI * radius;

const paidCircumference = computed(() => {
    const percentage = parseFloat(paidPercentage.value);
    return `${(percentage / 100) * circumference}, ${circumference}`;
});

const dueCircumference = computed(() => {
    const percentage = parseFloat(duePercentage.value);
    return `${(percentage / 100) * circumference}, ${circumference}`;
});

const dueOffset = computed(() => {
    const paidPercent = parseFloat(paidPercentage.value);
    return circumference - (paidPercent / 100) * circumference;
});

// --- Profit and Loss Computed Properties ---

const profitPercentage = computed(() => {
    if (animatedTotalSales.value === 0) return 0;
    return ((animatedProfit.value / animatedTotalSales.value) * 100).toFixed(0);
});

const lossPercentage = computed(() => {
    if (animatedTotalSales.value === 0) return 0;
    return ((animatedLoss.value / animatedTotalSales.value) * 100).toFixed(0);
});

const profitCircumference = computed(() => {
    const percentage = parseFloat(profitPercentage.value);
    return `${(percentage / 100) * circumference}, ${circumference}`;
});

const lossCircumference = computed(() => {
    const percentage = parseFloat(lossPercentage.value);
    return `${(percentage / 100) * circumference}, ${circumference}`;
});

// --- Computed Properties for Navigation ---

const selectedMonthYear = computed(() => {
    const date = new Date(selectedYear.value, selectedMonth.value);
    return date.toLocaleString(currentLanguage.value, {
        month: "long",
        year: "numeric",
    });
});

const animateNumber = (refVar, targetValue, duration) => {
    let startValue = refVar.value;
    const stepTime = 20;
    const steps = duration / stepTime;
    const increment = (targetValue - startValue) / steps;

    const timer = setInterval(() => {
        startValue += increment;
        if (
            (increment > 0 && startValue >= targetValue) ||
            (increment < 0 && startValue <= targetValue)
        ) {
            refVar.value = targetValue;
            clearInterval(timer);
        } else {
            refVar.value = Math.ceil(startValue);
        }
    }, stepTime);
};

onMounted(() => {
    const duration = 2000;

    // Set document language based on stored preference
    document.documentElement.lang = currentLanguage.value;

    animateNumber(
        animatedSuppliersCount,
        props.suppliersCount || props.suppliers.length || 0,
        duration
    );

    animatedWidths.value = new Array(props.topDeposits.length).fill(0);
    animatedDeposits.value = new Array(props.topDeposits.length).fill(0);

    props.topDeposits.forEach((deposit, index) => {
        let startDeposit = 0;
        const targetDeposit = deposit.total_remaining_deposit;
        const targetWidth = getBarWidth(deposit.total_remaining_deposit);
        const incrementDeposit = targetDeposit / (duration / 20);
        const widthIncrement = targetWidth / (duration / 20);

        const timer = setInterval(() => {
            startDeposit += incrementDeposit;
            if (startDeposit >= targetDeposit) {
                animatedDeposits.value[index] = targetDeposit;
                animatedWidths.value[index] = targetWidth;
                clearInterval(timer);
            } else {
                animatedDeposits.value[index] = Math.ceil(startDeposit);
                animatedWidths.value[index] = Math.min(
                    Math.ceil(startDeposit * widthIncrement),
                    100
                );
            }
        }, 20);
    });

    const { total_sales, paid_amount, due_amount, profit, loss } =
        props.monthlySales;
    animateNumber(animatedTotalSales, total_sales, duration);
    animateNumber(animatedPaidAmount, paid_amount, duration);
    animateNumber(animatedDueAmount, due_amount, duration);
    animateNumber(animatedProfit, profit, duration);
    animateNumber(animatedLoss, loss, duration);

    animateNumber(animatedTotalShops, props.shops.length, duration);
});

// Watch for changes to props.monthlySales
watch(
    () => props.monthlySales,
    (newVal) => {
        const duration = 1000;
        animateNumber(animatedTotalSales, newVal.total_sales, duration);
        animateNumber(animatedPaidAmount, newVal.paid_amount, duration);
        animateNumber(animatedDueAmount, newVal.due_amount, duration);
        animateNumber(animatedProfit, newVal.profit, duration);
        animateNumber(animatedLoss, newVal.loss, duration);
    },
    { deep: true }
);

const getBarWidth = (amount) => {
    const maxAmount = computed(() =>
        Math.max(...props.topDeposits.map((d) => d.total_remaining_deposit), 1)
    ).value;
    const width = (amount / maxAmount) * 100;
    return Math.min(Math.max(width, 10), 100);
};
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

.max-h-36::-webkit-scrollbar {
    width: 6px;
}
.max-h-36::-webkit-scrollbar-thumb {
    background: #a5b4fc;
    border-radius: 3px;
}
.max-h-36::-webkit-scrollbar-track {
    background: #f1f5f9;
}
</style>
```
