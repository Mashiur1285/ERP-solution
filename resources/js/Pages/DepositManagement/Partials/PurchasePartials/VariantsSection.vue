```vue
<template>
    <div
        class="bg-indigo-50 p-6 rounded-xl border border-indigo-100"
        :class="{ 'bangla-font': currentLanguage === 'bn' }"
    >
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ t("productVariants") }}
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
                {{ t("addVariant") }}
            </button>
        </div>
        <div class="space-y-6">
            <div
                v-for="(variant, index) in productForm.variants"
                :key="index"
                class="bg-white p-6 rounded-lg border border-gray-200 relative"
            >
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-md font-medium text-gray-700">
                        {{ t("variant") }}
                        {{
                            currentLanguage === "bn"
                                ? toBengaliNumber(index + 1)
                                : index + 1
                        }}
                    </h4>
                    <button
                        v-if="productForm.variants.length > 1"
                        @click="removeVariant(index)"
                        class="text-red-600 hover:text-red-800 p-2 rounded-full transition duration-300 bg-red-100 hover:bg-red-200"
                        :title="t('removeVariant')"
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
                    <!-- Variant Selection Dropdown -->
                    <div>
                        <label
                            :for="'variant_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("variantName") }}*
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
                            <select
                                v-model="variant.variant"
                                @change="
                                    handleVariantChange(index, variant.variant)
                                "
                                :id="'variant_' + index"
                                class="w-full pl-10 pr-8 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 appearance-none"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted && !variant.variant,
                                }"
                                required
                            >
                                <option value="">
                                    {{ t("selectVariant") }}
                                </option>
                                <option
                                    v-for="option in variantOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
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
                            v-if="isSubmitted && !variant.variant"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ t("variantNameRequired", { index: index + 1 }) }}
                        </p>
                    </div>

                    <!-- Number of Cases -->
                    <div>
                        <label
                            :for="'number_of_cases_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("numberOfCases") }}*
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
                                v-model.number="variant.number_of_cases"
                                type="number"
                                min="0"
                                :id="'number_of_cases_' + index"
                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted &&
                                        (!variant.number_of_cases ||
                                            variant.number_of_cases <= 0),
                                }"
                                required
                            />
                        </div>
                        <p
                            v-if="
                                isSubmitted &&
                                (!variant.number_of_cases ||
                                    variant.number_of_cases <= 0)
                            "
                            class="mt-2 text-sm text-red-600"
                        >
                            {{
                                t("numberOfCasesRequired", { index: index + 1 })
                            }}
                        </p>
                    </div>

                    <!-- Case Buying Price -->
                    <div>
                        <label
                            :for="'case_buying_price_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("caseBuyingPrice") }} (৳)*
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
                                v-model.number="variant.case_buying_price"
                                type="number"
                                step="0.01"
                                min="0"
                                :id="'case_buying_price_' + index"
                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted &&
                                        (!variant.case_buying_price ||
                                            variant.case_buying_price <= 0),
                                }"
                                required
                            />
                        </div>
                        <p
                            v-if="
                                isSubmitted &&
                                (!variant.case_buying_price ||
                                    variant.case_buying_price <= 0)
                            "
                            class="mt-2 text-sm text-red-600"
                        >
                            {{
                                t("caseBuyingPriceRequired", {
                                    index: index + 1,
                                })
                            }}
                        </p>
                    </div>

                    <!-- Bottles per Case (Editable) -->
                    <div>
                        <label
                            :for="'bottles_per_case_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("bottlesPerCase") }}*
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
                                v-model.number="variant.bottles_per_case"
                                type="number"
                                min="1"
                                :id="'bottles_per_case_' + index"
                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted &&
                                        (!variant.bottles_per_case ||
                                            variant.bottles_per_case <= 0),
                                }"
                                required
                            />
                        </div>
                        <p
                            v-if="
                                isSubmitted &&
                                (!variant.bottles_per_case ||
                                    variant.bottles_per_case <= 0)
                            "
                            class="mt-2 text-sm text-red-600"
                        >
                            {{
                                t("bottlesPerCaseRequired", {
                                    index: index + 1,
                                })
                            }}
                        </p>
                    </div>

                    <!-- Free Bottles per Case -->
                    <div>
                        <label
                            :for="'free_bottles_per_case_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("freeBottlesPerCase") }}
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
                                v-model.number="variant.free_bottles_per_case"
                                type="number"
                                min="0"
                                :id="'free_bottles_per_case_' + index"
                                class="w-full pl-10 pr-3 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                            />
                        </div>
                    </div>
                </div>

                <!-- Summary Cards for this variant -->
                <div
                    v-if="variant.number_of_cases && variant.case_buying_price"
                    class="mt-6 space-y-4"
                >
                    <!-- Summary Cards -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                    >
                        <!-- Total Bottles Card -->
                        <div
                            class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border border-indigo-200"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs font-medium text-indigo-600 uppercase tracking-wide"
                                    >
                                        {{ t("totalBottlesInVariant") }}
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-indigo-700 mt-1"
                                    >
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      getVariantTotalBottles(
                                                          variant
                                                      )
                                                  )
                                                : getVariantTotalBottles(
                                                      variant
                                                  ).toLocaleString()
                                        }}
                                    </p>
                                </div>
                                <div class="p-2 bg-indigo-200 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-indigo-600"
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
                            </div>
                        </div>

                        <!-- Total Cases Card -->
                        <div
                            class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs font-medium text-orange-600 uppercase tracking-wide"
                                    >
                                        {{ t("totalCasesInVariant") }}
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-orange-700 mt-1"
                                    >
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      (variant.number_of_cases ||
                                                          0) +
                                                          getCasesWithFreeBottles(
                                                              variant
                                                          )
                                                  )
                                                : (
                                                      (variant.number_of_cases ||
                                                          0) +
                                                      getCasesWithFreeBottles(
                                                          variant
                                                      )
                                                  ).toLocaleString()
                                        }}
                                    </p>
                                </div>
                                <div class="p-2 bg-orange-200 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-orange-600"
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
                            </div>
                        </div>

                        <!-- Total Free Bottles Card -->
                        <div
                            class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs font-medium text-green-600 uppercase tracking-wide"
                                    >
                                        {{ t("totalFreeBottles") }}
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-green-700 mt-1"
                                    >
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      getVariantTotalFreeBottles(
                                                          variant
                                                      )
                                                  )
                                                : getVariantTotalFreeBottles(
                                                      variant
                                                  ).toLocaleString()
                                        }}
                                    </p>
                                </div>
                                <div class="p-2 bg-green-200 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-green-600"
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
                            </div>
                        </div>

                        <!-- Extra Free Bottles Card -->
                        <div
                            class="bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-lg border border-teal-200"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs font-medium text-teal-600 uppercase tracking-wide"
                                    >
                                        {{ t("extraFreeBottles") }}
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-teal-700 mt-1"
                                    >
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      getExtraFreeBottles(
                                                          variant
                                                      )
                                                  )
                                                : getExtraFreeBottles(
                                                      variant
                                                  ).toLocaleString()
                                        }}
                                    </p>
                                </div>
                                <div class="p-2 bg-teal-200 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-teal-600"
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
                            </div>
                        </div>

                        <!-- Total Cost Card -->
                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs font-medium text-purple-600 uppercase tracking-wide"
                                    >
                                        {{ t("totalCost") }}
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-purple-700 mt-1"
                                    >
                                        ৳{{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      getVariantTotalCost(
                                                          variant
                                                      ).toFixed(2)
                                                  )
                                                : getVariantTotalCost(
                                                      variant
                                                  ).toLocaleString("en-US", {
                                                      minimumFractionDigits: 2,
                                                      maximumFractionDigits: 2,
                                                  })
                                        }}
                                    </p>
                                </div>
                                <div class="p-2 bg-purple-200 rounded-lg">
                                    <svg
                                        class="w-5 h-5 text-purple-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cost Analysis -->
                    <div
                        v-if="getVariantTotalCost(variant) > 0"
                        class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-lg border border-purple-200"
                    >
                        <h5
                            class="text-sm font-semibold text-purple-700 mb-3 flex items-center"
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
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ t("costAnalysis") }}
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-purple-100"
                            >
                                <p class="text-lg font-bold text-purple-600">
                                    ৳{{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getActualRatePerBottle(
                                                      variant
                                                  )
                                              )
                                            : getActualRatePerBottle(variant)
                                    }}
                                </p>
                                <p class="text-xs text-purple-600 font-medium">
                                    {{ t("actualRatePerBottle") }}
                                </p>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-purple-100"
                            >
                                <p class="text-lg font-bold text-purple-600">
                                    ৳{{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getActualRatePerCase(variant)
                                              )
                                            : getActualRatePerCase(variant)
                                    }}
                                </p>
                                <p class="text-xs text-purple-600 font-medium">
                                    {{ t("actualRatePerCase") }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Free Bottles Breakdown -->
                    <div
                        v-if="getVariantTotalFreeBottles(variant) > 0"
                        class="bg-gradient-to-r from-teal-50 to-cyan-50 p-4 rounded-lg border border-teal-200"
                    >
                        <h5
                            class="text-sm font-semibold text-teal-700 mb-3 flex items-center"
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
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            {{ t("freeBottlesBreakdown") }}
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-teal-100"
                            >
                                <p class="text-lg font-bold text-teal-600">
                                    {{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getVariantTotalFreeBottles(
                                                      variant
                                                  )
                                              )
                                            : getVariantTotalFreeBottles(
                                                  variant
                                              ).toLocaleString()
                                    }}
                                </p>
                                <p class="text-xs text-teal-600 font-medium">
                                    {{ t("totalFreeBottles") }}
                                </p>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-teal-100"
                            >
                                <p class="text-lg font-bold text-teal-600">
                                    {{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getCasesWithFreeBottles(
                                                      variant
                                                  )
                                              )
                                            : getCasesWithFreeBottles(
                                                  variant
                                              ).toLocaleString()
                                    }}
                                </p>
                                <p class="text-xs text-teal-600 font-medium">
                                    {{ t("casesFromFree") }}
                                </p>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-teal-100"
                            >
                                <p class="text-lg font-bold text-teal-600">
                                    {{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getExtraFreeBottles(variant)
                                              )
                                            : getExtraFreeBottles(
                                                  variant
                                              ).toLocaleString()
                                    }}
                                </p>
                                <p class="text-xs text-teal-600 font-medium">
                                    {{ t("extraFreeBottles") }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Remaining Deposit -->
                    <div
                        v-if="productForm.supplier_id"
                        class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 bg-gray-200 rounded-lg mr-3">
                                    <svg
                                        class="w-5 h-5 text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-700"
                                    >
                                        {{ t("remainingDeposit") }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ t("afterThisVariant") }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-xl font-bold"
                                    :class="{
                                        'text-green-600':
                                            variantRemainingDeposit(index) >= 0,
                                        'text-red-600':
                                            variantRemainingDeposit(index) < 0,
                                    }"
                                >
                                    ৳{{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  variantRemainingDeposit(
                                                      index
                                                  ).toFixed(2)
                                              )
                                            : variantRemainingDeposit(
                                                  index
                                              ).toLocaleString("en-US", {
                                                  minimumFractionDigits: 2,
                                                  maximumFractionDigits: 2,
                                              })
                                    }}
                                </p>
                                <p
                                    class="text-xs"
                                    :class="{
                                        'text-green-500':
                                            variantRemainingDeposit(index) >= 0,
                                        'text-red-500':
                                            variantRemainingDeposit(index) < 0,
                                    }"
                                >
                                    {{
                                        variantRemainingDeposit(index) >= 0
                                            ? t("sufficient")
                                            : t("insufficient")
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
interface ProductVariant {
    variant: string;
    number_of_cases: number;
    case_buying_price: number;
    bottles_per_case: number;
    free_bottles_per_case: number;
}

interface VariantOption {
    value: string;
    label: string;
    bottles_per_case: number;
}

defineProps<{
    productForm: {
        supplier_id: string;
        variants: ProductVariant[];
    };
    isSubmitted: boolean;
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
    getVariantTotalBottles: (variant: ProductVariant) => number;
    getVariantTotalFreeBottles: (variant: ProductVariant) => number;
    getVariantTotalCost: (variant: ProductVariant) => number;
    getActualRatePerBottle: (variant: ProductVariant) => string;
    getActualRatePerCase: (variant: ProductVariant) => string;
    getCasesWithFreeBottles: (variant: ProductVariant) => number;
    getCasesWithoutFreeBottles: (variant: ProductVariant) => number;
    getExtraFreeBottles: (variant: ProductVariant) => number;
    variantRemainingDeposit: (index: number) => number;
    variantOptions: VariantOption[];
}>();

const emit = defineEmits(["add-variant", "remove-variant", "variant-change"]);

const handleVariantChange = (index: number, variantValue: string) => {
    emit("variant-change", index, variantValue);
};

const addVariant = () => {
    emit("add-variant");
};

const removeVariant = (index: number) => {
    emit("remove-variant", index);
};
</script>

<style scoped>
.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

input:focus,
select:focus {
    outline: none;
    transform: translateY(-1px);
}

select {
    background-image: none;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>
```
