<template>
  <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Deposit Management</h1>

    <!-- Add Deposit Form -->
    <div class="space-y-6 mb-8 bg-gray-100 p-6 rounded-lg">
      <h2 class="text-2xl font-semibold text-gray-800">Add Deposit</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier*</label>
          <select
            v-model="depositForm.supplier_id"
            id="supplier_id"
            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
            required
          >
            <option value="" disabled>Select a supplier</option>
            <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
              {{ supplier.company_name }}
            </option>
          </select>
        </div>
        <div>
          <label for="balance_deposited" class="block text-sm font-medium text-gray-700">Deposit Amount (TK)*</label>
          <input
            v-model="depositForm.balance_deposited"
            type="number"
            step="0.01"
            id="balance_deposited"
            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
            required
          />
        </div>
      </div>
      <div class="flex justify-end">
        <button
          @click="submitDeposit"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
        >
          Add Deposit
        </button>
      </div>
    </div>

    <!-- Purchase Products Form -->
    <div class="space-y-6 mb-12 bg-gray-100 p-6 rounded-lg">
      <h2 class="text-2xl font-semibold text-gray-800">Purchase Products</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="purchase_supplier_id" class="block text-sm font-medium text-gray-700">Supplier*</label>
          <select
            v-model="productForm.supplier_id"
            id="purchase_supplier_id"
            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
            required
          >
            <option value="" disabled>Select a supplier</option>
            <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
              {{ supplier.company_name }}
            </option>
          </select>
        </div>
      </div>
      <div v-for="(variant, index) in productForm.variants" :key="index" class="space-y-4 border-t pt-4 mt-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
          <div>
            <label :for="'variant_' + index" class="block text-sm font-medium text-gray-700">Variant*</label>
            <input
              v-model="variant.variant"
              placeholder="EX: 500 ml"
              type="text"
              :id="'variant_' + index"
              class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
              required
            />
          </div>
          <div>
            <label :for="'quantity_' + index" class="block text-sm font-medium text-gray-700">Quantity*</label>
            <input
              v-model="variant.quantity"
              type="number"
              :id="'quantity_' + index"
              class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
              required
            />
          </div>
          <div>
            <label :for="'boxes_' + index" class="block text-sm font-medium text-gray-700">Boxes*</label>
            <input
              v-model="variant.bottles_per_box"
              type="number"
              :id="'boxes_' + index"
              class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
              required
            />
          </div>
          <div>
            <label :for="'free_bottles_' + index" class="block text-sm font-medium text-gray-700">Free Bottles</label>
            <input
              v-model="variant.free_bottles"
              type="number"
              :id="'free_bottles_' + index"
              class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
            />
          </div>
          <div>
            <label :for="'price_' + index" class="block text-sm font-medium text-gray-700">Price in TK*</label>
            <input
              v-model="variant.price"
              type="number"
              step="0.01"
              :id="'price_' + index"
              class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200"
              required
            />
          </div>
        </div>
        <div class="flex justify-end">
          <button
            v-if="productForm.variants.length > 1"
            @click="removeVariant(index)"
            class="text-red-600 hover:text-red-800 text-sm"
          >
            Remove Variant
          </button>
        </div>
      </div>
      <div class="flex justify-between mt-4">
        <button
          @click="addVariant"
          class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-200"
        >
          Add Variant
        </button>
        <button
          @click="submitProduct"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
        >
          Add Purchase
        </button>
      </div>
    </div>

    <!-- Deposit History -->
    <div class="space-y-6">
      <h2 class="text-2xl font-semibold text-gray-800">Deposit History</h2>
      <table class="min-w-full divide-y divide-gray-400">
        <thead class="bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Supplier
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Deposit Amount (TK)
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Used Money (TK)
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Remaining Amount (TK)
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Date
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="deposit in depositHistory" :key="deposit.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ deposit.name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ deposit.deposit_amount }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">
                {{
                  deposit.deposit_amount != null && deposit.remaining_amount != null
                    ? deposit.deposit_amount - deposit.remaining_amount
                    : "-"
                }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ deposit.remaining_amount ?? "-" }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ new Date(deposit.date).toLocaleString() }}</div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Layout from '../../Layout.vue';

interface Supplier {
  id: number;
  company_name: string;
}

interface Deposit {
  id: number;
  name: string;
  deposit_amount: number;
  used_money: number;
  remaining_amount: number;
  date: string;
}

interface ProductVariant {
  variant: string;
  quantity: number;
  bottles_per_box: number;
  free_bottles: number;
  price: number;
}

const props = defineProps<{
  suppliers: Supplier[];
  depositHistory: Deposit[];
}>();

defineOptions({
  layout: Layout,
});

const depositForm = ref({
  supplier_id: '',
  balance_deposited: 0,
});

const productForm = ref({
  supplier_id: '',
  variants: [{ variant: '', quantity: 0, bottles_per_box: 0, free_bottles: 0, price: 0 }],
});

const submitDeposit = () => {
  console.log('Submitting deposit:', depositForm.value);
  router.post('/deposits/store', depositForm.value, {
    onSuccess: () => {
      depositForm.value = { supplier_id: '', balance_deposited: 0 };
    },
    onError: (errors) => {
      console.error('Deposit submission errors:', errors);
    },
  });
};

const addVariant = () => {
  productForm.value.variants.push({ variant: '', quantity: 0, bottles_per_box: 0, free_bottles: 0, price: 0 });
};

const removeVariant = (index: number) => {
  productForm.value.variants.splice(index, 1);
};

const submitProduct = () => {
  console.log('Submitting product:', productForm.value);
  router.post('/products/store', productForm.value, {
    onSuccess: () => {
      productForm.value = { supplier_id: '', variants: [{ variant: '', quantity: 0, bottles_per_box: 0, free_bottles: 0, price: 0 }] };
    },
    onError: (errors) => {
      console.error('Product submission errors:', errors);
    },
  });
};

console.log('Deposit.vue component loaded');
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
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgb(142, 173, 200);
}

table {
  border-collapse: separate;
  border-spacing: 0;
  border-radius: 0.5rem;
  overflow: hidden;
}

thead th:first-child {
  border-top-left-radius: 0.5rem;
}

thead th:last-child {
  border-top-right-radius: 0.5rem;
}

tbody tr:last-child td:first-child {
  border-bottom-left-radius: 0.5rem;
}

tbody tr:last-child td:last-child {
  border-bottom-right-radius: 0.5rem;
}
</style>