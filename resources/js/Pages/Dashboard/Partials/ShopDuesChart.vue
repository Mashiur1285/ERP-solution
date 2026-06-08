<template>
    <div class="bg-white rounded-sm border border-gray-200 shadow-sm overflow-hidden h-full flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 bg-slate-800">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-white/15 rounded-lg">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 14l3-3 3 3 5-5" />
                    </svg>
                </div>
                <h3 class="ts-title font-semibold text-white uppercase tracking-wide">{{ t("salesTrend") }}</h3>
            </div>
            <div class="flex items-center gap-4 ts-label">
                <span class="flex items-center gap-1.5 text-emerald-50">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    {{ t("paid") }}
                </span>
                <span class="flex items-center gap-1.5 text-rose-50">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                    {{ t("due") }}
                </span>
            </div>
        </div>
        <!-- Chart -->
        <div class="p-5 flex-1 min-h-0">
            <div class="relative h-72">
                <canvas ref="salesChart"></canvas>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import Chart from "chart.js/auto";

const props = defineProps({
    dateWiseSalesData: Array,
    t: Function,
    toBengaliNumber: Function,
});

const salesChart = ref(null);
let chartInstance = null;

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", { month: "short", day: "numeric" });
};

// Builds a soft vertical gradient for the area fill under a line.
const buildGradient = (ctx, area, rgb) => {
    const gradient = ctx.createLinearGradient(0, area.top, 0, area.bottom);
    gradient.addColorStop(0, `rgba(${rgb}, 0.28)`);
    gradient.addColorStop(1, `rgba(${rgb}, 0.01)`);
    return gradient;
};

const initializeChart = () => {
    if (!salesChart.value || !props.dateWiseSalesData || !props.dateWiseSalesData.length) {
        return;
    }

    if (chartInstance) {
        chartInstance.destroy();
    }

    const isBangla = props.t("languageLabel") === "বাংলা";
    const fontFamily = isBangla
        ? "'Kalpurush', 'Noto Sans Bengali', sans-serif"
        : "'Inter', Arial, sans-serif";

    const labels = props.dateWiseSalesData.map((data) => formatDate(data.sale_date));
    const paidData = props.dateWiseSalesData.map((data) => parseFloat(data.total_paid) || 0);
    const dueData = props.dateWiseSalesData.map((data) => parseFloat(data.total_due) || 0);

    const lineDataset = (label, data, rgb) => ({
        label,
        data,
        borderColor: `rgb(${rgb})`,
        backgroundColor: (context) => {
            const { chart } = context;
            const { ctx, chartArea } = chart;
            if (!chartArea) return `rgba(${rgb}, 0.1)`;
            return buildGradient(ctx, chartArea, rgb);
        },
        fill: true,
        tension: 0.4,
        borderWidth: 2.5,
        pointRadius: 0,
        pointHoverRadius: 6,
        pointBackgroundColor: "#ffffff",
        pointBorderColor: `rgb(${rgb})`,
        pointBorderWidth: 2.5,
        pointHoverBackgroundColor: "#ffffff",
        pointHoverBorderWidth: 3,
    });

    chartInstance = new Chart(salesChart.value, {
        type: "line",
        data: {
            labels,
            datasets: [
                lineDataset(props.t("paid"), paidData, "16, 185, 129"), // emerald-500
                lineDataset(props.t("due"), dueData, "244, 63, 94"), // rose-500
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: "index", intersect: false },
            layout: { padding: { top: 8, right: 8 } },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    grid: { color: "rgba(148, 163, 184, 0.15)", drawTicks: false },
                    ticks: {
                        padding: 10,
                        color: "#64748b",
                        font: { size: 12, family: fontFamily },
                        maxTicksLimit: 6,
                        callback: (value) => props.toBengaliNumber(value, 0),
                    },
                },
                x: {
                    border: { display: false },
                    grid: { display: false },
                    ticks: {
                        padding: 8,
                        color: "#64748b",
                        font: { size: 12, family: fontFamily },
                        maxRotation: 0,
                        autoSkipPadding: 16,
                    },
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "#0f172a",
                    titleColor: "#f8fafc",
                    bodyColor: "#e2e8f0",
                    borderColor: "rgba(148, 163, 184, 0.2)",
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    boxPadding: 6,
                    usePointStyle: true,
                    titleFont: { size: 13, weight: "600", family: fontFamily },
                    bodyFont: { size: 12, family: fontFamily },
                    callbacks: {
                        label: (context) => {
                            const label = context.dataset.label || "";
                            const value = props.toBengaliNumber(context.parsed.y ?? 0);
                            return `  ${label}: ৳${value}`;
                        },
                        title: (context) => {
                            const index = context[0].dataIndex;
                            const saleData = props.dateWiseSalesData[index];
                            const shops = props.toBengaliNumber(saleData.shop_count || 0);
                            return `${context[0].label} • ${shops} ${props.t("shops")}`;
                        },
                    },
                },
            },
        },
    });
};

onMounted(() => {
    initializeChart();
});

watch(
    () => props.dateWiseSalesData,
    () => initializeChart(),
    { deep: true }
);

watch(
    () => props.t("languageLabel"),
    () => initializeChart()
);
</script>
