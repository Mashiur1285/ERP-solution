```vue
<template>
    <div class="mt-10">
        <div
            class="bg-white rounded-lg shadow-lg p-8 transition-all duration-300 hover:bg-gray-50 border border-gray-200 relative overflow-hidden"
        >
            <!-- Subtle Background Pattern -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-10 pointer-events-none"
                style="
                    background-image: radial-gradient(
                        circle at 10px 10px,
                        rgba(79, 70, 229, 0.1) 2px,
                        transparent 2px
                    );
                    background-size: 20px 20px;
                "
            ></div>

            <div class="relative z-10">
                <div class="flex justify-between items-center mb-8">
                    <h2
                        class="text-2xl font-semibold text-gray-800 flex items-center animate-fade-in"
                    >
                        <div
                            class="p-2 mr-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full shadow-lg transform hover:rotate-12 transition-transform duration-300"
                        >
                            <svg
                                class="w-8 h-8 text-white"
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
                        {{ selectedMonthYear }} {{ t("expensesOverview") }}
                    </h2>
                    <div class="flex space-x-2">
                        <button
                            @click="navigateToPreviousMonth"
                            class="p-2 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full hover:from-indigo-200 hover:to-purple-200 transition-all duration-300 shadow-md hover:shadow-lg"
                            :aria-label="t('previousMonth')"
                            :disabled="loading"
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
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </button>
                        <button
                            @click="navigateToNextMonth"
                            class="p-2 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full hover:from-indigo-200 hover:to-purple-200 transition-all duration-300 shadow-md hover:shadow-lg"
                            :aria-label="t('nextMonth')"
                            :disabled="loading"
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
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Expenses Cards Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Total Expenses Card -->
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-purple-100 p-6 rounded-xl shadow-lg border border-indigo-200 transform hover:scale-105 hover:shadow-xl transition-all duration-300 animate-fade-in"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-full shadow-lg transform hover:scale-110 transition-transform duration-300"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-sm font-medium text-indigo-600 uppercase tracking-wide"
                                >
                                    {{ t("totalExpenses") }}
                                </p>
                                <p
                                    class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-pulse-slow"
                                >
                                    {{ toBengaliNumber(animatedTotalExpenses) }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-indigo-200 rounded-full h-3">
                            <div
                                class="bg-gradient-to-r from-indigo-600 to-purple-700 h-3 rounded-full transition-all duration-1000"
                                style="width: 100%"
                            ></div>
                        </div>
                    </div>

                    <!-- Total Expense Amount Card -->
                    <div
                        class="bg-gradient-to-br from-purple-50 to-indigo-100 p-6 rounded-xl shadow-lg border border-purple-200 transform hover:scale-105 hover:shadow-xl transition-all duration-300 animate-fade-in"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-3 bg-gradient-to-br from-purple-600 to-indigo-700 rounded-full shadow-lg transform hover:scale-110 transition-transform duration-300"
                            >
                                <svg
                                    class="w-8 h-8 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-sm font-medium text-purple-600 uppercase tracking-wide"
                                >
                                    {{ t("totalAmount") }}
                                </p>
                                <p
                                    class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent animate-pulse-slow"
                                >
                                    ৳{{ toBengaliNumber(animatedTotalAmount) }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full bg-purple-200 rounded-full h-3">
                            <div
                                class="bg-gradient-to-r from-purple-600 to-indigo-700 h-3 rounded-full transition-all duration-1000"
                                style="width: 100%"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, onMounted } from "vue";

const props = defineProps({
    monthlyExpenses: Object,
    month: Number,
    year: Number,
    animatedTotalExpenses: Number,
    animatedTotalAmount: Number,
    t: Function,
    toBengaliNumber: Function,
    selectedMonthYear: String,
    navigateToPreviousMonth: Function,
    navigateToNextMonth: Function,
    loading: Boolean,
});

// Animate numbers
const animateNumber = (refVar, targetValue, duration, context = "unknown") => {
    if (!refVar || typeof refVar !== "object" || !("value" in refVar)) {
        console.error(
            `Invalid refVar in ${context}: Must be a Vue ref object`,
            refVar
        );
        return;
    }
    if (isNaN(targetValue)) {
        console.warn(`Invalid targetValue in ${context}:`, targetValue);
        targetValue = 0;
    }
    let startValue = refVar.value;
    const stepTime = 20;
    const steps = duration / stepTime;
    const increment = (targetValue - startValue) / steps;

    const timer = setInterval(() => {
        startValue += increment;
        if (
            (increment > 0 && startValue >= targetValue) ||
            (increment < 0 && startValue <= targetValue)
        ) {
            refVar.value = targetValue;
            clearInterval(timer);
        } else {
            refVar.value = Math.ceil(startValue);
        }
    }, stepTime);
};

// Initialize animations on mount
onMounted(() => {
    const duration = 1000;
    animateNumber(
        props.animatedTotalExpenses,
        props.monthlyExpenses.total_expenses || 0,
        duration,
        "totalExpenses"
    );
    animateNumber(
        props.animatedTotalAmount,
        props.monthlyExpenses.total_amount || 0,
        duration,
        "totalAmount"
    );
});
</script>

<style scoped>
@keyframes pulseSlow {
    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.03);
        opacity: 0.95;
    }
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

.animate-pulse-slow {
    animation: pulseSlow 2s infinite;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

.bg-gradient-to-r {
    background: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-indigo-600 {
    --tw-gradient-from: #4f46e5;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(79, 70, 229, 0));
}

.to-purple-600 {
    --tw-gradient-to: #7c3aed;
}

.from-purple-600 {
    --tw-gradient-from: #7c3aed;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(124, 58, 237, 0));
}

.to-indigo-600 {
    --tw-gradient-to: #4f46e5;
}

.from-indigo-700 {
    --tw-gradient-from: #4338ca;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(67, 56, 202, 0));
}

.to-purple-700 {
    --tw-gradient-to: #6d28d9;
}

.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

.text-transparent {
    color: transparent;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.shadow-xl {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

.from-indigo-50 {
    --tw-gradient-from: #eef2ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(238, 242, 255, 0));
}

.to-purple-50 {
    --tw-gradient-to: #faf5ff;
}

.from-indigo-100 {
    --tw-gradient-from: #e0e7ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(224, 231, 255, 0));
}

.to-purple-100 {
    --tw-gradient-to: #ede9fe;
}
</style>
```
