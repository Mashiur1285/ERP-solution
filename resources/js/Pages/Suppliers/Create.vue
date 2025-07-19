<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <!-- Toast Notification -->
        <div
            v-if="showToast"
            class="fixed top-6 right-6 px-7 py-5 rounded-lg shadow-md flex items-center space-x-3 animate-toast-in"
            :class="toastClasses"
            role="alert"
        >
            <svg
                class="w-5 h-5 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    v-if="toastType === 'success'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
                <path
                    v-else-if="toastType === 'warning'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
                <path
                    v-else
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <span class="font-medium text-white">{{ toastMessage }}</span>
            <button
                @click="closeToast"
                class="ml-2 text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white"
                aria-label="Close notification"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
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

        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            New Supplier
        </h1>
        <div class="space-y-8 bg-gray-100 p-6 rounded-lg">
            <div class="text-2xl font-semibold">Add new supplier</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label
                        for="company_name"
                        class="block text-sm font-medium text-gray-700"
                        >Company Name*</label
                    >
                    <input
                        v-model="form.company_name"
                        type="text"
                        id="company_name"
                        placeholder="Enter company name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{ 'border-red-500': form.errors.company_name }"
                        @input="form.clearErrors('company_name')"
                    />
                    <p
                        v-if="form.errors.company_name"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.company_name }}
                    </p>
                </div>
                <div>
                    <label
                        for="branch_name"
                        class="block text-sm font-medium text-gray-700"
                        >Branch Name</label
                    >
                    <input
                        v-model="form.branch_name"
                        type="text"
                        id="branch_name"
                        placeholder="Enter branch name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{ 'border-red-500': form.errors.branch_name }"
                        @input="form.clearErrors('branch_name')"
                    />
                    <p
                        v-if="form.errors.branch_name"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.branch_name }}
                    </p>
                </div>
                <div>
                    <label
                        for="phone_number"
                        class="block text-sm font-medium text-gray-700"
                        >Phone Number*</label
                    >
                    <input
                        v-model="form.phone_number"
                        type="tel"
                        id="phone_number"
                        placeholder="Enter phone number"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{ 'border-red-500': form.errors.phone_number }"
                        @input="form.clearErrors('phone_number')"
                    />
                    <p
                        v-if="form.errors.phone_number"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.phone_number }}
                    </p>
                </div>
                <div>
                    <label
                        for="emergency_phone_number"
                        class="block text-sm font-medium text-gray-700"
                        >Emergency Phone Number</label
                    >
                    <input
                        v-model="form.emergency_phone_number"
                        type="tel"
                        id="emergency_phone_number"
                        placeholder="Enter emergency phone number"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{
                            'border-red-500':
                                form.errors.emergency_phone_number,
                        }"
                        @input="form.clearErrors('emergency_phone_number')"
                    />
                    <p
                        v-if="form.errors.emergency_phone_number"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.emergency_phone_number }}
                    </p>
                </div>
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                        >Email</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        placeholder="Enter email"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{ 'border-red-500': form.errors.email }"
                        @input="form.clearErrors('email')"
                    />
                    <p
                        v-if="form.errors.email"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label
                        for="website"
                        class="block text-sm font-medium text-gray-700"
                        >Website</label
                    >
                    <input
                        v-model="form.website"
                        type="url"
                        id="website"
                        placeholder="Enter website"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{ 'border-red-500': form.errors.website }"
                        @input="form.clearErrors('website')"
                    />
                    <p
                        v-if="form.errors.website"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.website }}
                    </p>
                </div>
                <div>
                    <label
                        for="city"
                        class="block text-sm font-medium text-gray-700"
                        >City</label
                    >
                    <input
                        v-model="form.city"
                        type="text"
                        id="city"
                        placeholder="Enter city"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{ 'border-red-500': form.errors.city }"
                        @input="form.clearErrors('city')"
                    />
                    <p
                        v-if="form.errors.city"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.city }}
                    </p>
                </div>
                <div>
                    <label
                        for="country"
                        class="block text-sm font-medium text-gray-700"
                        >Country</label
                    >
                    <input
                        v-model="form.country"
                        type="text"
                        id="country"
                        placeholder="Enter country"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{ 'border-red-500': form.errors.country }"
                        @input="form.clearErrors('country')"
                    />
                    <p
                        v-if="form.errors.country"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.country }}
                    </p>
                </div>
            </div>
            <div>
                <label
                    for="address"
                    class="block text-sm font-medium text-gray-700"
                    >Address*</label
                >
                <textarea
                    v-model="form.address"
                    id="address"
                    placeholder="Enter address"
                    class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    :class="{ 'border-red-500': form.errors.address }"
                    rows="5"
                    @input="form.clearErrors('address')"
                ></textarea>
                <p v-if="form.errors.address" class="mt-1 text-sm text-red-500">
                    {{ form.errors.address }}
                </p>
            </div>
            <div class="flex justify-end space-x-4">
                <Link
                    href="/suppliers/index"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition duration-200"
                    >Cancel</Link
                >
                <button
                    @click="submitForm"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Saving..." : "Save Supplier" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Layout from "../../Layout.vue";

defineOptions({
    layout: Layout,
});

const form = useForm({
    company_name: "",
    branch_name: "",
    phone_number: "",
    emergency_phone_number: "",
    email: "",
    address: "",
    city: "",
    country: "",
    website: "",
});

// Toast state
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success"); // success, warning, error
const toastExiting = ref(false);

const toastClasses = computed(() => ({
    "bg-green-500": toastType.value === "success",
    "bg-orange-500": toastType.value === "warning",
    "bg-red-500": toastType.value === "error",
    "text-white": true,
}));

const showToastWithType = (message, type = "success") => {
    // Close any existing toast before showing a new one
    if (showToast.value) {
        toastExiting.value = true;
        setTimeout(() => {
            showToast.value = false;
            toastExiting.value = false;
            toastMessage.value = message;
            toastType.value = type;
            showToast.value = true;
            // Auto-hide toast after 5 seconds
            setTimeout(() => {
                toastExiting.value = true;
                setTimeout(() => {
                    showToast.value = false;
                }, 300);
            }, 5000);
        }, 300);
    } else {
        toastMessage.value = message;
        toastType.value = type;
        showToast.value = true;
        toastExiting.value = false;
        // Auto-hide toast after 5 seconds
        setTimeout(() => {
            toastExiting.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 300);
        }, 5000);
    }
};

const closeToast = () => {
    toastExiting.value = true;
    setTimeout(() => {
        showToast.value = false;
        toastExiting.value = false;
    }, 300);
};

const submitForm = () => {
    // Basic client-side validation for warning example
    if (!form.company_name || !form.phone_number || !form.address) {
        showToastWithType("Please fill all required fields.", "warning");
        return;
    }

    form.post("/suppliers/store", {
        onSuccess: () => {
            form.reset();
            showToastWithType("Supplier Created Successfully", "success");
        },
        onError: (errors) => {
            console.error("Form submission errors:", errors);
            showToastWithType(
                "Failed to create supplier. Please check the form.",
                "error"
            );
        },
    });
};
</script>

<style scoped>
input,
textarea {
    padding: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
}

.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgb(175, 185, 194);
}

/* Toast animations */
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

@keyframes toast-out {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
}

.animate-toast-out {
    animation: toast-out 0.3s ease-out forwards;
}
</style>
