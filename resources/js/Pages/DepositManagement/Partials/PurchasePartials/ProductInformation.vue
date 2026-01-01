<!-- Pages/Depositmanagement/partials/purchasePartials/ProductInformation.vue -->
<template>
    <div
        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            {{ t("productInformation") }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label
                    for="product_name"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    {{ t("productName") }}*
                </label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                            />
                        </svg>
                    </div>
                    <input
                        v-model="productForm.product_name"
                        id="product_name"
                        type="text"
                        :placeholder="t('enterProductName')"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                        :class="{
                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                isSubmitted && !productForm.product_name,
                        }"
                        required
                    />
                </div>
                <p
                    v-if="isSubmitted && !productForm.product_name"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ t("productNameRequired") }}
                </p>
            </div>
            <div>
                <label
                    for="category_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    {{ t("category") }}*
                </label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h10a2 2 0 012 2v2"
                            />
                        </svg>
                    </div>
                    <div class="flex">
                        <div class="relative flex-1">
                            <input
                                v-model="categorySearch"
                                @focus="showCategoryDropdown = true"
                                @input="showCategoryDropdown = true"
                                id="category_id"
                                type="text"
                                :placeholder="t('selectCategory')"
                                class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted && !productForm.category_id,
                                }"
                                autocomplete="off"
                            />
                            <div
                                v-if="showCategoryDropdown"
                                class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-auto z-20"
                            >
                                <button
                                    v-for="category in filteredCategories"
                                    :key="category.id"
                                    type="button"
                                    class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-sm text-gray-700"
                                    @click="selectCategory(category)"
                                >
                                    {{ category.name }}
                                </button>
                                <div
                                    v-if="!filteredCategories.length"
                                    class="px-4 py-3 text-sm text-gray-500"
                                >
                                    {{ t("noResults") }}
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="ml-2 px-3 py-3 rounded-lg border-2 border-indigo-200 text-indigo-700 hover:bg-indigo-50 flex items-center justify-center"
                            @click="$emit('add-category')"
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
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <p
                    v-if="isSubmitted && !productForm.category_id"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ t("categoryRequired") }}
                </p>
            </div>
            <div>
                <label
                    for="brand_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    {{ t("brand") }}*
                </label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400"
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
                    <div class="flex">
                        <div class="relative flex-1">
                            <input
                                v-model="brandSearch"
                                @focus="showBrandDropdown = true"
                                @input="showBrandDropdown = true"
                                id="brand_id"
                                type="text"
                                :placeholder="t('selectBrand')"
                                class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted && !productForm.brand_id,
                                }"
                                autocomplete="off"
                            />
                            <div
                                v-if="showBrandDropdown"
                                class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-auto z-20"
                            >
                                <button
                                    v-for="brand in filteredBrands"
                                    :key="brand.id"
                                    type="button"
                                    class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-sm text-gray-700"
                                    @click="selectBrand(brand)"
                                >
                                    {{ brand.brand_name }}
                                </button>
                                <div
                                    v-if="!filteredBrands.length"
                                    class="px-4 py-3 text-sm text-gray-500"
                                >
                                    {{ t("noResults") }}
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="ml-2 px-3 py-3 rounded-lg border-2 border-indigo-200 text-indigo-700 hover:bg-indigo-50 flex items-center justify-center"
                            @click="$emit('add-brand')"
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
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <p
                    v-if="isSubmitted && !productForm.brand_id"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ t("brandRequired") }}
                </p>
            </div>
            <div>
                <label
                    for="supplier_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    {{ t("supplier") }}*
                </label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                    >
                        <svg
                            class="w-5 h-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h10a2 2 0 012 2v2"
                            />
                        </svg>
                    </div>
                    <div class="flex">
                        <div class="relative flex-1">
                            <input
                                v-model="supplierSearch"
                                @focus="showSupplierDropdown = true"
                                @input="showSupplierDropdown = true"
                                id="supplier_id"
                                type="text"
                                :placeholder="t('selectSupplier')"
                                class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted && !productForm.supplier_id,
                                }"
                                autocomplete="off"
                            />
                            <div
                                v-if="showSupplierDropdown"
                                class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-auto z-20"
                            >
                                <button
                                    v-for="supplier in filteredSuppliers"
                                    :key="supplier.id"
                                    type="button"
                                    class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-sm text-gray-700"
                                    @click="selectSupplier(supplier)"
                                >
                                    {{ supplier.company_name }} (৳{{
                                        toBengaliNumber(
                                            supplier.remaining_deposit.toFixed(
                                                2
                                            )
                                        )
                                    }})
                                </button>
                                <div
                                    v-if="!filteredSuppliers.length"
                                    class="px-4 py-3 text-sm text-gray-500"
                                >
                                    {{ t("noResults") }}
                                </div>
                            </div>
                        </div>
                        <button
                            type="button"
                            class="ml-2 px-3 py-3 rounded-lg border-2 border-indigo-200 text-indigo-700 hover:bg-indigo-50 flex items-center justify-center"
                            @click="$emit('add-supplier')"
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
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <p
                    v-if="isSubmitted && !productForm.supplier_id"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ t("supplierRequired") }}
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    {{ t("remainingDepositNote") }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, defineEmits, defineProps, ref, watch } from "vue";

const props = defineProps<{
    productForm: {
        product_name: string;
        category_id: string;
        brand_id: string;
        supplier_id: string;
    };
    suppliers: Array<{
        id: number;
        company_name: string;
        remaining_deposit: number;
    }>;
    categories: Array<{ id: number; name: string }>;
    brands: Array<{ id: number; brand_name: string }>;
    isSubmitted: boolean;
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
}>();

const emit = defineEmits<{
    (e: "add-category"): void;
    (e: "add-brand"): void;
    (e: "add-supplier"): void;
}>();

const categorySearch = ref("");
const brandSearch = ref("");
const supplierSearch = ref("");
const showCategoryDropdown = ref(false);
const showBrandDropdown = ref(false);
const showSupplierDropdown = ref(false);

const filteredCategories = computed(() =>
    props.categories.filter((c) =>
        c.name.toLowerCase().includes(categorySearch.value.toLowerCase())
    )
);

const filteredBrands = computed(() =>
    props.brands.filter((b) =>
        b.brand_name.toLowerCase().includes(brandSearch.value.toLowerCase())
    )
);

const filteredSuppliers = computed(() =>
    props.suppliers.filter((s) =>
        s.company_name.toLowerCase().includes(supplierSearch.value.toLowerCase())
    )
);

const selectCategory = (category: { id: number; name: string }) => {
    props.productForm.category_id = category.id.toString();
    categorySearch.value = category.name;
    showCategoryDropdown.value = false;
};

const selectBrand = (brand: { id: number; brand_name: string }) => {
    props.productForm.brand_id = brand.id.toString();
    brandSearch.value = brand.brand_name;
    showBrandDropdown.value = false;
};

const selectSupplier = (supplier: {
    id: number;
    company_name: string;
    remaining_deposit: number;
}) => {
    props.productForm.supplier_id = supplier.id.toString();
    supplierSearch.value = supplier.company_name;
    showSupplierDropdown.value = false;
};

watch(
    () => props.productForm.category_id,
    (newVal) => {
        if (!newVal) return;
        const selected = props.categories.find(
            (c) => c.id.toString() === newVal.toString()
        );
        if (selected) categorySearch.value = selected.name;
    }
);

watch(
    () => props.productForm.brand_id,
    (newVal) => {
        if (!newVal) return;
        const selected = props.brands.find(
            (b) => b.id.toString() === newVal.toString()
        );
        if (selected) brandSearch.value = selected.brand_name;
    }
);

watch(
    () => props.productForm.supplier_id,
    (newVal) => {
        if (!newVal) return;
        const selected = props.suppliers.find(
            (s) => s.id.toString() === newVal.toString()
        );
        if (selected) supplierSearch.value = selected.company_name;
    }
);
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
