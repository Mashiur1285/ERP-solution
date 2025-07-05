<template>
    <div
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center"
    >
        <div class="bg-white p-8 rounded-xl shadow-xl max-w-2xl w-full">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                Add Deposit
            </h2>
            <div class="space-y-6">
                <div>
                    <label
                        for="supplier_id"
                        class="block text-sm font-medium text-gray-700"
                        >Supplier*</label
                    >
                    <select
                        v-model="depositForm.supplier_id"
                        id="supplier_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    >
                        <option value="" disabled>Select a supplier</option>
                        <option
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            :value="supplier.id"
                        >
                            {{ supplier.company_name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label
                        for="balance_deposited"
                        class="block text-sm font-medium text-gray-700"
                        >Deposit Amount (TK)*</label
                    >
                    <input
                        v-model="depositForm.balance_deposited"
                        type="number"
                        step="0.01"
                        id="balance_deposited"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    />
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
                    Add Deposit
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";

interface Supplier {
    id: number;
    company_name: string;
}

const props = defineProps<{
    suppliers: Supplier[];
}>();

const emit = defineEmits<{
    (e: "close"): void;
    (
        e: "submit",
        depositData: { supplier_id: string; balance_deposited: number }
    ): void;
}>();

const depositForm = ref({
    supplier_id: "",
    balance_deposited: 0,
});

const submit = () => {
    if (
        depositForm.value.supplier_id &&
        depositForm.value.balance_deposited > 0
    ) {
        emit("submit", { ...depositForm.value });
        depositForm.value = { supplier_id: "", balance_deposited: 0 };
    } else {
        console.error("Please fill all required fields");
    }
};
</script>
