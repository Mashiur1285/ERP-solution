<template>
    <div class="p-4 bg-gray-100 min-h-screen" :class="{ 'bangla-font': lang === 'bn' }">
        <!-- Toast -->
        <div v-if="toast.show" class="fixed top-20 right-4 z-[200] animate-slide-in print:hidden">
            <div class="px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium flex items-center space-x-2"
                :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'">
                <span>{{ toast.message }}</span>
                <button @click="toast.show = false" class="ml-2 opacity-70 hover:opacity-100">&times;</button>
            </div>
        </div>

        <!-- Create Product Modal -->
        <div v-if="showCreateProductModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 p-4 print:hidden">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ t('createNewProduct') }}</h3>
                    <button class="p-2 rounded-full hover:bg-gray-100" @click="showCreateProductModal = false">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('productName') }}*</label>
                        <input v-model="newProduct.name" class="w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-green-500 focus:ring-2 focus:ring-green-200" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('productImage') }}</label>
                        <div class="rounded-xl border-2 border-dashed border-green-200 bg-green-50/40 p-4">
                            <div class="flex items-start gap-4">
                                <div class="h-20 w-20 overflow-hidden rounded-xl border border-gray-200 bg-white flex items-center justify-center flex-shrink-0">
                                    <img
                                        v-if="newProduct.imagePreviewUrl"
                                        :src="newProduct.imagePreviewUrl"
                                        alt="Product preview"
                                        class="h-full w-full object-cover"
                                    />
                                    <svg
                                        v-else
                                        class="w-8 h-8 text-gray-300"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-10h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <input
                                        id="lift-product-image"
                                        type="file"
                                        accept="image/*"
                                        class="hidden"
                                        @change="handleNewProductImageChange"
                                    />
                                    <label
                                        for="lift-product-image"
                                        class="inline-flex cursor-pointer items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                                    >
                                        {{ newProduct.imageFile ? t('changeProductImage') : t('uploadProductImage') }}
                                    </label>
                                    <p v-if="newProduct.imageFile" class="mt-2 text-sm text-gray-600 break-all">
                                        {{ newProduct.imageFile.name }}
                                    </p>
                                    <button
                                        v-if="newProduct.imageFile"
                                        type="button"
                                        class="mt-3 text-sm font-medium text-red-600 hover:text-red-700"
                                        @click="removeNewProductImage"
                                    >
                                        {{ t('removeImage') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('category') }}</label>
                            <select v-model="newProduct.category_id" class="w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-green-500 focus:ring-2 focus:ring-green-200">
                                <option value="">{{ t('selectCategory') }}</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ t('brand') }}</label>
                            <select v-model="newProduct.brand_id" class="w-full rounded-lg border-2 border-gray-200 px-3 py-2 focus:border-green-500 focus:ring-2 focus:ring-green-200">
                                <option value="">{{ t('selectBrand') }}</option>
                                <option v-for="b in brands" :key="b.id" :value="b.id">{{ b.brand_name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50" @click="showCreateProductModal = false">{{ t('cancel') }}</button>
                    <button class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 shadow" @click="createProduct" :disabled="!newProduct.name">{{ t('save') }}</button>
                </div>
            </div>
        </div>

        <!-- Quick Deposit Modal -->
        <div v-if="showQuickDepositModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 print:hidden">
            <div class="bg-white rounded-xl p-5 max-w-md w-full mx-4 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ t('quickDeposit') }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ t('quickDepositPrompt') }}</p>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t('supplier') }}</span>
                        <span class="font-bold">{{ selectedSupplier?.company_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t('currentDeposit') }}</span>
                        <span class="font-bold">৳{{ toBengaliNumber(selectedSupplier?.remaining_deposit ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-blue-200 pt-2">
                        <span class="text-gray-600">{{ t('depositShortfall') }}</span>
                        <span class="font-bold text-blue-600">৳{{ toBengaliNumber(shortfallAmount, 2) }}</span>
                    </div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200 mb-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t('totalProducts') }}</span>
                        <span class="font-bold">{{ validItems.length }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ t('totalCost') }}</span>
                        <span class="font-bold text-green-600">৳{{ toBengaliNumber(grandTotal, 2) }}</span>
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button @click="showQuickDepositModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">{{ t('cancel') }}</button>
                    <button @click="submitQuickDeposit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2" :disabled="isQuickDepositLoading">
                        <svg v-if="isQuickDepositLoading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        {{ isQuickDepositLoading ? t('processing') : t('depositAndLift') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div v-if="showConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 print:hidden">
            <div class="bg-white rounded-xl p-5 max-w-md w-full mx-4 shadow-xl">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ t('confirmLift') }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ t('confirmLiftPrompt') }}</p>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200 mb-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">{{ t('totalProducts') }}</span><span class="font-bold">{{ liftItems.filter(i => i.variants.some(v => v.number_of_cases > 0)).length }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">{{ t('totalCost') }}</span><span class="font-bold text-green-600">৳{{ toBengaliNumber(grandTotal, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">{{ t('remainingDeposit') }}</span>
                        <span class="font-bold" :class="remainingDeposit >= 0 ? 'text-green-600' : 'text-red-600'">৳{{ toBengaliNumber(remainingDeposit, 2) }}</span></div>
                    </div>
                <div class="flex justify-end space-x-3">
                    <button @click="showConfirmModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">{{ t('cancel') }}</button>
                    <button @click="submitLift" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700" :disabled="isLoading || remainingDeposit < 0">
                        {{ isLoading ? t('processing') : t('confirm') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3 print:hidden bg-gradient-to-r from-green-600 to-green-500 rounded-xl px-4 py-3 shadow-sm">
            <h1 class="text-xl sm:text-2xl font-semibold text-white flex items-center">
                <div class="p-2 mr-3 bg-white/20 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                {{ t('liftManagement') }}
            </h1>
            <div class="flex space-x-2 self-start sm:self-auto">
                <button @click="lang = 'en'; saveLang()" :class="['px-3 py-1.5 rounded-md font-medium text-sm transition-colors', lang === 'en' ? 'bg-white text-green-600' : 'bg-white/20 text-white hover:bg-white/30']">English</button>
                <button @click="lang = 'bn'; saveLang()" :class="['px-3 py-1.5 rounded-md font-medium text-sm transition-colors', lang === 'bn' ? 'bg-white text-green-600' : 'bg-white/20 text-white hover:bg-white/30']">বাংলা</button>
            </div>
        </div>

        <!-- POS Two-Column Layout -->
        <div class="flex flex-col lg:flex-row gap-4 lg:items-start">
            <!-- LEFT SIDE -->
            <div class="flex-1 min-w-0 space-y-4 print:hidden">
                <!-- Step 1: Supplier Selection -->
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h2 class="text-base font-semibold text-gray-800 mb-3 flex items-center">
                        <span class="w-6 h-6 bg-green-600 text-white rounded-full text-xs flex items-center justify-center mr-2">1</span>
                        {{ t('selectSupplier') }}
                    </h2>
                    <div class="relative" ref="supplierDDRef">
                        <input v-model="supplierSearch" @focus="showSupplierDD = true" @input="showSupplierDD = true"
                            :placeholder="t('searchSupplier')"
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 bg-white focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all text-base"
                            :class="{ 'border-green-400 bg-green-50': selectedSupplier }" autocomplete="off" />
                        <div v-if="showSupplierDD && filteredSuppliers.length" class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto z-30">
                            <button v-for="s in filteredSuppliers" :key="s.id" type="button"
                                class="w-full text-left px-4 py-3 hover:bg-green-50 text-sm border-b border-gray-50 last:border-0"
                                @mousedown.prevent="selectSupplier(s)">
                                <span class="font-medium text-gray-800">{{ s.company_name }}</span>
                                <span class="float-right text-green-600 font-semibold">৳{{ toBengaliNumber(s.remaining_deposit, 2) }}</span>
                            </button>
                        </div>
                        <div v-if="showSupplierDD && !filteredSuppliers.length" class="absolute mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto z-30">
                            <div class="px-4 py-3 text-sm text-gray-400">{{ t('noResults') }}</div>
                        </div>
                    </div>
                    <!-- Supplier Tiles -->
                    <div class="flex flex-wrap gap-2 mt-3">
                        <button
                            v-for="s in props.suppliers"
                            :key="s.id"
                            type="button"
                            @click="selectSupplier(s); showSupplierDD = false"
                            :class="[
                                'px-3 py-1.5 rounded-full text-sm font-medium border transition-colors',
                                selectedSupplier?.id === s.id
                                    ? 'bg-green-600 text-white border-green-600'
                                    : 'bg-white text-gray-700 border-gray-300 hover:border-green-400 hover:text-green-600'
                            ]"
                        >
                            {{ s.company_name }}
                        </button>
                    </div>
                    <div v-if="selectedSupplier" class="mt-3 flex items-center justify-between bg-green-50 px-4 py-2 rounded-lg border border-green-200">
                        <span class="text-sm text-gray-700">{{ t('availableBalance') }}</span>
                        <span class="text-lg font-bold text-green-600">৳{{ toBengaliNumber(selectedSupplier.remaining_deposit, 2) }}</span>
                    </div>
                </div>

                <!-- Step 2: Product Selection & Variant Picker -->
                <div v-if="selectedSupplier" class="bg-white rounded-xl shadow-sm p-5">
                    <h2 class="text-base font-semibold text-gray-800 mb-3 flex items-center">
                        <span class="w-6 h-6 bg-green-600 text-white rounded-full text-xs flex items-center justify-center mr-2">2</span>
                        {{ t('addProducts') }}
                    </h2>

                    <!-- Search filter + Create button -->
                    <div class="flex gap-2 mb-3">
                        <div class="flex-1 relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input v-model="productSearch" @input="searchProducts"
                                :placeholder="t('searchProduct')"
                                class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 focus:ring-2 focus:ring-green-100 transition-all" />
                        </div>
                        <button @click="openCreateProductModal"
                            class="px-3 py-2 rounded-lg border border-green-200 text-green-600 hover:bg-green-50 flex items-center gap-1.5 text-sm font-medium flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ t('createNewProduct') }}
                        </button>
                    </div>

                    <!-- Product chips -->
                    <div v-if="isSearching" class="flex items-center gap-2 text-sm text-gray-400 py-3">
                        <svg class="w-4 h-4 animate-spin text-green-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        {{ t('loading') }}
                    </div>
                    <div v-else-if="productSearchResults.length" class="flex flex-wrap gap-2 max-h-[120px] overflow-y-auto pr-1">
                        <button v-for="p in productSearchResults" :key="p.id"
                            @click="selectProductForVariants(p)"
                            :class="['px-3 py-1.5 rounded-lg border text-sm font-medium transition-all',
                                activeProduct?.id === p.id
                                    ? 'bg-green-600 border-green-600 text-white shadow-sm'
                                    : liftItems.some(i => i.product_catalog_id === p.id)
                                        ? 'bg-green-50 border-green-300 text-green-700 hover:bg-green-100'
                                        : 'bg-white border-gray-200 text-gray-700 hover:border-green-300 hover:text-green-600'
                            ]">
                            {{ p.name }}
                            <span v-if="liftItems.some(i => i.product_catalog_id === p.id)" class="ml-1 text-xs">✓</span>
                        </button>
                    </div>
                    <div v-else class="text-sm text-gray-400 py-2 text-center">
                        {{ t('noResults') }}
                    </div>

                    <!-- Variant Picker -->
                    <div v-if="activeProduct" class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ activeProduct.name }}
                                <span class="ml-1.5 text-xs font-normal text-gray-400">— {{ t('selectVariantsToAdd') }}</span>
                            </p>
                            <button @click="closeVariantPicker" class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <label v-for="opt in variantOptions" :key="opt.value"
                                :class="['flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all select-none',
                                    variantSelections[opt.value]?.checked
                                        ? 'border-green-400 bg-green-50'
                                        : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'
                                ]">
                                <input type="checkbox"
                                    v-model="variantSelections[opt.value].checked"
                                    class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer flex-shrink-0" />
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ opt.label }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ variantSelections[opt.value]?.bottles_per_case ?? opt.bottles_per_case }} {{ t('bottlesPerCase') }}
                                        <span v-if="(variantSelections[opt.value]?.free_bottles_per_case ?? 0) > 0" class="text-green-500 ml-1">
                                            +{{ variantSelections[opt.value].free_bottles_per_case }} {{ t('free') }}
                                        </span>
                                    </p>
                                </div>
                            </label>

                            <!-- Custom variant tile -->
                            <label :class="['flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-all select-none col-span-2',
                                variantSelections['__custom__']?.checked
                                    ? 'border-green-400 bg-green-50'
                                    : 'border-dashed border-gray-300 bg-white hover:border-green-300 hover:bg-green-50/40'
                            ]">
                                <input type="checkbox"
                                    v-model="variantSelections['__custom__'].checked"
                                    class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer flex-shrink-0 mt-1" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800">+ Custom Variant</p>
                                    <div v-if="variantSelections['__custom__']?.checked" class="mt-2 space-y-2" @click.stop>
                                        <input
                                            v-model="variantSelections['__custom__'].customName"
                                            type="text"
                                            placeholder="e.g. 330ml, 750ml, 5L"
                                            class="w-full px-2.5 py-1.5 rounded-md border border-gray-200 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 bg-white"
                                        />
                                        <div class="flex gap-2">
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-400 mb-1">Bottles/Case</p>
                                                <input
                                                    v-model.number="variantSelections['__custom__'].bottles_per_case"
                                                    type="number" min="1"
                                                    class="w-full px-2.5 py-1.5 rounded-md border border-gray-200 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 bg-white"
                                                />
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-400 mb-1">Free/Case</p>
                                                <input
                                                    v-model.number="variantSelections['__custom__'].free_bottles_per_case"
                                                    type="number" min="0"
                                                    class="w-full px-2.5 py-1.5 rounded-md border border-gray-200 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 bg-white"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <p v-else class="text-xs text-gray-400 mt-0.5">Type any variant name</p>
                                </div>
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <button @click="addProductWithVariants"
                                :disabled="!hasCheckedVariants"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2 shadow-sm transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ t('addToCart') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Cart Items -->
                <div v-if="liftItems.length" class="space-y-3">
                    <div v-for="(item, itemIdx) in liftItems" :key="itemIdx" class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-400">
                        <!-- Product Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">{{ item.product_name }}</h3>
                                <p class="text-xs text-gray-400">{{ [item.category_name, item.brand_name].filter(Boolean).join(' / ') }}</p>
                            </div>
                            <button @click="removeItem(itemIdx)" class="p-2 rounded-full bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Variants Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('variantName') }}</th>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('numberOfCases') }}</th>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('caseBuyingPrice') }} (৳)</th>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('bottlesPerCase') }}</th>
                                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('freeBottlesPerCase') }}</th>
                                        <th class="text-right px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ t('totalCost') }}</th>
                                        <th class="w-8 px-2 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(v, vIdx) in item.variants" :key="vIdx"
                                        class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="px-2 py-2">
                                            <template v-if="isCustomVariant(v.variant)">
                                                <div class="flex items-center gap-1">
                                                    <input
                                                        v-model="v.variant"
                                                        type="text"
                                                        placeholder="Variant name"
                                                        class="w-full min-w-[100px] px-2 py-1.5 rounded-md border border-green-300 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 bg-green-50"
                                                    />
                                                    <button @click="v.variant = ''" class="text-gray-400 hover:text-gray-600 flex-shrink-0" title="Switch to preset">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <select v-model="v.variant"
                                                    class="w-full min-w-[120px] pl-2 pr-8 py-1.5 rounded-md border border-gray-200 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200 bg-white"
                                                    @change="onVariantSelect(itemIdx, vIdx)">
                                                    <option value="">{{ t('selectVariant') }}</option>
                                                    <option v-for="opt in variantOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                                                    <option value="__custom__">+ Custom</option>
                                                </select>
                                            </template>
                                        </td>
                                        <td class="px-2 py-2">
                                            <input v-model.number="v.number_of_cases" type="number" min="0"
                                                class="w-full px-2 py-1.5 rounded-md border border-gray-200 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200" />
                                        </td>
                                        <td class="px-2 py-2">
                                            <input v-model.number="v.case_buying_price" type="number" step="0.01" min="0"
                                                class="w-full px-2 py-1.5 rounded-md border border-gray-200 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200" />
                                        </td>
                                        <td class="px-2 py-2">
                                            <input v-model.number="v.bottles_per_case" type="number" min="1"
                                                class="w-full px-2 py-1.5 rounded-md border border-gray-200 text-sm bg-gray-100 focus:border-green-500 focus:ring-1 focus:ring-green-200" />
                                        </td>
                                        <td class="px-2 py-2">
                                            <input v-model.number="v.free_bottles_per_case" type="number" min="0" step="any"
                                                class="w-full px-2 py-1.5 rounded-md border border-gray-200 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-200" />
                                        </td>
                                        <td class="px-3 py-2 text-right">
                                            <span class="text-sm font-semibold text-green-600">
                                                ৳{{ toBengaliNumber(calcVariantCost(v), 2) }}
                                            </span>
                                            <div v-if="calcFreeBottles(v) > 0" class="text-xs text-green-500 mt-0.5">
                                                +{{ calcFreeBottles(v) }} {{ t('free') }}
                                            </div>
                                        </td>
                                        <td class="px-2 py-2 text-center">
                                            <button v-if="item.variants.length > 1"
                                                @click="removeVariant(itemIdx, vIdx)"
                                                class="p-1 rounded text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Variant Button -->
                        <button @click="addVariant(itemIdx)" class="mt-2 text-sm text-green-600 font-medium hover:text-green-800 flex items-center gap-1 px-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            {{ t('addVariant') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Invoice Panel -->
            <div class="w-full lg:w-[380px] lg:flex-shrink-0 lg:sticky lg:top-24 print:w-full print:static">
                <div id="printable-invoice" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <!-- Invoice Header -->
                    <div class="bg-green-600 text-white px-5 py-4 print:bg-white print:text-gray-800 print:border-b-2 print:border-gray-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-base font-bold">{{ t('liftInvoice') }}</h3>
                            </div>
                            <span class="text-xs bg-green-500 px-2 py-1 rounded-full print:bg-transparent print:text-gray-600 print:px-0">{{ currentDate }}</span>
                        </div>
                        <p v-if="selectedSupplier" class="text-green-200 text-xs mt-1 print:text-gray-600">{{ selectedSupplier.company_name }}</p>
                    </div>

                    <!-- Invoice Body -->
                    <div class="px-5 py-4 max-h-[calc(100vh-320px)] overflow-y-auto">
                        <!-- Empty State -->
                        <div v-if="!validItems.length" class="text-center py-8 text-gray-400">
                            <svg class="w-14 h-14 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="text-sm">{{ t('emptyInvoice') }}</p>
                        </div>

                        <!-- Line Items -->
                        <div v-else>
                            <div v-for="(item, idx) in validItems" :key="idx" class="mb-3">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ item.product_name }}</p>
                                <div v-for="(v, vi) in item.validVariants" :key="vi" class="flex justify-between py-1.5 border-b border-dashed border-gray-100 last:border-0 text-sm">
                                    <div>
                                        <span class="text-gray-800">{{ v.variant || 'Variant' }}</span>
                                        <span class="text-gray-400 text-xs ml-1">x{{ v.number_of_cases }} {{ t('case') }}</span>
                                        <span v-if="calcFreeBottles(v) > 0" class="text-green-500 text-xs block">+{{ calcFreeBottles(v) }} {{ t('free') }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-800">৳{{ toBengaliNumber(calcVariantCost(v), 2) }}</span>
                                </div>
                            </div>

                            <!-- Separator -->
                            <div class="border-t-2 border-dotted border-gray-300 my-3"></div>

                            <!-- Summary -->
                            <div class="space-y-1.5 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t('totalProducts') }}</span>
                                    <span class="font-medium">{{ validItems.length }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>{{ t('totalCases') }}</span>
                                    <span class="font-medium">{{ toBengaliNumber(totalCases) }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-gray-50/50 p-2 rounded-lg">
                                    <span class="text-xs text-gray-400 uppercase tracking-wider">{{ t('totalBottles') }}</span>
                                    <span class="font-bold text-gray-800">{{ toBengaliNumber(totalBottles) }}</span>
                                </div>
                                <div v-if="totalFreeBottles > 0" class="flex justify-between items-center bg-green-50/50 p-2 rounded-lg">
                                    <span class="text-xs text-green-600 uppercase tracking-wider">{{ t('totalFreeBottles') }}</span>
                                    <span class="font-medium text-green-600">+{{ toBengaliNumber(totalFreeBottles) }}</span>
                                </div>
                            </div>

                            <!-- Grand Total -->
                            <div class="border-t-2 border-dotted border-gray-300 mt-3 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-800">{{ t('totalCost') }}</span>
                                    <span class="text-xl font-bold text-green-600">৳{{ toBengaliNumber(grandTotal, 2) }}</span>
                                </div>
                            </div>

                            <!-- Remaining Deposit -->
                            <div v-if="selectedSupplier" class="mt-3 p-3 rounded-lg"
                                :class="remainingDeposit >= 0 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-xs text-gray-500">{{ t('remainingDeposit') }}</p>
                                        <p class="text-xs text-gray-400">{{ t('afterLift') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold" :class="remainingDeposit >= 0 ? 'text-green-600' : 'text-red-600'">
                                            ৳{{ toBengaliNumber(remainingDeposit, 2) }}
                                        </p>
                                        <p class="text-xs" :class="remainingDeposit >= 0 ? 'text-green-500' : 'text-red-500'">
                                            {{ remainingDeposit >= 0 ? t('sufficient') : t('insufficient') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Deposit Button (when shortfall) -->
                            <div v-if="selectedSupplier && remainingDeposit < 0" class="mt-2">
                                <button @click="showQuickDepositModal = true"
                                    class="w-full py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2 shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ t('addMissingDeposit') }} ৳{{ toBengaliNumber(shortfallAmount, 2) }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Footer -->
                    <div class="px-5 py-4 bg-gray-50 border-t border-gray-200 space-y-2 print:hidden">
                        <button @click="openConfirmModal"
                            class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all flex items-center justify-center space-x-2 shadow-md"
                            :disabled="isLoading || !validItems.length || !selectedSupplier">
                            <svg v-if="isLoading" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ isLoading ? t('processing') : editingCompletedLift ? t('updateLift') : t('confirmLift') }}</span>
                        </button>
                        <div class="flex gap-2">
                            <button v-if="!editingCompletedLift" @click="saveDraft"
                                class="flex-1 py-2.5 border-2 border-amber-300 rounded-lg text-amber-700 font-medium hover:bg-amber-50 transition-all flex items-center justify-center space-x-2 text-sm"
                                :disabled="isLoading || !selectedSupplier || !validItems.length">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h14v14H5V5zm3 0v4h8V5" />
                                </svg>
                                <span>{{ editingCompletedLift ? t('updateLift') : draftLiftId ? t('updateDraft') : t('saveDraft') }}</span>
                            </button>
                            <button @click="printInvoice"
                                class="flex-1 py-2.5 border-2 border-green-200 rounded-lg text-green-600 font-medium hover:bg-green-50 transition-all flex items-center justify-center space-x-2 text-sm"
                                :disabled="!validItems.length">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <span>{{ t('printInvoice') }}</span>
                            </button>
                            <button @click="resetAll"
                                class="flex-1 py-2.5 border-2 border-gray-300 rounded-lg text-gray-600 font-medium hover:bg-gray-100 transition-all flex items-center justify-center space-x-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>{{ t('resetForm') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";

interface Supplier {
    id: number;
    company_name: string;
    remaining_deposit: number;
}

interface CatalogProduct {
    id: number;
    name: string;
    image_url?: string | null;
    category_id: number | null;
    category_name: string;
    brand_id: number | null;
    brand_name: string;
    default_variants: DefaultVariant[];
}

interface DefaultVariant {
    variant: string;
    bottles_per_case: number;
    free_bottles_per_case: number;
}

interface LiftVariant {
    variant: string;
    number_of_cases: number;
    case_buying_price: number;
    bottles_per_case: number;
    free_bottles_per_case: number;
}

interface LiftItem {
    product_catalog_id: number;
    product_name: string;
    image_url?: string | null;
    category_name: string;
    brand_name: string;
    category_id: number | null;
    brand_id: number | null;
    variants: LiftVariant[];
}

interface DraftLift {
    id: number;
    supplier_id: number;
    lift_date: string;
    status?: string;
    notes?: string | null;
    items: Array<{
        product_catalog_id: number;
        variant: string;
        number_of_cases: number;
        case_buying_price: number;
        bottles_per_case: number;
        free_bottles_per_case: number;
        product_catalog?: {
            id: number;
            name: string;
        } | null;
    }>;
}

const props = defineProps<{
    suppliers: Supplier[];
    categories: Array<{ id: number; name: string }>;
    brands: Array<{ id: number; brand_name: string }>;
    draftLift?: DraftLift | null;
}>();

defineOptions({ layout: Layout });

// Variant options
const variantOptions = [
    { value: "250ml", label: "250 ml", bottles_per_case: 24 },
    { value: "500ml", label: "500 ml", bottles_per_case: 24 },
    { value: "1000ml", label: "1000 ml", bottles_per_case: 12 },
    { value: "2000ml", label: "2000 ml", bottles_per_case: 6 },
];

// Template refs for click-outside detection
const supplierDDRef = ref<HTMLElement | null>(null);
const getTodayString = () => {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
};

// State
const lang = ref(localStorage.getItem("language") || "en");
const currentDate = new Date().toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" });
const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || "";
const isLoading = ref(false);
const showConfirmModal = ref(false);
const showCreateProductModal = ref(false);
const showQuickDepositModal = ref(false);
const isQuickDepositLoading = ref(false);
const draftLiftId = ref<number | null>(props.draftLift?.id ?? null);
const loadedLiftStatus = ref(props.draftLift?.status || null);
const editingCompletedLift = computed(() => loadedLiftStatus.value === "completed" && !!draftLiftId.value);
const liftDate = ref(props.draftLift?.lift_date || getTodayString());

// Toast
const toast = ref({ show: false, message: "", type: "success" });
const showToast = (message: string, type: "success" | "error" = "success") => {
    toast.value = { show: true, message, type };
    setTimeout(() => (toast.value.show = false), 3000);
};

// Supplier
const supplierSearch = ref("");
const showSupplierDD = ref(false);
const selectedSupplier = ref<Supplier | null>(null);

const filteredSuppliers = computed(() =>
    props.suppliers.filter((s) =>
        s.company_name.toLowerCase().includes(supplierSearch.value.toLowerCase())
    )
);

const selectSupplier = (s: Supplier) => {
    selectedSupplier.value = s;
    supplierSearch.value = s.company_name;
    showSupplierDD.value = false;
    draftLiftId.value = null;
    loadedLiftStatus.value = null;
    liftItems.value = [];
    productSearch.value = "";
    activeProduct.value = null;
    variantSelections.value = {};
    fetchProducts(""); // auto-load on supplier select
};

const fillDraft = (draft: DraftLift) => {
    const supplier = props.suppliers.find((item) => item.id === draft.supplier_id);
    if (!supplier) return;

    selectedSupplier.value = { ...supplier };
    supplierSearch.value = supplier.company_name;
    draftLiftId.value = draft.id;
    loadedLiftStatus.value = draft.status || null;
    liftDate.value = draft.lift_date || getTodayString();
    liftItems.value = [];

    const groupedItems = new Map<number, LiftItem>();

    draft.items.forEach((item) => {
        const existing = groupedItems.get(item.product_catalog_id);
        const variantData: LiftVariant = {
            variant: item.variant,
            number_of_cases: item.number_of_cases,
            case_buying_price: Number(item.case_buying_price),
            bottles_per_case: item.bottles_per_case,
            free_bottles_per_case: item.free_bottles_per_case,
        };

        if (existing) {
            existing.variants.push(variantData);
            return;
        }

        groupedItems.set(item.product_catalog_id, {
            product_catalog_id: item.product_catalog_id,
            product_name: item.product_catalog?.name || "",
            category_name: "",
            brand_name: "",
            category_id: null,
            brand_id: null,
            variants: [variantData],
        });
    });

    liftItems.value = Array.from(groupedItems.values());
    fetchProducts("");
};

// Product Search
const productSearch = ref("");
const productSearchResults = ref<CatalogProduct[]>([]);
const isSearching = ref(false);
let searchTimeout: ReturnType<typeof setTimeout>;

const fetchProducts = async (query: string) => {
    if (!selectedSupplier.value) return;
    isSearching.value = true;
    try {
        const res = await fetch(
            `/api/product-catalog/search?supplier_id=${selectedSupplier.value.id}&q=${encodeURIComponent(query)}`,
            { headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" } }
        );
        productSearchResults.value = await res.json();
    } catch {
        productSearchResults.value = [];
    }
    isSearching.value = false;
};

const searchProducts = () => {
    clearTimeout(searchTimeout);
    if (!selectedSupplier.value) {
        productSearchResults.value = [];
        return;
    }
    isSearching.value = true;
    searchTimeout = setTimeout(() => fetchProducts(productSearch.value), 300);
};

// Variant Picker state
interface VariantCheckState {
    checked: boolean;
    bottles_per_case: number;
    free_bottles_per_case: number;
    customName?: string;
}
const activeProduct = ref<CatalogProduct | null>(null);
const variantSelections = ref<Record<string, VariantCheckState>>({});
const hasCheckedVariants = computed(() => {
    const fixed = variantOptions.some((opt) => variantSelections.value[opt.value]?.checked);
    const custom = variantSelections.value['__custom__']?.checked && !!variantSelections.value['__custom__']?.customName?.trim();
    return fixed || custom;
});

const selectProductForVariants = (p: CatalogProduct) => {
    activeProduct.value = p;
    const selections: Record<string, VariantCheckState> = {};
    for (const opt of variantOptions) {
        const defaultData = p.default_variants?.find((dv) => dv.variant === opt.value);
        selections[opt.value] = {
            checked: !!defaultData,
            bottles_per_case: defaultData?.bottles_per_case ?? opt.bottles_per_case,
            free_bottles_per_case: defaultData?.free_bottles_per_case ?? 0,
        };
    }
    selections['__custom__'] = { checked: false, bottles_per_case: 24, free_bottles_per_case: 0, customName: '' };
    variantSelections.value = selections;
};

const closeVariantPicker = () => {
    activeProduct.value = null;
    variantSelections.value = {};
};

const addProductWithVariants = () => {
    if (!activeProduct.value) return;
    const p = activeProduct.value;
    const selectedVariants: LiftVariant[] = variantOptions
        .filter((opt) => variantSelections.value[opt.value]?.checked)
        .map((opt) => ({
            variant: opt.value,
            number_of_cases: 0,
            case_buying_price: 0,
            bottles_per_case: variantSelections.value[opt.value].bottles_per_case,
            free_bottles_per_case: variantSelections.value[opt.value].free_bottles_per_case,
        }));

    // custom variant
    const customState = variantSelections.value['__custom__'];
    if (customState?.checked && customState.customName?.trim()) {
        selectedVariants.push({
            variant: customState.customName.trim(),
            number_of_cases: 0,
            case_buying_price: 0,
            bottles_per_case: customState.bottles_per_case,
            free_bottles_per_case: customState.free_bottles_per_case,
        });
    }

    if (!selectedVariants.length) return;

    const existing = liftItems.value.find((i) => i.product_catalog_id === p.id);
    if (existing) {
        for (const v of selectedVariants) {
            if (!existing.variants.some((ev) => ev.variant === v.variant)) {
                existing.variants.push(v);
            }
        }
    } else {
        liftItems.value.push({
            product_catalog_id: p.id,
            product_name: p.name,
            image_url: p.image_url || null,
            category_name: p.category_name,
            brand_name: p.brand_name,
            category_id: p.category_id,
            brand_id: p.brand_id,
            variants: selectedVariants,
        });
    }
    closeVariantPicker();
};

// Cart
const liftItems = ref<LiftItem[]>([]);

const removeItem = (idx: number) => liftItems.value.splice(idx, 1);

const addVariant = (itemIdx: number) => {
    liftItems.value[itemIdx].variants.push({
        variant: "",
        number_of_cases: 0,
        case_buying_price: 0,
        bottles_per_case: 0,
        free_bottles_per_case: 0,
    });
};

const removeVariant = (itemIdx: number, vIdx: number) => {
    if (liftItems.value[itemIdx].variants.length > 1) {
        liftItems.value[itemIdx].variants.splice(vIdx, 1);
    }
};

const fixedVariantValues = new Set(variantOptions.map((o) => o.value));

const isCustomVariant = (value: string) =>
    !!value && value !== '__custom__' && !fixedVariantValues.has(value);

const onVariantSelect = (itemIdx: number, vIdx: number) => {
    const v = liftItems.value[itemIdx].variants[vIdx];
    if (v.variant === '__custom__') {
        v.variant = '';
        return;
    }
    const opt = variantOptions.find((o) => o.value === v.variant);
    if (opt) v.bottles_per_case = opt.bottles_per_case;
};

// Create Product
const newProduct = ref({
    name: "",
    category_id: "" as any,
    brand_id: "" as any,
    imageFile: null as File | null,
    imagePreviewUrl: "",
});

const openCreateProductModal = () => {
    newProduct.value = {
        name: productSearch.value,
        category_id: "",
        brand_id: "",
        imageFile: null,
        imagePreviewUrl: "",
    };
    showCreateProductModal.value = true;
};

const handleNewProductImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] || null;

    newProduct.value.imageFile = file;
    newProduct.value.imagePreviewUrl = file ? URL.createObjectURL(file) : "";
};

const removeNewProductImage = () => {
    newProduct.value.imageFile = null;
    newProduct.value.imagePreviewUrl = "";
};

const createProduct = async () => {
    if (!newProduct.value.name || !selectedSupplier.value) return;
    try {
        const formData = new FormData();
        formData.append("name", newProduct.value.name);
        formData.append("supplier_id", String(selectedSupplier.value.id));
        if (newProduct.value.category_id) formData.append("category_id", String(newProduct.value.category_id));
        if (newProduct.value.brand_id) formData.append("brand_id", String(newProduct.value.brand_id));
        if (newProduct.value.imageFile) formData.append("product_image", newProduct.value.imageFile);

        const res = await fetch("/api/product-catalog/quick-store", {
            method: "POST",
            headers: { Accept: "application/json", "X-CSRF-TOKEN": csrfToken, "X-Requested-With": "XMLHttpRequest" },
            body: formData,
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || "Failed");
        showCreateProductModal.value = false;
        // Refresh product list and open variant picker for the new product
        await fetchProducts(productSearch.value);
        selectProductForVariants(data.product);
        showToast(t("productCreated"), "success");
    } catch (err: any) {
        showToast(err.message || t("error"), "error");
    }
};

// Calculations
const calcVariantCost = (v: LiftVariant) => (v.number_of_cases || 0) * (v.case_buying_price || 0);
const calcFreeBottles = (v: LiftVariant) => (v.number_of_cases || 0) * (v.free_bottles_per_case || 0);
const calcTotalBottles = (v: LiftVariant) => {
    const purchased = (v.number_of_cases || 0) * (v.bottles_per_case || 0);
    return purchased + calcFreeBottles(v);
};

const validItems = computed(() =>
    liftItems.value
        .map((item) => ({
            ...item,
            validVariants: item.variants.filter((v) => v.number_of_cases > 0 && v.case_buying_price > 0),
        }))
        .filter((item) => item.validVariants.length > 0)
);

const grandTotal = computed(() =>
    validItems.value.reduce((sum, item) => sum + item.validVariants.reduce((s, v) => s + calcVariantCost(v), 0), 0)
);

const totalCases = computed(() =>
    validItems.value.reduce((sum, item) => sum + item.validVariants.reduce((s, v) => s + (v.number_of_cases || 0), 0), 0)
);

const totalBottles = computed(() =>
    validItems.value.reduce((sum, item) => sum + item.validVariants.reduce((s, v) => s + calcTotalBottles(v), 0), 0)
);

const totalFreeBottles = computed(() =>
    validItems.value.reduce((sum, item) => sum + item.validVariants.reduce((s, v) => s + calcFreeBottles(v), 0), 0)
);

const remainingDeposit = computed(() => {
    if (!selectedSupplier.value) return 0;
    return selectedSupplier.value.remaining_deposit - grandTotal.value;
});

const shortfallAmount = computed(() =>
    remainingDeposit.value < 0 ? Math.abs(remainingDeposit.value) : 0
);

const submitQuickDeposit = async () => {
    if (!selectedSupplier.value || shortfallAmount.value <= 0) return;
    isQuickDepositLoading.value = true;
    try {
        const res = await fetch('/api/deposits/quick-store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                supplier_id: selectedSupplier.value.id,
                balance_deposited: shortfallAmount.value,
            }),
        });
        if (!res.ok) throw new Error(t('error'));
        selectedSupplier.value = {
            ...selectedSupplier.value,
            remaining_deposit: selectedSupplier.value.remaining_deposit + shortfallAmount.value,
        };
        showQuickDepositModal.value = false;
        showToast(t('depositSuccess'), 'success');
        submitLift();
    } catch (err: any) {
        showToast(err.message || t('error'), 'error');
    }
    isQuickDepositLoading.value = false;
};

// Submit
const openConfirmModal = () => {
    if (!validItems.value.length || !selectedSupplier.value) return;
    if (remainingDeposit.value < 0) {
        showToast(t("insufficientDeposit"), "error");
        return;
    }
    showConfirmModal.value = true;
};

const submitLift = () => {
    isLoading.value = true;

    const items = validItems.value.map((item) => ({
        product_catalog_id: item.product_catalog_id,
        product_name: item.product_name,
        category_id: item.category_id,
        brand_id: item.brand_id,
        variants: item.validVariants.map((v) => ({
            variant: v.variant,
            number_of_cases: v.number_of_cases,
            case_buying_price: v.case_buying_price,
            bottles_per_case: v.bottles_per_case,
            free_bottles_per_case: v.free_bottles_per_case,
        })),
    }));

    router.post(
        "/lifts/store",
        {
            draft_id: draftLiftId.value,
            supplier_id: selectedSupplier.value!.id,
            lift_date: liftDate.value,
            deposit_from_here_amount: shortfallAmount.value > 0 ? shortfallAmount.value : null,
            items,
        },
        {
            onSuccess: () => {
                showConfirmModal.value = false;
                isLoading.value = false;
                resetAll();
                showToast(t("liftSuccess"), "success");
            },
            onError: (errors: any) => {
                showConfirmModal.value = false;
                isLoading.value = false;
                showToast(Object.values(errors).flat().join(", "), "error");
            },
        }
    );
};

const saveDraft = () => {
    if (!selectedSupplier.value || !validItems.value.length) return;

    isLoading.value = true;

    const items = validItems.value.map((item) => ({
        product_catalog_id: item.product_catalog_id,
        product_name: item.product_name,
        category_id: item.category_id,
        brand_id: item.brand_id,
        variants: item.validVariants.map((v) => ({
            variant: v.variant,
            number_of_cases: v.number_of_cases,
            case_buying_price: v.case_buying_price,
            bottles_per_case: v.bottles_per_case,
            free_bottles_per_case: v.free_bottles_per_case,
        })),
    }));

    router.post("/lifts/store", {
        draft_id: draftLiftId.value,
        supplier_id: selectedSupplier.value.id,
        lift_date: liftDate.value,
        save_as_draft: true,
        items,
    }, {
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const resetAll = () => {
    liftItems.value = [];
    productSearch.value = "";
    productSearchResults.value = [];
    activeProduct.value = null;
    variantSelections.value = {};
    draftLiftId.value = null;
    loadedLiftStatus.value = null;
    liftDate.value = getTodayString();
};

const printInvoice = () => {
    window.print();
};

// Close dropdowns on outside click
const handleClickOutside = (e: MouseEvent) => {
    const target = e.target as Node;
    if (supplierDDRef.value && !supplierDDRef.value.contains(target)) {
        showSupplierDD.value = false;
    }
};

onMounted(() => {
    document.addEventListener("mousedown", handleClickOutside);
    if (props.draftLift) {
        fillDraft(props.draftLift);
    }
});

onUnmounted(() => {
    document.removeEventListener("mousedown", handleClickOutside);
});

const saveLang = () => localStorage.setItem("language", lang.value);

// Translations
const translations: Record<string, Record<string, string>> = {
    en: {
        liftManagement: "Lift Management",
        selectSupplier: "Select Supplier",
        searchSupplier: "Search supplier...",
        availableBalance: "Available Balance",
        addProducts: "Add Products",
        searchProduct: "Search product by name...",
        createNewProduct: "Create New Product",
        productImage: "Product Image",
        uploadProductImage: "Upload product image",
        changeProductImage: "Change image",
        removeImage: "Remove image",
        productName: "Product Name",
        category: "Category",
        selectCategory: "Select category",
        brand: "Brand",
        selectBrand: "Select brand",
        variantName: "Variant Size",
        selectVariant: "Select size",
        numberOfCases: "Cases",
        caseBuyingPrice: "Price/Case",
        bottlesPerCase: "Bottles/Case",
        freeBottlesPerCase: "Free/Case",
        addVariant: "Add Variant",
        liftInvoice: "Lift Invoice",
        emptyInvoice: "Add products to see invoice",
        totalProducts: "Total Products",
        totalCases: "Total Cases",
        totalBottles: "Total Bottles",
        freeBottles: "Free Bottles",
        totalCost: "Total Cost",
        remainingDeposit: "Remaining Deposit",
        afterLift: "After this lift",
        sufficient: "Sufficient",
        insufficient: "Insufficient",
        confirmLift: "Confirm Lift",
        confirmLiftPrompt: "Are you sure you want to record this lift?",
        cancel: "Cancel",
        confirm: "Confirm",
        updateLift: "Update Lift",
        save: "Save",
        processing: "Processing...",
        saveDraft: "Save Draft",
        updateDraft: "Update Draft",
        resetForm: "Reset",
        liftSuccess: "Lift recorded successfully!",
        insufficientDeposit: "Lift amount exceeds supplier's deposit",
        productAlreadyAdded: "Product already added to cart",
        productCreated: "Product created successfully",
        error: "Something went wrong",
        noResults: "No results found",
        case: "cases",
        free: "free",
        printInvoice: "Print",
        selectVariantsToAdd: "Select variants to add",
        addToCart: "Add to Cart",
        loading: "Loading...",
        quickDeposit: "Quick Deposit",
        quickDepositPrompt: "The lift amount exceeds the supplier's deposit. Add the shortfall to proceed.",
        supplier: "Supplier",
        currentDeposit: "Current Deposit",
        depositShortfall: "Shortfall Amount",
        addMissingDeposit: "Add Deposit",
        depositAndLift: "Deposit & Lift",
        depositSuccess: "Deposit added successfully!",
    },
    bn: {
        liftManagement: "লিফট ব্যবস্থাপনা",
        selectSupplier: "সরবরাহকারী নির্বাচন",
        searchSupplier: "সরবরাহকারী খুঁজুন...",
        availableBalance: "উপলব্ধ ব্যালেন্স",
        addProducts: "পণ্য যোগ করুন",
        searchProduct: "পণ্যের নাম দিয়ে খুঁজুন...",
        createNewProduct: "নতুন পণ্য তৈরি করুন",
        productImage: "পণ্যের ছবি",
        uploadProductImage: "পণ্যের ছবি আপলোড করুন",
        changeProductImage: "ছবি পরিবর্তন করুন",
        removeImage: "ছবি মুছুন",
        productName: "পণ্যের নাম",
        category: "বিভাগ",
        selectCategory: "বিভাগ নির্বাচন",
        brand: "ব্র্যান্ড",
        selectBrand: "ব্র্যান্ড নির্বাচন",
        variantName: "ভেরিয়েন্টের আকার",
        selectVariant: "আকার নির্বাচন",
        numberOfCases: "কেস",
        caseBuyingPrice: "মূল্য/কেস",
        bottlesPerCase: "বোতল/কেস",
        freeBottlesPerCase: "ফ্রি/কেস",
        addVariant: "ভেরিয়েন্ট যোগ",
        liftInvoice: "লিফট ইনভয়েস",
        emptyInvoice: "ইনভয়েস দেখতে পণ্য যোগ করুন",
        totalProducts: "মোট পণ্য",
        totalCases: "মোট কেস",
        totalBottles: "মোট বোতল",
        freeBottles: "বিনামূল্যে বোতল",
        totalCost: "মোট খরচ",
        remainingDeposit: "বাকি আমানত",
        afterLift: "এই লিফটের পর",
        sufficient: "যথেষ্ট",
        insufficient: "অপর্যাপ্ত",
        confirmLift: "লিফট নিশ্চিত করুন",
        confirmLiftPrompt: "আপনি কি নিশ্চিতভাবে এই লিফট রেকর্ড করতে চান?",
        cancel: "বাতিল",
        confirm: "নিশ্চিত",
        updateLift: "লিফট আপডেট করুন",
        save: "সংরক্ষণ",
        processing: "প্রক্রিয়াকরণ...",
        saveDraft: "ড্রাফট সেভ করুন",
        updateDraft: "ড্রাফট আপডেট করুন",
        resetForm: "রিসেট",
        liftSuccess: "লিফট সফলভাবে রেকর্ড হয়েছে!",
        insufficientDeposit: "লিফটের পরিমাণ সরবরাহকারীর আমানত অতিক্রম করেছে",
        productAlreadyAdded: "পণ্য ইতিমধ্যে যোগ করা হয়েছে",
        productCreated: "পণ্য সফলভাবে তৈরি হয়েছে",
        error: "কিছু ভুল হয়েছে",
        noResults: "কোন ফলাফল পাওয়া যায়নি",
        case: "কেস",
        free: "ফ্রি",
        printInvoice: "প্রিন্ট",
        selectVariantsToAdd: "যোগ করতে ভেরিয়েন্ট বেছে নিন",
        addToCart: "কার্টে যোগ করুন",
        loading: "লোড হচ্ছে...",
        quickDeposit: "দ্রুত ডিপোজিট",
        quickDepositPrompt: "লিফটের পরিমাণ সরবরাহকারীর আমানতের চেয়ে বেশি। অগ্রসর হতে ঘাটতি পরিমাণ জমা দিন।",
        supplier: "সরবরাহকারী",
        currentDeposit: "বর্তমান আমানত",
        depositShortfall: "ঘাটতি পরিমাণ",
        addMissingDeposit: "ডিপোজিট যোগ করুন",
        depositAndLift: "ডিপোজিট ও লিফট",
        depositSuccess: "ডিপোজিট সফলভাবে যোগ হয়েছে!",
    },
};

const t = (key: string) => translations[lang.value]?.[key] || translations.en[key] || key;

const toBengaliNumber = (numValue: number | string, decimals: number | null = null): string => {
    if (numValue === null || numValue === undefined || numValue === "") return "";
    
    let n = Number(numValue);
    if (isNaN(n)) return String(numValue);

    let output: string;
    if (decimals !== null) {
        output = n.toFixed(decimals);
    } else {
        output = n % 1 !== 0 ? n.toFixed(2) : n.toString();
    }

    if (lang.value !== 'bn') return output;

    const bengaliDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    return output.replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};
</script>

<style scoped>
.bangla-font {
    font-family: "Kalpurush", "Noto Sans Bengali", sans-serif;
}

input:focus,
select:focus {
    outline: none;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}

@media print {
    /* Hide everything outside the invoice */
    :deep(.print\:hidden),
    :deep(nav),
    :deep(aside) {
        display: none !important;
    }

    #printable-invoice {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        max-width: 100%;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    #printable-invoice .max-h-\[calc\(100vh-320px\)\] {
        max-height: none !important;
        overflow: visible !important;
    }
}
</style>
