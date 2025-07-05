<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Deposit Management
        </h1>

        <!-- Add Deposit Button -->
        <div class="mb-8 pl-[950px]">
            <button
                @click="showDepositModal = true"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
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
import { ref } from "vue";
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

const submitDeposit = (depositData: {
    supplier_id: string;
    balance_deposited: number;
}) => {
    console.log("Submitting deposit:", depositData);
    router.post("/deposits/store", depositData, {
        onSuccess: () => {
            showDepositModal.value = false;
        },
        onError: (errors) => {
            console.error("Deposit submission errors:", errors);
        },
    });
};

console.log("Deposit.vue component loaded");
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
