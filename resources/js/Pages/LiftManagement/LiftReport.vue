<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 max-w-7xl mx-auto"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
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
                {{ t("liftReport") }}
            </h1>
            <button
                @click="printReport"
                class="print:hidden inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-1m-10 0h10v4H10v-4z" />
                </svg>
                {{ t("printPdf") }}
            </button>
        </div>

        <section class="print-only lift-print-sheet">
            <div class="lift-print-header">
                <div>
                    <p class="lift-print-kicker">{{ t("liftReport") }}</p>
                    <h2 class="lift-print-title">{{ t("liftingPerformanceSummary") }}</h2>
                    <p class="lift-print-subtitle">
                        {{ t("reportPeriod") }}:
                        {{ formatDate(dateStart || "") }}
                        <span v-if="dateEnd">- {{ formatDate(dateEnd || "") }}</span>
                    </p>
                </div>
                <div class="lift-print-meta">
                    <p>{{ t("generatedOn") }}: {{ printedAtLabel }}</p>
                    <p>{{ t("status") }}: {{ t(activeTab === "draft" ? "draftTab" : "completedTab") }}</p>
                </div>
            </div>

            <div class="lift-print-summary">
                <div class="lift-print-card">
                    <span>{{ t("totalLifts") }}</span>
                    <strong>{{ toBn(totalLifts) }}</strong>
                </div>
                <div class="lift-print-card">
                    <span>{{ t("totalCases") }}</span>
                    <strong>{{ toBn(totalCases) }}</strong>
                </div>
                <div class="lift-print-card">
                    <span>{{ t("totalBottles") }}</span>
                    <strong>{{ toBn(totalBottles) }}</strong>
                </div>
                <div class="lift-print-card">
                    <span>{{ t("totalItems") }}</span>
                    <strong>{{ toBn(totalItems) }}</strong>
                </div>
                <div class="lift-print-card">
                    <span>{{ t("totalCost") }}</span>
                    <strong>৳{{ toBn(totalAmount.toFixed(2)) }}</strong>
                </div>
            </div>

            <table class="lift-print-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ t("liftNumber") }}</th>
                        <th>{{ t("supplier") }}</th>
                        <th>{{ t("liftDate") }}</th>
                        <th>{{ t("totalItems") }}</th>
                        <th>{{ t("totalCases") }}</th>
                        <th>{{ t("totalBottles") }}</th>
                        <th>{{ t("totalCost") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(lift, index) in filteredLifts" :key="`print-${lift.id}`">
                        <td>{{ toBn(index + 1) }}</td>
                        <td>{{ lift.lift_number || "-" }}</td>
                        <td>{{ lift.supplier?.company_name || "-" }}</td>
                        <td>{{ formatDate(lift.lift_date) }}</td>
                        <td>{{ toBn(lift.items?.length || 0) }}</td>
                        <td>{{ toBn(getLiftTotalCases(lift)) }}</td>
                        <td>{{ toBn(getLiftTotalBottles(lift)) }}</td>
                        <td>৳{{ toBn(Number(lift.total_amount || 0).toFixed(2)) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">{{ t("total") }}</td>
                        <td>{{ toBn(totalItems) }}</td>
                        <td>{{ toBn(totalCases) }}</td>
                        <td>{{ toBn(totalBottles) }}</td>
                        <td>৳{{ toBn(totalAmount.toFixed(2)) }}</td>
                    </tr>
                </tfoot>
            </table>
        </section>

        <!-- Total Metrics -->
        <div class="print:hidden grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
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
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-700">
                            {{ t("totalLifts") }}
                        </p>
                        <p class="text-lg font-bold text-indigo-900">
                            {{ toBn(totalLifts) }}
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
                            {{ t("totalCases") }}
                        </p>
                        <p class="text-lg font-bold text-orange-900">
                            {{ toBn(totalCases) }}
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
                            {{ t("totalBottles") }}
                        </p>
                        <p class="text-lg font-bold text-purple-900">
                            {{ toBn(totalBottles) }}
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
                            {{ t("totalAmount") }}
                        </p>
                        <p class="text-lg font-bold text-green-900">
                            ৳{{ toBn(totalAmount.toFixed(2)) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Fields -->
        <div class="print:hidden flex flex-col sm:flex-row sm:justify-between items-center mb-4 gap-4">
            <div class="flex items-center gap-2 rounded-xl bg-white p-1 shadow-sm border border-gray-200">
                <button
                    @click="activeTab = 'completed'"
                    :class="[
                        'px-4 py-2 text-sm font-medium rounded-lg transition-colors',
                        activeTab === 'completed' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100',
                    ]"
                >
                    {{ t("completedTab") }}
                </button>
                <button
                    @click="activeTab = 'draft'"
                    :class="[
                        'px-4 py-2 text-sm font-medium rounded-lg transition-colors',
                        activeTab === 'draft' ? 'bg-amber-500 text-white' : 'text-gray-600 hover:bg-gray-100',
                    ]"
                >
                    {{ t("draftTab") }}
                </button>
            </div>
            <DateRangePicker
                v-model:startDate="dateStart"
                v-model:endDate="dateEnd"
                :language="currentLanguage"
                class="w-full sm:w-auto"
            />
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="t('searchLifts')"
                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                />
            </div>
        </div>

        <!-- Lift Cards -->
        <div class="print:hidden space-y-4">
            <div
                v-if="!filteredLifts.length"
                class="text-center text-gray-500 py-8 text-sm bg-white rounded-xl shadow-sm"
            >
                {{ t("noLifts") }}
            </div>

            <div
                v-for="lift in filteredLifts"
                :key="lift.id"
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
            >
                <!-- Lift Header (clickable) -->
                <div
                    class="flex items-center justify-between px-4 lg:px-6 py-4 cursor-pointer hover:bg-gray-50 transition-colors"
                    @click="toggleLift(lift.id)"
                >
                    <div class="flex items-center space-x-4">
                        <svg
                            :class="[
                                'w-4 h-4 transition-transform flex-shrink-0 text-gray-500',
                                expandedLifts[lift.id] ? 'rotate-90' : '',
                            ]"
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
                        <div>
                            <div class="flex items-center space-x-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                                >
                                    {{ lift.lift_number }}
                                </span>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ lift.supplier?.company_name || '-' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ formatDate(lift.lift_date) }}
                                <span v-if="lift.items" class="ml-2">
                                    · {{ toBn(lift.items.length) }} {{ t("items") }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center justify-end gap-2 mb-2">
                            <button
                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                                :class="lift.status === 'draft' ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-blue-100 text-blue-800 hover:bg-blue-200'"
                                @click.stop="editLift(lift)"
                            >
                                {{ lift.status === 'draft' ? t("editDraft") : t("editLift") }}
                            </button>
                            <button
                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors"
                                @click.stop="deleteLift(lift.id)"
                            >
                                {{ t("deleteLift") }}
                            </button>
                        </div>
                        <p class="text-lg font-bold text-green-600">
                            ৳{{ toBn(Number(lift.total_amount || 0).toFixed(2)) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ toBn(getLiftTotalCases(lift)) }} {{ t("cases") }}
                            · {{ toBn(getLiftTotalBottles(lift)) }} {{ t("bottles") }}
                        </p>
                    </div>
                </div>

                <!-- Expanded Items -->
                <div
                    v-if="expandedLifts[lift.id]"
                    class="border-t border-gray-200 animate-slide-down"
                >
                    <!-- Notes -->
                    <div
                        v-if="lift.notes"
                        class="px-4 lg:px-6 py-2 bg-yellow-50 border-b border-yellow-100 text-sm text-yellow-800"
                    >
                        <span class="font-medium">{{ t("notes") }}:</span> {{ lift.notes }}
                    </div>

                    <!-- Items Table (Desktop) -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("product") }}
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("variant") }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("cases") }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("pricePerCase") }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("bottlesPerCase") }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("freePerCase") }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("totalBottles") }}
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("freeBottles") }}
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("ratePerBottle") }}
                                    </th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        {{ t("totalCost") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="item in lift.items"
                                    :key="item.id"
                                    class="hover:bg-gray-50 transition-colors"
                                >
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ item.product_catalog?.name || '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-xs font-medium">
                                            {{ item.variant }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-7 bg-indigo-100 text-indigo-800 rounded font-bold text-sm">
                                            {{ toBn(item.number_of_cases) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center font-medium">
                                        ৳{{ toBn(Number(item.case_buying_price).toFixed(2)) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        {{ toBn(item.bottles_per_case) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span :class="item.free_bottles_per_case > 0 ? 'text-green-600 font-medium' : 'text-gray-400'">
                                            {{ toBn(item.free_bottles_per_case || 0) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center font-medium">
                                        {{ toBn(item.total_bottles) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span :class="item.total_free_bottles > 0 ? 'text-green-600 font-medium' : 'text-gray-400'">
                                            {{ toBn(item.total_free_bottles || 0) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-700">
                                        ৳{{ toBn(Number(item.actual_rate_per_bottle || 0).toFixed(2)) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-bold text-green-600">
                                        ৳{{ toBn(Number(item.total_cost || 0).toFixed(2)) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Items Cards (Mobile) -->
                    <div class="lg:hidden p-4 space-y-3">
                        <div
                            v-for="item in lift.items"
                            :key="item.id"
                            class="p-4 bg-gray-50 rounded-lg border border-gray-200"
                        >
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">
                                        {{ item.product_catalog?.name || '-' }}
                                    </p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-200 text-gray-700 text-xs font-medium mt-1">
                                        {{ item.variant }}
                                    </span>
                                </div>
                                <p class="font-bold text-green-600">
                                    ৳{{ toBn(Number(item.total_cost || 0).toFixed(2)) }}
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="flex flex-col">
                                    <span class="text-gray-500">{{ t("cases") }}</span>
                                    <span class="font-medium text-gray-800">{{ toBn(item.number_of_cases) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500">{{ t("pricePerCase") }}</span>
                                    <span class="font-medium text-gray-800">৳{{ toBn(Number(item.case_buying_price).toFixed(2)) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500">{{ t("totalBottles") }}</span>
                                    <span class="font-medium text-gray-800">{{ toBn(item.total_bottles) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500">{{ t("freeBottles") }}</span>
                                    <span class="font-medium text-gray-800">{{ toBn(item.total_free_bottles || 0) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500">{{ t("ratePerBottle") }}</span>
                                    <span class="font-medium text-gray-800">৳{{ toBn(Number(item.actual_rate_per_bottle || 0).toFixed(2)) }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-gray-500">{{ t("bottlesPerCase") }}</span>
                                    <span class="font-medium text-gray-800">{{ toBn(item.bottles_per_case) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import DateRangePicker from "../../Components/DateRangePicker.vue";

const props = defineProps({
    liftHistory: {
        type: Array,
        default: () => [],
    },
    initialStartDate: {
        type: String,
        default: "",
    },
    initialEndDate: {
        type: String,
        default: "",
    },
});

defineOptions({
    layout: Layout,
});

const translations = {
    en: {
        languageLabel: "English",
        liftReport: "Lifting Report",
        totalLifts: "Total Lifts",
        totalCases: "Total Cases",
        totalBottles: "Total Bottles",
        totalAmount: "Total Amount",
        totalItems: "Total Items",
        completedTab: "Completed",
        draftTab: "Drafts",
        editDraft: "Edit Draft",
        editLift: "Edit Lift",
        deleteLift: "Delete",
        continueDraft: "Continue Lifting",
        searchLifts: "Search by supplier or lift number...",
        noLifts: "No lifting records found",
        items: "items",
        cases: "cases",
        bottles: "bottles",
        notes: "Notes",
        product: "Product",
        variant: "Variant",
        pricePerCase: "Price/Case",
        bottlesPerCase: "Bottles/Case",
        freePerCase: "Free/Case",
        freeBottles: "Free Bottles",
        ratePerBottle: "Rate/Bottle",
        totalCost: "Total Cost",
        liftNumber: "Lift Number",
        supplier: "Supplier",
        liftDate: "Lift Date",
        reportPeriod: "Report Period",
        generatedOn: "Generated On",
        status: "Status",
        total: "Total",
        printPdf: "Print / PDF",
        liftingPerformanceSummary: "Lifting Performance Summary",
    },
    bn: {
        languageLabel: "বাংলা",
        liftReport: "লিফটিং রিপোর্ট",
        totalLifts: "মোট লিফট",
        totalCases: "মোট কেস",
        totalBottles: "মোট বোতল",
        totalAmount: "মোট পরিমাণ",
        totalItems: "মোট আইটেম",
        completedTab: "সম্পন্ন",
        draftTab: "ড্রাফট",
        editDraft: "ড্রাফট এডিট করুন",
        editLift: "লিফট এডিট করুন",
        deleteLift: "মুছুন",
        continueDraft: "ড্রাফট চালিয়ে যান",
        searchLifts: "সরবরাহকারী বা লিফট নম্বর দিয়ে খুঁজুন...",
        noLifts: "কোন লিফটিং রেকর্ড পাওয়া যায়নি",
        items: "আইটেম",
        cases: "কেস",
        bottles: "বোতল",
        notes: "নোট",
        product: "পণ্য",
        variant: "ভেরিয়েন্ট",
        pricePerCase: "মূল্য/কেস",
        bottlesPerCase: "বোতল/কেস",
        freePerCase: "বিনামূল্যে/কেস",
        freeBottles: "বিনামূল্যে বোতল",
        ratePerBottle: "দর/বোতল",
        totalCost: "মোট খরচ",
        liftNumber: "লিফট নম্বর",
        supplier: "সরবরাহকারী",
        liftDate: "লিফটের তারিখ",
        reportPeriod: "রিপোর্ট সময়কাল",
        generatedOn: "তৈরি হয়েছে",
        status: "স্ট্যাটাস",
        total: "মোট",
        printPdf: "প্রিন্ট / পিডিএফ",
        liftingPerformanceSummary: "লিফটিং পারফরম্যান্স সারাংশ",
    },
};

const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref("");
const expandedLifts = ref({});
const activeTab = ref("completed");

// Date range state (default: server-provided range or today)
const _today = new Date();
const _todayStr = `${_today.getFullYear()}-${String(_today.getMonth() + 1).padStart(2, "0")}-${String(_today.getDate()).padStart(2, "0")}`;
const dateStart = ref(props.initialStartDate || _todayStr);
const dateEnd = ref(props.initialEndDate || _todayStr);

function t(key) {
    return translations[currentLanguage.value]?.[key] || key;
}


function toBn(num) {
    if (num === null || num === undefined || num === "") return "";
    if (currentLanguage.value !== "bn") return num;
    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
}


function formatDate(dateString) {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function toggleLift(liftId) {
    expandedLifts.value[liftId] = !expandedLifts.value[liftId];
}

function getLiftTotalCases(lift) {
    if (!lift.items) return 0;
    return lift.items.reduce((sum, item) => sum + (item.number_of_cases || 0), 0);
}

function getLiftTotalBottles(lift) {
    if (!lift.items) return 0;
    return lift.items.reduce((sum, item) => sum + (item.total_bottles || 0), 0);
}

// Computed metrics
const tabbedLifts = computed(() =>
    props.liftHistory.filter((lift) =>
        activeTab.value === "draft" ? lift.status === "draft" : lift.status !== "draft"
    )
);


function editLift(lift) {
    router.visit(lift.status === "draft" ? `/lifts?draft=${lift.id}` : `/lifts?edit=${lift.id}`);
}

function deleteLift(id) {
    if (!confirm('Are you sure you want to delete this lift? This cannot be undone.')) return;
    router.delete(route('lifts.destroy', { id }), { preserveScroll: true });
}

function printReport() {
    window.print();
}

const filteredLifts = computed(() => {
    const query = searchQuery.value.toLowerCase();
    
    return tabbedLifts.value.filter((lift) => {
        // Date range filter (parse in local timezone to avoid UTC offset mismatch)
        if (dateStart.value || dateEnd.value) {
            if (!lift.lift_date) return false;
            const d = new Date(String(lift.lift_date).replace(' ', 'T'));
            const lDateStr = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            if (dateStart.value && lDateStr < dateStart.value) return false;
            if (dateEnd.value && lDateStr > dateEnd.value) return false;
        }

        // Search filter
        if (query) {
            return (
                (lift.supplier?.company_name || "").toLowerCase().includes(query) ||
                (lift.lift_number || "").toLowerCase().includes(query) ||
                (lift.notes || "").toLowerCase().includes(query)
            );
        }
        
        return true;
    });
});

const totalLifts = computed(() => filteredLifts.value.length);

const totalAmount = computed(() =>
    filteredLifts.value.reduce(
        (sum, lift) => sum + Number(lift.total_amount || 0),
        0
    )
);

const totalCases = computed(() =>
    filteredLifts.value.reduce(
        (sum, lift) => sum + getLiftTotalCases(lift),
        0
    )
);

const totalBottles = computed(() =>
    filteredLifts.value.reduce(
        (sum, lift) => sum + getLiftTotalBottles(lift),
        0
    )
);

const totalItems = computed(() =>
    filteredLifts.value.reduce(
        (sum, lift) => sum + (lift.items?.length || 0),
        0
    )
);

const printedAtLabel = computed(() => {
    const now = new Date();
    return now.toLocaleString(currentLanguage.value === "bn" ? "bn-BD" : "en-GB");
});
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
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

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 1000px;
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

.rotate-90 {
    transform: rotate(90deg);
}

.print-only {
    display: none;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 12mm;
    }

    .print\:hidden {
        display: none !important;
    }

    .print-only {
        display: block !important;
    }

    .lift-print-sheet {
        color: #0f172a;
    }

    .lift-print-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        border-bottom: 2px solid #cbd5e1;
        padding-bottom: 14px;
        margin-bottom: 16px;
    }

    .lift-print-kicker {
        margin: 0 0 4px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #475569;
    }

    .lift-print-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }

    .lift-print-subtitle,
    .lift-print-meta p {
        margin: 4px 0 0;
        font-size: 11px;
        color: #475569;
    }

    .lift-print-meta {
        text-align: right;
    }

    .lift-print-summary {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }

    .lift-print-card {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 12px;
        background: #f8fafc;
    }

    .lift-print-card span {
        display: block;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 6px;
    }

    .lift-print-card strong {
        display: block;
        font-size: 16px;
        line-height: 1.2;
        color: #0f172a;
    }

    .lift-print-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }

    .lift-print-table th,
    .lift-print-table td {
        border: 1px solid #cbd5e1;
        padding: 6px 8px;
        text-align: left;
        vertical-align: top;
    }

    .lift-print-table thead th {
        background: #e2e8f0;
        font-weight: 700;
    }

    .lift-print-table tfoot td {
        background: #f8fafc;
        font-weight: 700;
    }

    .bg-gradient-to-br,
    .max-w-7xl {
        background: white !important;
    }
}
</style>
