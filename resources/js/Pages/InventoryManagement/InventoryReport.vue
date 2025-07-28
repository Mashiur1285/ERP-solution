<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50"
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
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                        />
                    </svg>
                </div>
                {{ getTranslation("inventoryReport") }}
            </h1>
        </div>

        <!-- Search and Total Metrics -->
        <div class="space-y-6 mb-6">
            <!-- Search Field -->
            <div class="flex justify-end">
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
                            ></path>
                        </svg>
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        :placeholder="getTranslation('searchProducts')"
                        class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                    />
                </div>
            </div>

            <!-- Total Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
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
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-indigo-700">
                                {{ getTranslation("totalProducts") }}
                            </p>
                            <p
                                class="text-2xl lg:text-3xl font-bold text-indigo-900"
                            >
                                {{ toBengaliNumber(totalProducts) }}
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
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-700">
                                {{ getTranslation("totalQuantity") }}
                            </p>
                            <p
                                class="text-2xl lg:text-3xl font-bold text-green-900"
                            >
                                {{ toBengaliNumber(totalQuantity) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm border border-blue-200"
                >
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
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
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-700">
                                {{ getTranslation("totalBoxes") }}
                            </p>
                            <p
                                class="text-2xl lg:text-3xl font-bold text-blue-900"
                            >
                                {{ toBengaliNumber(totalBoxes) }}
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
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-purple-700">
                                {{ getTranslation("totalPurchaseValue") }}
                            </p>
                            <p
                                class="text-2xl lg:text-3xl font-bold text-purple-900"
                            >
                                ৳{{ toBengaliNumber(totalPurchaseValue) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="bg-white rounded-xl shadow-sm p-3 lg:p-6">
            <div class="w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5"
                            >
                                {{ getTranslation("productName") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5 hidden sm:table-cell"
                            >
                                {{ getTranslation("variantCount") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5"
                            >
                                {{ getTranslation("quantity") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5"
                            >
                                {{ getTranslation("boxes") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5 hidden md:table-cell"
                            >
                                {{ getTranslation("unitPrice") }}
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5"
                            >
                                {{ getTranslation("totalValue") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template
                            v-for="(item, index) in filteredInventory"
                            :key="index"
                        >
                            <tr
                                class="hover:bg-gray-50 transition-colors cursor-pointer"
                                @click="toggleVariants(index)"
                            >
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm font-medium text-gray-900 w-1/5"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            :class="[
                                                'w-3 h-3 lg:w-4 lg:h-4 mr-1 lg:mr-2 transition-transform flex-shrink-0',
                                                expandedVariants[index]
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
                                            class="break-all"
                                            :title="item.product_name"
                                        >
                                            {{ item.product_name }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/5 hidden sm:table-cell"
                                >
                                    <div class="text-center font-medium">
                                        {{
                                            toBengaliNumber(
                                                item.variants.length
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/5"
                                >
                                    <div class="text-center font-medium">
                                        {{
                                            toBengaliNumber(item.total_quantity)
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/5"
                                >
                                    <div class="text-center font-medium">
                                        {{ toBengaliNumber(item.total_boxes) }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/5 hidden md:table-cell"
                                >
                                    <div class="text-right font-medium">
                                        ৳{{
                                            toBengaliNumber(
                                                Math.round(
                                                    item.variants[0]
                                                        ?.unit_price || 0
                                                )
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-500 w-1/5"
                                >
                                    <div class="text-right font-medium">
                                        ৳{{
                                            toBengaliNumber(
                                                Math.round(item.total_value)
                                            )
                                        }}
                                    </div>
                                </td>
                            </tr>

                            <tr
                                v-if="expandedVariants[index]"
                                class="bg-gradient-to-r from-gray-50 to-gray-100 animate-slide-down"
                            >
                                <td :colspan="6" class="px-2 lg:px-6 py-6">
                                    <div class="ml-2 lg:ml-6">
                                        <!-- Mobile view for hidden columns -->
                                        <div
                                            class="sm:hidden mb-6 p-4 bg-white rounded-lg shadow-sm border-l-4 border-indigo-500"
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
                                                    ></path>
                                                </svg>
                                                {{
                                                    getTranslation(
                                                        "productDetails"
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
                                                                    "variantCount"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    item
                                                                        .variants
                                                                        .length
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "boxes"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                toBengaliNumber(
                                                                    item.total_boxes
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <div
                                                        class="md:hidden flex flex-col"
                                                    >
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
                                                                    Math.round(
                                                                        item
                                                                            .variants[0]
                                                                            ?.unit_price ||
                                                                            0
                                                                    )
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Variants Header -->
                                        <div
                                            class="mb-4 flex items-center justify-between"
                                        >
                                            <h4
                                                class="text-lg font-semibold text-gray-800 flex items-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 mr-2 text-indigo-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                                    ></path>
                                                </svg>
                                                {{
                                                    getTranslation(
                                                        "productVariants"
                                                    )
                                                }}
                                                <span
                                                    class="ml-2 px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium"
                                                >
                                                    {{
                                                        toBengaliNumber(
                                                            item.variants.length
                                                        )
                                                    }}
                                                    {{
                                                        getTranslation(
                                                            "variants"
                                                        )
                                                    }}
                                                </span>
                                            </h4>
                                        </div>

                                        <!-- Variants Cards for Mobile -->
                                        <div class="lg:hidden space-y-3">
                                            <div
                                                v-for="variant in item.variants"
                                                :key="variant.variant"
                                                class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
                                            >
                                                <div
                                                    class="flex justify-between items-start mb-3"
                                                >
                                                    <div class="flex-1 min-w-0">
                                                        <h5
                                                            class="font-semibold text-gray-900 truncate"
                                                            :title="
                                                                variant.variant
                                                            "
                                                        >
                                                            {{
                                                                variant.variant
                                                            }}
                                                        </h5>
                                                        <p
                                                            class="text-sm text-gray-600 mt-1"
                                                        >
                                                            {{
                                                                getTranslation(
                                                                    "unitPrice"
                                                                )
                                                            }}: ৳{{
                                                                toBengaliNumber(
                                                                    Math.round(
                                                                        variant.unit_price
                                                                    )
                                                                )
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm text-gray-600 mt-1"
                                                        >
                                                            {{
                                                                getTranslation(
                                                                    "boxes"
                                                                )
                                                            }}:
                                                            {{
                                                                toBengaliNumber(
                                                                    variant.boxes
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="ml-3 text-right"
                                                    >
                                                        <div
                                                            class="flex items-center justify-end mb-1"
                                                        >
                                                            <svg
                                                                class="w-4 h-4 mr-1 text-gray-400"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                                                ></path>
                                                            </svg>
                                                            <span
                                                                class="text-sm font-medium text-gray-700"
                                                                >{{
                                                                    toBengaliNumber(
                                                                        variant.quantity
                                                                    )
                                                                }}</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="flex justify-center items-center pt-3 border-t border-gray-100"
                                                >
                                                    <div class="text-center">
                                                        <p
                                                            class="text-xs text-gray-500 font-medium"
                                                        >
                                                            {{
                                                                getTranslation(
                                                                    "totalValue"
                                                                )
                                                            }}
                                                        </p>
                                                        <p
                                                            class="text-sm font-bold text-gray-900"
                                                        >
                                                            ৳{{
                                                                toBengaliNumber(
                                                                    Math.round(
                                                                        variant.total_value
                                                                    )
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Variants Table for Desktop -->
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
                                                            class="w-1/5 px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
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
                                                                    ></path>
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "variant"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/5 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
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
                                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                                                    ></path>
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "quantity"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/5 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
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
                                                                    ></path>
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "boxes"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/5 px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
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
                                                                    ></path>
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "unitPrice"
                                                                    )
                                                                }}
                                                            </div>
                                                        </th>
                                                        <th
                                                            class="w-1/5 px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider"
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
                                                                    ></path>
                                                                </svg>
                                                                {{
                                                                    getTranslation(
                                                                        "totalValue"
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
                                                        v-for="(
                                                            variant,
                                                            variantIndex
                                                        ) in item.variants"
                                                        :key="variant.variant"
                                                        class="hover:bg-gray-50 transition-colors"
                                                        :class="
                                                            variantIndex % 2 ===
                                                            0
                                                                ? 'bg-white'
                                                                : 'bg-gray-25'
                                                        "
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
                                                                        >{{
                                                                            toBengaliNumber(
                                                                                variantIndex +
                                                                                    1
                                                                            )
                                                                        }}</span
                                                                    >
                                                                </div>
                                                                <div>
                                                                    <p
                                                                        class="font-semibold text-gray-900"
                                                                        :title="
                                                                            variant.variant
                                                                        "
                                                                    >
                                                                        {{
                                                                            variant.variant
                                                                        }}
                                                                    </p>
                                                                </div>
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
                                                                        variant.quantity
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
                                                                        variant.boxes
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="px-4 py-4 text-right border-r border-gray-200"
                                                        >
                                                            <span
                                                                class="font-bold text-gray-900 text-lg"
                                                            >
                                                                ৳{{
                                                                    toBengaliNumber(
                                                                        Math.round(
                                                                            variant.unit_price
                                                                        )
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
                                                                        Math.round(
                                                                            variant.total_value
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

<script setup>
import { defineProps, ref, computed } from "vue";
import Layout from "@/Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps({
    inventoryStock: Array,
});

const translations = {
    en: {
        languageLabel: "English",
        inventoryReport: "Inventory Report",
        productName: "Product Name",
        variant: "Variant",
        variantCount: "Variant Count",
        quantity: "Quantity",
        boxes: "Boxes",
        unitPrice: "Unit Price",
        totalValue: "Total Value",
        totalProducts: "Total Products",
        totalQuantity: "Total Quantity",
        totalBoxes: "Total Boxes",
        totalPurchaseValue: "Total Purchase Value",
        searchProducts: "Search products...",
        productDetails: "Product Details",
        productVariants: "Product Variants",
        variants: "variants",
    },
    bn: {
        languageLabel: "বাংলা",
        inventoryReport: "ইনভেন্টরি রিপোর্ট",
        productName: "পণ্যের নাম",
        variant: "ভেরিয়েন্ট",
        variantCount: "ভেরিয়েন্ট সংখ্যা",
        quantity: "পরিমাণ",
        boxes: "বক্স",
        unitPrice: "ইউনিট মূল্য",
        totalValue: "মোট মূল্য",
        totalProducts: "মোট পণ্য",
        totalQuantity: "মোট পরিমাণ",
        totalBoxes: "মোট বক্স",
        totalPurchaseValue: "মোট ক্রয় মূল্য",
        searchProducts: "পণ্য অনুসন্ধান করুন...",
        productDetails: "পণ্য বিবরণ",
        productVariants: "পণ্য ভেরিয়েন্ট",
        variants: "ভেরিয়েন্ট",
    },
};

const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref("");
const expandedVariants = ref({});

const totalProducts = computed(() => props.inventoryStock.length);
const totalQuantity = computed(() =>
    props.inventoryStock.reduce((sum, item) => sum + item.total_quantity, 0)
);
const totalBoxes = computed(() =>
    props.inventoryStock.reduce((sum, item) => sum + (item.total_boxes || 0), 0)
);
const totalPurchaseValue = computed(() =>
    props.inventoryStock.reduce((sum, item) => sum + item.total_value, 0)
);

const filteredInventory = computed(() => {
    if (!searchQuery.value) return props.inventoryStock;
    const query = searchQuery.value.toLowerCase();
    return props.inventoryStock.filter((item) =>
        item.product_name.toLowerCase().includes(query)
    );
});

function getTranslation(key) {
    return translations[currentLanguage.value]?.[key] || key;
}

function getTranslationLabel(key, lang) {
    return translations[lang]?.[key] || key;
}

function toBengaliNumber(num) {
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string") return num;
    if (currentLanguage.value !== "bn") return num;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
}

function changeLanguage(lang) {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
}

function toggleVariants(index) {
    expandedVariants.value[index] = !expandedVariants.value[index];
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;700&display=swap");

body,
html {
    font-family: "Noto Serif Bengali", Arial, sans-serif;
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

.animate-pulse-slow {
    animation: pulseSlow 2s infinite;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

.rotate-90 {
    transform: rotate(90deg);
}

/* Custom responsive utilities */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.max-w-24 {
    max-width: 6rem;
}

/* Ensure table doesn't overflow */
table {
    table-layout: fixed;
}

/* Adjust column widths for 6 columns in primary table */
th,
td {
    width: 16.67%;
}

/* Mobile optimizations */
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
