<template>
    <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
    >
        <div class="bg-white p-8 rounded-xl shadow-xl max-w-2xl w-full">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                {{ editMode ? "Edit Brand" : "Add Brand" }}
            </h2>
            <div class="space-y-6">
                <div>
                    <label
                        for="brand_name"
                        class="block text-sm font-medium text-gray-700"
                        >Brand Name*</label
                    >
                    <input
                        v-model="brandForm.brand_name"
                        id="brand_name"
                        type="text"
                        placeholder="Enter brand name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    />
                </div>
                <div>
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700"
                        >Description</label
                    >
                    <textarea
                        v-model="brandForm.description"
                        id="description"
                        placeholder="Enter brand description"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        rows="4"
                    ></textarea>
                </div>
            </div>
            <div class="flex justify-end mt-6 space-x-4">
                <button
                    @click="$emit('close')"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200"
                >
                    Cancel
                </button>
                <button
                    @click="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    {{ editMode ? "Update Brand" : "Add Brand" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";

const props = defineProps<{
    brand?: { id: number; brand_name: string; description: string | null };
    editMode?: boolean;
}>();

const emit = defineEmits<{
    (e: "close"): void;
    (
        e: "submit",
        brandData: { brand_name: string; description: string | null }
    ): void;
}>();

const brandForm = ref({
    brand_name: props.brand?.brand_name || "",
    description: props.brand?.description || null,
});

watch(
    () => props.brand,
    (newBrand) => {
        brandForm.value = {
            brand_name: newBrand?.brand_name || "",
            description: newBrand?.description || null,
        };
    },
    { deep: true }
);

const submit = () => {
    console.log("Form data:", brandForm.value);
    if (brandForm.value.brand_name) {
        emit("submit", { ...brandForm.value });
        if (!props.editMode) {
            brandForm.value = { brand_name: "", description: null };
        }
    } else {
        console.error("Please fill all required fields");
    }
};
</script>
