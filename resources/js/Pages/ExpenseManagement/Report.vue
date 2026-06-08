<template>
    <div class="p-4 sm:p-6 space-y-6 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
        :class="{ 'bangla-font': lang === 'bn' }">

        <!-- ═══════ SCREEN UI ═══════ -->

        <!-- Header -->
        <div class="print:hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-200 pb-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-1">Financial Report</p>
                <h1 class="text-2xl font-bold text-gray-900">{{ t('title') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ selectedMonthLabel }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button @click="setLang('en')" :class="['px-3 py-1.5 rounded-md text-xs font-semibold transition-colors', lang === 'en' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">EN</button>
                <button @click="setLang('bn')" :class="['px-3 py-1.5 rounded-md text-xs font-semibold transition-colors', lang === 'bn' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">বাং</button>
                <button @click="printPage" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-1m-10 0h10v4H10v-4z" />
                    </svg>
                    Print / PDF
                </button>
            </div>
        </div>

        <!-- Month Navigation -->
        <div class="print:hidden flex items-center justify-between bg-white rounded-xl border border-gray-200 p-3 shadow-sm">
            <button @click="prevMonth" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <span class="text-sm font-semibold text-gray-800">{{ selectedMonthLabel }}</span>
            <button @click="nextMonth" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="print:hidden grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">{{ t('totalAmount') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">৳{{ fmt(report.total) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ selectedMonthLabel }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">{{ t('totalEntries') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ report.detailed.length }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ t('expenseRecords') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm col-span-2 md:col-span-1">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">{{ t('largestCategory') }}</p>
                <p class="text-xl font-bold text-gray-900 mt-2 truncate">{{ largestCategory }}</p>
                <p class="text-xs text-gray-400 mt-1">৳{{ fmt(largestAmount) }}</p>
            </div>
        </div>

        <!-- Screen Table -->
        <div class="print:hidden bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-800">{{ t('breakdownByCategory') }}</h2>
                <span class="text-xs text-gray-400">{{ selectedMonthLabel }}</span>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-rose-600 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-3 text-left font-semibold">#</th>
                        <th class="px-5 py-3 text-left font-semibold">{{ t('category') }}</th>
                        <th class="px-5 py-3 text-center font-semibold">{{ t('entries') }}</th>
                        <th class="px-5 py-3 text-right font-semibold w-44">{{ t('amount') }}</th>
                        <th class="px-5 py-3 text-right font-semibold w-20">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="(amount, cat, idx) in report.summary" :key="cat" class="hover:bg-rose-50/30 transition-colors">
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ idx + 1 }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-400 flex-shrink-0"></span>
                                <span class="font-medium text-gray-800">{{ cat }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center text-gray-500">
                            {{ countByCategory(cat) }}
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-rose-700">৳{{ fmt(amount) }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-16 bg-gray-100 rounded-full h-1.5 hidden sm:block">
                                    <div class="bg-rose-400 h-1.5 rounded-full" :style="{ width: pct(amount) + '%' }"></div>
                                </div>
                                <span class="text-xs text-gray-400 w-8 text-right">{{ pct(amount) }}%</span>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!Object.keys(report.summary).length">
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">{{ t('noExpenses') }}</td>
                    </tr>
                </tbody>
                <tfoot class="bg-rose-50 border-t-2 border-rose-200">
                    <tr>
                        <td colspan="3" class="px-5 py-3 font-bold text-rose-800">{{ t('total') }}</td>
                        <td class="px-5 py-3 text-right font-bold text-rose-800">৳{{ fmt(report.total) }}</td>
                        <td class="px-5 py-3 text-right text-xs text-rose-600">100%</td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="print:hidden bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-base font-bold text-gray-800">{{ t('allEntries') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-gray-700 to-gray-600 text-white text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">{{ t('reason') }}</th>
                            <th class="px-4 py-3 text-left">{{ t('category') }}</th>
                            <th class="px-4 py-3 text-left hidden sm:table-cell">{{ t('description') }}</th>
                            <th class="px-4 py-3 text-left">{{ t('date') }}</th>
                            <th class="px-4 py-3 text-right">{{ t('amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="(expense, idx) in report.detailed" :key="expense.id" class="hover:bg-gray-50">
                            <td class="px-4 py-2.5 text-gray-400 text-xs">{{ idx + 1 }}</td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">{{ expense.reason }}</td>
                            <td class="px-4 py-2.5">
                                <span v-if="expense.category" class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">{{ expense.category }}</span>
                                <span v-else class="text-gray-400 text-xs">—</span>
                            </td>
                            <td class="px-4 py-2.5 text-gray-500 hidden sm:table-cell text-xs">{{ expense.description || '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-xs whitespace-nowrap">{{ formatDate(expense.created_at) }}</td>
                            <td class="px-4 py-2.5 text-right font-bold text-rose-600">৳{{ fmt(expense.amount) }}</td>
                        </tr>
                        <tr v-if="!report.detailed.length">
                            <td colspan="6" class="px-4 py-10 text-center text-gray-400">{{ t('noExpenses') }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-gray-700">{{ t('total') }}</td>
                            <td class="px-4 py-3 text-right text-rose-700">৳{{ fmt(report.total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <!-- ═══════ PRINT-ONLY LAYOUT ═══════ -->
        <div class="exp-report hidden print:block">

            <!-- Letterhead -->
            <div class="exp-letterhead">
                <div>
                    <p class="exp-company">ERP Solution</p>
                    <p class="exp-company-sub">Financial Statement</p>
                </div>
                <div class="exp-meta">
                    <p class="exp-meta-title">{{ t('title') }}</p>
                    <p class="exp-meta-sub">{{ t('period') }}: {{ selectedMonthLabel }}</p>
                    <p class="exp-meta-sub">{{ t('generatedOn') }}: {{ printedAt }}</p>
                </div>
            </div>

            <!-- Summary Strip -->
            <div class="exp-summary-strip">
                <div class="exp-sum-item">
                    <span class="exp-sum-label">{{ t('totalAmount') }}</span>
                    <span class="exp-sum-value">৳{{ fmt(report.total) }}</span>
                </div>
                <div class="exp-sum-divider"></div>
                <div class="exp-sum-item">
                    <span class="exp-sum-label">{{ t('totalEntries') }}</span>
                    <span class="exp-sum-value">{{ report.detailed.length }}</span>
                </div>
                <div class="exp-sum-divider"></div>
                <div class="exp-sum-item">
                    <span class="exp-sum-label">{{ t('categories') }}</span>
                    <span class="exp-sum-value">{{ Object.keys(report.summary).length }}</span>
                </div>
                <div class="exp-sum-divider"></div>
                <div class="exp-sum-item exp-sum-highlight">
                    <span class="exp-sum-label">{{ t('largestCategory') }}</span>
                    <span class="exp-sum-value">{{ largestCategory }} &nbsp;<em>৳{{ fmt(largestAmount) }}</em></span>
                </div>
            </div>

            <!-- Category Breakdown Table -->
            <p class="exp-section-label">A. {{ t('breakdownByCategory') }}</p>
            <table class="exp-table">
                <thead>
                    <tr>
                        <th class="exp-th" style="width:6%">#</th>
                        <th class="exp-th" style="width:42%;text-align:left">{{ t('category') }}</th>
                        <th class="exp-th" style="width:12%">{{ t('entries') }}</th>
                        <th class="exp-th" style="width:26%;text-align:right">{{ t('amount') }} (BDT)</th>
                        <th class="exp-th" style="width:14%;text-align:right">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(amount, cat, idx) in report.summary" :key="cat" class="exp-row">
                        <td class="exp-td exp-td-center">{{ idx + 1 }}</td>
                        <td class="exp-td exp-td-indent">{{ cat }}</td>
                        <td class="exp-td exp-td-center">{{ countByCategory(cat) }}</td>
                        <td class="exp-td exp-td-amount">৳{{ fmt(amount) }}</td>
                        <td class="exp-td exp-td-pct">{{ pct(amount) }}%</td>
                    </tr>
                    <tr v-if="!Object.keys(report.summary).length">
                        <td colspan="5" class="exp-td exp-td-center" style="color:#94a3b8;font-style:italic">{{ t('noExpenses') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="exp-total-row">
                        <td colspan="2" class="exp-td">{{ t('total') }}</td>
                        <td class="exp-td exp-td-center">{{ report.detailed.length }}</td>
                        <td class="exp-td exp-td-amount">৳{{ fmt(report.total) }}</td>
                        <td class="exp-td exp-td-pct">100%</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Detailed Entries Table -->
            <p class="exp-section-label" style="margin-top:14px">B. {{ t('allEntries') }}</p>
            <table class="exp-table">
                <thead>
                    <tr>
                        <th class="exp-th" style="width:6%">#</th>
                        <th class="exp-th" style="width:22%;text-align:left">{{ t('reason') }}</th>
                        <th class="exp-th" style="width:18%;text-align:left">{{ t('category') }}</th>
                        <th class="exp-th" style="width:26%;text-align:left">{{ t('description') }}</th>
                        <th class="exp-th" style="width:14%">{{ t('date') }}</th>
                        <th class="exp-th" style="width:14%;text-align:right">{{ t('amount') }} (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(expense, idx) in report.detailed" :key="expense.id" class="exp-row">
                        <td class="exp-td exp-td-center">{{ idx + 1 }}</td>
                        <td class="exp-td exp-td-indent">{{ expense.reason }}</td>
                        <td class="exp-td">{{ expense.category || '—' }}</td>
                        <td class="exp-td" style="color:#64748b;font-size:8pt">{{ expense.description || '—' }}</td>
                        <td class="exp-td exp-td-center">{{ formatDate(expense.created_at) }}</td>
                        <td class="exp-td exp-td-amount">৳{{ fmt(expense.amount) }}</td>
                    </tr>
                    <tr v-if="!report.detailed.length">
                        <td colspan="6" class="exp-td exp-td-center" style="color:#94a3b8;font-style:italic">{{ t('noExpenses') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="exp-total-row">
                        <td colspan="5" class="exp-td">{{ t('total') }}</td>
                        <td class="exp-td exp-td-amount">৳{{ fmt(report.total) }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Footer -->
            <div class="exp-footer">
                <p>ERP Solution · Expense Report · {{ selectedMonthLabel }} · {{ t('generatedOn') }}: {{ printedAt }}</p>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

defineOptions({ layout: Layout });

interface ExpenseEntry {
    id: number;
    reason: string;
    category: string | null;
    description: string | null;
    amount: number;
    created_at: string;
}

const props = defineProps<{
    report: { summary: Record<string, number>; total: number; detailed: ExpenseEntry[] };
    month: number;
    year: number;
}>();

const lang          = ref(localStorage.getItem("language") || "en");
const selectedMonth = ref(props.month - 1);
const selectedYear  = ref(props.year);

const translations = {
    en: {
        title: "Expense Report", period: "Period", generatedOn: "Generated On",
        totalAmount: "Total Expenses", totalEntries: "Total Entries",
        expenseRecords: "Expense records", largestCategory: "Largest Category",
        breakdownByCategory: "Breakdown by Category", allEntries: "All Entries",
        category: "Category", entries: "Entries", amount: "Amount",
        reason: "Reason", description: "Description", date: "Date",
        total: "Total", categories: "Categories", noExpenses: "No expenses this month",
    },
    bn: {
        title: "ব্যয় রিপোর্ট", period: "সময়কাল", generatedOn: "তৈরির তারিখ",
        totalAmount: "মোট ব্যয়", totalEntries: "মোট এন্ট্রি",
        expenseRecords: "ব্যয়ের রেকর্ড", largestCategory: "সর্বোচ্চ ক্যাটাগরি",
        breakdownByCategory: "ক্যাটাগরি অনুযায়ী বিভাজন", allEntries: "সব এন্ট্রি",
        category: "ক্যাটাগরি", entries: "এন্ট্রি", amount: "পরিমাণ",
        reason: "কারণ", description: "বিবরণ", date: "তারিখ",
        total: "মোট", categories: "ক্যাটাগরি", noExpenses: "এই মাসে কোনো ব্যয় নেই",
    },
} as const;

const t = (key: keyof typeof translations.en) =>
    translations[lang.value as keyof typeof translations]?.[key] ?? key;

const setLang = (l: string) => { lang.value = l; localStorage.setItem("language", l); };

const fmt = (n: number | string) =>
    Number(n).toLocaleString("en-BD", { minimumFractionDigits: 2, maximumFractionDigits: 2 });

const pct = (amount: number | string) => {
    if (!props.report.total) return "0";
    return ((Number(amount) / props.report.total) * 100).toFixed(1);
};

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString("en-GB") : "—";

const summaryEntries  = computed(() => Object.entries(props.report.summary));
const largestCategory = computed(() => summaryEntries.value.length ? summaryEntries.value[0][0] : "—");
const largestAmount   = computed(() => summaryEntries.value.length ? summaryEntries.value[0][1] : 0);

const countByCategory = (cat: string) =>
    props.report.detailed.filter(e => (e.category || e.reason) === cat).length;

const selectedMonthLabel = computed(() => {
    const d = new Date(selectedYear.value, selectedMonth.value);
    return d.toLocaleString(lang.value === "bn" ? "bn-BD" : "en-US", { month: "long", year: "numeric" });
});

const printedAt = computed(() =>
    new Date().toLocaleString(lang.value === "bn" ? "bn-BD" : "en-GB", {
        day: "2-digit", month: "short", year: "numeric", hour: "2-digit", minute: "2-digit",
    })
);

const printPage = () => window.print();

const navigate = (m: number, y: number) =>
    router.visit(route("expenses.report", { month: m + 1, year: y }), { preserveScroll: true });

const prevMonth = () => {
    let m = selectedMonth.value - 1, y = selectedYear.value;
    if (m < 0) { m = 11; y--; }
    selectedMonth.value = m; selectedYear.value = y; navigate(m, y);
};
const nextMonth = () => {
    let m = selectedMonth.value + 1, y = selectedYear.value;
    if (m > 11) { m = 0; y++; }
    selectedMonth.value = m; selectedYear.value = y; navigate(m, y);
};
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");
.bangla-font { font-family: "Kalpurush", "Noto Sans Bengali", sans-serif; }

@media print {
    @page { size: A4 portrait; margin: 14mm 16mm; }
    * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .print\:hidden { display: none !important; }
    .print\:block  { display: block !important; }
    .hidden        { display: none; }

    .exp-letterhead {
        display: flex; justify-content: space-between; align-items: flex-start;
        border-bottom: 3px solid #9f1239; padding-bottom: 10px; margin-bottom: 14px;
    }
    .exp-company     { font-size: 18pt; font-weight: 800; color: #9f1239; margin: 0; }
    .exp-company-sub { font-size: 8pt; color: #6b7280; text-transform: uppercase; letter-spacing: .1em; margin: 2px 0 0; }
    .exp-meta        { text-align: right; }
    .exp-meta-title  { font-size: 13pt; font-weight: 700; color: #9f1239; margin: 0; }
    .exp-meta-sub    { font-size: 8pt; color: #6b7280; margin: 2px 0 0; }

    .exp-summary-strip {
        display: flex; border: 1px solid #fecdd3; border-radius: 6px;
        overflow: hidden; margin-bottom: 14px; background: #fff1f2;
    }
    .exp-sum-item      { flex: 1; padding: 8px 12px; display: flex; flex-direction: column; gap: 2px; }
    .exp-sum-divider   { width: 1px; background: #fecdd3; flex-shrink: 0; }
    .exp-sum-label     { font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: #be123c; }
    .exp-sum-value     { font-size: 10pt; font-weight: 700; color: #0f172a; }
    .exp-sum-value em  { font-size: 8pt; font-weight: 400; font-style: normal; color: #64748b; }
    .exp-sum-highlight { background: #ffe4e6; }

    .exp-section-label {
        font-size: 8pt; font-weight: 800; text-transform: uppercase;
        letter-spacing: .08em; color: #9f1239; margin: 0 0 6px; padding: 4px 0;
        border-bottom: 1px solid #fecdd3;
    }

    .exp-table { width: 100%; border-collapse: collapse; font-size: 9pt; margin-bottom: 6px; }

    .exp-th {
        background: #9f1239; color: #fff; font-size: 7.5pt; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em; padding: 6px 8px;
        border: 1px solid #9f1239; text-align: center;
    }

    .exp-row td { padding: 4px 8px; border: 1px solid #e2e8f0; color: #374151; vertical-align: middle; }
    .exp-row:nth-child(even) td { background: #fff5f5; }

    .exp-total-row td {
        padding: 5px 8px; border: 1px solid #fca5a5;
        background: #fecdd3; color: #881337; font-weight: 700; font-size: 9.5pt;
    }

    .exp-td-center { text-align: center; }
    .exp-td-indent { padding-left: 18px !important; }
    .exp-td-amount { text-align: right; font-weight: 600; font-variant-numeric: tabular-nums; }
    .exp-td-pct    { text-align: right; color: #6b7280; font-size: 8pt; }

    .exp-footer {
        margin-top: 12px; border-top: 1px solid #e2e8f0;
        padding-top: 6px; text-align: center; font-size: 7pt; color: #9ca3af;
    }
}
</style>
