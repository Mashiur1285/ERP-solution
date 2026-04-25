```vue
<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Flash Message -->
        <div
            v-if="props.flash?.success"
            class="fixed top-6 right-6 px-7 py-5 rounded-lg shadow-lg flex items-center space-x-3 animate-toast-in z-50 bg-green-500 text-white"
            role="alert"
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
            <span class="font-medium">{{ props.flash.success }}</span>
        </div>

        <!-- Title and Language Toggle -->
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
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                        />
                    </svg>
                </div>
                {{ getTranslation("expenseManagement") }}
            </h1>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    @click="printReport"
                    class="print:hidden inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-1m-10 0h10v4H10v-4z" />
                    </svg>
                    <span>{{ getTranslation("printPdf") }}</span>
                </button>

                <!-- Language Toggle -->
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
        </div>

        <section class="print-only expense-print-sheet">
            <div class="expense-print-header">
                <div>
                    <p class="expense-print-kicker">{{ getTranslation("expenseManagement") }}</p>
                    <h2 class="expense-print-title">{{ getTranslation("expensePerformanceSummary") }}</h2>
                    <p class="expense-print-subtitle">
                        {{ getTranslation("reportPeriod") }}:
                        {{ formatDate(dateStart || "") }}
                        <span v-if="dateEnd">- {{ formatDate(dateEnd || "") }}</span>
                    </p>
                </div>
                <div class="expense-print-meta">
                    <p>{{ getTranslation("generatedOn") }}: {{ printedAtLabel }}</p>
                    <p>{{ getTranslation("totalReasons") }}: {{ toBengaliNumber(groupedExpenses.length) }}</p>
                </div>
            </div>

            <div class="expense-print-summary">
                <div class="expense-print-card">
                    <span>{{ getTranslation("totalExpenses") }}</span>
                    <strong>{{ toBengaliNumber(totalExpenses) }}</strong>
                </div>
                <div class="expense-print-card">
                    <span>{{ getTranslation("totalAmount") }}</span>
                    <strong>{{ toBengaliNumber(totalAmount, 2) }} {{ getTranslation("currency") }}</strong>
                </div>
                <div class="expense-print-card">
                    <span>{{ getTranslation("recentExpenses") }}</span>
                    <strong>{{ toBengaliNumber(recentExpenses) }}</strong>
                </div>
                <div class="expense-print-card">
                    <span>{{ getTranslation("totalReasons") }}</span>
                    <strong>{{ toBengaliNumber(groupedExpenses.length) }}</strong>
                </div>
            </div>

            <table class="expense-print-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ getTranslation("reason") }}</th>
                        <th>{{ getTranslation("entries") }}</th>
                        <th>{{ getTranslation("description") }}</th>
                        <th>{{ getTranslation("createdAt") }}</th>
                        <th>{{ getTranslation("totalAmount") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(group, index) in groupedExpenses" :key="`print-group-${group.key}`">
                        <td>{{ toBengaliNumber(index + 1) }}</td>
                        <td>{{ group.reason }}</td>
                        <td>{{ toBengaliNumber(group.items.length) }}</td>
                        <td>{{ getPrintDescription(group) }}</td>
                        <td>{{ getPrintCreatedAt(group) }}</td>
                        <td>{{ toBengaliNumber(group.totalAmount, 2) }} {{ getTranslation("currency") }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">{{ getTranslation("total") }}</td>
                        <td>{{ toBengaliNumber(totalExpenses) }}</td>
                        <td>{{ getTranslation("allReasons") }}</td>
                        <td>-</td>
                        <td>{{ toBengaliNumber(totalAmount, 2) }} {{ getTranslation("currency") }}</td>
                    </tr>
                </tfoot>
            </table>
        </section>

        <!-- Search, Filter & Add Button -->
        <div class="print:hidden flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <!-- Date Range Picker -->
            <DateRangePicker
                v-model:startDate="dateStart"
                v-model:endDate="dateEnd"
                :language="currentLanguage"
                class="w-full sm:w-auto"
            />
            
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                <!-- Search Field -->
                <div class="relative w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        :placeholder="getTranslation('searchExpenses')"
                        class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                    />
                </div>

                <!-- Add Expense Button -->
                <button
                    @click="showExpenseModal = true"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition duration-200 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl w-full sm:w-auto whitespace-nowrap"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>{{ getTranslation("addExpense") }}</span>
                </button>
            </div>
        </div>

        <!-- Summary Metrics -->
        <div class="print:hidden grid grid-cols-3 gap-2 mb-6">
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-2.5 rounded-xl shadow-sm border border-indigo-200 text-center">
                <div class="p-1.5 bg-indigo-500 rounded-lg w-fit mx-auto mb-1">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p class="text-[10px] font-medium text-indigo-700 leading-tight">{{ getTranslation("totalExpenses") }}</p>
                <p class="text-sm font-bold text-indigo-900">{{ toBengaliNumber(totalExpenses) }}</p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 p-2.5 rounded-xl shadow-sm border border-green-200 text-center">
                <div class="p-1.5 bg-green-500 rounded-lg w-fit mx-auto mb-1">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-[10px] font-medium text-green-700 leading-tight">{{ getTranslation("totalAmount") }}</p>
                <p class="text-sm font-bold text-green-900">{{ toBengaliNumber(totalAmount) }}</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-2.5 rounded-xl shadow-sm border border-blue-200 text-center">
                <div class="p-1.5 bg-blue-500 rounded-lg w-fit mx-auto mb-1">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h8a2 2 0 012 2v4m-4 8l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                </div>
                <p class="text-[10px] font-medium text-blue-700 leading-tight">{{ getTranslation("recentExpenses") }}</p>
                <p class="text-sm font-bold text-blue-900">{{ toBengaliNumber(recentExpenses) }}</p>
            </div>
        </div>

        <!-- Expense Modal -->
        <ExpenseModal
            v-if="showExpenseModal"
            :expense="currentExpense ?? undefined"
            :editMode="editMode"
            :existing-reasons="uniqueReasons"
            @close="closeExpenseModal"
            @submit="submitExpense"
        />

        <!-- Expenses Table -->
        <div class="print:hidden bg-white rounded-xl shadow-sm border border-indigo-100 overflow-hidden">
            <div class="w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-indigo-600 to-indigo-500">
                        <tr>
                            <th
                                class="px-2 lg:px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider w-1/4"
                            >
                                {{ getTranslation("reason") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider w-2/4 hidden sm:table-cell"
                            >
                                {{ getTranslation("description") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider w-1/4"
                            >
                                {{ getTranslation("totalAmount") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider w-1/4"
                            >
                                {{ getTranslation("actions") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template
                            v-for="group in groupedExpenses"
                            :key="group.reason"
                        >
                            <tr
                                class="hover:bg-gray-50 transition-colors cursor-pointer"
                                @click="toggleGroup(group.key)"
                            >
                                <!-- Reason -->
                                <td class="px-2 lg:px-3 py-3 text-xs lg:text-sm font-medium text-gray-900">
                                    <div class="flex items-center gap-1.5">
                                        <svg
                                            :class="['w-3 h-3 transition-transform flex-shrink-0', expandedGroups[group.key] ? 'rotate-90' : '']"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <div class="p-1 bg-indigo-100 rounded-lg flex-shrink-0 hidden sm:block">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate" :title="group.reason">
                                                {{ group.reason }}
                                            </p>
                                            <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                                <span v-if="group.items[0]?.category" class="inline-block px-1.5 py-0.5 rounded text-[9px] font-semibold bg-indigo-100 text-indigo-700">{{ group.items[0].category }}</span>
                                                <span class="text-[10px] text-gray-400">{{ group.items.length }} {{ getTranslation('entries') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Description (hidden on mobile) -->
                                <td class="px-2 lg:px-3 py-3 text-xs text-gray-400 hidden sm:table-cell">
                                    <div class="truncate italic">
                                        {{ group.items.length > 1 ? group.items.map(i => i.description).filter(Boolean).join(', ') : group.items[0].description || "-" }}
                                    </div>
                                </td>
                                <!-- Amount -->
                                <td class="px-2 lg:px-3 py-3 text-xs lg:text-sm font-bold text-indigo-600 whitespace-nowrap">
                                    {{ toBengaliNumber(group.totalAmount, 2) }} {{ getTranslation("currency") }}
                                </td>
                                <!-- Actions -->
                                <td class="px-2 lg:px-3 py-3 text-center whitespace-nowrap">
                                    <span class="text-[10px] lg:text-xs font-medium text-indigo-500 uppercase tracking-wider">
                                        {{ expandedGroups[group.reason] ? getTranslation('collapse') : getTranslation('expand') }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Expanded group details -->
                            <tr
                                v-if="expandedGroups[group.reason]"
                                class="bg-gray-50 border-t border-b border-gray-100"
                            >
                                <td :colspan="4" class="px-2 py-3">
                                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-x-auto">
                                        <table class="w-full min-w-[360px] text-xs">
                                            <thead class="bg-indigo-50 text-indigo-700">
                                                <tr>
                                                    <th class="px-3 py-2 font-semibold text-left whitespace-nowrap">{{ getTranslation('createdAt') }}</th>
                                                    <th class="px-3 py-2 font-semibold text-left">{{ getTranslation('description') }}</th>
                                                    <th class="px-3 py-2 font-semibold text-right whitespace-nowrap">{{ getTranslation('amount') }}</th>
                                                    <th class="px-3 py-2 font-semibold text-center w-10">{{ getTranslation('actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                                <tr v-for="item in group.items" :key="item.id" class="hover:bg-indigo-50/30">
                                                    <td class="px-3 py-2 whitespace-nowrap">{{ new Date(item.created_at).toLocaleDateString() }}</td>
                                                    <td class="px-3 py-2">{{ item.description || '-' }}</td>
                                                    <td class="px-3 py-2 font-semibold text-right whitespace-nowrap text-indigo-600">{{ toBengaliNumber(item.amount, 2) }} {{ getTranslation('currency') }}</td>
                                                    <td class="px-3 py-2 text-center">
                                                        <div class="flex items-center justify-center gap-1">
                                                            <button
                                                                @click.stop="editExpense(item)"
                                                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-full transition-all"
                                                                :title="getTranslation('edit')"
                                                            >
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                                </svg>
                                                            </button>
                                                            <button
                                                                @click.stop="deleteExpense(item.id)"
                                                                class="p-1.5 text-red-500 hover:bg-red-50 rounded-full transition-all"
                                                                title="Delete"
                                                            >
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import ExpenseModal from "./Partials/ExpenseModal.vue";
import DateRangePicker from "../../Components/DateRangePicker.vue";

interface Expense {
    id: number;
    reason: string;
    category: string | null;
    description: string | null;
    amount: number;
    created_at: string;
}

const props = defineProps<{
    expenses: Expense[];
    flash?: { success?: string; error?: string };
}>();

defineOptions({
    layout: Layout,
});

const translations = {
    en: {
        languageLabel: "English",
        expenseManagement: "Expense Management",
        addExpense: "Add Expense",
        searchExpenses: "Search expenses...",
        totalExpenses: "Total Expenses",
        totalAmount: "Total Amount",
        recentExpenses: "Recent Expenses",
        currency: "BDT",
        reason: "Reason",
        description: "Description",
        amount: "Amount",
        createdAt: "Created At",
        actions: "Actions",
        edit: "Edit",
        expenseDetails: "Expense Details",
        noDescription: "No description provided",
        metadata: "Metadata",
        expenseId: "Expense ID",
        entries: "entries",
        expand: "Expand",
        collapse: "Collapse",
        printPdf: "Print / PDF",
        expensePerformanceSummary: "Expense Performance Summary",
        reportPeriod: "Report Period",
        generatedOn: "Generated On",
        totalReasons: "Total Reasons",
        total: "Total",
        allReasons: "All Reasons",
    },
    bn: {
        languageLabel: "বাংলা",
        expenseManagement: "ব্যয় ব্যবস্থাপনা",
        addExpense: "ব্যয় যোগ করুন",
        searchExpenses: "ব্যয় অনুসন্ধান করুন...",
        totalExpenses: "মোট ব্যয়",
        totalAmount: "মোট পরিমাণ",
        recentExpenses: "সাম্প্রতিক ব্যয়",
        currency: "টাকা",
        reason: "কারণ",
        description: "বিবরণ",
        amount: "পরিমাণ",
        createdAt: "তৈরি হয়েছে",
        actions: "কার্যক্রম",
        edit: "সম্পাদনা",
        expenseDetails: "ব্যয়ের বিবরণ",
        noDescription: "কোনো বিবরণ প্রদান করা হয়নি",
        metadata: "মেটাডেটা",
        expenseId: "ব্যয় আইডি",
        entries: "টি এন্ট্রি",
        expand: "বিস্তারিত",
        collapse: "সংক্ষিপ্ত করুন",
        printPdf: "প্রিন্ট / পিডিএফ",
        expensePerformanceSummary: "ব্যয় পারফরম্যান্স সারাংশ",
        reportPeriod: "রিপোর্ট সময়কাল",
        generatedOn: "তৈরি হয়েছে",
        totalReasons: "মোট কারণ",
        total: "মোট",
        allReasons: "সব কারণ",
    },
} as const;

const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref("");
const showExpenseModal = ref(false);
const editMode = ref(false);
const currentExpense = ref<Expense | null>(null);
const expandedGroups = ref<Record<string, boolean>>({});

// Date range state (default: today)
const _todayExp = new Date();
const _todayStrExp = `${_todayExp.getFullYear()}-${String(_todayExp.getMonth() + 1).padStart(2, "0")}-${String(_todayExp.getDate()).padStart(2, "0")}`;
const dateStart = ref(_todayStrExp);
const dateEnd = ref(_todayStrExp);

const form = useForm({
    reason: "" as string,
    category: null as string | null,
    description: null as string | null,
    amount: null as number | null,
});

// Filter expenses based on search query + date range
const filteredExpenses = computed(() => {
    const query = searchQuery.value.toLowerCase();

    return props.expenses.filter((expense) => {
        // Date range filter by created_at (parse in local timezone to avoid UTC offset mismatch)
        if (dateStart.value || dateEnd.value) {
            if (!expense.created_at) return false;
            const d = new Date(String(expense.created_at).replace(' ', 'T'));
            const eDateStr = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            if (dateStart.value && eDateStr < dateStart.value) return false;
            if (dateEnd.value && eDateStr > dateEnd.value) return false;
        }

        // Search filter
        if (query) {
            return (
                expense.reason.toLowerCase().includes(query) ||
                (expense.description &&
                    expense.description.toLowerCase().includes(query))
            );
        }
        return true;
    });
});

// Summary statistics
const totalExpenses = computed(() => filteredExpenses.value.length);
const totalAmount = computed(() =>
    filteredExpenses.value.reduce((sum, expense) => sum + parseFloat(expense.amount as any), 0)
);
const recentExpenses = computed(() => {
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
    return filteredExpenses.value.filter(
        (expense) => new Date(expense.created_at) > sevenDaysAgo
    ).length;
});

const toggleGroup = (reason: string) => {
    expandedGroups.value[reason] = !expandedGroups.value[reason];
};

// Group expenses by reason (case-insensitive)
const groupedExpenses = computed(() => {
    const groups: Record<string, {
        key: string;
        reason: string;
        totalAmount: number;
        items: Expense[];
    }> = {};

    filteredExpenses.value.forEach(expense => {
        const key = expense.reason.toLowerCase();
        if (!groups[key]) {
            groups[key] = {
                key,
                reason: expense.reason, // first-seen casing used for display
                totalAmount: 0,
                items: []
            };
        }
        groups[key].totalAmount += Number(expense.amount);
        groups[key].items.push(expense);
    });

    // Sort by reason name for consistency
    return Object.values(groups).sort((a, b) => a.reason.localeCompare(b.reason));
});

// Keep reason suggestions independent from the active date/search filters.
const uniqueReasons = computed(() => {
    const reasonsMap = new Map<string, string>();

    props.expenses.forEach((expense) => {
        const reason = expense.reason?.trim();

        if (!reason) return;

        const key = reason.toLowerCase();

        if (!reasonsMap.has(key)) {
            reasonsMap.set(key, reason);
        }
    });

    return Array.from(reasonsMap.values()).sort((a, b) =>
        a.localeCompare(b)
    );
});

type TranslationKey = keyof typeof translations.en;
type TranslationLang = keyof typeof translations;

const getTranslation = (key: TranslationKey) =>
    translations[currentLanguage.value as TranslationLang]?.[key] ?? key;

const getTranslationLabel = (key: TranslationKey, lang: TranslationLang) =>
    translations[lang]?.[key] ?? key;

const formatDate = (dateString: string) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

const toBengaliNumber = (numValue: number | string, decimals: number | null = null): string => {
    if (numValue === null || numValue === undefined || numValue === "") return "";
    
    let n = Number(numValue);
    if (isNaN(n)) return String(numValue);

    let output: string;
    if (decimals !== null) {
        output = n.toFixed(decimals);
    } else {
        output = n % 1 !== 0 ? n.toFixed(2) : n.toString();
    }

    if (currentLanguage.value !== 'bn') return output;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return output.replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

const changeLanguage = (lang: string) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

const getPrintDescription = (group: { items: Expense[] }) => {
    const descriptions = group.items
        .map((item) => item.description?.trim())
        .filter(Boolean);

    return descriptions.length ? descriptions.join(", ") : "-";
};

const getPrintCreatedAt = (group: { items: Expense[] }) => {
    if (!group.items.length) return "-";

    const sortedDates = group.items
        .map((item) => item.created_at)
        .filter(Boolean)
        .sort();

    if (!sortedDates.length) return "-";

    const first = formatDate(sortedDates[0]);
    const last = formatDate(sortedDates[sortedDates.length - 1]);

    return first === last ? first : `${first} - ${last}`;
};

const printReport = () => {
    window.print();
};

const printedAtLabel = computed(() => {
    const now = new Date();
    return now.toLocaleString(currentLanguage.value === "bn" ? "bn-BD" : "en-GB");
});

const closeExpenseModal = () => {
    showExpenseModal.value = false;
    editMode.value = false;
    currentExpense.value = null;
    form.reset();
};

const submitExpense = (expenseData: {
    reason: string;
    category: string | null;
    description: string | null;
    amount: number | null;
}) => {
    console.log("Submitting expense:", expenseData);
    form.reason = expenseData.reason;
    form.category = expenseData.category;
    form.description = expenseData.description;
    form.amount = expenseData.amount;

    const url = editMode.value
        ? route("expenses.update", { id: currentExpense.value?.id })
        : route("expenses.store");

    if (editMode.value) {
        form.put(url, {
            preserveState: false,
            onSuccess: () => {
                closeExpenseModal();
                console.log("Expense updated successfully");
            },
            onError: (errors) => {
                console.error("Expense update errors:", errors);
                alert("Failed to update expense: " + JSON.stringify(errors));
            },
        });
    } else {
        form.post(url, {
            preserveState: false,
            onSuccess: () => {
                closeExpenseModal();
                console.log("Expense created successfully");
            },
            onError: (errors) => {
                console.error("Expense creation errors:", errors);
                alert("Failed to create expense: " + JSON.stringify(errors));
            },
        });
    }
};

const deleteExpense = (id: number) => {
    if (!confirm('Are you sure you want to delete this expense?')) return;
    form.delete(route('expenses.destroy', { id }), {
        preserveScroll: true,
        preserveState: false,
    });
};

const editExpense = (expense: Expense) => {
    currentExpense.value = { ...expense };
    editMode.value = true;
    showExpenseModal.value = true;
    form.reason = expense.reason;
    form.category = expense.category;
    form.description = expense.description;
    form.amount = expense.amount;
};

console.log("Expense.vue component loaded");
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
        max-height: 500px;
    }
}

@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
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

    .expense-print-sheet {
        color: #0f172a;
    }

    .expense-print-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        border-bottom: 2px solid #cbd5e1;
        padding-bottom: 14px;
        margin-bottom: 16px;
    }

    .expense-print-kicker {
        margin: 0 0 4px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #475569;
    }

    .expense-print-title {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }

    .expense-print-subtitle,
    .expense-print-meta p {
        margin: 4px 0 0;
        font-size: 11px;
        color: #475569;
    }

    .expense-print-meta {
        text-align: right;
    }

    .expense-print-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }

    .expense-print-card {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 10px 12px;
        background: #f8fafc;
    }

    .expense-print-card span {
        display: block;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 6px;
    }

    .expense-print-card strong {
        display: block;
        font-size: 16px;
        line-height: 1.2;
        color: #0f172a;
    }

    .expense-print-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }

    .expense-print-table th,
    .expense-print-table td {
        border: 1px solid #cbd5e1;
        padding: 6px 8px;
        text-align: left;
        vertical-align: top;
    }

    .expense-print-table thead th {
        background: #e2e8f0;
        font-weight: 700;
    }

    .expense-print-table tfoot td {
        background: #f8fafc;
        font-weight: 700;
    }

    .min-h-screen,
    .bg-gradient-to-br {
        background: white !important;
        min-height: auto !important;
    }
}

/* Custom responsive utilities */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Ensure table doesn't overflow */
table {
    table-layout: fixed;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .table-responsive {
        font-size: 0.75rem;
    }

    th,
    td {
        padding: 0.25rem 0.5rem;
    }
}

/* Enhanced focus states */
input:focus,
select:focus {
    outline: none;
    transform: translateY(-1px);
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Enhanced shadow effects */
.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(79, 70, 229, 0.1);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color,
        text-decoration-color, fill, stroke, opacity, box-shadow, transform,
        filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
```
