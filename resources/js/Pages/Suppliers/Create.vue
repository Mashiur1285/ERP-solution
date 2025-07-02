<template>
  <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Add New Supplier</h1>
    <div class="space-y-8">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name*</label>
          <input v-model="form.company_name" type="text" id="company_name" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" required />
        </div>
        <div>
          <label for="branch_name" class="block text-sm font-medium text-gray-700">Branch Name</label>
          <input v-model="form.branch_name" type="text" id="branch_name" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" />
        </div>
        <div>
          <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number*</label>
          <input v-model="form.phone_number" type="tel" id="phone_number" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" required />
        </div>
        <div>
          <label for="emergency_phone_number" class="block text-sm font-medium text-gray-700">Emergency Phone Number</label>
          <input v-model="form.emergency_phone_number" type="tel" id="emergency_phone_number" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" />
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input v-model="form.email" type="email" id="email" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" required />
        </div>
        <div>
          <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
          <input v-model="form.website" type="url" id="website" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" />
        </div>
        <div>
          <label for="city" class="block text-sm font-medium text-gray-700">City</label>
          <input v-model="form.city" type="text" id="city" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" required />
        </div>
        <div>
          <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
          <input v-model="form.country" type="text" id="country" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" required />
        </div>
      </div>
      <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Address*</label>
        <textarea v-model="form.address" id="address" class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600 focus:ring-opacity-50 transition duration-200" rows="5"></textarea>
      </div>
      <div class="flex justify-end space-x-4">
        <Link href="/suppliers/index" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition duration-200">Cancel</Link>
        <button @click="submitForm" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">Save Supplier</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import Layout from '../../Layout.vue';

defineOptions({
  layout: Layout,
});

const form = ref({
  company_name: '',
  branch_name: '',
  phone_number: '',
  emergency_phone_number: '',
  email: '',
  address: '',
  city: '',
  country: '',
  website: '',
});


const submitForm = () => {
  console.log('Submitting form with data:', form.value);
  router.post('/suppliers/store', form.value, {
    onSuccess: () => {
      router.visit('/suppliers/index');
    },
    onError: (errors) => {
      console.error('Form submission errors:', errors);
    }
  });
};
</script>

<style scoped>
input, textarea {
  padding: 0.75rem;
  font-size: 0.875rem;
  line-height: 1.5;
}

.shadow-xl:hover {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgb(175, 185, 194);
}
</style>