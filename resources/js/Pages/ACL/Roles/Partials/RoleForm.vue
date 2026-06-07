<template>
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">{{ title }}</h1>
                <p class="text-sm text-gray-500">{{ description }}</p>
            </div>
            <Link :href="route('roles.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Back to Roles
            </Link>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                    <input v-model="form.name" type="text" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea v-model="form.description" rows="4" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" />
                    <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    Active
                </label>

                <button
                    type="button"
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50"
                    :disabled="form.processing"
                    @click="$emit('submit')"
                >
                    {{ form.processing ? 'Processing...' : submitLabel }}
                </button>
            </div>

            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Permissions</h2>
                    <button
                        type="button"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                        @click="toggleAll"
                    >
                        {{ allSelected ? 'Clear All' : 'Select All' }}
                    </button>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div
                        v-for="group in groupedPermissions"
                        :key="group.module"
                        class="rounded-xl border border-gray-200 p-4"
                    >
                        <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide mb-3">
                            {{ group.label }}
                        </h3>
                        <div class="space-y-2">
                            <label
                                v-for="permission in group.permissions"
                                :key="permission.id"
                                class="flex items-center gap-2 text-sm text-gray-700"
                            >
                                <input
                                    :checked="form.permission_ids.includes(permission.id)"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    @change="togglePermission(permission.id)"
                                />
                                {{ permission.name }}
                            </label>
                        </div>
                    </div>
                </div>
                <p v-if="form.errors.permission_ids" class="mt-3 text-sm text-red-600">{{ form.errors.permission_ids }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    title: { type: String, required: true },
    description: { type: String, required: true },
    submitLabel: { type: String, required: true },
    permissions: { type: Array, default: () => [] },
    form: { type: Object, required: true },
});

defineEmits(["submit"]);

const groupedPermissions = computed(() => {
    const groups = {};
    props.permissions.forEach((permission) => {
        const [module] = permission.name.split(".");
        if (!groups[module]) {
            groups[module] = {
                module,
                label: module.replace(/-/g, " ").replace(/\b\w/g, (char) => char.toUpperCase()),
                permissions: [],
            };
        }
        groups[module].permissions.push(permission);
    });
    return Object.values(groups);
});

const allSelected = computed(() => {
    if (!props.permissions.length) return false;
    return props.permissions.every((permission) => props.form.permission_ids.includes(permission.id));
});

const togglePermission = (permissionId) => {
    if (props.form.permission_ids.includes(permissionId)) {
        props.form.permission_ids = props.form.permission_ids.filter((id) => id !== permissionId);
        return;
    }

    props.form.permission_ids = [...props.form.permission_ids, permissionId];
};

const toggleAll = () => {
    props.form.permission_ids = allSelected.value ? [] : props.permissions.map((permission) => permission.id);
};
</script>
