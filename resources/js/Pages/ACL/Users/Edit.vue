<template>
    <div class="p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Edit User</h1>
                <p class="text-sm text-gray-500">Update user details and change role access.</p>
            </div>
            <Link
                :href="route('users.index')"
                class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Back to Users
            </Link>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form class="space-y-5 max-w-2xl" @submit.prevent="submit">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="mt-1 w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="mt-1 w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    />
                    <InputError :message="form.errors.email" class="mt-1" />
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            placeholder="Leave blank to keep current password"
                        />
                        <InputError :message="form.errors.password" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select
                        v-model="form.role_id"
                        class="mt-1 w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="">Select a role</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.role_id" class="mt-1" />
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-60"
                        :disabled="form.processing"
                    >
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import Layout from "@/Layout.vue";
import { Link, useForm } from "@inertiajs/vue3";

defineOptions({ layout: Layout });

const props = defineProps({
    user: { type: Object, required: true },
    roles: { type: Array, default: () => [] },
});

const form = useForm({
    name: props.user.name ?? "",
    email: props.user.email ?? "",
    password: "",
    password_confirmation: "",
    role_id: props.user.role_id ?? "",
});

const submit = () => form.put(route("users.update", props.user.id));
</script>
