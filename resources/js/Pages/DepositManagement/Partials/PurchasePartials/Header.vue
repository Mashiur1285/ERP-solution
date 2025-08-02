<!-- Pages/Depositmanagement/partials/purchasePartials/Header.vue -->
<template>
    <div
        class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4"
    >
        <h1
            class="text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
        >
            <div
                class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
            >
                <svg
                    class="w-8 h-8 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                    />
                </svg>
            </div>
            {{ t("purchaseManagement") }}
        </h1>
        <div class="flex space-x-2">
            <button
                @click="$emit('change-language', 'en')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-colors',
                    currentLanguage === 'en'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ translations.en.languageLabel }}
            </button>
            <button
                @click="$emit('change-language', 'bn')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-colors',
                    currentLanguage === 'bn'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ translations.bn.languageLabel }}
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    currentLanguage: string;
    translations: any;
    t: (key: string, params?: Record<string, any>) => string;
}>();

defineEmits(["change-language"]);
</script>

<style scoped>
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

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}
</style>
