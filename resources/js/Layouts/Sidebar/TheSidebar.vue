<template>
    <aside
        id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 shadow"
        aria-label="Sidebar"
    >
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <SidebarSingleLink
                    label="Dashboard"
                    href="/dashboard"
                    icon="fa-solid fa-house"
                />

                <!-- Suppliers -->
                <SidebarMultiLevelMenu
                    label="Suppliers"
                    icon="fa-solid fa-users"
                    :submenu="suppliersMenu"
                />

                <!-- Purchase Management -->
                <SidebarMultiLevelMenu
                    label="Purchase"
                    icon="fa-solid fa-credit-card"
                    :submenu="purchaseMenu"
                />

                <!-- Sales Management -->
                <SidebarMultiLevelMenu
                    label="Sales"
                    icon="fa-solid fa-shopping-bag"
                    :submenu="salesMenu"
                />

                <!-- Expense Management -->
                <SidebarMultiLevelMenu
                    label="Expense"
                    icon="fa-solid fa-money-bill-wave"
                    :submenu="expenseMenu"
                />

                <!-- Inventory Management -->
                <SidebarMultiLevelMenu
                    label="Inventory"
                    icon="fa-solid fa-box"
                    :submenu="inventoryMenu"
                />
            </ul>
        </div>
    </aside>
</template>

<script setup>
import SidebarSingleLink from "@/Layouts/Sidebar/Partials/SidebarSingleLevelMenu.vue";
import SidebarMultiLevelMenu from "@/Layouts/Sidebar/Partials/SidebarMultiLevelMenu.vue";
import { ref } from "vue";

// Suppliers submenu
const suppliersMenu = ref([
    {
        label: "Add Supplier",
        href: "/suppliers/create",
        icon: "fa-solid fa-plus",
    },
    {
        label: "Supplier List",
        href: "/suppliers/index",
        icon: "fa-solid fa-list",
    },
]);

// Purchase submenu
const purchaseMenu = ref([
    {
        label: "Manage Deposits",
        href: "/deposits",
        icon: "fa-solid fa-money-check",
    },
    {
        label: "Manage Category",
        href: "/categories/index",
        icon: "fa-solid fa-folder",
    },
    {
        label: "Manage Brand",
        href: "/brands/index",
        icon: "fa-solid fa-tag",
    },
    {
        label: "Manage Purchase",
        href: "/purchases",
        icon: "fa-solid fa-shopping-cart",
    },
    {
        label: "Lifting Report",
        href: "/purchases/report",
        icon: "fa-solid fa-file-alt",
    },
]);

// Sales submenu
const salesMenu = ref([
    {
        label: "Create Shop",
        href: "/shops/create",
        icon: "fa-solid fa-plus",
    },
    {
        label: "Shop List",
        href: "/shops",
        icon: "fa-solid fa-list",
    },
    {
        label: "Create Sale",
        href: "/sales",
        icon: "fa-solid fa-plus-circle",
    },
    {
        label: "Sales Report",
        href: "/sales/report",
        icon: "fa-solid fa-file-alt",
    },
]);

// Expense submenu
const expenseMenu = ref([
    {
        label: "Expense Management",
        href: "/expenses",
        icon: "fa-solid fa-clipboard-list",
    },
]);

// Inventory submenu
const inventoryMenu = ref([
    {
        label: "Inventory Report",
        href: "/inventory/report",
        icon: "fa-solid fa-file-alt",
    },
]);
</script>
