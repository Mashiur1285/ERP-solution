<template>
    <div>
        <TheNavbar class="print:hidden" />
        <TheSidebar class="print:hidden" />

        <div
            class="flex flex-col min-h-screen justify-between transition-all duration-200 px-4 sm:ml-64 pt-[72px] print:p-0 print:sm:ml-0"
        >
            <div class="flex-1">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import TheNavbar from "@/Layouts/Navbar/TheNavbar.vue";
import TheSidebar from "@/Layouts/Sidebar/TheSidebar.vue";
</script>

<style scoped>
/* Add any custom styles if needed */
</style>
