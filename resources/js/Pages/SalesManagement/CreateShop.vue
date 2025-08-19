```vue
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
                xmlns="http://www.w3.org/2000/svg"
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
                    xmlns="http://www.w3.org/2000/svg"
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

        <!-- Header -->
        <div
            class="flex justify-between items-center mb-8 border-b border-indigo-100 pb-4"
        >
            <h1
                class="text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-indigo-50 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-8 h-8 text-indigo-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                        />
                    </svg>
                </div>
                New Shop
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
                    fill="#e0e7ff"
                    fill-opacity="0.1"
                    d="M0,160L48,138.7C96,117,192,75,288,80C384,85,480,139,576,149.3C672,160,768,128,864,106.7C960,85,1056,75,1152,90.7C1248,107,1344,149,1392,170.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"
                />
            </svg>

            <div class="relative z-10">
                <!-- Section Header -->
                <div class="flex items-center justify-between mb-8">
                    <h2
                        class="text-2xl font-semibold text-gray-800 flex items-center"
                    >
                        <div
                            class="p-2 mr-3 bg-indigo-50 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-indigo-400"
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
                        Add New Shop
                    </h2>
                </div>

                <!-- Form Fields -->
                <div class="space-y-8">
                    <!-- Shop Information -->
                    <div
                        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
                    >
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
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
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                ></path>
                            </svg>
                            Shop Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="shop_name"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                            ></path>
                                        </svg>
                                        Shop Name*
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.shop_name"
                                    type="text"
                                    id="shop_name"
                                    placeholder="Enter shop name"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.shop_name,
                                    }"
                                    @input="shopForm.clearErrors?.('shop_name')"
                                />
                                <p
                                    v-if="shopForm.errors?.shop_name"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.shop_name }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="owner_name"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            ></path>
                                        </svg>
                                        Owner Name
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.owner_name"
                                    type="text"
                                    id="owner_name"
                                    placeholder="Enter owner name"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.owner_name,
                                    }"
                                    @input="
                                        shopForm.clearErrors?.('owner_name')
                                    "
                                />
                                <p
                                    v-if="shopForm.errors?.owner_name"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.owner_name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div
                        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
                    >
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
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
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                ></path>
                            </svg>
                            Contact Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="phone_number"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                            ></path>
                                        </svg>
                                        Phone Number*
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.phone_number"
                                    type="tel"
                                    id="phone_number"
                                    placeholder="Enter phone number"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.phone_number,
                                    }"
                                    @input="
                                        shopForm.clearErrors?.('phone_number')
                                    "
                                />
                                <p
                                    v-if="shopForm.errors?.phone_number"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.phone_number }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                            ></path>
                                        </svg>
                                        Email Address
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.email"
                                    type="email"
                                    id="email"
                                    placeholder="Enter email address"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.email,
                                    }"
                                    @input="shopForm.clearErrors?.('email')"
                                />
                                <p
                                    v-if="shopForm.errors?.email"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="website"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"
                                            ></path>
                                        </svg>
                                        Website
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.website"
                                    type="url"
                                    id="website"
                                    placeholder="Enter website URL"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.website,
                                    }"
                                    @input="shopForm.clearErrors?.('website')"
                                />
                                <p
                                    v-if="shopForm.errors?.website"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.website }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div
                        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
                    >
                        <h3
                            class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
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
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                            </svg>
                            Additional Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label
                                    for="shop_address"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            ></path>
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            ></path>
                                        </svg>
                                        Shop Address
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.shop_address"
                                    type="text"
                                    id="shop_address"
                                    placeholder="Enter shop address"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.shop_address,
                                    }"
                                    @input="
                                        shopForm.clearErrors?.('shop_address')
                                    "
                                />
                                <p
                                    v-if="shopForm.errors?.shop_address"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.shop_address }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="national_id"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                            ></path>
                                        </svg>
                                        National ID
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.national_id"
                                    type="text"
                                    id="national_id"
                                    placeholder="Enter national ID"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.national_id,
                                    }"
                                    @input="
                                        shopForm.clearErrors?.('national_id')
                                    "
                                />
                                <p
                                    v-if="shopForm.errors?.national_id"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.national_id }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="trade_license"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            ></path>
                                        </svg>
                                        Trade License
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.trade_license"
                                    type="text"
                                    id="trade_license"
                                    placeholder="Enter trade license"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.trade_license,
                                    }"
                                    @input="
                                        shopForm.clearErrors?.('trade_license')
                                    "
                                />
                                <p
                                    v-if="shopForm.errors?.trade_license"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.trade_license }}
                                </p>
                            </div>
                            <div>
                                <label
                                    for="tax_id"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <span class="flex items-center">
                                        <svg
                                            class="w-4 h-4 text-indigo-500 mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            ></path>
                                        </svg>
                                        Tax ID
                                    </span>
                                </label>
                                <input
                                    v-model="shopForm.tax_id"
                                    type="text"
                                    id="tax_id"
                                    placeholder="Enter tax ID"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300"
                                    :class="{
                                        'border-red-400 focus:border-red-500 focus:ring-red-200':
                                            shopForm.errors?.tax_id,
                                    }"
                                    @input="shopForm.clearErrors?.('tax_id')"
                                />
                                <p
                                    v-if="shopForm.errors?.tax_id"
                                    class="mt-2 text-sm text-red-600 flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    {{ shopForm.errors.tax_id }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <label
                                for="notes"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                <span class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-indigo-500 mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                    Notes
                                </span>
                            </label>
                            <textarea
                                v-model="shopForm.notes"
                                id="notes"
                                placeholder="Enter any additional notes"
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 hover:border-indigo-300 resize-vertical"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        shopForm.errors?.notes,
                                }"
                                rows="4"
                                @input="shopForm.clearErrors?.('notes')"
                            ></textarea>
                            <p
                                v-if="shopForm.errors?.notes"
                                class="mt-2 text-sm text-red-600 flex items-center"
                            >
                                <svg
                                    class="w-4 h-4 mr-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                                {{ shopForm.errors.notes }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex justify-end space-x-4 pt-6 border-t border-gray-200"
                    >
                        <Link
                            href="/shops/index"
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
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                            <span>Cancel</span>
                        </Link>
                        <button
                            @click="submitShop"
                            class="px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="shopForm.processing"
                        >
                            <svg
                                v-if="shopForm.processing"
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
                                ></path>
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
                                    d="M5 13l4 4L19 7"
                                ></path>
                            </svg>
                            <span>{{
                                shopForm.processing
                                    ? "Creating..."
                                    : "Create Shop"
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
import { router, useForm, Link } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface ShopForm {
    shop_name: string;
    owner_name: string | null;
    shop_address: string | null;
    phone_number: string;
    email: string | null;
    website: string | null;
    national_id: string | null;
    trade_license: string | null;
    tax_id: string | null;
    notes: string | null;
    errors?: Record<string, string>;
}

const shopForm = useForm<ShopForm>({
    shop_name: "",
    owner_name: null,
    shop_address: null,
    phone_number: "",
    email: null,
    website: null,
    national_id: null,
    trade_license: null,
    tax_id: null,
    notes: null,
});

// Toast state
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success");
const toastExiting = ref(false);

const toastClasses = computed(() => ({
    "bg-green-500": toastType.value === "success",
    "bg-orange-500": toastType.value === "warning",
    "bg-red-500": toastType.value === "error",
    "text-white": true,
}));

const showToastWithType = (message: string, type: string = "success") => {
    if (showToast.value) {
        toastExiting.value = true;
        setTimeout(() => {
            showToast.value = false;
            toastExiting.value = false;
            toastMessage.value = message;
            toastType.value = type;
            showToast.value = true;
            setTimeout(() => {
                toastExiting.value = true;
                setTimeout(() => {
                    showToast.value = false;
                }, 300);
            }, 5000);
        }, 300);
    } else {
        toastMessage.value = message;
        toastType.value = type;
        showToast.value = true;
        toastExiting.value = false;
        setTimeout(() => {
            toastExiting.value = true;
            setTimeout(() => {
                showToast.value = false;
            }, 300);
        }, 5000);
    }
};

const closeToast = () => {
    toastExiting.value = true;
    setTimeout(() => {
        showToast.value = false;
        toastExiting.value = false;
    }, 300);
};

defineOptions({
    layout: Layout,
});

const submitShop = () => {
    if (!shopForm.shop_name || !shopForm.phone_number) {
        showToastWithType("Please fill all required fields.", "warning");
        return;
    }

    shopForm.post("/shops/store", {
        onSuccess: () => {
            shopForm.reset();
            showToastWithType("Shop Created Successfully", "success");
        },
        onError: (errors: Record<string, string>) => {
            console.error("Shop creation errors:", errors);
            showToastWithType(
                "Failed to create shop. Please check the form.",
                "error"
            );
        },
    });
};

console.log("CreateShop.vue component loaded");
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

@keyframes toast-out {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
}

.animate-toast-out {
    animation: toast-out 0.3s ease-out forwards;
}

input:focus,
textarea:focus {
    outline: none;
    transform: translateY(-1px);
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

textarea::-webkit-scrollbar {
    width: 6px;
}

textarea::-webkit-scrollbar-thumb {
    background: #a5b4fc;
    border-radius: 3px;
}

textarea::-webkit-scrollbar-track {
    background: #f3f4f6;
}
</style>
```
