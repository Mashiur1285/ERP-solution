<!-- Dashboard.vue -->
<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Language Toggle -->
        <div class="flex justify-end space-x-2 mb-4">
            <button
                @click="changeLanguage('en')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-colors',
                    currentLanguage === 'en'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ translations.en.languageLabel }}
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
                {{ translations.bn.languageLabel }}
            </button>
        </div>

        <!-- Dashboard Title -->
        <div
            class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4"
        >
            <h1
                class="text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
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
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        />
                    </svg>
                </div>
                {{ t("dashboardTitle") }}
            </h1>
        </div>

        <!-- Child Components -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <TopSuppliers
                :suppliers="suppliers"
                :suppliersCount="animatedSuppliersCount"
                :t="t"
                :toBengaliNumber="toBengaliNumber"
            />
            <TopDepositSuppliers
                :topDeposits="topDeposits"
                :animatedDeposits="animatedDeposits"
                :animatedWidths="animatedWidths"
                :t="t"
                :toBengaliNumber="toBengaliNumber"
            />
            <TotalShops
                :shops="shops"
                :totalShops="animatedTotalShops"
                :t="t"
                :toBengaliNumber="toBengaliNumber"
            />
        </div>

        <SalesOverview
            :monthlySales="monthlySales"
            :month="month"
            :year="year"
            :animatedTotalSales="animatedTotalSales"
            :animatedPaidAmount="animatedPaidAmount"
            :animatedDueAmount="animatedDueAmount"
            :animatedProfit="animatedProfit"
            :animatedLoss="animatedLoss"
            :t="t"
            :toBengaliNumber="toBengaliNumber"
            :selectedMonthYear="selectedMonthYear"
            :navigateToPreviousMonth="navigateToPreviousMonth"
            :navigateToNextMonth="navigateToNextMonth"
            :loading="loading"
        />

        <InventoryStock
            :inventoryStock="inventoryStock"
            :t="t"
            :toBengaliNumber="toBengaliNumber"
            :animatedInventoryStock="animatedInventoryStock"
        />
    </div>
</template>

<script setup>
import { defineProps, ref, onMounted, computed, watch, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import TopSuppliers from "./Partials/TopSuppliers.vue";
import TopDepositSuppliers from "./Partials/TopDepositSuppliers.vue";
import TotalShops from "./Partials/TotalShops.vue";
import SalesOverview from "./Partials/SalesOverview.vue";
import InventoryStock from "./Partials/InventoryStock.vue";
import Layout from "@/Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps({
    suppliersCount: Number,
    topDeposits: Array,
    suppliers: Array,
    shops: Array,
    monthlySales: Object,
    month: Number,
    year: Number,
    inventoryStock: Array,
});

// Translation object
const translations = {
    en: {
        languageLabel: "English",
        dashboardTitle: "Dashboard",
        totalSuppliers: "Total Suppliers",
        activeSuppliers: "Active suppliers in the system",
        supplier: "Supplier",
        id: "ID",
        topDepositSuppliers: "Top Deposit Suppliers",
        totalShops: "Total Shops",
        registeredShops: "Registered shops in the system",
        shop: "Shop",
        salesOverview: "Sales Overview",
        previousMonth: "Previous month",
        nextMonth: "Next month",
        salesBreakdown: "Sales Breakdown",
        paid: "Paid",
        due: "Due",
        totalSales: "Total Sales",
        paidAmount: "Paid Amount",
        dueAmount: "Due Amount",
        profitAndLoss: "Profit & Loss",
        profit: "Profit",
        loss: "Loss",
        inventoryStock: "Inventory Stock",
        totalQuantity: "Total Quantity",
        totalValue: "Total Value",
        totalProducts: "Total Products",
        totalBoxes: "Total Boxes",
        totalPurchaseValue: "Total Purchase Value",
        searchProducts: "Search products...",
        showChart: "Show Chart",
        showList: "Show List",
        products: "Products",
        variantDetails: "Variant Details",
        variant: "Variant",
        boxes: "Boxes",
        quantity: "Quantity",
        unitPrice: "Unit Price",
        stockLevel: "Stock Level",
    },
    bn: {
        languageLabel: "বাংলা",
        dashboardTitle: "ড্যাশবোর্ড",
        totalSuppliers: "মোট সরবরাহকারী",
        activeSuppliers: "সিস্টেমে সক্রিয় সরবরাহকারী",
        supplier: "সরবরাহকারী",
        id: "আইডি",
        topDepositSuppliers: "শীর্ষ আমানত সরবরাহকারী",
        totalShops: "মোট দোকান",
        registeredShops: "সিস্টেমে নিবন্ধিত দোকান",
        shop: "দোকান",
        salesOverview: "বিক্রয় ওভারভিউ",
        previousMonth: "পূর্ববর্তী মাস",
        nextMonth: "পরবর্তী মাস",
        salesBreakdown: "বিক্রয় বিভাজন",
        paid: "প্রদত্ত",
        due: "বাকি",
        totalSales: "মোট বিক্রয়",
        paidAmount: "প্রদত্ত পরিমাণ",
        dueAmount: "বাকি পরিমাণ",
        profitAndLoss: "লাভ ও ক্ষতি",
        profit: "লাভ",
        loss: "ক্ষতি",
        inventoryStock: "ইনভেন্টরি স্টক",
        totalQuantity: "মোট পরিমাণ",
        totalValue: "মোট মূল্য",
        totalProducts: "মোট পণ্য",
        totalBoxes: "মোট বক্স",
        totalPurchaseValue: "মোট ক্রয় মূল্য",
        searchProducts: "পণ্য অনুসন্ধান করুন...",
        showChart: "চার্ট দেখান",
        showList: "তালিকা দেখান",
        products: "পণ্য",
        variantDetails: "ভেরিয়েন্ট বিবরণ",
        variant: "ভেরিয়েন্ট",
        boxes: "বক্স",
        quantity: "পরিমাণ",
        unitPrice: "একক দাম",
        stockLevel: "স্টক লেভেল",
    },
};

// Reactive states
const currentLanguage = ref(localStorage.getItem("language") || "en");
const loading = ref(false);
const animatedSuppliersCount = ref(0);
const animatedDeposits = ref([]);
const animatedWidths = ref([]);
const animatedTotalSales = ref(0);
const animatedPaidAmount = ref(0);
const animatedDueAmount = ref(0);
const animatedProfit = ref(0);
const animatedLoss = ref(0);
const animatedTotalShops = ref(0);
const animatedInventoryStock = reactive({});
const selectedMonth = ref(props.month - 1);
const selectedYear = ref(props.year);

// Translation function
const t = computed(() => (key) => translations[currentLanguage.value][key]);

// Function to convert numbers to Bengali
const toBengaliNumber = (num) => {
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string") return num;
    if (currentLanguage.value !== "bn") return num;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
};

// Change language
const changeLanguage = (lang) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

// Navigation functions
const navigateToPreviousMonth = () => {
    let newMonth = selectedMonth.value - 1;
    let newYear = selectedYear.value;
    if (newMonth < 0) {
        newMonth = 11;
        newYear--;
    }
    selectedMonth.value = newMonth;
    selectedYear.value = newYear;
    navigateToMonth(newMonth, newYear);
};

const navigateToNextMonth = () => {
    let newMonth = selectedMonth.value + 1;
    let newYear = selectedYear.value;
    if (newMonth > 11) {
        newMonth = 0;
        newYear++;
    }
    selectedMonth.value = newMonth;
    selectedYear.value = newYear;
    navigateToMonth(newMonth, newYear);
};

const navigateToMonth = (month, year) => {
    loading.value = true;
    router.visit(`/dashboard?month=${month + 1}&year=${year}`, {
        preserveScroll: true,
        onSuccess: (page) => {
            const { total_sales, paid_amount, due_amount, profit, loss } =
                page.props.monthlySales;
            const duration = 1000;
            animateNumber(
                animatedTotalSales,
                total_sales || 0,
                duration,
                "totalSales"
            );
            animateNumber(
                animatedPaidAmount,
                paid_amount || 0,
                duration,
                "paidAmount"
            );
            animateNumber(
                animatedDueAmount,
                due_amount || 0,
                duration,
                "dueAmount"
            );
            animateNumber(animatedProfit, profit || 0, duration, "profit");
            animateNumber(animatedLoss, loss || 0, duration, "loss");
        },
        onError: (errors) => {
            console.error("Navigation error:", errors);
            alert(
                "Failed to load data for the selected month. Please try again."
            );
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

// Animation function
const animateNumber = (refVar, targetValue, duration, context = "unknown") => {
    if (!refVar || typeof refVar !== "object" || !("value" in refVar)) {
        console.error(
            `Invalid refVar in ${context}: Must be a Vue ref object`,
            refVar
        );
        return;
    }
    if (isNaN(targetValue)) {
        console.warn(`Invalid targetValue in ${context}:`, targetValue);
        targetValue = 0;
    }
    let startValue = refVar.value;
    const stepTime = 20;
    const steps = duration / stepTime;
    const increment = (targetValue - startValue) / steps;

    const timer = setInterval(() => {
        startValue += increment;
        if (
            (increment > 0 && startValue >= targetValue) ||
            (increment < 0 && startValue <= targetValue)
        ) {
            refVar.value = targetValue;
            clearInterval(timer);
        } else {
            refVar.value = Math.ceil(startValue);
        }
    }, stepTime);
};

// Animate inventory stock
const animateInventoryStock = (inventory, duration) => {
    Object.keys(animatedInventoryStock).forEach((key) => {
        delete animatedInventoryStock[key];
    });

    inventory.forEach((item) => {
        if (
            !item.product_name ||
            typeof item.product_name !== "string" ||
            isNaN(item.total_quantity) ||
            isNaN(item.total_value)
        ) {
            console.warn(`Invalid inventory item:`, item);
            return;
        }

        animatedInventoryStock[item.product_name] = {
            total_quantity: 0,
            total_value: 0,
        };

        const targetQuantity = item.total_quantity || 0;
        const targetValue = item.total_value || 0;

        let currentQuantity = 0;
        let currentValue = 0;

        const stepTime = 20;
        const steps = duration / stepTime;
        const quantityIncrement = targetQuantity / steps;
        const valueIncrement = targetValue / steps;

        const timer = setInterval(() => {
            currentQuantity += quantityIncrement;
            currentValue += valueIncrement;

            if (currentQuantity >= targetQuantity) {
                animatedInventoryStock[item.product_name].total_quantity =
                    targetQuantity;
                animatedInventoryStock[item.product_name].total_value =
                    targetValue;
                clearInterval(timer);
            } else {
                animatedInventoryStock[item.product_name].total_quantity =
                    Math.ceil(currentQuantity);
                animatedInventoryStock[item.product_name].total_value =
                    Math.ceil(currentValue);
            }
        }, stepTime);
    });
};

const selectedMonthYear = computed(() => {
    const date = new Date(selectedYear.value, selectedMonth.value);
    return date.toLocaleString(currentLanguage.value, {
        month: "long",
        year: "numeric",
    });
});

onMounted(() => {
    const duration = 2000;

    document.documentElement.lang = currentLanguage.value;

    animateNumber(
        animatedSuppliersCount,
        props.suppliersCount || props.suppliers.length || 0,
        duration,
        "suppliersCount"
    );

    animatedWidths.value = new Array(props.topDeposits.length).fill(0);
    animatedDeposits.value = new Array(props.topDeposits.length).fill(0);

    props.topDeposits.forEach((deposit, index) => {
        let startDeposit = 0;
        const targetDeposit = deposit.total_remaining_deposit || 0;
        const targetWidth = getBarWidth(deposit.total_remaining_deposit);
        const incrementDeposit = targetDeposit / (duration / 20);
        const widthIncrement = targetWidth / (duration / 20);

        const timer = setInterval(() => {
            startDeposit += incrementDeposit;
            if (startDeposit >= targetDeposit) {
                animatedDeposits.value[index] = targetDeposit;
                animatedWidths.value[index] = targetWidth;
                clearInterval(timer);
            } else {
                animatedDeposits.value[index] = Math.ceil(startDeposit);
                animatedWidths.value[index] = Math.min(
                    Math.ceil(startDeposit * widthIncrement),
                    100
                );
            }
        }, 20);
    });

    const { total_sales, paid_amount, due_amount, profit, loss } =
        props.monthlySales || {};
    if (
        typeof total_sales !== "number" ||
        typeof paid_amount !== "number" ||
        typeof due_amount !== "number" ||
        typeof profit !== "number" ||
        typeof loss !== "number"
    ) {
        console.warn("Invalid monthlySales data:", props.monthlySales);
    } else {
        animateNumber(animatedTotalSales, total_sales, duration, "totalSales");
        animateNumber(animatedPaidAmount, paid_amount, duration, "paidAmount");
        animateNumber(animatedDueAmount, due_amount, duration, "dueAmount");
        animateNumber(animatedProfit, profit, duration, "profit");
        animateNumber(animatedLoss, loss, duration, "loss");
    }

    animateNumber(
        animatedTotalShops,
        props.shops.length || 0,
        duration,
        "totalShops"
    );

    animateInventoryStock(props.inventoryStock, duration);
});

watch(
    () => props.monthlySales,
    (newVal) => {
        const duration = 1000;
        if (
            typeof newVal.total_sales !== "number" ||
            typeof newVal.paid_amount !== "number" ||
            typeof newVal.due_amount !== "number" ||
            typeof newVal.profit !== "number" ||
            typeof newVal.loss !== "number"
        ) {
            console.warn("Invalid monthlySales data in watch:", newVal);
            return;
        }
        animateNumber(
            animatedTotalSales,
            newVal.total_sales,
            duration,
            "totalSales"
        );
        animateNumber(
            animatedPaidAmount,
            newVal.paid_amount,
            duration,
            "paidAmount"
        );
        animateNumber(
            animatedDueAmount,
            newVal.due_amount,
            duration,
            "dueAmount"
        );
        animateNumber(animatedProfit, newVal.profit, duration, "profit");
        animateNumber(animatedLoss, newVal.loss, duration, "loss");
    },
    { deep: true }
);

watch(
    () => props.inventoryStock,
    (newVal) => {
        const duration = 1000;
        animateInventoryStock(newVal, duration);
    },
    { deep: true }
);

const getBarWidth = (amount) => {
    const maxAmount = Math.max(
        ...props.topDeposits.map((d) => d.total_remaining_deposit || 0),
        1
    );
    const width = (amount / maxAmount) * 100;
    return Math.min(Math.max(width, 10), 100);
};
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

.bangla-font h1,
.bangla-font h2,
.bangla-font h3,
.bangla-font h4,
.bangla-font p,
.bangla-font span,
.bangla-font button,
.bangla-font input::placeholder {
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

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

.from-white {
    --tw-gradient-from: #ffffff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(255, 255, 255, 0));
}

.to-gray-50 {
    --tw-gradient-to: #f9fafb;
}
</style>
