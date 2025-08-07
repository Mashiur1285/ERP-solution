<template>
    <div
        class="p-4 lg:p-6 space-y-6 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
    >
        <!-- Language Toggle -->
        <div class="flex justify-end space-x-2 mb-4">
            <button
                @click="changeLanguage('en')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-all duration-200',
                    currentLanguage === 'en'
                        ? 'bg-indigo-600 text-white shadow-md'
                        : 'bg-gray-200 text-gray-800 hover:bg-gray-300',
                ]"
            >
                {{ getTranslationLabel("languageLabel", "en") }}
            </button>
            <button
                @click="changeLanguage('bn')"
                :class="[
                    'px-4 py-2 rounded-md font-medium transition-all duration-200',
                    currentLanguage === 'bn'
                        ? 'bg-indigo-600 text-white shadow-md'
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
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                        />
                    </svg>
                </div>
                {{ getTranslation("salesReport") }}
            </h1>
        </div>

        <!-- Total Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-sm border border-indigo-200 hover:shadow-md transition-shadow"
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
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-700">
                            {{ getTranslation("totalSales") }}
                        </p>
                        <p
                            class="text-2xl lg:text-3xl font-bold text-indigo-900"
                        >
                            {{ toBengaliNumber(totalSales) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200 hover:shadow-md transition-shadow"
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
                        <p
                            class="text-2xl lg:text-3xl font-bold text-green-900"
                        >
                            ৳{{ toBengaliNumber(formatCurrency(totalAmount)) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-sm border border-purple-200 hover:shadow-md transition-shadow"
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
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-700">
                            {{ getTranslation("totalProfit") }}
                        </p>
                        <p
                            class="text-2xl lg:text-3xl font-bold"
                            :class="
                                totalProfit >= 0
                                    ? 'text-purple-900'
                                    : 'text-red-600'
                            "
                        >
                            ৳{{ toBengaliNumber(formatCurrency(totalProfit)) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl shadow-sm border border-orange-200 hover:shadow-md transition-shadow"
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
                            {{ getTranslation("totalItems") }}
                        </p>
                        <p
                            class="text-2xl lg:text-3xl font-bold text-orange-900"
                        >
                            {{ toBengaliNumber(totalItemsSold) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6"
        >
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-1.5 bg-indigo-100 rounded-lg mr-2">
                        <svg
                            class="w-4 h-4 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"
                            />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ getTranslation("filters") }}
                    </h2>
                </div>
                <div class="flex gap-2">
                    <button
                        @click="clearFilters"
                        class="px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200"
                    >
                        {{ getTranslation("clearFilters") }}
                    </button>
                    <button
                        @click="applyFilters"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200"
                    >
                        <span class="flex items-center">
                            <svg
                                class="w-4 h-4 mr-1.5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"
                                />
                            </svg>
                            {{ getTranslation("applyFilters") }}
                        </span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <div>
                    <label
                        for="start_date"
                        class="block text-xs font-medium text-gray-600 mb-1"
                    >
                        {{ getTranslation("startDate") }}
                    </label>
                    <input
                        v-model="filters.start_date"
                        id="start_date"
                        type="date"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    />
                </div>

                <div>
                    <label
                        for="end_date"
                        class="block text-xs font-medium text-gray-600 mb-1"
                    >
                        {{ getTranslation("endDate") }}
                    </label>
                    <input
                        v-model="filters.end_date"
                        id="end_date"
                        type="date"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    />
                </div>

                <div>
                    <label
                        for="shop_id"
                        class="block text-xs font-medium text-gray-600 mb-1"
                    >
                        {{ getTranslation("shop") }}
                    </label>
                    <select
                        v-model="filters.shop_id"
                        id="shop_id"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        <option value="">
                            {{ getTranslation("allShops") }}
                        </option>
                        <option
                            v-for="shop in shops"
                            :key="shop.id"
                            :value="shop.id"
                        >
                            {{ shop.shop_name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label
                        for="product_id"
                        class="block text-xs font-medium text-gray-600 mb-1"
                    >
                        {{ getTranslation("product") }}
                    </label>
                    <select
                        v-model="filters.product_id"
                        id="product_id"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        <option value="">
                            {{ getTranslation("allProducts") }}
                        </option>
                        <option
                            v-for="product in products"
                            :key="product.id"
                            :value="product.id"
                        >
                            {{ product.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label
                        for="supplier_id"
                        class="block text-xs font-medium text-gray-600 mb-1"
                    >
                        {{ getTranslation("supplier") }}
                    </label>
                    <select
                        v-model="filters.supplier_id"
                        id="supplier_id"
                        class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        <option value="">
                            {{ getTranslation("allSuppliers") }}
                        </option>
                        <option
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            :value="supplier.id"
                        >
                            {{ supplier.company_name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

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
                        />
                    </svg>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="getTranslation('searchSales')"
                    class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-300 text-sm font-medium hover:border-indigo-300"
                />
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
            <div
                class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"
            ></div>
        </div>

        <!-- Empty State -->
        <div v-else-if="filteredSales.length === 0" class="text-center py-12">
            <svg
                class="mx-auto h-12 w-12 text-gray-400"
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
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                {{ getTranslation("noSales") }}
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                {{ getTranslation("noSalesDescription") }}
            </p>
        </div>

        <!-- Sales Table -->
        <div v-else class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-3 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("invoiceNumber") }}
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell"
                            >
                                {{ getTranslation("shop") }}
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden xl:table-cell"
                            >
                                {{ getTranslation("supplier") }}
                            </th>
                            <th
                                class="px-3 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("totalAmount") }}
                            </th>
                            <th
                                class="px-3 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("totalProfit") }}
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell"
                            >
                                {{ getTranslation("saleDate") }}
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell"
                            >
                                {{ getTranslation("status") }}
                            </th>
                            <th
                                class="px-3 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ getTranslation("actions") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template v-for="sale in filteredSales" :key="sale.id">
                            <tr
                                class="hover:bg-gray-50 transition-colors cursor-pointer"
                                @click="toggleItems(sale.id)"
                            >
                                <td
                                    class="px-3 py-4 text-sm font-medium text-gray-900"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            :class="[
                                                'w-4 h-4 mr-2 transition-transform flex-shrink-0',
                                                expandedSale === sale.id
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
                                            :title="sale.invoice_number"
                                            >{{ sale.invoice_number }}</span
                                        >
                                    </div>
                                </td>
                                <td
                                    class="px-3 py-4 text-sm text-gray-500 hidden lg:table-cell"
                                >
                                    <span
                                        class="truncate max-w-32"
                                        :title="sale.shop_name"
                                        >{{ sale.shop_name }}</span
                                    >
                                </td>
                                <td
                                    class="px-3 py-4 text-sm text-gray-500 hidden xl:table-cell"
                                >
                                    <span
                                        class="truncate max-w-32"
                                        :title="sale.supplier_name"
                                        >{{ sale.supplier_name }}</span
                                    >
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    <div class="text-right font-medium">
                                        ৳{{
                                            toBengaliNumber(
                                                formatCurrency(
                                                    sale.total_amount
                                                )
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-3 py-4 text-sm"
                                    :class="
                                        sale.total_profit >= 0
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    <div class="text-right font-medium">
                                        ৳{{
                                            toBengaliNumber(
                                                formatCurrency(
                                                    sale.total_profit
                                                )
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-3 py-4 text-sm text-gray-500 hidden md:table-cell"
                                >
                                    {{ formatDate(sale.sale_date) }}
                                </td>
                                <td
                                    class="px-3 py-4 text-sm hidden sm:table-cell"
                                >
                                    <span
                                        :class="{
                                            'bg-yellow-100 text-yellow-800':
                                                sale.status === 'pending',
                                            'bg-blue-100 text-blue-800':
                                                sale.status === 'in_progress',
                                            'bg-green-100 text-green-800':
                                                sale.status === 'completed',
                                        }"
                                        class="px-2 py-1 rounded-full text-xs font-medium capitalize"
                                    >
                                        {{ getTranslation(sale.status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm">
                                    <div
                                        class="flex flex-col sm:flex-row gap-1"
                                    >
                                        <button
                                            @click.stop="toggleItems(sale.id)"
                                            class="px-2 py-1 bg-indigo-100 text-indigo-600 rounded text-xs hover:bg-indigo-200 transition duration-200 whitespace-nowrap"
                                        >
                                            {{
                                                expandedSale === sale.id
                                                    ? getTranslation("hide")
                                                    : `${getTranslation(
                                                          "show"
                                                      )} (${toBengaliNumber(
                                                          sale.items?.length ||
                                                              0
                                                      )})`
                                            }}
                                        </button>
                                        <button
                                            @click.stop="viewCashMemo(sale.id)"
                                            class="px-2 py-1 bg-green-100 text-green-600 rounded text-xs hover:bg-green-200 transition duration-200 whitespace-nowrap"
                                        >
                                            {{ getTranslation("view") }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Expanded Row -->
                            <tr
                                v-if="expandedSale === sale.id"
                                class="bg-gradient-to-r from-gray-50 to-gray-100 animate-slide-down"
                            >
                                <td :colspan="8" class="px-6 py-6">
                                    <div class="ml-6">
                                        <!-- Mobile Sale Details -->
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
                                                        "saleDetails"
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
                                                                    "shop"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                sale.shop_name
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="xl:hidden flex flex-col"
                                                    >
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "supplier"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                sale.supplier_name
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
                                                                    "saleDate"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            class="text-gray-800 font-medium"
                                                            >{{
                                                                formatDate(
                                                                    sale.sale_date
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="sm:hidden flex flex-col"
                                                    >
                                                        <span
                                                            class="text-xs text-gray-500 font-medium"
                                                            >{{
                                                                getTranslation(
                                                                    "status"
                                                                )
                                                            }}</span
                                                        >
                                                        <span
                                                            :class="{
                                                                'bg-yellow-100 text-yellow-800':
                                                                    sale.status ===
                                                                    'pending',
                                                                'bg-blue-100 text-blue-800':
                                                                    sale.status ===
                                                                    'in_progress',
                                                                'bg-green-100 text-green-800':
                                                                    sale.status ===
                                                                    'completed',
                                                            }"
                                                            class="px-2 py-1 rounded-full text-xs font-medium capitalize inline-block w-fit"
                                                        >
                                                            {{
                                                                getTranslation(
                                                                    sale.status
                                                                )
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Items Header -->
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
                                                    />
                                                </svg>
                                                {{
                                                    getTranslation("orderItems")
                                                }}
                                                <span
                                                    class="ml-2 px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium"
                                                >
                                                    {{
                                                        toBengaliNumber(
                                                            sale.items
                                                                ?.length || 0
                                                        )
                                                    }}
                                                    {{
                                                        getTranslation("items")
                                                    }}
                                                </span>
                                            </h4>
                                        </div>

                                        <!-- Items List -->
                                        <div
                                            v-if="
                                                sale.items &&
                                                sale.items.length > 0
                                            "
                                        >
                                            <!-- Mobile Cards -->
                                            <div class="lg:hidden space-y-3">
                                                <div
                                                    v-for="(
                                                        item, itemIndex
                                                    ) in sale.items"
                                                    :key="`${item.product_id}-${item.variant}-${itemIndex}`"
                                                    class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow"
                                                >
                                                    <div
                                                        class="flex justify-between items-start mb-3"
                                                    >
                                                        <div
                                                            class="flex-1 min-w-0"
                                                        >
                                                            <h5
                                                                class="font-semibold text-gray-900 truncate"
                                                                :title="
                                                                    item.product_name
                                                                "
                                                            >
                                                                {{
                                                                    item.product_name
                                                                }}
                                                            </h5>
                                                            <p
                                                                class="text-sm text-gray-600 mt-1"
                                                            >
                                                                {{
                                                                    item.variant
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
                                                                    />
                                                                </svg>
                                                                <span
                                                                    class="text-sm font-medium text-gray-700"
                                                                >
                                                                    {{
                                                                        toBengaliNumber(
                                                                            item.total_bottles_sold ||
                                                                                item.quantity ||
                                                                                0
                                                                        )
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="flex justify-between items-center pt-3 border-t border-gray-100"
                                                    >
                                                        <div
                                                            class="text-center"
                                                        >
                                                            <p
                                                                class="text-xs text-gray-500 font-medium"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "totalPrice"
                                                                    )
                                                                }}
                                                            </p>
                                                            <p
                                                                class="text-sm font-bold text-gray-900"
                                                            >
                                                                ৳{{
                                                                    toBengaliNumber(
                                                                        formatCurrency(
                                                                            item.total_price
                                                                        )
                                                                    )
                                                                }}
                                                            </p>
                                                        </div>
                                                        <div
                                                            class="text-center"
                                                        >
                                                            <p
                                                                class="text-xs text-gray-500 font-medium"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "profit"
                                                                    )
                                                                }}
                                                            </p>
                                                            <p
                                                                class="text-sm font-bold"
                                                                :class="
                                                                    parseFloat(
                                                                        item.profit
                                                                    ) >= 0
                                                                        ? 'text-green-600'
                                                                        : 'text-red-600'
                                                                "
                                                            >
                                                                ৳{{
                                                                    toBengaliNumber(
                                                                        formatCurrency(
                                                                            item.profit
                                                                        )
                                                                    )
                                                                }}
                                                            </p>
                                                        </div>
                                                        <div
                                                            v-if="
                                                                item.cases_sold
                                                            "
                                                            class="text-center"
                                                        >
                                                            <p
                                                                class="text-xs text-gray-500 font-medium"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "cases"
                                                                    )
                                                                }}
                                                            </p>
                                                            <p
                                                                class="text-sm font-bold text-blue-600"
                                                            >
                                                                {{
                                                                    toBengaliNumber(
                                                                        item.cases_sold
                                                                    )
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Desktop Table -->
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
                                                                class="w-2/5 px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
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
                                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                                        />
                                                                    </svg>
                                                                    {{
                                                                        getTranslation(
                                                                            "product"
                                                                        )
                                                                    }}
                                                                </div>
                                                            </th>
                                                            <th
                                                                class="w-1/5 px-4 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "variant"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th
                                                                class="w-1/12 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "quantity"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th
                                                                v-if="
                                                                    hasAnyCases
                                                                "
                                                                class="w-1/12 px-4 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "cases"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th
                                                                class="w-1/6 px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "totalPrice"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th
                                                                class="w-1/6 px-4 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider"
                                                            >
                                                                {{
                                                                    getTranslation(
                                                                        "profit"
                                                                    )
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody
                                                        class="divide-y divide-gray-100"
                                                    >
                                                        <tr
                                                            v-for="(
                                                                item, itemIndex
                                                            ) in sale.items"
                                                            :key="`${item.product_id}-${item.variant}-${itemIndex}`"
                                                            class="hover:bg-gray-50 transition-colors"
                                                            :class="
                                                                itemIndex %
                                                                    2 ===
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
                                                                        >
                                                                            {{
                                                                                toBengaliNumber(
                                                                                    itemIndex +
                                                                                        1
                                                                                )
                                                                            }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="font-semibold text-gray-900"
                                                                            :title="
                                                                                item.product_name
                                                                            "
                                                                        >
                                                                            {{
                                                                                item.product_name
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="px-4 py-4 border-r border-gray-200"
                                                            >
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                                                >
                                                                    {{
                                                                        item.variant
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
                                                                            item.total_bottles_sold ||
                                                                                item.quantity ||
                                                                                0
                                                                        )
                                                                    }}
                                                                </span>
                                                            </td>
                                                            <td
                                                                v-if="
                                                                    hasAnyCases
                                                                "
                                                                class="px-4 py-4 text-center border-r border-gray-200"
                                                            >
                                                                <span
                                                                    v-if="
                                                                        item.cases_sold
                                                                    "
                                                                    class="inline-flex items-center justify-center w-12 h-8 bg-blue-100 text-blue-800 rounded-lg font-bold text-sm"
                                                                >
                                                                    {{
                                                                        toBengaliNumber(
                                                                            item.cases_sold
                                                                        )
                                                                    }}
                                                                </span>
                                                                <span
                                                                    v-else
                                                                    class="text-gray-400"
                                                                    >-</span
                                                                >
                                                            </td>
                                                            <td
                                                                class="px-4 py-4 text-right border-r border-gray-200"
                                                            >
                                                                <span
                                                                    class="font-bold text-gray-900 text-lg"
                                                                >
                                                                    ৳{{
                                                                        toBengaliNumber(
                                                                            formatCurrency(
                                                                                item.total_price
                                                                            )
                                                                        )
                                                                    }}
                                                                </span>
                                                            </td>
                                                            <td
                                                                class="px-4 py-4 text-right"
                                                            >
                                                                <span
                                                                    class="font-bold text-lg"
                                                                    :class="
                                                                        parseFloat(
                                                                            item.profit
                                                                        ) >= 0
                                                                            ? 'text-green-600'
                                                                            : 'text-red-600'
                                                                    "
                                                                >
                                                                    ৳{{
                                                                        toBengaliNumber(
                                                                            formatCurrency(
                                                                                item.profit
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
                                        <div
                                            v-else
                                            class="text-center py-8 text-gray-500"
                                        >
                                            {{ getTranslation("noItemsFound") }}
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
import InventoryStock from "../Dashboard/Partials/InventoryStock.vue";

defineOptions({
    layout: Layout,
});

// Types
interface SaleItem {
    product_id: number;
    product_name: string;
    variant: string;
    cases_sold?: number;
    total_bottles_sold?: number;
    purchased_bottles_sold?: number;
    free_bottles_sold?: number;
    quantity?: number;
    total_price: string | number;
    profit: string | number;
}

interface Sale {
    id: number;
    shop_id: number;
    shop_name: string;
    supplier_id: number | null;
    supplier_name: string;
    invoice_number: string;
    total_amount: string | number;
    total_profit: string | number;
    sale_date: string;
    status: string;
    items: SaleItem[];
}

interface Shop {
    id: number;
    shop_name: string;
}

interface Product {
    id: number;
    name: string;
}

interface Supplier {
    id: number;
    company_name: string;
}

interface Filters {
    start_date: string | null;
    end_date: string | null;
    shop_id: number | string | null;
    product_id: number | string | null;
    supplier_id: number | string | null;
}

// Props
const props = defineProps<{
    sales: Sale[];
    shops?: Shop[];
    products?: Product[];
    suppliers?: Supplier[];
    filters?: Filters;
}>();

// Reactive data
const currentLanguage = ref(localStorage.getItem("language") || "en");
const searchQuery = ref("");
const expandedSale = ref<number | null>(null);
const isLoading = ref(false);

// Computed properties
const totalSales = computed(() => props.sales?.length || 0);

const totalAmount = computed(() => {
    return (
        props.sales?.reduce(
            (sum, sale) => sum + parseFloat(sale.total_amount.toString()),
            0
        ) || 0
    );
});

const totalProfit = computed(() => {
    return (
        props.sales?.reduce(
            (sum, sale) => sum + parseFloat(sale.total_profit.toString()),
            0
        ) || 0
    );
});

const totalItemsSold = computed(() => {
    return (
        props.sales?.reduce((sum, sale) => {
            return (
                sum +
                (sale.items?.reduce((itemSum, item) => {
                    return (
                        itemSum +
                        (item.total_bottles_sold || item.quantity || 0)
                    );
                }, 0) || 0)
            );
        }, 0) || 0
    );
});

const hasAnyCases = computed(() => {
    return (
        props.sales?.some((sale) =>
            sale.items?.some((item) => item.cases_sold && item.cases_sold > 0)
        ) || false
    );
});

const filteredSales = computed(() => {
    if (!searchQuery.value || !props.sales) return props.sales;

    const query = searchQuery.value.toLowerCase();
    return props.sales.filter(
        (sale) =>
            sale.invoice_number.toLowerCase().includes(query) ||
            sale.shop_name.toLowerCase().includes(query) ||
            sale.supplier_name.toLowerCase().includes(query)
    );
});

// Filters
const filters = ref<Filters>({
    start_date: props.filters?.start_date || "",
    end_date: props.filters?.end_date || "",
    shop_id: props.filters?.shop_id || "",
    product_id: props.filters?.product_id || "",
    supplier_id: props.filters?.supplier_id || "",
});

// Translations
const translations = {
    en: {
        languageLabel: "English",
        salesReport: "Sales Report",
        invoiceNumber: "Invoice",
        shop: "Shop",
        supplier: "Supplier",
        totalAmount: "Amount",
        totalProfit: "Profit",
        saleDate: "Date",
        status: "Status",
        items: "Items",
        totalItems: "Total Items",
        cases: "Cases",
        totalSales: "Total Sales",
        totalPrice: "Total",
        profit: "Profit",
        product: "Product",
        variant: "Variant",
        quantity: "Qty",
        filters: "Filters",
        startDate: "Start Date",
        endDate: "End Date",
        allShops: "All Shops",
        allProducts: "All Products",
        allSuppliers: "All Suppliers",
        applyFilters: "Apply Filters",
        clearFilters: "Clear Filters",
        searchSales: "Search with Invoice",
        pending: "Pending",
        in_progress: "In Progress",
        completed: "Completed",
        actions: "Actions",
        show: "Show",
        hide: "Hide",
        view: "Bill",
        saleDetails: "Sale Details",
        orderItems: "Sales Items",
        noSales: "No sales found",
        noSalesDescription: "Try adjusting your filters or search criteria.",
        noItemsFound: "No items found for this sale",
    },
    bn: {
        languageLabel: "বাংলা",
        salesReport: "বিক্রয় রিপোর্ট",
        invoiceNumber: "চালান",
        shop: "দোকান",
        supplier: "সরবরাহকারী",
        totalAmount: "পরিমাণ",
        totalProfit: "লাভ",
        saleDate: "তারিখ",
        status: "অবস্থা",
        items: "আইটেম",
        totalItems: "মোট আইটেম",
        cases: "কেস",
        totalSales: "মোট বিক্রয়",
        totalPrice: "মোট",
        profit: "লাভ",
        product: "পণ্য",
        variant: "ভেরিয়েন্ট",
        quantity: "পরিমাণ",
        filters: "ফিল্টার",
        startDate: "শুরুর তারিখ",
        endDate: "শেষের তারিখ",
        allShops: "সকল দোকান",
        allProducts: "সকল পণ্য",
        allSuppliers: "সকল সরবরাহকারী",
        applyFilters: "ফিল্টার প্রয়োগ করুন",
        clearFilters: "ফিল্টার পরিষ্কার করুন",
        searchSales: "ইনভয়েস দিয়ে খুঁজুন",
        pending: "অপেক্ষায়",
        in_progress: "চলমান",
        completed: "সম্পন্ন",
        actions: "কর্ম",
        show: "দেখান",
        hide: "লুকান",
        view: "বিল",
        saleDetails: "বিক্রয় বিবরণ",
        orderItems: "বিক্রয় আইটেম",
        noSales: "কোন বিক্রয় পাওয়া যায়নি",
        noSalesDescription:
            "ফিল্টার বা অনুসন্ধানের মানদণ্ড সামঞ্জস্য করার চেষ্টা করুন।",
        noItemsFound: "এই বিক্রয়ের জন্য কোন আইটেম পাওয়া যায়নি",
    },
};

// Methods
function getTranslation(key: string): string {
    return translations[currentLanguage.value]?.[key] || key;
}

function getTranslationLabel(key: string, lang: string): string {
    return translations[lang]?.[key] || key;
}

function toBengaliNumber(num: number | string): string {
    if (num === null || num === undefined || num === "") return "";
    if (typeof num !== "number" && typeof num !== "string")
        return num.toString();
    if (currentLanguage.value !== "bn") return num.toString();

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return num
        .toString()
        .replace(/\d/g, (digit) => bengaliDigits[parseInt(digit)]);
}

function formatCurrency(amount: string | number): string {
    const num = parseFloat(amount.toString());
    return isNaN(num) ? "0.00" : num.toFixed(2);
}

function formatDate(dateString: string): string {
    if (!dateString) return "";

    try {
        const date = new Date(dateString);
        if (currentLanguage.value === "bn") {
            return toBengaliNumber(date.toLocaleDateString("en-CA")); // YYYY-MM-DD format
        }
        return date.toLocaleDateString("en-CA"); // YYYY-MM-DD format
    } catch (error) {
        return dateString;
    }
}

function changeLanguage(lang: string): void {
    currentLanguage.value = lang;
    localStorage.setItem("language", lang);
    document.documentElement.lang = lang;
}

function toggleItems(saleId: number): void {
    expandedSale.value = expandedSale.value === saleId ? null : saleId;
}

function applyFilters(): void {
    isLoading.value = true;
    router.get("/sales/report", filters.value, {
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
}

function clearFilters(): void {
    filters.value = {
        start_date: "",
        end_date: "",
        shop_id: "",
        product_id: "",
        supplier_id: "",
    };
    applyFilters();
}

function viewCashMemo(saleId: number): void {
    router.visit(`/sales/cash-memo/${saleId}`);
}

// Lifecycle
onMounted(() => {
    // Set initial language
    document.documentElement.lang = currentLanguage.value;
});
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

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 1000px;
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
    overflow: hidden;
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

.max-w-32 {
    max-width: 8rem;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .table-responsive {
        font-size: 0.75rem;
    }

    th,
    td {
        padding: 0.5rem;
    }
}

/* Ensure table doesn't overflow */
.overflow-x-auto {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Improved hover effects */
.hover\:shadow-md:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Better focus states */
.focus\:ring-4:focus {
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0
        var(--tw-ring-offset-width) var(--tw-ring-offset-color);
    --tw-ring-shadow: var(--tw-ring-inset) 0 0 0
        calc(4px + var(--tw-ring-offset-width)) var(--tw-ring-color);
    box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow),
        var(--tw-shadow, 0 0 #0000);
}

.focus\:ring-indigo-100:focus {
    --tw-ring-color: rgb(224 231 255);
}
</style>
