<template>
    <div>
        <Head :title="pageTitle" />
        <TheNavbar class="print:hidden" @toggle-sidebar="toggleSidebar" />
        <TheSidebar
            class="print:hidden"
            :is-open="isSidebarOpen"
            @close="closeSidebar"
        />

        <!-- Backdrop Overlay for Mobile -->
        <Transition name="fade">
            <div
                v-if="isSidebarOpen"
                class="fixed inset-0 z-30 bg-black/50 sm:hidden"
                @click="closeSidebar"
            />
        </Transition>

        <div
            class="flex flex-col min-h-screen justify-between transition-all duration-200 px-4 sm:ml-72 pt-[72px] print:p-0 print:sm:ml-0"
        >
            <div class="flex-1">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import TheNavbar from "@/Layouts/Navbar/TheNavbar.vue";
import TheSidebar from "@/Layouts/Sidebar/TheSidebar.vue";

const page = usePage();

const pageTitleMap = {
    "Dashboard": "Dashboard",
    "SalesManagement/CreateSale": "Create Sale",
    "SalesManagement/SalesReport": "Sales Report",
    "SalesManagement/EditSale": "Edit Sale",
    "SalesManagement/CashMemo": "Cash Memo",
    "SalesManagement/SalesPayment": "Sales Payment",
    "SalesManagement/Index": "Sales",
    "SalesManagement/CreateShop": "Create Shop",
    "LiftManagement/Lift": "Create Lift",
    "LiftManagement/LiftReport": "Lift Report",
    "ExpenseManagement/Expense": "Expense Management",
    "DepositManagement/Purchase": "Purchase",
    "DepositManagement/PurchaseReport": "Purchase Report",
    "DepositManagement/Deposit": "Deposit",
    "InventoryManagement/InventoryReport": "Inventory Report",
    "Suppliers/Index": "Suppliers",
    "Suppliers/Create": "Add Supplier",
    "Suppliers/Edit": "Edit Supplier",
    "Category/Index": "Categories",
    "Brand/Index": "Brands",
    "Profile/Edit": "Profile",
};

const pageTitle = computed(() => {
    const component = String(page.component ?? "");
    return pageTitleMap[component] ?? component.split("/").pop() ?? "ERP";
});

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
