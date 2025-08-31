<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Flash Message -->
        <div
            v-if="props.flash?.success"
            class="fixed top-6 right-6 px-7 py-5 rounded-lg shadow-lg flex items-center space-x-3 animate-toast-in z-50 bg-green-500 text-white"
            role="alert"
        >
            <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
            </svg>
            <span class="font-medium">{{ props.flash.success }}</span>
        </div>

        <!-- Title and Language Toggle -->
        <div
            class="flex flex-col lg:flex-row lg:justify-between items-start lg:items-center mb-8 border-b border-gray-200 pb-4 gap-4"
        >
            <h1
                class="text-2xl lg:text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 lg:w-8 lg:h-8 text-indigo-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h10a2 2 0 012 2v2"
                        />
                    </svg>
                </div>
                {{ getTranslation("categoryManagement") }}
            </h1>

            <!-- Language Toggle -->
            <div class="flex space-x-2">
                <button
                    @click="changeLanguage('en')"
                    :class="[
                        'px-4 py-2 rounded-md font-medium transition-colors',
                        currentLanguage === 'en'
                            ? 'bg-indigo-600 text-white'
                            : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                    ]"
                >
                    {{ getTranslationLabel("languageLabel", "en") }}
                </button>
                <button
                    @click="changeLanguage('bn')"
                    :class="[
                        'px-4 py-2 rounded-md font-medium transition-colors',
                        currentLanguage === 'bn'
                            ? 'bg-indigo-600 text-white'
                            : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                    ]"
                >
                    {{ getTranslationLabel("languageLabel", "bn") }}
                </button>
            </div>
        </div>

        <!-- Search and Add Button -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"
        >
            <!-- Search Field -->
            <div class="relative w-full sm:w-80">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                >
                    <svg
                        class="w-5 h-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="getTranslation('searchCategories')"
                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                />
            </div>

            <!-- Add Category Button -->
            <button
                @click="showCategoryModal = true"
                class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                <span>{{ getTranslation("addCategory") }}</span>
            </button>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-sm border border-indigo-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h10a2 2 0 012 2v2"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-700">
                            {{ getTranslation("totalCategories") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-indigo-900">
                            {{ toBengaliNumber(totalCategories) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-green-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-700">
                            {{ getTranslation("withDescription") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-green-900">
                            {{ toBengaliNumber(categoriesWithDescription) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm border border-blue-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3a2 2 0 012-2h8a2 2 0 012 2v4m-4 8l-4-4m0 0l-4 4m4-4v12"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-700">
                            {{ getTranslation("recentlyAdded") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-blue-900">
                            {{ toBengaliNumber(recentCategories) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm border border-purple-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-purple-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-700">
                            {{ getTranslation("activeCategories") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-purple-900">
                            {{ toBengaliNumber(totalCategories) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Modal -->
        <CategoryModal
            v-if="showCategoryModal"
            :category="currentCategory"
            :editMode="editMode"
            @close="closeCategoryModal"
            @submit="submitCategory"
        />

        <!-- Categories Table -->
        <div class="bg-white rounded-xl shadow-sm p-3 lg:p-6">
            <div class="w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4"
                            >
                                {{ getTranslation("name") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/4 hidden sm:table-cell"
                            >
                                {{ getTranslation("description") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 hidden md:table-cell"
                            >
                                {{ getTranslation("createdAt") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4"
                            >
                                {{ getTranslation("actions") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template
                            v-for="(category, index) in filteredCategories"
                            :key="category.id"
                        >
                            <tr
                                class="hover:bg-gray-50 transition-colors cursor-pointer"
                                @click="toggleCategoryDetails(index)"
                            >
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm font-medium text-gray-900 w-1/4"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            :class="[
                                                'w-3 h-3 lg:w-4 lg:h-4 mr-1 lg:mr-2 transition-transform flex-shrink-0',
                                                expandedCategories[index]
                                                    ? 'rotate-90'
                                                    : '',
                                            ]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5l7 7-7 7"
                                            />
                                        </svg>
                                        <div class="min-w-0">
                                            <p
                                                class="font-semibold text-gray-900 truncate"
                                                :title="category.name"
                                            >
                                                {{ category.name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-2/4 hidden sm:table-cell"
                                >
                                    <div
                                        class="truncate"
                                        :title="category.description || '-'"
                                    >
                                        {{ category.description || "-" }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/4 hidden md:table-cell"
                                >
                                    <div class="text-center">
                                        {{
                                            new Date(
                                                category.created_at
                                            ).toLocaleDateString()
                                        }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-3 py-3 w-1/4">
                                    <div class="flex justify-center space-x-2">
                                        <button
                                            @click.stop="editCategory(category)"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full hover:bg-blue-200 transition-colors"
                                            :title="getTranslation('edit')"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                                />
                                            </svg>
                                        </button>
                                        <button
                                            @click.stop="
                                                deleteCategory(category.id)
                                            "
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full hover:bg-red-200 transition-colors"
                                            :title="getTranslation('delete')"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Expanded category details -->
                            <tr
                                v-if="expandedCategories[index]"
                                class="bg-gradient-to-r from-gray-50 to-gray-100 animate-slide-down"
                            >
                                <td :colspan="4" class="px-2 lg:px-6 py-6">
                                    <div class="ml-2 lg:ml-6">
                                        <!-- Mobile view for hidden columns -->
                                        <div
                                            class="sm:hidden mb-6 p-4 bg-white rounded-lg shadow-sm border-l-4 border-indigo-500"
                                        >
                                            <h4
                                                class="font-semibold text-gray-800 mb-3 flex items-center"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-2 text-indigo-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                {{
                                                    getTranslation(
                                                        "categoryDetails"
                                                    )
                                                }}
                                            </h4>
                                            <div class="space-y-3 text-sm">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-xs text-gray-500 font-medium"
                                                        >{{
                                                            getTranslation(
                                                                "description"
                                                            )
                                                        }}</span
                                                    >
                                                    <span
                                                        class="text-gray-800 font-medium"
                                                        >{{
                                                            category.description ||
                                                            "-"
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="md:hidden flex flex-col"
                                                >
                                                    <span
                                                        class="text-xs text-gray-500 font-medium"
                                                        >{{
                                                            getTranslation(
                                                                "createdAt"
                                                            )
                                                        }}</span
                                                    >
                                                    <span
                                                        class="text-gray-800 font-medium"
                                                        >{{
                                                            new Date(
                                                                category.created_at
                                                            ).toLocaleDateString()
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Category Details -->
                                        <div class="hidden sm:block">
                                            <div
                                                class="mb-4 flex items-center justify-between"
                                            >
                                                <h4
                                                    class="text-lg font-semibold text-gray-800 flex items-center"
                                                >
                                                    <svg
                                                        class="w-5 h-5 mr-2 text-indigo-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h10a2 2 0 012 2v2"
                                                        />
                                                    </svg>
                                                    {{
                                                        getTranslation(
                                                            "categoryDetails"
                                                        )
                                                    }}
                                                    - {{ category.name }}
                                                </h4>
                                            </div>

                                            <div
                                                class="bg-white rounded-xl p-6 shadow-sm border border-gray-200"
                                            >
                                                <div
                                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                                >
                                                    <div>
                                                        <h5
                                                            class="text-sm font-semibold text-gray-700 mb-2"
                                                        >
                                                            {{
                                                                getTranslation(
                                                                    "description"
                                                                )
                                                            }}
                                                        </h5>
                                                        <p
                                                            class="text-gray-900 bg-gray-50 p-3 rounded-lg"
                                                        >
                                                            {{
                                                                category.description ||
                                                                getTranslation(
                                                                    "noDescription"
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <h5
                                                            class="text-sm font-semibold text-gray-700 mb-2"
                                                        >
                                                            {{
                                                                getTranslation(
                                                                    "metadata"
                                                                )
                                                            }}
                                                        </h5>
                                                        <div
                                                            class="space-y-2 text-sm"
                                                        >
                                                            <div
                                                                class="flex justify-between"
                                                            >
                                                                <span
                                                                    class="text-gray-600"
                                                                    >{{
                                                                        getTranslation(
                                                                            "createdAt"
                                                                        )
                                                                    }}:</span
                                                                >
                                                                <span
                                                                    class="font-medium"
                                                                    >{{
                                                                        new Date(
                                                                            category.created_at
                                                                        ).toLocaleString()
                                                                    }}</span
                                                                >
                                                            </div>
                                                            <div
                                                                class="flex justify-between"
                                                            >
                                                                <span
                                                                    class="text-gray-600"
                                                                    >{{
                                                                        getTranslation(
                                                                            "categoryId"
                                                                        )
                                                                    }}:</span
                                                                >
                                                                <span
                                                                    class="font-medium"
                                                                    >#{{
                                                                        toBengaliNumber(
                                                                            category.id
                                                                        )
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
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

const translations = {
    en: {
        languageLabel: "English",
        categoryManagement: "Category Management",
        addCategory: "Add Category",
        searchCategories: "Search categories...",
        totalCategories: "Total Categories",
        withDescription: "With Description",
        recentlyAdded: "Recently Added",
        activeCategories: "Active Categories",
        name: "Name",
        description: "Description",
        createdAt: "Created At",
        actions: "Actions",
        edit: "Edit",
        delete: "Delete",
        categoryDetails: "Category Details",
        noDescription: "No description provided",
        metadata: "Metadata",
        categoryId: "Category ID",
    },
    bn: {
        languageLabel: "বাংলা",
        categoryManagement: "বিভাগ ব্যবস্থাপনা",
        addCategory: "বিভাগ যোগ করুন",
        searchCategories: "বিভাগ অনুসন্ধান করুন...",
        totalCategories: "মোট বিভাগ",
        withDescription: "বিবরণ সহ",
        recentlyAdded: "সম্প্রতি যোগ করা",
        activeCategories: "সক্রিয় বিভাগ",
        name: "নাম",
        description: "বিবরণ",
        createdAt: "তৈরি হয়েছে",
        actions: "কার্যক্রম",
        edit: "সম্পাদনা",
        delete: "মুছুন",
        categoryDetails: "বিভাগের বিবরণ",
        noDescription: "কোনো বিবরণ প্রদান করা হয়নি",
        metadata: "মেটাডেটা",
        categoryId: "বিভাগ আইডি",
    },
};

const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref("");
const showCategoryModal = ref(false);
const editMode = ref(false);
const currentCategory = ref<Category | null>(null);
const expandedCategories = ref({});

const form = useForm({
    name: "",
    description: null,
});

// Filter categories based on search query
const filteredCategories = computed(() => {
    if (!searchQuery.value) return props.categories;
    const query = searchQuery.value.toLowerCase();
    return props.categories.filter(
        (category) =>
            category.name.toLowerCase().includes(query) ||
            (category.description &&
                category.description.toLowerCase().includes(query))
    );
});

// Summary statistics
const totalCategories = computed(() => props.categories.length);
const categoriesWithDescription = computed(
    () =>
        props.categories.filter(
            (cat) => cat.description && cat.description.trim() !== ""
        ).length
);
const recentCategories = computed(() => {
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
    return props.categories.filter(
        (cat) => new Date(cat.created_at) > sevenDaysAgo
    ).length;
});

const toggleCategoryDetails = (index: number) => {
    expandedCategories.value[index] = !expandedCategories.value[index];
};

const getTranslation = (key: string) => {
    return translations[currentLanguage.value]?.[key] || key;
};

const getTranslationLabel = (key: string, lang: string) => {
    return translations[lang]?.[key] || key;
};

const toBengaliNumber = (num: number | string) => {
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string") return num;
    if (currentLanguage.value !== "bn") return num.toString();

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
};

const changeLanguage = (lang: string) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

const closeCategoryModal = () => {
    showCategoryModal.value = false;
    editMode.value = false;
    currentCategory.value = null;
    form.reset();
};

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
                closeCategoryModal();
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
                closeCategoryModal();
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
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
    }
}

@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
}

.rotate-90 {
    transform: rotate(90deg);
}

/* Custom responsive utilities */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Ensure table doesn't overflow */
table {
    table-layout: fixed;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .table-responsive {
        font-size: 0.75rem;
    }

    th,
    td {
        padding: 0.25rem 0.5rem;
    }
}

/* Enhanced focus states */
input:focus,
select:focus {
    outline: none;
    transform: translateY(-1px);
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Enhanced shadow effects */
.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(79, 70, 229, 0.1);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color,
        text-decoration-color, fill, stroke, opacity, box-shadow, transform,
        filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
