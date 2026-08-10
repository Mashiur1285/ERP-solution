<template>
    <div v-if="show" class="fixed inset-0 z-[210] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="px-4 sm:px-5 py-3.5 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 min-w-0">
                    <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="truncate">{{ t('addShop', { default: 'Add New Shop' }) }}</span>
                </h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-lg hover:bg-gray-200 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-4 sm:p-5 overflow-y-auto custom-scrollbar flex-1">
                <form @submit.prevent="submitShop" class="space-y-4">
                    <!-- Shop Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ t('shopName') }}*
                        </label>
                        <input
                            ref="shopNameInput"
                            v-model="form.shop_name"
                            type="text"
                            maxlength="30"
                            :placeholder="t('shopName')"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-base"
                            :class="{ 'border-red-400': errors.shop_name }"
                        />
                        <p v-if="errors.shop_name" class="mt-1 text-xs text-red-500">{{ errors.shop_name[0] }}</p>
                    </div>

                    <!-- Phone Number (optional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ t('phoneNumber', { default: 'Phone Number' }) }}
                            <span class="text-gray-400 font-normal text-xs ml-1">({{ t('optional', { default: 'optional' }) }})</span>
                        </label>
                        <input
                            v-model="form.phone_number"
                            type="tel"
                            inputmode="tel"
                            maxlength="20"
                            :placeholder="t('phoneNumber', { default: 'Phone Number' })"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-base"
                            :class="{ 'border-red-400': errors.phone_number }"
                        />
                        <p v-if="errors.phone_number" class="mt-1 text-xs text-red-500">{{ errors.phone_number[0] }}</p>
                    </div>

                    <!-- Road: pre-filled from the sale screen, but free to type a new one.
                         A road only exists as a label on its shops, so typing a new name
                         here is exactly how a new road gets created. -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ t('road') }}
                            <span class="text-gray-400 font-normal text-xs ml-1">({{ t('optional', { default: 'optional' }) }})</span>
                        </label>
                        <input
                            v-model="form.road"
                            type="text"
                            list="quick-shop-road-options"
                            maxlength="100"
                            :placeholder="t('road')"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 outline-none transition-all text-base"
                            :class="{ 'border-red-400': errors.road }"
                        />
                        <datalist id="quick-shop-road-options">
                            <option v-for="road in selectableRoads" :key="road" :value="road" />
                        </datalist>
                        <p v-if="errors.road" class="mt-1 text-xs text-red-500">{{ errors.road[0] }}</p>
                        <p v-else-if="isNewRoad" class="mt-1 text-xs text-orange-600">
                            {{ t('newRoadWillBeCreated', { default: 'New road — it will appear in the road list once this shop is saved.' }) }}
                        </p>
                    </div>

                    <!-- Submit on Enter -->
                    <button type="submit" class="hidden"></button>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-4 sm:px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                <button
                    @click="$emit('close')"
                    class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    :disabled="isSubmitting"
                >
                    {{ t('cancel', { default: 'Cancel' }) }}
                </button>
                <button
                    @click="submitShop"
                    class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-white bg-orange-500 rounded-lg hover:bg-orange-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2"
                    :disabled="isSubmitting || !form.shop_name.trim()"
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
import { ref, reactive, computed, watch, nextTick } from 'vue';
import axios from 'axios';

const props = defineProps<{
    show: boolean;
    t: (key: string, params?: Record<string, any>) => string;
    initialRoad?: string;
    /** Existing road names, so the user picks one instead of retyping (and mistyping) it. */
    roadOptions?: string[];
}>();

const emit = defineEmits(['close', 'shop-created']);

const isSubmitting = ref(false);
const errors = ref<Record<string, string[]>>({});
const shopNameInput = ref<HTMLInputElement | null>(null);

const form = reactive({
    shop_name: '',
    phone_number: '',
    road: '',
});

// "__UNASSIGNED__" is the sale screen's placeholder for shops with no road — it
// must never be saved as an actual road name.
const assignedRoad = computed(() =>
    props.initialRoad && props.initialRoad !== '__UNASSIGNED__' ? props.initialRoad : ''
);

const selectableRoads = computed(() => props.roadOptions ?? []);

const isNewRoad = computed(() => {
    const road = form.road.trim().toLowerCase();
    if (!road) return false;
    return !selectableRoads.value.some((r) => r.trim().toLowerCase() === road);
});

watch(() => props.show, async (newVal) => {
    if (newVal) {
        form.shop_name = '';
        form.phone_number = '';
        form.road = assignedRoad.value;
        errors.value = {};
        await nextTick();
        shopNameInput.value?.focus();
    }
});

const submitShop = async () => {
    if (isSubmitting.value || !form.shop_name.trim()) return;

    isSubmitting.value = true;
    errors.value = {};

    try {
        const response = await axios.post(
            '/shops/store',
            {
                shop_name: form.shop_name.trim(),
                phone_number: form.phone_number.trim(),
                road: form.road.trim(),
            },
            { headers: { Accept: 'application/json' } }
        );

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
