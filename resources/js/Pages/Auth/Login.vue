```vue
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
                        id="loginBgGradient"
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
                    fill="url(#loginBgGradient)"
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

                <!-- Login Form Header -->
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
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                />
                            </svg>
                        </div>
                        {{ t("loginTitle") }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ t("loginSubtitle") }}
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
                                    :class="[
                                        'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                        form.errors.email
                                            ? 'border-red-300'
                                            : 'border-gray-300',
                                    ]"
                                    :placeholder="t('emailPlaceholder')"
                                />
                                <div
                                    v-if="form.errors.email"
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
                                v-if="form.errors.email"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.email }}
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
                                    autocomplete="current-password"
                                    required
                                    :class="[
                                        'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                        form.errors.password
                                            ? 'border-red-300'
                                            : 'border-gray-300',
                                    ]"
                                    :placeholder="t('passwordPlaceholder')"
                                />
                                <div
                                    v-if="form.errors.password"
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
                                v-if="form.errors.password"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.password }}
                            </p>
                        </div>
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                            <label
                                for="remember-me"
                                class="ml-2 block text-sm text-gray-900"
                            >
                                {{ t("rememberMe") }}
                            </label>
                        </div>

                        <div class="text-sm">
                            <Link
                                :href="route('password.request')"
                                class="font-medium text-indigo-600 hover:text-indigo-500"
                            >
                                {{ t("forgotPassword") }}
                            </Link>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 disabled:opacity-50"
                        >
                            {{ t("signIn") }}
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    {{ t("noAccount") }}
                    <Link
                        :href="route('register')"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        {{ t("signUp") }}
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, Link } from "@inertiajs/vue3";

const currentLanguage = ref(localStorage.getItem("language") || "en");

// Translation object
const translations = {
    en: {
        languageLabel: "English",
        loginTitle: "Sign in to your account",
        loginSubtitle: "Enter your email and password to access the dashboard",
        email: "Email address",
        emailPlaceholder: "Enter your email",
        password: "Password",
        passwordPlaceholder: "Enter your password",
        rememberMe: "Remember me",
        forgotPassword: "Forgot your password?",
        signIn: "Sign in",
        noAccount: "Don’t have an account?",
        signUp: "Sign up",
    },
    bn: {
        languageLabel: "বাংলা",
        loginTitle: "আপনার অ্যাকাউন্টে সাইন ইন করুন",
        loginSubtitle:
            "ড্যাশবোর্ড অ্যাক্সেস করতে আপনার ইমেল এবং পাসওয়ার্ড লিখুন",
        email: "ইমেল ঠিকানা",
        emailPlaceholder: "আপনার ইমেল লিখুন",
        password: "পাসওয়ার্ড",
        passwordPlaceholder: "আপনার পাসওয়ার্ড লিখুন",
        rememberMe: "আমাকে মনে রাখুন",
        forgotPassword: "আপনার পাসওয়ার্ড ভুলে গেছেন?",
        signIn: "সাইন ইন",
        noAccount: "অ্যাকাউন্ট নেই?",
        signUp: "সাইন আপ করুন",
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

// Form handling
const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
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
```
