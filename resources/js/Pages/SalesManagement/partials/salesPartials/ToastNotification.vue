<template>
    <div
        v-if="show"
        class="fixed top-6 right-6 px-7 py-5 rounded-lg shadow-lg flex items-center space-x-3 animate-toast-in z-50"
        :class="toastClasses"
        role="alert"
    >
        <svg
            class="w-5 h-5 text-white"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                v-if="type === 'success'"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
            />
            <path
                v-else-if="type === 'warning'"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
            <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
        </svg>
        <span class="font-medium text-white">{{ t(message) }}</span>
        <button
            @click="$emit('close')"
            class="ml-2 text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white transition-colors"
            aria-label="Close notification"
        >
            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    show: boolean;
    message: string;
    type: string;
    t: (key: string, params?: Record<string, any>) => string;
}>();

defineEmits(["close"]);

const toastClasses = computed(() => ({
    "bg-green-500": props.type === "success",
    "bg-orange-500": props.type === "warning",
    "bg-red-500": props.type === "error",
    "text-white": true,
}));
</script>

<style scoped>
@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
}
</style>
