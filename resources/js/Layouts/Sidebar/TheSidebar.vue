<template>
    <!-- Sidebar -->
    <aside
        id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-72 h-screen transition-transform bg-white border-r border-gray-200 shadow-xl"
        :class="sidebarClasses"
        aria-label="Sidebar"
    >
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-[64px] px-4 border-b border-gray-100">
            <!-- Brand -->
            <Link :href="route('dashboard')" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 3h8v8H3zm10 0h8v8h-8zM3 13h8v8H3zm10 0h8v8h-8z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-800">ERP Solution</span>
            </Link>

            <!-- Close button (mobile only) -->
            <button
                @click="$emit('close')"
                class="sm:hidden inline-flex items-center justify-center w-8 h-8 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors focus:outline-none"
                aria-label="Close sidebar"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Items -->
        <div class="h-[calc(100vh-64px)] px-3 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-1 pt-3 font-medium">
                <!-- Dashboard -->
                <SidebarSingleLink
                    v-if="hasPermission('dashboard.view')"
                    label="Dashboard"
                    href="/dashboard"
                    icon="fa-solid fa-house"
                    @link-clicked="$emit('close')"
                />

                <!-- Suppliers -->
                <SidebarMultiLevelMenu
                    v-if="suppliersMenu.length"
                    label="Suppliers"
                    icon="fa-solid fa-users"
                    :submenu="suppliersMenu"
                    @link-clicked="$emit('close')"
                />

                <!-- Lift -->
                <SidebarMultiLevelMenu
                    v-if="purchaseMenu.length"
                    label="Lift"
                    icon="fa-solid fa-credit-card"
                    :submenu="purchaseMenu"
                    @link-clicked="$emit('close')"
                />

                <!-- Sales -->
                <SidebarMultiLevelMenu
                    v-if="salesMenu.length"
                    label="Sales"
                    icon="fa-solid fa-shopping-bag"
                    :submenu="salesMenu"
                    @link-clicked="$emit('close')"
                />

                <!-- Expense -->
                <SidebarMultiLevelMenu
                    v-if="expenseMenu.length"
                    label="Expense"
                    icon="fa-solid fa-money-bill-wave"
                    :submenu="expenseMenu"
                    @link-clicked="$emit('close')"
                />

                <!-- Inventory -->
                <SidebarMultiLevelMenu
                    v-if="inventoryMenu.length"
                    label="Inventory"
                    icon="fa-solid fa-box"
                    :submenu="inventoryMenu"
                    @link-clicked="$emit('close')"
                />

                <SidebarMultiLevelMenu
                    v-if="aclMenu.length"
                    label="ACL"
                    icon="fa-solid fa-user-shield"
                    :submenu="aclMenu"
                    @link-clicked="$emit('close')"
                />
            </ul>

            <!-- User info at bottom (mobile only) -->
            <div class="sm:hidden mt-6 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-3 px-2 py-3 rounded-lg bg-indigo-50">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $page.props.auth?.user?.name || 'User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $page.props.auth?.user?.email || '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</template>

<script setup>
import SidebarSingleLink from "@/Layouts/Sidebar/Partials/SidebarSingleLevelMenu.vue";
import SidebarMultiLevelMenu from "@/Layouts/Sidebar/Partials/SidebarMultiLevelMenu.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close']);

const page = usePage();
const permissions = computed(() => page.props.userPermissions || []);
const hasPermission = (permission) => permissions.value.includes(permission);

const sidebarClasses = computed(() => {
    return {
        // Desktop: always visible
        'sm:translate-x-0': true,
        // Mobile: slide in/out based on isOpen
        'translate-x-0': props.isOpen,
        '-translate-x-full': !props.isOpen,
    };
});

// Suppliers submenu
const suppliersMenu = computed(() => [
    {
        label: "Add Supplier",
        href: "/suppliers/create",
        icon: "fa-solid fa-plus",
        visible: hasPermission("supplier.add"),
    },
    {
        label: "Supplier List",
        href: "/suppliers/index",
        icon: "fa-solid fa-list",
        visible: hasPermission("supplier.view"),
    },
].filter((item) => item.visible));

// Purchase submenu
const purchaseMenu = computed(() => [
    {
        label: "Manage Deposits",
        href: "/deposits",
        icon: "fa-solid fa-money-check",
        visible: hasPermission("deposit.view"),
    },
    {
        label: "Manage Category",
        href: "/categories/index",
        icon: "fa-solid fa-folder",
        visible: hasPermission("category.view"),
    },
    {
        label: "Manage Brand",
        href: "/brands/index",
        icon: "fa-solid fa-tag",
        visible: hasPermission("brand.view"),
    },
    {
        label: "Manage Lift",
        href: "/lifts",
        icon: "fa-solid fa-shopping-cart",
        color: "green",
        visible: hasPermission("lift.add"),
    },
    {
        label: "Lifting Report",
        href: "/lifts/report",
        icon: "fa-solid fa-file-alt",
        visible: hasPermission("lift.view"),
    },
].filter((item) => item.visible));

// Sales submenu
const salesMenu = computed(() => [
    {
        label: "Create Shop",
        href: "/shops/create",
        icon: "fa-solid fa-plus",
        visible: hasPermission("shop.add"),
    },
    {
        label: "Shop List",
        href: "/shops",
        icon: "fa-solid fa-list",
        visible: hasPermission("shop.view"),
    },
    {
        label: "Create Sale",
        href: "/sales",
        icon: "fa-solid fa-plus-circle",
        color: "orange",
        visible: hasPermission("sales.add"),
    },
    {
        label: "Sales Report",
        href: "/sales/report",
        icon: "fa-solid fa-file-alt",
        visible: hasPermission("sales.view"),
    },
].filter((item) => item.visible));

// Expense submenu
const expenseMenu = computed(() => [
    {
        label: "Expense Management",
        href: "/expenses",
        icon: "fa-solid fa-clipboard-list",
        visible: hasPermission("expense.view"),
    },
].filter((item) => item.visible));

// Inventory submenu
const inventoryMenu = computed(() => [
    {
        label: "Inventory Report",
        href: "/inventory/report",
        icon: "fa-solid fa-file-alt",
        visible: hasPermission("inventory.view"),
    },
].filter((item) => item.visible));

const aclMenu = computed(() => [
    {
        label: "Role Management",
        href: "/roles",
        icon: "fa-solid fa-user-shield",
        visible: hasPermission("role.view"),
    },
    {
        label: "User Management",
        href: "/users",
        icon: "fa-solid fa-users-gear",
        visible: hasPermission("user.view"),
    },
].filter((item) => item.visible));
</script>

<style scoped>
aside {
    /* Ensure smooth slide transition */
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
/* Scrollbar styling */
div::-webkit-scrollbar {
    width: 4px;
}
div::-webkit-scrollbar-track {
    background: transparent;
}
div::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 4px;
}
div::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>
