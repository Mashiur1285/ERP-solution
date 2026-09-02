<template>
    <!-- The purple shows only through the notch at the panel's top-left corner,
         which is what makes the bar and the sidebar read as one surface. -->
    <div class="min-h-screen bg-[#3F0E40] print:bg-white">
        <Head :title="pageTitle" />
        <TheNavbar
            class="print:hidden"
            :collapsed="isMenuCollapsed"
            @toggle-sidebar="toggleSidebar"
        />
        <TheSidebar
            class="print:hidden"
            :is-open="isSidebarOpen"
            :collapsed="isMenuCollapsed"
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

        <main
            class="pt-14 transition-[padding] duration-200 print:p-0 print:sm:pl-0"
            :class="isMenuCollapsed ? 'sm:pl-16' : 'sm:pl-72'"
        >
            <div
                class="flex min-h-[calc(100vh-3.5rem)] flex-col justify-between bg-gray-50 sm:rounded-tl-[1.25rem] print:min-h-0 print:rounded-none print:bg-white"
            >
                <div class="flex-1">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
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
    "ExpenseManagement/Report": "Expense Report",
    "Reports/ProfitLoss": "Profit & Loss",
    "DepositManagement/Purchase": "Purchase",
    "DepositManagement/PurchaseReport": "Purchase Report",
    "DepositManagement/Deposit": "Deposit",
    "InventoryManagement/InventoryReport": "Inventory Report",
    "Suppliers/Index": "Suppliers",
    "Suppliers/Create": "Add Supplier",
    "Suppliers/Edit": "Edit Supplier",
    "Category/Index": "Categories",
    "Brand/Index": "Brands",
    "ACL/Roles/Index": "Roles",
    "ACL/Roles/Create": "Create Role",
    "ACL/Roles/Edit": "Edit Role",
    "ACL/Users/Index": "Users",
    "ACL/Users/Create": "Create User",
    "ACL/Users/Edit": "Edit User",
    "Profile/Edit": "Profile",
};

const pageTitle = computed(() => {
    const component = String(page.component ?? "");
    return pageTitleMap[component] ?? component.split("/").pop() ?? "ERP";
});

const isSidebarOpen = ref(false);

// Desktop keeps the rail and folds the menu column away; the choice is
// remembered so it does not spring back open on every page change.
const isMenuCollapsed = ref(false);

onMounted(() => {
    try {
        isMenuCollapsed.value = localStorage.getItem("sidebarCollapsed") === "1";
    } catch {
        // Private browsing can throw on access; the default is fine.
    }
});

const isDesktop = () =>
    typeof window !== "undefined" && window.matchMedia("(min-width: 640px)").matches;

const toggleSidebar = () => {
    if (isDesktop()) {
        isMenuCollapsed.value = !isMenuCollapsed.value;
        try {
            localStorage.setItem("sidebarCollapsed", isMenuCollapsed.value ? "1" : "0");
        } catch {
            // Not being able to remember it is not worth breaking the toggle.
        }
        return;
    }

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
