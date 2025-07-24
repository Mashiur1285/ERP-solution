<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
    >
        <!-- Toast Notification -->
        <div
            v-if="showToast"
            class="fixed top-6 right-6 px-7 py-5 rounded-lg shadow-lg flex items-center space-x-3 animate-toast-in z-50"
            :class="toastClasses"
            role="alert"
        >
            <svg
                class="w-5 h-5 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    v-if="toastType === 'success'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                />
                <path
                    v-else-if="toastType === 'warning'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
                <path
                    v-else
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <span class="font-medium text-white">{{ toastMessage }}</span>
            <button
                @click="closeToast"
                class="ml-2 text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white transition-colors"
                aria-label="Close notification"
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

        <!-- Confirmation Modal -->
        <!-- Confirmation Modal -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            role="dialog"
            aria-labelledby="modal-title"
        >
            <div
                class="bg-white rounded-2xl p-6 max-w-lg w-full mx-4 shadow-xl animate-fade-in"
            >
                <div class="flex items-center justify-between mb-4">
                    <h3
                        id="modal-title"
                        class="text-lg font-semibold text-gray-800 flex items-center"
                    >
                        <svg
                            class="w-6 h-6 text-indigo-600 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />
                        </svg>
                        Confirm Sale
                    </h3>
                    <button
                        @click="closeModal"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none"
                        aria-label="Close modal"
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
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Are you sure you want to create this sale?
                    </p>
                    <div
                        class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-xl border border-indigo-200"
                    >
                        <h4 class="text-md font-semibold text-gray-800 mb-3">
                            Sale Summary
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5 text-blue-600 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Items
                                    </p>
                                    <p class="font-bold text-blue-600">
                                        {{ totalSaleItems }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5 text-orange-600 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m0 0l-8-4m8 4v10"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Bottles
                                    </p>
                                    <p class="font-bold text-orange-600">
                                        {{ totalBottlesSold.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5 text-indigo-600 mr-2"
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
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Revenue
                                    </p>
                                    <p class="font-bold text-indigo-600">
                                        ৳{{ totalRevenue.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5 text-purple-600 mr-2"
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
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Profit
                                    </p>
                                    <p
                                        class="font-bold"
                                        :class="
                                            totalProfit >= 0
                                                ? 'text-purple-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        ৳{{ totalProfit.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button
                        @click="closeModal"
                        class="px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-300"
                    >
                        Cancel
                    </button>
                    <button
                        @click="confirmSale"
                        class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center space-x-2"
                        :disabled="isLoading"
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
                        <span>Confirm Sale</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div
            class="flex justify-between items-center mb-8 border-b border-gray-200 pb-4"
        >
            <h1
                class="text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
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
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 713.138-3.138z"
                        />
                    </svg>
                </div>
                Sales Management
            </h1>
            <div
                class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full"
            >
                {{ filteredProducts.length }} products available
            </div>
        </div>

        <!-- Main Form Container -->
        <div
            class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl relative overflow-hidden"
        >
            <!-- Background Pattern -->
            <svg
                class="absolute inset-0 w-full h-full opacity-5 pointer-events-none"
                viewBox="0 0 1440 320"
                preserveAspectRatio="none"
            >
                <path
                    fill="#4f46e5"
                    fill-opacity="0.1"
                    d="M0,160L48,138.7C96,117,192,75,288,80C384,85,480,139,576,149.3C672,160,768,128,864,106.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                />
            </svg>

            <div class="relative z-10">
                <!-- Form Header -->
                <div class="flex items-center justify-between mb-8">
                    <h2
                        class="text-2xl font-semibold text-gray-800 flex items-center"
                    >
                        <div
                            class="p-2 mr-3 bg-indigo-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-indigo-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                />
                            </svg>
                        </div>
                        Create New Sale
                    </h2>
                </div>

                <!-- Sale Information Section -->
                <div class="space-y-8">
                    <div
                        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
                    >
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">
                            Sale Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="shop_id"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                    >Shop*</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
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
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                            />
                                        </svg>
                                    </div>
                                    <select
                                        v-model="saleForm.shop_id"
                                        id="shop_id"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 appearance-none"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                                isSubmitted &&
                                                !saleForm.shop_id,
                                        }"
                                        required
                                    >
                                        <option value="" disabled>
                                            Select a shop
                                        </option>
                                        <option
                                            v-for="shop in shops"
                                            :key="shop.id"
                                            :value="shop.id"
                                        >
                                            {{ shop.shop_name }}
                                        </option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none"
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
                                                d="M19 9l-7 7-7-7"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <p
                                    v-if="isSubmitted && !saleForm.shop_id"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    Please select a shop
                                </p>
                            </div>

                            <div>
                                <label
                                    for="sale_date"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                    >Sale Date*</label
                                >
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
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
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="saleForm.sale_date"
                                        id="sale_date"
                                        type="date"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                                isSubmitted &&
                                                !saleForm.sale_date,
                                        }"
                                        required
                                    />
                                </div>
                                <p
                                    v-if="isSubmitted && !saleForm.sale_date"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    Sale date is required
                                </p>
                            </div>
                        </div>

                        <!-- Filters Section -->
                        <div class="mt-6">
                            <h4
                                class="text-md font-semibold text-gray-700 mb-4"
                            >
                                Filter Products
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label
                                        for="category_id"
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                        >Category</label
                                    >
                                    <select
                                        v-model="saleForm.category_id"
                                        id="category_id"
                                        class="w-full px-3 py-2 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                        @change="filterProducts"
                                    >
                                        <option value="">All Categories</option>
                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        for="brand_id"
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                        >Brand</label
                                    >
                                    <select
                                        v-model="saleForm.brand_id"
                                        id="brand_id"
                                        class="w-full px-3 py-2 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                        @change="filterProducts"
                                    >
                                        <option value="">All Brands</option>
                                        <option
                                            v-for="brand in brands"
                                            :key="brand.id"
                                            :value="brand.id"
                                        >
                                            {{ brand.brand_name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        for="supplier_id"
                                        class="block text-sm font-medium text-gray-700 mb-2"
                                        >Supplier</label
                                    >
                                    <select
                                        v-model="saleForm.supplier_id"
                                        id="supplier_id"
                                        class="w-full px-3 py-2 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                        @change="filterProducts"
                                    >
                                        <option value="">All Suppliers</option>
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
                    </div>

                    <!-- Sale Items Section -->
                    <div
                        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Sale Items
                            </h3>
                            <button
                                @click="addItem"
                                class="inline-flex items-center px-4 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition-all duration-300 shadow-md hover:shadow-lg"
                            >
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    />
                                </svg>
                                Add Item
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div
                                v-for="(item, index) in saleForm.items"
                                :key="index"
                                class="bg-white p-6 rounded-lg border border-gray-200 relative"
                            >
                                <div
                                    class="flex justify-between items-center mb-4"
                                >
                                    <h4
                                        class="text-md font-medium text-gray-700"
                                    >
                                        Sale Item {{ index + 1 }}
                                    </h4>
                                    <button
                                        v-if="saleForm.items.length > 1"
                                        @click="removeItem(index)"
                                        class="text-red-600 hover:text-red-800 p-2 rounded-full transition duration-300 bg-red-100 hover:bg-red-200"
                                        title="Remove Item"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Product Selection -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4"
                                >
                                    <div>
                                        <label
                                            :for="'product_id_' + index"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >Product*</label
                                        >
                                        <select
                                            v-model="item.product_id"
                                            :id="'product_id_' + index"
                                            class="w-full px-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                            required
                                            @change="
                                                updateAvailableVariants(index)
                                            "
                                        >
                                            <option value="" disabled>
                                                Select a product
                                            </option>
                                            <option
                                                v-for="product in filteredProducts"
                                                :key="product.id"
                                                :value="product.id"
                                            >
                                                {{ product.name }}
                                            </option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            :for="'variant_' + index"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >Variant*</label
                                        >
                                        <select
                                            v-model="item.variant"
                                            :id="'variant_' + index"
                                            class="w-full px-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                            required
                                            @change="
                                                populateVariantDetails(index)
                                            "
                                        >
                                            <option value="" disabled>
                                                Select a variant
                                            </option>
                                            <option
                                                v-for="variant in item.availableVariants"
                                                :key="variant"
                                                :value="variant"
                                            >
                                                {{ variant }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Inventory Information -->
                                <div
                                    class="border-t border-gray-200 pt-4 mb-4"
                                    v-if="item.variant"
                                >
                                    <h5
                                        class="text-md font-semibold text-gray-700 mb-3 flex items-center"
                                    >
                                        <svg
                                            class="w-5 h-5 text-blue-600 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m0 0l-8-4m8 4v10"
                                            />
                                        </svg>
                                        Available Inventory
                                    </h5>
                                    <div
                                        class="grid grid-cols-2 md:grid-cols-5 gap-4"
                                    >
                                        <div
                                            class="bg-blue-50 p-3 rounded-lg border border-blue-200"
                                        >
                                            <label
                                                class="block text-xs font-medium text-blue-700 mb-1"
                                                >Available Bottles</label
                                            >
                                            <div
                                                class="text-lg font-bold text-blue-600"
                                            >
                                                {{
                                                    item.available_bottles.toLocaleString()
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            class="bg-orange-50 p-3 rounded-lg border border-orange-200"
                                        >
                                            <label
                                                class="block text-xs font-medium text-orange-700 mb-1"
                                                >Available Boxes</label
                                            >
                                            <div
                                                class="text-lg font-bold text-orange-600"
                                            >
                                                {{
                                                    item.available_boxes.toLocaleString()
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            class="bg-purple-50 p-3 rounded-lg border border-purple-200"
                                        >
                                            <label
                                                class="block text-xs font-medium text-purple-700 mb-1"
                                                >Bottles per Box</label
                                            >
                                            <div
                                                class="text-lg font-bold text-purple-600"
                                            >
                                                {{
                                                    item.bottles_per_box.toLocaleString()
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            class="bg-indigo-50 p-3 rounded-lg border border-indigo-200"
                                        >
                                            <label
                                                class="block text-xs font-medium text-indigo-700 mb-1"
                                                >Free Bottles</label
                                            >
                                            <div
                                                class="text-lg font-bold text-indigo-600"
                                            >
                                                {{
                                                    item.free_bottles.toLocaleString()
                                                }}
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 p-3 rounded-lg border border-gray-200"
                                        >
                                            <label
                                                class="block text-xs font-medium text-gray-700 mb-1"
                                                >Cost Price (৳)</label
                                            >
                                            <div
                                                class="text-lg font-bold text-gray-600"
                                            >
                                                {{ item.unit_price.toFixed(2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sale Details -->
                                <div
                                    class="border-t border-gray-200 pt-4"
                                    v-if="item.variant"
                                >
                                    <h5
                                        class="text-md font-semibold text-gray-700 mb-3 flex items-center"
                                    >
                                        <svg
                                            class="w-5 h-5 text-indigo-600 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 713.138-3.138z"
                                            />
                                        </svg>
                                        Sale Details
                                    </h5>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4"
                                    >
                                        <div>
                                            <label
                                                :for="'boxes_sold_' + index"
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Boxes Sold*</label
                                            >
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
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
                                                            d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"
                                                        />
                                                    </svg>
                                                </div>
                                                <input
                                                    v-model.number="
                                                        item.boxes_sold
                                                    "
                                                    :id="'boxes_sold_' + index"
                                                    type="number"
                                                    min="0"
                                                    :max="item.available_boxes"
                                                    placeholder="Enter boxes"
                                                    class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                                    :class="{
                                                        'border-red-500 focus:border-red-500 focus:ring-red-200':
                                                            item.errors
                                                                ?.boxes_sold,
                                                    }"
                                                    required
                                                    @input="
                                                        calculateItemTotal(
                                                            index
                                                        )
                                                    "
                                                />
                                            </div>
                                            <p
                                                v-if="item.errors?.boxes_sold"
                                                class="mt-1 text-sm text-red-600"
                                            >
                                                {{ item.errors.boxes_sold }}
                                            </p>
                                        </div>

                                        <div>
                                            <label
                                                :for="'new_unit_price_' + index"
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Sell Price (৳)*</label
                                            >
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
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
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        />
                                                    </svg>
                                                </div>
                                                <input
                                                    v-model.number="
                                                        item.new_unit_price
                                                    "
                                                    :id="
                                                        'new_unit_price_' +
                                                        index
                                                    "
                                                    type="number"
                                                    min="0"
                                                    step="0.01"
                                                    placeholder="Enter price"
                                                    class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                                    required
                                                    @input="
                                                        calculateItemTotal(
                                                            index
                                                        )
                                                    "
                                                />
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                :for="'total_quantity_' + index"
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Total Bottles</label
                                            >
                                            <input
                                                v-model.number="
                                                    item.total_quantity
                                                "
                                                :id="'total_quantity_' + index"
                                                type="number"
                                                class="w-full px-3 py-2 rounded-md border-2 border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                                readonly
                                            />
                                        </div>

                                        <div>
                                            <label
                                                :for="'total_price_' + index"
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Total Revenue (৳)</label
                                            >
                                            <input
                                                v-model.number="
                                                    item.total_price
                                                "
                                                :id="'total_price_' + index"
                                                type="number"
                                                class="w-full px-3 py-2 rounded-md border-2 border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed text-indigo-600 font-semibold"
                                                readonly
                                            />
                                        </div>

                                        <div>
                                            <label
                                                :for="'profit_' + index"
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Profit (৳)</label
                                            >
                                            <input
                                                v-model.number="item.profit"
                                                :id="'profit_' + index"
                                                type="number"
                                                class="w-full px-3 py-2 rounded-md border-2 border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed font-semibold"
                                                :class="
                                                    item.profit >= 0
                                                        ? 'text-indigo-600'
                                                        : 'text-red-600'
                                                "
                                                readonly
                                            />
                                        </div>
                                    </div>

                                    <!-- Item Summary -->
                                    <div
                                        v-if="item.total_price > 0"
                                        class="mt-4 p-3 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-md border border-indigo-200"
                                    >
                                        <div
                                            class="flex justify-between items-center text-sm"
                                        >
                                            <span class="text-gray-600"
                                                >Item Summary:</span
                                            >
                                            <div class="flex space-x-4">
                                                <span
                                                    class="text-blue-600 font-medium"
                                                    >{{
                                                        item.total_quantity.toLocaleString()
                                                    }}
                                                    bottles</span
                                                >
                                                <span
                                                    class="text-indigo-600 font-semibold"
                                                    >৳{{
                                                        item.total_price.toLocaleString()
                                                    }}</span
                                                >
                                                <span
                                                    class="font-semibold"
                                                    :class="
                                                        item.profit >= 0
                                                            ? 'text-purple-600'
                                                            : 'text-red-600'
                                                    "
                                                >
                                                    Profit: ৳{{
                                                        item.profit.toLocaleString()
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div
                        v-if="totalRevenue > 0"
                        class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-xl border border-indigo-200"
                    >
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
                        >
                            <svg
                                class="w-6 h-6 text-indigo-600 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"
                                />
                            </svg>
                            Sale Summary
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div
                                class="text-center flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Items
                                    </p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ totalSaleItems }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="text-center flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-orange-600 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m0 0l-8-4m8 4v10"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Bottles
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-orange-600"
                                    >
                                        {{ totalBottlesSold.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="text-center flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-indigo-600 mr-2"
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
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Revenue
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-indigo-600"
                                    >
                                        ৳{{ totalRevenue.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="text-center flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-purple-600 mr-2"
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
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Profit
                                    </p>
                                    <p
                                        class="text-2xl font-bold"
                                        :class="
                                            totalProfit >= 0
                                                ? 'text-purple-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        ৳{{ totalProfit.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex justify-end space-x-4 pt-6 border-t border-gray-200"
                    >
                        <button
                            @click="resetForm"
                            class="px-8 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 flex items-center space-x-2"
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
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                />
                            </svg>
                            <span>Reset Form</span>
                        </button>

                        <button
                            @click="openModal"
                            class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="isLoading || !isFormValid"
                        >
                            <svg
                                v-if="isLoading"
                                class="w-5 h-5 animate-spin"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                />
                            </svg>
                            <svg
                                v-else
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 713.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 713.138-3.138z"
                                />
                            </svg>
                            <span>{{
                                isLoading ? "Processing..." : "Create Sale"
                            }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface Shop {
    id: number;
    shop_name: string;
}

interface Product {
    id: number;
    name: string;
    category_id: number | null;
    brand_id: number | null;
    supplier_id: number;
    metadata: {
        variants: {
            variant: string;
            quantity: number;
            bottles_per_box: number;
            free_bottles: number;
            unit_price: number;
        }[];
    };
}

interface Supplier {
    id: number;
    company_name: string;
}

interface Category {
    id: number;
    name: string;
}

interface Brand {
    id: number;
    brand_name: string;
}

interface SaleItem {
    product_id: number | null;
    supplier_id: number | null;
    variant: string | null;
    boxes_sold: number;
    bottles_per_box: number;
    available_bottles: number;
    available_boxes: number;
    total_quantity: number;
    free_bottles: number;
    unit_price: number;
    new_unit_price: number;
    total_price: number;
    profit: number;
    availableVariants: string[];
    errors?: { boxes_sold?: string };
}

interface Sale {
    shop_id: number | null;
    category_id: number | null;
    brand_id: number | null;
    supplier_id: number | null;
    sale_date: string;
    items: SaleItem[];
}

const props = defineProps<{
    shops: Shop[];
    products: Product[];
    suppliers: Supplier[];
    categories: Category[];
    brands: Brand[];
}>();

defineOptions({ layout: Layout });

const saleForm = ref<Sale>({
    shop_id: null,
    category_id: null,
    brand_id: null,
    supplier_id: null,
    sale_date: new Date().toISOString().split("T")[0],
    items: [
        {
            product_id: null,
            supplier_id: null,
            variant: null,
            boxes_sold: 0,
            bottles_per_box: 0,
            available_bottles: 0,
            available_boxes: 0,
            total_quantity: 0,
            free_bottles: 0,
            unit_price: 0,
            new_unit_price: 0,
            total_price: 0,
            profit: 0,
            availableVariants: [],
            errors: {},
        },
    ],
});

const isSubmitted = ref(false);
const isLoading = ref(false);
const showModal = ref(false);

// Toast state
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success");

const toastClasses = computed(() => ({
    "bg-indigo-500": toastType.value === "success",
    "bg-orange-500": toastType.value === "warning",
    "bg-red-500": toastType.value === "error",
    "text-white": true,
}));

// Computed properties
const filteredProducts = computed(() => {
    return props.products.filter((product) => {
        const matchesCategory = saleForm.value.category_id
            ? product.category_id === saleForm.value.category_id ||
              product.category_id === null
            : true;
        const matchesBrand = saleForm.value.brand_id
            ? product.brand_id === saleForm.value.brand_id ||
              product.brand_id === null
            : true;
        const matchesSupplier = saleForm.value.supplier_id
            ? product.supplier_id === saleForm.value.supplier_id
            : true;
        return matchesCategory && matchesBrand && matchesSupplier;
    });
});

const hasErrors = computed(() =>
    saleForm.value.items.some(
        (item) => item.errors && Object.keys(item.errors).length > 0
    )
);

const isFormValid = computed(
    () =>
        saleForm.value.shop_id !== null &&
        saleForm.value.sale_date &&
        saleForm.value.items.every(
            (item) =>
                item.product_id !== null &&
                item.variant !== null &&
                item.boxes_sold > 0 &&
                item.new_unit_price > 0
        )
);

const totalSaleItems = computed(() => {
    return saleForm.value.items.filter(
        (item) => item.product_id && item.variant && item.boxes_sold > 0
    ).length;
});

const totalBottlesSold = computed(() => {
    return saleForm.value.items.reduce(
        (sum, item) => sum + (item.total_quantity || 0),
        0
    );
});

const totalRevenue = computed(() => {
    return saleForm.value.items.reduce(
        (sum, item) => sum + (item.total_price || 0),
        0
    );
});

const totalProfit = computed(() => {
    return saleForm.value.items.reduce(
        (sum, item) => sum + (item.profit || 0),
        0
    );
});

// Toast functions
const showToastWithType = (message: string, type: string = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 5000);
};

const closeToast = () => {
    showToast.value = false;
};

// Modal functions
const openModal = () => {
    isSubmitted.value = true;
    const validationErrors = validateForm();
    if (validationErrors.length > 0) {
        showToastWithType(
            `Please fix the following errors: ${validationErrors.join(", ")}`,
            "error"
        );
        return;
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const validateForm = () => {
    const errors = [];

    if (!saleForm.value.shop_id) errors.push("Shop is required");
    if (!saleForm.value.sale_date) errors.push("Sale date is required");

    saleForm.value.items.forEach((item, index) => {
        if (!item.product_id)
            errors.push(`Item ${index + 1}: Product is required`);
        if (!item.variant)
            errors.push(`Item ${index + 1}: Variant is required`);
        if (!item.boxes_sold || item.boxes_sold <= 0)
            errors.push(`Item ${index + 1}: Boxes sold must be greater than 0`);
        if (!item.new_unit_price || item.new_unit_price <= 0)
            errors.push(`Item ${index + 1}: Sell price must be greater than 0`);
        if (item.boxes_sold > item.available_boxes)
            errors.push(`Item ${index + 1}: Cannot exceed available boxes`);
    });

    return errors;
};

// Product filtering
const filterProducts = () => {
    saleForm.value.items.forEach((item, index) => {
        item.product_id = null;
        item.variant = null;
        item.availableVariants = [];
        resetItemFields(item);
        updateAvailableVariants(index);
    });
};

const updateAvailableVariants = (index: number) => {
    const item = saleForm.value.items[index];
    item.supplier_id = saleForm.value.supplier_id;

    if (item.product_id) {
        const product = props.products.find((p) => p.id === item.product_id);
        if (product && product.metadata && product.metadata.variants) {
            item.availableVariants = product.metadata.variants
                .filter((variant) => variant.quantity > 0)
                .map((variant) => variant.variant);

            item.variant = item.availableVariants.includes(item.variant)
                ? item.variant
                : null;
        } else {
            item.availableVariants = [];
            item.variant = null;
        }
    } else {
        item.availableVariants = [];
        item.variant = null;
    }
    populateVariantDetails(index);
};

const populateVariantDetails = (index: number) => {
    const item = saleForm.value.items[index];

    if (item.product_id && item.variant) {
        const product = props.products.find((p) => p.id === item.product_id);
        if (product && product.metadata && product.metadata.variants) {
            const variantData = product.metadata.variants.find(
                (v) => v.variant === item.variant
            );
            if (variantData) {
                item.bottles_per_box = variantData.bottles_per_box;
                item.available_bottles = variantData.quantity;
                item.available_boxes = Math.floor(
                    variantData.quantity / variantData.bottles_per_box
                );
                item.free_bottles = variantData.free_bottles;
                item.unit_price = variantData.unit_price;
                item.new_unit_price = variantData.unit_price;

                // Validation
                item.errors = item.errors || {};
                if (item.boxes_sold > item.available_boxes) {
                    item.errors.boxes_sold = `Cannot exceed available boxes: ${item.available_boxes}`;
                } else {
                    delete item.errors.boxes_sold;
                }

                calculateItemTotal(index);
            } else {
                resetItemFields(item);
            }
        }
    } else {
        resetItemFields(item);
    }
};

const resetItemFields = (item: SaleItem) => {
    item.bottles_per_box = 0;
    item.available_bottles = 0;
    item.available_boxes = 0;
    item.total_quantity = 0;
    item.free_bottles = 0;
    item.unit_price = 0;
    item.new_unit_price = 0;
    item.total_price = 0;
    item.profit = 0;
    item.errors = {};
};

const calculateItemTotal = (index: number) => {
    const item = saleForm.value.items[index];

    item.total_quantity = item.boxes_sold * item.bottles_per_box;
    item.total_price = item.total_quantity * item.new_unit_price;
    item.profit = item.total_quantity * (item.new_unit_price - item.unit_price);
};

// Item management
const addItem = () => {
    saleForm.value.items.push({
        product_id: null,
        supplier_id: saleForm.value.supplier_id,
        variant: null,
        boxes_sold: 0,
        bottles_per_box: 0,
        available_bottles: 0,
        available_boxes: 0,
        total_quantity: 0,
        free_bottles: 0,
        unit_price: 0,
        new_unit_price: 0,
        total_price: 0,
        profit: 0,
        availableVariants: [],
        errors: {},
    });
};

const removeItem = (index: number) => {
    saleForm.value.items.splice(index, 1);
};

const resetForm = () => {
    saleForm.value = {
        shop_id: null,
        category_id: null,
        brand_id: null,
        supplier_id: null,
        sale_date: new Date().toISOString().split("T")[0],
        items: [
            {
                product_id: null,
                supplier_id: null,
                variant: null,
                boxes_sold: 0,
                bottles_per_box: 0,
                available_bottles: 0,
                available_boxes: 0,
                total_quantity: 0,
                free_bottles: 0,
                unit_price: 0,
                new_unit_price: 0,
                total_price: 0,
                profit: 0,
                availableVariants: [],
                errors: {},
            },
        ],
    };
    isSubmitted.value = false;
    showToastWithType("Form has been reset", "success");
};

const confirmSale = () => {
    if (hasErrors.value || !isFormValid.value) {
        showToastWithType(
            "Please fill all required fields and correct errors before submitting.",
            "error"
        );
        return;
    }

    isLoading.value = true;
    closeModal();

    console.log("Submitting sale:", saleForm.value);

    router.post("/sales/store", saleForm.value, {
        onSuccess: (page) => {
            console.log("onSuccess response:", page);
            const saleId = page.props.flash?.sale_id || null;

            if (saleId) {
                showToastWithType("Sale created successfully!", "success");
                router.visit(`/sales/payment/${saleId}`);
            } else {
                console.error("Sale ID not found in response");
                showToastWithType(
                    "Sale created, but unable to redirect to payment. Please navigate manually.",
                    "warning"
                );
                resetForm();
            }
        },
        onError: (errors) => {
            console.error("Sale creation errors:", errors);
            showToastWithType(
                "Failed to create sale. Please check your inputs.",
                "error"
            );
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

console.log("CreateSale.vue component loaded");
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

@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
}

/* Enhanced focus states */
input:focus,
select:focus {
    outline: none;
    transform: translateY(-1px);
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Custom select styling */
select {
    background-image: none;
}

/* Number input styling */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

/* Responsive grid adjustments */
@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}

/* Enhanced shadow effects */
.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(79, 70, 229, 0.1);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color,
        text-decoration-color, fill, stroke, opacity, box-shadow, transform,
        filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Custom scrollbar for modal */
.max-h-96::-webkit-scrollbar {
    width: 6px;
}

.max-h-96::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
