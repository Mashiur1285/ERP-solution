<template>
    <div
        class="p-4 sm:p-6 space-y-6 sm:space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
    >
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 sm:mb-8 border-b border-indigo-100 pb-4"
        >
            <h1
                class="text-2xl sm:text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-indigo-50 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-8 h-8 text-indigo-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.134-1.272.37-1.839m0 0C5.074 15.39 4 13.904 4 12V6a2 2 0 012-2h12a2 2 0 012 2v6c0 1.904-1.074 3.39-2.37 4.161M16 16v-4a2 2 0 00-2-2h-4a2 2 0 00-2 2v4m4 0h2m-2 0h-2m-6 0a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2z"
                        />
                    </svg>
                </div>
                Supplier List
            </h1>
            <a
                    href="/suppliers/create"
                    class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
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
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        ></path>
                    </svg>
                    <span>Add Supplier</span>
                </a>
        </div>

        <!-- Main Container -->
        <div
            class="bg-white rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl relative overflow-hidden"
        >
            <!-- Background Pattern -->
            <svg
                class="absolute inset-0 w-full h-full opacity-5 pointer-events-none"
                viewBox="0 0 1440 320"
                preserveAspectRatio="none"
            >
                <path
                    fill="#e0e7ff"
                    fill-opacity="0.1"
                    d="M0,160L48,138.7C96,117,192,75,288,80C384,85,480,139,576,149.3C672,160,768,128,864,106.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                />
            </svg>

            <div class="relative z-10 p-4 sm:p-8">
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8 gap-2">
                    <h2
                        class="text-xl sm:text-2xl font-semibold text-gray-800 flex items-center"
                    >
                        <div
                            class="p-2 mr-3 bg-indigo-50 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-indigo-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                        </div>
                        All Suppliers
                    </h2>
                    <div class="text-sm text-gray-600">
                        Total:
                        <span class="font-semibold text-indigo-600">{{
                            suppliers.length
                        }}</span>
                        suppliers
                    </div>
                </div>

                <!-- Table Container -->
                <div
                    class="bg-white rounded-xl border border-indigo-50 overflow-hidden"
                >
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="min-w-full divide-y divide-indigo-100">
                            <thead class="bg-indigo-50">
                                <th
                                    scope="col"
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider"
                                >
                                    Company
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider hidden sm:table-cell"
                                >
                                    Branch
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider"
                                >
                                    Contact
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider hidden md:table-cell"
                                >
                                    Website
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </thead>
                            <tbody class="bg-white divide-y divide-indigo-100">
                                <tr
                                    v-for="(supplier, index) in suppliers"
                                    :key="supplier.id"
                                    class="hover:bg-indigo-100 transition-all duration-200"
                                    :class="{ 'bg-indigo-50': index % 2 === 1 }"
                                >
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-semibold text-gray-900"
                                        >
                                            {{ supplier.company_name ?? "-" }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ supplier.branch_name ?? "-" }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ supplier.phone_number ?? "-" }}
                                        </div>
                                        <div
                                            v-if="supplier.email"
                                            class="text-xs text-indigo-600"
                                        >
                                            {{ supplier.email }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                        <a
                                            v-if="supplier.website"
                                            :href="
                                                supplier.website.startsWith(
                                                    'http'
                                                )
                                                    ? supplier.website
                                                    : `https://${supplier.website}`
                                            "
                                            target="_blank"
                                            class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors group"
                                        >
                                            <span class="truncate max-w-32">{{
                                                supplier.website
                                            }}</span>
                                            <svg
                                                class="w-3 h-3 ml-1 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                ></path>
                                            </svg>
                                        </a>
                                        <span
                                            v-else
                                            class="text-sm text-gray-400"
                                            >-</span
                                        >
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <a
                                                :href="`/suppliers/${supplier.id}/edit`"
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 group"
                                            >
                                                <svg
                                                    class="w-4 h-4 group-hover:scale-110 transition-transform"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                                    />
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="suppliers.length === 0">
                                    <td
                                        colspan="5"
                                        class="px-6 py-12 text-center"
                                    >
                                        <div
                                            class="flex flex-col items-center space-y-4"
                                        >
                                            <div
                                                class="p-4 bg-indigo-50 rounded-full"
                                            >
                                                <svg
                                                    class="w-8 h-8 text-indigo-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.134-1.272.37-1.839m0 0C5.074 15.39 4 13.904 4 12V6a2 2 0 012-2h12a2 2 0 012 2v6c0 1.904-1.074 3.39-2.37 4.161M16 16v-4a2 2 0 00-2-2h-4a2 2 0 00-2 2v4m4 0h2m-2 0h-2m-6 0a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2z"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="text-lg font-semibold text-gray-900 mb-1"
                                                >
                                                    No suppliers found
                                                </h3>
                                                <p class="text-gray-500 mb-4">
                                                    Get started by adding your
                                                    first supplier
                                                </p>
                                                <a
                                                    href="/suppliers/create"
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-200 to-purple-200 text-gray-800 font-semibold rounded-lg hover:from-indigo-300 hover:to-purple-300 transition-colors"
                                                >
                                                    <svg
                                                        class="w-4 h-4 mr-2"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                                        ></path>
                                                    </svg>
                                                    Add First Supplier
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Layout from "../../Layout.vue";

interface Supplier {
    id: number;
    company_name: string;
    branch_name: string;
    phone_number: string;
    emergency_phone_number: string;
    address: string;
    email: string;
    country: string;
    city: string;
    website: string;
    notes: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    suppliers: Supplier[];
}>();

defineOptions({
    layout: Layout,
});

console.log("Index.vue component loaded");
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;700&display=swap");

body,
html {
    font-family: "Noto Serif Bengali", Arial, sans-serif;
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
    animation: fadeIn 1s ease-out;
}

/* Custom scrollbar for horizontal overflow */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #a5b4fc;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f3f4f6;
}

/* Table styling */
table {
    border-collapse: separate;
    border-spacing: 0;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group:hover .group-hover\:translate-x-0\.5 {
    transform: translateX(0.125rem);
}

.group:hover .group-hover\:-translate-y-0\.5 {
    transform: translateY(-0.125rem);
}

/* Custom Tailwind color definitions */
.bg-indigo-25 {
    background-color: rgb(238 242 255 / 0.3);
}
</style>
```
