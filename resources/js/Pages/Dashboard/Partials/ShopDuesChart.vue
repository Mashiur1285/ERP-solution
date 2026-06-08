<template>
    <div class="bg-white rounded-lg border border-gray-200 p-5 hover:shadow-md transition-shadow">
        <div class="mb-4">
            <h3 class="ts-title font-semibold text-gray-700 uppercase tracking-wide">Sales Trend</h3>
        </div>
        <div>
            <canvas ref="salesChart" class="w-full h-64"></canvas>
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
    const options = { month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
};

const initializeChart = () => {
    if (salesChart.value && props.dateWiseSalesData && props.dateWiseSalesData.length) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        const labels = props.dateWiseSalesData.map((data) => formatDate(data.sale_date));
        const paidData = props.dateWiseSalesData.map((data) => parseFloat(data.total_paid));
        const dueData = props.dateWiseSalesData.map((data) => parseFloat(data.total_due));

        chartInstance = new Chart(salesChart.value, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: props.t("paid"),
                        data: paidData,
                        backgroundColor: "rgba(75, 192, 192, 0.8)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                    },
                    {
                        label: props.t("due"),
                        data: dueData,
                        backgroundColor: "rgba(255, 99, 132, 0.8)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: props.t("amount"),
                            font: {
                                size: 16,
                                weight: "600",
                                family:
                                    props.t("languageLabel") === "বাংলা"
                                        ? "'Kalpurush', 'Noto Sans Bengali', sans-serif"
                                        : "Arial, sans-serif",
                            },
                            color: "#1f2937",
                        },
                        ticks: {
                            callback: function (value) {
                                return props.toBengaliNumber(value, 0);
                            },
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: props.t("languageLabel") === "বাংলা" ? "তারিখ" : "Date",
                            font: {
                                size: 16,
                                weight: "600",
                                family:
                                    props.t("languageLabel") === "বাংলা"
                                        ? "'Kalpurush', 'Noto Sans Bengali', sans-serif"
                                        : "Arial, sans-serif",
                            },
                            color: "#1f2937",
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || "";
                                if (label) {
                                    label += ": ";
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat("en-IN", {
                                        style: "currency",
                                        currency: "BDT",
                                    }).format(context.parsed.y);
                                }
                                return props.toBengaliNumber(label);
                            },
                            title: function (context) {
                                const index = context[0].dataIndex;
                                const saleData = props.dateWiseSalesData[index];
                                return `${context[0].label} - ${saleData.shop_count} ${props.t("shops")}`;
                            },
                        },
                    },
                },
            },
        });
    }
};

onMounted(() => {
    initializeChart();
});

watch(
    () => props.dateWiseSalesData,
    () => {
        initializeChart();
    },
    { deep: true }
);

watch(
    () => props.t("languageLabel"),
    () => {
        initializeChart();
    }
);
</script>
