<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <div
            v-if="props.flash?.success"
            class="bg-green-100 p-4 mb-4 text-green-800 rounded"
        >
            {{ props.flash.success }}
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Category Management
        </h1>
        <div class="mb-8 flex justify-end">
            <button
                @click="showCategoryModal = true"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
            >
                Add Category
            </button>
        </div>
        <CategoryModal
            v-if="showCategoryModal"
            :category="currentCategory"
            :editMode="editMode"
            @close="showCategoryModal = false"
            @submit="submitCategory"
        />
        <div class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-800">Category List</h2>
            <table class="min-w-full divide-y divide-gray-400">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Name
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Description
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Created At
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="category in categories"
                        :key="category.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ category.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ category.description ?? "-" }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ new Date(category.created_at).toLocaleString() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <button
                                @click="editCategory(category)"
                                class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold text-blue-800 bg-indigo-100 rounded-[300px] hover:bg-indigo-200 transition"
                            >
                                <svg
                                    class="w-5 h-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                    />
                                    <path
                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                    />
                                </svg>
                            </button>
                            <button
                                @click="deleteCategory(category.id)"
                                class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold text-red-800 bg-red-100 rounded-[300px] hover:bg-red-200 transition"
                            >
                                <svg
                                    class="w-5 h-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M3 6h18" />
                                    <path
                                        d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-1 5v6a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-6m6-3H6"
                                    />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import CategoryModal from "./Partials/CategoryModal.vue";

interface Category {
    id: number;
    name: string;
    description: string | null;
    created_at: string;
}

const props = defineProps<{
    categories: Category[];
    flash?: { success?: string; error?: string };
}>();

defineOptions({
    layout: Layout,
});

const showCategoryModal = ref(false);
const editMode = ref(false);
const currentCategory = ref<Category | null>(null);

const form = useForm({
    name: "",
    description: null,
});

const submitCategory = (categoryData: {
    name: string;
    description: string | null;
}) => {
    console.log("Submitting category:", categoryData);
    form.name = categoryData.name;
    form.description = categoryData.description;

    const url =
        editMode.value && currentCategory.value
            ? `/categories/${currentCategory.value.id}/update`
            : "/categories/store";

    if (editMode.value) {
        form.put(url, {
            preserveState: false,
            onSuccess: () => {
                showCategoryModal.value = false;
                editMode.value = false;
                currentCategory.value = null;
                form.reset();
                console.log("Category updated successfully");
            },
            onError: (errors) => {
                console.error("Category update errors:", errors);
                alert("Failed to update category: " + JSON.stringify(errors));
            },
        });
    } else {
        form.post(url, {
            preserveState: false,
            onSuccess: () => {
                showCategoryModal.value = false;
                editMode.value = false;
                currentCategory.value = null;
                form.reset();
                console.log("Category created successfully");
            },
            onError: (errors) => {
                console.error("Category creation errors:", errors);
                alert("Failed to create category: " + JSON.stringify(errors));
            },
        });
    }
};

const editCategory = (category: Category) => {
    currentCategory.value = { ...category };
    editMode.value = true;
    showCategoryModal.value = true;
    form.name = category.name;
    form.description = category.description;
};

const deleteCategory = (categoryId: number) => {
    if (confirm("Are you sure you want to delete this category?")) {
        form.delete(`/categories/${categoryId}/delete`, {
            preserveState: false,
            onSuccess: () => {
                console.log("Category deleted successfully");
            },
            onError: (errors) => {
                console.error("Category deletion errors:", errors);
                alert("Failed to delete category: " + JSON.stringify(errors));
            },
        });
    }
};

console.log("Category.vue component loaded");
console.log("Form object:", form); // Debug form initialization
</script>

<style scoped>
input,
select,
textarea {
    padding: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
}

.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgb(142, 173, 200);
}

table {
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 0.5rem;
    overflow: hidden;
}

thead th:first-child {
    border-top-left-radius: 0.5rem;
}

thead th:last-child {
    border-top-right-radius: 0.5rem;
}

tbody tr:last-child td:first-child {
    border-bottom-left-radius: 0.5rem;
}

tbody tr:last-child td:last-child {
    border-bottom-right-radius: 0.5rem;
}
</style>
