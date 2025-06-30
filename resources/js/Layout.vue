<template>
  <div class="min-h-screen bg-gray-50 p-4">
    <div class="flex h-[calc(100vh-2rem)] bg-white rounded-xl shadow-lg overflow-hidden">
      <!-- Sidebar -->
      <div
        :class="[
          'relative bg-blue-800 text-white rounded-l-xl flex flex-col transition-all duration-300',
          collapsed ? 'w-20' : 'w-64'
        ]"
      >
        <!-- Collapse Button (Always visible at top-right) -->
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
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
          <svg
            v-else
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Logo / Title -->
        <div class="pt-10 px-4">
          <h1 v-if="!collapsed" class="text-2xl font-bold">HMS</h1>
        </div>

        <!-- Nav -->
        <nav class="mt-4 flex-1 px-2">
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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- Submenu -->
          <div v-if="suppliersMenuOpen && !collapsed" class="pl-6 mt-2 space-y-1">
            <Link
              href="/suppliers/index"
              class="block px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition duration-200"
              :class="{ 'bg-blue-600': $page.url.startsWith('/suppliers/index') }"
              @click="logNavigation('/suppliers/index')"
            >
              Supplier List
            </Link>
            <Link
              href="/suppliers/create"
              class="block px-4 py-2 text-sm rounded-md hover:bg-blue-600 transition duration-200"
              :class="{ 'bg-blue-600': $page.url.startsWith('/suppliers/create') }"
              @click="logNavigation('/suppliers/create')"
            >
              Add Supplier
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
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const suppliersMenuOpen = ref(false);
const collapsed = ref(false);

const toggleSuppliersMenu = () => {
  suppliersMenuOpen.value = !suppliersMenuOpen.value;
};

const logNavigation = (url) => {
  console.log('Navigating to:', url);
};
</script>

<style scoped>
.shadow-xl:hover {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgb(3, 113, 209);
}
</style>
