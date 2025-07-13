<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Create New Sale
        </h1>

        <!-- Sale Creation Form -->
        <div class="space-y-6 mb-12 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-800">Sale Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label
                        for="shop_id"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Shop*
                    </label>
                    <select
                        v-model="saleForm.shop_id"
                        id="shop_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    >
                        <option value="" disabled>Select a shop</option>
                        <option
                            v-for="shop in shops"
                            :key="shop.id"
                            :value="shop.id"
                        >
                            {{ shop.shop_name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label
                        for="sale_date"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Sale Date*
                    </label>
                    <input
                        v-model="saleForm.sale_date"
                        id="sale_date"
                        type="date"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    />
                </div>
            </div>

            <!-- Sale Items -->
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                    Sale Items
                </h3>
                <div
                    v-for="(item, index) in saleForm.items"
                    :key="index"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-white rounded-lg shadow-sm"
                >
                    <div>
                        <label
                            :for="'product_id_' + index"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Product*
                        </label>
                        <select
                            v-model="item.product_id"
                            :id="'product_id_' + index"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                            @change="updateAvailableVariants(index)"
                        >
                            <option value="" disabled>Select a product</option>
                            <option
                                v-for="product in products"
                                :key="product.id"
                                :value="product.id"
                            >
                                {{ product.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            :for="'variant_' + index"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Variant*
                        </label>
                        <select
                            v-model="item.variant"
                            :id="'variant_' + index"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                        >
                            <option value="" disabled>Select a variant</option>
                            <option
                                v-for="variant in item.availableVariants"
                                :key="variant"
                                :value="variant"
                            >
                                {{ variant }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            :for="'boxes_sold_' + index"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Sell Boxs*
                        </label>
                        <input
                            v-model.number="item.boxes_sold"
                            :id="'boxes_sold_' + index"
                            type="number"
                            min="0"
                            placeholder="Enter boxes sold"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                            @input="calculateItemTotal(index)"
                        />
                    </div>
                    <div>
                        <label
                            :for="'bottles_per_box_' + index"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Sell Bottles per Box*
                        </label>
                        <input
                            v-model.number="item.bottles_per_box"
                            :id="'bottles_per_box_' + index"
                            type="number"
                            min="0"
                            placeholder="Enter bottles per box"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                        />
                    </div>
                    <div>
                        <label
                            :for="'free_bottles_' + index"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Sell Free Bottles
                        </label>
                        <input
                            v-model.number="item.free_bottles"
                            :id="'free_bottles_' + index"
                            type="number"
                            min="0"
                            placeholder="Enter free bottles"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        />
                    </div>
                    <div>
                        <label
                            :for="'unit_price_' + index"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Unit Price*
                        </label>
                        <input
                            v-model.number="item.unit_price"
                            :id="'unit_price_' + index"
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="Enter unit price"
                            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                            required
                            @input="calculateItemTotal(index)"
                        />
                    </div>
                    <div class="flex items-center">
                        <button
                            v-if="saleForm.items.length > 1"
                            @click="removeItem(index)"
                            class="text-red-600 hover:text-red-800"
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
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <button
                    @click="addItem"
                    class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200"
                >
                    Add Item
                </button>
            </div>

            <div class="flex justify-end mt-6">
                <button
                    @click="createSale"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    Create Sale
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface Shop {
    id: number;
    shop_name: string;
}

interface Product {
    id: number;
    name: string;
}

interface Supplier {
    id: number;
    company_name: string;
}

interface SaleItem {
    product_id: number | null;
    supplier_id: number | null;
    variant: string | null;
    boxes_sold: number;
    bottles_per_box: number;
    free_bottles: number;
    unit_price: number;
    total_value: number;
    availableVariants: string[];
}

interface Sale {
    shop_id: number | null;
    sale_date: string;
    items: SaleItem[];
}

const props = defineProps<{
    shops: Shop[];
    products: Product[];
    inventory: { product_id: number; supplier_id: number; variant: string }[];
}>();

const saleForm = ref<Sale>({
    shop_id: null,
    sale_date: new Date().toISOString().split("T")[0],
    items: [
        {
            product_id: null,
            supplier_id: null,
            variant: null,
            boxes_sold: 0,
            bottles_per_box: 0,
            free_bottles: 0,
            unit_price: 0,
            total_value: 0,
            availableVariants: [],
        },
    ],
});

defineOptions({
    layout: Layout,
});

const addItem = () => {
    saleForm.value.items.push({
        product_id: null,
        supplier_id: null,
        variant: null,
        boxes_sold: 0,
        bottles_per_box: 0,
        free_bottles: 0,
        unit_price: 0,
        total_value: 0,
        availableVariants: [],
    });
};

const removeItem = (index: number) => {
    saleForm.value.items.splice(index, 1);
};

const updateAvailableVariants = (index: number) => {
    const item = saleForm.value.items[index];
    if (item.product_id && item.supplier_id) {
        item.availableVariants = props.inventory
            .filter(
                (inv) =>
                    inv.product_id === item.product_id &&
                    inv.supplier_id === item.supplier_id
            )
            .map((inv) => inv.variant);
        item.variant = item.availableVariants.includes(item.variant)
            ? item.variant
            : null;
    } else {
        item.availableVariants = [];
        item.variant = null;
    }
};

const calculateItemTotal = (index: number) => {
    const item = saleForm.value.items[index];
    item.total_value = item.boxes_sold * item.unit_price;
};

const createSale = () => {
    router.post("/sales/store", saleForm.value, {
        onSuccess: () => {
            saleForm.value = {
                shop_id: null,
                sale_date: new Date().toISOString().split("T")[0],
                items: [
                    {
                        product_id: null,
                        supplier_id: null,
                        variant: null,
                        boxes_sold: 0,
                        bottles_per_box: 0,
                        free_bottles: 0,
                        unit_price: 0,
                        total_value: 0,
                        availableVariants: [],
                    },
                ],
            };
            console.log("Sale created successfully");
        },
        onError: (errors) => {
            console.error("Sale creation errors:", errors);
        },
    });
};

console.log("CreateSale.vue component loaded");
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
