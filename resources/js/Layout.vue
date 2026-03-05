<template>
    <div>
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
import { ref } from "vue";
import TheNavbar from "@/Layouts/Navbar/TheNavbar.vue";
import TheSidebar from "@/Layouts/Sidebar/TheSidebar.vue";

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
