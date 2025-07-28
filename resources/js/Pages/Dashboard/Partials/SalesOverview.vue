<!-- components/SalesOverview.vue -->
<template>
    <div class="mt-10">
        <div
            class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden border border-gray-100"
        >
            <!-- Subtle Background SVG -->
            <svg
                class="absolute inset-0 w-full h-full opacity-5 pointer-events-none"
                viewBox="0 0 1440 320"
                preserveAspectRatio="none"
            >
                <defs>
                    <linearGradient
                        id="salesBgGradient"
                        x1="0%"
                        y1="0%"
                        x2="100%"
                        y2="100%"
                    >
                        <stop
                            offset="0%"
                            style="stop-color: #4f46e5; stop-opacity: 0.1"
                        />
                        <stop
                            offset="50%"
                            style="stop-color: #10b981; stop-opacity: 0.1"
                        />
                        <stop
                            offset="100%"
                            style="stop-color: #7c3aed; stop-opacity: 0.1"
                        />
                    </linearGradient>
                </defs>
                <path
                    fill="url(#salesBgGradient)"
                    d="M0,160L48,138.7C96,117,192,75,288,80C384,85,480,139,576,149.3C672,160,768,128,864,106.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                />
            </svg>

            <div class="relative z-10">
                <div class="flex justify-between items-center mb-8">
                    <h2
                        class="text-2xl font-semibold text-gray-800 flex items-center"
                    >
                        <div
                            class="p-2 mr-3 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center shadow-lg"
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
                            class="p-2 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full hover:from-indigo-100 hover:to-purple-100 transition-all duration-300 shadow-md"
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
                            class="p-2 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full hover:from-indigo-100 hover:to-purple-100 transition-all duration-300 shadow-md"
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

                <!-- Sales Cards Section -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"
                >
                    <!-- Total Sales Card -->
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-purple-100 p-6 rounded-xl shadow-lg border border-indigo-200 transform hover:scale-105 transition-all duration-300"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full shadow-lg"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-sm font-medium text-indigo-600 uppercase tracking-wide"
                                >
                                    {{ t("totalSales") }}
                                </p>
                                <p
                                    class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-pulse-slow"
                                >
                                    ৳{{ toBengaliNumber(animatedTotalSales) }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-indigo-200 rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-1000"
                                style="width: 100%"
                            ></div>
                        </div>
                    </div>

                    <!-- Paid Amount Card -->
                    <div
                        class="bg-gradient-to-br from-purple-50 to-indigo-100 p-6 rounded-xl shadow-lg border border-purple-200 transform hover:scale-105 transition-all duration-300"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full shadow-lg"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-sm font-medium text-purple-600 uppercase tracking-wide"
                                >
                                    {{ t("paidAmount") }}
                                </p>
                                <p
                                    class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent animate-pulse-slow"
                                >
                                    ৳{{ toBengaliNumber(animatedPaidAmount) }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-purple-200 rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2 rounded-full transition-all duration-1000"
                                :style="{ width: paidPercentage + '%' }"
                            ></div>
                        </div>
                    </div>

                    <!-- Due Amount Card -->
                    <div
                        class="bg-gradient-to-br from-indigo-100 to-purple-50 p-6 rounded-xl shadow-lg border border-indigo-200 transform hover:scale-105 transition-all duration-300"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-indigo-600 to-purple-500 rounded-full shadow-lg"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-sm font-medium text-indigo-600 uppercase tracking-wide"
                                >
                                    {{ t("dueAmount") }}
                                </p>
                                <p
                                    class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-pulse-slow"
                                >
                                    ৳{{ toBengaliNumber(animatedDueAmount) }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-indigo-200 rounded-full h-2">
                            <div
                                class="bg-gradient-to-r from-indigo-600 to-purple-500 h-2 rounded-full transition-all duration-1000"
                                :style="{ width: duePercentage + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Sales Metrics Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Sales Breakdown Circle -->
                    <div class="flex flex-col items-center space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ t("salesBreakdown") }}
                        </h3>
                        <div
                            class="relative w-64 h-64"
                            ref="salesCircleContainer"
                        >
                            <svg
                                class="w-full h-full transform -rotate-90"
                                viewBox="0 0 100 100"
                            >
                                <defs>
                                    <linearGradient
                                        id="paidGradient"
                                        x1="0%"
                                        y1="0%"
                                        x2="100%"
                                        y2="100%"
                                    >
                                        <stop
                                            offset="0%"
                                            style="stop-color: #4f46e5"
                                        />
                                        <stop
                                            offset="50%"
                                            style="stop-color: #6366f1"
                                        />
                                        <stop
                                            offset="100%"
                                            style="stop-color: #10b981"
                                        />
                                    </linearGradient>
                                    <linearGradient
                                        id="dueGradient"
                                        x1="0%"
                                        y1="0%"
                                        x2="100%"
                                        y2="100%"
                                    >
                                        <stop
                                            offset="0%"
                                            style="stop-color: #7c3aed"
                                        />
                                        <stop
                                            offset="50%"
                                            style="stop-color: #a855f7"
                                        />
                                        <stop
                                            offset="100%"
                                            style="stop-color: #d946ef"
                                        />
                                    </linearGradient>
                                </defs>
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="35"
                                    fill="none"
                                    stroke="#e5e7eb"
                                    stroke-width="20"
                                />
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="35"
                                    fill="none"
                                    stroke="url(#paidGradient)"
                                    stroke-width="20"
                                    :stroke-dasharray="paidCircumference"
                                    stroke-dashoffset="0"
                                    stroke-linecap="round"
                                    class="transition-all duration-1000 ease-in-out cursor-pointer hover:stroke-width-22"
                                    @mouseenter="showPaidTooltip"
                                    @mousemove="updateTooltipPosition"
                                    @mouseleave="hideTooltip"
                                />
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="35"
                                    fill="none"
                                    stroke="url(#dueGradient)"
                                    stroke-width="20"
                                    :stroke-dasharray="dueCircumference"
                                    :stroke-dashoffset="dueOffset"
                                    stroke-linecap="round"
                                    class="transition-all duration-1000 ease-in-out cursor-pointer hover:stroke-width-22"
                                    @mouseenter="showDueTooltip"
                                    @mousemove="updateTooltipPosition"
                                    @mouseleave="hideTooltip"
                                />
                            </svg>
                            <div
                                class="absolute inset-0 flex items-center justify-center"
                            >
                                <span
                                    class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-pulse-slow"
                                >
                                    {{ toBengaliNumber(totalSalesPercentage) }}%
                                </span>
                            </div>

                            <!-- Tooltip -->
                            <div
                                v-if="tooltip.show"
                                class="absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg pointer-events-none transition-all duration-200"
                                :style="{
                                    left: tooltip.x + 'px',
                                    top: tooltip.y + 'px',
                                    transform: 'translate(-50%, -100%)',
                                }"
                            >
                                <div class="font-semibold">
                                    {{ tooltip.title }}
                                </div>
                                <div class="text-xs opacity-90">
                                    {{ tooltip.content }}
                                </div>
                                <div
                                    class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"
                                ></div>
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-2 gap-4 text-sm text-gray-600"
                        >
                            <div class="flex items-center">
                                <span
                                    class="w-4 h-4 rounded-full bg-gradient-to-r from-indigo-500 to-green-500 mr-2 shadow-sm"
                                ></span>
                                <span>
                                    {{ t("paid") }}:
                                    <span class="font-semibold text-indigo-600"
                                        >{{
                                            toBengaliNumber(paidPercentage)
                                        }}%</span
                                    >
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="w-4 h-4 rounded-full bg-gradient-to-r from-purple-500 to-pink-400 mr-2 shadow-sm"
                                ></span>
                                <span>
                                    {{ t("due") }}:
                                    <span class="font-semibold text-purple-600"
                                        >{{
                                            toBengaliNumber(duePercentage)
                                        }}%</span
                                    >
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Profit and Loss Metrics -->
                    <div class="flex flex-col items-center space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            {{ t("profitAndLoss") }}
                        </h3>
                        <div class="flex space-x-12">
                            <!-- Profit Circle -->
                            <div class="flex flex-col items-center space-y-4">
                                <div
                                    class="relative w-48 h-48"
                                    ref="profitCircleContainer"
                                >
                                    <svg
                                        class="w-full h-full transform -rotate-90"
                                        viewBox="0 0 100 100"
                                    >
                                        <circle
                                            cx="50"
                                            cy="50"
                                            r="30"
                                            fill="none"
                                            stroke="#d1fae5"
                                            stroke-width="18"
                                        />
                                        <circle
                                            cx="50"
                                            cy="50"
                                            r="30"
                                            fill="none"
                                            stroke="url(#profitGradient)"
                                            stroke-width="18"
                                            stroke-linecap="round"
                                            :stroke-dasharray="
                                                profitCircumference
                                            "
                                            stroke-dashoffset="0"
                                            class="transition-all duration-1000 ease-in-out cursor-pointer hover:stroke-width-20"
                                            @mouseenter="showProfitTooltip"
                                            @mousemove="updateTooltipPosition"
                                            @mouseleave="hideTooltip"
                                        />
                                        <defs>
                                            <linearGradient
                                                id="profitGradient"
                                                x1="0%"
                                                y1="0%"
                                                x2="100%"
                                                y2="100%"
                                            >
                                                <stop
                                                    offset="0%"
                                                    style="stop-color: #4f46e5"
                                                />
                                                <stop
                                                    offset="50%"
                                                    style="stop-color: #6366f1"
                                                />
                                                <stop
                                                    offset="100%"
                                                    style="stop-color: #10b981"
                                                />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <div
                                        class="absolute inset-0 flex items-center justify-center"
                                    >
                                        <span
                                            class="text-2xl font-bold text-emerald-600"
                                        >
                                            {{
                                                toBengaliNumber(
                                                    profitPercentage
                                                )
                                            }}%
                                        </span>
                                    </div>

                                    <!-- Profit Tooltip -->
                                    <div
                                        v-if="
                                            tooltip.show &&
                                            tooltip.title === t('profit')
                                        "
                                        class="absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg pointer-events-none transition-all duration-200"
                                        :style="{
                                            left: tooltip.x + 'px',
                                            top: tooltip.y + 'px',
                                            transform: 'translate(-50%, -100%)',
                                        }"
                                    >
                                        <div class="font-semibold">
                                            {{ tooltip.title }}
                                        </div>
                                        <div class="text-xs opacity-90">
                                            {{ tooltip.content }}
                                        </div>
                                        <div
                                            class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"
                                        ></div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p
                                        class="text-sm text-gray-600 mb-2 font-medium"
                                    >
                                        {{ t("profit") }}
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-emerald-600 animate-pulse-slow"
                                    >
                                        ৳{{ toBengaliNumber(animatedProfit) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Loss Circle -->
                            <div class="flex flex-col items-center space-y-4">
                                <div
                                    class="relative w-48 h-48"
                                    ref="lossCircleContainer"
                                >
                                    <svg
                                        class="w-full h-full transform -rotate-90"
                                        viewBox="0 0 100 100"
                                    >
                                        <circle
                                            cx="50"
                                            cy="50"
                                            r="30"
                                            fill="none"
                                            stroke="#fee2e2"
                                            stroke-width="18"
                                        />
                                        <circle
                                            cx="50"
                                            cy="50"
                                            r="30"
                                            fill="none"
                                            stroke="url(#lossGradient)"
                                            stroke-width="18"
                                            stroke-linecap="round"
                                            :stroke-dasharray="
                                                lossCircumference
                                            "
                                            stroke-dashoffset="0"
                                            class="transition-all duration-1000 ease-in-out cursor-pointer hover:stroke-width-20"
                                            @mouseenter="showLossTooltip"
                                            @mousemove="updateTooltipPosition"
                                            @mouseleave="hideTooltip"
                                        />
                                        <defs>
                                            <linearGradient
                                                id="lossGradient"
                                                x1="0%"
                                                y1="0%"
                                                x2="100%"
                                                y2="100%"
                                            >
                                                <stop
                                                    offset="0%"
                                                    style="stop-color: #7c3aed"
                                                />
                                                <stop
                                                    offset="50%"
                                                    style="stop-color: #a855f7"
                                                />
                                                <stop
                                                    offset="100%"
                                                    style="stop-color: #c084fc"
                                                />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <div
                                        class="absolute inset-0 flex items-center justify-center"
                                    >
                                        <span
                                            class="text-2xl font-bold text-rose-600"
                                        >
                                            {{
                                                toBengaliNumber(lossPercentage)
                                            }}%
                                        </span>
                                    </div>

                                    <!-- Loss Tooltip -->
                                    <div
                                        v-if="
                                            tooltip.show &&
                                            tooltip.title === t('loss')
                                        "
                                        class="absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg pointer-events-none transition-all duration-200"
                                        :style="{
                                            left: tooltip.x + 'px',
                                            top: tooltip.y + 'px',
                                            transform: 'translate(-50%, -100%)',
                                        }"
                                    >
                                        <div class="font-semibold">
                                            {{ tooltip.title }}
                                        </div>
                                        <div class="text-xs opacity-90">
                                            {{ tooltip.content }}
                                        </div>
                                        <div
                                            class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"
                                        ></div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p
                                        class="text-sm text-gray-600 mb-2 font-medium"
                                    >
                                        {{ t("loss") }}
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-rose-600 animate-pulse-slow"
                                    >
                                        ৳{{ toBengaliNumber(animatedLoss) }}
                                    </p>
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
import { defineProps, ref, computed, reactive } from "vue";

const props = defineProps({
    monthlySales: Object,
    month: Number,
    year: Number,
    animatedTotalSales: Number,
    animatedPaidAmount: Number,
    animatedDueAmount: Number,
    animatedProfit: Number,
    animatedLoss: Number,
    t: Function,
    toBengaliNumber: Function,
    selectedMonthYear: String,
    navigateToPreviousMonth: Function,
    navigateToNextMonth: Function,
    loading: Boolean,
});

const salesCircleContainer = ref(null);
const profitCircleContainer = ref(null);
const lossCircleContainer = ref(null);

const tooltip = reactive({
    show: false,
    x: 0,
    y: 0,
    title: "",
    content: "",
});

const paidPercentage = computed(() => {
    if (props.animatedTotalSales === 0) return 0;
    return (
        (props.animatedPaidAmount / props.animatedTotalSales) *
        100
    ).toFixed(0);
});

const duePercentage = computed(() => {
    if (props.animatedTotalSales === 0) return 0;
    return ((props.animatedDueAmount / props.animatedTotalSales) * 100).toFixed(
        0
    );
});

const totalSalesPercentage = computed(() => {
    return (
        parseFloat(paidPercentage.value) + parseFloat(duePercentage.value)
    ).toFixed(0);
});

const profitPercentage = computed(() => {
    if (props.animatedTotalSales === 0) return 0;
    return ((props.animatedProfit / props.animatedTotalSales) * 100).toFixed(0);
});

const lossPercentage = computed(() => {
    if (props.animatedTotalSales === 0) return 0;
    return ((props.animatedLoss / props.animatedTotalSales) * 100).toFixed(0);
});

const radius = 35;
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
    return -(paidPercent / 100) * circumference;
});

const profitRadius = 30;
const profitCircumference = computed(() => {
    const percentage = parseFloat(profitPercentage.value);
    return `${(percentage / 100) * (2 * Math.PI * profitRadius)}, ${
        2 * Math.PI * profitRadius
    }`;
});

const lossCircumference = computed(() => {
    const percentage = parseFloat(lossPercentage.value);
    return `${(percentage / 100) * (2 * Math.PI * profitRadius)}, ${
        2 * Math.PI * profitRadius
    }`;
});

const showPaidTooltip = () => {
    tooltip.show = true;
    tooltip.title = props.t("paidAmount");
    tooltip.content = `৳${props.toBengaliNumber(
        props.animatedPaidAmount
    )} (${props.toBengaliNumber(paidPercentage.value)}%)`;
};

const showDueTooltip = () => {
    tooltip.show = true;
    tooltip.title = props.t("dueAmount");
    tooltip.content = `৳${props.toBengaliNumber(
        props.animatedDueAmount
    )} (${props.toBengaliNumber(duePercentage.value)}%)`;
};

const showProfitTooltip = () => {
    tooltip.show = true;
    tooltip.title = props.t("profit");
    tooltip.content = `৳${props.toBengaliNumber(
        props.animatedProfit
    )} (${props.toBengaliNumber(profitPercentage.value)}%)`;
};

const showLossTooltip = () => {
    tooltip.show = true;
    tooltip.title = props.t("loss");
    tooltip.content = `৳${props.toBengaliNumber(
        props.animatedLoss
    )} (${props.toBengaliNumber(lossPercentage.value)}%)`;
};

const updateTooltipPosition = (event) => {
    const rect = event.currentTarget
        .closest(".relative")
        .getBoundingClientRect();
    tooltip.x = event.clientX - rect.left;
    tooltip.y = event.clientY - rect.top - 10;
};

const hideTooltip = () => {
    tooltip.show = false;
};
</script>

<style scoped>
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

.animate-pulse-slow {
    animation: pulseSlow 2s infinite;
}

.bg-gradient-to-r {
    background: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-indigo-600 {
    --tw-gradient-from: #4f46e5;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(79, 70, 229, 0));
}

.to-purple-600 {
    --tw-gradient-to: #7c3aed;
}

.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

.text-transparent {
    color: transparent;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

.from-indigo-50 {
    --tw-gradient-from: #eef2ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(238, 242, 255, 0));
}

.to-purple-50 {
    --tw-gradient-to: #faf5ff;
}

.from-indigo-100 {
    --tw-gradient-from: #e0e7ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(224, 231, 255, 0));
}

.to-purple-100 {
    --tw-gradient-to: #ede9fe;
}
</style>
