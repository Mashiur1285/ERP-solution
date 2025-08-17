<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
    >
        <!-- Toast Notification -->
        <ToastNotification
            :show="showToast"
            :message="toastMessage"
            :type="toastType"
            :t="t"
            @close="closeToast"
        />

        <!-- Confirmation Modal -->
        <SalesConfirmationModal
            :show="showModal"
            :sale-summary="saleSummary"
            :is-loading="isLoading"
            :current-language="currentLanguage"
            :t="t"
            :to-bengali-number="toBengaliNumber"
            @close="closeModal"
            @confirm="confirmSale"
        />

        <!-- Header -->
        <SalesHeader
            :current-language="currentLanguage"
            :t="t"
            @change-language="changeLanguage"
        />

        <!-- Main Form Container -->
        <div
            class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl"
        >
            <!-- Form Header -->
            <div class="flex items-center justify-between mb-8">
                <h2
                    class="text-2xl font-semibold text-gray-800 flex items-center"
                >
                    <div class="p-2 mr-3 bg-indigo-200 rounded-full">
                        <svg
                            class="w-6 h-6 text-indigo-700"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            />
                        </svg>
                    </div>
                    {{ t("createNewSale") }}
                </h2>
            </div>

            <!-- Sale Information Section -->
            <div class="mb-8">
                <SaleInformation
                    :sale-form="saleForm"
                    :shops="props.shops"
                    :suppliers="props.suppliers"
                    :is-submitted="isSubmitted"
                    :current-language="currentLanguage"
                    :t="t"
                    :to-bengali-number="toBengaliNumber"
                    @supplier-change="onSupplierChange"
                />
            </div>

            <!-- Products Section -->
            <div class="mb-8" v-if="saleForm.supplier_id">
                <SaleItemsSection
                    :sale-form="saleForm"
                    :available-products="availableProducts"
                    :include-free-bottles="includeFreeBottles"
                    :is-submitted="isSubmitted"
                    :current-language="currentLanguage"
                    :t="t"
                    :to-bengali-number="toBengaliNumber"
                    :show-toggle="showToggleButton"
                    @add-item="addSaleItem"
                    @remove-item="removeSaleItem"
                    @item-change="handleItemChange"
                    @variant-change="onVariantChange"
                    @toggle-free-bottles="onToggleFreeBottles"
                    @item-input-complete="checkToggleVisibility"
                />
            </div>

            <!-- Total Summary -->
            <div class="mb-8" v-if="saleForm.items.length > 0">
                <SaleTotalSummary
                    :sale-form="saleForm"
                    :sale-summary="saleSummary"
                    :include-free-bottles="includeFreeBottles"
                    :available-products="availableProducts"
                    :current-language="currentLanguage"
                    :t="t"
                    :to-bengali-number="toBengaliNumber"
                />
            </div>

            <!-- Action Buttons -->
            <div
                class="flex justify-end space-x-4 pt-6 border-t border-gray-200"
            >
                <button
                    @click="resetForm"
                    class="px-8 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 flex items-center space-x-2"
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
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                        />
                    </svg>
                    <span>{{ t("resetForm") }}</span>
                </button>
                <button
                    @click="openModal"
                    class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl"
                    :disabled="isLoading || saleForm.items.length === 0"
                >
                    <svg
                        v-if="isLoading"
                        class="w-5 h-5 animate-spin"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                        />
                    </svg>
                    <span>{{
                        isLoading ? t("processing") : t("createSale")
                    }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import ToastNotification from "./partials/salesPartials/ToastNotification.vue";
import SalesConfirmationModal from "./partials/salesPartials/SalesConfirmationModal.vue";
import SalesHeader from "./partials/salesPartials/SalesHeader.vue";
import SaleInformation from "./partials/salesPartials/SaleInformation.vue";
import SaleItemsSection from "./partials/salesPartials/SaleItemsSection.vue";
import SaleTotalSummary from "./partials/salesPartials/ SaleTotalSummary.vue";

interface Shop {
    id: number;
    shop_name: string;
}

interface Supplier {
    id: number;
    company_name: string;
}

interface Product {
    product_id: number;
    product_name: string;
    supplier_id: number;
    supplier_name: string;
    variants: ProductVariant[];
}

interface ProductVariant {
    variant: string;
    purchased_bottles_available: number;
    free_bottles_available: number;
    total_bottles_available: number;
    bottles_per_case: number;
    purchase_rate: number;
    cases_available: number;
    variant_metadata: any;
}

interface SaleItem {
    product_id: number;
    variant: string;
    cases_to_sell: number;
    selling_price_per_case: number;
    bottles_per_case: number;
    purchase_rate: number;
    available_inventory?: any;
    purchase_metadata?: any;
    // Calculated fields
    total_bottles_to_sell?: number;
    selling_price_per_bottle?: number;
}

const props = defineProps<{
    shops: Shop[];
    suppliers: Supplier[];
}>();

defineOptions({
    layout: Layout,
});

// Language handling
const currentLanguage = ref(localStorage.getItem("language") || "en");
const includeFreeBottles = ref(true);
const availableProducts = ref<Product[]>([]);
const showToggleButton = ref(false);

const translations = {
    en: {
        languageLabel: "English",
        salesManagement: "Sales Management",
        createNewSale: "Create New Sale",
        includeFreeBottles: "Include Free Bottles",
        withoutFreeBottles: "Without Free Bottles",
        saleInformation: "Sale Information",
        shopName: "Shop Name",
        selectShop: "Select a shop",
        shopRequired: "Please select a shop",
        supplier: "Supplier",
        selectSupplier: "Select a supplier",
        supplierRequired: "Please select a supplier",
        saleDate: "Sale Date",
        saleDateRequired: "Sale date is required",
        saleItems: "Sale Items",
        addItem: "Add Item",
        removeItem: "Remove Item",
        product: "Product",
        selectProduct: "Select a product",
        productRequired: "Product is required",
        variant: "Variant",
        selectVariant: "Select variant",
        variantRequired: "Variant is required",
        casesToSell: "Cases to Sell",
        casesToSellRequired: "Cases to sell must be greater than 0",
        sellingPricePerCase: "Selling Price per Case (৳)",
        sellingPricePerCaseRequired:
            "Selling price per case must be greater than 0",
        totalBottlesCalculated: "Total Bottles",
        sellingPricePerBottleCalculated: "Selling Price per Bottle",
        calculated: "calculated",
        calculationBreakdown: "Calculation Breakdown",
        inputCases: "Input Cases",
        effectiveBottlesPerCase: "Effective Bottles per Case",
        totalBottlesToSell: "Total Bottles to Sell",
        totalBottlesToSellRequired:
            "Total bottles to sell must be greater than 0",
        sellingPricePerBottle: "Selling Price per Bottle (৳)",
        sellingPricePerBottleRequired:
            "Selling price per bottle must be greater than 0",
        casesNeededCalculated: "Cases Needed",
        sellingPricePerCaseCalculated: "Selling Price per Case",
        freeBottlesPerCase: "Free Bottles per Case",
        extraFreeBottles: "Extra Free Bottles",
        bottlesPerCase: "Bottles per Case",
        availableInventory: "Available Inventory",
        purchasedBottles: "Purchased Bottles",
        freeBottles: "Free Bottles",
        totalAvailable: "Total Available",
        purchaseRate: "Purchase Rate per Bottle",
        purchaseRateInfo: "Purchase Rate Information",
        perBottle: "Per Bottle",
        perCase: "Per Case",
        profit: "Profit",
        totalProfit: "Total Profit",
        totalAmount: "Total Amount",
        totalCases: "Total Cases",
        totalBottles: "Total Bottles",
        saleSummary: "Sale Summary",
        confirmSale: "Confirm Sale",
        confirmSalePrompt: "Are you sure you want to create this sale?",
        cancel: "Cancel",
        confirm: "Confirm",
        resetForm: "Reset Form",
        processing: "Processing...",
        createSale: "Create Sale",
        formReset: "Form has been reset",
        saleSuccess: "Sale created successfully!",
        saleError: "Please fix the following errors",
        insufficientInventory: "Insufficient inventory for this item",
        loadingProducts: "Loading products...",
        noProductsAvailable: "No products available for selected supplier",
        item: "Item",
        itemSummary: "Item Summary",
        totalItems: "Total Items",
        profitAnalysis: "Profit Analysis",
        expectedProfit: "Expected Profit",
        loss: "Loss",
        itemsBreakdown: "Items Breakdown",
        cases: "Cases",
        fromPurchase: "from purchase",
        autoPopulatedFromPurchase: "Auto-populated from purchase data",
        purchaseData: "Purchase Data",
        caseBuyingPrice: "Case Buying Price",
        calculatedCases: "Calculated Cases",
        bottlesBreakdown: "Bottles Breakdown",
        includes: "includes",
        includingFreeBottles: "including free bottles",
        targetBottles: "Target Bottles",
        actualBottles: "Actual Bottles",
        difference: "Difference",
        target: "target",
        bottles: "bottles",
        pricePerBottle: "Price per Bottle",
    },
    bn: {
        languageLabel: "বাংলা",
        salesManagement: "বিক্রয় ব্যবস্থাপনা",
        createNewSale: "নতুন বিক্রয় তৈরি করুন",
        includeFreeBottles: "বিনামূল্যে বোতল অন্তর্ভুক্ত করুন",
        withoutFreeBottles: "বিনামূল্যে বোতল ছাড়া",
        saleInformation: "বিক্রয়ের তথ্য",
        shopName: "দোকানের নাম",
        selectShop: "একটি দোকান নির্বাচন করুন",
        shopRequired: "অনুগ্রহ করে একটি দোকান নির্বাচন করুন",
        supplier: "সরবরাহকারী",
        selectSupplier: "একটি সরবরাহকারী নির্বাচন করুন",
        supplierRequired: "অনুগ্রহ করে একটি সরবরাহকারী নির্বাচন করুন",
        saleDate: "বিক্রয়ের তারিখ",
        saleDateRequired: "বিক্রয়ের তারিখ প্রয়োজন",
        saleItems: "বিক্রয়ের আইটেম",
        addItem: "আইটেম যোগ করুন",
        removeItem: "আইটেম সরান",
        product: "পণ্য",
        selectProduct: "একটি পণ্য নির্বাচন করুন",
        productRequired: "পণ্য প্রয়োজন",
        variant: "ভেরিয়েন্ট",
        selectVariant: "ভেরিয়েন্ট নির্বাচন করুন",
        variantRequired: "ভেরিয়েন্ট প্রয়োজন",
        casesToSell: "বিক্রয়ের কেস",
        casesToSellRequired: "বিক্রয়ের কেস ০-এর বেশি হতে হবে",
        sellingPricePerCase: "কেস প্রতি বিক্রয় মূল্য (৳)",
        sellingPricePerCaseRequired:
            "কেস প্রতি বিক্রয় মূল্য ০-এর বেশি হতে হবে",
        totalBottlesCalculated: "মোট বোতল",
        sellingPricePerBottleCalculated: "বোতল প্রতি বিক্রয় মূল্য",
        calculated: "গণনাকৃত",
        calculationBreakdown: "গণনার বিবরণ",
        inputCases: "ইনপুট কেস",
        effectiveBottlesPerCase: "কার্যকর বোতল প্রতি কেস",
        totalBottlesToSell: "মোট বিক্রয়ের বোতল",
        totalBottlesToSellRequired: "মোট বিক্রয়ের বোতল ০-এর বেশি হতে হবে",
        sellingPricePerBottle: "বোতল প্রতি বিক্রয় মূল্য (৳)",
        sellingPricePerBottleRequired:
            "বোতল প্রতি বিক্রয় মূল্য ০-এর বেশি হতে হবে",
        casesNeededCalculated: "প্রয়োজনীয় কেস",
        sellingPricePerCaseCalculated: "কেস প্রতি বিক্রয় মূল্য",
        freeBottlesPerCase: "কেস প্রতি বিনামূল্যে বোতল",
        extraFreeBottles: "অতিরিক্ত বিনামূল্যে বোতল",
        bottlesPerCase: "কেস প্রতি বোতল",
        availableInventory: "উপলব্ধ ইনভেন্টরি",
        purchasedBottles: "ক্রয়কৃত বোতল",
        freeBottles: "বিনামূল্যে বোতল",
        totalAvailable: "মোট উপলব্ধ",
        purchaseRate: "বোতল প্রতি ক্রয় হার",
        purchaseRateInfo: "ক্রয় হারের তথ্য",
        perBottle: "প্রতি বোতল",
        perCase: "প্রতি কেস",
        profit: "লাভ",
        totalProfit: "মোট লাভ",
        totalAmount: "মোট পরিমাণ",
        totalCases: "মোট কেস",
        totalBottles: "মোট বোতল",
        saleSummary: "বিক্রয় সারাংশ",
        confirmSale: "বিক্রয় নিশ্চিত করুন",
        confirmSalePrompt: "আপনি কি নিশ্চিতভাবে এই বিক্রয়টি তৈরি করতে চান?",
        cancel: "বাতিল",
        confirm: "নিশ্চিত করুন",
        resetForm: "ফর্ম রিসেট করুন",
        processing: "প্রক্রিয়াকরণ...",
        createSale: "বিক্রয় তৈরি করুন",
        formReset: "ফর্ম রিসেট করা হয়েছে",
        saleSuccess: "বিক্রয় সফলভাবে তৈরি করা হয়েছে!",
        saleError: "নিম্নলিখিত ত্রুটিগুলি ঠিক করুন",
        insufficientInventory: "এই আইটেমের জন্য অপর্যাপ্ত ইনভেন্টরি",
        loadingProducts: "পণ্য লোড হচ্ছে...",
        noProductsAvailable: "নির্বাচিত সরবরাহকারীর জন্য কোন পণ্য উপলব্ধ নেই",
        item: "আইটেম",
        itemSummary: "আইটেম সারাংশ",
        totalItems: "মোট আইটেম",
        profitAnalysis: "লাভ বিশ্লেষণ",
        expectedProfit: "প্রত্যাশিত লাভ",
        loss: "ক্ষতি",
        itemsBreakdown: "আইটেম বিবরণ",
        cases: "কেস",
        fromPurchase: "ক্রয় থেকে",
        autoPopulatedFromPurchase: "ক্রয় ডেটা থেকে স্বয়ংক্রিয়ভাবে পূরণ",
        purchaseData: "ক্রয় ডেটা",
        caseBuyingPrice: "কেস ক্রয় মূল্য",
        calculatedCases: "গণনাকৃত কেস",
        bottlesBreakdown: "বোতল বিবরণ",
        includes: "অন্তর্ভুক্ত",
        includingFreeBottles: "বিনামূল্যে বোতল সহ",
        targetBottles: "লক্ষ্য বোতল",
        actualBottles: "প্রকৃত বোতল",
        difference: "পার্থক্য",
        target: "লক্ষ্য",
        bottles: "বোতল",
        pricePerBottle: "বোতল প্রতি মূল্য",
    },
};

const saleForm = ref({
    shop_id: "",
    supplier_id: "",
    sale_date: new Date().toISOString().split("T")[0],
    include_free_bottles: true,
    items: [] as SaleItem[],
});

const isSubmitted = ref(false);
const isLoading = ref(false);
const showModal = ref(false);
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success");

// Helper function to safely convert values to numbers
const safeNumber = (value: any): number => {
    const num = Number(value);
    return isNaN(num) ? 0 : num;
};

// Check if toggle button should be visible
const checkToggleVisibility = () => {
    const hasValidItem = saleForm.value.items.some(
        (item) =>
            item.product_id &&
            item.variant &&
            safeNumber(item.cases_to_sell) > 0 &&
            safeNumber(item.selling_price_per_case) > 0
    );
    showToggleButton.value = hasValidItem;
};

// Watch for changes in form items to check toggle visibility
watch(() => saleForm.value.items, checkToggleVisibility, { deep: true });

// Computed properties for summary with corrected logic
const saleSummary = computed(() => {
    if (!saleForm.value.items || !Array.isArray(saleForm.value.items)) {
        return {
            totalCases: 0,
            totalAmount: 0,
            totalProfit: 0,
            totalBottlesToSell: 0,
            totalActualBottles: 0,
            totalPurchasedBottles: 0,
            totalFreeBottles: 0,
            itemCount: 0,
        };
    }

    const items = saleForm.value.items;
    let totalCases = 0;
    let totalAmount = 0;
    let totalProfit = 0;
    let totalBottlesToSell = 0;
    let totalActualBottles = 0;
    let totalPurchasedBottles = 0;
    let totalFreeBottles = 0;

    items.forEach((item) => {
        const cases = safeNumber(item.cases_to_sell);
        const pricePerCase = safeNumber(item.selling_price_per_case);
        const bottlesPerCase = safeNumber(item.bottles_per_case);
        const purchaseRate = safeNumber(item.purchase_rate);

        if (
            !cases ||
            !pricePerCase ||
            !bottlesPerCase ||
            !item.purchase_metadata
        )
            return;

        const freeBottlesPerCase = safeNumber(
            item.purchase_metadata.free_bottles_per_case
        );

        // Calculate the actual selling price per bottle (always based on with free bottles)
        const effectiveBottlesPerCaseWithFree =
            bottlesPerCase + freeBottlesPerCase;
        const actualSellingPricePerBottle =
            pricePerCase / effectiveBottlesPerCaseWithFree;

        totalCases += cases;

        if (includeFreeBottles.value) {
            // WITH free bottles calculation
            const totalBottles = cases * effectiveBottlesPerCaseWithFree;
            const purchasedBottles = cases * bottlesPerCase;
            const freeBottles = cases * freeBottlesPerCase;

            totalBottlesToSell += totalBottles;
            totalActualBottles += totalBottles;
            totalPurchasedBottles += purchasedBottles;
            totalFreeBottles += freeBottles;

            // Calculate total amount and profit
            const itemAmount = totalBottles * actualSellingPricePerBottle;
            const purchaseCost = totalBottles * purchaseRate;

            totalAmount += itemAmount;
            totalProfit += itemAmount - purchaseCost;
        } else {
            // WITHOUT free bottles calculation
            const totalBottles = cases * bottlesPerCase; // Only purchased bottles

            totalBottlesToSell += totalBottles;
            totalActualBottles += totalBottles;
            totalPurchasedBottles += totalBottles;

            // Calculate total amount and profit using the SAME selling price per bottle
            const itemAmount = totalBottles * actualSellingPricePerBottle;
            const purchaseCost = totalBottles * purchaseRate;

            totalAmount += itemAmount;
            totalProfit += itemAmount - purchaseCost;
        }
    });

    return {
        totalCases,
        totalAmount,
        totalProfit,
        totalBottlesToSell,
        totalActualBottles,
        totalPurchasedBottles,
        totalFreeBottles,
        itemCount: items.length,
    };
});

// Translation function
const t = (key: string, params: Record<string, any> = {}) => {
    let translation = translations[currentLanguage.value][key] || key;
    for (const [param, value] of Object.entries(params)) {
        translation = translation.replace(`{${param}}`, value);
    }
    return translation;
};

// Utility function to convert numbers to Bengali digits
const toBengaliNumber = (num: number | string): string => {
    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return String(num).replace(
        /[0-9]/g,
        (digit) => bengaliDigits[parseInt(digit)]
    );
};

// Language change handler
const changeLanguage = (lang: string) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
};

// Toggle free bottles handler
const onToggleFreeBottles = () => {
    includeFreeBottles.value = !includeFreeBottles.value;
    saleForm.value.include_free_bottles = includeFreeBottles.value;
};

// Supplier change handler
const onSupplierChange = async () => {
    if (!saleForm.value.supplier_id) {
        availableProducts.value = [];
        return;
    }

    try {
        const response = await fetch(
            `/api/products-by-supplier?supplier_id=${saleForm.value.supplier_id}`
        );
        const data = await response.json();
        availableProducts.value = data.products || [];
    } catch (error) {
        console.error("Error fetching products:", error);
        showToast.value = true;
        toastMessage.value = "Error loading products";
        toastType.value = "error";
    }
};

// Variant change handler
const onVariantChange = async (
    itemIndex: number,
    productId: number,
    variant: string
) => {
    try {
        const response = await fetch(
            `/api/variant-inventory?product_id=${productId}&variant=${variant}`
        );
        const inventory = await response.json();

        // Get the purchase metadata from the product's variant data
        const product = availableProducts.value.find(
            (p) => p.product_id === productId
        );
        const variantData = product?.variants.find(
            (v) => v.variant === variant
        );

        if (saleForm.value.items[itemIndex]) {
            const item = saleForm.value.items[itemIndex];
            item.bottles_per_case = safeNumber(inventory.bottles_per_case);
            item.purchase_rate = safeNumber(inventory.purchase_rate);
            item.available_inventory = inventory;

            // Store purchase metadata for free bottles calculation
            if (variantData && variantData.variant_metadata) {
                item.purchase_metadata = variantData.variant_metadata;
            }
        }
    } catch (error) {
        console.error("Error fetching variant inventory:", error);
    }
};

// Item change handler
const handleItemChange = (index: number, field: string, value: any) => {
    if (field !== "calculated" && saleForm.value.items[index]) {
        saleForm.value.items[index][field] = value;
        checkToggleVisibility(); // Check if toggle should be shown
    }
};

// Add sale item handler
const addSaleItem = () => {
    saleForm.value.items.push({
        product_id: 0,
        variant: "",
        cases_to_sell: 0,
        selling_price_per_case: 0,
        bottles_per_case: 0,
        purchase_rate: 0,
        available_inventory: null,
        purchase_metadata: null,
        total_bottles_to_sell: 0,
        selling_price_per_bottle: 0,
    });
};

// Remove sale item handler
const removeSaleItem = (index: number) => {
    if (saleForm.value.items.length > 1) {
        saleForm.value.items.splice(index, 1);
    }
    checkToggleVisibility(); // Check if toggle should be shown after removal
};

// Form reset handler
const resetForm = () => {
    saleForm.value = {
        shop_id: "",
        supplier_id: "",
        sale_date: new Date().toISOString().split("T")[0],
        include_free_bottles: true,
        items: [],
    };
    availableProducts.value = [];
    isSubmitted.value = false;
    showToggleButton.value = false;
    includeFreeBottles.value = true;
    showToast.value = true;
    toastMessage.value = "formReset";
    toastType.value = "success";
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

// Open confirmation modal
const openModal = () => {
    isSubmitted.value = true;

    // Validate form
    const isFormValid =
        saleForm.value.shop_id &&
        saleForm.value.supplier_id &&
        saleForm.value.sale_date &&
        saleForm.value.items.length > 0 &&
        saleForm.value.items.every(
            (item) =>
                item.product_id &&
                item.variant &&
                safeNumber(item.cases_to_sell) > 0 &&
                safeNumber(item.selling_price_per_case) > 0
        );

    if (!isFormValid) {
        showToast.value = true;
        toastMessage.value = "saleError";
        toastType.value = "error";
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
        return;
    }

    showModal.value = true;
};

// Close confirmation modal
const closeModal = () => {
    showModal.value = false;
};

// Close toast notification
const closeToast = () => {
    showToast.value = false;
};

// Confirm sale handler
const confirmSale = () => {
    isLoading.value = true;

    // Prepare sale data
    const saleData = {
        ...saleForm.value,
        items: saleForm.value.items.map((item) => {
            const cases = safeNumber(item.cases_to_sell);
            const pricePerCase = safeNumber(item.selling_price_per_case);
            const bottlesPerCase = safeNumber(item.bottles_per_case);
            const freeBottlesPerCase = safeNumber(
                item.purchase_metadata?.free_bottles_per_case
            );

            // ALWAYS calculate actual selling price per bottle from the "with free bottles" scenario
            const effectiveBottlesPerCaseWithFree =
                bottlesPerCase + freeBottlesPerCase;
            const actualSellingPricePerBottle =
                pricePerCase / effectiveBottlesPerCaseWithFree;

            let targetBottles: number;

            if (includeFreeBottles.value) {
                // WITH free bottles: cases × (bottles + free bottles)
                targetBottles = cases * effectiveBottlesPerCaseWithFree;
            } else {
                // WITHOUT free bottles: cases × bottles only
                targetBottles = cases * bottlesPerCase;
            }

            return {
                product_id: item.product_id,
                variant: item.variant,
                total_bottles_to_sell: targetBottles,
                selling_price_per_bottle: actualSellingPricePerBottle, // ALWAYS use the consistent price
                free_bottles_per_case: freeBottlesPerCase,
            };
        }),
    };

    router.post("/sales/store", saleData, {
        onSuccess: () => {
            showModal.value = false;
            isLoading.value = false;
            resetForm();
            showToast.value = true;
            toastMessage.value = "saleSuccess";
            toastType.value = "success";
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
        onError: (errors: any) => {
            showModal.value = false;
            isLoading.value = false;
            showToast.value = true;
            toastMessage.value = `saleError: ${Object.values(errors).join(
                ", "
            )}`;
            toastType.value = "error";
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
    });
};

// Initialize form
onMounted(() => {
    addSaleItem(); // Add initial item
});
</script>

<style scoped>
.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

input:focus,
select:focus {
    outline: none;
    transform: translateY(-1px);
}

select {
    background-image: none;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

.dot {
    transition: transform 0.3s ease;
}
</style>
