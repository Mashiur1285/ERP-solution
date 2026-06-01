<template>
    <div class="p-4 sm:p-6 space-y-6 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
        :class="{ 'bangla-font': lang === 'bn' }">

        <!-- ═══════════════════════ SCREEN UI ═══════════════════════ -->

        <!-- Header -->
        <div class="print:hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-200 pb-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-1">{{ t('financialReport') }}</p>
                <h1 class="text-2xl font-bold text-gray-900">{{ t('profitAndLoss') }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ selectedMonthLabel }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button @click="setLang('en')" :class="['px-3 py-1.5 rounded-md text-xs font-semibold transition-colors', lang === 'en' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">EN</button>
                <button @click="setLang('bn')" :class="['px-3 py-1.5 rounded-md text-xs font-semibold transition-colors', lang === 'bn' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">বাং</button>
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
        <div class="print:hidden grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">{{ t('netRevenue') }}</p>
                <p class="text-xl font-bold text-gray-900 mt-2">৳{{ fmt(report.net_revenue) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ t('afterDiscount') }} ৳{{ fmt(report.discount) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-orange-600">{{ t('cogs') }}</p>
                <p class="text-xl font-bold text-gray-900 mt-2">৳{{ fmt(report.cogs) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ t('costOfGoods') }}</p>
            </div>
            <div :class="['rounded-xl border p-4 shadow-sm', report.gross_profit >= 0 ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200']">
                <p :class="['text-xs font-semibold uppercase tracking-wide', report.gross_profit >= 0 ? 'text-emerald-700' : 'text-red-700']">{{ t('grossProfit') }}</p>
                <p :class="['text-xl font-bold mt-2', report.gross_profit >= 0 ? 'text-emerald-800' : 'text-red-800']">৳{{ fmt(report.gross_profit) }}</p>
                <p :class="['text-xs mt-1', report.gross_profit >= 0 ? 'text-emerald-600' : 'text-red-600']">{{ t('margin') }}: {{ report.gross_margin }}%</p>
            </div>
            <div :class="['rounded-xl border p-4 shadow-sm', report.net_profit >= 0 ? 'bg-indigo-50 border-indigo-200' : 'bg-red-50 border-red-200']">
                <p :class="['text-xs font-semibold uppercase tracking-wide', report.net_profit >= 0 ? 'text-indigo-700' : 'text-red-700']">{{ t('netProfit') }}</p>
                <p :class="['text-2xl font-bold mt-2', report.net_profit >= 0 ? 'text-indigo-900' : 'text-red-900']">৳{{ fmt(report.net_profit) }}</p>
                <p :class="['text-xs mt-1', report.net_profit >= 0 ? 'text-indigo-600' : 'text-red-600']">{{ t('netMargin') }}: {{ report.net_margin }}%</p>
            </div>
        </div>

        <!-- Screen P&L Table -->
        <div class="print:hidden bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-800">{{ t('plStatement') }}</h2>
                <span class="text-xs text-gray-400">{{ selectedMonthLabel }}</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-indigo-600 text-white text-xs uppercase tracking-wider">
                        <th class="px-5 py-3 text-left font-semibold w-1/2">{{ t('particulars') }}</th>
                        <th class="px-5 py-3 text-right font-semibold">{{ t('amount') }} (BDT)</th>
                        <th class="px-5 py-3 text-right font-semibold">%</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Revenue -->
                    <tr class="bg-blue-50">
                        <td colspan="3" class="px-5 py-2 text-xs font-bold uppercase tracking-widest text-blue-700">{{ t('revenue') }}</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-5 py-2.5 text-gray-700 pl-10">{{ t('totalSales') }}</td>
                        <td class="px-5 py-2.5 text-right text-gray-900 font-medium">৳{{ fmt(report.revenue) }}</td>
                        <td class="px-5 py-2.5 text-right text-gray-400 text-xs">—</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-5 py-2.5 text-gray-500 pl-10">(−) {{ t('discount') }}</td>
                        <td class="px-5 py-2.5 text-right text-red-500 font-medium">(৳{{ fmt(report.discount) }})</td>
                        <td class="px-5 py-2.5 text-right text-gray-400 text-xs">—</td>
                    </tr>
                    <tr class="bg-blue-100 font-bold border-b-2 border-blue-200">
                        <td class="px-5 py-3 text-blue-900">{{ t('netRevenue') }}</td>
                        <td class="px-5 py-3 text-right text-blue-900">৳{{ fmt(report.net_revenue) }}</td>
                        <td class="px-5 py-3 text-right text-blue-600 text-xs">100%</td>
                    </tr>

                    <!-- COGS -->
                    <tr class="bg-orange-50">
                        <td colspan="3" class="px-5 py-2 text-xs font-bold uppercase tracking-widest text-orange-700">{{ t('cogs') }}</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-5 py-2.5 text-gray-500 pl-10">(−) {{ t('costOfGoods') }}</td>
                        <td class="px-5 py-2.5 text-right text-orange-600 font-medium">(৳{{ fmt(report.cogs) }})</td>
                        <td class="px-5 py-2.5 text-right text-gray-400 text-xs">{{ pct(report.cogs) }}%</td>
                    </tr>

                    <!-- Gross Profit -->
                    <tr :class="['font-bold border-b-2', report.gross_profit >= 0 ? 'bg-emerald-100 border-emerald-300' : 'bg-red-100 border-red-300']">
                        <td :class="['px-5 py-3', report.gross_profit >= 0 ? 'text-emerald-900' : 'text-red-900']">{{ t('grossProfit') }}</td>
                        <td :class="['px-5 py-3 text-right', report.gross_profit >= 0 ? 'text-emerald-900' : 'text-red-900']">৳{{ fmt(report.gross_profit) }}</td>
                        <td :class="['px-5 py-3 text-right text-xs', report.gross_profit >= 0 ? 'text-emerald-700' : 'text-red-700']">{{ report.gross_margin }}%</td>
                    </tr>

                    <!-- Expenses -->
                    <tr class="bg-rose-50">
                        <td colspan="3" class="px-5 py-2 text-xs font-bold uppercase tracking-widest text-rose-700">{{ t('operatingExpenses') }}</td>
                    </tr>
                    <tr v-for="exp in report.expense_breakdown" :key="exp.name" class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-5 py-2.5 text-gray-600 pl-10 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400 inline-block flex-shrink-0 mt-px"></span>
                            (−) {{ exp.name }}
                            <span class="text-xs text-gray-400 font-normal">({{ exp.count }} {{ t('entries') }})</span>
                        </td>
                        <td class="px-5 py-2.5 text-right text-rose-600 font-medium">(৳{{ fmt(exp.amount) }})</td>
                        <td class="px-5 py-2.5 text-right text-gray-400 text-xs">{{ pct(exp.amount) }}%</td>
                    </tr>
                    <tr v-if="!report.expense_breakdown.length" class="border-b border-gray-100">
                        <td colspan="3" class="px-5 py-3 text-gray-400 text-center text-xs">{{ t('noExpenses') }}</td>
                    </tr>
                    <tr class="bg-rose-100 font-semibold border-b-2 border-rose-300">
                        <td class="px-5 py-3 text-rose-800 pl-10">(−) {{ t('totalExpenses') }}</td>
                        <td class="px-5 py-3 text-right text-rose-800">(৳{{ fmt(report.total_expenses) }})</td>
                        <td class="px-5 py-3 text-right text-rose-600 text-xs">{{ pct(report.total_expenses) }}%</td>
                    </tr>

                    <!-- Net Profit -->
                    <tr :class="['font-bold text-base', report.net_profit >= 0 ? 'bg-indigo-600' : 'bg-red-600']">
                        <td :class="['px-5 py-4', report.net_profit >= 0 ? 'text-white' : 'text-white']">{{ t('netProfit') }}</td>
                        <td class="px-5 py-4 text-right text-white text-lg">৳{{ fmt(report.net_profit) }}</td>
                        <td class="px-5 py-4 text-right text-white/80 text-sm">{{ report.net_margin }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- ═══════════════════════ PRINT-ONLY LAYOUT ═══════════════════════ -->
        <div class="pl-report hidden print:block">

            <!-- Letterhead -->
            <div class="pl-letterhead">
                <div class="pl-company">
                    <p class="pl-company-name">ERP Solution</p>
                    <p class="pl-company-sub">Financial Statement</p>
                </div>
                <div class="pl-meta">
                    <p class="pl-meta-title">{{ t('profitAndLoss') }}</p>
                    <p class="pl-meta-period">{{ t('period') }}: {{ selectedMonthLabel }}</p>
                    <p class="pl-meta-generated">{{ t('generatedOn') }}: {{ printedAt }}</p>
                </div>
            </div>

            <!-- Summary Row -->
            <div class="pl-summary-strip">
                <div class="pl-sum-item">
                    <span class="pl-sum-label">{{ t('netRevenue') }}</span>
                    <span class="pl-sum-value">৳{{ fmt(report.net_revenue) }}</span>
                </div>
                <div class="pl-sum-divider"></div>
                <div class="pl-sum-item">
                    <span class="pl-sum-label">{{ t('grossProfit') }}</span>
                    <span class="pl-sum-value">৳{{ fmt(report.gross_profit) }} <em>({{ report.gross_margin }}%)</em></span>
                </div>
                <div class="pl-sum-divider"></div>
                <div class="pl-sum-item">
                    <span class="pl-sum-label">{{ t('totalExpenses') }}</span>
                    <span class="pl-sum-value">৳{{ fmt(report.total_expenses) }}</span>
                </div>
                <div class="pl-sum-divider"></div>
                <div :class="['pl-sum-item', report.net_profit >= 0 ? 'pl-sum-profit' : 'pl-sum-loss']">
                    <span class="pl-sum-label">{{ t('netProfit') }}</span>
                    <span class="pl-sum-value-lg">৳{{ fmt(report.net_profit) }} <em>({{ report.net_margin }}%)</em></span>
                </div>
            </div>

            <!-- Main P&L Table -->
            <table class="pl-table">
                <thead>
                    <tr>
                        <th class="pl-th pl-th-particulars">{{ t('particulars') }}</th>
                        <th class="pl-th pl-th-entries">{{ t('entries') }}</th>
                        <th class="pl-th pl-th-amount">{{ t('amount') }} (BDT)</th>
                        <th class="pl-th pl-th-pct">% of Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ── Revenue ── -->
                    <tr class="pl-section-header pl-section-revenue">
                        <td colspan="4">A. {{ t('revenue') }}</td>
                    </tr>
                    <tr class="pl-row">
                        <td class="pl-td-indent">{{ t('totalSales') }}</td>
                        <td class="pl-td-center">—</td>
                        <td class="pl-td-amount">৳{{ fmt(report.revenue) }}</td>
                        <td class="pl-td-pct">—</td>
                    </tr>
                    <tr class="pl-row">
                        <td class="pl-td-indent">(−) {{ t('discount') }}</td>
                        <td class="pl-td-center">—</td>
                        <td class="pl-td-amount pl-negative">(৳{{ fmt(report.discount) }})</td>
                        <td class="pl-td-pct">—</td>
                    </tr>
                    <tr class="pl-subtotal pl-subtotal-blue">
                        <td>{{ t('netRevenue') }}</td>
                        <td class="pl-td-center">—</td>
                        <td class="pl-td-amount">৳{{ fmt(report.net_revenue) }}</td>
                        <td class="pl-td-pct">100%</td>
                    </tr>

                    <!-- ── COGS ── -->
                    <tr class="pl-section-header pl-section-orange">
                        <td colspan="4">B. {{ t('cogs') }}</td>
                    </tr>
                    <tr class="pl-row">
                        <td class="pl-td-indent">(−) {{ t('costOfGoods') }}</td>
                        <td class="pl-td-center">—</td>
                        <td class="pl-td-amount pl-negative">(৳{{ fmt(report.cogs) }})</td>
                        <td class="pl-td-pct">{{ pct(report.cogs) }}%</td>
                    </tr>

                    <!-- ── Gross Profit ── -->
                    <tr :class="['pl-total', report.gross_profit >= 0 ? 'pl-total-green' : 'pl-total-red']">
                        <td>C. {{ t('grossProfit') }} &nbsp;<em style="font-weight:400;font-size:9pt">(A − B)</em></td>
                        <td class="pl-td-center">—</td>
                        <td class="pl-td-amount">৳{{ fmt(report.gross_profit) }}</td>
                        <td class="pl-td-pct">{{ report.gross_margin }}%</td>
                    </tr>

                    <!-- ── Operating Expenses ── -->
                    <tr class="pl-section-header pl-section-rose">
                        <td colspan="4">D. {{ t('operatingExpenses') }}</td>
                    </tr>
                    <tr v-for="exp in report.expense_breakdown" :key="exp.name" class="pl-row">
                        <td class="pl-td-indent">(−) {{ exp.name }}</td>
                        <td class="pl-td-center">{{ exp.count }}</td>
                        <td class="pl-td-amount pl-negative">(৳{{ fmt(exp.amount) }})</td>
                        <td class="pl-td-pct">{{ pct(exp.amount) }}%</td>
                    </tr>
                    <tr v-if="!report.expense_breakdown.length" class="pl-row">
                        <td colspan="4" class="pl-td-center" style="color:#94a3b8;font-style:italic;">{{ t('noExpenses') }}</td>
                    </tr>
                    <tr class="pl-subtotal pl-subtotal-rose">
                        <td>(−) {{ t('totalExpenses') }}</td>
                        <td class="pl-td-center">{{ report.expense_breakdown.reduce((s,e) => s + e.count, 0) }}</td>
                        <td class="pl-td-amount pl-negative">(৳{{ fmt(report.total_expenses) }})</td>
                        <td class="pl-td-pct">{{ pct(report.total_expenses) }}%</td>
                    </tr>

                    <!-- ── Net Profit ── -->
                    <tr :class="['pl-total pl-total-final', report.net_profit >= 0 ? 'pl-total-indigo' : 'pl-total-red']">
                        <td>E. {{ t('netProfit') }} &nbsp;<em style="font-weight:400;font-size:9pt">(C − D)</em></td>
                        <td class="pl-td-center">—</td>
                        <td class="pl-td-amount">৳{{ fmt(report.net_profit) }}</td>
                        <td class="pl-td-pct">{{ report.net_margin }}%</td>
                    </tr>
                </tbody>
            </table>

            <!-- Footer -->
            <div class="pl-footer">
                <p>This report was generated automatically by ERP Solution · {{ printedAt }}</p>
            </div>
        </div>

    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

defineOptions({ layout: Layout });

interface ExpenseBreakdownItem { name: string; amount: number; count: number; }
interface PLReport {
    revenue: number; discount: number; net_revenue: number;
    cogs: number; gross_profit: number; gross_margin: number;
    expense_breakdown: ExpenseBreakdownItem[];
    total_expenses: number; net_profit: number; net_margin: number;
}

const props = defineProps<{ report: PLReport; month: number; year: number; }>();

const lang = ref(localStorage.getItem("language") || "en");
const selectedMonth = ref(props.month - 1);
const selectedYear  = ref(props.year);

const translations = {
    en: {
        financialReport: "Financial Report", profitAndLoss: "Profit & Loss Statement",
        plStatement: "P&L Statement", revenue: "Revenue", totalSales: "Total Sales",
        discount: "Less: Discount", netRevenue: "Net Revenue", afterDiscount: "after discount",
        cogs: "Cost of Goods Sold (COGS)", costOfGoods: "Cost of goods sold",
        grossProfit: "Gross Profit", operatingExpenses: "Operating Expenses",
        totalExpenses: "Total Operating Expenses", netProfit: "Net Profit / (Loss)",
        margin: "Gross Margin", netMargin: "Net Margin", noExpenses: "No expenses recorded",
        particulars: "Particulars", amount: "Amount", entries: "Entries",
        period: "Period", generatedOn: "Generated On",
    },
    bn: {
        financialReport: "আর্থিক রিপোর্ট", profitAndLoss: "লাভ ও ক্ষতির বিবরণী",
        plStatement: "লাভ-ক্ষতির বিবরণী", revenue: "রাজস্ব", totalSales: "মোট বিক্রয়",
        discount: "বাদ: ছাড়", netRevenue: "নেট রাজস্ব", afterDiscount: "ছাড়ের পরে",
        cogs: "বিক্রীত পণ্যের খরচ (COGS)", costOfGoods: "বিক্রীত পণ্যের খরচ",
        grossProfit: "গ্রস লাভ", operatingExpenses: "পরিচালন ব্যয়",
        totalExpenses: "মোট পরিচালন ব্যয়", netProfit: "নেট লাভ / (ক্ষতি)",
        margin: "গ্রস মার্জিন", netMargin: "নেট মার্জিন", noExpenses: "কোনো ব্যয় নেই",
        particulars: "বিবরণ", amount: "পরিমাণ", entries: "এন্ট্রি",
        period: "সময়কাল", generatedOn: "তৈরির তারিখ",
    },
} as const;

const t = (key: keyof typeof translations.en) =>
    translations[lang.value as keyof typeof translations]?.[key] ?? key;

const setLang = (l: string) => { lang.value = l; localStorage.setItem("language", l); };

const fmt = (n: number | string) =>
    Number(n).toLocaleString("en-BD", { minimumFractionDigits: 2, maximumFractionDigits: 2 });

const pct = (n: number | string) => {
    const rev = props.report.net_revenue;
    if (!rev) return "0.00";
    return ((Number(n) / rev) * 100).toFixed(2);
};

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

const navigate = (month: number, year: number) =>
    router.visit(route("profit-loss.index", { month: month + 1, year }), { preserveScroll: true });

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

/* ═══════════════════════════════════════════════
   PRINT STYLES
═══════════════════════════════════════════════ */
@media print {
    @page { size: A4 portrait; margin: 14mm 16mm; }

    * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

    .print\:hidden  { display: none !important; }
    .print\:block   { display: block !important; }
    .hidden         { display: none; }

    /* ── Letterhead ── */
    .pl-letterhead {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 3px solid #1e1b4b;
        padding-bottom: 10px;
        margin-bottom: 14px;
    }
    .pl-company-name {
        font-size: 18pt;
        font-weight: 800;
        color: #1e1b4b;
        margin: 0;
    }
    .pl-company-sub {
        font-size: 8pt;
        color: #6b7280;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin: 2px 0 0;
    }
    .pl-meta { text-align: right; }
    .pl-meta-title {
        font-size: 13pt;
        font-weight: 700;
        color: #1e1b4b;
        margin: 0;
    }
    .pl-meta-period,
    .pl-meta-generated {
        font-size: 8pt;
        color: #6b7280;
        margin: 2px 0 0;
    }

    /* ── Summary strip ── */
    .pl-summary-strip {
        display: flex;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 16px;
        background: #f8fafc;
    }
    .pl-sum-item {
        flex: 1;
        padding: 8px 12px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .pl-sum-divider {
        width: 1px;
        background: #e2e8f0;
        flex-shrink: 0;
    }
    .pl-sum-label {
        font-size: 7pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #64748b;
    }
    .pl-sum-value {
        font-size: 9.5pt;
        font-weight: 700;
        color: #0f172a;
    }
    .pl-sum-value em { font-size: 7.5pt; font-weight: 400; font-style: normal; color: #64748b; }
    .pl-sum-value-lg {
        font-size: 11pt;
        font-weight: 800;
        color: #0f172a;
    }
    .pl-sum-value-lg em { font-size: 8pt; font-weight: 400; font-style: normal; }
    .pl-sum-profit { background: #f0fdf4; }
    .pl-sum-profit .pl-sum-value-lg { color: #166534; }
    .pl-sum-loss   { background: #fef2f2; }
    .pl-sum-loss   .pl-sum-value-lg { color: #991b1b; }

    /* ── Main Table ── */
    .pl-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5pt;
        margin-bottom: 14px;
    }
    .pl-th {
        background: #1e1b4b;
        color: #fff;
        font-size: 8pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 7px 10px;
        border: 1px solid #1e1b4b;
    }
    .pl-th-particulars { text-align: left; width: 48%; }
    .pl-th-entries     { text-align: center; width: 10%; }
    .pl-th-amount      { text-align: right;  width: 26%; }
    .pl-th-pct         { text-align: right;  width: 16%; }

    /* Section headers */
    .pl-section-header td {
        font-size: 8pt;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 5px 10px;
        border: 1px solid #e2e8f0;
    }
    .pl-section-revenue  td { background: #dbeafe; color: #1e40af; }
    .pl-section-orange   td { background: #ffedd5; color: #9a3412; }
    .pl-section-rose     td { background: #ffe4e6; color: #9f1239; }

    /* Normal rows */
    .pl-row td {
        padding: 5px 10px;
        border: 1px solid #e2e8f0;
        color: #374151;
        vertical-align: middle;
    }
    .pl-row:nth-child(even) td { background: #f9fafb; }

    /* Indented items */
    .pl-td-indent { padding-left: 22px !important; }

    /* Subtotals */
    .pl-subtotal td {
        padding: 5px 10px;
        font-weight: 700;
        border: 1px solid #d1d5db;
        vertical-align: middle;
    }
    .pl-subtotal-blue td { background: #bfdbfe; color: #1e3a8a; }
    .pl-subtotal-rose td { background: #fecdd3; color: #881337; }

    /* Grand totals */
    .pl-total td {
        padding: 7px 10px;
        font-weight: 800;
        font-size: 10pt;
        border: 2px solid;
        vertical-align: middle;
    }
    .pl-total-green td  { background: #bbf7d0; color: #14532d; border-color: #16a34a; }
    .pl-total-indigo td { background: #3730a3; color: #fff;     border-color: #1e1b4b; }
    .pl-total-red td    { background: #fca5a5; color: #7f1d1d; border-color: #dc2626; }
    .pl-total-final td  { font-size: 11pt; }

    /* Alignment helpers */
    .pl-td-center { text-align: center; color: #9ca3af; }
    .pl-td-amount { text-align: right; font-weight: 600; font-variant-numeric: tabular-nums; }
    .pl-td-pct    { text-align: right; color: #6b7280; font-size: 8.5pt; }
    .pl-negative  { color: #b91c1c; }

    /* Footer */
    .pl-footer {
        margin-top: 14px;
        border-top: 1px solid #e2e8f0;
        padding-top: 6px;
        text-align: center;
        font-size: 7pt;
        color: #9ca3af;
    }
}
</style>
