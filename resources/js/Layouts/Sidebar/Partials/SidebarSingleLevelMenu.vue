<template>
    <li>
        <Link
            :href="href"
            class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group"
            :class="{ 'bg-gray-100': $page.url === href || active }"
        >
            <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                <font-awesome-icon
                    :icon="['fas', getIconName(icon)]"
                    class="w-5 h-5 text-gray-700"
                />
            </div>
            <span class="ml-3 text-sm tracking-wide truncate">{{ label }}</span>
        </Link>
    </li>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        default: "#",
    },
    label: {
        type: String,
        default: "label",
    },
    icon: {
        type: String,
        default: "fa-solid fa-house",
    },
    active: {
        type: Boolean,
        default: false,
    },
});

// Extract icon name from FontAwesome class string
const getIconName = (iconClass) => {
    const parts = iconClass.split(' ');
    return parts[parts.length - 1].replace('fa-', '');
};
</script>

<style scoped>
a {
    transition: background-color 0.2s ease;
}

.w-6 {
    min-width: 1.5rem; /* 24px */
}

.text-gray-700 {
    color: #374151; /* Tailwind gray-700 */
}

.group:hover .text-gray-700 {
    color: #374151; /* Maintain color on hover */
}
</style>
