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
                        Confirm Purchase
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
                        Are you sure you want to add this purchase?
                    </p>
                    <div
                        class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-xl border border-indigo-200"
                    >
                        <h4 class="text-md font-semibold text-gray-800 mb-3">
                            Purchase Summary
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
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
                                        d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m0 0l-8-4m8 4v10"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Items
                                    </p>
                                    <p class="font-bold text-indigo-600">
                                        {{ totalQuantity.toLocaleString() }}
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
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Variants
                                    </p>
                                    <p class="font-bold text-orange-600">
                                        {{ productForm.variants.length }}
                                    </p>
                                </div>
                            </div>
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
                                        d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Boxes
                                    </p>
                                    <p class="font-bold text-blue-600">
                                        {{ totalBoxes.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5 text-green-600 mr-2"
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
                                        Total Cost
                                    </p>
                                    <p class="font-bold text-green-600">
                                        ৳{{ totalCost.toLocaleString() }}
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
                        @click="confirmPurchase"
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
                        <span>Confirm</span>
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
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                        />
                    </svg>
                </div>
                Purchase Management
            </h1>
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
                            class="p-2 mr-3 bg-indigo-200 rounded-full flex items-center justify-center"
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
                        Add New Purchase
                    </h2>
                </div>

                <!-- Product Information Section -->
                <div class="space-y-8">
                    <div
                        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
                    >
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">
                            Product Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="product_name"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Product Name*
                                </label>
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
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="productForm.product_name"
                                        id="product_name"
                                        type="text"
                                        placeholder="Enter product name"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                                isSubmitted &&
                                                !productForm.product_name,
                                        }"
                                        required
                                    />
                                </div>
                                <p
                                    v-if="
                                        isSubmitted && !productForm.product_name
                                    "
                                    class="mt-2 text-sm text-red-600"
                                >
                                    Product name is required
                                </p>
                            </div>

                            <div>
                                <label
                                    for="category_id"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Category*
                                </label>
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
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0 Anita2 0 012-2h10"
                                            />
                                        </svg>
                                    </div>
                                    <select
                                        v-model="productForm.category_id"
                                        id="category_id"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 appearance-none"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                                isSubmitted &&
                                                !productForm.category_id,
                                        }"
                                        required
                                    >
                                        <option value="" disabled>
                                            Select a category
                                        </option>
                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
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
                                    v-if="
                                        isSubmitted && !productForm.category_id
                                    "
                                    class="mt-2 text-sm text-red-600"
                                >
                                    Please select a category
                                </p>
                            </div>

                            <div>
                                <label
                                    for="brand_id"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Brand*
                                </label>
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
                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>
                                    <select
                                        v-model="productForm.brand_id"
                                        id="brand_id"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 appearance-none"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                                isSubmitted &&
                                                !productForm.brand_id,
                                        }"
                                        required
                                    >
                                        <option value="" disabled>
                                            Select a brand
                                        </option>
                                        <option
                                            v-for="brand in brands"
                                            :key="brand.id"
                                            :value="brand.id"
                                        >
                                            {{ brand.brand_name }}
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
                                    v-if="isSubmitted && !productForm.brand_id"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    Please select a brand
                                </p>
                            </div>

                            <div>
                                <label
                                    for="purchase_supplier_id"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Supplier*
                                </label>
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
                                                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m6 10a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                    <select
                                        v-model="productForm.supplier_id"
                                        id="purchase_supplier_id"
                                        class="w-full pl-10 pr-10 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 appearance-none"
                                        :class="{
                                            'border-red-400 focus:border-red-500 focus:ring-red-200':
                                                isSubmitted &&
                                                !productForm.supplier_id,
                                        }"
                                        required
                                    >
                                        <option value="" disabled>
                                            Select a supplier
                                        </option>
                                        <option
                                            v-for="supplier in suppliers"
                                            :key="supplier.id"
                                            :value="supplier.id"
                                        >
                                            {{ supplier.company_name }} (৳{{
                                                supplier.remaining_deposit.toFixed(
                                                    2
                                                )
                                            }})
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
                                    v-if="
                                        isSubmitted && !productForm.supplier_id
                                    "
                                    class="mt-2 text-sm text-red-600"
                                >
                                    Please select a supplier
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Remaining deposit amount is shown in
                                    parentheses
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Variants Section -->
                    <div
                        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
                    >
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Product Variants
                            </h3>
                            <button
                                @click="addVariant"
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
                                Add Variant
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div
                                v-for="(variant, index) in productForm.variants"
                                :key="index"
                                class="bg-white p-6 rounded-lg border border-gray-200 relative"
                            >
                                <div
                                    class="flex justify-between items-center mb-4"
                                >
                                    <h4
                                        class="text-md font-medium text-gray-700"
                                    >
                                        Variant {{ index + 1 }}
                                    </h4>
                                    <button
                                        v-if="productForm.variants.length > 1"
                                        @click="removeVariant(index)"
                                        class="text-red-600 hover:text-red-800 p-2 rounded-full transition duration-300 bg-red-100 hover:bg-red-200"
                                        title="Remove Variant"
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

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4"
                                >
                                    <div>
                                        <label
                                            :for="'variant_' + index"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Variant Name*
                                        </label>
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
                                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                                    />
                                                </svg>
                                            </div>
                                            <input
                                                v-model="variant.variant"
                                                placeholder="e.g., 500ml, Large"
                                                type="text"
                                                :id="'variant_' + index"
                                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            :for="'quantity_' + index"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Quantity*
                                        </label>
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
                                                        d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m0 0l-8-4m8 4v10"
                                                    />
                                                </svg>
                                            </div>
                                            <input
                                                v-model.number="
                                                    variant.quantity
                                                "
                                                type="number"
                                                min="0"
                                                :id="'quantity_' + index"
                                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            :for="'boxes_' + index"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Items per Box*
                                        </label>
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
                                                    variant.bottles_per_box
                                                "
                                                type="number"
                                                min="1"
                                                :id="'boxes_' + index"
                                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            :for="'free_bottles_' + index"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Total Free Items
                                        </label>
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
                                                    variant.free_bottles
                                                "
                                                type="number"
                                                min="0"
                                                :id="'free_bottles_' + index"
                                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            :for="'unit_price_' + index"
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Unit Price (৳)*
                                        </label>
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
                                                    variant.unit_price
                                                "
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                :id="'unit_price_' + index"
                                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Variant Summary -->
                                <div
                                    v-if="
                                        variant.quantity && variant.unit_price
                                    "
                                    class="mt-4 p-3 bg-gray-50 rounded-md border border-gray-200"
                                >
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600"
                                            >Total Cost:</span
                                        >
                                        <span
                                            class="font-semibold text-indigo-600"
                                        >
                                            ৳{{
                                                (
                                                    variant.quantity *
                                                    variant.unit_price
                                                ).toLocaleString()
                                            }}
                                        </span>
                                    </div>
                                    <div
                                        v-if="variant.bottles_per_box"
                                        class="flex justify-between text-sm mt-1"
                                    >
                                        <span class="text-gray-600"
                                            >Total Boxes:</span
                                        >
                                        <span class="font-medium">
                                            {{
                                                Math.ceil(
                                                    variant.quantity /
                                                        variant.bottles_per_box
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div
                        v-if="totalCost > 0"
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
                            Purchase Summary
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                                        d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4m0 0l-8-4m8 4v10"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Items
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-indigo-600"
                                    >
                                        {{ totalQuantity.toLocaleString() }}
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
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Variants
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-orange-600"
                                    >
                                        {{ productForm.variants.length }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="text-center flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600 mr-2"
                                    fill="none"
                                    stroke="current personally. I don't like it."
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"
                                    />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Boxes
                                    </p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ totalBoxes.toLocaleString() }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="text-center flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-green-600 mr-2"
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
                                        Total Cost
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-green-600"
                                    >
                                        ৳{{ totalCost.toLocaleString() }}
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
                            :disabled="isLoading"
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
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                />
                            </svg>
                            <span>{{
                                isLoading ? "Processing..." : "Add Purchase"
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

interface Supplier {
    id: number;
    company_name: string;
    remaining_deposit: number;
}

interface Category {
    id: number;
    name: string;
}

interface Brand {
    id: number;
    brand_name: string;
}

interface ProductVariant {
    variant: string;
    quantity: number;
    bottles_per_box: number;
    free_bottles: number;
    unit_price: number;
}

const props = defineProps<{
    suppliers: Supplier[];
    categories: Category[];
    brands: Brand[];
}>();

defineOptions({
    layout: Layout,
});

const productForm = ref({
    product_name: "",
    category_id: "",
    brand_id: "",
    supplier_id: "",
    variants: [
        {
            variant: "",
            quantity: 0,
            bottles_per_box: 0,
            free_bottles: 0,
            unit_price: 0,
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
    "bg-green-500": toastType.value === "success",
    "bg-orange-500": toastType.value === "warning",
    "bg-red-500": toastType.value === "error",
    "text-white": true,
}));

// Computed properties for summary
const totalQuantity = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) => sum + (variant.quantity || 0),
        0
    );
});

const totalBoxes = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) =>
            sum +
            (variant.bottles_per_box
                ? Math.ceil(variant.quantity / variant.bottles_per_box)
                : 0),
        0
    );
});

const totalCost = computed(() => {
    return productForm.value.variants.reduce(
        (sum, variant) =>
            sum + (variant.quantity || 0) * (variant.unit_price || 0),
        0
    );
});

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

const addVariant = () => {
    productForm.value.variants.push({
        variant: "",
        quantity: 0,
        bottles_per_box: 0,
        free_bottles: 0,
        unit_price: 0,
    });
};

const removeVariant = (index: number) => {
    productForm.value.variants.splice(index, 1);
};

const resetForm = () => {
    productForm.value = {
        product_name: "",
        category_id: "",
        brand_id: "",
        supplier_id: "",
        variants: [
            {
                variant: "",
                quantity: 0,
                bottles_per_box: 0,
                free_bottles: 0,
                unit_price: 0,
            },
        ],
    };
    isSubmitted.value = false;
    showToastWithType("Form has been reset", "success");
};

const validateForm = () => {
    const errors = [];

    if (!productForm.value.product_name)
        errors.push("Product name is required");
    if (!productForm.value.category_id) errors.push("Category is required");
    if (!productForm.value.brand_id) errors.push("Brand is required");
    if (!productForm.value.supplier_id) errors.push("Supplier is required");

    productForm.value.variants.forEach((variant, index) => {
        if (!variant.variant)
            errors.push(`Variant ${index + 1}: Name is required`);
        if (!variant.quantity || variant.quantity <= 0)
            errors.push(
                `Variant ${index + 1}: Quantity must be greater than 0`
            );
        if (!variant.bottles_per_box || variant.bottles_per_box <= 0)
            errors.push(
                `Variant ${index + 1}: Items per box must be greater than 0`
            );
        if (!variant.unit_price || variant.unit_price <= 0)
            errors.push(
                `Variant ${index + 1}: Unit price must be greater than 0`
            );
    });

    return errors;
};

const confirmPurchase = () => {
    isLoading.value = true;
    closeModal();
    console.log("Submitting product:", productForm.value);

    router.post("/products-store", productForm.value, {
        onSuccess: () => {
            resetForm();
            showToastWithType("Purchase added successfully!", "success");
            console.log("Product submitted successfully");
        },
        onError: (errors) => {
            console.error("Product submission errors:", errors);
            showToastWithType(
                "Failed to add purchase. Please check your inputs.",
                "error"
            );
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

console.log("Purchase.vue component loaded");
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
</style>
