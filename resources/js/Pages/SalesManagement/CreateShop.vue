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
            Create Shop
        </h1>

        <!-- Shop Creation Form -->
        <div class="space-y-6 mb-12 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-800">Add New Shop</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label
                        for="shop_name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Shop Name*
                    </label>
                    <input
                        v-model="shopForm.shop_name"
                        id="shop_name"
                        type="text"
                        placeholder="Enter shop name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{
                            'border-red-500': shopForm.errors?.shop_name,
                        }"
                        @input="shopForm.clearErrors?.('shop_name')"
                        required
                    />
                    <p
                        v-if="shopForm.errors?.shop_name"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ shopForm.errors.shop_name }}
                    </p>
                </div>
                <div>
                    <label
                        for="owner_name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Owner Name
                    </label>
                    <input
                        v-model="shopForm.owner_name"
                        id="owner_name"
                        type="text"
                        placeholder="Enter owner name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="shop_address"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Shop Address
                    </label>
                    <input
                        v-model="shopForm.shop_address"
                        id="shop_address"
                        type="text"
                        placeholder="Enter shop address"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="phone_number"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Phone Number*
                    </label>
                    <input
                        v-model="shopForm.phone_number"
                        id="phone_number"
                        type="text"
                        placeholder="Enter phone number"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        :class="{
                            'border-red-500': shopForm.errors?.phone_number,
                        }"
                        @input="shopForm.clearErrors?.('phone_number')"
                        required
                    />
                    <p
                        v-if="shopForm.errors?.phone_number"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ shopForm.errors.phone_number }}
                    </p>
                </div>
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Email
                    </label>
                    <input
                        v-model="shopForm.email"
                        id="email"
                        type="email"
                        placeholder="Enter email"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="website"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Website
                    </label>
                    <input
                        v-model="shopForm.website"
                        id="website"
                        type="url"
                        placeholder="Enter website"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="national_id"
                        class="block text-sm font-medium text-gray-700"
                    >
                        National ID
                    </label>
                    <input
                        v-model="shopForm.national_id"
                        id="national_id"
                        type="text"
                        placeholder="Enter national ID"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="trade_license"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Trade License
                    </label>
                    <input
                        v-model="shopForm.trade_license"
                        id="trade_license"
                        type="text"
                        placeholder="Enter trade license"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="tax_id"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Tax ID
                    </label>
                    <input
                        v-model="shopForm.tax_id"
                        id="tax_id"
                        type="text"
                        placeholder="Enter tax ID"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
            </div>
            <div>
                <label
                    for="notes"
                    class="block text-sm font-medium text-gray-700"
                >
                    Notes
                </label>
                <textarea
                    v-model="shopForm.notes"
                    id="notes"
                    placeholder="Enter any additional notes"
                    class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    rows="4"
                ></textarea>
            </div>
            <div class="flex justify-end mt-6">
                <button
                    @click="submitShop"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                    :disabled="shopForm.processing"
                >
                    {{ shopForm.processing ? "Creating..." : "Create Shop" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface ShopForm {
    shop_name: string;
    owner_name: string | null;
    shop_address: string | null;
    phone_number: string;
    email: string | null;
    website: string | null;
    national_id: string | null;
    trade_license: string | null;
    tax_id: string | null;
    notes: string | null;
    errors?: Record<string, string>;
}

const shopForm = useForm<ShopForm>({
    shop_name: "",
    owner_name: null,
    shop_address: null,
    phone_number: "",
    email: null,
    website: null,
    national_id: null,
    trade_license: null,
    tax_id: null,
    notes: null,
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

const showToastWithType = (message: string, type: string = "success") => {
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

defineOptions({
    layout: Layout,
});

const submitShop = () => {
    // Basic client-side validation for required fields
    if (!shopForm.shop_name || !shopForm.phone_number) {
        showToastWithType("Please fill all required fields.", "warning");
        return;
    }

    shopForm.post("/shops/store", {
        onSuccess: () => {
            shopForm.reset();
            showToastWithType("Shop Created Successfully", "success");
        },
        onError: (errors: Record<string, string>) => {
            console.error("Shop creation errors:", errors);
            showToastWithType(
                "Failed to create shop. Please check the form.",
                "error"
            );
        },
    });
};

console.log("CreateShop.vue component loaded");
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
