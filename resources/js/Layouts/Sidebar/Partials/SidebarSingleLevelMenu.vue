<template>
    <li>
        <Link
            :href="href"
            class="flex items-center p-2.5 rounded-lg group transition-colors duration-150"
            :class="linkClasses"
            @click="$emit('link-clicked')"
        >
            <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                <font-awesome-icon
                    :icon="['fas', getIconName(icon)]"
                    class="w-[15px] h-[15px]"
                    :class="iconClasses"
                />
            </div>
            <span class="ml-3 text-sm tracking-wide truncate font-medium">{{ label }}</span>
        </Link>
    </li>
</template>

<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

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
    color: {
        type: String,
        default: "indigo", // indigo | orange | green
    },
});

defineEmits(['link-clicked']);

const page = usePage();
const isActive = computed(() => page.url === props.href || props.active);

const colorMap = {
    indigo: {
        link: "text-gray-300 hover:bg-gray-700 hover:text-white",
        linkActive: "bg-indigo-600 text-white shadow-sm",
        icon: "text-gray-400 group-hover:text-white",
        iconActive: "text-white",
    },
    orange: {
        link: "text-gray-300 hover:bg-gray-700 hover:text-orange-300",
        linkActive: "bg-orange-600 text-white shadow-sm",
        icon: "text-orange-400 group-hover:text-orange-300",
        iconActive: "text-white",
    },
    green: {
        link: "text-gray-300 hover:bg-gray-700 hover:text-green-300",
        linkActive: "bg-green-600 text-white shadow-sm",
        icon: "text-green-400 group-hover:text-green-300",
        iconActive: "text-white",
    },
};

const scheme = computed(() => colorMap[props.color] ?? colorMap.indigo);

const linkClasses = computed(() =>
    isActive.value
        ? `${scheme.value.linkActive} font-medium`
        : scheme.value.link
);

const iconClasses = computed(() =>
    isActive.value ? scheme.value.iconActive : scheme.value.icon
);

// Extract icon name from FontAwesome class string
const getIconName = (iconClass) => {
    const parts = iconClass.split(' ');
    return parts[parts.length - 1].replace('fa-', '');
};
</script>

<style scoped>
a {
    transition: background-color 0.15s ease, color 0.15s ease;
}
</style>
