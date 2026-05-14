<template>
    <div>
        <v-button
            @click="dialog = true"
            icon="mdi mdi-eye-outline"
            :title="__('View Details')"
            round
            variant="success"
        />

        <v-modal
            v-model="dialog"
            :title="__('Transaction Details')"
            panel-class="w-full lg:w-7xl"
        >
            <template #body>
                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-7xl max-h-[90vh] overflow-hidden border border-gray-200 dark:border-gray-800"
                >
                    <!-- Modern Header with Gradient -->
                    <v-header
                        :title="__('Transaction Overview')"
                        :description="
                            __('Complete financial transaction record')
                        "
                    >
                    </v-header>

                    <!-- Content Area -->
                    <div class="overflow-y-auto max-h-[calc(90vh-200px)]">
                        <!-- Metrics Cards -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-gray-50 dark:bg-gray-800/30 border-b border-gray-200 dark:border-gray-800"
                        >
                            <!-- Transaction ID -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Transaction ID") }}
                                        </p>
                                        <p
                                            class="text-sm font-mono font-semibold text-gray-900 dark:text-white mt-1 truncate"
                                        >
                                            {{ item.code || item.id }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-identifier text-blue-600 dark:text-blue-400 text-sm"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Amount") }}
                                        </p>
                                        <p
                                            class="text-2xl font-bold text-gray-900 dark:text-white mt-1"
                                        >
                                            {{
                                                formatCurrency(
                                                    item.total,
                                                    item.currency,
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-currency-usd text-green-600 dark:text-green-400 text-sm"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Status") }}
                                        </p>
                                        <div class="mt-1">
                                            <span
                                                :class="statusBadgeClass"
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-semibold"
                                            >
                                                <i
                                                    :class="statusIconClass"
                                                    class="text-xs"
                                                ></i>
                                                {{ capitalize(item.status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-check-circle text-purple-600 dark:text-purple-400 text-sm"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                                        >
                                            {{ __("Payment Method") }}
                                        </p>
                                        <p
                                            class="text-sm font-semibold text-gray-900 dark:text-white mt-1 capitalize"
                                        >
                                            {{ item.payment_method || "N/A" }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-credit-card text-orange-600 dark:text-orange-400 text-sm"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Key Details -->
                        <div
                            class="p-6 border-b border-gray-200 dark:border-gray-800"
                        >
                            <div class="flex items-center gap-2 mb-4">
                                <div
                                    class="w-1 h-6 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"
                                ></div>
                                <i
                                    class="mdi mdi-information-outline text-gray-600 dark:text-gray-400 text-lg"
                                ></i>
                                <h3
                                    class="text-base font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ __("Key Transaction Details") }}
                                </h3>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="(value, key) in filteredItem"
                                    :key="key"
                                    class="group"
                                >
                                    <div
                                        class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 border border-gray-200 dark:border-gray-700 group-hover:border-blue-300 dark:group-hover:border-blue-600 transition-all"
                                    >
                                        <p
                                            class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1"
                                        >
                                            {{ formatKey(key) }}
                                        </p>
                                        <p
                                            class="text-sm font-semibold text-gray-900 dark:text-white break-words"
                                        >
                                            {{ formatValue(value) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expandable Sections -->
                        <div class="p-6 space-y-4">
                            <!-- Payment Response -->
                            <div
                                v-if="item.response"
                                class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                            >
                                <button
                                    @click="toggleExpansion('response')"
                                    class="flex items-center justify-between w-full p-4 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center"
                                        >
                                            <i
                                                class="mdi mdi-code-json text-indigo-600 dark:text-indigo-400 text-lg"
                                            ></i>
                                        </div>
                                        <div class="text-left">
                                            <p
                                                class="font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    __(
                                                        "Payment Gateway Response",
                                                    )
                                                }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{
                                                    __(
                                                        "Raw response from payment processor",
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <i
                                        :class="[
                                            'mdi text-xl text-gray-400 transition-transform duration-300',
                                            expandedSections.response
                                                ? 'mdi-chevron-up'
                                                : 'mdi-chevron-down',
                                        ]"
                                    ></i>
                                </button>
                                <div
                                    v-if="expandedSections.response"
                                    class="border-t border-gray-200 dark:border-gray-700"
                                >
                                    <div class="bg-slate-950 p-4">
                                        <pre
                                            class="text-xs text-green-400 whitespace-pre-wrap overflow-x-auto font-mono max-h-96 overflow-y-auto"
                                            >{{
                                                prettyJSON(item.response)
                                            }}</pre
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Meta Information -->
                            <div
                                v-if="item.meta"
                                class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                            >
                                <button
                                    @click="toggleExpansion('meta')"
                                    class="flex items-center justify-between w-full p-4 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center"
                                        >
                                            <i
                                                class="mdi mdi-database-outline text-amber-600 dark:text-amber-400 text-lg"
                                            ></i>
                                        </div>
                                        <div class="text-left">
                                            <p
                                                class="font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    __(
                                                        "Metadata & Subscription Info",
                                                    )
                                                }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{
                                                    __(
                                                        "Transaction metadata and subscription details",
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <i
                                        :class="[
                                            'mdi text-xl text-gray-400 transition-transform duration-300',
                                            expandedSections.meta
                                                ? 'mdi-chevron-up'
                                                : 'mdi-chevron-down',
                                        ]"
                                    ></i>
                                </button>
                                <div
                                    v-if="expandedSections.meta"
                                    class="border-t border-gray-200 dark:border-gray-700"
                                >
                                    <div class="bg-slate-950 p-4">
                                        <pre
                                            class="text-xs text-amber-400 whitespace-pre-wrap overflow-x-auto font-mono max-h-96 overflow-y-auto"
                                            >{{ prettyJSON(item.meta) }}</pre
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Information -->
                            <div
                                v-if="item.owner"
                                class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                            >
                                <button
                                    @click="toggleExpansion('customer')"
                                    class="flex items-center justify-between w-full p-4 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center"
                                        >
                                            <i
                                                class="mdi mdi-account-circle text-cyan-600 dark:text-cyan-400 text-lg"
                                            ></i>
                                        </div>
                                        <div class="text-left">
                                            <p
                                                class="font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{ __("Customer Information") }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{
                                                    __(
                                                        "Customer details and contact information",
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <i
                                        :class="[
                                            'mdi text-xl text-gray-400 transition-transform duration-300',
                                            expandedSections.customer
                                                ? 'mdi-chevron-up'
                                                : 'mdi-chevron-down',
                                        ]"
                                    ></i>
                                </button>
                                <div
                                    v-if="expandedSections.customer"
                                    class="border-t border-gray-200 dark:border-gray-700"
                                >
                                    <div
                                        class="p-4 bg-gray-50 dark:bg-gray-800/30"
                                    >
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                        >
                                            <div
                                                class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                                            >
                                                <i
                                                    class="mdi mdi-account text-gray-400 text-lg"
                                                ></i>
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400"
                                                    >
                                                        {{ __("Full Name") }}
                                                    </p>
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-white"
                                                    >
                                                        {{ item.owner.name }}
                                                        {{
                                                            item.owner.last_name
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                                            >
                                                <i
                                                    class="mdi mdi-email text-gray-400 text-lg"
                                                ></i>
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400"
                                                    >
                                                        {{
                                                            __("Email Address")
                                                        }}
                                                    </p>
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-white"
                                                    >
                                                        {{ item.owner.email }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div
                        class="border-t border-gray-200 dark:border-gray-800 px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex flex-col sm:flex-row justify-between items-center gap-4"
                    >
                        <div
                            class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2"
                        >
                            <i class="mdi mdi-calendar-clock text-base"></i>
                            <span
                                >{{ __("Processed") }}:
                                {{ formatDate(item.created) }}</span
                            >
                        </div>
                        <div class="flex gap-3">
                            <v-button
                                @click="copyTransactionData"
                                :label="__('Copy data')"
                                icon="mdi mdi-content-copy"
                            />
                        </div>
                    </div>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import VButton from "@/components/VButton.vue";
import VModal from "@/components/VModal.vue";
import VHead from "@/components/VHead.vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const dialog = ref(false);
const expandedSections = ref({
    response: false,
    meta: false,
    customer: false,
});

const filteredItem = computed(() => {
    const exclude = [
        "response",
        "meta",
        "links",
        "id",
        "code",
        "total",
        "currency",
        "status",
        "payment_method",
        "owner",
        "refund",
    ];
    return Object.keys(props.item)
        .filter(
            (key) =>
                !exclude.includes(key) &&
                props.item[key] !== null &&
                props.item[key] !== undefined &&
                props.item[key] !== "",
        )
        .reduce((acc, key) => {
            acc[key] = props.item[key];
            return acc;
        }, {});
});

const statusBadgeClass = computed(() => {
    const status = props.item.status?.toLowerCase();
    switch (status) {
        case "successful":
        case "succeeded":
            return "bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800";
        case "pending":
            return "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800";
        case "failed":
            return "bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800";
        default:
            return "bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-700";
    }
});

const statusIconClass = computed(() => {
    const status = props.item.status?.toLowerCase();
    switch (status) {
        case "successful":
        case "succeeded":
            return "mdi mdi-check-circle";
        case "pending":
            return "mdi mdi-clock-outline";
        case "failed":
            return "mdi mdi-close-circle";
        default:
            return "mdi mdi-circle-outline";
    }
});

const formatKey = (key) =>
    key.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());

const formatValue = (value) => {
    if (value === null || value === undefined) return "N/A";
    if (typeof value === "boolean") return value ? "Yes" : "No";
    if (typeof value === "object") {
        try {
            return JSON.stringify(value, null, 2);
        } catch {
            return String(value);
        }
    }
    if (typeof value === "string" && value.length > 100) {
        return value.substring(0, 100) + "...";
    }
    return value;
};

const formatCurrency = (amount, currency) => {
    if (!amount) return "N/A";
    const formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: currency || "USD",
    });
    return formatter.format(amount);
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
};

const capitalize = (str) =>
    str ? str.charAt(0).toUpperCase() + str.slice(1) : "N/A";

const prettyJSON = (jsonData) => {
    try {
        const parsed =
            typeof jsonData === "string" ? JSON.parse(jsonData) : jsonData;
        return JSON.stringify(parsed, null, 2);
    } catch (e) {
        return String(jsonData);
    }
};

const toggleExpansion = (section) => {
    expandedSections.value[section] = !expandedSections.value[section];
};

const copyTransactionData = () => {
    const dataToCopy = {
        transaction_id: props.item.id,
        code: props.item.code,
        amount: props.item.total,
        currency: props.item.currency,
        status: props.item.status,
        payment_method: props.item.payment_method,
        customer: props.item.owner,
        timestamp: props.item.created,
    };

    navigator.clipboard
        .writeText(JSON.stringify(dataToCopy, null, 2))
        .then(() => $notify?.success?.("Transaction data copied to clipboard"))
        .catch(() => $notify?.error?.("Failed to copy data"));
};
</script>
