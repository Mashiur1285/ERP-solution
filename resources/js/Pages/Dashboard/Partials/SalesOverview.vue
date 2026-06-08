<!-- components/SalesOverview.vue -->
<template>
    <div class="space-y-6">
        <!-- Sales Summary Section -->
        <div class="bg-white rounded-sm border border-gray-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 bg-indigo-600">
                <div class="flex items-center gap-2">
                    <div class="hidden sm:block p-1.5 bg-white/20 rounded-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="ts-title font-semibold text-white uppercase tracking-wide">{{ t("dailySales") }}</h3>
                    <span class="ts-label text-indigo-100">— {{ dailySalesDate }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="showSalesList = !showSalesList"
                        class="ts-label text-white/80 hover:text-white font-medium flex items-center gap-1"
                    >
                        {{ t("showList") }}
                        <svg
                            class="w-3.5 h-3.5 transition-transform"
                            :class="{ 'rotate-180': showSalesList }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <button
                        @click="goToDailySalesReport"
                        class="ts-label text-white hover:text-indigo-100 font-semibold flex items-center gap-1"
                    >
                        {{ t("totalSales") }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-5 divide-x divide-y sm:divide-y-0 divide-gray-100">
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalSales") }}</p>
                    <p class="ts-value font-bold text-gray-900">৳{{ props.toBengaliNumber(dailyTotals.total, 2) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("paidAmount") }}</p>
                    <p class="ts-value font-bold text-green-600">৳{{ props.toBengaliNumber(dailyTotals.paid, 2) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("dueAmount") }}</p>
                    <p class="ts-value font-bold text-red-500">৳{{ props.toBengaliNumber(dailyTotals.due, 2) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("profit") }}</p>
                    <p
                        class="ts-value font-bold"
                        :class="dailyTotals.profit >= 0 ? 'text-indigo-600' : 'text-red-600'"
                    >
                        ৳{{ props.toBengaliNumber(dailyTotals.profit, 2) }}
                    </p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalCasesSold") }}</p>
                    <p class="ts-value font-bold text-sky-600">{{ props.toBengaliNumber(props.totalCasesSold || 0) }}</p>
                </div>
            </div>

            <!-- Sales Table -->
            <div v-if="showSalesList" class="border-t border-gray-100">
                <div v-if="props.sales && props.sales.length > 0" class="overflow-x-auto">
                    <table class="w-full ts-detail">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("shopName") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("totalAmount") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">{{ t("paidAmount") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">{{ t("dueAmount") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("totalCasesSold") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("profit") }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="(sale, idx) in props.sales"
                                :key="sale.id"
                                class="hover:bg-gray-50 cursor-pointer transition-colors"
                                @click="redirectToSale(sale.id)"
                            >
                                <td class="px-4 py-3 text-gray-400 ts-label">{{ props.toBengaliNumber(idx + 1) }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ sale?.shop?.shop_name || '—' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                    ৳{{ props.toBengaliNumber(parseFloat(sale.total_amount) || 0, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-green-600 hidden sm:table-cell">
                                    ৳{{ props.toBengaliNumber(parseFloat(sale.paid_amount) || 0, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-red-500 hidden sm:table-cell">
                                    ৳{{ props.toBengaliNumber(parseFloat(sale.due_amount) || 0, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-sky-600">
                                    {{ props.toBengaliNumber(getSaleCases(sale)) }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right font-medium"
                                    :class="(sale.items || []).reduce((s, i) => s + (parseFloat(i.profit) || 0), 0) >= 0 ? 'text-indigo-600' : 'text-red-600'"
                                >
                                    ৳{{ props.toBengaliNumber((sale.items || []).reduce((s, i) => s + (parseFloat(i.profit) || 0), 0), 2) }}
                                </td>
                            </tr>
                            <tr class="bg-indigo-50 font-bold border-t-2 border-indigo-100">
                                <td class="px-4 py-3 ts-label text-indigo-700" colspan="2">{{ t("total") }}</td>
                                <td class="px-4 py-3 text-right text-indigo-800">
                                    ৳{{ props.toBengaliNumber(dailyTotals.total, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-green-700 hidden sm:table-cell">
                                    ৳{{ props.toBengaliNumber(dailyTotals.paid, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-red-600 hidden sm:table-cell">
                                    ৳{{ props.toBengaliNumber(dailyTotals.due, 2) }}
                                </td>
                                <td class="px-4 py-3 text-right text-sky-700">
                                    {{ props.toBengaliNumber(props.totalCasesSold || 0) }}
                                </td>
                                <td
                                    class="px-4 py-3 text-right"
                                    :class="dailyTotals.profit >= 0 ? 'text-indigo-700' : 'text-red-700'"
                                >
                                    ৳{{ props.toBengaliNumber(dailyTotals.profit, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-10 text-center text-gray-400 ts-detail">
                    <svg class="mx-auto h-8 w-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    No sales today
                </div>
            </div>
        </div>

        <div class="bg-white rounded-sm border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 bg-amber-500">
                <div class="flex items-center gap-2">
                    <div class="hidden sm:block p-1.5 bg-white/20 rounded-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m5 4h6" />
                        </svg>
                    </div>
                    <h3 class="ts-title font-semibold text-white uppercase tracking-wide">{{ t("dailyLifting") }}</h3>
                    <span class="ts-label text-amber-50">— {{ formattedSelectedMonthYear }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="showLiftList = !showLiftList"
                        class="ts-label text-white/80 hover:text-white font-medium flex items-center gap-1"
                    >
                        {{ t("showList") }}
                        <svg
                            class="w-3.5 h-3.5 transition-transform"
                            :class="{ 'rotate-180': showLiftList }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <button
                        @click="goToLiftReport"
                        class="ts-label text-white hover:text-amber-50 font-semibold flex items-center gap-1"
                    >
                        {{ t("totalCost") }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-gray-100">
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalCost") }}</p>
                    <p class="ts-value font-bold text-gray-900">৳{{ props.toBengaliNumber(monthlyLiftTotals.totalAmount, 2) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("todaysLifting") }}</p>
                    <p class="ts-value font-bold text-amber-600">{{ props.toBengaliNumber(monthlyLiftTotals.totalLifts) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalCases") }}</p>
                    <p class="ts-value font-bold text-sky-600">{{ props.toBengaliNumber(monthlyLiftTotals.totalCases) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalBottles") }}</p>
                    <p class="ts-value font-bold text-emerald-600">{{ props.toBengaliNumber(monthlyLiftTotals.totalBottles) }}</p>
                </div>
            </div>

            <div v-if="showLiftList" class="border-t border-gray-100">
                <div v-if="props.lifts && props.lifts.length > 0" class="overflow-x-auto overflow-y-auto max-h-[34rem]">
                    <table class="w-full ts-detail">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("liftNo") }}</th>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("supplierName") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("totalCases") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">{{ t("totalBottles") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("totalAmount") }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="(lift, idx) in props.lifts"
                                :key="lift.id"
                                class="hover:bg-gray-50 cursor-pointer transition-colors"
                                @click="goToLiftReport"
                            >
                                <td class="px-4 py-3 text-gray-400 ts-label">{{ props.toBengaliNumber(idx + 1) }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ lift.lift_number || "—" }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ lift?.supplier?.company_name || "—" }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-sky-700">
                                    {{ props.toBengaliNumber(getLiftCases(lift)) }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-700 hidden sm:table-cell">
                                    {{ props.toBengaliNumber(getLiftBottles(lift)) }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-amber-700">
                                    ৳{{ props.toBengaliNumber(parseFloat(lift.total_amount) || 0, 2) }}
                                </td>
                            </tr>
                            <tr class="bg-amber-50 font-bold border-t-2 border-amber-100 sticky bottom-0">
                                <td class="px-4 py-3 ts-label text-amber-700" colspan="3">{{ t("total") }}</td>
                                <td class="px-4 py-3 text-right text-sky-800">
                                    {{ props.toBengaliNumber(monthlyLiftTotals.totalCases) }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-800 hidden sm:table-cell">
                                    {{ props.toBengaliNumber(monthlyLiftTotals.totalBottles) }}
                                </td>
                                <td class="px-4 py-3 text-right text-amber-800">
                                    ৳{{ props.toBengaliNumber(monthlyLiftTotals.totalAmount, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-10 text-center text-gray-400 ts-detail">
                    <svg class="mx-auto h-8 w-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m5 4h6" />
                    </svg>
                    {{ t("noLiftingToday") }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-sm border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 bg-emerald-600">
                <div class="flex items-center gap-2">
                    <div class="hidden sm:block p-1.5 bg-white/20 rounded-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                        </svg>
                    </div>
                    <h3 class="ts-title font-semibold text-white uppercase tracking-wide">{{ t("availableStock") }}</h3>
                    <span class="ts-label text-emerald-50">— {{ t("inventoryStock") }}</span>
                </div>
                <button
                    type="button"
                    @click="showStockList = !showStockList"
                    class="ts-label text-white hover:text-emerald-50 font-semibold flex items-center gap-1"
                >
                    {{ t("showList") }}
                    <svg
                        class="w-3.5 h-3.5 transition-transform"
                        :class="{ 'rotate-180': showStockList }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 divide-x divide-y sm:divide-y-0 divide-gray-100">
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalProducts") }}</p>
                    <p class="ts-value font-bold text-gray-900">{{ props.toBengaliNumber(availableStockTotals.totalProducts) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("stockValue") }}</p>
                    <p class="ts-value font-bold text-rose-600">৳{{ props.toBengaliNumber(availableStockTotals.totalStockValue) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalCases") }}</p>
                    <p class="ts-value font-bold text-emerald-600">{{ props.toBengaliNumber(availableStockTotals.totalCases) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalBottles") }}</p>
                    <p class="ts-value font-bold text-sky-600">{{ props.toBengaliNumber(availableStockTotals.totalBottles) }}</p>
                </div>
                <div class="p-4">
                    <p class="ts-label text-gray-500 mb-1">{{ t("totalSuppliers") }}</p>
                    <p class="ts-value font-bold text-indigo-600">{{ props.toBengaliNumber(availableStockTotals.totalSuppliers) }}</p>
                </div>
            </div>

            <div v-if="showStockList" class="border-t border-gray-100">
                <div v-if="props.inventoryStock && props.inventoryStock.length > 0" class="overflow-x-auto overflow-y-auto max-h-[34rem]">
                    <table class="w-full ts-detail">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("products") }}</th>
                                <th class="px-4 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("supplierName") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("totalCases") }}</th>
                                <th class="px-4 py-3 text-right ts-label font-medium text-gray-500 uppercase tracking-wider">{{ t("totalBottles") }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="(item, idx) in props.inventoryStock" :key="item.product_id || item.product_name" class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-400 ts-label">{{ props.toBengaliNumber(idx + 1) }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ item.product_name || "—" }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ item.supplier_name || "—" }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-700">
                                    {{ props.toBengaliNumber(item.total_available_cases || 0) }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-sky-700">
                                    {{ props.toBengaliNumber(item.total_available_bottles || 0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="py-10 text-center text-gray-400 ts-detail">
                    <svg class="mx-auto h-8 w-8 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                    </svg>
                    {{ t("noStockAvailable") }}
                </div>
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
                                        class="px-6 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t("shopName") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t("totalAmount") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t("paidAmount") }}
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left ts-label font-medium text-gray-500 uppercase tracking-wider"
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
import { defineProps, ref, computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    t: Function,
    toBengaliNumber: Function,
    month: Number,
    year: Number,
    shops: Array,
    dateWiseSalesData: Array,
    sales: Array,
    lifts: Array,
    totalShopsSold: Number,
    totalCasesSold: Number,
    totalLiftCases: Number,
    totalLiftBottles: Number,
    totalLiftAmount: Number,
    totalFreeLiftBottles: Number,
    monthlySales: Object,
    selectedMonthYear: String,
    todaysExpensesAmount: Number,
    topSellingProducts: Array,
    lowStockProducts: Array,
    todaysLiftingCount: Number,
    inventoryStock: Array,
});

const showLiftList = ref(false);
const showStockList = ref(false);
const showSalesList = ref(false);

// Returns date as YYYY-MM-DD in LOCAL timezone (avoids UTC offset bug)
const localDate = (d = new Date()) =>
    `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;

// Get initial dates from URL or use today's date
const getInitialDate = (paramName) => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(paramName) || localDate();
};

const dailySalesDate = ref(getInitialDate("daily_sales_date"));


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
    const date = dailySalesDate.value || localDate();
    goToSalesReport(date, date);
};


const goToLiftReport = () => {
    const today = new Date();
    const year = props.year || today.getFullYear();
    const monthIndex =
        typeof props.month === "number" ? props.month - 1 : today.getMonth();

    const start = localDate(new Date(year, monthIndex, 1));
    const end = localDate(new Date(year, monthIndex + 1, 0));

    router.get(
        "/lifts/report",
        {
            start_date: start,
            end_date: end,
        },
        {
            preserveScroll: true,
        }
    );
};

const redirectToSale = (saleId) => {
    router.visit(`/sales/report?id=${saleId}`);
};

const getLiftCases = (lift) =>
    (lift.items || []).reduce(
        (sum, item) => sum + (parseInt(item.number_of_cases, 10) || 0),
        0
    );

const getSaleCases = (sale) =>
    (sale.items || []).reduce(
        (sum, item) =>
            sum +
            (parseInt(item.cases_sold, 10) ||
                parseInt(item.number_of_cases, 10) ||
                0),
        0
    );

const getLiftBottles = (lift) =>
    (lift.items || []).reduce(
        (sum, item) => sum + (parseInt(item.total_bottles, 10) || 0),
        0
    );

const dailyTotals = computed(() => {
    if (!props.sales) {
        return { total: 0, paid: 0, due: 0, profit: 0 };
    }
    return props.sales.reduce(
        (totals, sale) => {
            totals.total += parseFloat(sale.total_amount) || 0;
            totals.paid += parseFloat(sale.paid_amount) || 0;
            totals.due += parseFloat(sale.due_amount) || 0;
            totals.profit += (sale.items || []).reduce(
                (s, item) => s + (parseFloat(item.profit) || 0),
                0
            );
            return totals;
        },
        { total: 0, paid: 0, due: 0, profit: 0 }
    );
});

const monthlyLiftTotals = computed(() => ({
    totalLifts: props.lifts?.length || 0,
    totalCases: Number(props.totalLiftCases) || 0,
    totalBottles: Number(props.totalLiftBottles) || 0,
    totalAmount: Number(props.totalLiftAmount) || 0,
    totalFreeBottles: Number(props.totalFreeLiftBottles) || 0,
}));

const availableStockTotals = computed(() => ({
    totalProducts: props.inventoryStock?.length || 0,
    totalCases: (props.inventoryStock || []).reduce(
        (sum, item) => sum + (Number(item.total_available_cases) || 0),
        0
    ),
    totalBottles: (props.inventoryStock || []).reduce(
        (sum, item) => sum + (Number(item.total_available_bottles) || 0),
        0
    ),
    totalStockValue: (props.inventoryStock || []).reduce(
        (sum, item) => sum + (Number(item.total_stock_value) || 0),
        0
    ),
    totalSuppliers: new Set(
        (props.inventoryStock || [])
            .map((item) => item.supplier_name)
            .filter(Boolean)
    ).size,
}));

const formattedSelectedMonthYear = computed(
    () => props.selectedMonthYear || ""
);
</script>
