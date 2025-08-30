```vue
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
                                d="M12 11c0-1.1-.9-2-2-2H8V7h2c2.2 0 4 1.8 4 4v2c0 2.2-1.8 4-4 4H8v-2h2c1.1 0 2-.9 2-2v-2zm8 5h-2v2h-2v-2h-2v-2h2v-2h2v2h2v2z"
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
                    :href="route('login')"
                    class="font-medium text-indigo-600 hover:text-indigo-500"
                >
                    {{ t("signIn") }}
                </Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, Link, usePage } from "@inertiajs/vue3";

defineProps({
    status: String,
});

const currentLanguage = ref(localStorage.getItem("language") || "en");
const statusMessage = ref(null); // State for toast message

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

// Form handling
const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"), {
        onSuccess: () => {
            form.reset("email");
            statusMessage.value =
                translations[currentLanguage.value].statusMessage;
        },
        onError: () => {
            statusMessage.value = null;
        },
    });
};

// Watch for status prop to display toast
import { watch } from "vue";
watch(
    () => usePage().props.status,
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
.bangla-font a,
.bangla-font span {
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

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}
</style>
```
