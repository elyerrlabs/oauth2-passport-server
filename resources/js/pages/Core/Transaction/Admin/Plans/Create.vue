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
    <div class="p-4 space-y-4">
        <!-- Add Plan Button -->
        <button
            class="fixed bottom-5 right-5 z-50 bg-blue-600 cursor-pointer text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-700 transition-colors"
            @click="open"
        >
            <i class="mdi mdi-plus text-xl"></i>
        </button>

        <!-- Create Plan Dialog -->
        <v-modal
            v-model="dialog"
            class="fixed inset-0 z-50 flex items-center justify-center p-0 bg-black/80"
            panel-class="min-w-full m-4 min-h-screen"
            :title="__('Create New Plan')"
        >
            <template #body>
                <div class="space-y-6">
                    <!-- Plan Information Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                        <v-input
                            :label="__('Plan Information')"
                            v-model="form.name"
                            :error="errors.name"
                            :placeholder="__('Vpn pro')"
                            :required="true"
                        />
                    </div>

                    <div class="grid grid-cols-1">
                        <v-editor
                            v-model="form.description"
                            :label="__('Description')"
                            :error="errors.description"
                            :required="true"
                        />
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 px-4 gap-4">
                        <v-switch
                            :label="__('Active Plan')"
                            v-model="form.active"
                            :error="errors.active"
                        />

                        <v-switch
                            :label="__('Enable Bonus')"
                            v-model="form.bonus_enabled"
                            :error="errors.bonus_enabled"
                        />

                        <v-input
                            v-if="form.bonus_enabled"
                            :label="__('Bonus duration')"
                            v-model="form.bonus_duration"
                            :error="errors.bonus_duration"
                            type="number"
                            :placeholder="__('8')"
                        />
                    </div>

                    <!-- Pricing Section -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-2xl font-semibold">
                            {{ __("Pricing") }}
                        </h3>
                        <div v-if="form.prices.length">
                            <div
                                v-for="(price, index) in form.prices"
                                :key="index"
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 items-end"
                            >
                                <!-- Billing Period -->
                                <v-select
                                    :label="__('Billing period')"
                                    :options="billing_periods"
                                    label-key="name"
                                    value-key="id"
                                    v-model="price.billing_period"
                                    :error="
                                        errors?.prices?.[index]?.billing_period
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
                                    :error="errors?.prices?.[index]?.currency"
                                    :required="true"
                                />

                                <!-- Amount -->
                                <v-input
                                    v-model="price.amount"
                                    :label="__('Amount')"
                                    :error="errors?.prices?.[index]?.amount"
                                    :required="true"
                                    type="money"
                                />

                                <!-- Remove Price Button -->
                                <button
                                    class="h-[42px] bg-red-600 text-white border border-red-600 rounded-xl flex items-center justify-center gap-2 hover:bg-red-700 transition-colors"
                                    @click="form.prices.splice(index, 1)"
                                >
                                    <i class="mdi mdi-delete text-lg"></i>
                                    {{ __("Delete") }}
                                </button>
                            </div>
                        </div>
                        <v-error
                            v-if="!form.prices.length"
                            :error="errors?.prices"
                        />
                        <!-- Add Price Button -->
                        <button
                            class="text-white bg-green-500 hover:bg-green-600 cursor-pointer border border-primary rounded-md px-4 py-2 flex items-center transition-colors"
                            @click="addPrice"
                        >
                            <i class="mdi mdi-plus mr-2"></i>
                            {{ __("Add Price Option") }}
                        </button>
                    </div>

                    <!-- Scopes Section -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center text-lg font-medium mb-4">
                            <i class="mdi mdi-key-chain mr-2"></i>
                            {{ __("Access Scopes") }}
                        </div>

                        <!-- Service Selection -->
                        <div class="relative mb-4">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <i class="mdi mdi-server text-gray-500"></i>
                            </div>
                            <div>
                                <v-select
                                    :label="__('Service')"
                                    :options="services"
                                    label-key="name"
                                    value-key="id"
                                    v-model="selectedServiceId"
                                    :error="errors?.scopes"
                                    :clearable="true"
                                    @change="onServiceChange"
                                />
                            </div>
                        </div>

                        <!-- Scopes List -->
                        <div v-if="scopes.length" class="scopes-container">
                            <div class="text-sm font-medium mb-2">
                                {{ __("Available Roles for") }}
                                {{
                                    selectedService?.name || "Selected Service"
                                }}
                            </div>
                            <div class="space-y-2">
                                <div
                                    v-for="(item, index) in scopes"
                                    :key="index"
                                    class="flex items-center p-3 border rounded-md transition-colors"
                                    :class="
                                        hasScope(item.id)
                                            ? 'scope-item-selected border-positive bg-green-50'
                                            : 'border-gray-200'
                                    "
                                >
                                    <div class="flex-shrink-0 mr-3">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center"
                                            :class="
                                                hasScope(item.id)
                                                    ? 'bg-positive'
                                                    : 'bg-gray-300'
                                            "
                                        >
                                            <i
                                                class="mdi mdi-account-key text-gray-800"
                                            ></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="font-medium">
                                            {{ item.role?.name || "No name" }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{
                                                item.role?.description ||
                                                "No description"
                                            }}
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button
                                            class="border rounded-full w-8 h-8 flex items-center justify-center transition-colors"
                                            :class="
                                                hasScope(item.id)
                                                    ? 'text-positive border-positive hover:bg-green-50'
                                                    : 'text-primary border-primary hover:bg-blue-50'
                                            "
                                            @click="toggleScope(item.id)"
                                        >
                                            <i
                                                :class="
                                                    hasScope(item.id)
                                                        ? 'mdi mdi-check'
                                                        : 'mdi mdi-plus'
                                                "
                                            ></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div v-else-if="loadingScopes" class="text-center py-4">
                            <i
                                class="mdi mdi-loading mdi-spin text-2xl text-blue-500"
                            ></i>
                            <p class="text-gray-600 mt-2">
                                {{ __("Loading scopes...") }}
                            </p>
                        </div>

                        <!-- No Scopes State -->
                        <div
                            v-else-if="selectedServiceId && !scopes.length"
                            class="text-center py-4 text-gray-500"
                        >
                            <i class="mdi mdi-information-outline text-2xl"></i>
                            <p class="mt-2">
                                {{ __("No scopes available for this service") }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dialog Actions -->
                <div class="flex justify-end p-4 border-t border-gray-200">
                    <button
                        class="bg-red-500 hover:bg-red-600 cursor-pointe text-white rounded-md px-4 py-2 mr-2 transition-colors"
                        @click="close"
                    >
                        {{ __("Cancel") }}
                    </button>
                    <button
                        class="bg-blue-500 text-white rounded-md px-4 py-2 flex items-center hover:bg-blue-700 transition-colors"
                        @click="create"
                    >
                        <i class="mdi mdi-check mr-2"></i>
                        {{ __("Create Plan") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VError from "@/components/VError.vue";
import VEditor from "@/components/VEditor.vue";
import VInput from "@/components/VInput.vue";
import VSwitch from "@/components/VSwitch.vue";
import VSelect from "@/components/VSelect.vue";
import VModal from "@/components/VModal.vue";

export default {
    components: {
        VError,
        VEditor,
        VInput,
        VSwitch,
        VSelect,
        VModal,
    },
    emits: ["created"],

    data() {
        return {
            dialog: false,
            form: {
                name: "",
                description: "",
                active: false,
                bonus_enabled: false,
                bonus_duration: 0,
                trial_enabled: false,
                trial_duration: 0,
                scopes: [],
                prices: [],
            },
            errors: {},
            scopes: [],
            services: [],
            selectedServiceId: null,
            loadingScopes: false,
            currencies: [],
            billing_periods: [],
        };
    },

    computed: {
        selectedService() {
            if (!this.selectedServiceId) return null;
            return (
                this.services.find(
                    (service) => service.id === this.selectedServiceId
                ) || null
            );
        },
    },

    methods: {
        close() {
            this.dialog = false;
            this.clean();
        },

        clean() {
            this.form = {
                name: "",
                description: "",
                active: false,
                bonus_enabled: false,
                bonus_duration: 0,
                trial_enabled: false,
                trial_duration: 0,
                scopes: [],
                prices: [],
            };
            this.errors = {};
            this.selectedServiceId = null;
            this.scopes = [];
            this.loadingScopes = false;
        },

        open() {
            this.clean();
            this.getServices();
            this.getBillingPeriod();
            this.getCurrencies();
            this.dialog = true;
        },

        addPrice() {
            this.form.prices.push({
                billing_period: this.billing_periods.length
                    ? this.billing_periods[0].id
                    : "",
                currency: this.currencies.length ? this.currencies[0].code : "",
                amount: null,
            });
        },

        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.routes.plans,
                    this.form
                );

                if (res.status == 201) {
                    this.clean();
                    this.$emit("created", true);
                    this.dialog = false;
                    // Show success notification
                    $notify.success(
                        __("New plan has been created successfully")
                    );
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getServices() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.services,
                    {
                        params: {
                            per_page: 500,
                            visibility: "public",
                        },
                    }
                );

                if (res.status == 200) {
                    this.services = res.data.data;
                    console.log("Services loaded:", this.services); // Debug log
                }
            } catch (e) {
                console.error("Error loading services:", e);
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getBillingPeriod() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.billing_period
                );

                if (res.status == 200) {
                    this.billing_periods = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async getCurrencies() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.currencies
                );

                if (res.status == 200) {
                    this.currencies = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        async onServiceChange(serviceId) {
            this.scopes = [];
            this.form.scopes = []; // Clear previous scopes when service changes

            if (!serviceId) {
                return;
            }

            await this.getServicesScope(serviceId);
        },

        async getServicesScope(serviceId) {
            if (!serviceId) return;

            const service = this.services.find((s) => s.id === serviceId);
            if (!service || !service.links || !service.links.scopes) {
                console.warn("Service or scopes link not found:", service);
                return;
            }

            this.loadingScopes = true;

            try {
                const res = await this.$server.get(service.links.scopes, {
                    params: {
                        per_page: 500,
                    },
                });

                if (res.status == 200) {
                    this.scopes = res.data.data;
                    console.log(
                        "Scopes loaded for service:",
                        serviceId,
                        this.scopes
                    ); // Debug log
                }
            } catch (e) {
                console.error("Error loading scopes:", e);
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loadingScopes = false;
            }
        },

        hasScope(id) {
            return this.form.scopes.includes(id);
        },

        toggleScope(id) {
            const index = this.form.scopes.indexOf(id);
            if (index === -1) {
                this.form.scopes.push(id);
            } else {
                this.form.scopes.splice(index, 1);
            }
        },
    },
};
</script>

<style scoped>
.floating-action-btn {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.scope-item-selected {
    background-color: rgba(33, 186, 69, 0.1);
    border-color: rgba(33, 186, 69, 0.3);
}

.toggle-line {
    transition: background-color 0.3s ease;
}

.toggle-dot {
    transition: transform 0.3s ease;
}
</style>
