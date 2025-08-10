<template>
    <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ t("saleItems") }}
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
                {{ t("addItem") }}
            </button>
        </div>

        <div class="space-y-6">
            <div
                v-for="(item, index) in saleForm.items"
                :key="index"
                class="bg-white p-6 rounded-lg border border-gray-200 relative"
            >
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-md font-medium text-gray-700">
                        {{ t("item") }}
                        {{
                            currentLanguage === "bn"
                                ? toBengaliNumber(index + 1)
                                : index + 1
                        }}
                    </h4>
                    <button
                        v-if="saleForm.items.length > 1"
                        @click="removeItem(index)"
                        class="text-red-600 hover:text-red-800 p-2 rounded-full transition duration-300 bg-red-100 hover:bg-red-200"
                        :title="t('removeItem')"
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
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
                >
                    <!-- Product Selection -->
                    <div>
                        <label
                            :for="'product_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("product") }}*
                        </label>
                        <div class="relative">
                            <select
                                v-model="item.product_id"
                                @change="onProductChange(index)"
                                :id="'product_' + index"
                                class="w-full pr-8 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 appearance-none"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted && !item.product_id,
                                }"
                                required
                            >
                                <option value="">
                                    {{ t("selectProduct") }}
                                </option>
                                <option
                                    v-for="product in availableProducts"
                                    :key="product.product_id"
                                    :value="product.product_id"
                                >
                                    {{ product.product_name }}
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
                            v-if="isSubmitted && !item.product_id"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ t("productRequired") }}
                        </p>
                    </div>

                    <!-- Variant Selection -->
                    <div v-if="item.product_id">
                        <label
                            :for="'variant_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("variant") }}*
                        </label>
                        <div class="relative">
                            <select
                                v-model="item.variant"
                                @change="onVariantChange(index)"
                                :id="'variant_' + index"
                                class="w-full pr-8 py-2 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 appearance-none"
                                :class="{
                                    'border-red-400 focus:border-red-500 focus:ring-red-200':
                                        isSubmitted && !item.variant,
                                }"
                                required
                            >
                                <option value="">
                                    {{ t("selectVariant") }}
                                </option>
                                <option
                                    v-for="variant in getProductVariants(
                                        item.product_id
                                    )"
                                    :key="variant.variant"
                                    :value="variant.variant"
                                >
                                    {{ variant.variant }}
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
                            v-if="isSubmitted && !item.variant"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ t("variantRequired") }}
                        </p>
                    </div>

                    <!-- Cases Sold -->
                    <div v-if="item.variant">
                        <label
                            :for="'cases_sold_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("casesSold") }}*
                        </label>
                        <input
                            v-model.number="item.cases_sold"
                            @input="calculateItemTotals(index)"
                            type="number"
                            min="1"
                            :id="'cases_sold_' + index"
                            class="w-full py-2 px-3 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                            :class="{
                                'border-red-400 focus:border-red-500 focus:ring-red-200':
                                    isSubmitted &&
                                    (!item.cases_sold || item.cases_sold <= 0),
                            }"
                            required
                        />
                        <p
                            v-if="
                                isSubmitted &&
                                (!item.cases_sold || item.cases_sold <= 0)
                            "
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ t("casesSoldRequired") }}
                        </p>
                    </div>

                    <!-- Selling Price per Case -->
                    <div v-if="item.variant">
                        <label
                            :for="'selling_price_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("sellingPricePerCase") }}*
                        </label>
                        <input
                            v-model.number="item.selling_price_per_case"
                            @input="calculateItemTotals(index)"
                            type="number"
                            step="0.01"
                            min="0"
                            :id="'selling_price_' + index"
                            class="w-full py-2 px-3 rounded-md border-2 border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300"
                            :class="{
                                'border-red-400 focus:border-red-500 focus:ring-red-200':
                                    isSubmitted &&
                                    (!item.selling_price_per_case ||
                                        item.selling_price_per_case <= 0),
                            }"
                            required
                        />
                        <p
                            v-if="
                                isSubmitted &&
                                (!item.selling_price_per_case ||
                                    item.selling_price_per_case <= 0)
                            "
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ t("sellingPriceRequired") }}
                        </p>
                    </div>

                    <!-- Free Bottles per Case (READ-ONLY, populated from purchase data) -->
                    <div
                        v-if="
                            includeFreeBottles &&
                            item.variant &&
                            item.purchase_metadata
                        "
                    >
                        <label
                            :for="'free_bottles_per_case_' + index"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {{ t("freeBottlesPerCase") }}
                            <span class="text-xs text-gray-500"
                                >({{ t("fromPurchase") }})</span
                            >
                        </label>
                        <input
                            :value="
                                item.purchase_metadata.free_bottles_per_case ||
                                0
                            "
                            type="number"
                            :id="'free_bottles_per_case_' + index"
                            class="w-full py-2 px-3 rounded-md border-2 border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed"
                            readonly
                            disabled
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ t("autoPopulatedFromPurchase") }}
                        </p>
                    </div>
                </div>

                <!-- Item Summary -->
                <div
                    v-if="item.variant && item.available_inventory"
                    class="mt-6"
                >
                    <!-- Available Inventory Info -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h5
                            class="text-sm font-semibold text-gray-700 mb-3 flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-2 text-gray-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                />
                            </svg>
                            {{ t("availableInventory") }}
                        </h5>

                        <!-- Inventory Cards -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4"
                        >
                            <!-- Purchased Bottles -->
                            <div
                                class="bg-white p-3 rounded-lg border border-blue-200 shadow-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="p-1.5 bg-blue-100 rounded-md mr-2"
                                        >
                                            <svg
                                                class="w-3 h-3 text-blue-600"
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
                                        <div>
                                            <p
                                                class="text-xs text-gray-600 font-medium"
                                            >
                                                {{ t("purchasedBottles") }}
                                            </p>
                                            <p
                                                class="text-lg font-bold text-blue-600"
                                            >
                                                {{
                                                    currentLanguage === "bn"
                                                        ? toBengaliNumber(
                                                              item
                                                                  .available_inventory
                                                                  .purchased_bottles_available
                                                          )
                                                        : item.available_inventory.purchased_bottles_available.toLocaleString()
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Free Bottles (only if toggle is on) -->
                            <div
                                v-if="includeFreeBottles"
                                class="bg-white p-3 rounded-lg border border-green-200 shadow-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="p-1.5 bg-green-100 rounded-md mr-2"
                                        >
                                            <svg
                                                class="w-3 h-3 text-green-600"
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
                                            <p
                                                class="text-xs text-gray-600 font-medium"
                                            >
                                                {{ t("freeBottles") }}
                                            </p>
                                            <p
                                                class="text-lg font-bold text-green-600"
                                            >
                                                {{
                                                    currentLanguage === "bn"
                                                        ? toBengaliNumber(
                                                              item
                                                                  .available_inventory
                                                                  .free_bottles_available
                                                          )
                                                        : item.available_inventory.free_bottles_available.toLocaleString()
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Available -->
                            <div
                                class="bg-white p-3 rounded-lg border border-indigo-200 shadow-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="p-1.5 bg-indigo-100 rounded-md mr-2"
                                        >
                                            <svg
                                                class="w-3 h-3 text-indigo-600"
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
                                            <p
                                                class="text-xs text-gray-600 font-medium"
                                            >
                                                {{ t("totalAvailable") }}
                                            </p>
                                            <p
                                                class="text-lg font-bold text-indigo-600"
                                            >
                                                {{
                                                    currentLanguage === "bn"
                                                        ? toBengaliNumber(
                                                              getTotalAvailableBottles(
                                                                  item
                                                              )
                                                          )
                                                        : getTotalAvailableBottles(
                                                              item
                                                          ).toLocaleString()
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Rate Information -->
                        <div
                            class="bg-white p-3 rounded-lg border border-purple-200 shadow-sm"
                        >
                            <h6
                                class="text-xs font-semibold text-purple-700 mb-2 flex items-center"
                            >
                                <svg
                                    class="w-3 h-3 mr-1 text-purple-600"
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
                                {{ t("purchaseRateInfo") }}
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <p class="text-xs text-gray-600">
                                        {{ t("perBottle") }}
                                    </p>
                                    <p
                                        class="text-sm font-bold text-purple-600"
                                    >
                                        ৳{{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      item.purchase_rate.toFixed(
                                                          2
                                                      )
                                                  )
                                                : item.purchase_rate.toFixed(2)
                                        }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-600">
                                        {{ t("perCase") }}
                                    </p>
                                    <p
                                        class="text-sm font-bold text-purple-600"
                                    >
                                        ৳{{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      getPurchaseRatePerCase(
                                                          item
                                                      ).toFixed(2)
                                                  )
                                                : getPurchaseRatePerCase(
                                                      item
                                                  ).toFixed(2)
                                        }}
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-600">
                                        {{ t("bottlesPerCase") }}
                                    </p>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{
                                            currentLanguage === "bn"
                                                ? toBengaliNumber(
                                                      item.bottles_per_case
                                                  )
                                                : item.bottles_per_case
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Metadata Info (if available) -->
                        <div
                            v-if="item.purchase_metadata && includeFreeBottles"
                            class="bg-white p-3 rounded-lg border border-yellow-200 shadow-sm mt-3"
                        >
                            <h6
                                class="text-xs font-semibold text-yellow-700 mb-2 flex items-center"
                            >
                                <svg
                                    class="w-3 h-3 mr-1 text-yellow-600"
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
                                {{ t("purchaseData") }}
                            </h6>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs"
                            >
                                <div>
                                    <span class="text-gray-600"
                                        >{{ t("freeBottlesPerCase") }}:</span
                                    >
                                    <span class="font-bold text-gray-800 ml-1">
                                        {{
                                            item.purchase_metadata
                                                .free_bottles_per_case || 0
                                        }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-600"
                                        >{{ t("caseBuyingPrice") }}:</span
                                    >
                                    <span class="font-bold text-gray-800 ml-1">
                                        ৳{{
                                            item.purchase_metadata
                                                .case_buying_price || 0
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item Calculations -->
                    <div
                        v-if="item.cases_sold && item.selling_price_per_case"
                        class="bg-gradient-to-r from-indigo-50 to-blue-50 p-4 rounded-lg"
                    >
                        <h5 class="text-sm font-semibold text-indigo-700 mb-3">
                            {{ t("itemSummary") }}
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-indigo-100"
                            >
                                <p class="text-lg font-bold text-indigo-600">
                                    ৳{{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getItemTotal(item).toFixed(2)
                                              )
                                            : getItemTotal(item).toLocaleString(
                                                  "en-US",
                                                  {
                                                      minimumFractionDigits: 2,
                                                      maximumFractionDigits: 2,
                                                  }
                                              )
                                    }}
                                </p>
                                <p class="text-xs text-indigo-600 font-medium">
                                    {{ t("totalAmount") }}
                                </p>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-green-100"
                            >
                                <p class="text-lg font-bold text-green-600">
                                    ৳{{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getItemProfit(item).toFixed(2)
                                              )
                                            : getItemProfit(
                                                  item
                                              ).toLocaleString("en-US", {
                                                  minimumFractionDigits: 2,
                                                  maximumFractionDigits: 2,
                                              })
                                    }}
                                </p>
                                <p class="text-xs text-green-600 font-medium">
                                    {{ t("profit") }}
                                </p>
                            </div>
                            <div
                                class="text-center p-3 bg-white rounded-lg border border-orange-100"
                            >
                                <p class="text-lg font-bold text-orange-600">
                                    {{
                                        currentLanguage === "bn"
                                            ? toBengaliNumber(
                                                  getTotalBottlesSold(item)
                                              )
                                            : getTotalBottlesSold(
                                                  item
                                              ).toLocaleString()
                                    }}
                                </p>
                                <p class="text-xs text-orange-600 font-medium">
                                    {{ t("totalBottles") }}
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
    purchased_bottles_available: number;
    free_bottles_available: number;
    total_bottles_available: number;
    bottles_per_case: number;
    purchase_rate: number;
    cases_available: number;
    variant_metadata: any;
}

interface Product {
    product_id: number;
    product_name: string;
    supplier_id: number;
    supplier_name: string;
    variants: ProductVariant[];
}

interface SaleItem {
    product_id: number;
    variant: string;
    cases_sold: number;
    selling_price_per_case: number;
    bottles_per_case: number;
    purchase_rate: number;
    available_inventory?: any;
    purchase_metadata?: any; // Added to store purchase-level data
}

const props = defineProps<{
    saleForm: {
        items: SaleItem[];
    };
    availableProducts: Product[];
    includeFreeBottles: boolean;
    isSubmitted: boolean;
    currentLanguage: string;
    t: (key: string, params?: Record<string, any>) => string;
    toBengaliNumber: (num: number | string) => string;
}>();

const emit = defineEmits([
    "add-item",
    "remove-item",
    "item-change",
    "variant-change",
]);

const addItem = () => {
    emit("add-item");
};

const removeItem = (index: number) => {
    emit("remove-item", index);
};

const onProductChange = (index: number) => {
    const item = props.saleForm.items[index];
    item.variant = "";
    item.bottles_per_case = 0;
    item.purchase_rate = 0;
    item.available_inventory = null;
    item.purchase_metadata = null;
    emit("item-change", index, "product_id", item.product_id);
};

const onVariantChange = (index: number) => {
    const item = props.saleForm.items[index];
    emit("variant-change", index, item.product_id, item.variant);
};

const calculateItemTotals = (index: number) => {
    // This will trigger reactivity for calculated fields
    emit("item-change", index, "calculated", true);
};

const getProductVariants = (productId: number): ProductVariant[] => {
    const product = props.availableProducts.find(
        (p) => p.product_id === productId
    );
    return product ? product.variants : [];
};

const getItemTotal = (item: SaleItem): number => {
    return (item.cases_sold || 0) * (item.selling_price_per_case || 0);
};

const getItemProfit = (item: SaleItem): number => {
    const sellPrice = getItemTotal(item);

    // Calculate total bottles sold (including free bottles from purchase data)
    const totalBottlesSold = getTotalBottlesSold(item);
    const purchaseCost = totalBottlesSold * (item.purchase_rate || 0);

    return sellPrice - purchaseCost;
};

const getTotalBottlesSold = (item: SaleItem): number => {
    const purchasedBottles =
        (item.cases_sold || 0) * (item.bottles_per_case || 0);
    let freeBottles = 0;

    if (props.includeFreeBottles && item.purchase_metadata) {
        // Use free bottles per case from purchase data
        const freeBottlesPerCase =
            item.purchase_metadata.free_bottles_per_case || 0;
        freeBottles = (item.cases_sold || 0) * freeBottlesPerCase;
    }

    return purchasedBottles + freeBottles;
};

const getTotalAvailableBottles = (item: SaleItem): number => {
    if (!item.available_inventory) return 0;
    if (props.includeFreeBottles) {
        return (
            item.available_inventory.purchased_bottles_available +
            item.available_inventory.free_bottles_available
        );
    }
    return item.available_inventory.purchased_bottles_available;
};

const getPurchaseRatePerCase = (item: SaleItem): number => {
    // Use the actual case buying price from purchase metadata
    if (item.purchase_metadata && item.purchase_metadata.case_buying_price) {
        return item.purchase_metadata.case_buying_price;
    }
    // Fallback to calculation if case_buying_price is not available
    return (item.purchase_rate || 0) * (item.bottles_per_case || 0);
};
</script>

<style scoped>
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
