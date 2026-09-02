<template>
    <div class="px-1 py-3 sm:p-6 space-y-3 sm:space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-lg sm:text-2xl font-semibold text-gray-800">User Management</h1>
                <p class="text-sm text-gray-500">Create users and assign their roles.</p>
            </div>
            <Link
                v-if="can('user.add')"
                :href="route('users.create')"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700"
            >
                Add User
            </Link>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-2.5 sm:p-4">
            <input
                v-model="search"
                type="text"
                placeholder="Search by name or email..."
                class="w-full rounded-lg border border-gray-200 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            />
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div v-if="filteredUsers.length === 0" class="p-8 text-center text-sm text-gray-500">
                No users found.
            </div>
            <template v-else>
            <!-- Cards (mobile) -->
            <div class="sm:hidden divide-y divide-gray-100">
                <div v-for="user in filteredUsers" :key="`m-${user.id}`" class="px-3 py-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 break-words">{{ user.name }}</p>
                            <p class="text-[11px] text-gray-500 break-all">{{ user.email }}</p>
                        </div>
                        <Link
                            v-if="can('user.update')"
                            :href="route('users.edit', user.id)"
                            class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-semibold hover:bg-blue-100 flex-shrink-0"
                        >
                            Edit
                        </Link>
                    </div>
                    <span class="inline-flex items-center mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ user.role || 'No Role' }}
                    </span>
                </div>
            </div>

            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">
                            <td class="px-4 py-4 font-semibold text-gray-900">{{ user.name }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ user.email }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ user.role || 'No Role' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <Link
                                        v-if="can('user.update')"
                                        :href="route('users.edit', user.id)"
                                        class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100"
                                    >
                                        Edit
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import Layout from "@/Layout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

defineOptions({ layout: Layout });

const props = defineProps({
    users: { type: Array, default: () => [] },
});

const search = ref("");
const page = usePage();
const permissions = computed(() => page.props.userPermissions || []);
const can = (permission) => permissions.value.includes(permission);

const filteredUsers = computed(() => {
    const query = search.value.trim().toLowerCase();
    if (!query) return props.users;

    return props.users.filter((user) =>
        [user.name, user.email, user.role]
            .filter(Boolean)
            .some((value) => String(value).toLowerCase().includes(query))
    );
});
</script>
