<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 max-w-7xl mx-auto"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <!-- Language Toggle -->
        <div class="flex justify-end space-x-2 mb-4">
            <button
                @click="changeLanguage('en')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-colors',
                    currentLanguage === 'en'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ getTranslationLabel("languageLabel", "en") }}
            </button>
            <button
                @click="changeLanguage('bn')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-colors',
                    currentLanguage === 'bn'
                        ? 'bg-indigo-600 text-white'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ getTranslationLabel("languageLabel", "bn") }}
            </button>
        </div>

        <!-- Title -->
        <div
            class="flex flex-col lg:flex-row lg:justify-between items-start lg:items-center mb-8 border-b border-gray-200 pb-4 gap-4"
        >
            <h1
                class="text-2xl lg:text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 lg:w-8 lg:h-8 text-indigo-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h18v6H3V3zm0 8h18v10H3V11zm6 4h6"
                        />
                    </svg>
                </div>
                {{ getTranslation("purchaseReport") }}
            </h1>
        </div>

        <!-- Total Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-sm border border-indigo-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 7h10v4H7zM3 3h18v6H3V3zm0 8h18v10H3V11z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-700">
                            {{ getTranslation("totalPurchases") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-indigo-900">
                            {{ toBengaliNumber(totalPurchases) }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl shadow-sm border border-orange-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-orange-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-orange-700">
                            {{ getTranslation("totalCases") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-orange-900">
                            {{ toBengaliNumber(totalCases) }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm border border-purple-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-purple-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-700">
                            {{ getTranslation("totalBottles") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-purple-900">
                            {{ toBengaliNumber(totalBottles) }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-green-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
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
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-700">
                            {{ getTranslation("totalAmount") }}
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-green-900">
                            ৳{{ toBengaliNumber(totalAmount) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Field -->
        <div class="flex justify-end mb-4">
            <div class="relative w-full sm:w-80">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
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
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="getTranslation('searchPurchases')"
                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                />
            </div>
        </div>

        <!-- Purchase Table -->
        <div class="bg-white rounded-xl shadow-sm p-3 lg:p-6">
            <div
                v-if="!filteredPurchases.length"
                class="text-center text-gray-500 py-3 text-xs"
            >
                {{ getTranslation("noPurchases") }}
            </div>
            <div v-else class="w-full overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 table-fixed table-responsive">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6"
                            >
                                {{ getTranslation("supplier") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6"
                            >
                                {{ getTranslation("product") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6"
                            >
                                {{ getTranslation("variant") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6"
                            >
                                {{ getTranslation("totalBottles") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6"
                            >
                                {{ getTranslation("totalCases") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6"
                            >
                                {{ getTranslation("totalAmount") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 hidden md:table-cell"
                            >
                                {{ getTranslation("purchaseDate") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template
                            v-for="(purchase, index) in filteredPurchases"
                            :key="index"
                        >
                            <tr
                                class="hover:bg-gray-50 transition-colors cursor-pointer"
                                @click="toggleRow(index)"
                            >
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm font-medium text-gray-900 w-1/6"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            :class="[
                                                'w-3 h-3 lg:w-4 lg:h-4 mr-1 lg:mr-2 transition-transform flex-shrink-0',
                                                expandedRows[index]
                                                    ? 'rotate-90'
                                                    : '',
                                            ]"
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
                                        <span
                                            class="break-all max-w-24"
                                            :title="
                                                purchase.supplier_name || '-'
                                            "
                                        >
                                            {{ purchase.supplier_name || "-" }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/6"
                                >
                                    <span
                                        class="break-all max-w-24"
                                        :title="purchase.product_name || '-'"
                                    >
                                        {{ purchase.product_name || "-" }}
                                    </span>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/6"
                                >
                                    <span
                                        class="break-all max-w-24"
                                        :title="purchase.variant || 'N/A'"
                                    >
                                        {{ purchase.variant || "N/A" }}
                                    </span>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/6"
                                >
                                    <div class="text-right font-medium">
                                        {{
                                            toBengaliNumber(
                                                purchase.total_bottles || 0
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/6"
                                >
                                    <div class="text-right font-medium">
                                        {{
                                            toBengaliNumber(
                                                purchase.total_cases || 0
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/6"
                                >
                                    <div class="text-right font-medium">
                                        ৳{{
                                            toBengaliNumber(
                                                Number(
                                                    purchase.total_value || 0
                                                ).toFixed(2)
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/6 hidden md:table-cell"
                                >
                                    {{
                                        purchase.purchase_date
                                            ? toBengaliNumber(
                                                  formatDate(
                                                      purchase.purchase_date
                                                  )
                                              )
                                            : "-"
                                    }}
                                </td>
                            </tr>
                            <tr
                                v-if="expandedRows[index]"
                                class="bg-gradient-to-r from-gray-50 to-gray-100 animate-slide-down"
                            >
                                <td :colspan="7" class="px-2 lg:px-6 py-6">
                                    <div class="ml-2 lg:ml-6">
                                        <!-- Mobile view for details -->
                                        <div
                                            class="lg:hidden mb-6 p-4 bg-white rounded-lg shadow-sm border-l-4 border-indigo-500"
                                        >
                                            <h4
                                                class="font-semibold text-gray-800 mb-3 flex items-center"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-2 text-indigo-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                {{
                                                    getTranslation(
                                                        "purchaseDetails"
                                                    )
                                                }}
                                            </h4>
                                            <div
                                                class="grid grid-cols-2 gap-3 text-sm"
                                            >
                                                <div class="space-y-2">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "variant"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                purchase.variant ||
                                                                "N/A"
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "purchasedCases"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    purchase.purchased_cases ||
                                                                        0
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "casesFromFree"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    purchase.cases_from_free ||
                                                                        0
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "totalCases"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    purchase.total_cases ||
                                                                        0
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "purchasedBottles"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    purchase.quantity ||
                                                                        0
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "freeBottles"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    purchase.free_bottles ||
                                                                        0
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "extraFreeBottles"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    purchase.extra_free_bottles ||
                                                                        0
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "unitPrice"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >৳{{
                                                                toBengaliNumber(
                                                                    Number(
                                                                        purchase.unit_price ||
                                                                            0
                                                                    ).toFixed(2)
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="md:hidden flex flex-col"
                                                    >
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "purchaseDate"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                purchase.purchase_date
                                                                    ? toBengaliNumber(
                                                                          formatDate(
                                                                              purchase.purchase_date
                                                                          )
                                                                      )
                                                                    : "-"
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Desktop Details Table -->
                                        <div
                                            class="hidden lg:block overflow-hidden rounded-xl border border-gray-200 shadow-sm"
                                        >
                                            <table
                                                class="w-full table-fixed bg-white"
                                            >
                                                <thead
                                                    class="bg-gradient-to-r from-indigo-50 to-purple-50"
                                                >
                                                    <tr>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-2 text-indigo-600"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "variant"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center justify-center"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-1 text-indigo-600"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "purchasedCases"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center justify-center"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-1 text-indigo-600"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "casesFromFree"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center justify-center"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-1 text-indigo-600"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "totalCases"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center justify-center"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-1 text-indigo-600"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "purchasedBottles"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center justify-center"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-1 text-indigo-600"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "freeBottles"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center justify-center"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-1 text-indigo-600"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "extraFreeBottles"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/6 px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                        >
                                                            <div
                                                                class="flex items-center justify-end"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 mr-2 text-indigo-600"
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
                                                                {{
                                                                    getTranslation(
                                                                        "unitPrice"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="divide-y divide-gray-100"
                                                >
                                                    <tr
                                                        class="hover:bg-gray-50 transition-colors"
                                                    >
                                                        <td
                                                            class="px-4 py-4 border-r border-gray-200"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <div
                                                                    class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3"
                                                                >
                                                                    <span
                                                                        class="text-xs font-medium text-indigo-700"
                                                                        >1</span
                                                                    >
                                                                </div>
                                                                <p
                                                                    class="font-semibold text-gray-900"
                                                                    :title="
                                                                        purchase.variant ||
                                                                        'N/A'
                                                                    "
                                                                >
                                                                    {{
                                                                        purchase.variant ||
                                                                        "N/A"
                                                                    }}
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-center border-r border-gray-200"
                                                        >
                                                            <span
                                                                class="inline-flex items-center justify-center w-12 h-8 bg-indigo-100 text-indigo-800 rounded-lg font-bold text-sm"
                                                            >
                                                                {{
                                                                    toBengaliNumber(
                                                                        purchase.purchased_cases ||
                                                                            0
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-center border-r border-gray-200"
                                                        >
                                                            <span
                                                                class="inline-flex items-center justify-center w-12 h-8 bg-indigo-100 text-indigo-800 rounded-lg font-bold text-sm"
                                                            >
                                                                {{
                                                                    toBengaliNumber(
                                                                        purchase.cases_from_free ||
                                                                            0
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-center border-r border-gray-200"
                                                        >
                                                            <span
                                                                class="inline-flex items-center justify-center w-12 h-8 bg-indigo-100 text-indigo-800 rounded-lg font-bold text-sm"
                                                            >
                                                                {{
                                                                    toBengaliNumber(
                                                                        purchase.total_cases ||
                                                                            0
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-center border-r border-gray-200"
                                                        >
                                                            <span
                                                                class="inline-flex items-center justify-center w-12 h-8 bg-indigo-100 text-indigo-800 rounded-lg font-bold text-sm"
                                                            >
                                                                {{
                                                                    toBengaliNumber(
                                                                        purchase.quantity ||
                                                                            0
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-center border-r border-gray-200"
                                                        >
                                                            <span
                                                                class="inline-flex items-center justify-center w-12 h-8 bg-indigo-100 text-indigo-800 rounded-lg font-bold text-sm"
                                                            >
                                                                {{
                                                                    toBengaliNumber(
                                                                        purchase.free_bottles ||
                                                                            0
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-center border-r border-gray-200"
                                                        >
                                                            <span
                                                                class="inline-flex items-center justify-center w-12 h-8 bg-indigo-100 text-indigo-800 rounded-lg font-bold text-sm"
                                                            >
                                                                {{
                                                                    toBengaliNumber(
                                                                        purchase.extra_free_bottles ||
                                                                            0
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-right"
                                                        >
                                                            <span
                                                                class="font-bold text-lg text-green-600"
                                                            >
                                                                ৳{{
                                                                    toBengaliNumber(
                                                                        Number(
                                                                            purchase.unit_price ||
                                                                                0
                                                                        ).toFixed(
                                                                            2
                                                                        )
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface Purchase {
    supplier_name: string;
    product_name: string;
    variant: string;
    bottles_per_case: number;
    quantity: number;
    free_bottles: number;
    extra_free_bottles: number;
    total_bottles: number;
    purchased_cases: number;
    cases_from_free: number;
    unit_price: number | string;
    total_value: number;
    purchase_date: string;
    total_cases?: number;
}

const props = defineProps<{
    purchaseHistory: Purchase[];
    products: { id: number; name: string }[];
    suppliers: { id: number; company_name: string }[];
}>();

defineOptions({
    layout: Layout,
});

const translations = {
    en: {
        languageLabel: "English",
        purchaseReport: "Lifting Report",
        supplier: "Supplier",
        product: "Product",
        variant: "Variant",
        boxes: "Boxes",
        totalBottles: "Total Bottles",
        totalCases: "Lifted Products",
        purchasedBottles: "Purchased Bottles",
        freeBottles: "Free Bottles",
        extraFreeBottles: "Extra Free Bottles",
        purchasedCases: "Purchased Cases",
        casesFromFree: "Cases from Free",
        unitPrice: "Unit Price",
        totalAmount: "Total Amount",
        purchaseDate: "Purchase Date",
        totalPurchases: "Total Lifting",
        searchPurchases: "Search with Supplier or Product",
        noPurchases: "No lifting found",
        purchaseDetails: "Lifting Details",
    },
    bn: {
        languageLabel: "বাংলা",
        purchaseReport: "লিফটিং রিপোর্ট",
        supplier: "সরবরাহকারী",
        product: "পণ্য",
        variant: "ভেরিয়েন্ট",
        boxes: "বক্স",
        totalBottles: "মোট বোতল",
        totalCases: "মোট কেস",
        purchasedBottles: "ক্রয়কৃত বোতল",
        freeBottles: "বিনামূল্যে বোতল",
        extraFreeBottles: "অতিরিক্ত বিনামূল্যে বোতল",
        purchasedCases: "ক্রয়কৃত কেস",
        casesFromFree: "বিনামূল্যে কেস",
        unitPrice: "একক মূল্য",
        totalAmount: "মোট পরিমাণ",
        purchaseDate: "ক্রয়ের তারিখ",
        totalPurchases: "মোট লিফটিং",
        searchPurchases: "সরবরাহকারী বা পণ্য দিয়ে খুঁজুন",
        noPurchases: "কোন লিফটিং পাওয়া যায়নি",
        purchaseDetails: "লিফটিং বিবরণ",
    },
};

const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref("");
const expandedRows = ref({});

const totalPurchases = computed(() => props.purchaseHistory.length);
const totalAmount = computed(() =>
    props.purchaseHistory.reduce(
        (sum, purchase) => sum + Number(purchase.total_value),
        0
    )
);
const totalBottles = computed(() =>
    props.purchaseHistory.reduce(
        (sum, purchase) => sum + Number(purchase.total_bottles),
        0
    )
);
const totalCases = computed(() =>
    props.purchaseHistory.reduce(
        (sum, purchase) =>
            sum + Number(purchase.purchased_cases + purchase.cases_from_free),
        0
    )
);

const filteredPurchases = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return props.purchaseHistory
        .filter(
            (purchase) =>
                (purchase.total_bottles > 0 || purchase.total_value > 0) &&
                (purchase.supplier_name?.toLowerCase().includes(query) ||
                    purchase.product_name?.toLowerCase().includes(query) ||
                    purchase.variant?.toLowerCase().includes(query))
        )
        .map((purchase) => ({
            ...purchase,
            total_cases: purchase.purchased_cases + purchase.cases_from_free,
        }));
});

function getTranslation(key: string) {
    return translations[currentLanguage.value]?.[key] || key;
}

function getTranslationLabel(key: string, lang: string) {
    return translations[lang]?.[key] || key;
}

function toBengaliNumber(num: number | string) {
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string") return num;
    if (currentLanguage.value !== "bn") return num;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
}

function changeLanguage(lang: string) {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    // Set document language for proper font rendering
    document.documentElement.lang = lang;
}

function formatDate(dateString: string) {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function toggleRow(index: number) {
    expandedRows.value[index] = !expandedRows.value[index];
}
</script>

<style scoped>
@import url("https://fonts.maateen.me/kalpurush/font.css");

.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
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

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

.rotate-90 {
    transform: rotate(90deg);
}

.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.max-w-24 {
    max-width: 6rem;
}

table {
    table-layout: fixed;
}

th,
td {
    width: 16.67%;
}

@media (max-width: 640px) {
    .table-responsive {
        font-size: 0.75rem;
    }

    th,
    td {
        padding: 0.25rem 0.5rem;
    }
}
</style>
