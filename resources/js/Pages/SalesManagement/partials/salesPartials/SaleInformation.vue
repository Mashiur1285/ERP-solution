<template>
    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">
            {{ t("saleInformation") }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Shop Selection -->
            <div>
                <label
                    for="shop_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    {{ t("shopName") }}*
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
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                            />
                        </svg>
                    </div>
                    <select
                        v-model="saleForm.shop_id"
                        id="shop_id"
                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 appearance-none"
                        :class="{
                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                isSubmitted && !saleForm.shop_id,
                        }"
                        required
                    >
                        <option value="" disabled>{{ t("selectShop") }}</option>
                        <option
                            v-for="shop in shops"
                            :key="shop.id"
                            :value="shop.id"
                        >
                            {{ shop.shop_name }}
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
                    v-if="isSubmitted && !saleForm.shop_id"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ t("shopRequired") }}
                </p>
            </div>

            <!-- Supplier Selection -->
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
                        v-model="saleForm.supplier_id"
                        @change="$emit('supplier-change')"
                        id="supplier_id"
                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 appearance-none"
                        :class="{
                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                isSubmitted && !saleForm.supplier_id,
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
                            {{ supplier.company_name }}
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
                    v-if="isSubmitted && !saleForm.supplier_id"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ t("supplierRequired") }}
                </p>
            </div>

            <!-- Sale Date -->
            <div>
                <label
                    for="sale_date"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    {{ t("saleDate") }}*
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
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <input
                        v-model="saleForm.sale_date"
                        type="date"
                        id="sale_date"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                        :class="{
                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                isSubmitted && !saleForm.sale_date,
                        }"
                        required
                    />
                </div>
                <p
                    v-if="isSubmitted && !saleForm.sale_date"
                    class="mt-2 text-sm text-red-600"
                >
                    {{ t("saleDateRequired") }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    saleForm: {
        shop_id: string;
        supplier_id: string;
        sale_date: string;
    };
    shops: Array<{ id: number; shop_name: string }>;
    suppliers: Array<{ id: number; company_name: string }>;
    isSubmitted: boolean;
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
}>();

defineEmits(["supplier-change"]);
</script>

<style scoped>
input:focus,
select:focus {
    outline: none;
    transform: translateY(-1px);
}

select {
    background-image: none;
}
</style>
