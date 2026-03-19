<template>
    <div
        class="min-h-screen bg-gray-100 flex flex-col"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Toast -->
        <ToastNotification
            :show="showToast"
            :message="toastMessage"
            :type="toastType"
            :t="t"
            @close="showToast = false"
        />

        <!-- Confirmation Modal -->
        <SalesConfirmationModal
            :show="showModal"
            :sale-summary="saleSummary"
            :is-loading="isLoading"
            :current-language="currentLanguage"
            :t="t"
            :to-bengali-number="toBengaliNumber"
            @close="showModal = false"
            @confirm="confirmSale"
        />

        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-400 border-b border-orange-600 px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 shadow-sm">
            <h1 class="text-lg sm:text-xl font-semibold text-white flex items-center gap-2">
                <div class="p-1.5 bg-white/20 rounded-lg flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                {{ t('salesManagement') }}
            </h1>
            <div class="flex gap-2 self-start sm:self-auto">
                <button
                    @click="changeLanguage('en')"
                    :class="['px-3 py-1.5 rounded text-sm font-medium transition-colors', currentLanguage === 'en' ? 'bg-white text-orange-600' : 'bg-white/20 text-white hover:bg-white/30']"
                >English</button>
                <button
                    @click="changeLanguage('bn')"
                    :class="['px-3 py-1.5 rounded text-sm font-medium transition-colors', currentLanguage === 'bn' ? 'bg-white text-orange-600' : 'bg-white/20 text-white hover:bg-white/30']"
                >বাংলা</button>
            </div>
        </div>

        <!-- POS Layout -->
        <div class="flex flex-col lg:flex-row flex-1 gap-4 p-4 min-h-0">

            <!-- LEFT: Product search + cart -->
            <div class="flex-1 space-y-4 overflow-y-auto">

                <!-- Products -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ t('searchProduct') }}
                    </label>
                    <div class="relative mb-3">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg v-if="!productLoading" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-orange-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            @input="onSearchInput"
                            type="text"
                            :placeholder="t('typeToSearch')"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition"
                        />
                    </div>
                    <!-- Product Chips -->
                    <div v-if="productLoading" class="flex flex-wrap gap-2">
                        <div class="h-8 w-24 bg-gray-100 rounded-lg animate-pulse"></div>
                        <div class="h-8 w-32 bg-gray-100 rounded-lg animate-pulse"></div>
                        <div class="h-8 w-20 bg-gray-100 rounded-lg animate-pulse"></div>
                        <div class="h-8 w-28 bg-gray-100 rounded-lg animate-pulse"></div>
                    </div>
                    <div v-else-if="productList.length === 0" class="text-center text-gray-400 text-sm py-3">
                        {{ t('noProductsFound') }}
                    </div>
                    <div v-else class="flex flex-wrap gap-2 max-h-[120px] overflow-y-auto pr-1">
                        <button
                            v-for="p in productList"
                            :key="p.product_id"
                            @click="selectProduct(p)"
                            :class="['px-3 py-1.5 rounded-lg border text-sm font-medium transition-all',
                                pendingProduct?.product_id === p.product_id
                                    ? 'bg-orange-500 border-orange-500 text-white shadow-sm'
                                    : cartItems.some(c => c.product_id === p.product_id)
                                        ? 'bg-green-50 border-green-300 text-green-700 hover:bg-green-100'
                                        : 'bg-white border-gray-200 text-gray-700 hover:border-orange-300 hover:text-orange-600'
                            ]"
                        >
                            {{ p.product_name }}
                            <span class="font-normal opacity-70">({{ p.supplier_name }})</span>
                            <span v-if="cartItems.some(c => c.product_id === p.product_id)" class="ml-1 text-xs">✓</span>
                        </button>
                    </div>
                </div>

                <!-- Variant Picker -->
                <div v-if="pendingProduct" class="bg-white rounded-xl shadow-sm border border-orange-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs font-medium text-orange-500 uppercase tracking-wide mb-0.5">{{ t('selectVariants') }}</p>
                            <h3 class="text-sm font-semibold text-gray-800">{{ pendingProduct.product_name }}</h3>
                            <p class="text-xs text-gray-500">{{ pendingProduct.supplier_name }}</p>
                        </div>
                        <button @click="cancelVariantPicker" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <label v-for="v in pendingProduct.variants" :key="v.variant"
                            :class="['flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all select-none',
                                variantSelections[v.variant]
                                    ? 'border-orange-400 bg-orange-50'
                                    : 'border-gray-200 bg-gray-50 hover:border-gray-300 hover:bg-white'
                            ]">
                            <input type="checkbox" v-model="variantSelections[v.variant]" class="w-4 h-4 text-orange-500 rounded" />
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ v.variant }}</p>
                                <p class="text-xs text-gray-400">{{ v.total_bottles_available }} {{ t('bottlesAvailable') }}</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button @click="cancelVariantPicker"
                            class="px-3 py-1.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            {{ t('cancel') }}
                        </button>
                        <button @click="addVariantsToCart" :disabled="!hasCheckedVariants"
                            class="px-4 py-1.5 text-sm font-medium bg-orange-500 text-white rounded-lg hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            {{ t('addToCart') }}
                        </button>
                    </div>
                </div>

                <!-- Cart Items -->
                <div v-if="cartItems.length === 0 && !pendingProduct" class="bg-white rounded-xl border border-dashed border-gray-300 p-10 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-400 text-sm">{{ t('searchToAddProducts') }}</p>
                </div>

                <div v-if="cartItems.length > 0" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[800px] text-sm border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-8">#</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('product') }}</th>
                                    <th class="px-2 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('variant') }}</th>
                                    <th class="px-2 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-20">{{ t('cases') }}</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-16">{{ t('bpc') }}</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide w-14">{{ t('free') }}</th>
                                    <th class="px-2 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-36">{{ t('pricePerCase') }} (৳)</th>
                                    <th class="px-3 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide w-28">{{ t('subtotal') }}</th>
                                    <th class="w-8 px-2 py-2.5"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in cartItems" :key="index"
                                    class="border-b border-gray-100 hover:bg-gray-50 transition-colors last:border-b-0"
                                    :class="{ 'bg-red-50/40': isSubmitted && (!item.selected_variant || !(item.cases > 0) || !(item.price_per_case > 0)) }">

                                    <!-- # -->
                                    <td class="px-3 py-2 text-center">
                                        <span class="w-5 h-5 bg-orange-500 text-white text-xs rounded-full flex items-center justify-center font-bold mx-auto">
                                            {{ index + 1 }}
                                        </span>
                                    </td>

                                    <!-- Product -->
                                    <td class="px-3 py-2">
                                        <p class="font-semibold text-gray-800 text-sm leading-tight">{{ item.product_name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ item.supplier_name }}</p>
                                    </td>

                                    <!-- Variant -->
                                    <td class="px-2 py-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-orange-50 text-orange-700 text-xs font-semibold border border-orange-100">
                                            {{ item.selected_variant }}
                                        </span>
                                    </td>

                                    <!-- Cases -->
                                    <td class="px-2 py-2">
                                        <input
                                            v-model.number="item.cases"
                                            type="number"
                                            min="1"
                                            :max="getMaxCases(item)"
                                            :placeholder="t('cases')"
                                            class="w-full px-2 py-1.5 rounded-md border border-gray-200 text-sm focus:border-orange-400 focus:ring-1 focus:ring-orange-200 outline-none"
                                            :class="{
                                                'border-red-300 bg-red-50': (isSubmitted && !(item.cases > 0)) || itemExceedsStock(item),
                                                'border-orange-300': !itemExceedsStock(item) && item.cases > 0
                                            }"
                                        />
                                        <p v-if="getVariantData(item)" class="text-xs mt-0.5"
                                            :class="itemExceedsStock(item) ? 'text-red-500 font-medium' : 'text-gray-400'">
                                            {{ itemExceedsStock(item) ? t('exceedsStock') : `${getMaxCases(item)} ${t('casesAvailable')}` }}
                                        </p>
                                    </td>

                                    <!-- Btl/Case -->
                                    <td class="px-3 py-2 text-center">
                                        <span class="text-sm font-medium text-gray-700">{{ item.bottles_per_case || '—' }}</span>
                                    </td>

                                    <!-- Free -->
                                    <td class="px-3 py-2 text-center">
                                        <span v-if="item.free_bottles_per_case > 0" class="text-xs font-semibold text-green-600">
                                            +{{ item.free_bottles_per_case }}
                                        </span>
                                        <span v-else class="text-gray-300 text-xs">—</span>
                                    </td>

                                    <!-- Price/Case -->
                                    <td class="px-2 py-2">
                                        <input
                                            v-model.number="item.price_per_case"
                                            type="number"
                                            min="0"
                                            :placeholder="t('pricePerCase')"
                                            class="w-full px-2 py-1.5 rounded-md border border-gray-200 text-sm focus:border-orange-400 focus:ring-1 focus:ring-orange-200 outline-none"
                                            :class="{ 'border-red-300 bg-red-50': isSubmitted && !(item.price_per_case > 0) }"
                                        />
                                    </td>

                                    <!-- Subtotal -->
                                    <td class="px-3 py-2 text-right">
                                        <span v-if="getItemSubtotal(item) > 0" class="text-sm font-bold text-orange-600">
                                            ৳{{ formatNumber(getItemSubtotal(item)) }}
                                        </span>
                                        <span v-else class="text-gray-300 text-xs">৳0.00</span>
                                    </td>

                                    <!-- Remove -->
                                    <td class="px-2 py-2 text-center">
                                        <button
                                            @click="removeCartItem(index)"
                                            class="p-1 rounded text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Sticky invoice panel -->
            <div class="w-full lg:w-72 lg:flex-shrink-0">
                <div class="sticky top-4 space-y-3">

                    <!-- Sale Details card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-3">
                        <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">{{ t('saleDetails') }}</h3>

                        <!-- Shop -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ t('shopName') }}*</label>
                            <select
                                v-model="shopId"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none bg-white"
                                :class="{ 'border-red-300': isSubmitted && !shopId }"
                            >
                                <option value="">{{ t('selectShop') }}</option>
                                <option v-for="shop in shops" :key="shop.id" :value="shop.id">{{ shop.shop_name }}</option>
                            </select>
                        </div>

                        <!-- Suppliers (auto from cart) -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ t('supplier') }}</label>
                            <div v-if="cartSuppliers.length === 0"
                                class="px-3 py-2 rounded-lg border border-dashed border-gray-200 text-xs text-gray-400 text-center">
                                {{ t('suppliersFromCart') }}
                            </div>
                            <div v-else class="flex flex-wrap gap-1.5">
                                <span
                                    v-for="sup in cartSuppliers"
                                    :key="sup.id"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700"
                                >
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    {{ sup.name }}
                                </span>
                            </div>
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ t('saleDate') }}*</label>
                            <input
                                v-model="saleDate"
                                type="date"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none"
                                :class="{ 'border-red-300': isSubmitted && !saleDate }"
                            />
                        </div>

                        <!-- Free bottles toggle -->
                        <div class="flex items-center justify-between py-1">
                            <span class="text-xs font-medium text-gray-600">{{ t('includeFreeBottles') }}</span>
                            <button
                                @click="includeFreeBottles = !includeFreeBottles"
                                :class="['relative inline-flex h-5 w-9 items-center rounded-full transition-colors', includeFreeBottles ? 'bg-orange-500' : 'bg-gray-300']"
                            >
                                <span :class="['inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform', includeFreeBottles ? 'translate-x-4' : 'translate-x-1']" />
                            </button>
                        </div>
                    </div>

                    <!-- Invoice panel -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                        <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2 mb-3">{{ t('saleSummary') }}</h3>

                        <div v-if="cartItems.length === 0" class="text-center text-gray-400 text-xs py-4">
                            {{ t('noItems') }}
                        </div>

                        <div v-else class="space-y-1.5">
                            <!-- Per-item breakdown -->
                            <div v-for="(item, index) in cartItems" :key="index" class="flex justify-between text-xs">
                                <span class="text-gray-600 truncate max-w-[130px]">
                                    {{ item.product_name }}
                                    <span v-if="item.selected_variant" class="text-gray-400">({{ item.selected_variant }})</span>
                                </span>
                                <span class="font-medium text-gray-800 ml-2 flex-shrink-0">
                                    ৳{{ formatNumber(getItemSubtotal(item)) }}
                                </span>
                            </div>

                            <div class="border-t border-gray-100 pt-2 mt-2 space-y-1">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ t('totalCases') }}</span>
                                    <span>{{ saleSummary.totalCases }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ t('totalBottles') }}</span>
                                    <span>{{ saleSummary.totalBottles }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-2 flex justify-between items-center">
                                <span class="text-sm font-semibold text-gray-700">{{ t('totalAmount') }}</span>
                                <span class="text-lg font-bold text-orange-600">৳{{ formatNumber(saleSummary.totalAmount) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <button
                        @click="openModal"
                        :disabled="isLoading || cartItems.length === 0 || anyItemExceedsStock"
                        class="w-full py-3 bg-orange-500 text-white text-sm font-semibold rounded-xl hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2 shadow-sm"
                    >
                        <svg v-if="isLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ isLoading ? t('processing') : t('confirmSale') }}
                    </button>

                    <button
                        @click="resetForm"
                        class="w-full py-2.5 border border-gray-300 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors"
                    >
                        {{ t('resetForm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import ToastNotification from "./partials/salesPartials/ToastNotification.vue";
import SalesConfirmationModal from "./partials/salesPartials/SalesConfirmationModal.vue";

interface Shop {
    id: number;
    shop_name: string;
}

interface Supplier {
    id: number;
    company_name: string;
}

interface ProductVariant {
    variant: string;
    purchased_bottles_available: number;
    free_bottles_available: number;
    total_bottles_available: number;
    bottles_per_case: number;
    purchase_rate: number;
    cases_available: number;
    variant_metadata: Record<string, any>;
}

interface SearchProduct {
    product_id: number;
    product_name: string;
    supplier_id: number;
    supplier_name: string;
    variants: ProductVariant[];
    total_available_bottles: number;
    total_available_cases: number;
}

interface CartItem {
    product_id: number;
    product_name: string;
    supplier_id: number;
    supplier_name: string;
    available_variants: ProductVariant[];
    selected_variant: string;
    bottles_per_case: number;
    free_bottles_per_case: number;
    purchase_rate: number;
    cases: number;
    price_per_case: number;
}

const props = defineProps<{
    shops: Shop[];
    suppliers: Supplier[];
}>();

defineOptions({ layout: Layout });

// Language
const currentLanguage = ref(localStorage.getItem("language") || "en");

const translations: Record<string, Record<string, string>> = {
    en: {
        salesManagement: "Sales Management",
        searchProduct: "Search Product",
        typeToSearch: "Type product name to search...",
        searchMoreProducts: "Search more products from this supplier...",
        noProductsFound: "No products found",
        variants: "variants",
        bottlesAvailable: "bottles available",
        searchToAddProducts: "Search a product above to start adding items",
        supplier: "Supplier",
        autoDetected: "auto-detected",
        product: "Product",
        variant: "Variant",
        selectVariant: "Select variant",
        cases: "Cases",
        case: "case",
        free: "free",
        bpc: "Btl/Case",
        subtotal: "Subtotal",
        pricePerCase: "Price / Case",
        avail: "avail",
        shopName: "Shop Name",
        selectShop: "Select a shop",
        saleDate: "Sale Date",
        includeFreeBottles: "Include Free Bottles",
        saleDetails: "Sale Details",
        saleSummary: "Sale Summary",
        noItems: "No items added yet",
        totalCases: "Total Cases",
        totalBottles: "Total Bottles",
        totalAmount: "Total Amount",
        confirmSale: "Confirm Sale",
        processing: "Processing...",
        resetForm: "Reset",
        confirmSalePrompt: "Are you sure you want to create this sale?",
        cancel: "Cancel",
        confirm: "Confirm",
        totalItems: "Total Items",
        profit: "Profit",
        loss: "Loss",
        expectedProfit: "Expected Profit",
        formReset: "Form has been reset",
        saleSuccess: "Sale created successfully!",
        saleError: "Please fill in all required fields",
        supplierRequired: "Please select a supplier",
        productAlreadyInCart: "This product is already in the cart",
        selectSupplier: "Select a supplier",
        moreFromSupplier: "More from",
        typeToSearchAll: "Or type to search all suppliers",
        sameSupplier: "same supplier",
        searchAllOrPickFromSupplier: "Search all products or pick from supplier below",
        suppliersFromCart: "Auto-filled from selected products",
        selectVariants: "Select Variants",
        addToCart: "Add to Cart",
        casesAvailable: "cases available",
        exceedsStock: "Exceeds available stock",
    },
    bn: {
        salesManagement: "বিক্রয় ব্যবস্থাপনা",
        searchProduct: "পণ্য খুঁজুন",
        typeToSearch: "পণ্যের নাম লিখুন...",
        searchMoreProducts: "এই সরবরাহকারীর আরও পণ্য খুঁজুন...",
        noProductsFound: "কোনো পণ্য পাওয়া যায়নি",
        variants: "ভেরিয়েন্ট",
        bottlesAvailable: "বোতল উপলব্ধ",
        searchToAddProducts: "পণ্য যোগ করতে উপরে সার্চ করুন",
        supplier: "সরবরাহকারী",
        autoDetected: "স্বয়ংক্রিয়",
        product: "পণ্য",
        variant: "ভেরিয়েন্ট",
        selectVariant: "ভেরিয়েন্ট নির্বাচন করুন",
        cases: "কেস",
        case: "কেস",
        free: "ফ্রি",
        bpc: "বোতল/কেস",
        subtotal: "সাবটোটাল",
        pricePerCase: "কেস প্রতি মূল্য",
        avail: "উপলব্ধ",
        shopName: "দোকানের নাম",
        selectShop: "দোকান নির্বাচন করুন",
        saleDate: "বিক্রয়ের তারিখ",
        includeFreeBottles: "বিনামূল্যে বোতল",
        saleDetails: "বিক্রয়ের তথ্য",
        saleSummary: "বিক্রয় সারাংশ",
        noItems: "কোনো আইটেম যোগ করা হয়নি",
        totalCases: "মোট কেস",
        totalBottles: "মোট বোতল",
        totalAmount: "মোট পরিমাণ",
        confirmSale: "বিক্রয় নিশ্চিত করুন",
        processing: "প্রক্রিয়াকরণ...",
        resetForm: "রিসেট",
        confirmSalePrompt: "আপনি কি নিশ্চিতভাবে এই বিক্রয়টি তৈরি করতে চান?",
        cancel: "বাতিল",
        confirm: "নিশ্চিত করুন",
        totalItems: "মোট আইটেম",
        profit: "লাভ",
        loss: "ক্ষতি",
        expectedProfit: "প্রত্যাশিত লাভ",
        formReset: "ফর্ম রিসেট করা হয়েছে",
        saleSuccess: "বিক্রয় সফলভাবে তৈরি করা হয়েছে!",
        saleError: "অনুগ্রহ করে সব তথ্য পূরণ করুন",
        supplierRequired: "সরবরাহকারী নির্বাচন করুন",
        productAlreadyInCart: "এই পণ্যটি ইতিমধ্যে কার্টে আছে",
        selectSupplier: "সরবরাহকারী নির্বাচন করুন",
        moreFromSupplier: "এই সরবরাহকারী থেকে আরও",
        typeToSearchAll: "অথবা সব সরবরাহকারী সার্চ করুন",
        sameSupplier: "একই সরবরাহকারী",
        searchAllOrPickFromSupplier: "সব পণ্য সার্চ করুন অথবা নিচে থেকে বেছে নিন",
        suppliersFromCart: "পণ্য যোগ করলে সরবরাহকারী দেখাবে",
        selectVariants: "ভেরিয়েন্ট নির্বাচন করুন",
        addToCart: "কার্টে যোগ করুন",
        casesAvailable: "কেস উপলব্ধ",
        exceedsStock: "উপলব্ধ স্টকের বেশি",
    },
};

const t = (key: string, _params?: Record<string, any>) => translations[currentLanguage.value]?.[key] ?? key;

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

const changeLanguage = (lang: string) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

const formatNumber = (value: number, decimals = 2): string => {
    return Number(value || 0).toLocaleString("en-US", {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
};

// Returns today's date in local timezone as YYYY-MM-DD (avoids UTC offset bug)
const localDateString = () => {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
};

const safeNumber = (v: any): number => {
    const n = Number(v);
    return isNaN(n) ? 0 : n;
};

// Form state
const shopId = ref<number | string>("");
const saleDate = ref(localDateString());
const includeFreeBottles = ref(true);
const cartItems = ref<CartItem[]>([]);

// Search / product list state
const searchQuery = ref("");
const productList = ref<SearchProduct[]>([]);
const productLoading = ref(false);
let searchDebounceTimer: ReturnType<typeof setTimeout> | null = null;

// Unique suppliers currently in the cart
const cartSuppliers = computed(() => {
    const seen = new Set<number>();
    const list: { id: number; name: string }[] = [];
    for (const item of cartItems.value) {
        if (!seen.has(item.supplier_id)) {
            seen.add(item.supplier_id);
            list.push({ id: item.supplier_id, name: item.supplier_name });
        }
    }
    return list;
});

// Primary supplier for backend (first item's supplier)
const primarySupplierId = computed(() =>
    cartItems.value.length > 0 ? cartItems.value[0].supplier_id : null
);

// UI state
const isSubmitted = ref(false);
const isLoading = ref(false);
const showModal = ref(false);
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref<"success" | "error">("success");

onMounted(() => {
    document.documentElement.lang = currentLanguage.value;
    fetchProducts();
});

// Search
const onSearchInput = () => {
    if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
    searchDebounceTimer = setTimeout(() => fetchProducts(), 300);
};

const fetchProducts = async () => {
    productLoading.value = true;
    try {
        const res = await fetch(`/api/inventory/search?q=${encodeURIComponent(searchQuery.value)}`);
        const data: SearchProduct[] = await res.json();
        productList.value = data;
    } catch {
        productList.value = [];
    } finally {
        productLoading.value = false;
    }
};

// Variant picker state
const pendingProduct = ref<SearchProduct | null>(null);
const variantSelections = ref<Record<string, boolean>>({});
const hasCheckedVariants = computed(() =>
    Object.values(variantSelections.value).some((v) => v)
);

const openVariantPicker = (product: SearchProduct) => {
    pendingProduct.value = product;
    const selections: Record<string, boolean> = {};
    for (const v of product.variants) {
        selections[v.variant] = false;
    }
    variantSelections.value = selections;
};

const cancelVariantPicker = () => {
    pendingProduct.value = null;
    variantSelections.value = {};
};

const addVariantsToCart = () => {
    if (!pendingProduct.value) return;
    for (const v of pendingProduct.value.variants) {
        if (!variantSelections.value[v.variant]) continue;
        const alreadyInCart = cartItems.value.some(
            (c) => c.product_id === pendingProduct.value!.product_id && c.selected_variant === v.variant
        );
        if (alreadyInCart) continue;
        cartItems.value.push({
            product_id: pendingProduct.value.product_id,
            product_name: pendingProduct.value.product_name,
            supplier_id: pendingProduct.value.supplier_id,
            supplier_name: pendingProduct.value.supplier_name,
            available_variants: pendingProduct.value.variants,
            selected_variant: v.variant,
            bottles_per_case: safeNumber(v.bottles_per_case),
            free_bottles_per_case: safeNumber(v.variant_metadata?.free_bottles_per_case ?? 0),
            purchase_rate: safeNumber(v.purchase_rate),
            cases: 0,
            price_per_case: 0,
        });
    }
    cancelVariantPicker();
};

const selectProduct = (product: SearchProduct) => {
    openVariantPicker(product);
};

const removeCartItem = (index: number) => {
    cartItems.value.splice(index, 1);
};

// Stock limit helpers
const getVariantData = (item: CartItem): ProductVariant | null =>
    item.available_variants.find(v => v.variant === item.selected_variant) ?? null;

const getMaxCases = (item: CartItem): number => {
    const vd = getVariantData(item);
    if (!vd) return 0;
    const fbpc = safeNumber(vd.variant_metadata?.free_bottles_per_case ?? 0);
    if (includeFreeBottles.value && fbpc > 0) {
        const effectiveBPC = safeNumber(vd.bottles_per_case) + fbpc;
        return effectiveBPC > 0 ? Math.floor(vd.total_bottles_available / effectiveBPC) : 0;
    }
    return vd.cases_available;
};

const itemExceedsStock = (item: CartItem): boolean => {
    const vd = getVariantData(item);
    if (!vd) return false;
    return safeNumber(item.cases) > getMaxCases(item);
};

const anyItemExceedsStock = computed(() =>
    cartItems.value.some(item => itemExceedsStock(item))
);

// Subtotal per item (what customer pays for this item)
const getItemSubtotal = (item: CartItem): number => {
    const cases = safeNumber(item.cases);
    const pricePerCase = safeNumber(item.price_per_case);
    const bottlesPerCase = safeNumber(item.bottles_per_case);
    const freePerCase = safeNumber(item.free_bottles_per_case);

    if (!cases || !pricePerCase || !bottlesPerCase) return 0;

    const effectiveBPC = bottlesPerCase + freePerCase;
    const pricePerBottle = effectiveBPC > 0 ? pricePerCase / effectiveBPC : 0;
    const targetBottles = includeFreeBottles.value ? cases * effectiveBPC : cases * bottlesPerCase;
    return Math.round(targetBottles * pricePerBottle * 100) / 100;
};

// Invoice summary
const saleSummary = computed(() => {
    let totalCases = 0;
    let totalBottles = 0;
    let totalAmount = 0;
    let totalProfit = 0;

    for (const item of cartItems.value) {
        const cases = safeNumber(item.cases);
        const pricePerCase = safeNumber(item.price_per_case);
        const bpc = safeNumber(item.bottles_per_case);
        const freePerCase = safeNumber(item.free_bottles_per_case);
        const purchaseRate = safeNumber(item.purchase_rate);

        if (!cases || !bpc) continue;

        const effectiveBPC = bpc + freePerCase;
        const pricePerBottle = effectiveBPC > 0 ? pricePerCase / effectiveBPC : 0;
        const targetBottles = includeFreeBottles.value ? cases * effectiveBPC : cases * bpc;
        const subtotal = Math.round(targetBottles * pricePerBottle * 100) / 100;
        const cost = Math.round(targetBottles * purchaseRate * 100) / 100;

        totalCases += cases;
        totalBottles += targetBottles;
        totalAmount += subtotal;
        totalProfit += subtotal - cost;
    }

    return {
        totalCases,
        totalBottles,
        totalAmount,
        totalProfit,
        itemCount: cartItems.value.length,
        totalBottlesToSell: totalBottles,
    };
});

const showToastMessage = (msg: string, type: "success" | "error" = "success") => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => { showToast.value = false; }, 3000);
};

const resetForm = () => {
    cartItems.value = [];
    shopId.value = "";
    saleDate.value = localDateString();
    includeFreeBottles.value = true;
    isSubmitted.value = false;
    searchQuery.value = "";
    cancelVariantPicker();
    showToastMessage("formReset", "success");
};

const openModal = () => {
    isSubmitted.value = true;

    if (!shopId.value || !saleDate.value || cartItems.value.length === 0) {
        showToastMessage("saleError", "error");
        return;
    }

    const allItemsValid = cartItems.value.every(
        item =>
            item.selected_variant &&
            safeNumber(item.cases) > 0 &&
            safeNumber(item.price_per_case) > 0
    );

    if (!allItemsValid || cartItems.value.length === 0) {
        showToastMessage("saleError", "error");
        return;
    }

    showModal.value = true;
};

const confirmSale = () => {
    isLoading.value = true;

    const items = cartItems.value.map(item => {
        const cases = safeNumber(item.cases);
        const pricePerCase = safeNumber(item.price_per_case);
        const bpc = safeNumber(item.bottles_per_case);
        const freePerCase = safeNumber(item.free_bottles_per_case);
        const effectiveBPC = bpc + freePerCase;
        const pricePerBottle = effectiveBPC > 0 ? pricePerCase / effectiveBPC : 0;
        const targetBottles = includeFreeBottles.value ? cases * effectiveBPC : cases * bpc;

        return {
            product_id: item.product_id,
            variant: item.selected_variant,
            total_bottles_to_sell: targetBottles,
            selling_price_per_bottle: pricePerBottle,
            free_bottles_per_case: freePerCase,
        };
    });

    const payload = {
        shop_id: shopId.value,
        supplier_id: primarySupplierId.value,
        sale_date: saleDate.value,
        include_free_bottles: includeFreeBottles.value,
        items,
    };

    router.post("/sales/store", payload, {
        onSuccess: () => {
            showModal.value = false;
            isLoading.value = false;
            resetForm();
        },
        onError: (errors: Record<string, string>) => {
            showModal.value = false;
            isLoading.value = false;
            const messages = Object.values(errors);
            // Show first backend validation error directly in toast.
            // t() returns the raw string as-is when the key is not in translations,
            // so Laravel validation messages display correctly.
            showToastMessage(messages.length > 0 ? messages[0] : "saleError", "error");
        },
    });
};
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font,
.bangla-font * {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

input:focus,
select:focus {
    outline: none;
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

