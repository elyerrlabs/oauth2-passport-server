<template>
    <v-main-layout>
        <div class="space-y-6">
            <!-- Breadcrumb & Actions Bar -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
            >
                <nav class="flex items-center gap-2 text-sm">
                    <button
                        @click="goToIndex"
                        class="inline-flex items-center gap-2 px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                    >
                        <i class="mdi mdi-arrow-left"></i>
                        <span>{{ __("Back to Transactions") }}</span>
                    </button>
                    <i class="mdi mdi-chevron-right text-gray-400 text-xs"></i>
                    <span class="text-gray-900 dark:text-white font-medium">{{
                        data.code
                    }}</span>
                </nav>

                <div class="flex items-center gap-2">
                    <span
                        :class="statusBadgeClass"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold"
                    >
                        <i :class="statusIconClass"></i>
                        {{ capitalize(data.status) }}
                    </span>
                </div>
            </div>

            <!-- Main Content Card -->
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden"
            >
                <!-- Header -->
                <div
                    class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800/50 dark:to-gray-900"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-receipt text-blue-600 dark:text-blue-400 text-xl"
                            ></i>
                        </div>
                        <div>
                            <h2
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                {{ __("Transaction Details") }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{
                                    __("Complete financial transaction record")
                                }}
                                • {{ data.code }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6"
                >
                    <!-- Amount Card -->
                    <div
                        class="relative overflow-hidden bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800"
                    >
                        <div
                            class="absolute top-0 right-0 w-20 h-20 -mr-6 -mt-6 bg-green-500/10 rounded-full"
                        ></div>
                        <div class="relative">
                            <p
                                class="text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider mb-1"
                            >
                                {{ __("Total Amount") }}
                            </p>
                            <p
                                class="text-2xl font-bold text-gray-900 dark:text-white"
                            >
                                {{ formatCurrency(data.total, data.currency) }}
                            </p>
                            <p
                                class="text-xs text-green-600 dark:text-green-400 mt-1 capitalize"
                            >
                                {{ data.billing_period || __("One-time") }}
                            </p>
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div
                        class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-blue-200 dark:border-blue-800"
                    >
                        <div
                            class="absolute top-0 right-0 w-20 h-20 -mr-6 -mt-6 bg-blue-500/10 rounded-full"
                        ></div>
                        <div class="relative">
                            <p
                                class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1"
                            >
                                {{ __("Payment Method") }}
                            </p>
                            <p
                                class="text-lg font-bold text-gray-900 dark:text-white capitalize"
                            >
                                {{ data.payment_method }}
                            </p>
                            <p
                                v-if="data.payment_intent_id"
                                class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-mono truncate"
                                :title="data.payment_intent_id"
                            >
                                {{
                                    data.payment_intent_id.substring(0, 20) +
                                    "..."
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Customer Card -->
                    <div
                        class="relative overflow-hidden bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800"
                    >
                        <div
                            class="absolute top-0 right-0 w-20 h-20 -mr-6 -mt-6 bg-purple-500/10 rounded-full"
                        ></div>
                        <div class="relative">
                            <p
                                class="text-xs font-medium text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1"
                            >
                                {{ __("Activated") }}
                            </p>
                            <p
                                class="text-sm font-bold text-gray-900 dark:text-white"
                            >
                                {{ data.activated }}
                            </p>
                        </div>
                    </div>

                    <!-- Date Card -->
                    <div
                        class="relative overflow-hidden bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-5 border border-amber-200 dark:border-amber-800"
                    >
                        <div
                            class="absolute top-0 right-0 w-20 h-20 -mr-6 -mt-6 bg-amber-500/10 rounded-full"
                        ></div>
                        <div class="relative">
                            <p
                                class="text-xs font-medium text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1"
                            >
                                {{ __("Date") }}
                            </p>
                            <p
                                class="text-sm font-bold text-gray-900 dark:text-white"
                            >
                                {{ formatDate(data.created) }}
                            </p>
                            <p
                                class="text-xs text-amber-600 dark:text-amber-400 mt-1"
                            >
                                {{ data.renew ? __("Renewal") : __("New") }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div class="px-6 pb-6">
                    <h3
                        class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4"
                    >
                        {{ __("Transaction Information") }}
                    </h3>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
                    >
                        <!-- Type -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-shape-outline text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Type") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white capitalize"
                                >
                                    {{ data.type }}
                                </p>
                            </div>
                        </div>

                        <!-- Billing Period -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-calendar-range text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Billing Period") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white capitalize"
                                >
                                    {{ data.billing_period }}
                                </p>
                            </div>
                        </div>

                        <!-- Cents -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-currency-usd-circle-outline text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Cents") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white font-mono"
                                >
                                    {{ data.cents }}
                                </p>
                            </div>
                        </div>

                        <!-- Payment Intent ID -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-identifier text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Payment Intent ID") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white font-mono truncate"
                                    :title="data.payment_intent_id"
                                >
                                    {{ data.payment_intent_id }}
                                </p>
                            </div>
                        </div>

                        <!-- Payment Method ID -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-credit-card-outline text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Payment Method ID") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white font-mono truncate"
                                    :title="data.payment_method_id"
                                >
                                    {{ data.payment_method_id }}
                                </p>
                            </div>
                        </div>

                        <!-- Activated -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-account-check text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Activated") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    {{ data.activated }}
                                </p>
                            </div>
                        </div>

                        <!-- Created -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-calendar-plus text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Created") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    {{ formatDate(data.created) }}
                                </p>
                            </div>
                        </div>

                        <!-- Updated -->
                        <div
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-calendar-edit text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Updated") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    {{ formatDate(data.updated) }}
                                </p>
                            </div>
                        </div>

                        <!-- Session ID -->
                        <div
                            v-if="data.session_id"
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-session text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Session ID") }}
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-white font-mono truncate"
                                    :title="data.session_id"
                                >
                                    {{ data.session_id }}
                                </p>
                            </div>
                        </div>

                        <!-- Payment URL -->
                        <div
                            v-if="data.payment_url"
                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                        >
                            <div
                                class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                            >
                                <i
                                    class="mdi mdi-receipt-text text-gray-500 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                >
                                    {{ __("Receipt") }}
                                </p>
                                <a
                                    :href="data.payment_url"
                                    target="_blank"
                                    class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline truncate block"
                                    :title="data.payment_url"
                                >
                                    {{ __("View Stripe Receipt") }}
                                    <i
                                        class="mdi mdi-open-in-new text-xs ml-1"
                                    ></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div
                    class="border-t border-gray-200 dark:border-gray-800 px-6 py-4 bg-gray-50 dark:bg-gray-800/30 flex flex-col sm:flex-row justify-between items-center gap-4"
                >
                    <div
                        class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400"
                    >
                        <i class="mdi mdi-update"></i>
                        <span
                            >{{ __("Last updated") }}:
                            {{ formatDate(data.updated) }}</span
                        >
                    </div>
                    <div class="flex gap-2">
                        <v-button
                            @click="goToIndex"
                            :label="__('Back to Transactions')"
                            icon="mdi mdi-arrow-left"
                            variant="secondary"
                            size="sm"
                        />
                        <v-button
                            @click="copyData"
                            :label="__('Copy Data')"
                            icon="mdi mdi-content-copy"
                            variant="primary"
                            size="sm"
                        />
                        <v-button
                            v-if="data.payment_url"
                            @click="openPaymentUrl"
                            :label="__('View Receipt')"
                            icon="mdi mdi-open-in-new"
                            variant="success"
                            size="sm"
                        />
                    </div>
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import VButton from "@/components/VButton.vue";
import VMainLayout  from "@/components/VMainLayout.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const statusBadgeClass = computed(() => {
    const map = {
        successful:
            "bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800",
        succeeded:
            "bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800",
        pending:
            "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800",
        failed: "bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800",
        refunded:
            "bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-800",
    };
    return (
        map[props.data.status?.toLowerCase()] ||
        "bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-700"
    );
});

const statusIconClass = computed(() => {
    const map = {
        successful: "mdi mdi-check-circle",
        succeeded: "mdi mdi-check-circle",
        pending: "mdi mdi-clock-outline",
        failed: "mdi mdi-close-circle",
        refunded: "mdi mdi-cash-refund",
    };
    return map[props.data.status?.toLowerCase()] || "mdi mdi-circle-outline";
});

const formatCurrency = (amount, currency) => {
    if (!amount) return "N/A";
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: currency || "USD",
    }).format(amount);
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const capitalize = (str) =>
    str ? str.charAt(0).toUpperCase() + str.slice(1) : "N/A";

const goToIndex = () => {
    if (props.data.links?.index) {
        router.visit(props.data.links.index);
    }
};

const copyData = () => {
    const data = {
        code: props.data.code,
        amount: props.data.total,
        currency: props.data.currency,
        status: props.data.status,
        payment_method: props.data.payment_method,
        customer: props.data.activated,
        date: props.data.created,
    };
    navigator.clipboard
        .writeText(JSON.stringify(data, null, 2))
        .then(() => window.$notify?.success?.(__("Copied to clipboard")))
        .catch(() => window.$notify?.error?.(__("Failed to copy")));
};

const openPaymentUrl = () => {
    if (props.data.payment_url) {
        window.open(props.data.payment_url, "_blank");
    }
};
</script>
