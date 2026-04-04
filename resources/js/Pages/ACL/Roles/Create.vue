<template>
    <RoleForm
        title="Create Role"
        description="Define a role and assign permissions."
        submit-label="Create Role"
        :permissions="permissions"
        :form="form"
        @submit="submit"
    />
</template>

<script setup>
import Layout from "@/Layout.vue";
import RoleForm from "./Partials/RoleForm.vue";
import { useForm } from "@inertiajs/vue3";

defineOptions({ layout: Layout });

defineProps({
    permissions: { type: Array, default: () => [] },
});

const form = useForm({
    name: "",
    description: "",
    is_active: true,
    permission_ids: [],
});

const submit = () => form.post(route("roles.store"));
</script>
