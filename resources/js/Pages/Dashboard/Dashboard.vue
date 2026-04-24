<template>
    <div
        class="p-3 space-y-4 bg-gradient-to-br from-gray-50 via-white to-gray-50"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Language Toggle and Logout -->
        <div class="flex justify-end space-x-2">
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

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm min-h-[15.5rem]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-green-600">
                            {{ t("totalSalesCard") }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">{{ selectedMonthYear }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18m-4-4h8m-8-10h8" />
                        </svg>
                    </div>
                </div>
                <p class="text-lg xl:text-xl font-bold text-gray-900 mt-5 break-all leading-tight">৳{{ toBengaliNumber(monthlySales?.total_sales || 0, 2) }}</p>
                <div class="mt-4 space-y-2 border-t border-gray-100 pt-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500">{{ t("paidAmount") }}</span>
                        <span class="font-semibold text-green-700">৳{{ toBengaliNumber(monthlySales?.paid_amount || 0, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500">{{ t("dueAmount") }}</span>
                        <span class="font-semibold text-red-600">৳{{ toBengaliNumber(monthlySales?.due_amount || 0, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500">{{ t("profit") }}</span>
                        <span class="font-semibold text-indigo-700">৳{{ toBengaliNumber(monthlySales?.profit || 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm min-h-[15.5rem]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                            {{ t("totalExpenseCard") }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">{{ selectedMonthYear }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-rose-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 10H6L5 9z" />
                        </svg>
                    </div>
                </div>
                <p class="text-lg xl:text-xl font-bold text-gray-900 mt-5 break-all leading-tight">৳{{ toBengaliNumber(monthlyExpenses?.total_amount || 0, 2) }}</p>
                <p class="text-sm text-gray-500 mt-4 border-t border-gray-100 pt-3">
                    {{ toBengaliNumber(monthlyExpenses?.total_expenses || 0) }} {{ t("totalExpenses") }}
                </p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm min-h-[15.5rem]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600">
                            {{ t("totalDeposit") }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">{{ t("topDepositSuppliers") }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-sky-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-lg xl:text-xl font-bold text-gray-900 mt-5 break-all leading-tight">৳{{ toBengaliNumber(totalDepositAmount, 2) }}</p>
                <div class="mt-4 space-y-2 border-t border-gray-100 pt-3">
                    <div
                        v-for="deposit in topDepositSuppliers"
                        :key="deposit.supplier_name"
                        class="flex items-center justify-between text-sm gap-3"
                    >
                        <span class="text-gray-600 truncate min-w-0">{{ deposit.supplier_name }}</span>
                        <span class="font-semibold text-sky-700 shrink text-right text-xs xl:text-sm break-all">৳{{ toBengaliNumber(deposit.total_remaining_deposit || 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm min-h-[15.5rem]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                            {{ t("totalLifting") }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">{{ selectedMonthYear }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m5 4h6" />
                        </svg>
                    </div>
                </div>
                <p class="text-lg xl:text-xl font-bold text-gray-900 mt-5 break-words leading-tight">{{ toBengaliNumber(totalMonthlyLifts) }}</p>
                <div class="mt-4 space-y-2 border-t border-gray-100 pt-3 text-sm">
                    <p class="text-gray-500">
                        {{ toBengaliNumber(totalLiftCases || 0) }} {{ t("totalCases") }}
                    </p>
                    <p class="truncate text-gray-600">
                        {{ t("recentSupplier") }}: <span class="font-medium text-gray-800">{{ recentLiftSupplierName }}</span>
                    </p>
                    <p class="text-gray-600">
                        {{ t("depositBalance") }}:
                        <span class="font-semibold text-amber-700">৳{{ toBengaliNumber(recentLiftSupplierDeposit, 2) }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Child Components -->
        <SalesOverview
            :monthlySales="monthlySales"
            :month="month"
            :year="year"
            :animatedTotalSales="animatedTotalSales"
            :animatedPaidAmount="animatedPaidAmount"
            :animatedDueAmount="animatedDueAmount"
            :animatedProfit="animatedProfit"
            :animatedLoss="animatedLoss"
            :t="t"
            :toBengaliNumber="toBengaliNumber"
            :selectedMonthYear="selectedMonthYear"
            :navigateToPreviousMonth="navigateToPreviousMonth"
            :navigateToNextMonth="navigateToNextMonth"
            :loading="loading"
            :shops="shops"
            :dateWiseSalesData="dateWiseSalesData"
            :sales="sales"
            :lifts="lifts"
            :totalShopsSold="totalShopsSold"
            :totalCasesSold="totalCasesSold"
            :totalLiftCases="totalLiftCases"
            :totalLiftBottles="totalLiftBottles"
            :totalLiftAmount="totalLiftAmount"
            :totalFreeLiftBottles="totalFreeLiftBottles"
            :todaysExpensesAmount="todaysExpensesAmount"
            :topSellingProducts="topSellingProducts"
            :lowStockProducts="lowStockProducts"
            :todaysLiftingCount="todaysLiftingCount"
            :inventoryStock="inventoryStock"
        />

        <!-- InventoryStock
        <InventoryStock
            :inventoryStock="inventoryStock"
            :topSellingProducts="topSellingProducts"
            :lowStockProducts="lowStockProducts"
            :t="t"
            :toBengaliNumber="toBengaliNumber"
            :animatedInventoryStock="animatedInventoryStock"
        /> -->
        <!-- Total Suppliers / Deposit Remaining / Total Shops -->
        <!--
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <TopSuppliers
                :suppliers="suppliers"
                :suppliersCount="animatedSuppliersCount"
                :t="t"
                :toBengaliNumber="toBengaliNumber"
            />
            <TopDepositSuppliers
                :topDeposits="topDeposits"
                :animatedDeposits="animatedDeposits"
                :animatedWidths="animatedWidths"
                :t="t"
                :toBengaliNumber="toBengaliNumber"
            />
            <TotalShops
                :shops="shops"
                :totalShops="animatedTotalShops"
                :t="t"
                :toBengaliNumber="toBengaliNumber"
            />
        </div>
        -->
        <!-- ExpensesOverview
        <ExpensesOverview
            :monthlyExpenses="monthlyExpenses"
            :month="month"
            :year="year"
            :animatedTotalExpenses="animatedTotalExpenses"
            :animatedTotalAmount="animatedTotalAmount"
            :t="t"
            :toBengaliNumber="toBengaliNumber"
            :selectedMonthYear="selectedMonthYear"
            :navigateToPreviousMonth="navigateToPreviousMonth"
            :navigateToNextMonth="navigateToNextMonth"
            :loading="loading"
        /> -->
    </div>
</template>

<script setup>
import { defineProps, ref, onMounted, computed, watch, reactive } from "vue";
import { router, useForm, Link } from "@inertiajs/vue3";
import TopSuppliers from "./Partials/TopSuppliers.vue";
import TopDepositSuppliers from "./Partials/TopDepositSuppliers.vue";
import TotalShops from "./Partials/TotalShops.vue";
import SalesOverview from "./Partials/SalesOverview.vue";
import ExpensesOverview from "./Partials/ExpenseOverview.vue";
import InventoryStock from "./Partials/InventoryStock.vue";
import Layout from "@/Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps({
    suppliersCount: Number,
    topDeposits: Array,
    totalDepositAmount: Number,
    suppliers: Array,
    shops: Array,
    dateWiseSalesData: Array,
    monthlySales: Object,
    monthlyExpenses: Object,
    month: Number,
    year: Number,
    inventoryStock: Array,
    topSellingProducts: Array,
    lowStockProducts: Array,
    sales: Array,
    lifts: Array,
    totalShopsSold: Number,
    totalCasesSold: Number,
    totalLiftCases: Number,
    totalLiftBottles: Number,
    totalLiftAmount: Number,
    totalFreeLiftBottles: Number,
    todaysExpensesAmount: Number,
    topSellingProducts: Array,
    lowStockProducts: Array,
    todaysLiftingCount: Number,
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
        topDepositSuppliers: "Deposit Remaining",
        totalShops: "Shops",
        registeredShops: "Registered shops in the system",
        shop: "Shop",
        expensesOverview: "Expenses Overview",
        previousMonth: "Previous month",
        nextMonth: "Next month",
        salesBreakdown: "Sales Breakdown",
        expensesBreakdown: "Expenses Breakdown",
        paid: "Paid",
        due: "Due",
        totalSales: "Total Revenue",
        totalSalesCard: "Total Sales",
        paidAmount: "Amount Received",
        dueAmount: "Due Amount",
        profitAndLoss: "Profit & Loss",
        profit: "Profit",
        loss: "Loss",
        recentSupplier: "Recent Supplier",
        depositBalance: "Deposit Balance",
        totalExpenses: "Total Expenses",
        totalExpenseCard: "Total Expense",
        totalAmount: "Total Amount",
        totalCost: "Total Cost",
        totalDeposit: "Total Deposit",
        totalLifting: "Total Lifting",
        inventoryStock: "Inventory Stock",
        availableStock: "Available Stock",
        topSellingProducts: "Top Selling Products",
        lowStockProducts: "Low Stock Products",
        totalQuantity: "Total Quantity",
        totalValue: "Total Value",
        totalProducts: "Total Products",
        totalBoxes: "Total Cases",
        totalPurchaseValue: "Total Purchase Value",
        searchProducts: "Search products...",
        showChart: "Show Chart",
        showList: "Show List",
        products: "Products",
        noStockAvailable: "No stock available",
        variantDetails: "Variant Details",
        variant: "Variant",
        boxes: "Cases",
        quantity: "Quantity",
        unitPrice: "Unit Price",
        stockLevel: "Stock Level",
        logout: "Logout",
        shopDues: "Shop Dues",
        amount: "Amount",
        shops: "Shops",
        dailySales: "Daily Sales",
        dailyLifting: "Monthly Lifting",
        shopName: "Shop Name",
        supplierName: "Supplier Name",
        liftNo: "Lift No",
        total: "Total",
        totalShopsSold: "Total Shops Sold",
        totalCasesSold: "Total Cases Sold",
        todaysSnapshot: "Today's Snapshot",
        todaysSell: "Today's Sales",
        totalShops: "Total Shops",
        totalCases: "Total Cases (Box)",
        totalBottles: "Total Bottles",
        monthlySell: "This Month's Sales",
        todaysExpenses: "Today's Expenses",
        topProduct: "Top Product",
        lowStock: "Low Stock",
        todaysLifting: "Total Lifts",
        noLiftingToday: "No lifting this month",
        fastMoving: "Fast Moving",
        fastMovingLowStock: "Fast Moving (Low Stock)",
        slowMoving: "Slow Moving",
        slowMovingHighStock: "Slow Moving (High Stock)",
    },
    bn: {
        languageLabel: "বাংলা",
        dashboardTitle: "ড্যাশবোর্ড",
        totalSuppliers: "মোট সরবরাহকারী",
        activeSuppliers: "সিস্টেমে সক্রিয় সরবরাহকারী",
        supplier: "সরবরাহকারী",
        id: "আইডি",
        topDepositSuppliers: "অবশিষ্ট জমা",
        totalShops: "দোকান",
        registeredShops: "সিস্টেমে নিবন্ধিত দোকান",
        shop: "দোকান",
        expensesOverview: "ব্যয় ওভারভিউ",
        previousMonth: "পূর্ববর্তী মাস",
        nextMonth: "পরবর্তী মাস",
        salesBreakdown: "বিক্রয় বিভাজন",
        expensesBreakdown: "ব্যয় বিভাজন",
        paid: "প্রদত্ত",
        due: "বাকি",
        totalSales: "মোট আয়",
        totalSalesCard: "মোট বিক্রয়",
        paidAmount: "প্রাপ্ত অর্থ",
        dueAmount: "বাকি পরিমাণ",
        profitAndLoss: "লাভ ও ক্ষতি",
        profit: "লাভ",
        loss: "ক্ষতি",
        recentSupplier: "সর্বশেষ সাপ্লায়ার",
        depositBalance: "ডিপোজিট ব্যালেন্স",
        totalExpenses: "মোট ব্যয়",
        totalExpenseCard: "মোট খরচ",
        totalAmount: "মোট পরিমাণ",
        totalCost: "মোট খরচ",
        totalDeposit: "মোট ডিপোজিট",
        totalLifting: "মোট লিফটিং",
        inventoryStock: "ইনভেন্টরি স্টক",
        availableStock: "এভেইলেবল স্টক",
        topSellingProducts: "সর্বাধিক বিক্রিত পণ্য",
        lowStockProducts: "কম স্টক পণ্য",
        totalQuantity: "মোট পরিমাণ",
        totalValue: "মোট মূল্য",
        totalProducts: "মোট পণ্য",
        totalBoxes: "মোট কেস (কার্টুন)",
        totalPurchaseValue: "মোট ক্রয় মূল্য",
        searchProducts: "পণ্য অনুসন্ধান করুন...",
        showChart: "চার্ট দেখান",
        showList: "তালিকা দেখান",
        products: "পণ্য",
        noStockAvailable: "কোন স্টক পাওয়া যায়নি",
        variantDetails: "ভেরিয়েন্ট বিবরণ",
        variant: "ভেরিয়েন্ট",
        boxes: "বক্স",
        quantity: "পরিমাণ",
        unitPrice: "একক দাম",
        stockLevel: "স্টক লেভেল",
        logout: "লগআউট",
        shopDues: "দোকানের বাকি",
        amount: "টাকার পরিমাণ",
        shops: "দোকান",
        dailySales: "দৈনিক বিক্রয়",
        dailyLifting: "মাসিক লিফটিং",
        shopName: " দোকানের নাম",
        supplierName: "সাপ্লায়ারের নাম",
        liftNo: "লিফট নং",
        total: "মোট",
        totalShopsSold: "মোট দোকান বিক্রি",
        totalCasesSold: "মোট কেস বিক্রি",
        todaysSnapshot: "আজকের সারাংশ",
        todaysSell: "আজকের বিক্রয়",
        totalShops: "মোট দোকান",
        totalCases: "মোট কেস (বক্স)",
        totalBottles: "মোট বোতল",
        monthlySell: "এই মাসের বিক্রয়",
        todaysExpenses: "আজকের খরচ",
        topProduct: "শীর্ষ পণ্য",
        lowStock: "কম স্টক",
        todaysLifting: "মোট লিফট",
        noLiftingToday: "এই মাসে কোনো লিফটিং নেই",
        fastMoving: "দ্রুত বিক্রিত",
        fastMovingLowStock: "দ্রুত বিক্রিত (কম স্টক)",
        slowMoving: "ধীর বিক্রিত",
        slowMovingHighStock: "ধীর বিক্রিত (বেশি স্টক)",
    },
};

// Reactive states
const currentLanguage = ref(localStorage.getItem("language") || "en");
const loading = ref(false);
const animatedSuppliersCount = ref(0);
const animatedDeposits = ref([]);
const animatedWidths = ref([]);
const animatedTotalSales = ref(0);
const animatedPaidAmount = ref(0);
const animatedDueAmount = ref(0);
const animatedProfit = ref(0);
const animatedLoss = ref(0);
const animatedTotalShops = ref(0);
const animatedTotalExpenses = ref(0);
const animatedTotalAmount = ref(0);
const animatedInventoryStock = reactive({});
const selectedMonth = ref(props.month - 1);
const selectedYear = ref(props.year);

// Translation function
const t = computed(() => (key) => translations[currentLanguage.value][key]);

// Function to convert numbers to Bengali
const toBengaliNumber = (numValue, decimals = null) => {
    if (numValue === null || numValue === undefined || numValue === "") return "";
    
    let n = Number(numValue);
    if (isNaN(n)) return String(numValue);

    let output;
    if (decimals !== null) {
        output = n.toFixed(decimals);
    } else {
        output = n % 1 !== 0 ? n.toFixed(2) : n.toString();
    }

    if (currentLanguage.value !== "bn") return output;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return output.replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

// Change language
const changeLanguage = (lang) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

// Logout function
const logout = () => {
    const form = useForm({});
    form.post(route("logout"), {
        onSuccess: () => {
            router.visit(route("login"));
        },
    });
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
            const { total_sales, paid_amount, due_amount, profit, loss } =
                page.props.monthlySales;
            const { total_expenses, total_amount } = page.props.monthlyExpenses;
            const duration = 1000;
            animateNumber(
                animatedTotalSales,
                total_sales || 0,
                duration,
                "totalSales"
            );
            animateNumber(
                animatedPaidAmount,
                paid_amount || 0,
                duration,
                "paidAmount"
            );
            animateNumber(
                animatedDueAmount,
                due_amount || 0,
                duration,
                "dueAmount"
            );
            animateNumber(animatedProfit, profit || 0, duration, "profit");
            animateNumber(animatedLoss, loss || 0, duration, "loss");
            animateNumber(
                animatedTotalExpenses,
                total_expenses || 0,
                duration,
                "totalExpenses"
            );
            animateNumber(
                animatedTotalAmount,
                total_amount || 0,
                duration,
                "totalAmount"
            );
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

// Animation function
const animateNumber = (refVar, targetValue, duration, context = "unknown") => {
    if (!refVar || typeof refVar !== "object" || !("value" in refVar)) {
        console.error(
            `Invalid refVar in ${context}: Must be a Vue ref object`,
            refVar
        );
        return;
    }
    if (isNaN(targetValue)) {
        console.warn(`Invalid targetValue in ${context}:`, targetValue);
        targetValue = 0;
    }
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

// Animate inventory stock
const animateInventoryStock = (inventory, duration) => {
    Object.keys(animatedInventoryStock).forEach((key) => {
        delete animatedInventoryStock[key];
    });

    inventory.forEach((item) => {
        if (
            !item.product_name ||
            typeof item.product_name !== "string" ||
            isNaN(item.total_quantity) ||
            isNaN(item.total_value)
        ) {
            console.warn(`Invalid inventory item:`, item);
            return;
        }

        animatedInventoryStock[item.product_name] = {
            total_quantity: 0,
            total_value: 0,
        };

        const targetQuantity = item.total_quantity || 0;
        const targetValue = item.total_value || 0;

        let currentQuantity = 0;
        let currentValue = 0;

        const stepTime = 20;
        const steps = duration / stepTime;
        const quantityIncrement = targetQuantity / steps;
        const valueIncrement = targetValue / steps;

        const timer = setInterval(() => {
            currentQuantity += quantityIncrement;
            currentValue += valueIncrement;

            if (currentQuantity >= targetQuantity) {
                animatedInventoryStock[item.product_name].total_quantity =
                    targetQuantity;
                animatedInventoryStock[item.product_name].total_value =
                    targetValue;
                clearInterval(timer);
            } else {
                animatedInventoryStock[item.product_name].total_quantity =
                    Math.ceil(currentQuantity);
                animatedInventoryStock[item.product_name].total_value =
                    Math.ceil(currentValue);
            }
        }, stepTime);
    });
};

const selectedMonthYear = computed(() => {
    const date = new Date(selectedYear.value, selectedMonth.value);
    return date.toLocaleString(currentLanguage.value, {
        month: "long",
        year: "numeric",
    });
});

const topDepositSuppliers = computed(() => (props.topDeposits || []).slice(0, 4));
const recentLift = computed(() => (props.lifts || [])[0] || null);
const recentLiftSupplierName = computed(
    () => recentLift.value?.supplier?.company_name || recentLift.value?.supplier_name || "-"
);
const recentLiftSupplierDeposit = computed(() => {
    const supplierName = recentLiftSupplierName.value;
    if (!supplierName || supplierName === "-") return 0;

    const matchedDeposit = (props.topDeposits || []).find(
        (deposit) => deposit.supplier_name === supplierName
    );

    return Number(matchedDeposit?.total_remaining_deposit || 0);
});

const totalMonthlyLifts = computed(() => (props.lifts || []).length);

onMounted(() => {
    const duration = 2000;

    document.documentElement.lang = currentLanguage.value;

    animateNumber(
        animatedSuppliersCount,
        props.suppliersCount || props.suppliers.length || 0,
        duration,
        "suppliersCount"
    );

    animatedWidths.value = new Array(props.topDeposits.length).fill(0);
    animatedDeposits.value = new Array(props.topDeposits.length).fill(0);

    props.topDeposits.forEach((deposit, index) => {
        let startDeposit = 0;
        const targetDeposit = deposit.total_remaining_deposit || 0;
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
        props.monthlySales || {};
    const { total_expenses, total_amount } = props.monthlyExpenses || {};
    if (
        typeof total_sales !== "number" ||
        typeof paid_amount !== "number" ||
        typeof due_amount !== "number" ||
        typeof profit !== "number" ||
        typeof loss !== "number"
    ) {
        console.warn("Invalid monthlySales data:", props.monthlySales);
    } else {
        animateNumber(animatedTotalSales, total_sales, duration, "totalSales");
        animateNumber(animatedPaidAmount, paid_amount, duration, "paidAmount");
        animateNumber(animatedDueAmount, due_amount, duration, "dueAmount");
        animateNumber(animatedProfit, profit, duration, "profit");
        animateNumber(animatedLoss, loss, duration, "loss");
    }

    if (
        typeof total_expenses !== "number" ||
        typeof total_amount !== "number"
    ) {
        console.warn("Invalid monthlyExpenses data:", props.monthlyExpenses);
    } else {
        animateNumber(
            animatedTotalExpenses,
            total_expenses,
            duration,
            "totalExpenses"
        );
        animateNumber(
            animatedTotalAmount,
            total_amount,
            duration,
            "totalAmount"
        );
    }

    animateNumber(
        animatedTotalShops,
        props.shops.length || 0,
        duration,
        "totalShops"
    );

    animateInventoryStock(props.inventoryStock, duration);
});

watch(
    () => props.monthlySales,
    (newVal) => {
        const duration = 1000;
        if (
            typeof newVal.total_sales !== "number" ||
            typeof newVal.paid_amount !== "number" ||
            typeof newVal.due_amount !== "number" ||
            typeof newVal.profit !== "number" ||
            typeof newVal.loss !== "number"
        ) {
            console.warn("Invalid monthlySales data in watch:", newVal);
            return;
        }
        animateNumber(
            animatedTotalSales,
            newVal.total_sales,
            duration,
            "totalSales"
        );
        animateNumber(
            animatedPaidAmount,
            newVal.paid_amount,
            duration,
            "paidAmount"
        );
        animateNumber(
            animatedDueAmount,
            newVal.due_amount,
            duration,
            "dueAmount"
        );
        animateNumber(animatedProfit, newVal.profit, duration, "profit");
        animateNumber(animatedLoss, newVal.loss, duration, "loss");
    },
    { deep: true }
);

watch(
    () => props.monthlyExpenses,
    (newVal) => {
        const duration = 1000;
        if (
            typeof newVal.total_expenses !== "number" ||
            typeof newVal.total_amount !== "number"
        ) {
            console.warn("Invalid monthlyExpenses data in watch:", newVal);
            return;
        }
        animateNumber(
            animatedTotalExpenses,
            newVal.total_expenses,
            duration,
            "totalExpenses"
        );
        animateNumber(
            animatedTotalAmount,
            newVal.total_amount,
            duration,
            "totalAmount"
        );
    },
    { deep: true }
);

watch(
    () => props.inventoryStock,
    (newVal) => {
        const duration = 1000;
        animateInventoryStock(newVal, duration);
    },
    { deep: true }
);

const getBarWidth = (amount) => {
    const maxAmount = Math.max(
        ...props.topDeposits.map((d) => d.total_remaining_deposit || 0),
        1
    );
    const width = (amount / maxAmount) * 100;
    return Math.min(Math.max(width, 10), 100);
};
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

.bangla-font h1,
.bangla-font h2,
.bangla-font h3,
.bangla-font h4,
.bangla-font p,
.bangla-font span,
.bangla-font button,
.bangla-font input::placeholder {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
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

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

.from-white {
    --tw-gradient-from: #ffffff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(255, 255, 255, 0));
}

.to-gray-50 {
    --tw-gradient-to: #f9fafb;
}
</style>
