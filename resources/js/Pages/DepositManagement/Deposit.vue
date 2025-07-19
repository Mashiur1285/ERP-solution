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
            Deposit Management
        </h1>

        <!-- Add Deposit Button -->
        <div class="mb-8 pl-[950px]">
            <button
                @click="showDepositModal = true"
                class="px-4 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
            >
                Add Deposit
            </button>
        </div>

        <!-- Deposit Modal -->
        <DepositModal
            v-if="showDepositModal"
            :suppliers="suppliers"
            @close="showDepositModal = false"
            @submit="submitDeposit"
        />

        <!-- Deposit History -->
        <div class="space-y-6">
            <h2 class="text-2xl font-semibold text-gray-800">
                Deposit History
            </h2>
            <table class="min-w-full divide-y divide-gray-400">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Supplier
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Deposit Amount (TK)
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Used Money (TK)
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Remaining Amount (TK)
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="deposit in depositHistory"
                        :key="deposit.id"
                        class="hover:bg-gray-50 transition-colors"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ deposit.name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ deposit.deposit_amount }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{
                                    deposit.deposit_amount != null &&
                                    deposit.remaining_amount != null
                                        ? deposit.deposit_amount -
                                          deposit.remaining_amount
                                        : "-"
                                }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ deposit.remaining_amount ?? "-" }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ new Date(deposit.date).toLocaleString() }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import DepositModal from "./Partials/DepositModal.vue";

interface Supplier {
    id: number;
    company_name: string;
}

interface Deposit {
    id: number;
    name: string;
    deposit_amount: number;
    used_money: number;
    remaining_amount: number;
    date: string;
}

const props = defineProps<{
    suppliers: Supplier[];
    depositHistory: Deposit[];
}>();

defineOptions({
    layout: Layout,
});

const showDepositModal = ref(false);

// Toast state
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success"); // success, error
const toastExiting = ref(false);

const toastClasses = computed(() => ({
    "bg-green-500": toastType.value === "success",
    "bg-red-500": toastType.value === "error",
    "text-white": true,
}));

const showToastWithType = (message: string, type: string = "success") => {
    if (showToast.value) {
        toastExiting.value = true;
        setTimeout(() => {
            showToast.value = false;
            toastExiting.value = false;
            toastMessage.value = message;
            toastType.value = type;
            showToast.value = true;
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

const submitDeposit = (depositData: {
    supplier_id: string;
    balance_deposited: number;
}) => {
    router.post("/deposits/store", depositData, {
        onSuccess: () => {
            showDepositModal.value = false;
            showToastWithType("Deposit Added Successfully", "success");
        },
        onError: (errors) => {
            console.error("Deposit submission errors:", errors);
            showToastWithType(
                "Failed to add deposit. Please check the form.",
                "error"
            );
        },
    });
};

console.log("DepositManagement.vue component loaded");
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
