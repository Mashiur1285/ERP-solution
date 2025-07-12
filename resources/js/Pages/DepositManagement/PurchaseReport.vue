<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Purchase Report
        </h1>

        <!-- Purchase History -->
        <div class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                Purchase History
            </h2>
            <div
                v-if="!purchaseHistory.length"
                class="text-center text-gray-500 py-4"
            >
                No purchases found.
            </div>
            <table v-else class="min-w-full divide-y divide-gray-400">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Supplier
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Product Name
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Variant
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Bottles per Box
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Boxes
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Free Bottles
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Total Value (TK)
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="(purchase, index) in purchaseHistory"
                        :key="index"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ purchase.supplier_name || "-" }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ purchase.product_name || "-" }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ purchase.variant || "N/A" }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ purchase.bottles_per_box || 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ purchase.quantity || 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ purchase.free_bottles || 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ (purchase.total_value || 0).toFixed(2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{
                                    purchase.purchase_date
                                        ? new Date(
                                              purchase.purchase_date
                                          ).toLocaleString()
                                        : "-"
                                }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import Layout from "../../Layout.vue";

interface Purchase {
    supplier_name: string;
    product_name: string;
    variant: string;
    bottles_per_box: number;
    quantity: number;
    free_bottles: number;
    unit_price: number;
    total_value: number;
    purchase_date: string;
}

const props = defineProps<{
    purchaseHistory: Purchase[];
}>();

defineOptions({
    layout: Layout,
});

console.log("PurchaseReport.vue component loaded");
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

table {
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 0.5rem;
    overflow: hidden;
}

thead th:first-child {
    border-top-left-radius: 0.5rem;
}

thead th:last-child {
    border-top-right-radius: 0.5rem;
}

tbody tr:last-child td:first-child {
    border-bottom-left-radius: 0.5rem;
}

tbody tr:last-child td:last-child {
    border-bottom-right-radius: 0.5rem;
}
</style>
