<template>
    <li>
        <div class="flex flex-col space-y-1">
            <!-- Menu Header (clickable to expand/collapse) -->
            <div
                class="flex items-center p-2.5 text-gray-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 group cursor-pointer transition-colors duration-150"
                :class="{ 'bg-indigo-50 text-indigo-700 font-medium': active }"
                @click="states.rotate = !states.rotate"
            >
                <!-- Icon -->
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <font-awesome-icon
                        :icon="['fas', getIconName(icon)]"
                        class="w-[15px] h-[15px]"
                        :class="active ? 'text-indigo-600' : 'text-gray-500 group-hover:text-indigo-600'"
                    />
                </div>
                <!-- Label -->
                <span class="ml-3 text-sm tracking-wide truncate flex-1">{{ label }}</span>
                <!-- Chevron -->
                <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center">
                    <font-awesome-icon
                        :icon="['fas', 'chevron-down']"
                        class="w-3 h-3 transition-transform duration-200 ease-in-out"
                        :class="[states.rotate ? 'rotate-180 text-indigo-500' : 'text-gray-400']"
                    />
                </div>
            </div>

            <!-- Submenu with smooth animation -->
            <Transition name="submenu">
                <div v-if="states.rotate" class="ml-5 space-y-0.5 border-l-2 border-indigo-100 pl-3">
                    <SidebarSingleLevelMenu
                        v-for="menu in submenu"
                        :key="menu.href"
                        :label="menu.label"
                        :href="menu.href"
                        :icon="menu.icon"
                        :color="menu.color || 'indigo'"
                        :active="$page.url === menu.href"
                        @link-clicked="$emit('link-clicked')"
                    />
                </div>
            </Transition>
        </div>
    </li>
</template>

<script setup>
import { computed, reactive } from "vue";
import SidebarSingleLevelMenu from "./SidebarSingleLevelMenu.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    label: {
        type: String,
        default: "label",
    },
    icon: {
        type: String,
        default: "fa-solid fa-layer-group",
    },
    submenu: {
        type: Array,
        default: () => [],
    },
});

defineEmits(['link-clicked']);

const states = reactive({
    rotate: false,
});

const page = usePage();

const active = computed(() => {
    return props.submenu.some((item) => item.href === page.url);
});

// Extract icon name from FontAwesome class string
const getIconName = (iconClass) => {
    const parts = iconClass.split(' ');
    return parts[parts.length - 1].replace('fa-', '');
};
</script>

<style scoped>
.submenu-enter-active,
.submenu-leave-active {
    transition: all 0.2s ease;
    overflow: hidden;
}
.submenu-enter-from,
.submenu-leave-to {
    opacity: 0;
    max-height: 0;
}
.submenu-enter-to,
.submenu-leave-from {
    opacity: 1;
    max-height: 300px;
}
</style>
