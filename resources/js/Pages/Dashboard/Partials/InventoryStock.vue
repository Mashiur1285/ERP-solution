```vue
<template>
    <div class="mt-10">
        <div
            class="bg-white rounded-lg shadow-lg p-8 transition-all duration-300 hover:bg-gray-50 border border-gray-200 relative"
        >
            <!-- Enhanced SVG Background -->
            <svg
                class="absolute inset-0 w-full h-full opacity-5 pointer-events-none"
                viewBox="0 0 1440 320"
                preserveAspectRatio="none"
            >
                <defs>
                    <linearGradient
                        id="bgGradient"
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
                            style="stop-color: #7c3aed; stop-opacity: 0.1"
                        />
                        <stop
                            offset="100%"
                            style="stop-color: #4f46e5; stop-opacity: 0.1"
                        />
                    </linearGradient>
                </defs>
                <path
                    fill="url(#bgGradient)"
                    d="M0,160L48,138.4C96,117,192,75,288,80C384,85,480,139,576,160C672,181,768,171,864,149.3C960,128,1056,96,1152,96C1248,96,1344,128,1392,144L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                />
            </svg>

            <div class="relative z-10">
                <div class="flex justify-between items-center mb-6">
                    <h2
                        class="text-2xl font-semibold text-gray-800 flex items-center"
                    >
                        <div
                            class="p-2 mr-3 bg-gradient-to-br from-indigo-200 to-purple-200 rounded-full flex items-center justify-center shadow-lg"
                        >
                            <svg
                                class="w-8 h-8 text-indigo-700"
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
                        {{ t("inventoryStock") }}
                    </h2>
                    <div class="flex space-x-4">
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="t('searchProducts')"
                            class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        />
                        <button
                            @click="toggleView"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-md"
                        >
                            {{
                                viewMode === "chart"
                                    ? t("showList")
                                    : t("showChart")
                            }}
                        </button>
                    </div>
                </div>

                <!-- Stock Level Legend -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">
                        {{ t("stockLevel") }}
                    </h3>
                    <div class="flex flex-wrap gap-6">
                        <div class="flex items-center space-x-1">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span class="text-sm text-gray-600">{{
                                t("lowStock")
                            }}</span>
                            <span class="text-xs text-gray-400">(≤ 50)</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div
                                class="w-3 h-3 rounded-full bg-amber-500"
                            ></div>
                            <span class="text-sm text-gray-600">{{
                                t("mediumStock")
                            }}</span>
                            <span class="text-xs text-gray-400">(51–200)</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <div
                                class="w-3 h-3 rounded-full bg-emerald-500"
                            ></div>
                            <span class="text-sm text-gray-600">{{
                                t("highStock")
                            }}</span>
                            <span class="text-xs text-gray-400">(> 200)</span>
                        </div>
                    </div>
                </div>

                <!-- Total Metrics -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"
                >
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-lg shadow-md border border-indigo-100"
                    >
                        <div class="flex items-center mb-1">
                            <svg
                                class="w-4 h-4 text-indigo-600 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                ></path>
                            </svg>
                            <p class="text-sm text-gray-600">
                                {{ t("totalProducts") }}
                            </p>
                        </div>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent metric-value"
                            :class="{
                                'large-number':
                                    totalProducts.toString().length > 10,
                            }"
                        >
                            {{ toBengaliNumber(totalProducts, 0) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-lg shadow-md border border-indigo-100"
                    >
                        <div class="flex items-center mb-1">
                            <svg
                                class="w-4 h-4 text-indigo-600 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                ></path>
                            </svg>
                            <p class="text-sm text-gray-600">
                                {{ t("totalBoxes") }}
                            </p>
                        </div>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent metric-value"
                            :class="{
                                'large-number':
                                    totalBoxes.toString().length > 10,
                            }"
                        >
                            {{ toBengaliNumber(totalBoxes, 0) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-lg shadow-md border border-indigo-100"
                    >
                        <div class="flex items-center mb-1">
                            <svg
                                class="w-4 h-4 text-indigo-600 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                                ></path>
                            </svg>
                            <p class="text-sm text-gray-600">
                                {{ t("totalQuantity") }}
                            </p>
                        </div>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent metric-value"
                            :class="{
                                'large-number':
                                    totalQuantity.toString().length > 10,
                            }"
                        >
                            {{ toBengaliNumber(totalQuantity, 0) }}
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-lg shadow-md border border-indigo-100"
                    >
                        <div class="flex items-center mb-1">
                            <svg
                                class="w-4 h-4 text-indigo-600 mr-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <p class="text-sm text-gray-600">
                                {{ t("totalPurchaseValue") }}
                            </p>
                        </div>
                        <p
                            class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent metric-value"
                            :class="{
                                'large-number':
                                    totalPurchaseValue.toString().length > 10,
                            }"
                        >
                            ৳{{ toBengaliNumber(totalPurchaseValue, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Chart View -->
                <div v-if="viewMode === 'chart'" class="mb-6">
                    <canvas ref="inventoryChart" class="w-full h-80"></canvas>
                </div>

                <!-- List View -->
                <div v-if="viewMode === 'list'" class="space-y-4">
                    <div
                        v-for="(item, index) in filteredInventory"
                        :key="index"
                        class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer border"
                        :class="
                            getStockLevelBorder(item.total_available_bottles)
                        "
                        @click="toggleVariants(index)"
                    >
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <div class="flex items-center mb-3">
                                    <h3
                                        class="text-lg font-semibold text-gray-800"
                                    >
                                        {{ item.product_name }}
                                    </h3>
                                    <!-- Stock Badge -->
                                    <div class="ml-3">
                                        <span
                                            class="clean-stock-badge"
                                            :class="
                                                getCleanStockBadge(
                                                    item.total_available_bottles
                                                )
                                            "
                                        >
                                            <div
                                                class="stock-dot"
                                                :class="
                                                    getStockDot(
                                                        item.total_available_bottles
                                                    )
                                                "
                                            ></div>
                                            {{
                                                t(
                                                    getStockLevel(
                                                        item.total_available_bottles
                                                    )
                                                )
                                            }}
                                            <span class="stock-count">{{
                                                toBengaliNumber(
                                                    item.total_available_bottles,
                                                    0
                                                )
                                            }}</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Product Summary Cards -->
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4"
                                >
                                    <div
                                        class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-3 rounded-lg border border-emerald-200 shadow-sm"
                                    >
                                        <div class="flex items-center mb-1">
                                            <svg
                                                class="w-4 h-4 text-emerald-600 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                ></path>
                                            </svg>
                                            <span
                                                class="text-xs text-gray-600 uppercase font-medium"
                                                >{{ t("totalBoxes") }}</span
                                            >
                                        </div>
                                        <span
                                            class="text-lg font-bold text-emerald-600 metric-value"
                                            :class="{
                                                'large-number':
                                                    item.total_available_cases.toString()
                                                        .length > 10,
                                            }"
                                        >
                                            {{
                                                toBengaliNumber(
                                                    item.total_available_cases,
                                                    0
                                                )
                                            }}
                                        </span>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-lg border border-blue-200 shadow-sm"
                                    >
                                        <div class="flex items-center mb-1">
                                            <svg
                                                class="w-4 h-4 text-blue-600 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                                                ></path>
                                            </svg>
                                            <span
                                                class="text-xs text-gray-600 uppercase font-medium"
                                                >{{ t("totalQuantity") }}</span
                                            >
                                        </div>
                                        <span
                                            class="text-lg font-bold text-blue-600 metric-value"
                                            :class="{
                                                'large-number':
                                                    item.total_available_bottles.toString()
                                                        .length > 10,
                                            }"
                                        >
                                            {{
                                                toBengaliNumber(
                                                    item.total_available_bottles,
                                                    0
                                                )
                                            }}
                                        </span>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-purple-100 p-3 rounded-lg border border-purple-200 shadow-sm"
                                    >
                                        <div class="flex items-center mb-1">
                                            <svg
                                                class="w-4 h-4 text-purple-600 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                            <span
                                                class="text-xs text-gray-600 uppercase font-medium"
                                                >{{ t("totalValue") }}</span
                                            >
                                        </div>
                                        <span
                                            class="text-lg font-bold text-purple-600 metric-value"
                                            :class="{
                                                'large-number':
                                                    item.total_value.toString()
                                                        .length > 10,
                                            }"
                                        >
                                            ৳{{
                                                toBengaliNumber(
                                                    item.total_value,
                                                    2
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Centered SVG Arrow -->
                            <div
                                class="flex ml-3 items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full shadow-md"
                            >
                                <svg
                                    class="w-6 h-6 text-indigo-600 transform transition-transform"
                                    :class="{
                                        'rotate-180': expandedVariants[index],
                                    }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Variant Details -->
                        <div
                            v-if="expandedVariants[index]"
                            class="mt-6 space-y-3 animate-slide-down border-t border-gray-200 pt-4"
                        >
                            <h4
                                class="text-sm font-semibold text-gray-700 mb-3"
                            >
                                {{ t("variantDetails") }}
                            </h4>
                            <div
                                v-for="(variant, vIndex) in item.variants"
                                :key="vIndex"
                                class="bg-gradient-to-r from-gray-50 to-indigo-50 p-4 rounded-lg border border-gray-200 shadow-sm"
                            >
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-5 gap-3"
                                >
                                    <div class="text-center">
                                        <span
                                            class="text-xs text-gray-500 uppercase font-medium block mb-1"
                                            >{{ t("variant") }}</span
                                        >
                                        <span
                                            class="text-sm font-semibold text-gray-800"
                                            >{{ variant.variant }}</span
                                        >
                                    </div>
                                    <div class="text-center">
                                        <span
                                            class="text-xs text-gray-500 uppercase font-medium block mb-1"
                                            >{{ t("stockLevel") }}</span
                                        >
                                        <!-- Variant Stock Badge -->
                                        <span
                                            class="clean-variant-badge"
                                            :class="
                                                getCleanVariantBadge(
                                                    variant.total_bottles_available
                                                )
                                            "
                                        >
                                            <div
                                                class="variant-dot"
                                                :class="
                                                    getStockDot(
                                                        variant.total_bottles_available
                                                    )
                                                "
                                            ></div>
                                            {{
                                                t(
                                                    getStockLevel(
                                                        variant.total_bottles_available
                                                    )
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <div class="text-center">
                                        <span
                                            class="text-xs text-gray-500 uppercase font-medium block mb-1"
                                            >{{ t("boxes") }}</span
                                        >
                                        <span
                                            class="text-sm font-semibold text-emerald-600 flex items-center justify-center"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                ></path>
                                            </svg>
                                            {{
                                                toBengaliNumber(
                                                    variant.cases_available,
                                                    0
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <div class="text-center">
                                        <span
                                            class="text-xs text-gray-500 uppercase font-medium block mb-1"
                                            >{{ t("unitPrice") }}</span
                                        >
                                        <span
                                            class="text-sm font-semibold text-gray-800 metric-value"
                                            :class="{
                                                'large-number':
                                                    variant.unit_price.toString()
                                                        .length > 10,
                                            }"
                                        >
                                            ৳{{
                                                toBengaliNumber(
                                                    variant.unit_price,
                                                    2
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <div class="text-center">
                                        <span
                                            class="text-xs text-gray-500 uppercase font-medium block mb-1"
                                            >{{ t("totalValue") }}</span
                                        >
                                        <span
                                            class="text-sm font-semibold text-purple-600 metric-value"
                                            :class="{
                                                'large-number':
                                                    (
                                                        variant.total_bottles_available *
                                                        variant.unit_price
                                                    ).toString().length > 10,
                                            }"
                                        >
                                            ৳{{
                                                toBengaliNumber(
                                                    variant.total_bottles_available *
                                                        variant.unit_price,
                                                    2
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Progress Bar for Variant Quantity -->
                                <div class="mt-3">
                                    <div
                                        class="flex justify-between items-center mb-1"
                                    >
                                        <span class="text-xs text-gray-500">{{
                                            t("stockLevel")
                                        }}</span>
                                        <span class="text-xs text-gray-600"
                                            >{{
                                                toBengaliNumber(
                                                    variant.total_bottles_available,
                                                    0
                                                )
                                            }}
                                            /
                                            {{
                                                toBengaliNumber(
                                                    getMaxVariantQuantity(),
                                                    0
                                                )
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="w-full bg-gray-200 rounded-full h-2 overflow-hidden"
                                    >
                                        <div
                                            class="h-2 rounded-full transition-all duration-1000"
                                            :class="
                                                getCleanProgressBar(
                                                    variant.total_bottles_available
                                                )
                                            "
                                            :style="{
                                                width:
                                                    getVariantBarWidth(
                                                        variant.total_bottles_available
                                                    ) + '%',
                                            }"
                                        ></div>
                                    </div>
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
import { ref, computed, onMounted, watch } from "vue";
import Chart from "chart.js/auto";

const props = defineProps({
    inventoryStock: Array,
    t: Function,
    toBengaliNumber: Function,
    animatedInventoryStock: Object,
});

const viewMode = ref("chart");
const searchQuery = ref("");
const expandedVariants = ref({});
const inventoryChart = ref(null);
let chartInstance = null;

// Updated toBengaliNumber to handle decimals
const toBengaliNumber = (num, decimals = 0) => {
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string") return num;
    if (props.t("languageLabel") !== "বাংলা") {
        return decimals > 0 ? Number(num).toFixed(decimals) : num;
    }

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    let formattedNum =
        decimals > 0 ? Number(num).toFixed(decimals) : num.toString();
    return formattedNum.replace(
        /\d/g,
        (digit) => bengaliDigits[parseInt(digit)]
    );
};

// Computed properties for total metrics
const totalProducts = computed(() => props.inventoryStock.length);
const totalQuantity = computed(() =>
    props.inventoryStock.reduce(
        (sum, item) => sum + (item.total_available_bottles || 0),
        0
    )
);
const totalBoxes = computed(() =>
    props.inventoryStock.reduce(
        (sum, item) => sum + (item.total_available_cases || 0),
        0
    )
);
const totalPurchaseValue = computed(() => {
    const total = props.inventoryStock.reduce((sum, item) => {
        const itemTotalValue = item.variants.reduce((variantSum, variant) => {
            if (
                typeof variant.total_bottles_available !== "number" ||
                typeof variant.unit_price !== "number"
            ) {
                console.warn(
                    `Invalid variant data in item ${item.product_name}:`,
                    variant
                );
                return variantSum;
            }
            return (
                variantSum +
                variant.total_bottles_available * variant.unit_price
            );
        }, 0);
        return sum + itemTotalValue;
    }, 0);
    return total;
});

// Processed inventory to ensure total_value is calculated
const processedInventory = computed(() => {
    return props.inventoryStock.map((item) => {
        if (!item.variants || !Array.isArray(item.variants)) {
            console.warn(
                `Invalid variants for item ${item.product_name}:`,
                item
            );
            return { ...item, total_value: 0 };
        }
        const total_value = item.variants.reduce((sum, variant) => {
            if (
                typeof variant.total_bottles_available !== "number" ||
                typeof variant.unit_price !== "number"
            ) {
                console.warn(
                    `Invalid variant data in item ${item.product_name}:`,
                    variant
                );
                return sum;
            }
            return sum + variant.total_bottles_available * variant.unit_price;
        }, 0);
        return {
            ...item,
            total_value,
        };
    });
});

// Determine stock level based on quantity
const getStockLevel = (quantity) => {
    if (quantity <= 50) return "lowStock";
    if (quantity <= 200) return "mediumStock";
    return "highStock";
};

// Get clean badge classes for stock level
const getCleanStockBadge = (quantity) => {
    const level = getStockLevel(quantity);
    return {
        "stock-low": level === "lowStock",
        "stock-medium": level === "mediumStock",
        "stock-high": level === "highStock",
    };
};

// Get clean variant badge classes
const getCleanVariantBadge = (quantity) => {
    const level = getStockLevel(quantity);
    return {
        "variant-stock-low": level === "lowStock",
        "variant-stock-medium": level === "mediumStock",
        "variant-stock-high": level === "highStock",
    };
};

// Get stock dot classes
const getStockDot = (quantity) => {
    const level = getStockLevel(quantity);
    return {
        "dot-low": level === "lowStock",
        "dot-medium": level === "mediumStock",
        "dot-high": level === "highStock",
    };
};

// Get clean progress bar classes
const getCleanProgressBar = (quantity) => {
    const level = getStockLevel(quantity);
    return {
        "progress-low": level === "lowStock",
        "progress-medium": level === "mediumStock",
        "progress-high": level === "highStock",
    };
};

// Get border classes for low stock items
const getStockLevelBorder = (quantity) => {
    const level = getStockLevel(quantity);
    return level === "lowStock"
        ? "border-red-300 bg-red-50/30"
        : "border-gray-200";
};

// Toggle between chart and list view
const toggleView = () => {
    viewMode.value = viewMode.value === "chart" ? "list" : "chart";
};

// Toggle variant details
const toggleVariants = (index) => {
    expandedVariants.value[index] = !expandedVariants.value[index];
};

// Calculate progress bar width for variants
const getVariantBarWidth = (quantity) => {
    const maxQuantity = getMaxVariantQuantity();
    return Math.min((quantity / maxQuantity) * 100, 100);
};

// Get maximum variant quantity for progress bar calculations
const getMaxVariantQuantity = () => {
    return Math.max(
        ...props.inventoryStock.flatMap((item) =>
            item.variants.map((v) => v.total_bottles_available || 0)
        ),
        1
    );
};

// Filter inventory based on search query
const filteredInventory = computed(() => {
    if (!searchQuery.value) return processedInventory.value;
    const query = searchQuery.value.toLowerCase();
    return processedInventory.value.filter((item) =>
        item.product_name.toLowerCase().includes(query)
    );
});

const initializeChart = () => {
    if (inventoryChart.value && props.inventoryStock.length) {
        if (chartInstance) {
            chartInstance.destroy();
        }

        const labels = props.inventoryStock.map((item) => item.product_name);
        const quantities = props.inventoryStock.map(
            (item) =>
                props.animatedInventoryStock[item.product_name]
                    ?.total_quantity ||
                item.total_available_bottles ||
                0
        );
        const stockLevels = props.inventoryStock.map((item) =>
            getStockLevel(item.total_available_bottles || 0)
        );

        chartInstance = new Chart(inventoryChart.value, {
            type: "bar",
            data: {
                labels,
                datasets: [
                    {
                        label: props.t("totalQuantity"),
                        data: quantities,
                        backgroundColor: "rgba(79, 70, 229, 0.8)",
                        borderColor: "rgba(79, 70, 229, 1)",
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
                            text: props.t("totalQuantity"),
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
                        grid: {
                            color: "rgba(0, 0, 0, 0.05)",
                            borderColor: "#d1d5db",
                            drawTicks: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family:
                                    props.t("languageLabel") === "বাংলা"
                                        ? "'Kalpurush', 'Noto Sans Bengali', sans-serif"
                                        : "Arial, sans-serif",
                            },
                            color: "#4b5563",
                            padding: 10,
                            callback: function (value) {
                                return props.t("languageLabel") === "বাংলা"
                                    ? props.toBengaliNumber(value, 0)
                                    : value;
                            },
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: props.t("products"),
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
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family:
                                    props.t("languageLabel") === "বাংলা"
                                        ? "'Kalpurush', 'Noto Sans Bengali', sans-serif"
                                        : "Arial, sans-serif",
                            },
                            color: "#4b5563",
                            maxRotation: 0,
                            minRotation: 0,
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: "rgba(17, 24, 39, 0.9)",
                        titleFont: {
                            size: 14,
                            family:
                                props.t("languageLabel") === "বাংলা"
                                    ? "'Kalpurush', 'Noto Sans Bengali', sans-serif"
                                    : "Arial, sans-serif",
                        },
                        bodyFont: {
                            size: 12,
                            family:
                                props.t("languageLabel") === "বাংলা"
                                    ? "'Kalpurush', 'Noto Sans Bengali', sans-serif"
                                    : "Arial, sans-serif",
                        },
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 6,
                        callbacks: {
                            label: function (context) {
                                const index = context.dataIndex;
                                const stockLevel =
                                    stockLevels[index] || "unknown";
                                const stockLevelLabel =
                                    props.t(stockLevel) || stockLevel;
                                return [
                                    `${props.t(
                                        "totalQuantity"
                                    )}: ${props.toBengaliNumber(
                                        context.raw,
                                        0
                                    )}`,
                                    `${props.t(
                                        "stockLevel"
                                    )}: ${stockLevelLabel}`,
                                ];
                            },
                        },
                    },
                },
                animation: {
                    duration: 2000,
                    easing: "easeOutQuart",
                },
                hover: {
                    animationDuration: 400,
                },
            },
        });
    }
};

onMounted(() => {
    if (viewMode.value === "chart") {
        initializeChart();
    }
    // Log inventoryStock for debugging
    console.log("InventoryStock prop:", props.inventoryStock);
});

watch(viewMode, () => {
    if (viewMode.value === "chart") {
        setTimeout(initializeChart, 0);
    }
});

watch(
    () => props.inventoryStock,
    () => {
        console.log("InventoryStock updated:", props.inventoryStock);
        initializeChart();
    },
    { deep: true }
);

watch(
    () => props.t("languageLabel"),
    () => {
        if (viewMode.value === "chart") {
            setTimeout(initializeChart, 0);
        }
    }
);
</script>

<style scoped>
/* Clean Stock Badges */
.clean-stock-badge {
    @apply inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200;
    border: 1px solid;
}

.stock-low {
    @apply bg-red-50 text-red-700 border-red-200 hover:bg-red-100;
}

.stock-medium {
    @apply bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100;
}

.stock-high {
    @apply bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100;
}

/* Stock Dots */
.stock-dot {
    @apply w-2 h-2 rounded-full;
}

.dot-low {
    @apply bg-red-500;
    animation: pulse-dot 2s infinite;
}

.dot-medium {
    @apply bg-amber-500;
}

.dot-high {
    @apply bg-emerald-500;
}

/* Stock Count */
.stock-count {
    @apply ml-auto px-2 py-0.5 rounded text-xs font-semibold;
    background: rgba(255, 255, 255, 0.8);
}

.stock-low .stock-count {
    @apply text-red-800;
}

.stock-medium .stock-count {
    @apply text-amber-800;
}

.stock-high .stock-count {
    @apply text-emerald-800;
}

/* Clean Variant Badges */
.clean-variant-badge {
    @apply inline-flex items-center gap-1.5 px-2 py-1 rounded text-xs font-medium;
}

.variant-stock-low {
    @apply bg-red-100 text-red-700;
}

.variant-stock-medium {
    @apply bg-amber-100 text-amber-700;
}

.variant-stock-high {
    @apply bg-emerald-100 text-emerald-700;
}

/* Variant Dots */
.variant-dot {
    @apply w-1.5 h-1.5 rounded-full;
}

/* Clean Progress Bars */
.progress-low {
    @apply bg-red-500;
}

.progress-medium {
    @apply bg-amber-500;
}

.progress-high {
    @apply bg-emerald-500;
}

/* Pulse Animation for Low Stock */
@keyframes pulse-dot {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.6;
    }
}

/* Adjust metrics card text to prevent overflow */
.metric-value {
    word-break: break-all;
    overflow-wrap: break-word;
    white-space: normal;
    line-height: 1.2;
}

/* Reduce font size for very large numbers */
.metric-value.large-number {
    font-size: 1.5rem; /* Adjust for smaller screens */
}

@media (min-width: 1024px) {
    .metric-value.large-number {
        font-size: 1.75rem; /* Slightly larger for desktop */
    }
}

/* Legacy styles for compatibility */
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

.transition-all {
    transition: all 0.3s ease;
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

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}
</style>
```
