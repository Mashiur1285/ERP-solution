<!-- resources/js/Pages/DepositManagement/Purchase.vue -->

<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Purchase Management
        </h1>

        <!-- Purchase Products Form -->
        <div class="space-y-6 mb-12 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-800">
                Purchase Products
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label
                        for="product_name"
                        class="block text-sm font-medium text-gray-700"
                        >Product Name*</label
                    >
                    <input
                        v-model="productForm.product_name"
                        id="product_name"
                        type="text"
                        placeholder="Enter product name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    />
                </div>
                <div>
                    <label
                        for="purchase_supplier_id"
                        class="block text-sm font-medium text-gray-700"
                        >Supplier*</label
                    >
                    <select
                        v-model="productForm.supplier_id"
                        id="purchase_supplier_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    >
                        <option value="" disabled>Select a supplier</option>
                        <option
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            :value="supplier.id"
                        >
                            {{ supplier.company_name }} (Remaining Deposit:
                            {{ supplier.remaining_deposit.toFixed(2) }} TK)
                        </option>
                    </select>
                </div>
            </div>
            <div
                v-for="(variant, index) in productForm.variants"
                :key="index"
                class="space-y-4 border-t pt-4 mt-4"
            >
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div>
                        <label
                            :for="'variant_' + index"
                            class="block text-sm font-medium text-gray-700"
                            >Variant Name*</label
                        >
                        <input
                            v-model="variant.variant"
                            placeholder="Ex: 500 ml"
                            type="text"
                            :id="'variant_' + index"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                        />
                    </div>
                    <div>
                        <label
                            :for="'quantity_' + index"
                            class="block text-sm font-medium text-gray-700"
                            >Quantity*</label
                        >
                        <input
                            v-model.number="variant.quantity"
                            type="number"
                            :id="'quantity_' + index"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                        />
                    </div>
                    <div>
                        <label
                            :for="'boxes_' + index"
                            class="block text-sm font-medium text-gray-700"
                            >Bottles per Box*</label
                        >
                        <input
                            v-model.number="variant.bottles_per_box"
                            type="number"
                            :id="'boxes_' + index"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                        />
                    </div>
                    <div>
                        <label
                            :for="'free_bottles_' + index"
                            class="block text-sm font-medium text-gray-700"
                            >Free Bottles</label
                        >
                        <input
                            v-model.number="variant.free_bottles"
                            type="number"
                            :id="'free_bottles_' + index"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        />
                    </div>
                    <div>
                        <label
                            :for="'unit_price_' + index"
                            class="block text-sm font-medium text-gray-700"
                            >Unit Price in TK*</label
                        >
                        <input
                            v-model.number="variant.unit_price"
                            type="number"
                            step="0.01"
                            :id="'unit_price_' + index"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                        />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button
                        v-if="productForm.variants.length > 1"
                        @click="removeVariant(index)"
                        class="text-red-600 hover:text-red-800 p-2 rounded-full transition duration-200 bg-red-100 hover:bg-red-200"
                        title="Remove Variant"
                        aria-label="Remove Variant"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <button
                    @click="addVariant"
                    class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200"
                >
                    Add Variant
                </button>
                <button
                    @click="submitProduct"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    Add Purchase
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface Supplier {
    id: number;
    company_name: string;
    remaining_deposit: number; // Added remaining_deposit
}

interface ProductVariant {
    variant: string;
    quantity: number;
    bottles_per_box: number;
    free_bottles: number;
    unit_price: number;
}

const props = defineProps<{
    suppliers: Supplier[];
}>();

defineOptions({
    layout: Layout,
});

const productForm = ref({
    product_name: "",
    supplier_id: "",
    variants: [
        {
            variant: "",
            quantity: 0,
            bottles_per_box: 0,
            free_bottles: 0,
            unit_price: 0,
        },
    ],
});

const addVariant = () => {
    productForm.value.variants.push({
        variant: "",
        quantity: 0,
        bottles_per_box: 0,
        free_bottles: 0,
        unit_price: 0,
    });
};

const removeVariant = (index: number) => {
    productForm.value.variants.splice(index, 1);
};

const submitProduct = () => {
    console.log("Submitting product:", productForm.value);
    router.post("/products-store", productForm.value, {
        onSuccess: () => {
            productForm.value = {
                product_name: "",
                supplier_id: "",
                variants: [
                    {
                        variant: "",
                        quantity: 0,
                        bottles_per_box: 0,
                        free_bottles: 0,
                        unit_price: 0,
                    },
                ],
            };
        },
        onError: (errors) => {
            console.error("Product submission errors:", errors);
        },
    });
};

console.log("Purchase.vue component loaded");
</script>

<style scoped>
input,
select,
textarea {
    padding: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
}

.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgb(142, 173, 200);
}
</style>
