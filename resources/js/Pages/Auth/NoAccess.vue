<template>
    <Head title="No Access" />

    <main class="flex min-h-screen items-center justify-center bg-gray-50 px-6 py-12">
        <section class="w-full max-w-lg rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-gray-200">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-2xl text-amber-600">
                !
            </div>

            <h1 class="mt-5 text-2xl font-semibold text-gray-900">No access assigned</h1>
            <p class="mt-3 text-sm leading-6 text-gray-600">
                Your account does not have access to any module yet. Please contact an administrator to assign a role.
            </p>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="mt-6 inline-flex items-center rounded-lg bg-[#3F0E40] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#5a1d5b]"
            >
                Log out
            </Link>
        </section>
    </main>
</template>

<script setup>
import { Head, Link } from "@inertiajs/vue3";
</script>
