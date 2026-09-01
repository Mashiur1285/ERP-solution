<template>
    <div
        class="relative flex min-h-screen flex-col bg-slate-950 lg:grid lg:grid-cols-2 lg:bg-white"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- One toggle for both layouts: it sits over the photo on a phone and
             over the form column on a wide screen, so the styles flip too. -->
        <div class="absolute right-4 top-4 z-30 flex gap-1 rounded-xl bg-white/10 p-1 backdrop-blur-md ring-1 ring-inset ring-white/20 sm:right-5 sm:top-5 lg:right-8 lg:top-8 lg:bg-transparent lg:p-0 lg:ring-0 lg:backdrop-blur-none">
            <button
                v-for="lang in ['en', 'bn']"
                :key="lang"
                type="button"
                @click="changeLanguage(lang)"
                :class="[
                    'rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors',
                    currentLanguage === lang
                        ? 'bg-white text-slate-900 shadow-sm lg:bg-indigo-600 lg:text-white'
                        : 'text-white/80 hover:text-white lg:text-slate-500 lg:hover:bg-slate-100 lg:hover:text-slate-700',
                ]"
            >
                {{ translations[lang].languageLabel }}
            </button>
        </div>

        <!-- ── Hero ────────────────────────────────────────────────────── -->
        <section
            class="relative h-[46vh] min-h-[300px] shrink-0 overflow-hidden lg:h-auto lg:min-h-0"
        >
            <img
                src="/images/login-hero.jpg"
                :alt="t('heroAlt')"
                class="absolute inset-0 h-full w-full object-cover object-[50%_35%]"
            />

            <!-- Dark wash so the title stays readable over any part of the photo -->
            <div
                class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/55 to-slate-900/30"
            ></div>

            <!-- Extra bottom padding on phones keeps the title clear of the sheet -->
            <div
                class="relative flex h-full flex-col justify-between p-6 pb-16 sm:p-8 sm:pb-20 lg:p-12"
            >
                <div class="flex items-center gap-2.5">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 ring-1 ring-inset ring-white/25 backdrop-blur-sm"
                    >
                        <svg
                            class="h-5 w-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>
                    </span>
                    <span class="text-sm font-semibold tracking-wide text-white/90">{{
                        t("brandShort")
                    }}</span>
                </div>

                <div>
                    <div class="mb-4 h-px w-12 bg-white/40 sm:mb-5 sm:w-16"></div>
                    <h1
                        class="max-w-md text-[1.75rem] font-bold leading-[1.15] tracking-tight text-white drop-shadow-sm sm:text-4xl lg:text-[2.75rem]"
                    >
                        {{ t("appName") }}
                    </h1>
                    <p
                        class="mt-3 hidden max-w-sm text-sm leading-relaxed text-white/70 sm:block"
                    >
                        {{ t("appTagline") }}
                    </p>
                </div>
            </div>
        </section>

        <!-- ── Form: a sheet that rides up over the photo on phones ────── -->
        <section
            class="relative z-10 -mt-7 flex flex-1 flex-col rounded-t-[1.75rem] bg-white px-5 pb-10 pt-5 shadow-[0_-10px_40px_-12px_rgba(2,6,23,0.45)] sm:px-8 lg:mt-0 lg:justify-center lg:rounded-none lg:px-12 lg:pt-10 lg:shadow-none"
        >
            <div class="mx-auto mb-7 h-1 w-10 shrink-0 rounded-full bg-slate-200 lg:hidden"></div>

            <div class="mx-auto w-full max-w-sm">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">
                    {{ t("loginTitle") }}
                </h2>
                <p class="mt-2 text-sm text-slate-500">{{ t("loginSubtitle") }}</p>

                <form class="mt-7 space-y-5 lg:mt-8" @submit.prevent="submit">
                    <!-- Login -->
                    <div>
                        <label
                            for="login"
                            class="mb-1.5 block text-sm font-medium text-slate-700"
                        >
                            {{ t("login") }}
                        </label>
                        <input
                            id="login"
                            v-model="form.login"
                            type="text"
                            autocomplete="username"
                            required
                            :placeholder="t('loginPlaceholder')"
                            :class="[
                                'block w-full rounded-xl border bg-slate-50 px-4 py-3.5 text-base text-slate-900 placeholder-slate-400 transition-colors focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 sm:text-sm',
                                form.errors.login
                                    ? 'border-red-300 focus:border-red-400'
                                    : 'border-slate-200 focus:border-indigo-500',
                            ]"
                        />
                        <p v-if="form.errors.login" class="mt-1.5 text-sm text-red-600">
                            {{ form.errors.login }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label
                            for="password"
                            class="mb-1.5 block text-sm font-medium text-slate-700"
                        >
                            {{ t("password") }}
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                :placeholder="t('passwordPlaceholder')"
                                :class="[
                                    'block w-full rounded-xl border bg-slate-50 py-3.5 pl-4 pr-12 text-base text-slate-900 placeholder-slate-400 transition-colors focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 sm:text-sm',
                                    form.errors.password
                                        ? 'border-red-300 focus:border-red-400'
                                        : 'border-slate-200 focus:border-indigo-500',
                                ]"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                :aria-label="t(showPassword ? 'hidePassword' : 'showPassword')"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition-colors hover:text-slate-600"
                            >
                                <svg
                                    v-if="showPassword"
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3.98 8.223A10.5 10.5 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.774 3.162 10.066 7.5a10.52 10.52 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.036 12.322a1 1 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178a1 1 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="mt-1.5 text-sm text-red-600">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <label class="flex cursor-pointer items-center gap-2.5 pt-0.5">
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        />
                        <span class="text-sm text-slate-600">{{ t("rememberMe") }}</span>
                    </label>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/25 transition-all active:scale-[0.99] hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <svg
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            />
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v8H4z"
                            />
                        </svg>
                        {{ form.processing ? t("signingIn") : t("signIn") }}
                    </button>
                </form>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const currentLanguage = ref(localStorage.getItem("language") || "en");
const showPassword = ref(false);

const translations = {
    en: {
        languageLabel: "English",
        brandShort: "DMS",
        appName: "Distribution Management System",
        appTagline:
            "Every lift, sale and payment tracked from warehouse to shop.",
        heroAlt: "A delivery being handed over to a shop owner",
        loginTitle: "Sign in to your account",
        loginSubtitle: "Enter your credentials to access the dashboard",
        login: "Username or Email",
        loginPlaceholder: "Enter your username or email",
        password: "Password",
        passwordPlaceholder: "Enter your password",
        showPassword: "Show password",
        hidePassword: "Hide password",
        rememberMe: "Keep me signed in",
        signIn: "Sign in",
        signingIn: "Signing in...",
    },
    bn: {
        languageLabel: "বাংলা",
        brandShort: "ডিএমএস",
        appName: "ডিস্ট্রিবিউশন ম্যানেজমেন্ট সিস্টেম",
        appTagline:
            "গুদাম থেকে দোকান — প্রতিটি লিফট, বিক্রি ও পেমেন্টের হিসাব এক জায়গায়।",
        heroAlt: "দোকানদারের হাতে পণ্য পৌঁছে দেওয়া হচ্ছে",
        loginTitle: "আপনার অ্যাকাউন্টে সাইন ইন করুন",
        loginSubtitle: "ড্যাশবোর্ডে ঢুকতে আপনার তথ্য দিন",
        login: "ইউজারনেম বা ইমেল",
        loginPlaceholder: "ইউজারনেম বা ইমেল লিখুন",
        password: "পাসওয়ার্ড",
        passwordPlaceholder: "আপনার পাসওয়ার্ড লিখুন",
        showPassword: "পাসওয়ার্ড দেখান",
        hidePassword: "পাসওয়ার্ড লুকান",
        rememberMe: "আমাকে সাইন ইন রাখুন",
        signIn: "সাইন ইন",
        signingIn: "সাইন ইন হচ্ছে...",
    },
};

const t = computed(() => (key) => translations[currentLanguage.value][key]);

const changeLanguage = (lang) => {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
};

const form = useForm({
    login: "",
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

.bangla-font,
.bangla-font h1,
.bangla-font h2,
.bangla-font p,
.bangla-font label,
.bangla-font span,
.bangla-font button,
.bangla-font input::placeholder {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}
</style>
