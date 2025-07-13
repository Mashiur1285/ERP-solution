<template>
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div>
            <h1 class="font-bold text-2xl py-4">Shop List</h1>
        </div>
        <table class="min-w-full divide-y divide-gray-400">
            <thead class="bg-gray-50">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Shop Name
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Owner Name
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Contact
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Email
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Website
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr
                    v-for="shop in shops"
                    :key="shop.id"
                    class="hover:bg-gray-50 transition-colors"
                >
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ shop.shop_name ?? "-" }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ shop.owner_name ?? "-" }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ shop.phone_number ?? "-" }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ shop.email ?? "-" }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a
                            :href="shop.website"
                            target="_blank"
                            class="text-sm text-blue-600 hover:text-blue-800 hover:underline"
                        >
                            {{ shop.website ?? "-" }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a
                            :href="`/shops/${shop.id}/edit`"
                            class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold text-blue-800 bg-indigo-100 rounded-[300px] hover:bg-indigo-200 transition"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                />
                                <path
                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                />
                            </svg>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import Layout from "../../Layout.vue";

interface Shop {
    id: number;
    shop_name: string;
    owner_name: string | null;
    phone_number: string;
    email: string | null;
    website: string | null;
}

const props = defineProps<{
    shops: Shop[];
}>();

defineOptions({
    layout: Layout,
});

console.log("ShopIndex.vue component loaded");
</script>

<style scoped>
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
