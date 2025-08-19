```vue
<template>
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        role="dialog"
        aria-labelledby="modal-title"
        aria-modal="true"
    >
        <!-- Background overlay -->
        <div
            class="fixed inset-0 bg-black bg-opacity-30 transition-opacity duration-300"
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
                    class="bg-gradient-to-r from-indigo-200 to-purple-200 px-6 py-4"
                >
                    <div class="flex items-center justify-between">
                        <h3
                            id="modal-title"
                            class="text-xl font-semibold text-gray-800 flex items-center"
                        >
                            <div
                                class="p-2 bg-white bg-opacity-20 rounded-full mr-3"
                            >
                                <svg
                                    class="w-6 h-6 text-indigo-400"
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
                                    ? getTranslation("editBrand")
                                    : getTranslation("addBrand")
                            }}
                        </h3>
                        <button
                            @click="$emit('close')"
                            class="text-gray-800 hover:text-gray-600 transition-colors p-2 rounded-full hover:bg-white hover:bg-opacity-20"
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
                        <!-- Brand Name -->
                        <div>
                            <label
                                for="brand_name"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 mr-2 text-indigo-400"
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
                                    {{ getTranslation("brandName") }}*
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
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    v-model="brandForm.brand_name"
                                    id="brand_name"
                                    type="text"
                                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-indigo-100 rounded-xl shadow-sm focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 transition-all duration-300 text-sm font-medium hover:border-indigo-200"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            isSubmitted &&
                                            !brandForm.brand_name,
                                    }"
                                    :placeholder="
                                        getTranslation('enterBrandName')
                                    "
                                    required
                                />
                            </div>
                            <p
                                v-if="isSubmitted && !brandForm.brand_name"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ getTranslation("brandNameRequired") }}
                            </p>
                        </div>

                        <!-- Brand Description -->
                        <div>
                            <label
                                for="description"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 mr-2 text-indigo-400"
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
                                    v-model="brandForm.description"
                                    id="description"
                                    rows="4"
                                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-indigo-100 rounded-xl shadow-sm focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 transition-all duration-300 text-sm font-medium hover:border-indigo-200 resize-none"
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
                            v-if="brandForm.brand_name"
                            class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-100"
                        >
                            <h4
                                class="text-sm font-semibold text-gray-800 mb-3 flex items-center"
                            >
                                <svg
                                    class="w-4 h-4 mr-2 text-indigo-400"
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
                                {{ getTranslation("brandPreview") }}
                            </h4>
                            <div
                                class="bg-white rounded-lg p-4 border border-indigo-100"
                            >
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="p-2 bg-indigo-50 rounded-lg flex-shrink-0"
                                    >
                                        <svg
                                            class="w-5 h-5 text-indigo-400"
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
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5
                                            class="font-semibold text-gray-900 mb-1"
                                        >
                                            {{ brandForm.brand_name }}
                                        </h5>
                                        <p class="text-sm text-gray-600">
                                            {{
                                                brandForm.description ||
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
                        class="w-full sm:w-auto px-6 py-3 border-2 border-indigo-100 rounded-xl text-gray-700 font-semibold hover:bg-gray-100 hover:border-indigo-200 transition-all duration-300 flex items-center justify-center space-x-2"
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
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-200 to-purple-200 text-gray-800 font-semibold rounded-xl hover:from-indigo-300 hover:to-purple-300 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
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
                                    ? getTranslation("updateBrand")
                                    : getTranslation("addBrand")
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

const translations = {
    en: {
        addBrand: "Add Brand",
        editBrand: "Edit Brand",
        brandName: "Brand Name",
        description: "Description",
        optional: "optional",
        enterBrandName: "Enter brand name",
        enterDescription: "Enter brand description (optional)",
        brandNameRequired: "Brand name is required",
        descriptionHint:
            "Provide a brief description to help identify this brand",
        brandPreview: "Brand Preview",
        noDescriptionPreview: "No description provided",
        cancel: "Cancel",
        updateBrand: "Update Brand",
        processing: "Processing...",
    },
    bn: {
        addBrand: "ব্র্যান্ড যোগ করুন",
        editBrand: "ব্র্যান্ড সম্পাদনা করুন",
        brandName: "ব্র্যান্ডের নাম",
        description: "বিবরণ",
        optional: "ঐচ্ছিক",
        enterBrandName: "ব্র্যান্ডের নাম লিখুন",
        enterDescription: "ব্র্যান্ডের বিবরণ লিখুন (ঐচ্ছিক)",
        brandNameRequired: "ব্র্যান্ডের নাম প্রয়োজন",
        descriptionHint:
            "এই ব্র্যান্ডটি চিহ্নিত করতে সহায়তা করার জন্য একটি সংক্ষিপ্ত বিবরণ প্রদান করুন",
        brandPreview: "ব্র্যান্ড পূর্বরূপ",
        noDescriptionPreview: "কোনো বিবরণ প্রদান করা হয়নি",
        cancel: "বাতিল",
        updateBrand: "ব্র্যান্ড আপডেট করুন",
        processing: "প্রক্রিয়াকরণ...",
    },
};

const currentLanguage = ref(localStorage?.getItem("language") || "en");
const isSubmitted = ref(false);
const isLoading = ref(false);

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

const getTranslation = (key: string) => {
    return (
        translations[currentLanguage.value]?.[key] ||
        translations.en[key] ||
        key
    );
};

const submit = () => {
    isSubmitted.value = true;

    if (brandForm.value.brand_name.trim()) {
        isLoading.value = true;
        console.log("Form data:", brandForm.value);
        emit("submit", { ...brandForm.value });

        // Reset form after submission if not in edit mode
        setTimeout(() => {
            if (!props.editMode) {
                brandForm.value = { brand_name: "", description: null };
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

/* Custom Tailwind color definitions */
.from-indigo-50 {
    --tw-gradient-from: #eef2ff;
}

.to-purple-50 {
    --tw-gradient-to: #f5f3ff;
}

.from-indigo-100 {
    --tw-gradient-from: #e0e7ff;
}

.to-purple-100 {
    --tw-gradient-to: #ede9fe;
}

.from-indigo-200 {
    --tw-gradient-from: #c7d2fe;
}

.to-purple-200 {
    --tw-gradient-to: #ddd6fe;
}

.from-indigo-300 {
    --tw-gradient-from: #a5b4fc;
}

.to-purple-300 {
    --tw-gradient-to: #c4b5fd;
}
</style>
```
