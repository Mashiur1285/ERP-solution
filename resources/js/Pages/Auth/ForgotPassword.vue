<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-gray-50 py-12 px-4 sm:px-6 lg:px-8 relative"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Subtle Background SVG -->
        <svg
            class="absolute inset-0 w-full h-full opacity-5 pointer-events-none -z-10"
            viewBox="0 0 1440 320"
            preserveAspectRatio="none"
        >
            <defs>
                <linearGradient
                    id="forgotPasswordBgGradient"
                    x1="0%"
                    y1="0%"
                    x2="100%"
                    y2="100%"
                >
                    <stop
                        offset="0%"
                        style="stop-color: #4f46e5; stop-opacity: 0.1"
                    />
                    <stop
                        offset="50%"
                        style="stop-color: #10b981; stop-opacity: 0.1"
                    />
                    <stop
                        offset="100%"
                        style="stop-color: #7c3aed; stop-opacity: 0.1"
                    />
                </linearGradient>
            </defs>
            <path
                fill="url(#forgotPasswordBgGradient)"
                d="M0,160L48,138.7C96,117,192,75,288,80C384,85,480,139,576,149.3C672,160,768,128,864,106.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
            />
        </svg>

        <!-- Toast Notification -->
        <div
            v-if="statusMessage"
            class="fixed top-4 right-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md shadow-lg flex items-center space-x-2 animate-fade-in z-50"
            :class="{ 'bangla-font': currentLanguage === 'bn' }"
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
            <span>{{ statusMessage }}</span>
            <button
                @click="statusMessage = null"
                class="ml-2 text-white hover:text-gray-200"
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
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <div
            class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-lg p-8 border border-gray-100 relative z-10"
        >
            <!-- Language Toggle -->
            <div class="flex justify-end space-x-2 mb-6">
                <button
                    @click="changeLanguage('en')"
                    :class="[
                        'px-3 py-1 rounded-md font-medium transition-colors text-sm',
                        currentLanguage === 'en'
                            ? 'bg-indigo-600 text-white'
                            : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                    ]"
                >
                    {{ translations.en.languageLabel }}
                </button>
                <button
                    @click="changeLanguage('bn')"
                    :class="[
                        'px-3 py-1 rounded-md font-medium transition-colors text-sm',
                        currentLanguage === 'bn'
                            ? 'bg-indigo-600 text-white'
                            : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                    ]"
                >
                    {{ translations.bn.languageLabel }}
                </button>
            </div>

            <!-- Forgot Password Form Header -->
            <div class="text-center">
                <h2
                    class="text-3xl font-semibold text-gray-800 flex items-center justify-center mb-6"
                >
                    <div
                        class="p-2 mr-3 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center shadow-lg"
                    >
                        <svg
                            class="w-8 h-8 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>
                    </div>
                    {{ t("forgotPasswordTitle") }}
                </h2>
                <p class="text-sm text-gray-600">
                    {{ t("forgotPasswordSubtitle") }}
                </p>
            </div>

            <!-- Form -->
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="space-y-4">
                    <!-- Email Field -->
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700"
                        >
                            {{ t("email") }}
                        </label>
                        <div class="mt-1 relative">
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                autocomplete="email"
                                required
                                autofocus
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                    safeErrors.email
                                        ? 'border-red-300'
                                        : 'border-gray-300',
                                ]"
                                :placeholder="t('emailPlaceholder')"
                            />
                            <div
                                v-if="safeErrors.email"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="h-5 w-5 text-red-500"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>
                        <p
                            v-if="safeErrors.email"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ safeErrors.email }}
                        </p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 disabled:opacity-50"
                    >
                        {{ t("submitButton") }}
                    </button>
                </div>
            </form>

            <!-- Back to Login Link -->
            <p class="mt-6 text-center text-sm text-gray-600">
                {{ t("backToLogin") }}
                <Link
                    :href="safeRoute('login', '/login')"
                    class="font-medium text-indigo-600 hover:text-indigo-500"
                >
                    {{ t("signIn") }}
                </Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onErrorCaptured } from "vue";
import { useForm, Link, usePage } from "@inertiajs/vue3";

defineProps({
    status: String,
});

const currentLanguage = ref(localStorage.getItem("language") || "en");
const statusMessage = ref(null);

// Translation object
const translations = {
    en: {
        languageLabel: "English",
        forgotPasswordTitle: "Reset Your Password",
        forgotPasswordSubtitle:
            "Enter your email address to receive a password reset link.",
        email: "Email address",
        emailPlaceholder: "Enter your email",
        submitButton: "Email Password Reset Link",
        backToLogin: "Back to",
        signIn: "Sign in",
        statusMessage: "A password reset link has been sent to your email.",
    },
    bn: {
        languageLabel: "বাংলা",
        forgotPasswordTitle: "আপনার পাসওয়ার্ড পুনরায় সেট করুন",
        forgotPasswordSubtitle:
            "পাসওয়ার্ড পুনরায় সেট করার লিঙ্ক পেতে আপনার ইমেল ঠিকানা লিখুন।",
        email: "ইমেল ঠিকানা",
        emailPlaceholder: "আপনার ইমেল লিখুন",
        submitButton: "পাসওয়ার্ড পুনরায় সেট করার লিঙ্ক ইমেল করুন",
        backToLogin: "ফিরে যান",
        signIn: "সাইন ইন",
        statusMessage:
            "আপনার ইমেলে একটি পাসওয়ার্ড পুনরায় সেট করার লিঙ্ক পাঠানো হয়েছে।",
    },
};

// Translation function
const t = computed(() => (key) => translations[currentLanguage.value][key]);

// Change language
const changeLanguage = (lang) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

// Form handling with safe initialization
const form = useForm({
    email: "",
});

// Safe error access - CRITICAL FIX for password.request error
const safeErrors = computed(() => {
    try {
        return {
            email: form.errors?.email || null,
            general: form.errors?.general || null,
        };
    } catch (error) {
        console.warn('Error accessing form errors:', error);
        return { email: null, general: null };
    }
});

// Safe route helper
const safeRoute = (routeName, fallback = '#') => {
    try {
        if (typeof route === 'function') {
            return route(routeName);
        }
        return fallback;
    } catch (error) {
        console.warn(`Route ${routeName} not found:`, error);
        return fallback;
    }
};

const submit = () => {
    try {
        form.post(safeRoute("password.email", "/password/email"), {
            onSuccess: () => {
                form.reset("email");
                statusMessage.value = translations[currentLanguage.value].statusMessage;
            },
            onError: (errors) => {
                console.log('Password reset errors:', errors);
                statusMessage.value = null;
            },
        });
    } catch (error) {
        console.error('Password reset submission error:', error);
    }
};

// Watch for status prop to display toast
watch(
    () => {
        try {
            return usePage().props?.status;
        } catch {
            return null;
        }
    },
    (newStatus) => {
        if (newStatus) {
            statusMessage.value = newStatus;
            // Auto-dismiss toast after 5 seconds
            setTimeout(() => {
                statusMessage.value = null;
            }, 5000);
        }
    }
);

// Error boundary
onErrorCaptured((error, instance, info) => {
    console.error('ForgotPassword component error:', error, info);
    return false; // Prevent error from propagating
});

</script>