<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        leave-active-class="transition duration-150 ease-in"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="message"
            class="fixed inset-x-4 bottom-5 z-50 mx-auto flex max-w-sm items-start gap-2.5 rounded-xl px-4 py-3 shadow-lg sm:inset-x-auto sm:right-6 sm:mx-0"
            :class="tone === 'error'
                ? 'bg-red-600 text-white shadow-red-600/25'
                : 'bg-slate-900 text-white shadow-slate-900/25'"
            role="status"
        >
            <svg
                v-if="tone === 'error'"
                class="mt-0.5 h-4 w-4 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <svg
                v-else
                class="mt-0.5 h-4 w-4 flex-shrink-0 text-green-400"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            <p class="text-sm font-medium leading-snug">{{ message }}</p>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { ref, watch, onUnmounted } from "vue";
import { usePage } from "@inertiajs/vue3";

/**
 * Shows the server's flash message after a redirect. Pages that redirect
 * elsewhere on success (save a lift, save a draft) otherwise land the user on a
 * list with no sign that anything happened.
 */
const props = withDefaults(defineProps<{ duration?: number }>(), {
    duration: 4000,
});

const page = usePage();
const message = ref("");
const tone = ref<"success" | "error">("success");
let timer: ReturnType<typeof setTimeout> | undefined;

const show = (text: string, kind: "success" | "error") => {
    clearTimeout(timer);
    message.value = text;
    tone.value = kind;
    timer = setTimeout(() => (message.value = ""), props.duration);
};

watch(
    () => page.props.flash,
    (flash: any) => {
        if (!flash) return;
        if (flash.error) show(flash.error, "error");
        else if (flash.success) show(flash.success, "success");
    },
    { immediate: true, deep: true }
);

onUnmounted(() => clearTimeout(timer));
</script>
