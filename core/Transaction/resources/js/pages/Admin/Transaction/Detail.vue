<template>
    <v-layout>
        <template #aside>
            <v-item-menu
                :items="page.props.menus"
                icon="mdi mdi-apps text-2xl"
                :collapse="true"
            />
        </template>
        <template #main>
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
                        <i
                            class="mdi mdi-chevron-right text-gray-400 text-xs"
                        ></i>
                        <span
                            class="text-gray-900 dark:text-white font-medium"
                            >{{ data.code }}</span
                        >
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
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        __(
                                            "Complete financial transaction record",
                                        )
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
                                    {{
                                        formatCurrency(
                                            data.total,
                                            data.currency,
                                        )
                                    }}
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
                                    {{ data.payment_method || "N/A" }}
                                </p>
                                <p
                                    class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-mono truncate"
                                    :title="data.payment_intent_id"
                                >
                                    {{
                                        data.payment_intent_id
                                            ? data.payment_intent_id.substring(
                                                  0,
                                                  20,
                                              ) + "..."
                                            : ""
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
                                    {{ __("Customer") }}
                                </p>
                                <p
                                    class="text-sm font-bold text-gray-900 dark:text-white"
                                >
                                    {{ data.owner?.name }}
                                    {{ data.owner?.last_name }}
                                </p>
                                <p
                                    class="text-xs text-purple-600 dark:text-purple-400 mt-1 truncate"
                                    :title="data.owner?.email"
                                >
                                    {{ data.owner?.email }}
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

                    <!-- Package Details Section (NUEVO) -->
                    <div v-if="data.meta?.meta" class="px-6 pb-6">
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden"
                        >
                            <!-- Package Header -->
                            <div
                                class="px-5 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-package-variant-closed text-blue-600 dark:text-blue-400 text-lg"
                                        ></i>
                                    </div>
                                    <div>
                                        <h3
                                            class="font-bold text-gray-900 dark:text-white"
                                        >
                                            {{ __("Purchased Package") }}
                                        </h3>
                                        <p
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                data.meta.meta.name ||
                                                __("Plan Details")
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Plan Info -->
                            <div class="p-5 space-y-4">
                                <!-- Plan Name & Status -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4"
                                >
                                    <div
                                        class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
                                    >
                                        <i
                                            class="mdi mdi-tag text-blue-500 text-lg"
                                        ></i>
                                        <div>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ __("Plan") }}
                                            </p>
                                            <p
                                                class="text-sm font-bold text-gray-900 dark:text-white"
                                            >
                                                {{ data.meta.meta.name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
                                    >
                                        <i
                                            :class="
                                                data.meta.meta.active
                                                    ? 'mdi mdi-check-circle text-green-500'
                                                    : 'mdi mdi-close-circle text-red-500'
                                            "
                                            class="text-lg"
                                        ></i>
                                        <div>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ __("Status") }}
                                            </p>
                                            <p
                                                class="text-sm font-bold text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    data.meta.meta.active
                                                        ? __("Active")
                                                        : __("Inactive")
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
                                    >
                                        <i
                                            class="mdi mdi-calendar text-green-500 text-lg"
                                        ></i>
                                        <div>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ __("Period") }}
                                            </p>
                                            <p
                                                class="text-sm font-bold text-gray-900 dark:text-white"
                                            >
                                                {{ data.meta.start_at }} →
                                                {{ data.meta.end_at }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Details -->
                                <div
                                    v-if="data.meta.meta.price"
                                    class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200 dark:border-green-800"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div class="flex items-center gap-3">
                                            <i
                                                class="mdi mdi-cash text-green-600 dark:text-green-400 text-xl"
                                            ></i>
                                            <div>
                                                <p
                                                    class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wider"
                                                >
                                                    {{ __("Price") }}
                                                </p>
                                                <p
                                                    class="text-lg font-bold text-gray-900 dark:text-white"
                                                >
                                                    {{
                                                        data.meta.meta.price
                                                            .amount_format
                                                    }}
                                                    {{
                                                        data.meta.meta.price
                                                            .currency
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <span
                                            class="px-3 py-1 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 rounded-full text-xs font-medium capitalize"
                                        >
                                            {{
                                                data.meta.meta.price
                                                    .billing_period
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div
                                    v-if="
                                        data.meta.meta.description &&
                                        data.meta.meta.description !== '<p></p>'
                                    "
                                    class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
                                >
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"
                                    >
                                        {{ __("Description") }}
                                    </p>
                                    <div
                                        class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300"
                                        v-html="data.meta.meta.description"
                                    ></div>
                                </div>

                                <!-- Features: Trial & Bonus -->
                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-3"
                                >
                                    <div
                                        class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
                                    >
                                        <i
                                            class="mdi mdi-gift text-purple-500 text-lg"
                                        ></i>
                                        <div>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ __("Trial") }}
                                            </p>
                                            <p
                                                class="text-sm font-medium text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    data.meta.meta.trial_enabled
                                                        ? data.meta.meta
                                                              .trial_duration +
                                                          " " +
                                                          __("days")
                                                        : __("Not available")
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
                                    >
                                        <i
                                            class="mdi mdi-plus-circle text-amber-500 text-lg"
                                        ></i>
                                        <div>
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ __("Bonus") }}
                                            </p>
                                            <p
                                                class="text-sm font-medium text-gray-900 dark:text-white"
                                            >
                                                {{
                                                    data.meta.meta.bonus_enabled
                                                        ? data.meta.meta
                                                              .bonus_duration +
                                                          " " +
                                                          __("days")
                                                        : __("Not available")
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Scopes -->
                                <div
                                    v-if="data.meta.meta.scopes?.length"
                                    class="border-t border-gray-200 dark:border-gray-700 pt-4"
                                >
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3"
                                    >
                                        {{ __("Included Scopes") }}
                                    </p>
                                    <div class="space-y-2">
                                        <div
                                            v-for="scope in data.meta.meta
                                                .scopes"
                                            :key="scope.id"
                                            class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700"
                                        >
                                            <div
                                                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center shrink-0"
                                            >
                                                <i
                                                    class="mdi mdi-key text-indigo-600 dark:text-indigo-400 text-sm"
                                                ></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-medium text-gray-900 dark:text-white"
                                                >
                                                    {{ scope.gsr_id }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400"
                                                >
                                                    {{ scope.role?.name }} •
                                                    {{ scope.service?.name }}
                                                </p>
                                                <div
                                                    class="flex flex-wrap gap-1 mt-1.5"
                                                >
                                                    <span
                                                        v-if="scope.api_key"
                                                        class="text-xs px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded"
                                                        >API</span
                                                    >
                                                    <span
                                                        v-if="scope.web"
                                                        class="text-xs px-1.5 py-0.5 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded"
                                                        >Web</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transaction Code -->
                                <div
                                    class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg"
                                >
                                    <i
                                        class="mdi mdi-barcode text-gray-500 text-lg"
                                    ></i>
                                    <div>
                                        <p
                                            class="text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            {{ __("Transaction Code") }}
                                        </p>
                                        <p
                                            class="text-sm font-mono font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                data.meta.meta.transaction_code
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="px-6 pb-6">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
                        >
                            <div
                                v-for="(value, key) in filteredItem"
                                :key="key"
                                class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors"
                            >
                                <div
                                    class="w-8 h-8 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center shrink-0"
                                >
                                    <i
                                        :class="getFieldIcon(key)"
                                        class="text-gray-500 dark:text-gray-400 text-sm"
                                    ></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider truncate"
                                    >
                                        {{ formatKey(key) }}
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                        :title="formatValue(value)"
                                    >
                                        {{ formatValue(value) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expandable Sections -->
                    <div class="border-t border-gray-200 dark:border-gray-800">
                        <!-- Payment Response -->
                        <div v-if="data.response">
                            <button
                                @click="toggleExpansion('response')"
                                class="flex items-center justify-between w-full px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
                            >
                                <div class="flex items-center gap-3">
                                    <i
                                        class="mdi mdi-code-json text-indigo-500 text-lg"
                                    ></i>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white"
                                        >{{
                                            __("Payment Gateway Response")
                                        }}</span
                                    >
                                </div>
                                <i
                                    :class="
                                        expandedSections.response
                                            ? 'mdi mdi-chevron-up'
                                            : 'mdi mdi-chevron-down'
                                    "
                                    class="text-gray-400"
                                ></i>
                            </button>
                            <div
                                v-if="expandedSections.response"
                                class="border-t border-gray-200 dark:border-gray-700"
                            >
                                <pre
                                    class="text-xs text-green-400 bg-slate-950 p-4 max-h-80 overflow-y-auto font-mono"
                                    >{{ prettyJSON(data.response) }}</pre
                                >
                            </div>
                        </div>

                        <!-- Raw Metadata -->
                        <div
                            v-if="data.meta"
                            class="border-t border-gray-200 dark:border-gray-800"
                        >
                            <button
                                @click="toggleExpansion('meta')"
                                class="flex items-center justify-between w-full px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
                            >
                                <div class="flex items-center gap-3">
                                    <i
                                        class="mdi mdi-code-braces text-amber-500 text-lg"
                                    ></i>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white"
                                        >{{ __("Raw Metadata") }}</span
                                    >
                                </div>
                                <i
                                    :class="
                                        expandedSections.meta
                                            ? 'mdi mdi-chevron-up'
                                            : 'mdi mdi-chevron-down'
                                    "
                                    class="text-gray-400"
                                ></i>
                            </button>
                            <div
                                v-if="expandedSections.meta"
                                class="border-t border-gray-200 dark:border-gray-700"
                            >
                                <pre
                                    class="text-xs text-amber-400 bg-slate-950 p-4 max-h-80 overflow-y-auto font-mono"
                                    >{{ prettyJSON(data.meta) }}</pre
                                >
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
                                {{
                                    formatDate(data.updated || data.created)
                                }}</span
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
        </template>
    </v-layout>
</template>

<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import VButton from "@/components/VButton.vue";
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const expandedSections = ref({
    response: false,
    meta: false,
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
        "payment_url",
        "payment_intent_id",
        "payment_method_id",
        "session_id",
    ];
    return Object.keys(props.data)
        .filter(
            (key) =>
                !exclude.includes(key) &&
                props.data[key] !== null &&
                props.data[key] !== undefined &&
                props.data[key] !== "",
        )
        .reduce((acc, key) => {
            acc[key] = props.data[key];
            return acc;
        }, {});
});

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

const getFieldIcon = (key) => {
    const icons = {
        type: "mdi mdi-shape-outline",
        billing_period: "mdi mdi-calendar-range",
        renew: "mdi mdi-autorenew",
        cancellation_at: "mdi mdi-cancel",
        activated: "mdi mdi-account-check",
        created: "mdi mdi-calendar-plus",
        updated: "mdi mdi-calendar-edit",
        partner_commission_rate: "mdi mdi-percent",
    };
    return icons[key] || "mdi mdi-circle-small";
};

const formatKey = (key) =>
    key.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());

const formatValue = (value) => {
    if (value === null || value === undefined) return "N/A";
    if (typeof value === "boolean") return value ? __("Yes") : __("No");
    if (typeof value === "object") return JSON.stringify(value);
    return String(value);
};

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

const prettyJSON = (data) => {
    try {
        return JSON.stringify(
            typeof data === "string" ? JSON.parse(data) : data,
            null,
            2,
        );
    } catch {
        return String(data);
    }
};

const toggleExpansion = (section) => {
    expandedSections.value[section] = !expandedSections.value[section];
};

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
        customer: props.data.owner?.email,
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
