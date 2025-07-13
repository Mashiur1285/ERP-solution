<template>
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">
            Create Shop
        </h1>

        <!-- Shop Creation Form -->
        <div class="space-y-6 mb-12 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-800">Add New Shop</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label
                        for="shop_name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Shop Name*
                    </label>
                    <input
                        v-model="shopForm.shop_name"
                        id="shop_name"
                        type="text"
                        placeholder="Enter shop name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    />
                </div>
                <div>
                    <label
                        for="owner_name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Owner Name
                    </label>
                    <input
                        v-model="shopForm.owner_name"
                        id="owner_name"
                        type="text"
                        placeholder="Enter owner name"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="shop_address"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Shop Address
                    </label>
                    <input
                        v-model="shopForm.shop_address"
                        id="shop_address"
                        type="text"
                        placeholder="Enter shop address"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="phone_number"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Phone Number*
                    </label>
                    <input
                        v-model="shopForm.phone_number"
                        id="phone_number"
                        type="text"
                        placeholder="Enter phone number"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                        required
                    />
                </div>
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Email
                    </label>
                    <input
                        v-model="shopForm.email"
                        id="email"
                        type="email"
                        placeholder="Enter email"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="website"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Website
                    </label>
                    <input
                        v-model="shopForm.website"
                        id="website"
                        type="url"
                        placeholder="Enter website"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="national_id"
                        class="block text-sm font-medium text-gray-700"
                    >
                        National ID
                    </label>
                    <input
                        v-model="shopForm.national_id"
                        id="national_id"
                        type="text"
                        placeholder="Enter national ID"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="trade_license"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Trade License
                    </label>
                    <input
                        v-model="shopForm.trade_license"
                        id="trade_license"
                        type="text"
                        placeholder="Enter trade license"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
                <div>
                    <label
                        for="tax_id"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Tax ID
                    </label>
                    <input
                        v-model="shopForm.tax_id"
                        id="tax_id"
                        type="text"
                        placeholder="Enter tax ID"
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    />
                </div>
            </div>
            <div>
                <label
                    for="notes"
                    class="block text-sm font-medium text-gray-700"
                >
                    Notes
                </label>
                <textarea
                    v-model="shopForm.notes"
                    id="notes"
                    placeholder="Enter any additional notes"
                    class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
                    rows="4"
                ></textarea>
            </div>
            <div class="flex justify-end mt-6">
                <button
                    @click="submitShop"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
                >
                    Create Shop
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
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
}

const shopForm = ref<ShopForm>({
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

defineOptions({
    layout: Layout,
});

const submitShop = () => {
    router.post("/shops/store", shopForm.value, {
        onSuccess: () => {
            shopForm.value = {
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
            };
        },
        onError: (errors) => {
            console.error("Shop creation errors:", errors);
        },
    });
};

console.log("CreateShop.vue component loaded");
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
        0 10px 10px -5px rgb(142, 173, 200);
}
</style>
