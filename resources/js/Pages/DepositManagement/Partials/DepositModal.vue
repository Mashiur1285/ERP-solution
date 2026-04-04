```vue
<template>
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
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
                class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all duration-300 w-full max-w-lg"
                @click.stop
            >
                <!-- Modal header -->
                <div class="bg-white px-6 py-4 border-b border-gray-200">
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
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    />
                                </svg>
                            </div>
                            {{ editMode ? getTranslation("editDeposit") : getTranslation("addDeposit") }}
                        </h3>
                        <button
                            @click="$emit('close')"
                            class="text-gray-800 hover:text-gray-600 transition-colors p-2 rounded-full hover:bg-gray-100"
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
                <div class="px-6 py-6 bg-white">
                    <div class="space-y-6">
                        <!-- Supplier Selection -->
                        <div>
                            <label
                                for="supplier_id"
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
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>
                                    {{ getTranslation("supplier") }}*
                                </div>
                            </label>
                            <div class="relative">
                                <select
                                    v-model="depositForm.supplier_id"
                                    id="supplier_id"
                                    class="w-full px-4 py-3 bg-white border-2 border-indigo-100 rounded-xl shadow-sm focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 transition-all duration-300 text-sm font-medium hover:border-indigo-200 appearance-none"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            isSubmitted &&
                                            !depositForm.supplier_id,
                                    }"
                                    :disabled="editMode"
                                    required
                                >
                                    <option value="" disabled>
                                        {{ getTranslation("selectSupplier") }}
                                    </option>
                                    <option
                                        v-for="supplier in suppliers"
                                        :key="supplier.id"
                                        :value="supplier.id"
                                    >
                                        {{ supplier.company_name }}
                                    </option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none"
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
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <p
                                v-if="isSubmitted && !depositForm.supplier_id"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ getTranslation("supplierRequired") }}
                            </p>
                        </div>

                        <!-- Deposit Amount -->
                        <div>
                            <label
                                for="balance_deposited"
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
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    {{ getTranslation("depositAmount") }} ({{
                                        getTranslation("currency")
                                    }})*
                                </div>
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <span
                                        class="text-gray-500 text-lg font-medium"
                                        >৳</span
                                    >
                                </div>
                                <input
                                    v-model.number="
                                        depositForm.balance_deposited
                                    "
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="balance_deposited"
                                    class="w-full pl-8 pr-4 py-3 bg-white border-2 border-indigo-100 rounded-xl shadow-sm focus:border-indigo-300 focus:ring-4 focus:ring-indigo-50 transition-all duration-300 text-sm font-medium hover:border-indigo-200"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            isSubmitted &&
                                            (!depositForm.balance_deposited ||
                                                depositForm.balance_deposited <=
                                                    0),
                                    }"
                                    :placeholder="getTranslation('enterAmount')"
                                    required
                                />
                            </div>
                            <p
                                v-if="
                                    isSubmitted &&
                                    (!depositForm.balance_deposited ||
                                        depositForm.balance_deposited <= 0)
                                "
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ getTranslation("amountRequired") }}
                            </p>
                        </div>

                        <!-- Preview Section -->
                        <div
                            v-if="
                                depositForm.supplier_id &&
                                depositForm.balance_deposited > 0
                            "
                                class="bg-white rounded-xl p-4 border border-gray-200"
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
                                {{ getTranslation("depositPreview") }}
                            </h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">
                                        {{
                                            getTranslation("selectedSupplier")
                                        }}:
                                    </p>
                                    <p class="font-semibold text-gray-900">
                                        {{ selectedSupplierName }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600">
                                        {{ getTranslation("depositAmount") }}:
                                    </p>
                                    <p
                                        class="font-semibold text-green-600 text-lg"
                                    >
                                        ৳{{
                                            toBengaliNumber(
                                                depositForm.balance_deposited
                                            )
                                        }}
                                    </p>
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
                        class="w-full sm:w-auto px-6 py-3 bg-white text-gray-800 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 focus:ring-4 focus:ring-indigo-50 transition-all duration-300 flex items-center justify-center space-x-2 shadow"
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
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            />
                        </svg>
                        <span>{{
                            isLoading
                                ? getTranslation("processing")
                                : editMode
                                    ? getTranslation("updateDeposit")
                                    : getTranslation("addDeposit")
                        }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onUnmounted } from "vue";

interface Supplier {
    id: number;
    company_name: string;
}

const props = defineProps<{
    suppliers: Supplier[];
    editMode?: boolean;
    deposit?: {
        id?: number;
        supplier_id: number | string;
        balance_deposited: number;
    } | null;
}>();

const emit = defineEmits<{
    (e: "close"): void;
    (
        e: "submit",
        depositData: { supplier_id: string; balance_deposited: number }
    ): void;
}>();

const translations = {
    en: {
        addDeposit: "Add Deposit",
        supplier: "Supplier",
        selectSupplier: "Select a supplier",
        supplierRequired: "Please select a supplier",
        depositAmount: "Deposit Amount",
        currency: "TK",
        enterAmount: "Enter deposit amount",
        amountRequired: "Please enter a valid amount greater than 0",
        depositPreview: "Deposit Preview",
        selectedSupplier: "Selected Supplier",
        editDeposit: "Edit Deposit",
        updateDeposit: "Update Deposit",
        cancel: "Cancel",
        processing: "Processing...",
    },
    bn: {
        addDeposit: "আমানত যোগ করুন",
        supplier: "সরবরাহকারী",
        selectSupplier: "একটি সরবরাহকারী নির্বাচন করুন",
        supplierRequired: "অনুগ্রহ করে একটি সরবরাহকারী নির্বাচন করুন",
        depositAmount: "আমানতের পরিমাণ",
        currency: "টাকা",
        enterAmount: "আমানতের পরিমাণ লিখুন",
        amountRequired: "অনুগ্রহ করে ০-এর বেশি একটি বৈধ পরিমাণ লিখুন",
        depositPreview: "আমানত পূর্বরূপ",
        selectedSupplier: "নির্বাচিত সরবরাহকারী",
        editDeposit: "আমানত সম্পাদনা করুন",
        updateDeposit: "আমানত আপডেট করুন",
        cancel: "বাতিল",
        processing: "প্রক্রিয়াকরণ...",
    },
};

const currentLanguage = ref(localStorage?.getItem("language") || "en");
const isSubmitted = ref(false);
const isLoading = ref(false);
const editMode = computed(() => Boolean(props.editMode));

const depositForm = ref({
    supplier_id: "",
    balance_deposited: 0,
});

watch(
    () => props.deposit,
    (deposit) => {
        depositForm.value = deposit
            ? {
                  supplier_id: String(deposit.supplier_id ?? ""),
                  balance_deposited: Number(deposit.balance_deposited ?? 0),
              }
            : { supplier_id: "", balance_deposited: 0 };
        isSubmitted.value = false;
    },
    { immediate: true }
);

const selectedSupplierName = computed(() => {
    const supplier = props.suppliers.find(
        (s) => s.id === parseInt(depositForm.value.supplier_id)
    );
    return supplier?.company_name || "";
});

const getTranslation = (key: string) => {
    return (
        translations[currentLanguage.value]?.[key] ||
        translations.en[key] ||
        key
    );
};

const toBengaliNumber = (num: number | string): string => {
    if (num === null || num === undefined || num === "") return "";
    
    // Round decimals to 2 places if it's a number or a numeric string
    let n = Number(num);
    if (!isNaN(n) && n % 1 !== 0) {
        num = n.toFixed(2);
    } else if (!isNaN(n)) {
        num = n.toString();
    }

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

const submit = () => {
    isSubmitted.value = true;

    if (
        depositForm.value.supplier_id &&
        depositForm.value.balance_deposited > 0
    ) {
        isLoading.value = true;
        emit("submit", { ...depositForm.value });
        isLoading.value = false;
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

onUnmounted(cleanup);
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}
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
select:focus {
    outline: none;
    transform: translateY(-1px);
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Number input styling */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
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
