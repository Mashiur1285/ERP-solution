<template>
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-labelledby="modal-title"
        aria-modal="true"
    >
        <!-- Background overlay -->
        <div
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300"
            @click="$emit('close')"
        ></div>

        <!-- Modal container -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 w-full max-w-2xl"
                @click.stop
            >
                <!-- Modal header -->
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4"
                >
                    <div class="flex items-center justify-between">
                        <h3
                            id="modal-title"
                            class="text-xl font-semibold text-white flex items-center"
                        >
                            <div
                                class="p-2 bg-white bg-opacity-20 rounded-full mr-3"
                            >
                                <svg
                                    class="w-6 h-6 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        v-if="!editMode"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    />
                                    <path
                                        v-else
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                                    />
                                    <path
                                        v-if="editMode"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                                    />
                                </svg>
                            </div>
                            {{
                                editMode
                                    ? getTranslation("editCategory")
                                    : getTranslation("addCategory")
                            }}
                        </h3>
                        <button
                            @click="$emit('close')"
                            class="text-white hover:text-gray-200 transition-colors p-2 rounded-full hover:bg-white hover:bg-opacity-20"
                            aria-label="Close modal"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal body -->
                <div class="px-6 py-6">
                    <div class="space-y-6">
                        <!-- Category Name -->
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                <div class="flex items-center">
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
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                        />
                                    </svg>
                                    {{ getTranslation("categoryName") }}*
                                </div>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
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
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h10a2 2 0 012 2v2"
                                        />
                                    </svg>
                                </div>
                                <input
                                    v-model="categoryForm.name"
                                    id="name"
                                    type="text"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            isSubmitted && !categoryForm.name,
                                    }"
                                    :placeholder="
                                        getTranslation('enterCategoryName')
                                    "
                                    required
                                />
                            </div>
                            <p
                                v-if="isSubmitted && !categoryForm.name"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ getTranslation("categoryNameRequired") }}
                            </p>
                        </div>

                        <!-- Category Description -->
                        <div>
                            <label
                                for="description"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                <div class="flex items-center">
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
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    {{ getTranslation("description") }} ({{
                                        getTranslation("optional")
                                    }})
                                </div>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute top-3 left-3 pointer-events-none"
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
                                            d="M4 6h16M4 12h16M4 18h7"
                                        />
                                    </svg>
                                </div>
                                <textarea
                                    v-model="categoryForm.description"
                                    id="description"
                                    rows="4"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300 resize-none"
                                    :placeholder="
                                        getTranslation('enterDescription')
                                    "
                                ></textarea>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                {{ getTranslation("descriptionHint") }}
                            </p>
                        </div>

                        <!-- Preview Section -->
                        <div
                            v-if="categoryForm.name"
                            class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-200"
                        >
                            <h4
                                class="text-sm font-semibold text-gray-800 mb-3 flex items-center"
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
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                {{ getTranslation("categoryPreview") }}
                            </h4>
                            <div
                                class="bg-white rounded-lg p-4 border border-indigo-100"
                            >
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="p-2 bg-indigo-100 rounded-lg flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-indigo-600"
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
                                    <div class="flex-1 min-w-0">
                                        <h5
                                            class="font-semibold text-gray-900 mb-1"
                                        >
                                            {{ categoryForm.name }}
                                        </h5>
                                        <p class="text-sm text-gray-600">
                                            {{
                                                categoryForm.description ||
                                                getTranslation(
                                                    "noDescriptionPreview"
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div
                    class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0"
                >
                    <button
                        @click="$emit('close')"
                        class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-100 hover:border-gray-400 transition-all duration-300 flex items-center justify-center space-x-2"
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
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                        <span>{{ getTranslation("cancel") }}</span>
                    </button>
                    <button
                        @click="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
                        :disabled="isLoading"
                    >
                        <svg
                            v-if="isLoading"
                            class="w-5 h-5 animate-spin"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                        <svg
                            v-else-if="!editMode"
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
                        <svg
                            v-else
                            class="w-5 h-5"
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
                        <span>
                            {{
                                isLoading
                                    ? getTranslation("processing")
                                    : editMode
                                    ? getTranslation("updateCategory")
                                    : getTranslation("addCategory")
                            }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";

const props = defineProps<{
    category?: { id: number; name: string; description: string | null };
    editMode?: boolean;
}>();

const emit = defineEmits<{
    (e: "close"): void;
    (
        e: "submit",
        categoryData: { name: string; description: string | null }
    ): void;
}>();

const translations = {
    en: {
        addCategory: "Add Category",
        editCategory: "Edit Category",
        categoryName: "Category Name",
        description: "Description",
        optional: "optional",
        enterCategoryName: "Enter category name",
        enterDescription: "Enter category description (optional)",
        categoryNameRequired: "Category name is required",
        descriptionHint:
            "Provide a brief description to help identify this category",
        categoryPreview: "Category Preview",
        noDescriptionPreview: "No description provided",
        cancel: "Cancel",
        updateCategory: "Update Category",
        processing: "Processing...",
    },
    bn: {
        addCategory: "বিভাগ যোগ করুন",
        editCategory: "বিভাগ সম্পাদনা করুন",
        categoryName: "বিভাগের নাম",
        description: "বিবরণ",
        optional: "ঐচ্ছিক",
        enterCategoryName: "বিভাগের নাম লিখুন",
        enterDescription: "বিভাগের বিবরণ লিখুন (ঐচ্ছিক)",
        categoryNameRequired: "বিভাগের নাম প্রয়োজন",
        descriptionHint:
            "এই বিভাগটি চিহ্নিত করতে সহায়তা করার জন্য একটি সংক্ষিপ্ত বিবরণ প্রদান করুন",
        categoryPreview: "বিভাগ পূর্বরূপ",
        noDescriptionPreview: "কোনো বিবরণ প্রদান করা হয়নি",
        cancel: "বাতিল",
        updateCategory: "বিভাগ আপডেট করুন",
        processing: "প্রক্রিয়াকরণ...",
    },
};

const currentLanguage = ref(localStorage?.getItem("language") || "en");
const isSubmitted = ref(false);
const isLoading = ref(false);

const categoryForm = ref({
    name: props.category?.name || "",
    description: props.category?.description || null,
});

watch(
    () => props.category,
    (newCategory) => {
        categoryForm.value = {
            name: newCategory?.name || "",
            description: newCategory?.description || null,
        };
    },
    { deep: true }
);

const getTranslation = (key: string) => {
    return (
        translations[currentLanguage.value]?.[key] ||
        translations.en[key] ||
        key
    );
};

const submit = () => {
    isSubmitted.value = true;

    if (categoryForm.value.name.trim()) {
        isLoading.value = true;
        console.log("Form data:", categoryForm.value);
        emit("submit", { ...categoryForm.value });

        // Reset form after submission if not in edit mode
        setTimeout(() => {
            if (!props.editMode) {
                categoryForm.value = { name: "", description: null };
            }
            isSubmitted.value = false;
            isLoading.value = false;
        }, 1000);
    } else {
        console.error("Please fill all required fields");
    }
};

// Close modal on Escape key
const handleKeyDown = (event: KeyboardEvent) => {
    if (event.key === "Escape") {
        emit("close");
    }
};

// Add event listener when modal is mounted
if (typeof window !== "undefined") {
    window.addEventListener("keydown", handleKeyDown);
}

// Remove event listener when modal is unmounted
const cleanup = () => {
    if (typeof window !== "undefined") {
        window.removeEventListener("keydown", handleKeyDown);
    }
};

// Cleanup on unmount
import { onUnmounted } from "vue";
onUnmounted(cleanup);
</script>

<style scoped>
/* Custom animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Modal animation */
.transform {
    animation: fadeIn 0.3s ease-out;
}

/* Focus states */
input:focus,
textarea:focus {
    outline: none;
    transform: translateY(-1px);
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Textarea styling */
textarea {
    resize: none;
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
