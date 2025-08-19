<!-- components/TopSuppliers.vue -->
<template>
    <div
        class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100"
    >
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
                {{ t("totalSuppliers") }}
            </h2>
            <div
                class="p-2 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center shadow-md"
            >
                <svg
                    class="w-6 h-6 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.134-1.272.37-1.839m0 0C5.074 15.39 4 13.904 4 12V6a2 2 0 012-2h12a2 2 0 012 2v6c0 1.904-1.074 3.39-2.37 4.161M16 16v-4a2 2 0 00-2-2h-4a2 2 0 00-2 2v4m4 0h2m-2 0h-2m-6 0a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2z"
                    ></path>
                </svg>
            </div>
        </div>
        <p
            class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-pulse-slow"
        >
            {{ toBengaliNumber(suppliersCount) }}
        </p>
        <p class="text-sm text-gray-500 mt-2">
            {{ t("activeSuppliers") }}
        </p>
        <div class="mt-4 max-h-36 overflow-y-auto space-y-2">
            <div
                v-for="(supplier, index) in suppliers.slice(
                    0,
                    Math.max(5, suppliers.length)
                )"
                :key="supplier.id"
                class="bg-gradient-to-r from-gray-50 to-indigo-50 p-2 rounded flex justify-between items-center border border-gray-100"
            >
                <span class="font-medium text-gray-700">{{
                    supplier.company_name || t("supplier") + " " + (index + 1)
                }}</span>
                <span class="text-sm text-gray-500"
                    >{{ t("id") }}: {{ toBengaliNumber(supplier.id) }}</span
                >
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

defineProps({
    suppliers: Array,
    suppliersCount: Number,
    t: Function,
    toBengaliNumber: Function,
});
</script>

<style scoped>
@keyframes pulseSlow {
    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.03);
        opacity: 0.95;
    }
}

.animate-pulse-slow {
    animation: pulseSlow 2s infinite;
}

.max-h-36::-webkit-scrollbar {
    width: 6px;
}

.max-h-36::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #a5b4fc, #c4b5fd);
    border-radius: 3px;
}

.max-h-36::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.bg-gradient-to-r {
    background: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-indigo-600 {
    --tw-gradient-from: #4f46e5;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(79, 70, 229, 0));
}

.to-purple-600 {
    --tw-gradient-to: #7c3aed;
}

.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

.text-transparent {
    color: transparent;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

.from-indigo-100 {
    --tw-gradient-from: #e0e7ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(224, 231, 255, 0));
}

.to-purple-100 {
    --tw-gradient-to: #ede9fe;
}
</style>
