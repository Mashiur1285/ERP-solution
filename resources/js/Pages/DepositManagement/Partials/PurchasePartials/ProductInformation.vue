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
                    <select
                        v-model="productForm.category_id"
                        id="category_id"
                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 appearance-none"
                        :class="{
                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                isSubmitted && !productForm.category_id,
                        }"
                        required
                    >
                        <option value="" disabled>
                            {{ t("selectCategory") }}
                        </option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <div
                        class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
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
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
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
                    <select
                        v-model="productForm.brand_id"
                        id="brand_id"
                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 appearance-none"
                        :class="{
                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                isSubmitted && !productForm.brand_id,
                        }"
                        required
                    >
                        <option value="" disabled>
                            {{ t("selectBrand") }}
                        </option>
                        <option
                            v-for="brand in brands"
                            :key="brand.id"
                            :value="brand.id"
                        >
                            {{ brand.brand_name }}
                        </option>
                    </select>
                    <div
                        class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
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
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
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
                    <select
                        v-model="productForm.supplier_id"
                        id="supplier_id"
                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 appearance-none"
                        :class="{
                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                isSubmitted && !productForm.supplier_id,
                        }"
                        required
                    >
                        <option value="" disabled>
                            {{ t("selectSupplier") }}
                        </option>
                        <option
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            :value="supplier.id"
                        >
                            {{ supplier.company_name }} (৳{{
                                toBengaliNumber(
                                    supplier.remaining_deposit.toFixed(2)
                                )
                            }})
                        </option>
                    </select>
                    <div
                        class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
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
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
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
defineProps<{
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
