<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ t('addShop', { default: 'Add New Shop' }) }}
                </h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <form @submit.prevent="submitShop" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Shop Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('shopName') }}*
                            </label>
                            <input
                                v-model="form.shop_name"
                                type="text"
                                :placeholder="t('shopName')"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                                :class="{'border-red-400': errors.shop_name}"
                            />
                            <p v-if="errors.shop_name" class="mt-1 text-xs text-red-500">{{ errors.shop_name[0] }}</p>
                        </div>
                        
                        <!-- Road -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('road') }}
                            </label>
                            <input
                                v-model="form.road"
                                type="text"
                                :placeholder="t('road')"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                                :class="{'border-red-400': errors.road}"
                            />
                            <p v-if="errors.road" class="mt-1 text-xs text-red-500">{{ errors.road[0] }}</p>
                        </div>
                        
                        <!-- Owner Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('ownerName', { default: 'Owner Name' }) }}
                            </label>
                            <input
                                v-model="form.owner_name"
                                type="text"
                                :placeholder="t('ownerName', { default: 'Owner Name' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                            />
                        </div>
                        
                        <!-- Phone Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('phoneNumber', { default: 'Phone Number' }) }}*
                            </label>
                            <input
                                v-model="form.phone_number"
                                type="tel"
                                :placeholder="t('phoneNumber', { default: 'Phone Number' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                                :class="{'border-red-400': errors.phone_number}"
                            />
                            <p v-if="errors.phone_number" class="mt-1 text-xs text-red-500">{{ errors.phone_number[0] }}</p>
                        </div>

                        <!-- Shop Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('shopAddress', { default: 'Shop Address' }) }}
                            </label>
                            <input
                                v-model="form.shop_address"
                                type="text"
                                :placeholder="t('shopAddress', { default: 'Shop Address' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                            />
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('email', { default: 'Email' }) }}
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                :placeholder="t('email', { default: 'Email' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                                :class="{'border-red-400': errors.email}"
                            />
                            <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
                        </div>

                        <!-- Website -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('website', { default: 'Website' }) }}
                            </label>
                            <input
                                v-model="form.website"
                                type="url"
                                :placeholder="t('website', { default: 'Website' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                            />
                        </div>

                        <!-- National ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('nationalId', { default: 'National ID' }) }}
                            </label>
                            <input
                                v-model="form.national_id"
                                type="text"
                                :placeholder="t('nationalId', { default: 'National ID' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                            />
                        </div>

                        <!-- Trade License -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('tradeLicense', { default: 'Trade License' }) }}
                            </label>
                            <input
                                v-model="form.trade_license"
                                type="text"
                                :placeholder="t('tradeLicense', { default: 'Trade License' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                            />
                        </div>

                        <!-- Tax ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ t('taxId', { default: 'Tax ID' }) }}
                            </label>
                            <input
                                v-model="form.tax_id"
                                type="text"
                                :placeholder="t('taxId', { default: 'Tax ID' })"
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                            />
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ t('notes', { default: 'Notes' }) }}
                        </label>
                        <textarea
                            v-model="form.notes"
                            rows="2"
                            :placeholder="t('notes', { default: 'Notes' })"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-sm"
                        ></textarea>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button
                    @click="$emit('close')"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    :disabled="isSubmitting"
                >
                    {{ t('cancel', { default: 'Cancel' }) }}
                </button>
                <button
                    @click="submitShop"
                    class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2"
                    :disabled="isSubmitting"
                >
                    <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    {{ isSubmitting ? t('processing', { default: 'Processing...' }) : t('createShop', { default: 'Create Shop' }) }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue';
import axios from 'axios';

const props = defineProps<{
    show: boolean;
    t: (key: string, params?: Record<string, any>) => string;
    initialRoad?: string;
}>();

const emit = defineEmits(['close', 'shop-created']);

const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});

const form = reactive({
    shop_name: '',
    road: '',
    owner_name: '',
    shop_address: '',
    phone_number: '',
    email: '',
    website: '',
    national_id: '',
    trade_license: '',
    tax_id: '',
    notes: '',
});

watch(() => props.show, (newVal) => {
    if (newVal) {
        // Reset form when opened
        form.shop_name = '';
        form.road = props.initialRoad || '';
        form.owner_name = '';
        form.shop_address = '';
        form.phone_number = '';
        form.email = '';
        form.website = '';
        form.national_id = '';
        form.trade_license = '';
        form.tax_id = '';
        form.notes = '';
        errors.value = {};
    }
});

const submitShop = async () => {
    isSubmitting.value = true;
    errors.value = {};

    try {
        const response = await axios.post('/shops/store', form, {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (response.data.success) {
            emit('shop-created', response.data.shop);
            emit('close');
        }
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to create shop', error);
            alert('Failed to create shop. Please try again.');
        }
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 10px;
}
</style>
