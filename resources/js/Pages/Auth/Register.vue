<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-gray-50 py-12 px-4 sm:px-6 lg:px-8"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <div
            class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-lg p-8 border border-gray-100"
        >
            <!-- Subtle Background SVG -->
            <svg
                class="absolute inset-0 w-full h-full opacity-5 pointer-events-none"
                viewBox="0 0 1440 320"
                preserveAspectRatio="none"
            >
                <defs>
                    <linearGradient
                        id="registerBgGradient"
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
                    fill="url(#registerBgGradient)"
                    d="M0,160L48,138.7C96,117,192,75,288,80C384,85,480,139,576,149.3C672,160,768,128,864,106.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                />
            </svg>

            <div class="relative z-10">
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

                <!-- Register Form Header -->
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
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                />
                            </svg>
                        </div>
                        {{ t("registerTitle") }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ t("registerSubtitle") }}
                    </p>
                </div>

                <!-- Form -->
                <form class="mt-8 space-y-6" @submit.prevent="submit">
                    <div class="space-y-4">
                        <!-- Name Field -->
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ t("name") }}
                            </label>
                            <div class="mt-1 relative">
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    autocomplete="name"
                                    required
                                    :class="[
                                        'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                        safeErrors.name
                                            ? 'border-red-300'
                                            : 'border-gray-300',
                                    ]"
                                    :placeholder="t('namePlaceholder')"
                                />
                                <div
                                    v-if="safeErrors.name"
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
                                v-if="safeErrors.name"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ safeErrors.name }}
                            </p>
                        </div>

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

                        <!-- Password Field -->
                        <div>
                            <label
                                for="password"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ t("password") }}
                            </label>
                            <div class="mt-1 relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    :class="[
                                        'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                        safeErrors.password
                                            ? 'border-red-300'
                                            : 'border-gray-300',
                                    ]"
                                    :placeholder="t('passwordPlaceholder')"
                                />
                                <div
                                    v-if="safeErrors.password"
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
                                v-if="safeErrors.password"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ safeErrors.password }}
                            </p>
                        </div>

                        <!-- Password Confirmation Field -->
                        <div>
                            <label
                                for="password_confirmation"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ t("passwordConfirmation") }}
                            </label>
                            <div class="mt-1 relative">
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    required
                                    :class="[
                                        'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                        safeErrors.password_confirmation
                                            ? 'border-red-300'
                                            : 'border-gray-300',
                                    ]"
                                    :placeholder="
                                        t('passwordConfirmationPlaceholder')
                                    "
                                />
                                <div
                                    v-if="safeErrors.password_confirmation"
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
                                v-if="safeErrors.password_confirmation"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ safeErrors.password_confirmation }}
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 disabled:opacity-50"
                        >
                            {{ t("signUp") }}
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    {{ t("hasAccount") }}
                    <Link
                        :href="safeRoute('login', '/login')"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        {{ t("signIn") }}
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onErrorCaptured } from "vue";
import { useForm, Link } from "@inertiajs/vue3";

const currentLanguage = ref(localStorage.getItem("language") || "en");

// Translation object
const translations = {
    en: {
        languageLabel: "English",
        registerTitle: "Create your account",
        registerSubtitle: "Fill in the details below to register",
        name: "Name",
        namePlaceholder: "Enter your name",
        email: "Email address",
        emailPlaceholder: "Enter your email",
        password: "Password",
        passwordPlaceholder: "Enter your password",
        passwordConfirmation: "Confirm Password",
        passwordConfirmationPlaceholder: "Confirm your password",
        signUp: "Sign up",
        hasAccount: "Already have an account?",
        signIn: "Sign in",
    },
    bn: {
        languageLabel: "বাংলা",
        registerTitle: "আপনার অ্যাকাউন্ট তৈরি করুন",
        registerSubtitle: "নিবন্ধন করতে নীচের বিবরণ পূরণ করুন",
        name: "নাম",
        namePlaceholder: "আপনার নাম লিখুন",
        email: "ইমেল ঠিকানা",
        emailPlaceholder: "আপনার ইমেল লিখুন",
        password: "পাসওয়ার্ড",
        passwordPlaceholder: "আপনার পাসওয়ার্ড লিখুন",
        passwordConfirmation: "পাসওয়ার্ড নিশ্চিত করুন",
        passwordConfirmationPlaceholder: "আপনার পাসওয়ার্ড নিশ্চিত করুন",
        signUp: "সাইন আপ",
        hasAccount: "ইতিমধ্যে একটি অ্যাকাউন্ট আছে?",
        signIn: "সাইন ইন",
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
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

// Safe error access - CRITICAL FIX for undefined errors
const safeErrors = computed(() => {
    try {
        return {
            name: form.errors?.name || null,
            email: form.errors?.email || null,
            password: form.errors?.password || null,
            password_confirmation: form.errors?.password_confirmation || null,
            general: form.errors?.general || null,
        };
    } catch (error) {
        console.warn('Error accessing form errors:', error);
        return { 
            name: null, 
            email: null, 
            password: null, 
            password_confirmation: null, 
            general: null 
        };
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
        form.post(safeRoute("register", "/register"), {
            onSuccess: () => form.reset("password", "password_confirmation"),
            onError: (errors) => {
                console.log('Registration errors:', errors);
            }
        });
    } catch (error) {
        console.error('Registration submission error:', error);
    }
};

// Error boundary
onErrorCaptured((error, instance, info) => {
    console.error('Register component error:', error, info);
    return false; // Prevent error from propagating
});
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

.bangla-font h2,
.bangla-font p,
.bangla-font label,
.bangla-font input::placeholder,
.bangla-font button,
.bangla-font a {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

.bg-gradient-to-br {
    background-image: linear-gradient(
        to bottom right,
        var(--tw-gradient-stops)
    );
}

.from-gray-50 {
    --tw-gradient-from: #f9fafb;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(249, 250, 251, 0));
}

.via-white {
    --tw-gradient-stops: var(--tw-gradient-from), #ffffff,
        var(--tw-gradient-to, rgba(255, 255, 255, 0));
}

.to-gray-50 {
    --tw-gradient-to: #f9fafb;
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

.from-indigo-100 {
    --tw-gradient-from: #e0e7ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(224, 231, 255, 0));
}

.to-purple-100 {
    --tw-gradient-to: #ede9fe;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>