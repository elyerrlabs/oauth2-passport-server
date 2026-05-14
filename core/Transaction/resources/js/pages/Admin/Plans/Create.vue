<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
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
            <v-head
                :title="__('Plan Information')"
                :description="__('Basic plan details and description')"
            ></v-head>

            <div
                class="grid grid-cols-1 lg:grid-cols-4 gap-2 p-4 my-4 border border-gray-200 dark:border-gray-400 shadow rounded"
            >
                <v-input
                    :label="__('Plan Name')"
                    v-model="form.name"
                    :error="form.errors.name"
                    :placeholder="__('e.g., VPN Pro')"
                    :required="true"
                />

                <v-switch
                    :label="__('Active Plan')"
                    v-model="form.active"
                    :error="form.errors.active"
                    :description="__('Make this plan available for purchase')"
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

            <div class="mt-4">
                <v-editor
                    v-model="form.description"
                    :label="__('Description')"
                    :error="form.errors.description"
                    :required="true"
                    :placeholder="
                        __('Describe the features and benefits of this plan...')
                    "
                />
            </div>

            <!-- Pricing Section -->
            <div
                class="p-4 my-4 border border-gray-200 dark:border-gray-400 shadow rounded"
            >
                <v-head
                    :title="__('Pricing Configuration')"
                    :description="
                        __(
                            'Set up pricing options for different billing periods',
                        )
                    "
                >
                    <template #actions>
                        <v-button
                            :title="__('Add Pricing Option')"
                            :label="__('Add Pricing Option')"
                            @click="addPrice"
                            icon="mdi mdi-plus-circle"
                            variant="warning"
                            size="xs"
                        />
                    </template>
                </v-head>

                <div v-if="form.prices?.length" class="space-y-4">
                    <div
                        v-for="(price, index) in form.prices"
                        :key="price.id || `new-${index}`"
                        class="flex justify-around items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600"
                    >
                        <div
                            class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-2"
                        >
                            <v-select
                                :label="__('Billing Period')"
                                :options="periods"
                                label-key="name"
                                value-key="id"
                                v-model="price.billing_period"
                                :error="
                                    form.errors?.prices?.[index]?.billing_period
                                "
                                :required="true"
                            />

                            <v-select
                                :label="__('Currency')"
                                :options="currencies"
                                label-key="name"
                                value-key="code"
                                v-model="price.currency"
                                :error="form.errors?.prices?.[index]?.currency"
                                :required="true"
                            />

                            <v-input
                                v-model="price.amount"
                                :label="__('Amount')"
                                :error="form.errors?.prices?.[index]?.amount"
                                :required="true"
                                type="money"
                                :placeholder="__('0.00')"
                            />
                        </div>

                        <div class="flex shrink-0">
                            <v-button
                                @click="removePrice(price, index)"
                                :disabled="deletingPriceId === price.id"
                                :icon="
                                    deletingPriceId !== price.id
                                        ? 'mdi mdi-delete text-lg'
                                        : 'mdi mdi-loading mdi-spin text-lg'
                                "
                                :label="__('Remove')"
                                :delete="__('Remove')"
                                variant="danger"
                                size="xs"
                            />
                        </div>
                    </div>
                </div>

                <v-error
                    v-if="!form.prices?.length"
                    :error="form.errors?.prices"
                    class="mb-4"
                />
            </div>

            <!-- Access Scopes Section -->
            <div
                class="p-4 my-4 border border-gray-200 dark:border-gray-400 shadow rounded"
            >
                <v-head
                    :title="__('Access Scopes')"
                    :description="
                        __(
                            'Choose which service features and permissions this plan will grant to customers',
                        )
                    "
                />

                <!-- Selector de Servicio -->
                <div class="mb-6">
                    <v-select
                        :label="__('Choose Service')"
                        v-model="selectedServiceId"
                        :options="services"
                        label-key="name"
                        value-key="id"
                        searchable
                        @search="searchServices"
                        :placeholder="__('Search service...')"
                    />
                </div>

                <!-- Scopes disponibles del servicio seleccionado -->
                <div
                    v-if="selectedServiceId && filteredServiceScopes.length"
                    class="mb-4"
                >
                    <p
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Available scopes for") }}:
                        {{ selectedService?.name }}
                    </p>
                    <div class="space-y-2">
                        <div
                            v-for="scope in filteredServiceScopes"
                            :key="scope.id"
                            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"
                        >
                            <div class="flex-1">
                                <p
                                    class="font-medium text-gray-800 dark:text-gray-200"
                                >
                                    {{ scope.gsr_id }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ scope.role?.name }} -
                                    {{ scope.role?.description }}
                                </p>
                                <div class="flex gap-2 mt-1">
                                    <span
                                        v-if="scope.api_key"
                                        class="text-xs px-2 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded"
                                    >
                                        API
                                    </span>
                                    <span
                                        v-if="scope.web"
                                        class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded"
                                    >
                                        Web
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <v-button
                                    v-if="!isScopeSelected(scope.id)"
                                    @click="addScope(scope)"
                                    :label="__('Add')"
                                    icon="mdi mdi-plus"
                                    variant="success"
                                    size="xs"
                                />
                                <v-button
                                    v-else
                                    @click="removeScope(scope.id)"
                                    :label="__('Remove')"
                                    icon="mdi mdi-delete"
                                    variant="danger"
                                    size="xs"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-else-if="selectedServiceId"
                    class="text-gray-500 dark:text-gray-400 text-sm py-4"
                >
                    {{ __("No scopes available for this service") }}
                </div>

                <!-- Lista de scopes seleccionados agrupados por servicio -->
                <div v-if="groupedSelectedScopes.length" class="mt-6">
                    <p
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                    >
                        {{ __("Selected scopes") }}
                    </p>
                    <div
                        v-for="group in groupedSelectedScopes"
                        :key="group.service.id"
                        class="mb-4"
                    >
                        <p
                            class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase"
                        >
                            {{ group.service.name }}
                        </p>
                        <div class="space-y-1">
                            <div
                                v-for="scope in group.scopes"
                                :key="scope.id"
                                class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700/50 rounded border border-gray-200 dark:border-gray-600"
                            >
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-800 dark:text-gray-200"
                                    >
                                        {{ scope.gsr_id }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400"
                                    >
                                        {{ scope.role?.name }}
                                    </p>
                                </div>
                                <div class="">
                                    <v-button
                                        @click="removeScope(scope.id)"
                                        :label="__('Remove')"
                                        icon="mdi mdi-close"
                                        variant="danger"
                                        size="xs"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <v-error :error="form.errors?.scopes" class="mt-4" />
            </div>

            <!-- Dialog Actions -->
            <div
                class="flex justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50"
            >
                <v-button
                    @click="close"
                    :label="__('Cancel')"
                    variant="danger"
                />
                <v-button
                    @click="handled"
                    :disabled="loading"
                    :label="data?.id ? __('Update Plan') : __('Create Plan')"
                    icon="mdi mdi-check-circle "
                    variant="success"
                />
            </div>
        </template>
    </v-layout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import VError from "@/components/VError.vue";
import VEditor from "@/components/VEditor.vue";
import VInput from "@/components/VInput.vue";
import VHead from "@/components/VHead.vue";
import VSwitch from "@/components/VSwitch.vue";
import VSelect from "@/components/VSelect.vue";
import VButton from "@/components/VButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();

const props = defineProps({
    data: {
        type: Object,
        default: () => {},
    },
    currencies: {
        type: Object,
        default: () => {},
    },
    periods: {
        type: Object,
        default: () => {},
    },
});

const selectedServiceId = ref(null);
const service_search_by_name = ref("");
const services = ref([]);
const selectedScopesMap = ref(new Map());
const deletingPriceId = ref(null);
const loading = ref(false);

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

// Computed properties
const selectedService = computed(() => {
    return services.value.find((s) => s.id === selectedServiceId.value);
});

const filteredServiceScopes = computed(() => {
    if (!selectedService.value?.scopes) return [];
    return selectedService.value.scopes;
});

const groupedSelectedScopes = computed(() => {
    const groups = new Map();

    selectedScopesMap.value.forEach((scope) => {
        const serviceId = scope.service?.id || scope.service_id;
        const serviceName = scope.service?.name || "Unknown Service";

        if (!groups.has(serviceId)) {
            groups.set(serviceId, {
                service: { id: serviceId, name: serviceName },
                scopes: [],
            });
        }
        groups.get(serviceId).scopes.push(scope);
    });

    return Array.from(groups.values());
});

// Métodos
const isScopeSelected = (scopeId) => {
    return selectedScopesMap.value.has(scopeId);
};

const addScope = (scope) => {
    const scopeWithService = {
        ...scope,
        service: selectedService.value
            ? {
                  id: selectedService.value.id,
                  name: selectedService.value.name,
              }
            : null,
    };

    selectedScopesMap.value.set(scope.id, scopeWithService);
    updateFormScopes();
};

const removeScope = (scopeId) => {
    selectedScopesMap.value.delete(scopeId);
    updateFormScopes();
};

const updateFormScopes = () => {
    form.scopes = Array.from(selectedScopesMap.value.keys());
};

const addPrice = () => {
    form.prices.push({
        period: null,
        currency: null,
        amount: 0.0,
    });
};

const removePrice = async (price, index) => {
    form.prices.splice(index, 1);
    if (form.errors?.prices) {
        const newPriceErrors = [...form.errors.prices];
        newPriceErrors.splice(index, 1);
        form.errors.prices = newPriceErrors;
    }
};

const searchServices = async (val) => {
    service_search_by_name.value = val;
    await getServices();
};

const createPlan = async () => {
    loading.value = true;

    form.post(page.props.routes.store, {
        forceFormData: true,
        onSuccess: (response) => {},
        onError: (errors) => {
            form.errors = $errors(errors);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const updatePlan = async (item) => {
    loading.value = true;

    form.put(item.links.update, {
        onSuccess: (response) => {},
        onError: (errors) => {
            form.errors = $errors(errors);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const handled = async () => {
    if (props.data?.id) {
        await updatePlan(props.data);
    } else {
        await createPlan();
    }
};

const getServices = async () => {
    try {
        const res = await $server.get(page.props.api.services, {
            params: {
                per_page: 25,
                visibility: "public",
                name: service_search_by_name.value,
            },
        });
        if (res.status === 200) {
            services.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const open = async () => {
    if (props.data?.id) {
        form.name = props.data.name || "";
        form.description = props.data.description || "";
        form.active = props.data.active || false;
        form.bonus_enabled = props.data.bonus_enabled || false;
        form.bonus_duration = props.data.bonus_duration || 0;
        form.trial_enabled = props.data.trial_enabled || false;
        form.trial_duration = props.data.trial_duration || 0;
        form.prices = props.data.prices;

        // Cargar scopes existentes con su información de servicio
        if (props.data.scopes?.length > 0) {
            selectedScopesMap.value.clear();
            props.data.scopes.forEach((scope) => {
                selectedScopesMap.value.set(scope.id, scope);
            });
            updateFormScopes();
        }
    }

    await getServices();
};

const close = () => {
    form.resetAndClearErrors();
    selectedScopesMap.value.clear();
    selectedServiceId.value = null;
};

onMounted(async () => {
    await open();
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
