```vue
<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Create New Sale
        </h1>

        <!-- Debug Info -->
        <div class="mb-4 text-sm text-gray-600">
            <div>Products: {{ products.length }} items</div>
            <div>Filtered Products: {{ filteredProducts.length }} items</div>
        </div>

        <!-- Sale Creation Form -->
        <div
            class="space-y-6 mb-12 bg-gray-50 p-6 rounded-lg border border-gray-200"
        >
            <h2 class="text-2xl font-semibold text-gray-800">Sale Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label
                        for="shop_id"
                        class="block text-sm font-medium text-gray-700"
                        >Shop*</label
                    >
                    <select
                        v-model="saleForm.shop_id"
                        id="shop_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                        required
                    >
                        <option value="" disabled>Select a shop</option>
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
                        for="sale_date"
                        class="block text-sm font-medium text-gray-700"
                        >Sale Date*</label
                    >
                    <input
                        v-model="saleForm.sale_date"
                        id="sale_date"
                        type="date"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                        required
                    />
                </div>
                <div>
                    <label
                        for="category_id"
                        class="block text-sm font-medium text-gray-700"
                        >Category</label
                    >
                    <select
                        v-model="saleForm.category_id"
                        id="category_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                    >
                        <option value="" selected>All Categories</option>
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
                        class="block text-sm font-medium text-gray-700"
                        >Brand</label
                    >
                    <select
                        v-model="saleForm.brand_id"
                        id="brand_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                    >
                        <option value="" selected>All Brands</option>
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
                        class="block text-sm font-medium text-gray-700"
                        >Supplier</label
                    >
                    <select
                        v-model="saleForm.supplier_id"
                        id="supplier_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                    >
                        <option value="" selected>All Suppliers</option>
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

            <!-- Sale Items -->
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                    Sale Items
                </h3>
                <div
                    v-for="(item, index) in saleForm.items"
                    :key="index"
                    class="mb-6 p-6 bg-white rounded-lg shadow-sm border border-gray-200"
                >
                    <!-- Product Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label
                                :for="'product_id_' + index"
                                class="block text-sm font-medium text-gray-700"
                                >Product*</label
                            >
                            <select
                                v-model="item.product_id"
                                :id="'product_id_' + index"
                                class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                required
                                @change="updateAvailableVariants(index)"
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
                                class="block text-sm font-medium text-gray-700"
                                >Variant*</label
                            >
                            <select
                                v-model="item.variant"
                                :id="'variant_' + index"
                                class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                required
                                @change="populateVariantDetails(index)"
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

                    <!-- Available Inventory -->
                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h4 class="text-sm font-medium text-gray-600 mb-2">
                            Available Inventory
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label
                                    :for="'available_bottles_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Available Bottles</label
                                >
                                <input
                                    v-model.number="item.available_bottles"
                                    :id="'available_bottles_' + index"
                                    type="number"
                                    min="0"
                                    placeholder="Available bottles"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    title="Total bottles available in stock"
                                />
                            </div>
                            <div>
                                <label
                                    :for="'available_boxes_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Available Boxes</label
                                >
                                <input
                                    v-model.number="item.available_boxes"
                                    :id="'available_boxes_' + index"
                                    type="number"
                                    min="0"
                                    placeholder="Available boxes"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    title="Available boxes (total available bottles ÷ bottles per box)"
                                />
                            </div>
                            <div>
                                <label
                                    :for="'bottles_per_box_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Bottles per Box</label
                                >
                                <input
                                    v-model.number="item.bottles_per_box"
                                    :id="'bottles_per_box_' + index"
                                    type="number"
                                    min="0"
                                    placeholder="Bottles per box"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    title="Number of bottles per box (from product data)"
                                />
                            </div>
                            <div>
                                <label
                                    :for="'free_bottles_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Free Bottles</label
                                >
                                <input
                                    v-model.number="item.free_bottles"
                                    :id="'free_bottles_' + index"
                                    type="number"
                                    min="0"
                                    placeholder="Free bottles"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    title="Free bottles included (from product data)"
                                />
                            </div>
                            <div>
                                <label
                                    :for="'unit_price_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Purchase Unit Price</label
                                >
                                <input
                                    v-model.number="item.unit_price"
                                    :id="'unit_price_' + index"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="Purchase unit price"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    title="Cost per bottle (from product data)"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Add Sale -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-600 mb-2">
                            Add Sale
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label
                                    :for="'boxes_sold_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Boxes Sold*</label
                                >
                                <input
                                    v-model.number="item.boxes_sold"
                                    :id="'boxes_sold_' + index"
                                    type="number"
                                    min="0"
                                    :max="item.available_boxes"
                                    placeholder="Enter boxes sold"
                                    class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                    :class="{
                                        'border-red-500':
                                            item.errors?.boxes_sold,
                                    }"
                                    required
                                    @input="calculateItemTotal(index)"
                                />
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
                                    class="block text-sm font-medium text-gray-700"
                                    >Sell Unit Price*</label
                                >
                                <input
                                    v-model.number="item.new_unit_price"
                                    :id="'new_unit_price_' + index"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="Enter sell unit price"
                                    class="mt-1 block w-full rounded-lg border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
                                    required
                                    @input="calculateItemTotal(index)"
                                />
                            </div>
                            <div>
                                <label
                                    :for="'total_quantity_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Total Bottles*</label
                                >
                                <input
                                    v-model.number="item.total_quantity"
                                    :id="'total_quantity_' + index"
                                    type="number"
                                    min="0"
                                    placeholder="Total bottles"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    title="Total bottles sold (boxes sold × bottles per box)"
                                />
                            </div>
                            <div>
                                <label
                                    :for="'total_price_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Total Price</label
                                >
                                <input
                                    v-model.number="item.total_price"
                                    :id="'total_price_' + index"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="Total price"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    title="Total price (total bottles × sell unit price)"
                                />
                            </div>
                            <div>
                                <label
                                    :for="'profit_' + index"
                                    class="block text-sm font-medium text-gray-700"
                                    >Profit</label
                                >
                                <input
                                    v-model.number="item.profit"
                                    :id="'profit_' + index"
                                    type="number"
                                    step="0.01"
                                    placeholder="Profit"
                                    class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-100 shadow-sm cursor-not-allowed"
                                    readonly
                                    :class="
                                        item.profit >= 0
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                    title="Profit (total bottles × (sell unit price - purchase unit price))"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <div class="flex justify-end mt-4">
                        <button
                            v-if="saleForm.items.length > 1"
                            @click="removeItem(index)"
                            class="text-red-600 hover:text-red-800 flex items-center"
                        >
                            <svg
                                class="w-5 h-5 mr-1"
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
                            Remove
                        </button>
                    </div>
                </div>
                <button
                    @click="addItem"
                    class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200 flex items-center"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    Add Item
                </button>
            </div>

            <div class="flex justify-end mt-6">
                <button
                    @click="createSale"
                    :disabled="hasErrors"
                    class="px-6 py-3 text-white rounded-lg transition duration-200"
                    :class="
                        hasErrors
                            ? 'bg-gray-400 cursor-not-allowed'
                            : 'bg-blue-600 hover:bg-blue-700'
                    "
                >
                    Create Sale
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
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

defineOptions({
    layout: Layout,
});

const filteredProducts = computed(() => {
    console.log("Filtering products with:", {
        category_id: saleForm.value.category_id,
        brand_id: saleForm.value.brand_id,
        supplier_id: saleForm.value.supplier_id,
    });
    const filtered = props.products.filter((product) => {
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
    console.log("Filtered products:", filtered);
    return filtered;
});

const hasErrors = computed(() => {
    return saleForm.value.items.some(
        (item) => item.errors && Object.keys(item.errors).length > 0
    );
});

const filterProducts = () => {
    console.log("filterProducts called");
    saleForm.value.items.forEach((item, index) => {
        item.product_id = null;
        item.variant = null;
        item.availableVariants = [];
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
            console.log(
                `Variants for product ${item.product_id}:`,
                item.availableVariants
            );
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

const getAvailableQuantity = (
    productId: number | null,
    variant: string | null
) => {
    if (!productId || !variant) return 0;
    const product = props.products.find((p) => p.id === productId);
    if (product && product.metadata && product.metadata.variants) {
        const variantData = product.metadata.variants.find(
            (v) => v.variant === variant
        );
        return variantData ? variantData.quantity : 0;
    }
    return 0;
};

const populateVariantDetails = (index: number) => {
    const item = saleForm.value.items[index];
    if (item.product_id && item.supplier_id && item.variant) {
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
                item.new_unit_price = variantData.unit_price; // Initialize with purchase price
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
    item.errors = item.errors || {};
    if (item.boxes_sold > item.available_boxes) {
        item.errors.boxes_sold = `Cannot exceed available boxes: ${item.available_boxes}`;
    } else {
        delete item.errors.boxes_sold;
    }
    item.total_quantity = item.boxes_sold * item.bottles_per_box;
    item.total_price = item.total_quantity * item.new_unit_price;
    item.profit = item.total_quantity * (item.new_unit_price - item.unit_price);
};

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

const createSale = () => {
    if (hasErrors.value) {
        alert("Please correct all errors before submitting.");
        return;
    }
    console.log("Submitting sale:", saleForm.value);
    router.post("/sales/store", saleForm.value, {
        onSuccess: () => {
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
            console.log("Sale created successfully");
        },
        onError: (errors) => {
            console.error("Sale creation errors:", errors);
            alert("Failed to create sale: " + JSON.stringify(errors));
        },
    });
};

// Watch for changes in products prop to debug
watch(
    () => props.products,
    (newProducts) => {
        console.log("Products prop updated:", newProducts);
    },
    { immediate: true }
);

// Watch for changes in filteredProducts to debug
watch(
    () => filteredProducts.value,
    (newFilteredProducts) => {
        console.log("Filtered products updated:", newFilteredProducts);
    },
    { deep: true }
);

console.log("CreateSale.vue component loaded");
</script>

<style scoped>
input,
select,
textarea {
    padding: 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
}

.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(59, 130, 246, 0.2);
}

button:hover svg {
    transform: scale(1.1);
    transition: transform 0.2s;
}

.border-red-500 {
    border-color: #ef4444 !important;
}
</style>
```
