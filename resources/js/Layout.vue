<template>
    <div class="min-h-screen bg-gray-50 p-4">
        <div
            class="flex h-[calc(100vh-2rem)] bg-white rounded-xl shadow-lg overflow-hidden"
        >
            <!-- Sidebar -->
            <div
                :class="[
                    'relative bg-blue-800 text-white rounded-l-xl flex flex-col transition-all duration-300',
                    collapsed ? 'w-20' : 'w-64',
                ]"
            >
                <!-- Collapse Button -->
                <button
                    @click="collapsed = !collapsed"
                    class="absolute top-4 right-2 z-50 text-white hover:text-gray-300 transition"
                >
                    <svg
                        v-if="!collapsed"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </button>

                <!-- Logo / Title -->
                <div class="pt-10 px-4">
                    <h1 v-if="!collapsed" class="text-2xl font-bold">WMS</h1>
                </div>

                <!-- Nav -->
                <nav class="mt-4 flex-1 px-2">
                    <!-- Suppliers Menu -->
                    <button
                        @click="toggleSuppliersMenu"
                        class="w-full flex items-center px-2 py-3 text-left rounded-md hover:bg-blue-700 transition duration-200"
                    >
                        <svg
                            class="w-5 h-5 mr-2 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4"
                            />
                        </svg>
                        <span v-if="!collapsed">Suppliers</span>
                        <svg
                            v-if="!collapsed"
                            class="w-4 h-4 ml-auto transition-transform duration-200"
                            :class="{ 'rotate-180': suppliersMenuOpen }"
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
                    </button>
                    <div
                        v-if="suppliersMenuOpen && !collapsed"
                        class="pl-6 mt-2 space-y-1"
                    >
                        <Link
                            href="/suppliers/create"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition duration-200 flex items-center"
                            :class="{
                                'bg-blue-600':
                                    $page.url.startsWith('/suppliers/create'),
                            }"
                            @click.stop="logNavigation('/suppliers/create')"
                            :preserveState="true"
                            :preserveScroll="true"
                            @error="handleNavigationError"
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
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                            Add Supplier
                        </Link>
                        <Link
                            href="/suppliers/index"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition duration-200 flex items-center"
                            :class="{
                                'bg-blue-600':
                                    $page.url.startsWith('/suppliers/index'),
                            }"
                            @click.stop="logNavigation('/suppliers/index')"
                            :preserveState="true"
                            :preserveScroll="true"
                            @error="handleNavigationError"
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
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                />
                            </svg>
                            Supplier List
                        </Link>
                    </div>

                    <!-- Purchase Management Menu -->
                    <button
                        @click="toggleDepositsMenu"
                        class="w-full flex items-center px-2 py-3 text-left rounded-md hover:bg-blue-700 transition duration-200"
                    >
                        <svg
                            class="w-5 h-5 mr-2 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                            />
                        </svg>
                        <span v-if="!collapsed">Purchase Management</span>
                        <svg
                            v-if="!collapsed"
                            class="w-4 h-4 ml-auto transition-transform duration-200"
                            :class="{ 'rotate-180': depositsMenuOpen }"
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
                    </button>
                    <div
                        v-if="depositsMenuOpen && !collapsed"
                        class="pl-6 mt-2 space-y-1"
                    >
                        <Link
                            href="/deposits"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition duration-200 flex items-center"
                            :class="{
                                'bg-blue-600':
                                    $page.url.startsWith('/deposits'),
                            }"
                            @click.stop="logNavigation('/deposits')"
                            :preserveState="true"
                            :preserveScroll="true"
                            @error="handleNavigationError"
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
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            Manage Deposits
                        </Link>
                        <Link
                            href="/purchases"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition duration-200 flex items-center"
                            :class="{
                                'bg-blue-600':
                                    $page.url.startsWith('/purchases'),
                            }"
                            @click.stop="logNavigation('/purchases')"
                            :preserveState="true"
                            :preserveScroll="true"
                            @error="handleNavigationError"
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
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            Manage Purchase
                        </Link>
                    </div>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 p-6 bg-white rounded-r-xl overflow-y-auto">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const suppliersMenuOpen = ref(false);
const depositsMenuOpen = ref(false);
const collapsed = ref(false);

const toggleSuppliersMenu = () => {
    console.log(
        "Toggling Suppliers Menu. Current state:",
        suppliersMenuOpen.value
    );
    suppliersMenuOpen.value = !suppliersMenuOpen.value;
};

const toggleDepositsMenu = () => {
    console.log(
        "Toggling Purchase Management Menu. Current state:",
        depositsMenuOpen.value
    );
    depositsMenuOpen.value = !depositsMenuOpen.value;
};

const logNavigation = (url) => {
    console.log("Navigating to:", url);
};

const handleNavigationError = (error) => {
    console.error("Navigation error:", error);
};
</script>

<style scoped>
.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgb(3, 113, 209);
}
</style>
