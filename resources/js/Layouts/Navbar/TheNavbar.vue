<template>
    <nav
        id="the-navbar"
        class="fixed top-0 z-[100] h-14 w-full bg-[#3F0E40]"
    >
        <div class="h-full px-3 lg:px-4">
            <div class="flex h-full items-center justify-between">
                <div class="flex items-center justify-start gap-2">
                    <!-- Opens the drawer on a phone; folds the menu column away
                         on a wide screen, so the icon says which one it does. -->
                    <button
                        type="button"
                        class="inline-flex items-center rounded-lg p-2 text-white/70 transition-colors hover:bg-white/10 hover:text-white focus:outline-none"
                        :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                        :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                        @click="$emit('toggle-sidebar')"
                    >
                        <!-- Phone: a plain menu button -->
                        <svg
                            class="h-6 w-6 sm:hidden"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            ></path>
                        </svg>

                        <!-- Desktop: a panel with an arrow pointing the way it will move -->
                        <svg
                            class="hidden h-5 w-5 sm:block"
                            aria-hidden="true"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect x="3" y="4" width="18" height="16" rx="2.5" />
                            <line x1="9.5" y1="4" x2="9.5" y2="20" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                :d="collapsed ? 'M14 9.5l2.5 2.5L14 14.5' : 'M18 9.5L15.5 12l2.5 2.5'"
                            />
                        </svg>
                    </button>

                    <!-- Logo / Brand Name -->
                    <Link :href="route('dashboard')" class="flex md:mr-24">
                        <span class="whitespace-nowrap text-lg font-bold text-white">
                            ERP Solution
                        </span>
                    </Link>
                </div>

                <!-- Right Side: User dropdown (desktop) -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Date & Time Stamp -->
                    <div class="flex items-center gap-2 rounded-lg bg-white/10 px-3 py-1.5 ring-1 ring-inset ring-white/15">
                        <font-awesome-icon :icon="['fas', 'clock']" class="h-3.5 w-3.5 text-white/70" />
                        <div class="text-right leading-tight">
                            <span class="block text-xs font-semibold text-white">{{ currentTime }}</span>
                            <span class="block text-[10px] text-white/60">{{ currentDate }}</span>
                        </div>
                    </div>

                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent px-3 py-2 text-sm font-medium leading-4 text-white/80 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:outline-none"
                                    >
                                        {{ $page.props.auth?.user?.name || 'User' }}
                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- Right Side: Mobile user icon -->
                <div class="flex items-center sm:hidden">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20 focus:outline-none"
                                aria-label="User menu"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ $page.props.auth?.user?.name || 'User' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $page.props.auth?.user?.email || '' }}</p>
                            </div>
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import Dropdown from "@/Components/Others/Dropdown.vue";
import DropdownLink from "@/Components/Others/DropdownLink.vue";
import { Link } from "@inertiajs/vue3";
import { ref, computed, onMounted, onUnmounted } from "vue";

defineProps({
    collapsed: { type: Boolean, default: false },
});

defineEmits(['toggle-sidebar']);

const now = ref(new Date());
let timer = null;

onMounted(() => {
    timer = setInterval(() => { now.value = new Date(); }, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

const currentDate = computed(() =>
    now.value.toLocaleDateString("en-GB", {
        weekday: "short",
        day: "2-digit",
        month: "short",
        year: "numeric",
    })
);

const currentTime = computed(() =>
    now.value.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: true,
    })
);
</script>
