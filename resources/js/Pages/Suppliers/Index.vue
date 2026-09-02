<template>
    <div
        class="px-1 py-4 sm:p-6 space-y-6 sm:space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
    >
        <!-- Toast Notification -->
        <div
            v-if="toast.show"
            class="fixed top-20 left-3 right-3 sm:left-auto sm:right-6 sm:max-w-md px-4 py-4 sm:px-7 sm:py-5 rounded-lg shadow-lg flex items-center space-x-3 animate-toast-in z-[200]"
            :class="toastClasses"
            role="alert"
        >
            <svg
                class="w-5 h-5 text-white flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    v-if="toast.type === 'success'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
                <path
                    v-else-if="toast.type === 'warning'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
                <path
                    v-else
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <span class="font-medium text-white flex-1 min-w-0 text-sm sm:text-base">{{ toast.message }}</span>
            <button
                @click="toast.show = false"
                class="ml-2 flex-shrink-0 text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white transition-colors"
                aria-label="Close notification"
            >
                <svg
                    class="w-4 h-4"
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

        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 sm:mb-8 border-b border-indigo-100 pb-4"
        >
            <h1
                class="text-xl sm:text-2xl lg:text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-2 sm:mr-3 bg-indigo-50 rounded-full flex items-center justify-center flex-shrink-0"
                >
                    <svg
                        class="w-6 h-6 sm:w-8 sm:h-8 text-indigo-400"
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

            <div class="relative z-10 p-3 sm:p-6 lg:p-8">
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8 gap-2">
                    <h2
                        class="text-lg sm:text-xl lg:text-2xl font-semibold text-gray-800 flex items-center"
                    >
                        <div
                            class="p-2 mr-2 sm:mr-3 bg-indigo-50 rounded-full flex items-center justify-center flex-shrink-0"
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
                    <!-- Empty State (shared) -->
                    <div
                        v-if="suppliers.length === 0"
                        class="px-4 py-12 text-center"
                    >
                        <div class="flex flex-col items-center space-y-4">
                            <div class="p-4 bg-indigo-50 rounded-full">
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
                                    Get started by adding your first supplier
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
                    </div>

                    <template v-else>
                        <!-- Supplier Table (Desktop) -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-indigo-100">
                                <thead class="bg-indigo-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider"
                                        >
                                            Company
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider"
                                        >
                                            Branch
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider"
                                        >
                                            Contact
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 lg:px-6 py-3 lg:py-4 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider hidden lg:table-cell"
                                        >
                                            Website
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-4 lg:px-6 py-3 lg:py-4 text-right text-xs font-semibold text-gray-800 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-indigo-100">
                                    <tr
                                        v-for="(supplier, index) in suppliers"
                                        :key="supplier.id"
                                        class="hover:bg-indigo-100 transition-all duration-200"
                                        :class="{ 'bg-indigo-50': index % 2 === 1 }"
                                    >
                                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                                            <div
                                                class="text-sm font-semibold text-gray-900"
                                            >
                                                {{ supplier.company_name ?? "-" }}
                                            </div>
                                        </td>
                                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ supplier.branch_name ?? "-" }}
                                            </div>
                                        </td>
                                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900 whitespace-nowrap"
                                            >
                                                {{ supplier.phone_number ?? "-" }}
                                            </div>
                                            <div
                                                v-if="supplier.email"
                                                class="text-xs text-indigo-600 break-all"
                                            >
                                                {{ supplier.email }}
                                            </div>
                                        </td>
                                        <td class="px-4 lg:px-6 py-3 lg:py-4 hidden lg:table-cell">
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
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors group"
                                            >
                                                <span class="truncate max-w-32">{{
                                                    supplier.website
                                                }}</span>
                                                <svg
                                                    class="w-3 h-3 ml-1 flex-shrink-0 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"
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
                                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                                            <div
                                                class="flex items-center justify-end gap-2"
                                            >
                                                <a
                                                    :href="`/suppliers/${supplier.id}/edit`"
                                                    class="inline-flex items-center gap-2 px-3 lg:px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 group"
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
                                                <button
                                                    @click="deleteSupplier(supplier.id)"
                                                    class="inline-flex items-center gap-2 px-3 lg:px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-all duration-200"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Supplier Cards (Mobile) -->
                        <div class="md:hidden divide-y divide-indigo-100">
                            <div
                                v-for="supplier in suppliers"
                                :key="supplier.id"
                                class="p-4"
                            >
                                <!-- Company + branch -->
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="text-base font-semibold text-gray-900 leading-tight break-words min-w-0">
                                        {{ supplier.company_name ?? "-" }}
                                    </h3>
                                    <span
                                        v-if="supplier.branch_name"
                                        class="inline-flex flex-shrink-0 items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium"
                                    >
                                        {{ supplier.branch_name }}
                                    </span>
                                </div>

                                <!-- Contact details -->
                                <div class="mt-3 space-y-2 text-sm">
                                    <a
                                        v-if="supplier.phone_number"
                                        :href="`tel:${supplier.phone_number}`"
                                        class="flex items-center gap-2 text-gray-700"
                                    >
                                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="font-medium">{{ supplier.phone_number }}</span>
                                    </a>
                                    <a
                                        v-if="supplier.email"
                                        :href="`mailto:${supplier.email}`"
                                        class="flex items-center gap-2 text-indigo-600"
                                    >
                                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="break-all">{{ supplier.email }}</span>
                                    </a>
                                    <a
                                        v-if="supplier.website"
                                        :href="
                                            supplier.website.startsWith('http')
                                                ? supplier.website
                                                : `https://${supplier.website}`
                                        "
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="flex items-center gap-2 text-indigo-600"
                                    >
                                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                                        </svg>
                                        <span class="break-all">{{ supplier.website }}</span>
                                    </a>
                                    <p
                                        v-if="supplier.city || supplier.country"
                                        class="flex items-center gap-2 text-gray-500"
                                    >
                                        <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ [supplier.city, supplier.country].filter(Boolean).join(", ") }}</span>
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <a
                                        :href="`/suppliers/${supplier.id}/edit`"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>
                                    <button
                                        @click="deleteSupplier(supplier.id)"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Layout from "../../Layout.vue";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

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

const deleteSupplier = (id: number) => {
    if (!confirm('Are you sure you want to delete this supplier?')) return;
    router.delete(route('suppliers.destroy', { id }), { preserveScroll: true });
};

// Toast driven by the server's flash messages (create redirect, delete, etc.)
const page = usePage();

const toast = ref({ show: false, message: "", type: "success" });
let toastTimer: ReturnType<typeof setTimeout> | null = null;

const toastClasses = computed(() => ({
    "bg-green-500": toast.value.type === "success",
    "bg-orange-500": toast.value.type === "warning",
    "bg-red-500": toast.value.type === "error",
}));

const showToast = (message: string, type = "success") => {
    if (toastTimer) clearTimeout(toastTimer);
    toast.value = { show: true, message, type };
    toastTimer = setTimeout(() => {
        toast.value.show = false;
    }, 5000);
};

const readFlash = () => {
    const flash = (page.props.flash ?? {}) as Record<string, string | null>;
    if (flash.success) showToast(flash.success, "success");
    else if (flash.error) showToast(flash.error, "error");
    else if (flash.warning) showToast(flash.warning, "warning");
    else if (flash.info) showToast(flash.info, "success");
};

onMounted(readFlash);
watch(() => page.props.flash, readFlash, { deep: true });
onUnmounted(() => {
    if (toastTimer) clearTimeout(toastTimer);
});
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

@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
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
