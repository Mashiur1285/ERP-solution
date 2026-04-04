<template>
    <div
        class="p-6 space-y-8 bg-gradient-to-br from-gray-50 via-white to-gray-50 min-h-screen"
    >
        <!-- Toast Notification -->
        <div
            v-if="showToast"
            class="fixed top-6 right-6 px-7 py-5 rounded-lg shadow-md flex items-center space-x-3 animate-toast-in z-50"
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
                class="ml-2 text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white"
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

        <!-- Title and Add Button -->
        <div
            class="flex flex-col lg:flex-row lg:justify-between items-start lg:items-center mb-8 border-b border-gray-200 pb-4 gap-4"
        >
            <h1
                class="text-2xl lg:text-3xl font-semibold text-gray-800 flex items-center tracking-tight animate-fade-in"
            >
                <div
                    class="p-2 mr-3 bg-blue-100 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-6 h-6 lg:w-8 lg:h-8 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                        />
                    </svg>
                </div>
                Deposit Management
            </h1>

            <!-- Add Deposit Button -->
            <button
                @click="closeDepositModal(); showDepositModal = true"
                class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl"
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
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                </svg>
                <span>Add Deposit</span>
            </button>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-sm border border-blue-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-700">
                            Total Suppliers
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-blue-900">
                            {{ Object.keys(groupedDeposits).length }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-sm border border-green-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-green-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-700">
                            Total Deposits
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-green-900">
                            ৳{{ formatCurrency(totalDepositAmount) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl shadow-sm border border-orange-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-orange-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-orange-700">
                            Total Used
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-orange-900">
                            ৳{{ formatCurrency(totalUsedAmount) }}
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-xl shadow-sm border border-indigo-200"
            >
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 12H4"
                            />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-indigo-700">
                            Total Remaining
                        </p>
                        <p class="text-lg lg:text-lg font-bold text-indigo-900">
                            ৳{{ formatCurrency(totalRemainingAmount) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit Modal -->
        <DepositModal
            v-if="showDepositModal"
            :suppliers="suppliers"
            :deposit="currentDeposit"
            :edit-mode="editMode"
            @close="closeDepositModal"
            @submit="submitDeposit"
        />

        <!-- Deposit History -->
        <div class="bg-white rounded-xl shadow-sm p-3 lg:p-6">
            <h2
                class="text-2xl font-semibold text-gray-800 mb-6 flex items-center"
            >
                <svg
                    class="w-6 h-6 mr-2 text-gray-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                    />
                </svg>
                Deposit History
            </h2>

            <div class="w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4"
                            >
                                Supplier
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 hidden sm:table-cell"
                            >
                                Total Deposit (TK)
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 hidden md:table-cell"
                            >
                                Used Money (TK)
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4"
                            >
                                Remaining (TK)
                            </th>
                            <th
                                class="px-2 lg:px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template
                            v-for="(
                                supplierData, supplierName, index
                            ) in groupedDeposits"
                            :key="supplierName"
                        >
                            <!-- Primary Row -->
                            <tr
                                class="hover:bg-gray-50 transition-colors cursor-pointer"
                                @click="toggleSupplierDetails(supplierName)"
                            >
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm font-medium text-gray-900 w-1/4"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            :class="[
                                                'w-3 h-3 lg:w-4 lg:h-4 mr-1 lg:mr-2 transition-transform flex-shrink-0',
                                                expandedSuppliers[supplierName]
                                                    ? 'rotate-90'
                                                    : '',
                                            ]"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5l7 7-7 7"
                                            />
                                        </svg>
                                        <div class="min-w-0">
                                            <p
                                                class="font-semibold text-gray-900 truncate"
                                                :title="supplierName"
                                            >
                                                {{ supplierName }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{
                                                    supplierData.deposits.length
                                                }}
                                                deposit{{
                                                    supplierData.deposits
                                                        .length > 1
                                                        ? "s"
                                                        : ""
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-900 w-1/4 hidden sm:table-cell"
                                >
                                    <div class="font-medium">
                                        ৳{{
                                            formatCurrency(
                                                supplierData.totalDeposit
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-900 w-1/4 hidden md:table-cell"
                                >
                                    <div class="font-medium">
                                        ৳{{
                                            formatCurrency(
                                                supplierData.totalUsed
                                            )
                                        }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-3 py-3 text-xs lg:text-sm text-gray-900 w-1/4">
                                    <div
                                        class="font-medium"
                                        :class="
                                            supplierData.totalRemaining > 0
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        ৳{{
                                            formatCurrency(
                                                supplierData.totalRemaining
                                            )
                                        }}
                                    </div>
                                </td>
                                <td class="px-2 lg:px-3 py-3 text-center text-xs lg:text-sm text-gray-900 w-1/4">
                                    <span class="text-xs text-gray-400">Expand</span>
                                </td>
                            </tr>

                            <!-- Expanded Details -->
                            <tr
                                v-if="expandedSuppliers[supplierName]"
                                class="bg-gradient-to-r from-gray-50 to-gray-100 animate-slide-down"
                            >
                                <td :colspan="5" class="px-2 lg:px-6 py-6">
                                    <div class="ml-2 lg:ml-6">
                                        <!-- Mobile Summary (shown on small screens) -->
                                        <div
                                            class="sm:hidden mb-6 p-4 bg-white rounded-lg shadow-sm border-l-4 border-blue-500"
                                        >
                                            <h4
                                                class="font-semibold text-gray-800 mb-3 flex items-center"
                                            >
                                                <svg
                                                    class="w-4 h-4 mr-2 text-blue-600"
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
                                                Supplier Summary
                                            </h4>
                                            <div class="space-y-3 text-sm">
                                                <div
                                                    class="flex justify-between"
                                                >
                                                    <span class="text-gray-600"
                                                        >Total Deposit:</span
                                                    >
                                                    <span class="font-medium"
                                                        >৳{{
                                                            formatCurrency(
                                                                supplierData.totalDeposit
                                                            )
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex justify-between"
                                                >
                                                    <span class="text-gray-600"
                                                        >Used Money:</span
                                                    >
                                                    <span class="font-medium"
                                                        >৳{{
                                                            formatCurrency(
                                                                supplierData.totalUsed
                                                            )
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex justify-between"
                                                >
                                                    <span class="text-gray-600"
                                                        >Remaining:</span
                                                    >
                                                    <span
                                                        class="font-medium"
                                                        :class="
                                                            supplierData.totalRemaining >
                                                            0
                                                                ? 'text-green-600'
                                                                : 'text-red-600'
                                                        "
                                                    >
                                                        ৳{{
                                                            formatCurrency(
                                                                supplierData.totalRemaining
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Individual Deposits -->
                                        <div
                                            class="bg-white rounded-xl p-6 shadow-sm border border-gray-200"
                                        >
                                            <h4
                                                class="text-lg font-semibold text-gray-800 mb-4 flex items-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 mr-2 text-blue-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                    />
                                                </svg>
                                                Individual Deposits -
                                                {{ supplierName }}
                                            </h4>

                                            <div class="space-y-3">
                                                <div
                                                    v-for="(
                                                        deposit, depositIndex
                                                    ) in supplierData.deposits"
                                                    :key="depositIndex"
                                                    class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition-colors"
                                                >
                                                    <div class="flex-1">
                                                        <div
                                                            class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0"
                                                        >
                                                            <div
                                                                class="flex items-center space-x-2"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 text-blue-500"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    class="font-medium text-gray-700"
                                                                    >৳{{
                                                                        formatCurrency(
                                                                            deposit.deposit_amount ||
                                                                                0
                                                                        )
                                                                    }}</span
                                                                >
                                                            </div>
                                                            <div
                                                                class="flex items-center space-x-2"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 text-orange-500"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    class="text-gray-600"
                                                                    >Used: ৳{{
                                                                        formatCurrency(
                                                                            deposit.balance_used ||
                                                                                0
                                                                        )
                                                                    }}</span
                                                                >
                                                            </div>
                                                            <div
                                                                class="flex items-center space-x-2"
                                                            >
                                                                <svg
                                                                    class="w-4 h-4 text-green-500"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M20 12H4"
                                                                    />
                                                                </svg>
                                                                <span
                                                                    class="text-gray-600"
                                                                    >Remaining:
                                                                    ৳{{
                                                                        formatCurrency(
                                                                            deposit.remaining_amount ||
                                                                                0
                                                                        )
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="mt-2 sm:mt-0 sm:ml-4"
                                                    >
                                                        <div class="flex flex-col sm:items-end gap-2">
                                                            <div class="flex items-center text-xs text-gray-500">
                                                                <svg
                                                                    class="w-3 h-3 mr-1"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M8 7V3a2 2 0 012-2h8a2 2 0 012 2v4m-4 8l-4-4m0 0l-4 4m4-4v12"
                                                                    />
                                                                </svg>
                                                                {{
                                                                    formatDate(
                                                                        deposit.date
                                                                    )
                                                                }}
                                                            </div>
                                                            <button
                                                                @click.stop="editDeposit(deposit)"
                                                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 text-xs font-semibold hover:bg-blue-200 transition-colors"
                                                            >
                                                                Edit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div
                    v-if="Object.keys(groupedDeposits).length === 0"
                    class="text-center py-12"
                >
                    <svg
                        class="w-16 h-16 mx-auto text-gray-300 mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"
                        />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-400 mb-2">
                        No deposits found
                    </h3>
                    <p class="text-gray-300">
                        Start by adding your first deposit
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import Layout from "../../Layout.vue";
import DepositModal from "./Partials/DepositModal.vue";

interface Supplier {
    id: number;
    company_name: string;
}

interface Deposit {
    id: number;
    supplier_id: number;
    name: string;
    phone_number?: string;
    deposit_amount: number;
    balance_used: number;
    remaining_amount: number;
    date: string;
}

interface GroupedSupplierData {
    deposits: Deposit[];
    totalDeposit: number;
    totalUsed: number;
    totalRemaining: number;
}

const props = defineProps<{
    suppliers: Supplier[];
    depositHistory: Deposit[];
}>();

defineOptions({
    layout: Layout,
});

const showDepositModal = ref(false);
const editMode = ref(false);
const currentDeposit = ref<{ id?: number; supplier_id: number | string; balance_deposited: number } | null>(null);
const expandedSuppliers = ref<Record<string, boolean>>({});

// Toast state
const showToast = ref(false);
const toastMessage = ref("");
const toastType = ref("success");

const toastClasses = computed(() => ({
    "bg-green-500": toastType.value === "success",
    "bg-red-500": toastType.value === "error",
    "bg-yellow-500": toastType.value === "warning",
    "text-white": true,
}));

// Group deposits by supplier name
const groupedDeposits = computed(() => {
    const grouped: Record<string, GroupedSupplierData> = {};

    props.depositHistory.forEach((deposit) => {
        const supplierName = deposit.name;

        if (!grouped[supplierName]) {
            grouped[supplierName] = {
                deposits: [],
                totalDeposit: 0,
                totalUsed: 0,
                totalRemaining: 0,
            };
        }

        grouped[supplierName].deposits.push(deposit);

        // Safe parsing with null checks
        const depositAmount = deposit.deposit_amount
            ? parseFloat(deposit.deposit_amount.toString())
            : 0;
        const balanceUsed = deposit.balance_used
            ? parseFloat(deposit.balance_used.toString())
            : 0;
        const remainingAmount = deposit.remaining_amount
            ? parseFloat(deposit.remaining_amount.toString())
            : 0;

        grouped[supplierName].totalDeposit += depositAmount;
        grouped[supplierName].totalUsed += balanceUsed;
        grouped[supplierName].totalRemaining += remainingAmount;
    });

    // Sort deposits within each supplier by date (newest first)
    Object.values(grouped).forEach((supplierData) => {
        supplierData.deposits.sort(
            (a, b) => new Date(b.date).getTime() - new Date(a.date).getTime()
        );
    });

    return grouped;
});

// Calculate summary statistics
const totalDepositAmount = computed(() => {
    return Object.values(groupedDeposits.value).reduce(
        (sum, supplier) => sum + supplier.totalDeposit,
        0
    );
});

const totalUsedAmount = computed(() => {
    return Object.values(groupedDeposits.value).reduce(
        (sum, supplier) => sum + supplier.totalUsed,
        0
    );
});

const totalRemainingAmount = computed(() => {
    return Object.values(groupedDeposits.value).reduce(
        (sum, supplier) => sum + supplier.totalRemaining,
        0
    );
});

const toggleSupplierDetails = (supplierName: string) => {
    expandedSuppliers.value[supplierName] =
        !expandedSuppliers.value[supplierName];
};

const formatCurrency = (amount: number | null | undefined): string => {
    if (amount === null || amount === undefined || isNaN(amount)) {
        return "0";
    }
    return new Intl.NumberFormat("en-BD").format(amount);
};

const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const showToastWithType = (message: string, type: string = "success") => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;

    setTimeout(() => {
        showToast.value = false;
    }, 5000);
};

const closeToast = () => {
    showToast.value = false;
};

const closeDepositModal = () => {
    showDepositModal.value = false;
    currentDeposit.value = null;
    editMode.value = false;
};

const submitDeposit = (depositData: {
    supplier_id: string;
    balance_deposited: number;
}) => {
    const options = {
        onSuccess: () => {
            const wasEdit = editMode.value;
            closeDepositModal();
            showToastWithType(
                wasEdit
                    ? "Deposit Updated Successfully"
                    : "Deposit Added Successfully",
                "success"
            );
        },
        onError: (errors) => {
            console.error("Deposit submission errors:", errors);
            showToastWithType(
                editMode.value
                    ? "Failed to update deposit. Please check the form."
                    : "Failed to add deposit. Please check the form.",
                "error"
            );
        },
    };

    if (editMode.value && currentDeposit.value?.id) {
        router.put(
            `/deposits/${currentDeposit.value.id}`,
            { balance_deposited: depositData.balance_deposited },
            options
        );
        return;
    }

    router.post("/deposits/store", depositData, options);
};

const editDeposit = (deposit: Deposit) => {
    currentDeposit.value = {
        id: deposit.id,
        supplier_id: deposit.supplier_id,
        balance_deposited: Number(deposit.deposit_amount || 0),
    };
    editMode.value = true;
    showDepositModal.value = true;
};

console.log("DepositManagement.vue component loaded");
</script>

<style scoped>
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

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 1000px;
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

.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

.animate-toast-in {
    animation: toast-in 0.3s ease-out forwards;
}

.rotate-90 {
    transform: rotate(90deg);
}

/* Custom responsive utilities */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Table layout */
table {
    table-layout: fixed;
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

/* Mobile optimizations */
@media (max-width: 640px) {
    .table-responsive {
        font-size: 0.75rem;
    }

    th,
    td {
        padding: 0.25rem 0.5rem;
    }
}

/* Enhanced focus states */
input:focus,
select:focus {
    outline: none;
    transform: translateY(-1px);
}

button:hover:not(:disabled) {
    transform: translateY(-1px);
}

/* Enhanced shadow effects */
.shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(59, 130, 246, 0.1);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color,
        text-decoration-color, fill, stroke, opacity, box-shadow, transform,
        filter, backdrop-filter;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
