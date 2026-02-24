```vue
<template>
    <div
        class="p-4 bg-gray-100 min-h-screen"
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

        <CategoryModal
            v-if="showCategoryModal"
            @close="showCategoryModal = false"
            @submit="handleCategoryCreate"
        />

        <BrandModal
            v-if="showBrandModal"
            @close="showBrandModal = false"
            @submit="handleBrandCreate"
        />

        <div
            v-if="showSupplierModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 p-4"
        >
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">
                        {{ t("addSupplier") }}
                    </h3>
                    <button
                        class="p-2 rounded-full hover:bg-gray-100"
                        @click="showSupplierModal = false"
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
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="text-sm font-medium text-gray-700">
                        {{ t("companyName") }}*
                        <input
                            v-model="supplierQuickForm.company_name"
                            class="mt-1 w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                    </label>
                    <label class="text-sm font-medium text-gray-700">
                        {{ t("phoneNumber") }}*
                        <input
                            v-model="supplierQuickForm.phone_number"
                            class="mt-1 w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                    </label>
                    <label class="text-sm font-medium text-gray-700 md:col-span-2">
                        {{ t("address") }}*
                        <input
                            v-model="supplierQuickForm.address"
                            class="mt-1 w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                    </label>
                    <label class="text-sm font-medium text-gray-700">
                        {{ t("branchName") }}
                        <input
                            v-model="supplierQuickForm.branch_name"
                            class="mt-1 w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                    </label>
                    <label class="text-sm font-medium text-gray-700">
                        {{ t("emergencyPhoneNumber") }}
                        <input
                            v-model="supplierQuickForm.emergency_phone_number"
                            class="mt-1 w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        />
                    </label>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <button
                        class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"
                        @click="showSupplierModal = false"
                    >
                        {{ t("cancel") }}
                    </button>
                    <button
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow"
                        @click="handleSupplierCreate"
                    >
                        {{ t("save") }}
                    </button>
                </div>
            </div>
        </div>

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

        <!-- POS Style Two-Column Layout -->
        <div class="flex gap-4 items-start">
            <!-- LEFT SIDE: Form Area -->
            <div class="flex-1 min-w-0 space-y-4">
                <!-- Product Information Section -->
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <div class="flex items-center mb-4">
                        <div class="p-2 mr-3 bg-indigo-100 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ t("addNewPurchase") }}</h2>
                    </div>
                    <ProductInformation
                        :product-form="productForm"
                        :suppliers="suppliersList"
                        :categories="categoriesList"
                        :brands="brandsList"
                        :is-submitted="isSubmitted"
                        :current-language="currentLanguage"
                        :t="t"
                        :to-bengali-number="toBengaliNumber"
                        @add-category="showCategoryModal = true"
                        @add-brand="showBrandModal = true"
                        @add-supplier="showSupplierModal = true"
                    />
                </div>

                <!-- Variants Section -->
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <VariantsSection
                        :product-form="productForm"
                        :is-submitted="isSubmitted"
                        :current-language="currentLanguage"
                        :t="t"
                        :to-bengali-number="toBengaliNumber"
                        :get-variant-total-bottles="getVariantTotalBottles"
                        :get-variant-total-free-bottles="getVariantTotalFreeBottles"
                        :get-variant-total-cost="getVariantTotalCost"
                        :get-actual-rate-per-bottle="getActualRatePerBottle"
                        :get-actual-rate-per-case="getActualRatePerCase"
                        :get-cases-with-free-bottles="getCasesWithFreeBottles"
                        :get-cases-without-free-bottles="getCasesWithoutFreeBottles"
                        :get-extra-free-bottles="getExtraFreeBottles"
                        :variant-remaining-deposit="variantRemainingDeposit"
                        :variant-options="variantOptions"
                        @add-variant="addVariant"
                        @remove-variant="removeVariant"
                        @variant-change="handleVariantChange"
                    />
                </div>
            </div>

            <!-- RIGHT SIDE: Invoice Panel -->
            <div class="w-[400px] flex-shrink-0 sticky top-24">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <!-- Invoice Header -->
                    <div class="bg-indigo-600 text-white px-5 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-lg font-bold">{{ t("purchaseSummary") }}</h3>
                            </div>
                            <span class="text-xs bg-indigo-500 px-2 py-1 rounded-full">{{ currentDate }}</span>
                        </div>
                        <p v-if="productForm.product_name" class="text-indigo-200 text-sm mt-1">{{ productForm.product_name }}</p>
                    </div>

                    <!-- Invoice Body -->
                    <div class="px-5 py-4 max-h-[calc(100vh-280px)] overflow-y-auto">
                        <!-- Empty State -->
                        <div v-if="productForm.variants.every(v => !v.number_of_cases || !v.case_buying_price)" class="text-center py-8 text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm">{{ t("emptyInvoice") }}</p>
                        </div>

                        <!-- Variant Line Items -->
                        <div v-else>
                            <!-- Table Header -->
                            <div class="grid grid-cols-12 gap-1 text-xs font-semibold text-gray-500 uppercase tracking-wider pb-2 border-b border-gray-200 mb-2">
                                <div class="col-span-4">{{ t("variant") }}</div>
                                <div class="col-span-2 text-center">{{ t("numberOfCases") }}</div>
                                <div class="col-span-3 text-center">{{ t("bottlesPerCase") }}</div>
                                <div class="col-span-3 text-right">{{ t("totalCost") }}</div>
                            </div>

                            <!-- Line Items -->
                            <div
                                v-for="(variant, index) in productForm.variants"
                                :key="index"
                                class="py-2 border-b border-dashed border-gray-100 last:border-0"
                            >
                                <div v-if="variant.number_of_cases && variant.case_buying_price" class="grid grid-cols-12 gap-1 text-sm">
                                    <div class="col-span-4">
                                        <p class="font-medium text-gray-800">{{ variant.variant || t("variant") + ' ' + (index + 1) }}</p>
                                        <p class="text-xs text-gray-400">৳{{ currentLanguage === 'bn' ? toBengaliNumber(variant.case_buying_price) : variant.case_buying_price }}/{{ t("case") }}</p>
                                    </div>
                                    <div class="col-span-2 text-center text-gray-700">
                                        {{ currentLanguage === 'bn' ? toBengaliNumber(variant.number_of_cases) : variant.number_of_cases }}
                                    </div>
                                    <div class="col-span-3 text-center text-gray-700">
                                        {{ currentLanguage === 'bn' ? toBengaliNumber(getVariantTotalBottles(variant)) : getVariantTotalBottles(variant) }}
                                        <span v-if="getVariantTotalFreeBottles(variant) > 0" class="text-xs text-green-500 block">
                                            +{{ currentLanguage === 'bn' ? toBengaliNumber(getVariantTotalFreeBottles(variant)) : getVariantTotalFreeBottles(variant) }} {{ t("free") }}
                                        </span>
                                    </div>
                                    <div class="col-span-3 text-right font-semibold text-gray-800">
                                        ৳{{ currentLanguage === 'bn' ? toBengaliNumber(getVariantTotalCost(variant).toFixed(2)) : getVariantTotalCost(variant).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Dotted Separator -->
                            <div class="border-t-2 border-dotted border-gray-300 my-3"></div>

                            <!-- Summary Rows -->
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t("totalVariants") }}</span>
                                    <span class="font-medium">{{ currentLanguage === 'bn' ? toBengaliNumber(productForm.variants.filter(v => v.number_of_cases && v.case_buying_price).length) : productForm.variants.filter(v => v.number_of_cases && v.case_buying_price).length }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t("purchasedCases") }}</span>
                                    <span class="font-medium">{{ currentLanguage === 'bn' ? toBengaliNumber(totalPurchasedCases) : totalPurchasedCases.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t("casesFromFree") }}</span>
                                    <span class="font-medium text-green-600">+{{ currentLanguage === 'bn' ? toBengaliNumber(totalCasesFromFree) : totalCasesFromFree.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t("totalNumberOfCases") }}</span>
                                    <span class="font-bold text-gray-800">{{ currentLanguage === 'bn' ? toBengaliNumber(totalCases) : totalCases.toLocaleString() }}</span>
                                </div>

                                <div class="border-t border-gray-200 pt-2"></div>

                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t("purchasedBottles") }}</span>
                                    <span class="font-medium">{{ currentLanguage === 'bn' ? toBengaliNumber(totalPurchasedBottles) : totalPurchasedBottles.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t("freeBottles") }}</span>
                                    <span class="font-medium text-green-600">+{{ currentLanguage === 'bn' ? toBengaliNumber(totalFreeBottles) : totalFreeBottles.toLocaleString() }}</span>
                                </div>
                                <div v-if="totalExtraFreeBottles > 0" class="flex justify-between text-gray-600">
                                    <span>{{ t("extraFreeBottles") }}</span>
                                    <span class="font-medium text-teal-600">+{{ currentLanguage === 'bn' ? toBengaliNumber(totalExtraFreeBottles) : totalExtraFreeBottles.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t("totalBottles") }}</span>
                                    <span class="font-bold text-gray-800">{{ currentLanguage === 'bn' ? toBengaliNumber(totalBottles) : totalBottles.toLocaleString() }}</span>
                                </div>
                            </div>

                            <!-- Grand Total -->
                            <div class="border-t-2 border-dotted border-gray-300 mt-3 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-800">{{ t("totalCost") }}</span>
                                    <span class="text-xl font-bold text-indigo-600">
                                        ৳{{ currentLanguage === 'bn' ? toBengaliNumber(totalCost.toFixed(2)) : totalCost.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Remaining Deposit -->
                            <div v-if="productForm.supplier_id" class="mt-3 p-3 rounded-lg" :class="remainingDepositAfterPurchase >= 0 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-xs text-gray-500">{{ t("remainingDeposit") }}</p>
                                        <p class="text-xs text-gray-400">{{ t("afterPurchase") }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold" :class="remainingDepositAfterPurchase >= 0 ? 'text-green-600' : 'text-red-600'">
                                            ৳{{ currentLanguage === 'bn' ? toBengaliNumber(remainingDepositAfterPurchase.toFixed(2)) : remainingDepositAfterPurchase.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                        </p>
                                        <p class="text-xs" :class="remainingDepositAfterPurchase >= 0 ? 'text-green-500' : 'text-red-500'">
                                            {{ remainingDepositAfterPurchase >= 0 ? t("sufficient") : t("insufficient") }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Footer: Action Buttons -->
                    <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 space-y-2">
                        <button
                            @click="openModal"
                            class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center justify-center space-x-2 shadow-md"
                            :disabled="isLoading"
                        >
                            <svg v-if="isLoading" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span>{{ isLoading ? t("processing") : t("addPurchase") }}</span>
                        </button>
                        <button
                            @click="resetForm"
                            class="w-full py-2.5 border-2 border-gray-300 rounded-lg text-gray-600 font-medium hover:bg-gray-100 hover:border-gray-400 transition-all duration-300 flex items-center justify-center space-x-2 text-sm"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>{{ t("resetForm") }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import ToastNotification from "./Partials/PurchasePartials/ToastNotification.vue";
import ConfirmationModal from "./Partials/PurchasePartials/ConfirmationModal.vue";
import Header from "./Partials/PurchasePartials/Header.vue";
import ProductInformation from "./Partials/PurchasePartials/ProductInformation.vue";
import VariantsSection from "./Partials/PurchasePartials/VariantsSection.vue";
import CategoryModal from "../Category/Partials/CategoryModal.vue";
import BrandModal from "../Brand/Partials/BrandModal.vue";

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
const currentDate = new Date().toLocaleDateString(currentLanguage.value === 'bn' ? 'bn-BD' : 'en-US', { year: 'numeric', month: 'short', day: 'numeric' });
const csrfToken =
    (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)
        ?.content || "";

const categoriesList = ref([...props.categories]);
const brandsList = ref([...props.brands]);
const suppliersList = ref([...props.suppliers]);

const showCategoryModal = ref(false);
const showBrandModal = ref(false);
const showSupplierModal = ref(false);

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
            "Purchase amount ({totalCost} BDT) exceeds supplier's remaining deposit ({deposit} BDT)",
        currency: "BDT",
        noResults: "No results found",
        quickCreateSuccess: "Created successfully",
        quickCreateError: "Could not create item",
        addSupplier: "Add Supplier",
        companyName: "Company Name",
        phoneNumber: "Phone Number",
        address: "Address",
        branchName: "Branch Name",
        emergencyPhoneNumber: "Emergency Phone",
        cancel: "Cancel",
        save: "Save",
        emptyInvoice: "Add variants to see invoice",
        free: "free",
        case: "case",
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
            "ক্রয়ের পরিমাণ ({totalCost} টাকা) সরবরাহকারীর বাকি আমানত ({deposit} টাকা) অতিক্রম করেছে",
        currency: "টাকা",
        noResults: "কোন ফলাফল পাওয়া যায়নি",
        quickCreateSuccess: "সফলভাবে তৈরি হয়েছে",
        quickCreateError: "তৈরি করা যায়নি",
        addSupplier: "সরবরাহকারী যোগ করুন",
        companyName: "কোম্পানির নাম",
        phoneNumber: "ফোন নম্বর",
        address: "ঠিকানা",
        branchName: "শাখার নাম",
        emergencyPhoneNumber: "জরুরি ফোন",
        cancel: "বাতিল",
        save: "সংরক্ষণ",
        emptyInvoice: "ইনভয়েস দেখতে ভেরিয়েন্ট যোগ করুন",
        free: "ফ্রি",
        case: "কেস",
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
const supplierQuickForm = ref({
    company_name: "",
    branch_name: "",
    phone_number: "",
    emergency_phone_number: "",
    address: "",
    email: "",
    country: "",
    city: "",
    website: "",
    notes: "",
});

const pushToast = (message: string, type: "success" | "error" = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => (showToast.value = false), 3000);
};

const postJson = async (url: string, payload: Record<string, any>) => {
    const response = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(payload),
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        const validationMessage =
            data?.message ||
            (data?.errors &&
                Object.values(data.errors)
                    .flat()
                    .join(", "));
        throw new Error(validationMessage || "Request failed");
    }

    return data;
};

const handleCategoryCreate = async (data: {
    name: string;
    description: string | null;
}) => {
    try {
        const result = await postJson("/categories/quick-store", data);
        categoriesList.value.push(result.category);
        productForm.value.category_id = result.category.id.toString();
        showCategoryModal.value = false;
        pushToast(t("quickCreateSuccess"), "success");
    } catch (error) {
        pushToast(error.message || t("quickCreateError"), "error");
    }
};

const handleBrandCreate = async (data: {
    brand_name: string;
    description: string | null;
}) => {
    try {
        const payload = { name: data.brand_name, description: data.description };
        const result = await postJson("/brands/quick-store", payload);
        brandsList.value.push(result.brand);
        productForm.value.brand_id = result.brand.id.toString();
        showBrandModal.value = false;
        pushToast(t("quickCreateSuccess"), "success");
    } catch (error) {
        pushToast(error.message || t("quickCreateError"), "error");
    }
};

const handleSupplierCreate = async () => {
    try {
        const result = await postJson(
            "/suppliers/quick-store",
            supplierQuickForm.value
        );
        suppliersList.value.push({
            ...result.supplier,
            remaining_deposit: result.supplier.remaining_deposit || 0,
        });
        productForm.value.supplier_id = result.supplier.id.toString();
        showSupplierModal.value = false;
        pushToast(t("quickCreateSuccess"), "success");
        supplierQuickForm.value = {
            company_name: "",
            branch_name: "",
            phone_number: "",
            emergency_phone_number: "",
            address: "",
            email: "",
            country: "",
            city: "",
            website: "",
            notes: "",
        };
    } catch (error) {
        pushToast(error.message || t("quickCreateError"), "error");
    }
};

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
    if (num === null || num === undefined || num === "") return "";
    
    // Round decimals to 2 places if it's a number or a numeric string
    let n = Number(num);
    if (!isNaN(n) && n % 1 !== 0) {
        num = n.toFixed(2);
    } else if (!isNaN(n)) {
        num = n.toString();
    }

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
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
