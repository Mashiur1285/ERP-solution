<template>
    <nav
        id="the-navbar"
        class="fixed top-0 z-[100] w-full bg-white border-b border-gray-200 shadow"
    >
        <div class="px-3 py-2 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start gap-2">
                    <!-- Hamburger Button (Mobile Only) -->
                    <button
                        type="button"
                        class="inline-flex items-center p-2 text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors"
                        aria-label="Toggle sidebar"
                        @click="$emit('toggle-sidebar')"
                    >
                        <svg
                            class="w-6 h-6"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            ></path>
                        </svg>
                    </button>

                    <!-- Logo / Brand Name -->
                    <Link :href="route('dashboard')" class="flex md:mr-24">
                        <span class="text-xl font-bold text-gray-800 whitespace-nowrap">
                            ERP Solution
                        </span>
                    </Link>
                </div>

                <!-- Right Side: User dropdown (desktop) -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Date & Time Stamp -->
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 border border-gray-200">
                        <font-awesome-icon :icon="['fas', 'clock']" class="w-3.5 h-3.5 text-indigo-500" />
                        <div class="leading-tight text-right">
                            <span class="block text-xs font-semibold text-gray-700">{{ currentTime }}</span>
                            <span class="block text-[10px] text-gray-400">{{ currentDate }}</span>
                        </div>
                    </div>

                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
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
                                class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 focus:outline-none transition"
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
