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
                            {{ supplier.company_name }}
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
                            placeholder="EX: 500 ml"
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
                            v-model="variant.quantity"
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
                            v-model="variant.bottles_per_box"
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
                            v-model="variant.free_bottles"
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
                            v-model="variant.unit_price"
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
                        class="text-red-600 hover:text-red-800 text-sm"
                    >
                        Remove Variant
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
