<!-- components/SalesOverview.vue -->
<template>
    <div class="space-y-6">
        <ShopDuesChart
            :dateWiseSalesData="dateWiseSalesData"
            :t="t"
            :toBengaliNumber="toBengaliNumber"
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Today's Sales -->
            <div
                class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow cursor-pointer"
                @click="goToDailySalesReport"
            >
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">{{ t("dailySales") }}</span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ t("todaysSell") }}</span>
                        <span class="text-lg font-bold text-gray-900">{{ toBengaliNumber(dailyTotals.total) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ t("totalShops") }}</span>
                        <span class="text-lg font-bold text-gray-900">{{ toBengaliNumber(totalShopsSold) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">{{ t("totalCases") }}</span>
                        <span class="text-lg font-bold text-gray-900">{{ toBengaliNumber(totalCasesSold) }}</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div
                class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow cursor-pointer"
                @click="goToMonthlySalesReport"
            >
                <span class="text-xs font-semibold text-green-600 uppercase tracking-wide">{{ t("monthlySell") }}</span>
                <p class="text-sm text-gray-500 mt-2">{{ formattedSelectedMonthYear }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ toBengaliNumber(monthlySalesTotal) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ t("totalSales") }}</p>
            </div>

            <!-- Today's Expenses -->
            <div
                class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow cursor-pointer"
                @click="goToExpensesPage"
            >
                <span class="text-xs font-semibold text-red-600 uppercase tracking-wide">{{ t("todaysExpenses") }}</span>
                <p class="text-sm text-gray-500 mt-2">{{ dailySalesDate }}</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ toBengaliNumber(todaysExpensesTotal) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ t("totalExpenses") }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Top Product -->
            <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-orange-600 uppercase tracking-wide">{{ t("topProduct") }}</span>
                <p class="text-lg font-bold text-gray-900 mt-2 truncate">{{ topProductName }}</p>
            </div>

            <!-- Low Stock -->
            <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-yellow-600 uppercase tracking-wide">{{ t("lowStock") }}</span>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ toBengaliNumber(lowStockCount) }}</p>
            </div>

            <!-- Today's Lifting -->
            <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">{{ t("todaysLifting") }}</span>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ toBengaliNumber(todaysLifting) }}</p>
            </div>

            <!-- Fast Moving -->
            <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <span class="text-xs font-semibold text-purple-600 uppercase tracking-wide">{{ t("fastMoving") }}</span>
                <p class="text-sm font-medium text-gray-900 mt-2 truncate">{{ fastMovingLowStock }}</p>
            </div>
        </div>

        <!-- Daily sales table and list commented out as requested -->
        <!--
        <div class="mt-8">
                    <div
                        class="bg-gray-50 rounded-lg p-4 mb-6 shadow-inner border border-gray-200"
                    >
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-semibold text-gray-800">
                                {{ t("dailySales") }}
                            </h3>
                            <div class="flex items-center space-x-6">
                                <input
                                    type="date"
                                    v-model="dailySalesDate"
                                    @change="navigateDailySales"
                                    class="px-4 py-2 rounded-md font-medium transition-colors bg-gray-200 text-gray-800 hover:bg-gray-300"
                                />
                                <div class="text-right">
                                    <h4
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        {{ t("totalShopsSold") }}
                                    </h4>
                                    <p class="text-2xl font-bold text-gray-700">
                                        {{ toBengaliNumber(totalShopsSold) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <h4
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        {{ t("totalCasesSold") }}
                                    </h4>
                                    <p class="text-2xl font-bold text-gray-700">
                                        {{ toBengaliNumber(totalCasesSold) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t("shopName") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t("totalAmount") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t("paidAmount") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t("dueAmount") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="sale in sales"
                                    :key="sale.id"
                                    class="hover:bg-gray-50 cursor-pointer"
                                    @click="redirectToSale(sale.id)"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{ sale?.shop?.shop_name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ toBengaliNumber(sale.total_amount) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ toBengaliNumber(sale.paid_amount) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ toBengaliNumber(sale.due_amount) }}
                                    </td>
                                </tr>
                                <tr class="bg-gray-50 font-semibold">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                                    >
                                        {{ t("total") }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                                    >
                                        {{ toBengaliNumber(dailyTotals.total) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                                    >
                                        {{ toBengaliNumber(dailyTotals.paid) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800"
                                    >
                                        {{ toBengaliNumber(dailyTotals.due) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
        </div>
        -->
    </div>
</template>

<script setup>
import { defineProps, ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import ShopDuesChart from "./ShopDuesChart.vue";

const props = defineProps({
    t: Function,
    toBengaliNumber: Function,
    month: Number,
    year: Number,
    shops: Array,
    dateWiseSalesData: Array,
    sales: Array,
    totalShopsSold: Number,
    totalCasesSold: Number,
    monthlySales: Object,
    selectedMonthYear: String,
    todaysExpensesAmount: Number,
    topSellingProducts: Array,
    lowStockProducts: Array,
    todaysLiftingCount: Number,
    inventoryStock: Array,
});

// Get initial dates from URL or use today's date
const getInitialDate = (paramName) => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(paramName) || new Date().toISOString().substr(0, 10);
};

const dailySalesDate = ref(getInitialDate("daily_sales_date"));

const navigateDailySales = () => {
    router.visit(`/dashboard?daily_sales_date=${dailySalesDate.value}`, {
        preserveScroll: true,
        preserveState: true,
    });
};

const goToSalesReport = (startDate, endDate) => {
    const params = {};

    if (startDate) params.start_date = startDate;
    if (endDate) params.end_date = endDate;

    router.get("/sales/report", params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const goToDailySalesReport = () => {
    const date = dailySalesDate.value || new Date().toISOString().slice(0, 10);
    goToSalesReport(date, date);
};

const goToMonthlySalesReport = () => {
    const today = new Date();
    const year = props.year || today.getFullYear();
    const monthIndex =
        typeof props.month === "number" ? props.month - 1 : today.getMonth();

    const start = new Date(year, monthIndex, 1)
        .toISOString()
        .slice(0, 10);
    const end = new Date(year, monthIndex + 1, 0)
        .toISOString()
        .slice(0, 10);

    goToSalesReport(start, end);
};

const goToExpensesPage = () => {
    router.visit("/expenses", {
        preserveState: true,
        preserveScroll: true,
    });
};

const redirectToSale = (saleId) => {
    router.visit(`/sales/report?id=${saleId}`);
};

const dailyTotals = computed(() => {
    if (!props.sales) {
        return { total: 0, paid: 0, due: 0 };
    }
    return props.sales.reduce(
        (totals, sale) => {
            totals.total += parseFloat(sale.total_amount) || 0;
            totals.paid += parseFloat(sale.paid_amount) || 0;
            totals.due += parseFloat(sale.due_amount) || 0;
            return totals;
        },
        { total: 0, paid: 0, due: 0 }
    );
});

const monthlySalesTotal = computed(() => {
    if (!props.monthlySales || typeof props.monthlySales.total_sales !== "number") {
        return 0;
    }
    return props.monthlySales.total_sales;
});

const formattedSelectedMonthYear = computed(
    () => props.selectedMonthYear || ""
);

const todaysExpensesTotal = computed(
    () => props.todaysExpensesAmount || 0
);

const topProductName = computed(() => {
    if (!props.topSellingProducts || !props.topSellingProducts.length) {
        return "-";
    }
    return props.topSellingProducts[0].name || "-";
});

const lowStockCount = computed(() => {
    if (!props.lowStockProducts) return 0;
    return props.lowStockProducts.length;
});

const todaysLifting = computed(() => props.todaysLiftingCount || 0);

const fastMovingLowStock = computed(() => {
    if (!props.lowStockProducts || !props.lowStockProducts.length) {
        return "-";
    }
    // Show the first low stock product name
    const product = props.lowStockProducts[0];
    return product.product_name || product.name || "-";
});

const slowMovingHighStock = computed(() => {
    if (!props.inventoryStock || !props.inventoryStock.length) {
        return "-";
    }
    const sorted = [...props.inventoryStock].sort(
        (a, b) => (b.total_available_bottles || 0) - (a.total_available_bottles || 0)
    );
    const product = sorted[0];
    return product.product_name || product.name || "-";
});
</script>
