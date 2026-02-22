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
        <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <div class="p-1.5 bg-indigo-100 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                {{ t('salesManagement') }}
            </h1>
            <div class="flex gap-2">
                <button
                    @click="changeLanguage('en')"
                    :class="['px-3 py-1.5 rounded text-sm font-medium transition-colors', currentLanguage === 'en' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
                >English</button>
                <button
                    @click="changeLanguage('bn')"
                    :class="['px-3 py-1.5 rounded text-sm font-medium transition-colors', currentLanguage === 'bn' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']"
                >বাংলা</button>
            </div>
        </div>

        <!-- POS Layout -->
        <div class="flex flex-1 gap-4 p-4 min-h-0">

            <!-- LEFT: Product search + cart -->
            <div class="flex-1 space-y-4 overflow-y-auto">

                <!-- Product Search -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ t('searchProduct') }}
                    </label>
                    <div class="relative" ref="searchBoxRef">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg v-if="!searchLoading" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            @input="onSearchInput"
                            @focus="onSearchFocus"
                            type="text"
                            :placeholder="detectedSupplierName ? t('searchAllOrPickFromSupplier') : t('typeToSearch')"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none transition"
                        />
                        <!-- Dropdown -->
                        <div
                            v-if="showSearchDropdown && dropdownVisible"
                            class="absolute z-30 top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-80 overflow-y-auto"
                        >
                            <!-- Suggestions: same supplier (shown when query is empty) -->
                            <template v-if="!searchQuery.trim() && suggestedProducts.length > 0">
                                <div class="px-3 py-2 bg-indigo-50 border-b border-indigo-100 flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span class="text-xs font-semibold text-indigo-600">{{ t('moreFromSupplier') }}: {{ detectedSupplierName }}</span>
                                </div>
                                <div
                                    v-for="product in suggestedProducts"
                                    :key="'sug-' + product.product_id"
                                    @mousedown.prevent="selectProduct(product)"
                                    class="flex items-center justify-between px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-50 last:border-b-0 transition-colors"
                                >
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ product.product_name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ product.variants?.length || 0 }} {{ t('variants') }}</p>
                                    </div>
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full ml-4 flex-shrink-0">
                                        {{ product.total_available_bottles }} {{ t('bottlesAvailable') }}
                                    </span>
                                </div>
                                <div class="px-3 py-2 border-t border-gray-100 text-center">
                                    <span class="text-xs text-gray-400">{{ t('typeToSearchAll') }}</span>
                                </div>
                            </template>

                            <!-- Search results (shown when typing) -->
                            <template v-else-if="searchQuery.trim()">
                                <div v-if="searchResults.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                                    {{ t('noProductsFound') }}
                                </div>
                                <div
                                    v-for="product in searchResults"
                                    :key="'res-' + product.product_id"
                                    @mousedown.prevent="selectProduct(product)"
                                    class="flex items-center justify-between px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-50 last:border-b-0 transition-colors"
                                    :class="{ 'bg-indigo-50/30': detectedSupplierId && product.supplier_id === detectedSupplierId }"
                                >
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <p class="text-sm font-medium text-gray-800">{{ product.product_name }}</p>
                                            <span v-if="detectedSupplierId && product.supplier_id === detectedSupplierId"
                                                class="text-xs bg-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded font-medium">
                                                {{ t('sameSupplier') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ product.supplier_name }} &middot; {{ product.variants?.length || 0 }} {{ t('variants') }}
                                        </p>
                                    </div>
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full ml-4 flex-shrink-0">
                                        {{ product.total_available_bottles }} {{ t('bottlesAvailable') }}
                                    </span>
                                </div>
                            </template>

                            <!-- Empty state when no cart items and empty query -->
                            <template v-else>
                                <div class="px-4 py-6 text-center text-gray-400 text-sm">
                                    {{ t('typeToSearch') }}
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Cart Items -->
                <div v-if="cartItems.length === 0" class="bg-white rounded-xl border border-dashed border-gray-300 p-10 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-gray-400 text-sm">{{ t('searchToAddProducts') }}</p>
                </div>

                <div v-for="(item, index) in cartItems" :key="index" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <!-- Item header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-indigo-600 text-white text-xs rounded-full flex items-center justify-center font-bold flex-shrink-0">
                                {{ index + 1 }}
                            </span>
                            <span class="font-semibold text-gray-800 text-sm">{{ item.product_name }}</span>
                        </div>
                        <button
                            @click="removeCartItem(index)"
                            class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Fields row -->
                    <div class="grid grid-cols-3 gap-3">
                        <!-- Variant -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ t('variant') }}</label>
                            <select
                                v-model="item.selected_variant"
                                @change="onVariantSelect(index)"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none bg-white"
                                :class="{ 'border-red-300': isSubmitted && !item.selected_variant }"
                            >
                                <option value="">{{ t('selectVariant') }}</option>
                                <option v-for="v in item.available_variants" :key="v.variant" :value="v.variant">
                                    {{ v.variant }}
                                    ({{ v.purchased_bottles_available + v.free_bottles_available }} {{ t('avail') }})
                                </option>
                            </select>
                        </div>

                        <!-- Cases -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ t('cases') }}</label>
                            <input
                                v-model.number="item.cases"
                                type="number"
                                min="1"
                                :placeholder="t('cases')"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none"
                                :class="{ 'border-red-300': isSubmitted && !(item.cases > 0) }"
                            />
                            <p v-if="item.bottles_per_case" class="text-xs text-gray-400 mt-0.5">
                                {{ item.bottles_per_case }}/{{ t('case') }}
                                <span v-if="item.free_bottles_per_case > 0" class="text-green-600">+{{ item.free_bottles_per_case }} {{ t('free') }}</span>
                            </p>
                        </div>

                        <!-- Price per case -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ t('pricePerCase') }} (৳)</label>
                            <input
                                v-model.number="item.price_per_case"
                                type="number"
                                min="0"
                                :placeholder="t('pricePerCase')"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none"
                                :class="{ 'border-red-300': isSubmitted && !(item.price_per_case > 0) }"
                            />
                            <p v-if="getItemSubtotal(item) > 0" class="text-xs text-indigo-600 mt-0.5 font-medium">
                                ৳{{ formatNumber(getItemSubtotal(item)) }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Sticky invoice panel -->
            <div class="w-72 flex-shrink-0">
                <div class="sticky top-4 space-y-3">

                    <!-- Sale Details card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 space-y-3">
                        <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-2">{{ t('saleDetails') }}</h3>

                        <!-- Shop -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">{{ t('shopName') }}*</label>
                            <select
                                v-model="shopId"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none bg-white"
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
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700"
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
                                class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 outline-none"
                                :class="{ 'border-red-300': isSubmitted && !saleDate }"
                            />
                        </div>

                        <!-- Free bottles toggle -->
                        <div class="flex items-center justify-between py-1">
                            <span class="text-xs font-medium text-gray-600">{{ t('includeFreeBottles') }}</span>
                            <button
                                @click="includeFreeBottles = !includeFreeBottles"
                                :class="['relative inline-flex h-5 w-9 items-center rounded-full transition-colors', includeFreeBottles ? 'bg-indigo-600' : 'bg-gray-300']"
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
                                <span class="text-lg font-bold text-indigo-700">৳{{ formatNumber(saleSummary.totalAmount) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <button
                        @click="openModal"
                        :disabled="isLoading || cartItems.length === 0"
                        class="w-full py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2 shadow-sm"
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
import { ref, computed, onMounted, onUnmounted } from "vue";
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
        variant: "Variant",
        selectVariant: "Select variant",
        cases: "Cases",
        case: "case",
        free: "free",
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
        variant: "ভেরিয়েন্ট",
        selectVariant: "ভেরিয়েন্ট নির্বাচন করুন",
        cases: "কেস",
        case: "কেস",
        free: "ফ্রি",
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
    },
};

const t = (key: string, _params?: Record<string, any>) => translations[currentLanguage.value]?.[key] ?? key;

const toBengaliNumber = (num: number | string): string => {
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

const safeNumber = (v: any): number => {
    const n = Number(v);
    return isNaN(n) ? 0 : n;
};

// Form state
const shopId = ref<number | string>("");
const saleDate = ref(new Date().toISOString().split("T")[0]);
const includeFreeBottles = ref(true);
const cartItems = ref<CartItem[]>([]);

// Search state
const searchQuery = ref("");
const searchResults = ref<SearchProduct[]>([]);
const suggestedProducts = ref<SearchProduct[]>([]);
const searchLoading = ref(false);
const showSearchDropdown = ref(false);
const searchBoxRef = ref<HTMLElement | null>(null);
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

// Most recently added item's supplier — used for suggestions context
const detectedSupplierId = computed(() =>
    cartItems.value.length > 0 ? cartItems.value[cartItems.value.length - 1].supplier_id : null
);
const detectedSupplierName = computed(() =>
    cartItems.value.length > 0 ? cartItems.value[cartItems.value.length - 1].supplier_name : ""
);

// Dropdown is visible if there's something to show
const dropdownVisible = computed(() =>
    !searchQuery.value.trim()
        ? suggestedProducts.value.length > 0
        : searchResults.value.length > 0 || searchQuery.value.length > 0
);

// UI state
const isSubmitted = ref(false);
const isLoading = ref(false);
const showModal = ref(false);
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref<"success" | "error">("success");

// Click outside to close dropdown
const handleClickOutside = (e: MouseEvent) => {
    if (searchBoxRef.value && !searchBoxRef.value.contains(e.target as Node)) {
        showSearchDropdown.value = false;
    }
};

onMounted(() => {
    document.documentElement.lang = currentLanguage.value;
    document.addEventListener("mousedown", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("mousedown", handleClickOutside);
});

// Search
const onSearchFocus = () => {
    showSearchDropdown.value = true;
    // When focused with empty query and a supplier is detected, load their products as suggestions
    if (!searchQuery.value.trim() && detectedSupplierId.value && suggestedProducts.value.length === 0) {
        fetchSuggestions(detectedSupplierId.value);
    }
};

const onSearchInput = () => {
    if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
    showSearchDropdown.value = true;
    if (!searchQuery.value.trim()) {
        searchResults.value = [];
        return;
    }
    searchDebounceTimer = setTimeout(() => fetchProducts(), 300);
};

const fetchSuggestions = async (supplierId: number) => {
    try {
        const res = await fetch(`/api/products-by-supplier?supplier_id=${supplierId}`);
        const data = await res.json();
        const all: SearchProduct[] = Array.isArray(data.products)
            ? data.products
            : Object.values(data.products || {});
        // Exclude products already in cart
        suggestedProducts.value = all.filter(
            p => !cartItems.value.some(c => c.product_id === p.product_id)
        );
    } catch {
        suggestedProducts.value = [];
    }
};

const fetchProducts = async () => {
    searchLoading.value = true;
    try {
        const res = await fetch(`/api/inventory/search?q=${encodeURIComponent(searchQuery.value)}`);
        const data: SearchProduct[] = await res.json();
        searchResults.value = data;
    } catch {
        searchResults.value = [];
    } finally {
        searchLoading.value = false;
    }
};

const selectProduct = (product: SearchProduct) => {
    // Check if product already in cart (same product_id)
    const exists = cartItems.value.some(item => item.product_id === product.product_id);
    if (exists) {
        showToastMessage("productAlreadyInCart", "error");
        showSearchDropdown.value = false;
        searchQuery.value = "";
        return;
    }

    cartItems.value.push({
        product_id: product.product_id,
        product_name: product.product_name,
        supplier_id: product.supplier_id,
        supplier_name: product.supplier_name,
        available_variants: product.variants || [],
        selected_variant: "",
        bottles_per_case: 0,
        free_bottles_per_case: 0,
        purchase_rate: 0,
        cases: 0,
        price_per_case: 0,
    });

    // Auto-select variant if only one
    const lastIdx = cartItems.value.length - 1;
    if (product.variants?.length === 1) {
        cartItems.value[lastIdx].selected_variant = product.variants[0].variant;
        onVariantSelect(lastIdx);
    }

    showSearchDropdown.value = false;
    searchQuery.value = "";
    searchResults.value = [];

    // Refresh suggestions to exclude the newly added product
    if (detectedSupplierId.value) {
        fetchSuggestions(detectedSupplierId.value);
    }
};

const onVariantSelect = (index: number) => {
    const item = cartItems.value[index];
    const variantData = item.available_variants.find(v => v.variant === item.selected_variant);
    if (variantData) {
        item.bottles_per_case = safeNumber(variantData.bottles_per_case);
        item.purchase_rate = safeNumber(variantData.purchase_rate);
        item.free_bottles_per_case = safeNumber(variantData.variant_metadata?.free_bottles_per_case ?? 0);
    }
};

const removeCartItem = (index: number) => {
    cartItems.value.splice(index, 1);
    if (cartItems.value.length === 0) {
        suggestedProducts.value = [];
    }
};

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
    return targetBottles * pricePerBottle;
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
        const subtotal = targetBottles * pricePerBottle;
        const cost = targetBottles * purchaseRate;

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
    saleDate.value = new Date().toISOString().split("T")[0];
    includeFreeBottles.value = true;
    isSubmitted.value = false;
    searchQuery.value = "";
    searchResults.value = [];
    suggestedProducts.value = [];
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
