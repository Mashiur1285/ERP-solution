<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <div
            v-if="editModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
        >
            <div class="w-full max-w-2xl rounded-2xl bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ getTranslation("editProduct") }}</h3>
                    <button
                        class="rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                        @click="closeEditModal"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-5 px-6 py-5">
                    <div class="flex gap-5">
                        <div class="h-28 w-28 rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0">
                            <img
                                v-if="editForm.imagePreviewUrl"
                                :src="editForm.imagePreviewUrl"
                                :alt="editForm.name"
                                class="h-full w-full object-cover"
                            />
                            <svg
                                v-else
                                class="w-10 h-10 text-gray-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-10h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ getTranslation("productImage") }}</label>
                            <input
                                id="edit-product-image"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @change="handleEditImageChange"
                            />
                            <label
                                for="edit-product-image"
                                class="inline-flex cursor-pointer items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                            >
                                {{ editForm.imageFile ? getTranslation("changeImage") : getTranslation("uploadImage") }}
                            </label>
                            <p v-if="editForm.imageFile" class="mt-2 text-sm text-gray-500 break-all">
                                {{ editForm.imageFile.name }}
                            </p>
                            <button
                                v-if="editForm.imagePreviewUrl"
                                type="button"
                                class="mt-3 text-sm font-medium text-red-600 hover:text-red-700"
                                @click="removeEditImage"
                            >
                                {{ getTranslation("removeImage") }}
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ getTranslation("productName") }}</label>
                            <input
                                v-model="editForm.name"
                                type="text"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ getTranslation("category") }}</label>
                            <select
                                v-model="editForm.category_id"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            >
                                <option :value="''">{{ getTranslation("selectCategory") }}</option>
                                <option v-for="category in categories" :key="category.id" :value="String(category.id)">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ getTranslation("brand") }}</label>
                            <select
                                v-model="editForm.brand_id"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            >
                                <option :value="''">{{ getTranslation("selectBrand") }}</option>
                                <option v-for="brand in brands" :key="brand.id" :value="String(brand.id)">
                                    {{ brand.brand_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3">
                        <input v-model="editForm.is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="text-sm font-medium text-gray-700">{{ getTranslation("activeProductToggle") }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                    <button
                        class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50"
                        @click="closeEditModal"
                    >
                        {{ getTranslation("cancel") }}
                    </button>
                    <button
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                        :disabled="isSubmittingEdit"
                        @click="submitEdit"
                    >
                        {{ isSubmittingEdit ? getTranslation("saving") : getTranslation("saveChanges") }}
                    </button>
                </div>
            </div>
        </div>

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
                {{ getTranslationLabel("languageLabel", "en") }}
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
                {{ getTranslationLabel("languageLabel", "bn") }}
            </button>
        </div>

        <div
            class="flex flex-col lg:flex-row lg:justify-between items-start lg:items-center mb-8 border-b border-gray-200 pb-4 gap-4"
        >
            <h1
                class="text-2xl lg:text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 lg:w-8 lg:h-8 text-indigo-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-10h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                </div>
                {{ getTranslation("productList") }}
            </h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-sm border border-indigo-200"
            >
                <p class="text-sm font-medium text-indigo-700">{{ getTranslation("totalProducts") }}</p>
                <p class="text-lg font-bold text-indigo-900">{{ toBengaliNumber(totalProducts) }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200"
            >
                <p class="text-sm font-medium text-green-700">{{ getTranslation("withImages") }}</p>
                <p class="text-lg font-bold text-green-900">{{ toBengaliNumber(totalWithImages) }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl shadow-sm border border-orange-200"
            >
                <p class="text-sm font-medium text-orange-700">{{ getTranslation("totalVariants") }}</p>
                <p class="text-lg font-bold text-orange-900">{{ toBengaliNumber(totalVariants) }}</p>
            </div>
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm border border-purple-200"
            >
                <p class="text-sm font-medium text-purple-700">{{ getTranslation("activeProducts") }}</p>
                <p class="text-lg font-bold text-purple-900">{{ toBengaliNumber(totalActiveProducts) }}</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-between items-center gap-4">
            <div class="relative w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="getTranslation('searchProducts')"
                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                />
            </div>
        </div>

        <div v-if="!products.data.length" class="bg-white rounded-xl shadow-sm border border-gray-200 py-12 text-center text-gray-500">
            {{ getTranslation("noProductsFound") }}
        </div>

        <div v-else class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5">
            <div
                v-for="product in products.data"
                :key="product.id"
                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md hover:scale-[1.01] transition-all duration-200"
            >
                <div class="flex gap-4 p-5">
                    <div class="h-20 w-20 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center flex-shrink-0">
                            <img
                                v-if="product.image_url"
                                :src="product.image_url"
                                :alt="product.name"
                                class="h-full w-full object-cover"
                            />
                            <svg
                                v-else
                                class="w-10 h-10 text-gray-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-10h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="text-lg font-semibold leading-tight text-gray-900 break-words">{{ product.name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ product.supplier_name }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    v-if="canEditProducts"
                                    class="inline-flex items-center rounded-full border border-gray-200 px-2.5 py-1 text-xs font-semibold text-gray-600 hover:bg-gray-50"
                                    @click="openEditModal(product)"
                                >
                                    {{ getTranslation("edit") }}
                                </button>
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                    :class="product.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'"
                                >
                                    {{ product.is_active ? getTranslation("active") : getTranslation("inactive") }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 mt-4 text-sm">
                            <div>
                                <p class="text-gray-400">{{ getTranslation("category") }}</p>
                                <p class="mt-1 font-medium text-gray-700 break-words">{{ product.category_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">{{ getTranslation("brand") }}</p>
                                <p class="mt-1 font-medium text-gray-700 break-words">{{ product.brand_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">{{ getTranslation("variants") }}</p>
                                <p class="mt-1 font-medium text-gray-700">{{ toBengaliNumber(product.variant_count) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">{{ getTranslation("purchaseBatches") }}</p>
                                <p class="mt-1 font-medium text-gray-700">{{ toBengaliNumber(product.purchase_batches_count) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">{{ getTranslation("stockCases") }}</p>
                                <p class="mt-1 font-medium text-gray-700">{{ toBengaliNumber(product.stock_cases) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">{{ getTranslation("stockBottles") }}</p>
                                <p class="mt-1 font-medium text-gray-700">{{ toBengaliNumber(product.stock_bottles) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <div class="mb-4 rounded-xl border border-gray-100 bg-gray-50/80 px-3.5 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                                    {{ getTranslation("variantPurchaseDetails") }}
                                </p>
                                <div v-if="product.default_variants.length" class="mt-1 flex flex-wrap gap-2">
                                    <span
                                        v-for="variant in getVisibleVariants(product)"
                                        :key="`${product.id}-${variant.variant}`"
                                        class="inline-flex max-w-full items-center gap-1 rounded-full bg-white px-2.5 py-1 text-xs font-medium text-gray-700 ring-1 ring-gray-200"
                                    >
                                        <span class="truncate">{{ variant.variant }}</span>
                                        <span class="text-gray-400">•</span>
                                        <span class="whitespace-nowrap">৳{{ toBengaliNumber(variant.total_purchase_amount || 0) }}</span>
                                    </span>
                                </div>
                                <p v-else class="mt-1 text-sm text-gray-400">
                                    {{ getTranslation("noVariants") }}
                                </p>
                            </div>
                            <button
                                v-if="product.default_variants.length > defaultVisibleVariantCount"
                                type="button"
                                class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-semibold text-indigo-600 ring-1 ring-gray-200 hover:text-indigo-700"
                                @click="toggleVariantList(product.id)"
                            >
                                {{
                                    expandedVariants[product.id]
                                        ? getTranslation("showLess")
                                        : `+${toBengaliNumber(product.default_variants.length - defaultVisibleVariantCount)}`
                                }}
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <div>
                            <p class="text-gray-400 text-sm">{{ getTranslation("purchaseAmount") }}</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900">৳{{ toBengaliNumber(product.total_purchase_amount) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-sm">{{ getTranslation("createdAt") }}</p>
                            <p class="mt-1 font-medium text-gray-700">{{ product.created_at || "-" }}</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div v-if="products.last_page > 1" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white rounded-xl border border-gray-200 px-5 py-4">
                <p class="text-sm text-gray-500">
                    {{ getTranslation("showing") }}
                    {{ toBengaliNumber(products.from || 0) }}
                    -
                    {{ toBengaliNumber(products.to || 0) }}
                    {{ getTranslation("of") }}
                    {{ toBengaliNumber(products.total || 0) }}
                </p>

                <div class="flex items-center gap-2 flex-wrap">
                    <button
                        class="px-3 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-50"
                        :disabled="products.current_page <= 1"
                        @click="goToPage(products.current_page - 1)"
                    >
                        {{ getTranslation("previous") }}
                    </button>

                    <button
                        v-for="page in visiblePages"
                        :key="page"
                        class="min-w-10 px-3 py-2 rounded-lg text-sm font-medium border"
                        :class="page === products.current_page ? 'bg-indigo-600 border-indigo-600 text-white' : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
                        @click="goToPage(page)"
                    >
                        {{ toBengaliNumber(page) }}
                    </button>

                    <button
                        class="px-3 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-50"
                        :disabled="products.current_page >= products.last_page"
                        @click="goToPage(products.current_page + 1)"
                    >
                        {{ getTranslation("next") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps({
    products: {
        type: Object,
        default: () => ({ data: [] }),
    },
    filters: {
        type: Object,
        default: () => ({ search: "" }),
    },
    categories: {
        type: Array,
        default: () => [],
    },
    brands: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

const translations = {
    en: {
        languageLabel: "English",
        productList: "Product List",
        totalProducts: "Total Products",
        withImages: "With Images",
        totalVariants: "Total Variants",
        activeProducts: "Active Products",
        searchProducts: "Search products, supplier, category or brand...",
        noProductsFound: "No products found",
        category: "Category",
        brand: "Brand",
        variants: "Variants",
        variantPurchaseDetails: "Variant Purchase Details",
        purchaseBatches: "Purchase Batches",
        purchaseAmount: "Purchase Amount",
        stockCases: "Stock Cases",
        stockBottles: "Stock Bottles",
        bottlesPerCase: "bottles/case",
        createdAt: "Created At",
        active: "Active",
        inactive: "Inactive",
        edit: "Edit",
        editProduct: "Edit Product Catalog",
        saveChanges: "Save Changes",
        saving: "Saving...",
        cancel: "Cancel",
        productImage: "Product Image",
        uploadImage: "Upload image",
        changeImage: "Change image",
        removeImage: "Remove image",
        productName: "Product Name",
        selectCategory: "Select category",
        selectBrand: "Select brand",
        activeProductToggle: "Keep this product active",
        noVariants: "No variants added",
        showLess: "Less",
        availability: "Availability",
        casesLabel: "cases",
        bottlesLabel: "bottles",
        liveStock: "Live Stock",
        showing: "Showing",
        of: "of",
        previous: "Previous",
        next: "Next",
    },
    bn: {
        languageLabel: "বাংলা",
        productList: "পণ্যের তালিকা",
        totalProducts: "মোট পণ্য",
        withImages: "ছবিসহ পণ্য",
        totalVariants: "মোট ভেরিয়েন্ট",
        activeProducts: "সক্রিয় পণ্য",
        searchProducts: "পণ্য, সরবরাহকারী, বিভাগ বা ব্র্যান্ড দিয়ে খুঁজুন...",
        noProductsFound: "কোন পণ্য পাওয়া যায়নি",
        category: "বিভাগ",
        brand: "ব্র্যান্ড",
        variants: "ভেরিয়েন্ট",
        variantPurchaseDetails: "ভেরিয়েন্ট অনুযায়ী ক্রয়",
        purchaseBatches: "ক্রয় ব্যাচ",
        purchaseAmount: "ক্রয়ের পরিমাণ",
        stockCases: "স্টক কেস",
        stockBottles: "স্টক বোতল",
        bottlesPerCase: "বোতল/কেস",
        createdAt: "তৈরি হয়েছে",
        active: "সক্রিয়",
        inactive: "নিষ্ক্রিয়",
        edit: "এডিট",
        editProduct: "পণ্যের ক্যাটালগ এডিট",
        saveChanges: "পরিবর্তন সংরক্ষণ",
        saving: "সংরক্ষণ হচ্ছে...",
        cancel: "বাতিল",
        productImage: "পণ্যের ছবি",
        uploadImage: "ছবি আপলোড",
        changeImage: "ছবি পরিবর্তন",
        removeImage: "ছবি মুছুন",
        productName: "পণ্যের নাম",
        selectCategory: "বিভাগ নির্বাচন",
        selectBrand: "ব্র্যান্ড নির্বাচন",
        activeProductToggle: "এই পণ্য সক্রিয় রাখুন",
        noVariants: "কোন ভেরিয়েন্ট যোগ করা হয়নি",
        showLess: "কম",
        availability: "স্টক অবস্থা",
        casesLabel: "কেস",
        bottlesLabel: "বোতল",
        liveStock: "বর্তমান স্টক",
        showing: "দেখানো হচ্ছে",
        of: "মোট",
        previous: "পূর্বের",
        next: "পরের",
    },
};

const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref(props.filters.search || "");
let searchTimer;
const editModalOpen = ref(false);
const isSubmittingEdit = ref(false);
const expandedVariants = ref({});
const defaultVisibleVariantCount = 2;
const editForm = ref({
    id: null,
    name: "",
    category_id: "",
    brand_id: "",
    is_active: true,
    imageFile: null,
    imagePreviewUrl: "",
    removeImage: false,
});

const canEditProducts = computed(() =>
    (page.props.userPermissions || []).includes("lift.add")
);

const getTranslation = (key) => translations[currentLanguage.value]?.[key] || key;
const getTranslationLabel = (key, lang) => translations[lang]?.[key] || key;

const changeLanguage = (lang) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

const toBengaliNumber = (numValue) => {
    if (numValue === null || numValue === undefined || numValue === "") return "";
    const n = Number(numValue);
    const output = Number.isNaN(n) ? String(numValue) : (n % 1 !== 0 ? n.toFixed(2) : n.toString());
    if (currentLanguage.value !== "bn") return output;
    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return output.replace(/[0-9]/g, (digit) => bengaliDigits[parseInt(digit)]);
};

watch(searchQuery, (value) => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get(
            "/products",
            { search: value || undefined },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 300);
});

const visiblePages = computed(() => {
    const current = props.products.current_page || 1;
    const last = props.products.last_page || 1;
    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);
    return Array.from({ length: end - start + 1 }, (_, index) => start + index);
});

const goToPage = (page) => {
    if (page < 1 || page > (props.products.last_page || 1)) return;
    router.get(
        "/products",
        { search: searchQuery.value || undefined, page },
        { preserveState: true, preserveScroll: true, replace: true }
    );
};

const getVisibleVariants = (product) => {
    const variants = product.default_variants || [];
    return expandedVariants.value[product.id]
        ? variants
        : variants.slice(0, defaultVisibleVariantCount);
};

const toggleVariantList = (productId) => {
    expandedVariants.value = {
        ...expandedVariants.value,
        [productId]: !expandedVariants.value[productId],
    };
};

const openEditModal = (product) => {
    editForm.value = {
        id: product.id,
        name: product.name,
        category_id: product.category_id?.toString?.() || "",
        brand_id: product.brand_id?.toString?.() || "",
        is_active: !!product.is_active,
        imageFile: null,
        imagePreviewUrl: product.image_url || "",
        removeImage: false,
    };
    editModalOpen.value = true;
};

const closeEditModal = () => {
    editModalOpen.value = false;
    editForm.value = {
        id: null,
        name: "",
        category_id: "",
        brand_id: "",
        is_active: true,
        imageFile: null,
        imagePreviewUrl: "",
        removeImage: false,
    };
};

const handleEditImageChange = (event) => {
    const target = event.target;
    const file = target.files?.[0] || null;
    editForm.value.imageFile = file;
    editForm.value.imagePreviewUrl = file ? URL.createObjectURL(file) : "";
};

const removeEditImage = () => {
    editForm.value.imageFile = null;
    editForm.value.imagePreviewUrl = "";
    editForm.value.removeImage = true;
};

const submitEdit = () => {
    if (!editForm.value.id || !editForm.value.name) return;

    isSubmittingEdit.value = true;
    const formData = new FormData();
    formData.append("name", editForm.value.name);
    formData.append("category_id", editForm.value.category_id || "");
    formData.append("brand_id", editForm.value.brand_id || "");
    formData.append("is_active", editForm.value.is_active ? "1" : "0");
    formData.append("remove_image", editForm.value.removeImage ? "1" : "0");
    formData.append("search", searchQuery.value || "");
    formData.append("page", String(props.products.current_page || 1));
    if (editForm.value.imageFile) {
        formData.append("product_image", editForm.value.imageFile);
    }

    router.post(`/products/${editForm.value.id}/update`, formData, {
        forceFormData: true,
        onFinish: () => {
            isSubmittingEdit.value = false;
        },
        onSuccess: () => {
            closeEditModal();
        },
    });
};

const totalProducts = computed(() => props.products.total || 0);
const totalWithImages = computed(() => props.products.data.filter((product) => product.image_url).length);
const totalVariants = computed(() =>
    props.products.data.reduce((sum, product) => sum + Number(product.variant_count || 0), 0)
);
const totalActiveProducts = computed(() => props.products.data.filter((product) => product.is_active).length);
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
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
    animation: fadeIn 0.8s ease-out;
}
</style>
