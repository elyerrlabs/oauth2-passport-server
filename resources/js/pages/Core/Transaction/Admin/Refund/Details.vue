<template>
    <v-admin-transaction-layout>
        <div class="mb-6">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div>
                    <h1
                        class="text-2xl font-medium text-gray-900 dark:text-white"
                    >
                        {{ __("Refund Review") }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Review and process refund request") }} #
                        {{ data?.transaction?.code }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a
                        :href="data.web?.index"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-normal text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    >
                        <svg
                            class="w-4 h-4 mr-1.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        {{ __("Back to List") }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Refund Details -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Status Banner -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-5"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                    >
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <div
                                    :class="`px-3 py-1 rounded-full text-xs font-medium ${getStatusColorClass(
                                        data.status
                                    )}`"
                                >
                                    {{ getStatusLabel(data.status) }}
                                </div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Created") }}:
                                    {{ data.transaction?.created }}
                                </div>
                            </div>
                            <h2
                                class="text-base font-medium text-gray-900 dark:text-white"
                            >
                                {{ __("Refund Request for") }}
                                {{ data.transaction?.owner?.name }}
                            </h2>
                        </div>
                        <div class="text-right">
                            <div
                                class="text-xl font-medium text-gray-900 dark:text-white"
                            >
                                {{ data.amount }} {{ data.currency }}
                            </div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                            >
                                {{ data.cents }} {{ __("cents") }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer & Transaction Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Customer Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <h3
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ __("Customer Information") }}
                            </h3>
                            <div
                                class="p-1.5 bg-blue-100 dark:bg-blue-900/20 rounded"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-0.5"
                                >
                                    {{ __("Full Name") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white"
                                >
                                    {{ data.user?.name }}
                                    {{ data.user?.last_name }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-0.5"
                                >
                                    {{ __("Email Address") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white"
                                >
                                    {{ data.user?.email }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-0.5"
                                >
                                    {{ __("Handled By") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white"
                                >
                                    {{
                                        data.handled?.name || __("Not assigned")
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <h3
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ __("Transaction Details") }}
                            </h3>
                            <div
                                class="p-1.5 bg-green-100 dark:bg-green-900/20 rounded"
                            >
                                <svg
                                    class="w-4 h-4 text-green-600 dark:text-green-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-0.5"
                                >
                                    {{ __("Transaction ID") }}
                                </div>
                                <div
                                    class="text-sm font-mono text-gray-900 dark:text-white"
                                >
                                    {{ data.transaction?.code }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-0.5"
                                >
                                    {{ __("Payment Method") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white capitalize"
                                >
                                    {{ data.transaction?.payment_method }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-0.5"
                                >
                                    {{ __("Original Amount") }}
                                </div>
                                <div
                                    class="text-sm text-gray-900 dark:text-white"
                                >
                                    {{ data.transaction?.total }}
                                    {{ data.transaction?.currency }}
                                </div>
                            </div>
                            <div v-if="data.transaction?.payment_url">
                                <a
                                    :href="data.transaction.payment_url"
                                    target="_blank"
                                    class="inline-flex items-center text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors"
                                >
                                    <svg
                                        class="w-3.5 h-3.5 mr-1.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                        />
                                    </svg>
                                    {{ __("View Payment Receipt") }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Refund Details -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                >
                    <div class="flex items-center justify-between mb-4">
                        <h3
                            class="text-sm font-medium text-gray-900 dark:text-white"
                        >
                            {{ __("Refund Information") }}
                        </h3>
                        <div
                            class="p-1.5 bg-amber-100 dark:bg-amber-900/20 rounded"
                        >
                            <svg
                                class="w-4 h-4 text-amber-600 dark:text-amber-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.5 16.5c-.77.833.192 2.5 1.732 2.5z"
                                />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Reason -->
                        <div>
                            <div
                                class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                            >
                                {{ __("Refund Reason") }}
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-900/30 rounded p-3 border border-gray-200 dark:border-gray-700"
                            >
                                <div
                                    class="text-sm text-gray-900 dark:text-white"
                                >
                                    {{ data.reason }}
                                </div>
                                <div
                                    v-if="data.description"
                                    class="mt-1.5 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    {{ data.description }}
                                </div>
                            </div>
                        </div>

                        <!-- Files -->
                        <div v-if="data.files?.length" class="mb-6">
                            <h4
                                class="text-sm font-medium text-gray-900 dark:text-white mb-4 flex items-center gap-2"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-blue-500"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828L18 9.828m-4.828-2.828L6 14.172a4 4 0 105.657 5.657L21 10.485"
                                    />
                                </svg>
                                {{ __("Supporting Evidence Documents") }}
                            </h4>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="file in data.files"
                                    :key="file.id"
                                    class="rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden"
                                >
                                    <div
                                        v-if="
                                            file.mime_type.startsWith('image/')
                                        "
                                        class="w-full h-40 bg-gray-100 dark:bg-gray-800 overflow-hidden"
                                    >
                                        <img
                                            :src="file.links.show"
                                            alt="Preview"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>

                                    <div
                                        v-else
                                        class="w-full h-40 flex items-center justify-center bg-gray-100 dark:bg-gray-800"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 text-gray-500"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                        >
                                            <path
                                                d="M16.5 2h-9A2.5 2.5 0 005 4.5v15A2.5 2.5 0 007.5 22h9a2.5 2.5 0 002.5-2.5v-15A2.5 2.5 0 0016.5 2zm-1 14h-7v-2h7v2zm0-4h-7V10h7v2zm0-4h-7V6h7v2z"
                                            />
                                        </svg>
                                    </div>

                                    <div class="p-4">
                                        <div
                                            class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                        >
                                            {{ file.original_name }}
                                        </div>

                                        <div
                                            class="text-xs text-gray-500 dark:text-gray-300 mt-1"
                                        >
                                            {{ file.mime_type }} ·
                                            {{ Math.round(file.size / 1024) }}
                                            KB
                                        </div>

                                        <a
                                            :href="file.links.show"
                                            target="_blank"
                                            class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors"
                                        >
                                            {{ __("View Document") }}
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4"
                                                viewBox="0 0 24 24"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M13 5h6v6h-2V8.414l-7.293 7.293-1.414-1.414L15.586 7H13V5z"
                                                />
                                                <path
                                                    d="M5 5h5V3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5h-2v5H5V5z"
                                                />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Action Form -->
            <div class="lg:col-span-1">
                <!-- Status Update Form -->
                <div class="sticky top-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3
                                class="text-sm font-medium text-gray-900 dark:text-white"
                            >
                                {{ __("Update Status") }}
                            </h3>
                            <div
                                class="p-1.5 bg-blue-100 dark:bg-blue-900/20 rounded"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600 dark:text-blue-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Status locked message -->
                        <div
                            v-if="isStatusLocked"
                            class="mb-4 p-3 bg-gray-50 dark:bg-gray-900/30 rounded border border-gray-200 dark:border-gray-700"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5 text-amber-500 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </svg>
                                <div>
                                    <div
                                        class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        {{ __("Status Locked") }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                    >
                                        {{
                                            __("This status cannot be modified")
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form
                            v-if="!isStatusLocked"
                            @submit.prevent="submitForm"
                        >
                            <!-- Current Status -->
                            <div
                                class="mb-4 p-3 bg-gray-50 dark:bg-gray-900/30 rounded border border-gray-200 dark:border-gray-700"
                            >
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    {{ __("Current Status") }}
                                </div>
                                <div class="flex items-center justify-between">
                                    <div
                                        :class="`px-2.5 py-1 rounded-full text-xs font-medium ${getStatusColorClass(
                                            data.status
                                        )}`"
                                    >
                                        {{ getStatusLabel(data.status) }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Last updated") }}:
                                        {{
                                            data.updated_at ||
                                            data.transaction?.updated
                                        }}
                                    </div>
                                </div>
                            </div>

                            <!-- Status Select -->
                            <div class="mb-4">
                                <label
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5"
                                >
                                    {{ __("Update Status to") }}
                                </label>
                                <select
                                    v-model="form.status"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400 dark:bg-gray-800 dark:text-white transition-colors"
                                    :class="{
                                        'border-red-300 dark:border-red-700':
                                            form.errors.status,
                                    }"
                                >
                                    <option value="">
                                        {{ __("Select new status...") }}
                                    </option>
                                    <option
                                        v-for="option in availableStatusOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <p
                                    v-if="form.errors.status"
                                    class="mt-1 text-xs text-red-600 dark:text-red-400"
                                >
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <!-- Form Actions -->
                            <div class="space-y-2">
                                <button
                                    type="submit"
                                    :disabled="
                                        form.processing ||
                                        form.status === data.status ||
                                        !form.status
                                    "
                                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    <svg
                                        v-if="form.processing"
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="3"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                    {{
                                        form.processing
                                            ? __("Updating...")
                                            : __("Update Status")
                                    }}
                                </button>

                                <button
                                    type="button"
                                    @click="resetForm"
                                    class="w-full py-2 px-4 border border-gray-300 dark:border-gray-600 rounded text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                                >
                                    {{ __("Reset Form") }}
                                </button>
                            </div>

                            <!-- Form Status -->
                            <div
                                v-if="form.recentlySuccessful"
                                class="mt-3 p-2.5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-green-500 dark:text-green-400 mr-1.5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    <span
                                        class="text-xs text-green-700 dark:text-green-300"
                                    >
                                        {{ __("Status updated successfully!") }}
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-transaction-layout>
</template>

<script setup>
import VAdminTransactionLayout from "@/components/VGeneralLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";

const page = usePage();
const data = ref({});

// Convert PHP array structure to JavaScript object
// PHP: $status = [
//     'pending' => ['processing'],
//     'processing' => ['under_review'],
//     'under_review' => ['approved', 'rejected'],
//     'approved' => ['waiting_for_return'],
//     'waiting_for_return' => ['refunding'],
//     'refunding' => ['completed'],
// ];
const statusWorkflow = {
    // Pending can go to Processing
    pending: [{ value: "processing", label: __("Processing") }],

    // Processing can go to Under Review
    processing: [{ value: "under_review", label: __("Under Review") }],

    // Under Review can go to Approved or Rejected
    under_review: [
        { value: "approved", label: __("Approved") },
        { value: "rejected", label: __("Rejected") },
    ],

    // Approved can go to Waiting for Return
    approved: [
        { value: "waiting_for_return", label: __("Waiting for Return") },
    ],

    // Waiting for Return can go to Refunding
    waiting_for_return: [{ value: "refunding", label: __("Refunding") }],

    // Refunding can go to Completed
    refunding: [{ value: "completed", label: __("Completed") }],

    // Final statuses - no further transitions allowed
    completed: [],
    rejected: [],
};

// Final statuses that cannot be modified
const finalStatuses = ["completed", "rejected"];

const form = useForm({
    status: "",
});

// Computed properties
const isStatusLocked = computed(() => {
    return finalStatuses.includes(data.value.status);
});

const availableStatusOptions = computed(() => {
    const currentStatus = data.value.status;
    if (!currentStatus || !statusWorkflow[currentStatus]) return [];
    return statusWorkflow[currentStatus];
});

onMounted(() => {
    data.value = page.props.data;
    form.status = data.value.status;
});

// Methods
const submitForm = () => {
    if (!form.status || form.status === data.value.status) return;

    form.put(data.value.web?.update, {
        preserveScroll: true,
        onSuccess: () => {
            data.value = page.props.data;
        },
    });
};

const resetForm = () => {
    form.reset();
    form.status = data.value.status;
};

const getStatusLabel = (status) => {
    const statusMap = {
        pending: __("Pending"),
        processing: __("Processing"),
        under_review: __("Under Review"),
        approved: __("Approved"),
        waiting_for_return: __("Waiting for Return"),
        refunding: __("Refunding"),
        completed: __("Completed"),
        rejected: __("Rejected"),
    };
    return statusMap[status] || status;
};

const getStatusColorClass = (status) => {
    const colors = {
        pending:
            "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300",
        processing:
            "bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300",
        under_review:
            "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300",
        approved:
            "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300",
        waiting_for_return:
            "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300",
        refunding:
            "bg-cyan-100 text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300",
        completed:
            "bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300",
        rejected:
            "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300",
    };
    return (
        colors[status] ||
        "bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300"
    );
};
</script>
