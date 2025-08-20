
<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
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
        <ConfirmationModal
            :show="showModal"
            :total-bottles="totalBottles"
            :total-variants="productForm.variants.length"
            :total-cases="totalCases"
            :total-cost="totalCost"
            :total-purchased-bottles="totalPurchasedBottles"
            :total-free-bottles="totalFreeBottles"
            :total-extra-free-bottles="totalExtraFreeBottles"
            :total-purchased-cases="totalPurchasedCases"
            :total-cases-from-free="totalCasesFromFree"
            :remaining-deposit-after-purchase="remainingDepositAfterPurchase"
            :is-loading="isLoading"
            :current-language="currentLanguage"
            :t="t"
            :to-bengali-number="toBengaliNumber"
            @close="closeModal"
            @confirm="confirmPurchase"
        />

        <!-- Header -->
        <Header
            :current-language="currentLanguage"
            :translations="translations"
            :t="t"
            @change-language="changeLanguage"
        />

        <!-- Main Form Container -->
        <div
            class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden"
        >
            <!-- Background Pattern -->
            <svg
                class="absolute inset-0 w-full h-full opacity-5 pointer-events-none"
                viewBox="0 0 1440 320"
                preserveAspectRatio="none"
            >
                <path
                    fill="#4f46e5"
                    fill-opacity="0.1"
                    d="M0,160L48,138.7C96,117,192,75,288,80C384,85,480,139,576,149.3C672,160,768,128,864,106.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                />
            </svg>

            <div class="relative z-10">
                <!-- Form Header -->
                <div class="flex items-center justify-between mb-8">
                    <h2
                        class="text-2xl font-semibold text-gray-800 flex items-center"
                    >
                        <div
                            class="p-2 mr-3 bg-indigo-200 rounded-full flex items-center justify-center"
                        >
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
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                        </div>
                        {{ t("addNewPurchase") }}
                    </h2>
                </div>

                <!-- Product Information Section -->
                <div class="mb-8">
                    <ProductInformation
                        :product-form="productForm"
                        :suppliers="props.suppliers"
                        :categories="props.categories"
                        :brands="props.brands"
                        :is-submitted="isSubmitted"
                        :current-language="currentLanguage"
                        :t="t"
                        :to-bengali-number="toBengaliNumber"
                    />
                </div>

                <!-- Variants Section -->
                <div class="mb-8">
                    <VariantsSection
                        :product-form="productForm"
                        :is-submitted="isSubmitted"
                        :current-language="currentLanguage"
                        :t="t"
                        :to-bengali-number="toBengaliNumber"
                        :get-variant-total-bottles="getVariantTotalBottles"
                        :get-variant-total-free-bottles="
                            getVariantTotalFreeBottles
                        "
                        :get-variant-total-cost="getVariantTotalCost"
                        :get-actual-rate-per-bottle="getActualRatePerBottle"
                        :get-actual-rate-per-case="getActualRatePerCase"
                        :get-cases-with-free-bottles="getCasesWithFreeBottles"
                        :get-cases-without-free-bottles="
                            getCasesWithoutFreeBottles
                        "
                        :get-extra-free-bottles="getExtraFreeBottles"
                        :variant-remaining-deposit="variantRemainingDeposit"
                        :variant-options="variantOptions"
                        @add-variant="addVariant"
                        @remove-variant="removeVariant"
                        @variant-change="handleVariantChange"
                    />
                </div>

                <!-- Total Summary -->
                <div class="mb-8">
                    <TotalSummary
                        :product-form="productForm"
                        :total-bottles="totalBottles"
                        :total-variants="productForm.variants.length"
                        :total-cases="totalCases"
                        :total-purchased-cases="totalPurchasedCases"
                        :total-purchased-bottles="totalPurchasedBottles"
                        :total-free-bottles="totalFreeBottles"
                        :total-cases-from-free="totalCasesFromFree"
                        :total-extra-free-bottles="totalExtraFreeBottles"
                        :total-cost="totalCost"
                        :remaining-deposit-after-purchase="
                            remainingDepositAfterPurchase
                        "
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
                        :disabled="isLoading"
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
                            isLoading ? t("processing") : t("addPurchase")
                        }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
// import ToastNotification from "./partials/purchasePartials/ToastNotification.vue";
// import ConfirmationModal from "./partials/purchasePartials/ConfirmationModal.vue";
import Header from "./partials/purchasePartials/Header.vue";
import ProductInformation from "./partials/purchasePartials/ProductInformation.vue";
import VariantsSection from "./partials/purchasePartials/VariantsSection.vue";
import TotalSummary from "./partials/purchasePartials/TotalSummary.vue";

interface Supplier {
    id: number;
    company_name: string;
    remaining_deposit: number;
}

interface Category {
    id: number;
    name: string;
}

interface Brand {
    id: number;
    brand_name: string;
}

interface ProductVariant {
    variant: string;
    number_of_cases: number;
    case_buying_price: number;
    bottles_per_case: number;
    free_bottles_per_case: number;
}

interface VariantOption {
    value: string;
    label: string;
    bottles_per_case: number;
}

const props = defineProps<{
    suppliers: Supplier[];
    categories: Category[];
    brands: Brand[];
}>();

defineOptions({
    layout: Layout,
});

// Language handling
const currentLanguage = ref(localStorage.getItem("language") || "en");

// Variant options with predefined bottle counts
const variantOptions: VariantOption[] = [
    { value: "250ml", label: "250 ml", bottles_per_case: 24 },
    { value: "500ml", label: "500 ml", bottles_per_case: 24 },
    { value: "1000ml", label: "1000 ml", bottles_per_case: 12 },
    { value: "2000ml", label: "2000 ml", bottles_per_case: 6 },
];

const translations = {
    en: {
        languageLabel: "English",
        purchaseManagement: "Purchase Management",
        addNewPurchase: "Add New Purchase",
        productInformation: "Product Information",
        productName: "Product Name",
        enterProductName: "Enter product name",
        productNameRequired: "Product name is required",
        category: "Category",
        selectCategory: "Select a category",
        categoryRequired: "Please select a category",
        brand: "Brand",
        selectBrand: "Select a brand",
        brandRequired: "Please select a brand",
        supplier: "Supplier",
        selectSupplier: "Select a supplier",
        supplierRequired: "Please select a supplier",
        remainingDepositNote:
            "Remaining deposit amount is shown in parentheses",
        productVariants: "Product Variants",
        addVariant: "Add Variant",
        removeVariant: "Remove Variant",
        variant: "Variant",
        variantName: "Variant Size",
        selectVariant: "Select variant size",
        variantNameRequired: "Variant {index}: Size is required",
        numberOfCases: "Number of Cases",
        numberOfCasesRequired:
            "Variant {index}: Number of cases must be greater than 0",
        caseBuyingPrice: "Case Buying Price",
        caseBuyingPriceRequired:
            "Variant {index}: Case buying price must be greater than 0",
        bottlesPerCase: "Bottles per Case",
        bottlesPerCaseRequired:
            "Variant {index}: Bottles per case must be greater than 0",
        freeBottlesPerCase: "Free Bottles per Case",
        casesWithFreeBottles: "Cases with Free Bottles",
        casesWithoutFreeBottles: "Cases without Free Bottles",
        extraFreeBottles: "Extra Free Bottles",
        totalBottlesInVariant: "Total Bottles",
        totalCasesInVariant: "Total Cases",
        totalFreeBottles: "Total Free Bottles",
        totalCost: "Total Cost",
        totalCases: "Total Cases",
        totalNumberOfCases: "Total Number of Cases",
        totalBottles: "Total Bottles",
        purchasedPlusFree: "Purchased + Free",
        bottlesBreakdown: "Bottles Breakdown",
        casesBreakdown: "Cases Breakdown",
        purchasedBottles: "Purchased Bottles",
        freeBottles: "Free Bottles",
        purchasedCases: "Purchased Cases",
        casesFromFree: "Cases from Free",
        actualRatePerBottle: "Actual Rate per Bottle",
        actualRatePerCase: "Actual Rate per Case",
        remainingDeposit: "Remaining Deposit",
        afterPurchase: "After Purchase",
        insufficientFunds: "Insufficient Funds",
        availableBalance: "Available Balance",
        avgCostPerBottle: "Avg Cost per Bottle",
        avgCostPerCase: "Avg Cost per Case",
        avgBottlesPerCase: "Avg Bottles per Case",
        freeBottlesBreakdown: "Free Bottles Breakdown",
        costAnalysis: "Cost Analysis",
        afterThisVariant: "After This Variant",
        sufficient: "Sufficient",
        insufficient: "Insufficient",
        purchaseSummary: "Purchase Summary",
        totalVariants: "Total Variants",
        confirmPurchase: "Confirm Purchase",
        confirmPurchasePrompt: "Are you sure you want to add this purchase?",
        cancel: "Cancel",
        confirm: "Confirm",
        resetForm: "Reset Form",
        processing: "Processing...",
        addPurchase: "Add Purchase",
        formReset: "Form has been reset",
        purchaseSuccess: "Purchase added successfully!",
        purchaseError: "Please fix the following errors: {errors}",
        insufficientDeposit:
            "Purchase amount (৳{totalCost}) exceeds supplier's remaining deposit (৳{deposit})",
    },
    bn: {
        languageLabel: "বাংলা",
        purchaseManagement: "ক্রয় ব্যবস্থাপনা",
        addNewPurchase: "নতুন ক্রয় যোগ করুন",
        productInformation: "পণ্যের তথ্য",
        productName: "পণ্যের নাম",
        enterProductName: "পণ্যের নাম লিখুন",
        productNameRequired: "পণ্যের নাম প্রয়োজন",
        category: "বিভাগ",
        selectCategory: "একটি বিভাগ নির্বাচন করুন",
        categoryRequired: "অনুগ্রহ করে একটি বিভাগ নির্বাচন করুন",
        brand: "ব্র্যান্ড",
        selectBrand: "একটি ব্র্যান্ড নির্বাচন করুন",
        brandRequired: "অনুগ্রহ করে একটি ব্র্যান্ড নির্বাচন করুন",
        supplier: "সরবরাহকারী",
        selectSupplier: "একটি সরবরাহকারী নির্বাচন করুন",
        supplierRequired: "অনুগ্রহ করে একটি সরবরাহকারী নির্বাচন করুন",
        remainingDepositNote: "বাকি আমানতের পরিমাণ বন্ধনীতে দেখানো হয়েছে",
        productVariants: "পণ্যের ভেরিয়েন্ট",
        addVariant: "ভেরিয়েন্ট যোগ করুন",
        removeVariant: "ভেরিয়েন্ট সরান",
        variant: "ভেরিয়েন্ট",
        variantName: "ভেরিয়েন্টের আকার",
        selectVariant: "ভেরিয়েন্টের আকার নির্বাচন করুন",
        variantNameRequired: "ভেরিয়েন্ট {index}: আকার প্রয়োজন",
        numberOfCases: "কেস সংখ্যা",
        numberOfCasesRequired:
            "ভেরিয়েন্ট {index}: কেস সংখ্যা ০-এর বেশি হতে হবে",
        caseBuyingPrice: "কেস ক্রয় মূল্য",
        caseBuyingPriceRequired:
            "ভেরিয়েন্ট {index}: কেস ক্রয় মূল্য ০-এর বেশি হতে হবে",
        bottlesPerCase: "কেস প্রতি বোতল",
        bottlesPerCaseRequired:
            "ভেরিয়েন্ট {index}: কেস প্রতি বোতল ০-এর বেশি হতে হবে",
        freeBottlesPerCase: "কেস প্রতি বিনামূল্যে বোতল",
        casesWithFreeBottles: "বিনামূল্যে বোতল সহ কেস",
        casesWithoutFreeBottles: "বিনামূল্যে বোতল ছাড়া কেস",
        extraFreeBottles: "অতিরিক্ত বিনামূল্যে বোতল",
        totalBottlesInVariant: "মোট বোতল",
        totalCasesInVariant: "মোট কেস",
        totalFreeBottles: "মোট বিনামূল্যে বোতল",
        totalCost: "মোট খরচ",
        totalCases: "মোট কেস",
        totalNumberOfCases: "মোট কেস সংখ্যা",
        totalBottles: "মোট বোতল",
        purchasedPlusFree: "ক্রয়কৃত + বিনামূল্যে",
        bottlesBreakdown: "বোতলের বিবরণ",
        casesBreakdown: "কেসের বিবরণ",
        purchasedBottles: "ক্রয়কৃত বোতল",
        freeBottles: "বিনামূল্যে বোতল",
        purchasedCases: "ক্রয়কৃত কেস",
        casesFromFree: "বিনামূল্যে থেকে কেস",
        actualRatePerBottle: "প্রকৃত হার প্রতি বোতল",
        actualRatePerCase: "প্রকৃত হার প্রতি কেস",
        remainingDeposit: "বাকি আমানত",
        afterPurchase: "ক্রয়ের পর",
        insufficientFunds: "অপর্যাপ্ত তহবিল",
        availableBalance: "উপলব্ধ ব্যালেন্স",
        avgCostPerBottle: "গড় খরচ প্রতি বোতল",
        avgCostPerCase: "গড় খরচ প্রতি কেস",
        avgBottlesPerCase: "গড় বোতল প্রতি কেস",
        freeBottlesBreakdown: "বিনামূল্যে বোতলের বিবরণ",
        costAnalysis: "খরচ বিশ্লেষণ",
        afterThisVariant: "এই ভেরিয়েন্টের পর",
        sufficient: "যথেষ্ট",
        insufficient: "অপর্যাপ্ত",
        purchaseSummary: "ক্রয় সারাংশ",
        totalVariants: "মোট ভেরিয়েন্ট",
        confirmPurchase: "ক্রয় নিশ্চিত করুন",
        confirmPurchasePrompt: "আপনি কি নিশ্চিতভাবে এই ক্রয়টি যোগ করতে চান?",
        cancel: "বাতিল",
        confirm: "নিশ্চিত করুন",
        resetForm: "ফর্ম রিসেট করুন",
        processing: "প্রক্রিয়াকরণ...",
        addPurchase: "ক্রয় যোগ করুন",
        formReset: "ফর্ম রিসেট করা হয়েছে",
        purchaseSuccess: "ক্রয় সফলভাবে যোগ করা হয়েছে!",
        purchaseError: "নিম্নলিখিত ত্রুটিগুলি ঠিক করুন: {errors}",
        insufficientDeposit:
            "ক্রয়ের পরিমাণ (৳{totalCost}) সরবরাহকারীর বাকি আমানত (৳{deposit}) অতিক্রম করেছে",
    },
};

const productForm = ref({
    product_name: "",
    category_id: "",
    brand_id: "",
    supplier_id: "",
    variants: [
        {
            variant: "",
            number_of_cases: 0,
            case_buying_price: 0,
            bottles_per_case: 0,
            free_bottles_per_case: 0,
        },
    ],
});

const isSubmitted = ref(false);
const isLoading = ref(false);
const showModal = ref(false);
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success");

// Helper functions for calculations
const getVariantTotalBottles = (variant: ProductVariant) => {
    const purchasedBottles =
        (variant.number_of_cases || 0) * (variant.bottles_per_case || 0);
    const freeBottles = getVariantTotalFreeBottles(variant);
    return purchasedBottles + freeBottles;
};

const getVariantTotalFreeBottles = (variant: ProductVariant) => {
    return (
        (variant.number_of_cases || 0) * (variant.free_bottles_per_case || 0)
    );
};

const getVariantTotalCost = (variant: ProductVariant) => {
    return (variant.number_of_cases || 0) * (variant.case_buying_price || 0);
};

const getActualRatePerBottle = (variant: ProductVariant) => {
    const totalBottles = getVariantTotalBottles(variant);
    const totalCost = getVariantTotalCost(variant);
    if (totalBottles === 0) return "0.00";
    return (totalCost / totalBottles).toFixed(2);
};

const getActualRatePerCase = (variant: ProductVariant) => {
    return (variant.case_buying_price || 0).toFixed(2);
};

const getCasesWithFreeBottles = (variant: ProductVariant) => {
    if (variant.free_bottles_per_case && variant.free_bottles_per_case > 0) {
        const totalFreeBottles = getVariantTotalFreeBottles(variant);
        return Math.floor(totalFreeBottles / (variant.bottles_per_case || 1));
    }
    return 0;
};

const getExtraFreeBottles = (variant: ProductVariant) => {
    if (variant.free_bottles_per_case && variant.free_bottles_per_case > 0) {
        const totalFreeBottles = getVariantTotalFreeBottles(variant);
        const completeCasesFromFreeBottles = getCasesWithFreeBottles(variant);
        return (
            totalFreeBottles -
            completeCasesFromFreeBottles * (variant.bottles_per_case || 1)
        );
    }
    return 0;
};

const getCasesWithoutFreeBottles = (variant: ProductVariant) => {
    const casesWithFreeBottles = getCasesWithFreeBottles(variant);
    return (variant.number_of_cases || 0) - casesWithFreeBottles;
};

// Computed properties for summary
const totalBottles = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) => sum + getVariantTotalBottles(variant),
        0
    );
});

const totalPurchasedBottles = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) =>
            sum +
            (variant.number_of_cases || 0) * (variant.bottles_per_case || 0),
        0
    );
});

const totalFreeBottles = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) => sum + getVariantTotalFreeBottles(variant),
        0
    );
});

const totalExtraFreeBottles = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) => sum + getExtraFreeBottles(variant),
        0
    );
});

const totalCases = computed(() => {
    return productForm.value.variants.reduce((sum, variant) => {
        const purchasedCases = variant.number_of_cases || 0;
        const freeCases = getCasesWithFreeBottles(variant);
        return sum + purchasedCases + freeCases;
    }, 0);
});

const totalPurchasedCases = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) => sum + (variant.number_of_cases || 0),
        0
    );
});

const totalCasesFromFree = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) => sum + getCasesWithFreeBottles(variant),
        0
    );
});

const totalCost = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) => sum + getVariantTotalCost(variant),
        0
    );
});

const selectedSupplier = computed(() => {
    return props.suppliers.find(
        (supplier) => supplier.id === Number(productForm.value.supplier_id)
    );
});

const remainingDepositAfterPurchase = computed(() => {
    if (!selectedSupplier.value) return 0;
    return selectedSupplier.value.remaining_deposit - totalCost.value;
});

const variantRemainingDeposit = (index: number) => {
    if (!selectedSupplier.value) return 0;
    let totalCostUpToIndex = 0;
    for (let i = 0; i <= index; i++) {
        const variant = productForm.value.variants[i];
        totalCostUpToIndex += getVariantTotalCost(variant);
    }
    return selectedSupplier.value.remaining_deposit - totalCostUpToIndex;
};

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

// Variant change handler
const handleVariantChange = (index: number, variantValue: string) => {
    const selectedVariant = variantOptions.find(
        (option) => option.value === variantValue
    );
    if (selectedVariant) {
        productForm.value.variants[index].variant = variantValue;
        productForm.value.variants[index].bottles_per_case =
            selectedVariant.bottles_per_case;
    }
};

// Form reset handler
const resetForm = () => {
    productForm.value = {
        product_name: "",
        category_id: "",
        brand_id: "",
        supplier_id: "",
        variants: [
            {
                variant: "",
                number_of_cases: 0,
                case_buying_price: 0,
                bottles_per_case: 0,
                free_bottles_per_case: 0,
            },
        ],
    };
    isSubmitted.value = false;
    showToast.value = true;
    toastMessage.value = "formReset";
    toastType.value = "success";
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

// Add variant handler
const addVariant = () => {
    productForm.value.variants.push({
        variant: "",
        number_of_cases: 0,
        case_buying_price: 0,
        bottles_per_case: 0,
        free_bottles_per_case: 0,
    });
};

// Remove variant handler
const removeVariant = (index: number) => {
    if (productForm.value.variants.length > 1) {
        productForm.value.variants.splice(index, 1);
    }
};

// Open confirmation modal
const openModal = () => {
    isSubmitted.value = true;

    // Validate form
    const isFormValid =
        productForm.value.product_name &&
        productForm.value.category_id &&
        productForm.value.brand_id &&
        productForm.value.supplier_id &&
        productForm.value.variants.every(
            (variant) =>
                variant.variant &&
                variant.number_of_cases > 0 &&
                variant.case_buying_price > 0 &&
                variant.bottles_per_case > 0 &&
                variant.free_bottles_per_case >= 0
        );

    if (!isFormValid) {
        showToast.value = true;
        toastMessage.value = t("purchaseError", {
            errors: t("purchaseError"),
        });
        toastType.value = "error";
        setTimeout(() => {
            showToast.value = false;
        }, 3000);
        return;
    }

    // Check if purchase amount exceeds supplier's deposit
    if (remainingDepositAfterPurchase.value < 0) {
        showToast.value = true;
        toastMessage.value = t("insufficientDeposit", {
            totalCost: toBengaliNumber(totalCost.value.toFixed(2)),
            deposit: toBengaliNumber(
                selectedSupplier.value?.remaining_deposit.toFixed(2) || "0.00"
            ),
        });
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

// Confirm purchase handler
const confirmPurchase = () => {
    isLoading.value = true;
    const purchaseData = {
        product_name: productForm.value.product_name,
        category_id: productForm.value.category_id,
        brand_id: productForm.value.brand_id,
        supplier_id: productForm.value.supplier_id,
        total_bottles: totalBottles.value,
        total_purchased_bottles: totalPurchasedBottles.value,
        total_free_bottles: totalFreeBottles.value,
        total_extra_free_bottles: totalExtraFreeBottles.value,
        total_cases: totalCases.value,
        total_purchased_cases: totalPurchasedCases.value,
        total_cases_from_free: totalCasesFromFree.value,
        total_cost: totalCost.value,
        remaining_deposit_after_purchase: remainingDepositAfterPurchase.value,
        variants: productForm.value.variants.map((variant) => ({
            variant: variant.variant,
            number_of_cases: variant.number_of_cases,
            case_buying_price: variant.case_buying_price,
            bottles_per_case: variant.bottles_per_case,
            free_bottles_per_case: variant.free_bottles_per_case,
            total_bottles: getVariantTotalBottles(variant),
            total_free_bottles: getVariantTotalFreeBottles(variant),
            total_cost: getVariantTotalCost(variant),
            actual_rate_per_bottle: parseFloat(getActualRatePerBottle(variant)),
            actual_rate_per_case: parseFloat(getActualRatePerCase(variant)),
            cases_with_free_bottles: getCasesWithFreeBottles(variant),
            cases_without_free_bottles: getCasesWithoutFreeBottles(variant),
            extra_free_bottles: getExtraFreeBottles(variant),
        })),
    };

    router.post("/products-store", purchaseData, {
        onSuccess: () => {
            showModal.value = false;
            isLoading.value = false;
            resetForm();
            showToast.value = true;
            toastMessage.value = "purchaseSuccess";
            toastType.value = "success";
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
        onError: (errors: any) => {
            showModal.value = false;
            isLoading.value = false;
            showToast.value = true;
            toastMessage.value = t("purchaseError", {
                errors: Object.values(errors).join(", "),
            });
            toastType.value = "error";
            setTimeout(() => {
                showToast.value = false;
            }, 3000);
        },
    });
};
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
</style>
```
