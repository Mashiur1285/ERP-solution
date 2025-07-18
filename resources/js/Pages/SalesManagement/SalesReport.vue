<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Sales Report
        </h1>

        <!-- Filters -->
        <div class="mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label
                        for="start_date"
                        class="block text-sm font-medium text-gray-700"
                        >Start Date</label
                    >
                    <input
                        v-model="filters.start_date"
                        id="start_date"
                        type="date"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                    />
                </div>
                <div>
                    <label
                        for="end_date"
                        class="block text-sm font-medium text-gray-700"
                        >End Date</label
                    >
                    <input
                        v-model="filters.end_date"
                        id="end_date"
                        type="date"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                    />
                </div>
                <div>
                    <label
                        for="shop_id"
                        class="block text-sm font-medium text-gray-700"
                        >Shop</label
                    >
                    <select
                        v-model="filters.shop_id"
                        id="shop_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                    >
                        <option value="">All Shops</option>
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
                        for="product_id"
                        class="block text-sm font-medium text-gray-700"
                        >Product</label
                    >
                    <select
                        v-model="filters.product_id"
                        id="product_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                    >
                        <option value="">All Products</option>
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
                        for="supplier_id"
                        class="block text-sm font-medium text-gray-700"
                        >Supplier</label
                    >
                    <select
                        v-model="filters.supplier_id"
                        id="supplier_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                    >
                        <option value="">All Suppliers</option>
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
            <div class="flex justify-end mt-4">
                <button
                    @click="applyFilters"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    Apply Filters
                </button>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="overflow-x-auto">
            <table
                class="min-w-full bg-white border border-gray-200 rounded-lg"
            >
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Invoice Number
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Shop
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Supplier
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Total Amount
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Total Profit
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Sale Date
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Items
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-700"
                        >
                            Cash Memo / Bill
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sale in sales" :key="sale.id" class="border-t">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ sale.invoice_number }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ sale.shop_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ sale.supplier_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ sale.total_amount }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm"
                            :class="
                                sale.total_profit >= 0
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                        >
                            {{ sale.total_profit }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ sale.sale_date }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span
                                :class="{
                                    'status-pending': sale.status === 'pending',
                                    'status-in_progress':
                                        sale.status === 'in_progress',
                                    'status-completed':
                                        sale.status === 'completed',
                                }"
                                class="px-2 py-1 rounded-full text-sm capitalize"
                            >
                                {{ sale.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button
                                @click="toggleItems(sale.id)"
                                class="text-orange-600 hover:text-orange-700 transition duration-200 bg-orange-100 rounded-lg p-1.5"
                            >
                                {{
                                    expandedSale === sale.id
                                        ? "Hide Items"
                                        : "Show Items"
                                }}
                            </button>
                            <div v-if="expandedSale === sale.id" class="mt-2">
                                <table
                                    class="min-w-full bg-gray-50 border border-gray-200 rounded-lg"
                                >
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-4 py-2 text-sm font-medium text-gray-700"
                                            >
                                                Product
                                            </th>
                                            <th
                                                class="px-4 py-2 text-sm font-medium text-gray-700"
                                            >
                                                Variant
                                            </th>
                                            <th
                                                class="px-4 py-2 text-sm font-medium text-gray-700"
                                            >
                                                Quantity
                                            </th>
                                            <th
                                                class="px-4 py-2 text-sm font-medium text-gray-700"
                                            >
                                                Total Price
                                            </th>
                                            <th
                                                class="px-4 py-2 text-sm font-medium text-gray-700"
                                            >
                                                Profit
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in sale.items"
                                            :key="
                                                item.product_id + item.variant
                                            "
                                        >
                                            <td
                                                class="px-4 py-2 text-sm text-gray-900"
                                            >
                                                {{ item.product_name }}
                                            </td>
                                            <td
                                                class="px-4 py-2 text-sm text-gray-900"
                                            >
                                                {{ item.variant }}
                                            </td>
                                            <td
                                                class="px-4 py-2 text-sm text-gray-900"
                                            >
                                                {{ item.quantity }}
                                            </td>
                                            <td
                                                class="px-4 py-2 text-sm text-gray-900"
                                            >
                                                {{ item.total_price }}
                                            </td>
                                            <td
                                                class="px-4 py-2 text-sm"
                                                :class="
                                                    item.profit >= 0
                                                        ? 'text-green-600'
                                                        : 'text-red-600'
                                                "
                                            >
                                                {{ item.profit }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button
                                @click="viewCashMemo(sale.id)"
                                class="px-3 py-1 bg-teal-300 text-teal-800 rounded-xl hover:bg-teal-400 transition duration-200"
                            >
                                View Receipt
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Layout.vue";

defineOptions({
    layout: Layout,
});

const props = defineProps<{
    sales: {
        id: number;
        shop_id: number;
        shop_name: string;
        supplier_id: number | null;
        supplier_name: string;
        invoice_number: string;
        total_amount: number;
        total_profit: number;
        sale_date: string;
        status: string;
        items: {
            product_id: number;
            product_name: string;
            variant: string;
            quantity: number;
            total_price: number;
            profit: number;
        }[];
    }[];
    shops: { id: number; shop_name: string }[];
    products: { id: number; name: string }[];
    suppliers: { id: number; company_name: string }[];
    filters: {
        start_date: string | null;
        end_date: string | null;
        shop_id: number | null;
        product_id: number | null;
        supplier_id: number | null;
    };
}>();

const filters = ref({
    start_date: props.filters.start_date || "",
    end_date: props.filters.end_date || "",
    shop_id: props.filters.shop_id || "",
    product_id: props.filters.product_id || "",
    supplier_id: props.filters.supplier_id || "",
});

const expandedSale = ref<number | null>(null);

const toggleItems = (saleId: number) => {
    expandedSale.value = expandedSale.value === saleId ? null : saleId;
};

const applyFilters = () => {
    router.get("/sales/report", filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const viewCashMemo = (saleId: number) => {
    router.visit(`/sales/cash-memo/${saleId}`);
};
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 0.75rem;
    text-align: left;
}

thead {
    background-color: #f9fafb;
}

tbody tr:hover {
    background-color: #f1f5f9;
}

.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(59, 130, 246, 0.2);
}

.status-pending {
    background-color: #8c56e3; /* Yellow background */
    color: #ffffff; /* Dark font (gray-900) */
}

.status-in_progress {
    background-color: #3b82f6; /* Blue background */
    color: #ffffff; /* White font */
}

.status-completed {
    background-color: #10b981; /* Green background */
    color: #ffffff; /* White font */
}
</style>
