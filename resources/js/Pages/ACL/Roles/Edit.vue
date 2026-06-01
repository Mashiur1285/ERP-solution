<template>
    <RoleForm
        title="Edit Role"
        description="Update role details and permissions."
        submit-label="Update Role"
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

const props = defineProps({
    role: { type: Object, required: true },
    permissions: { type: Array, default: () => [] },
});

const form = useForm({
    name: props.role.name ?? "",
    description: props.role.description ?? "",
    is_active: Boolean(props.role.is_active),
    permission_ids: [...(props.role.permission_ids ?? [])],
});

const submit = () => form.put(route("roles.update", props.role.id));
</script>
