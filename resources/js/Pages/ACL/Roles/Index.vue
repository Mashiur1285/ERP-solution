<template>
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Role Management</h1>
                <p class="text-sm text-gray-500">Manage role-based access for ERP modules.</p>
            </div>
            <Link
                v-if="can('role.add')"
                :href="route('roles.create')"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700"
            >
                Add Role
            </Link>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <input
                v-model="search"
                type="text"
                placeholder="Search role name..."
                class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
            />
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div v-if="filteredRoles.length === 0" class="p-8 text-center text-sm text-gray-500">
                No roles found.
            </div>
            <div v-else class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Permissions</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="role in filteredRoles" :key="role.id" class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-900">{{ role.name }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                {{ role.description || '—' }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="permission in role.permissions"
                                        :key="permission"
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100"
                                    >
                                        {{ permission }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                    :class="role.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                >
                                    {{ role.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <Link
                                        v-if="can('role.update')"
                                        :href="route('roles.edit', role.id)"
                                        class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        v-if="can('role.delete')"
                                        type="button"
                                        class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100"
                                        @click="destroy(role)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import Layout from "@/Layout.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

defineOptions({ layout: Layout });

const props = defineProps({
    roles: { type: Array, default: () => [] },
});

const search = ref("");
const page = usePage();
const permissions = computed(() => page.props.userPermissions || []);
const can = (permission) => permissions.value.includes(permission);

const filteredRoles = computed(() => {
    const query = search.value.trim().toLowerCase();
    if (!query) return props.roles;
    return props.roles.filter((role) => role.name.toLowerCase().includes(query));
});

const destroy = (role) => {
    if (!window.confirm(`Delete role "${role.name}"?`)) return;
    router.delete(route("roles.destroy", role.id));
};
</script>
