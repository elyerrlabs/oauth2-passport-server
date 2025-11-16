<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->
<template>
    <!-- Add Plan Button - Normal Style -->
    <button
        v-if="item?.id"
        @click="open"
        class="rounded-full w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white p-3 shadow-md transition"
        title="Update Plan"
    >
        <i class="mdi mdi-pencil text-lg"></i>
    </button>

    <button
        v-else
        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-xl px-6 py-3 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer font-semibold"
        @click="open"
    >
        <i class="mdi mdi-plus text-md md:text-lg"></i>
        <span>{{ __("Create New Plan") }}</span>
    </button>

    <!-- Create Plan Dialog -->
    <v-modal
        v-model="dialog"
        panel-class="w-full lg:w-7xl"
        :title="item?.id ? __('Edit Plan') : __('Create New Plan')"
    >
        <template #body>
            <div>
                <!-- Plan Information Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-1 sm:p-2 lg:p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-information text-blue-600 dark:text-blue-400 text-lg"
                            ></i>
                        </div>
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Plan Information") }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __("Basic plan details and description") }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <v-input
                            :label="__('Plan Name')"
                            v-model="form.name"
                            :error="form.errors.name"
                            :placeholder="__('e.g., VPN Pro')"
                            :required="true"
                        />
                    </div>

                    <div class="mt-4">
                        <v-editor
                            v-model="form.description"
                            :label="__('Description')"
                            :error="form.errors.description"
                            :required="true"
                            :placeholder="
                                __(
                                    'Describe the features and benefits of this plan...'
                                )
                            "
                        />
                    </div>

                    <div
                        class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6 items-end"
                    >
                        <v-switch
                            :label="__('Active Plan')"
                            v-model="form.active"
                            :error="form.errors.active"
                            :description="
                                __('Make this plan available for purchase')
                            "
                        />

                        <v-switch
                            :label="__('Enable Bonus')"
                            v-model="form.bonus_enabled"
                            :error="form.errors.bonus_enabled"
                            :description="__('Add bonus days to this plan')"
                        />

                        <v-input
                            v-if="form.bonus_enabled"
                            :label="__('Bonus Duration (Days)')"
                            v-model="form.bonus_duration"
                            :error="form.errors.bonus_duration"
                            type="number"
                            :placeholder="__('8')"
                            :description="__('Number of extra bonus days')"
                        />
                    </div>
                </div>

                <!-- Pricing Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-currency-usd text-green-600 dark:text-green-400 text-lg"
                            ></i>
                        </div>
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Pricing Configuration") }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{
                                    __(
                                        "Set up pricing options for different billing periods"
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <div v-if="form.prices.length" class="space-y-4">
                        <div
                            v-for="(price, index) in form.prices"
                            :key="price.id || `new-${index}`"
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 items-end"
                        >
                            <!-- Billing Period -->
                            <v-select
                                :label="__('Billing Period')"
                                :options="billing_periods"
                                label-key="name"
                                value-key="id"
                                v-model="price.billing_period"
                                :error="
                                    form.errors?.prices?.[index]?.billing_period
                                "
                                :required="true"
                            />

                            <!-- Currency -->
                            <v-select
                                :label="__('Currency')"
                                :options="currencies"
                                label-key="name"
                                value-key="code"
                                v-model="price.currency"
                                :error="form.errors?.prices?.[index]?.currency"
                                :required="true"
                            />

                            <!-- Amount -->
                            <v-input
                                v-model="price.amount"
                                :label="__('Amount')"
                                :error="form.errors?.prices?.[index]?.amount"
                                :required="true"
                                type="money"
                                :placeholder="__('0.00')"
                            />

                            <!-- Remove Price Button - Always visible for all prices -->
                            <div class="flex items-end h-full">
                                <button
                                    class="w-full h-[42px] bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white border border-red-500 dark:border-red-600 rounded-xl flex items-center justify-center gap-2 transition-all duration-200 transform hover:scale-105 cursor-pointer font-medium"
                                    @click="removePrice(price, index)"
                                    :disabled="deletingPriceId === price.id"
                                >
                                    <i
                                        v-if="deletingPriceId !== price.id"
                                        class="mdi mdi-delete text-lg"
                                    ></i>
                                    <i
                                        v-else
                                        class="mdi mdi-loading mdi-spin text-lg"
                                    ></i>
                                    {{ __("Remove") }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <v-error
                        v-if="!form.prices.length"
                        :error="form.errors?.prices"
                        class="mb-4"
                    />

                    <!-- Add Price Button -->
                    <button
                        class="w-full mt-4 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white border border-green-500 dark:border-green-600 rounded-xl px-4 py-3 flex items-center justify-center gap-2 transition-all duration-200 transform hover:scale-105 cursor-pointer font-semibold shadow-lg hover:shadow-xl"
                        @click="addPrice"
                    >
                        <i class="mdi mdi-plus-circle text-lg"></i>
                        {{ __("Add Pricing Option") }}
                    </button>
                </div>

                <!-- Scopes Section -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm"
                >
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-key-chain text-purple-600 dark:text-purple-400 text-lg"
                            ></i>
                        </div>
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Access Scopes") }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{
                                    __(
                                        "Configure service access and permissions"
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Service Selection -->
                    <div class="mb-6">
                        <v-select
                            :label="__('Select Service')"
                            :options="services"
                            label-key="name"
                            value-key="id"
                            v-model="selectedServiceId"
                            :error="form.errors?.scopes"
                            :clearable="true"
                            @change="onServiceChange"
                            :description="
                                __('Choose a service to configure access roles')
                            "
                            :placeholder="__('Select a service...')"
                        />
                    </div>

                    <!-- Scopes List -->
                    <div v-if="scopes.length" class="scopes-container">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-2 h-4 bg-blue-500 rounded-full"></div>
                            <h4
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Available Roles for") }}
                                <span
                                    class="text-blue-600 dark:text-blue-400"
                                    >{{
                                        selectedService?.name ||
                                        "Selected Service"
                                    }}</span
                                >
                            </h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div
                                v-for="(scope, index) in scopes"
                                :key="scope.id"
                                class="flex items-center p-4 border-2 rounded-xl transition-all duration-200 cursor-pointer group relative"
                                :class="
                                    hasScope(scope.id)
                                        ? 'border-green-500 bg-green-50 dark:bg-green-900/20 shadow-md scale-105'
                                        : 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-lg'
                                "
                                @click="toggleScope(scope.id)"
                            >
                                <!-- Delete Scope Button (only for update mode and assigned scopes) -->
                                <button
                                    v-if="
                                        item?.id &&
                                        hasScope(scope.id) &&
                                        scope.links?.revoke
                                    "
                                    class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all duration-200 transform hover:scale-110 z-10"
                                    @click.stop="deleteScope(scope)"
                                    :disabled="deletingScopeId === scope.id"
                                >
                                    <i
                                        v-if="deletingScopeId !== scope.id"
                                        class="mdi mdi-close text-sm"
                                    ></i>
                                    <i
                                        v-else
                                        class="mdi mdi-loading mdi-spin text-sm"
                                    ></i>
                                </button>

                                <div class="flex-shrink-0 mr-4">
                                    <div
                                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-200"
                                        :class="
                                            hasScope(scope.id)
                                                ? 'bg-green-500 text-white'
                                                : 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30'
                                        "
                                    >
                                        <i
                                            class="mdi mdi-account-key text-lg"
                                        ></i>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <div
                                        class="font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{
                                            scope.role?.name ||
                                            scope.gsr_id ||
                                            "No name"
                                        }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2"
                                    >
                                        {{
                                            scope.role?.description ||
                                            scope.gsr_id ||
                                            "No description available"
                                        }}
                                    </div>
                                    <div
                                        v-if="scope.gsr_id"
                                        class="text-xs text-gray-500 dark:text-gray-500 mt-1 font-mono"
                                    >
                                        ID: {{ scope.gsr_id }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors duration-200"
                                        :class="
                                            hasScope(scope.id)
                                                ? 'bg-green-500 border-green-500 text-white'
                                                : 'border-gray-300 dark:border-gray-500 group-hover:border-blue-500'
                                        "
                                    >
                                        <i
                                            v-if="hasScope(scope.id)"
                                            class="mdi mdi-check text-xs"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div v-else-if="loadingScopes" class="text-center py-8">
                        <div
                            class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-3"
                        >
                            <i
                                class="mdi mdi-loading mdi-spin text-blue-600 dark:text-blue-400 text-2xl"
                            ></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 font-medium">
                            {{ __("Loading available roles...") }}
                        </p>
                    </div>

                    <!-- No Scopes State -->
                    <div
                        v-else-if="selectedServiceId && !scopes.length"
                        class="text-center py-8"
                    >
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-3"
                        >
                            <i
                                class="mdi mdi-information-outline text-gray-400 dark:text-gray-500 text-2xl"
                            ></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">
                            {{ __("No roles available for this service") }}
                        </p>
                        <p
                            class="text-sm text-gray-400 dark:text-gray-500 mt-1"
                        >
                            {{
                                __(
                                    "Please contact administrator to configure roles"
                                )
                            }}
                        </p>
                    </div>

                    <!-- No Service Selected State -->
                    <div
                        v-else-if="!selectedServiceId"
                        class="text-center py-8"
                    >
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-3"
                        >
                            <i
                                class="mdi mdi-server text-gray-400 dark:text-gray-500 text-2xl"
                            ></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">
                            {{ __("Select a service to configure access") }}
                        </p>
                        <p
                            class="text-sm text-gray-400 dark:text-gray-500 mt-1"
                        >
                            {{ __("Choose a service from the dropdown above") }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dialog Actions -->
            <div
                class="flex justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
            >
                <button
                    class="px-6 py-3 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-105 cursor-pointer font-semibold"
                    @click="close"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white rounded-xl flex items-center justify-center space-x-2 transition-all duration-200 transform hover:scale-105 cursor-pointer font-semibold shadow-lg hover:shadow-xl"
                    @click="create"
                >
                    <i class="mdi mdi-check-circle text-lg"></i>
                    <span>{{
                        item?.id ? __("Update Plan") : __("Create Plan")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, computed } from "vue";
import VError from "@/components/VError.vue";
import VEditor from "@/components/VEditor.vue";
import VInput from "@/components/VInput.vue";
import VSwitch from "@/components/VSwitch.vue";
import VSelect from "@/components/VSelect.vue";
import VModal from "@/components/VModal.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();

const props = defineProps({
    item: {
        type: Object,
        default: () => [],
    },
});

// Props and emits
const emits = defineEmits(["created", "updated", "scopeDeleted"]);

// Refs and states
const dialog = ref(false);
const selectedServiceId = ref(null);
const loadingScopes = ref(false);
const currencies = ref([]);
const billing_periods = ref([]);
const services = ref([]);
const scopes = ref([]);
const deletingScopeId = ref(null);
const deletingPriceId = ref(null);

const form = useForm({
    name: "",
    description: "",
    active: false,
    bonus_enabled: false,
    bonus_duration: 0,
    trial_enabled: false,
    trial_duration: 0,
    scopes: [],
    prices: [],
});

// Computed
const selectedService = computed(() => {
    if (!selectedServiceId.value) return null;
    return (
        services.value.find(
            (service) => service.id === selectedServiceId.value
        ) || null
    );
});

// Methods
/**
 * Clean form and reset all states
 */
const clean = () => {
    form.resetAndClearErrors();
    selectedServiceId.value = null;
    scopes.value = [];
    loadingScopes.value = false;
    deletingScopeId.value = null;
    deletingPriceId.value = null;
};

/**
 * Close the dialog and clean form
 */
const close = () => {
    dialog.value = false;
    clean();
};

/**
 * Open the dialog and initialize form data
 */
const open = async () => {
    clean();

    if (props.item?.id) {
        // Initialize form with existing item data
        form.name = props.item.name || "";
        form.description = props.item.description || "";
        form.active = props.item.active || false;
        form.bonus_enabled = props.item.bonus_enabled || false;
        form.bonus_duration = props.item.bonus_duration || 0;
        form.trial_enabled = props.item.trial_enabled || false;
        form.trial_duration = props.item.trial_duration || 0;
        form.scopes = props.item.scopes?.map((scope) => scope.id) || [];
        form.prices = props.item.prices;

        // Auto-select the service if scopes exist
        if (props.item.scopes?.length > 0) {
            const firstScope = props.item.scopes[0];
            // In real data, the service comes inside the scope
            if (firstScope.service) {
                selectedServiceId.value = firstScope.service.id;
                await loadScopesForService(firstScope.service.id);
            }
        }
    }

    services.value = page.props.services;
    await getBillingPeriod();
    await getCurrencies();
    dialog.value = true;
};

/**
 * Add a new price row to the form
 */
const addPrice = () => {
    form.prices.push({
        billing_period: billing_periods.value.length
            ? billing_periods.value[0].id
            : "",
        currency: currencies.value.length ? currencies.value[0].code : "",
        amount: null,
    });
};

/**
 * Remove or delete a price
 * @param {Object} price - The price object to remove
 * @param {number} index - The index of the price in the array
 */
const removePrice = async (price, index) => {
    // If it's a new price (no ID), just remove it from the array
    if (!price.id) {
        form.prices.splice(index, 1);
        return;
    }

    // For existing prices, confirm and make API call
    if (!confirm(__("Are you sure you want to delete this price?"))) {
        return;
    }

    deletingPriceId.value = price.id;
    try {
        const response = await $server.delete(price.links.destroy);
        if (response.status === 200) {
            form.prices.splice(index, 1);
            $notify.success(__("Price deleted successfully"));
        }
    } catch (error) {
        $notify.error(
            error?.response?.data?.message || __("Failed to delete price")
        );
    } finally {
        deletingPriceId.value = null;
    }
};

/**
 * Create or update plan
 */
const create = () => {
    if (props.item?.id) {
        form.put(props.item.links.update, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                emits("updated");
                dialog.value = false;
                $notify.success(__("Plan has been updated successfully"));
                clean();
            },
        });
    } else {
        form.post(page.props.routes.plans, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                emits("created");
                dialog.value = false;
                $notify.success(__("New plan has been created successfully"));
                clean();
            },
        });
    }
};

/**
 * Fetch billing periods from API
 */
const getBillingPeriod = async () => {
    try {
        const res = await $server.get(
            page.props.api.transactions.billing_period
        );
        if (res.status === 200) {
            billing_periods.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

/**
 * Fetch currencies from API
 */
const getCurrencies = async () => {
    try {
        const res = await $server.get(page.props.api.transactions.currencies);
        if (res.status === 200) {
            currencies.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

/**
 * Handle service selection change
 * @param {string} serviceId - The selected service ID
 */
const onServiceChange = async (serviceId) => {
    scopes.value = [];
    if (!serviceId) return;
    await loadScopesForService(serviceId);
};

/**
 * Load scopes for the selected service
 * @param {string} serviceId - The service ID to load scopes for
 */
const loadScopesForService = async (serviceId) => {
    if (!serviceId) return;

    const service = services.value.find((s) => s.id === serviceId);
    if (!service) return;

    loadingScopes.value = true;
    try {
        if (Array.isArray(service.scopes)) {
            scopes.value = service.scopes;
        } else {
            scopes.value = [];
        }
    } catch {
        scopes.value = [];
    } finally {
        loadingScopes.value = false;
    }
};

/**
 * Check if a scope is currently selected
 * @param {string} id - The scope ID to check
 * @returns {boolean} - True if scope is selected
 */
const hasScope = (id) => {
    return form.scopes.includes(id);
};

/**
 * Toggle scope selection
 * @param {string} id - The scope ID to toggle
 */
const toggleScope = (id) => {
    const index = form.scopes.indexOf(id);
    if (index === -1) {
        form.scopes.push(id);
    } else {
        form.scopes.splice(index, 1);
    }
};

/**
 * Delete/revoke a scope from the plan
 * @param {Object} scope - The scope object to delete
 */
const deleteScope = async (scope) => {
    // Find the existing scope in props.item to get the correct revoke link
    const existingScope = props.item.scopes.find((s) => s.id === scope.id);

    if (!existingScope?.links?.revoke) {
        $notify.error(__("No revoke link available for this scope"));
        return;
    }

    if (!confirm(__("Are you sure you want to revoke this scope?"))) {
        return;
    }

    deletingScopeId.value = scope.id;
    try {
        const response = await $server.delete(existingScope.links.revoke);

        if (response.status === 200) {
            // Remove the scope from the form
            const formIndex = form.scopes.indexOf(scope.id);
            if (formIndex !== -1) {
                form.scopes.splice(formIndex, 1);
            }

            // Remove the scope from props.item scopes array
            const itemScopeIndex = props.item.scopes.findIndex(
                (s) => s.id === scope.id
            );
            if (itemScopeIndex !== -1) {
                props.item.scopes.splice(itemScopeIndex, 1);
            }

            // Emit event with the deleted scope ID
            emits("scopeDeleted", scope.id);

            $notify.success(__("Scope revoked successfully"));
        }
    } catch (error) {
        $notify.error(
            error?.response?.data?.message || __("Failed to revoke scope")
        );
    } finally {
        deletingScopeId.value = null;
    }
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Ensure delete button is aligned at the bottom */
.items-end {
    align-items: flex-end;
}

/* Smooth transitions for all interactive elements */
.bg-white,
.bg-gray-50,
.bg-blue-100,
.border-gray-200 {
    transition: all 0.3s ease-in-out;
}

/* Dark mode transitions */
.dark .bg-gray-800,
.dark .bg-gray-700,
.dark .bg-blue-900\/30,
.dark .border-gray-700,
.dark .border-gray-600 {
    transition: all 0.3s ease-in-out;
}

/* Position for delete scope button */
.relative {
    position: relative;
}

.absolute {
    position: absolute;
}

.-top-2 {
    top: -0.5rem;
}

.-right-2 {
    right: -0.5rem;
}

.z-10 {
    z-index: 10;
}
</style>
