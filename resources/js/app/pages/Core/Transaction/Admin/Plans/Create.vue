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
    <div class="q-pa-md q-gutter-sm">
        <!-- Add Plan Button -->
        <q-btn
            round
            color="primary"
            @click="open"
            icon="mdi-plus"
            size="md"
            class="floating-action-btn shadow-5"
        >
            <q-tooltip class="bg-primary">{{
                __("Create New Plan")
            }}</q-tooltip>
        </q-btn>

        <!-- Create Plan Dialog -->
        <q-dialog v-model="dialog" persistent full-width>
            <q-card class="create-plan-dialog rounded-borders">
                <!-- Dialog Header -->
                <q-card-section class="dialog-header bg-primary text-white">
                    <div class="row items-center">
                        <q-icon
                            name="mdi-plus-circle"
                            size="28px"
                            class="q-mr-sm"
                        />
                        <div class="text-h5 text-weight-bold">
                            {{ __("Create New Plan") }}
                        </div>
                        <q-space />
                        <q-btn
                            icon="close"
                            flat
                            round
                            dense
                            v-close-popup
                            class="text-white"
                            @click="close"
                        />
                    </div>
                </q-card-section>

                <!-- Form Content -->
                <q-card-section class="dialog-content scroll">
                    <div class="q-gutter-y-lg">
                        <!-- Plan Information Section -->
                        <div class="section-container">
                            <div class="section-title">
                                <q-icon
                                    name="mdi-information"
                                    class="q-mr-sm"
                                />
                                {{ __("Plan Information") }}
                            </div>

                            <div class="row q-col-gutter-md">
                                <!-- Plan Name -->
                                <div class="col-12">
                                    <q-input
                                        outlined
                                        v-model="form.name"
                                        :label="__('Plan Name')"
                                        :error="!!errors.name"
                                        color="primary"
                                        class="custom-input"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon name="mdi-tag" />
                                        </template>
                                        <template v-slot:error>
                                            <v-error :error="errors.name" />
                                        </template>
                                    </q-input>
                                </div>

                                <!-- Plan Description -->
                                <div class="col-12">
                                    <div
                                        class="text-caption text-weight-medium q-mb-xs"
                                    >
                                        <q-icon
                                            name="mdi-text"
                                            class="q-mr-xs"
                                        />
                                        {{ __("Description") }}
                                    </div>
                                    <v-editor
                                        class="required"
                                        v-model="form.description"
                                        :label="__('Plan Description')"
                                    />
                                    <v-error :error="errors.description" />
                                </div>
                            </div>
                        </div>

                        <!-- Plan Settings Section -->
                        <div class="section-container">
                            <div class="section-title">
                                <q-icon name="mdi-cog" class="q-mr-sm" />
                                {{ __("Plan Settings") }}
                            </div>

                            <div class="row q-col-gutter-md">
                                <!-- Active Toggle -->
                                <div class="col-12 col-md-6">
                                    <q-toggle
                                        v-model="form.active"
                                        :label="__('Active Plan')"
                                        color="positive"
                                        :error="!!errors.active"
                                        icon="mdi-check-circle"
                                        class="custom-toggle"
                                    >
                                        <q-tooltip>{{
                                            __(
                                                "Make this plan available to users"
                                            )
                                        }}</q-tooltip>
                                    </q-toggle>
                                    <v-error :error="errors.active" />
                                </div>

                                <!-- Bonus Settings -->
                                <div class="col-12 col-md-6">
                                    <q-toggle
                                        v-model="form.bonus_enabled"
                                        :label="__('Enable Bonus')"
                                        color="accent"
                                        :error="!!errors.bonus_enabled"
                                        icon="mdi-gift"
                                        class="custom-toggle"
                                    >
                                        <q-tooltip>{{
                                            __("Add bonus days to this plan")
                                        }}</q-tooltip>
                                    </q-toggle>
                                    <v-error :error="errors.bonus_enabled" />
                                </div>

                                <!-- Bonus Duration -->
                                <div
                                    class="col-12 col-md-6"
                                    v-if="form.bonus_enabled"
                                >
                                    <q-input
                                        outlined
                                        v-model="form.bonus_duration"
                                        :label="__('Bonus Duration (Days)')"
                                        type="number"
                                        :error="!!errors.bonus_duration"
                                        color="accent"
                                        class="custom-input"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon name="mdi-calendar" />
                                        </template>
                                        <template v-slot:error>
                                            <v-error
                                                :error="errors.bonus_duration"
                                            />
                                        </template>
                                    </q-input>
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="section-container">
                            <div class="section-title">
                                <q-icon
                                    name="mdi-currency-usd"
                                    class="q-mr-sm"
                                />
                                {{ __("Pricing") }}
                            </div>
                            <v-error :error="errors.prices" />

                            <div
                                class="row q-col-gutter-md q-mb-md"
                                v-for="(price, index) in form.prices"
                                :key="index"
                            >
                                <!-- Billing Period -->
                                <div class="col-12 col-md-3">
                                    <q-select
                                        outlined
                                        v-model="price.billing_period"
                                        :options="billing_periods"
                                        emit-value
                                        map-options
                                        :label="__('Billing Period')"
                                        :error="
                                            !!errors[
                                                `prices.${index}.billing_period`
                                            ]
                                        "
                                        color="primary"
                                        class="custom-select"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon
                                                name="mdi-calendar-refresh"
                                            />
                                        </template>
                                        <template v-slot:error>
                                            <v-error
                                                :error="
                                                    errors[
                                                        `prices.${index}.billing_period`
                                                    ]
                                                "
                                            />
                                        </template>
                                    </q-select>
                                </div>

                                <!-- Currency -->
                                <div class="col-12 col-md-3">
                                    <q-select
                                        outlined
                                        v-model="price.currency"
                                        :options="currencies"
                                        emit-value
                                        :label="__('Currency')"
                                        :error="
                                            !!errors[`prices.${index}.currency`]
                                        "
                                        color="primary"
                                        class="custom-select"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon name="mdi-currency-sign" />
                                        </template>
                                        <template v-slot:error>
                                            <v-error
                                                :error="
                                                    errors[
                                                        `prices.${index}.currency`
                                                    ]
                                                "
                                            />
                                        </template>
                                    </q-select>
                                </div>

                                <!-- Amount -->
                                <div class="col-12 col-md-3">
                                    <q-input
                                        outlined
                                        v-model="price.amount"
                                        :label="__('Amount')"
                                        mask="#.##"
                                        fill-mask="0"
                                        reverse-fill-mask
                                        :error="
                                            !!errors[`prices.${index}.amount`]
                                        "
                                        color="primary"
                                        class="custom-input"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon name="mdi-cash" />
                                        </template>
                                        <template v-slot:error>
                                            <v-error
                                                :error="
                                                    errors[
                                                        `prices.${index}.amount`
                                                    ]
                                                "
                                            />
                                        </template>
                                    </q-input>
                                </div>

                                <!-- Remove Price Button -->
                                <div
                                    class="col-12 col-md-3 flex justify-center items-center"
                                >
                                    <q-btn
                                        icon="mdi-delete"
                                        color="negative"
                                        round
                                        outline
                                        @click="form.prices.splice(index, 1)"
                                        class="delete-btn"
                                    >
                                        <q-tooltip>{{
                                            __("Remove this price")
                                        }}</q-tooltip>
                                    </q-btn>
                                </div>
                            </div>

                            <!-- Add Price Button -->
                            <q-btn
                                color="primary"
                                icon="mdi-plus"
                                :label="__('Add Price Option')"
                                @click="addPrice"
                                class="add-price-btn"
                                outline
                            />
                        </div>

                        <!-- Scopes Section -->
                        <div class="section-container">
                            <div class="section-title">
                                <q-icon name="mdi-key-chain" class="q-mr-sm" />
                                {{ __("Access Scopes") }}
                            </div>

                            <!-- Service Selection -->
                            <q-select
                                filled
                                v-model="service"
                                :options="services"
                                option-label="name"
                                :label="__('Select Service')"
                                color="teal"
                                clearable
                                :error="!!errors.scopes"
                                class="custom-select q-mb-md"
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-server" />
                                </template>
                                <template v-slot:option="scope">
                                    <q-item
                                        v-bind="scope.itemProps"
                                        class="service-option"
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="teal"
                                                text-color="white"
                                                icon="mdi-server"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >{{
                                                    scope.opt.name
                                                }}</q-item-label
                                            >
                                            <q-item-label caption>
                                                {{ scope.opt.group.name }} •
                                                {{
                                                    scope.opt.group.description
                                                }}
                                            </q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </template>
                                <template v-slot:selected-item="scope">
                                    <q-item v-if="scope">
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="teal"
                                                text-color="white"
                                                icon="mdi-server"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>{{
                                                scope.opt.name
                                            }}</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.scopes" />
                                </template>
                            </q-select>

                            <!-- Scopes List -->
                            <div v-if="scopes.length" class="scopes-container">
                                <div
                                    class="text-caption text-weight-medium q-mb-sm"
                                >
                                    {{ __("Available Roles for") }}
                                    {{ service?.name }}
                                </div>
                                <q-list class="scopes-list">
                                    <q-item
                                        v-for="(item, index) in scopes"
                                        :key="index"
                                        class="scope-item rounded-borders"
                                        :class="
                                            hasScope(item.id)
                                                ? 'scope-item-selected'
                                                : ''
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                :color="
                                                    hasScope(item.id)
                                                        ? 'positive'
                                                        : 'grey-4'
                                                "
                                                text-color="white"
                                            >
                                                <q-icon
                                                    name="mdi-account-key"
                                                />
                                            </q-avatar>
                                        </q-item-section>

                                        <q-item-section>
                                            <div class="text-weight-medium">
                                                {{ item.role.name }}
                                            </div>
                                            <div
                                                class="text-caption text-grey-7"
                                            >
                                                {{ item.role.description }}
                                            </div>
                                        </q-item-section>

                                        <q-item-section side>
                                            <q-btn
                                                :icon="
                                                    hasScope(item.id)
                                                        ? 'mdi-check'
                                                        : 'mdi-plus'
                                                "
                                                :color="
                                                    hasScope(item.id)
                                                        ? 'positive'
                                                        : 'primary'
                                                "
                                                round
                                                outline
                                                dense
                                                @click="toggleScope(item.id)"
                                                class="scope-toggle-btn"
                                            >
                                                <q-tooltip>
                                                    {{
                                                        hasScope(item.id)
                                                            ? __("Remove scope")
                                                            : __("Add scope")
                                                    }}
                                                </q-tooltip>
                                            </q-btn>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </div>
                        </div>
                    </div>
                </q-card-section>

                <!-- Dialog Actions -->
                <q-card-actions align="right" class="dialog-actions q-pa-md">
                    <q-btn
                        :label="__('Cancel')"
                        color="grey"
                        @click="close"
                        outline
                        class="action-btn"
                    />
                    <q-btn
                        :label="__('Create Plan')"
                        color="primary"
                        @click="create"
                        icon="mdi-check"
                        class="action-btn"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
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
            service: null,
            currencies: [],
            billing_periods: [],
        };
    },

    watch: {
        service(value) {
            if (value) {
                this.getServicesScope();
            } else {
                this.scopes = [];
            }
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
            this.service = null;
            this.scopes = [];
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
                    ? this.billing_periods[0].value
                    : "",
                currency: this.currencies.length
                    ? this.currencies[0].value
                    : "",
                amount: null,
            });
        },

        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route["plans"],
                    this.form
                );

                if (res.status == 201) {
                    this.clean();
                    this.$emit("created", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "New plan has been created successfully",
                        timeout: 3000,
                        icon: "mdi-check-circle",
                        position: "top-right",
                    });
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
            }
        },

        async getServices() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["services"],
                    {
                        params: {
                            per_page: 500,
                            visibility: "public",
                        },
                    }
                );

                if (res.status == 200) {
                    this.services = res.data.data;
                }
            } catch (error) {
                console.error("Error fetching services:", error);
            }
        },

        async getBillingPeriod() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["billing_period"]
                );

                if (res.status == 200) {
                    this.billing_periods = res.data.data.map((item) => ({
                        label: item.name,
                        value: item.id,
                    }));
                }
            } catch (e) {
                 if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },

        async getCurrencies() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["currencies"]
                );

                if (res.status == 200) {
                    this.currencies = res.data.data.map((item) => ({
                        label: `${item.code} - ${item.name}`,
                        value: item.code,
                    }));
                }
            } catch (error) {
                console.error("Error fetching currencies:", error);
            }
        },

        async getServicesScope() {
            if (
                !this.service ||
                !this.service.links ||
                !this.service.links.scopes
            )
                return;

            try {
                const res = await this.$server.get(this.service.links.scopes, {
                    params: {
                        per_page: 500,
                    },
                });

                if (res.status == 200) {
                    this.scopes = res.data.data;
                }
            } catch (error) {
                console.error("Error fetching service scopes:", error);
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
/* CSS Variables for Theme Consistency */
:root {
    --color-primary: #1976d2;
    --color-secondary: #26a69a;
    --color-accent: #ff6b35;
    --color-positive: #21ba45;
    --color-negative: #c10015;
    --color-warning: #f2c037;
    --color-dark: #1d1d1d;
    --color-light: #f5f5f5;
    --border-radius: 12px;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition-speed: 0.3s;
}

.floating-action-btn {
    position: relative;
    transition: transform var(--transition-speed) ease,
        box-shadow var(--transition-speed) ease;
}

.floating-action-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
}

.create-plan-dialog {
    border-radius: var(--border-radius);
    overflow: hidden;
    max-height: 90vh;
}

.dialog-header {
    padding: 20px 24px;
}

.dialog-content {
    padding: 24px;
    max-height: 65vh;
}

.dialog-actions {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    background: var(--color-light);
}

.section-container {
    padding: 20px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: var(--border-radius);
    background: white;
    margin-bottom: 20px;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
}

.custom-input :deep(.q-field__control) {
    border-radius: 8px;
}

.custom-select :deep(.q-field__control) {
    border-radius: 8px;
}

.custom-toggle :deep(.q-toggle__label) {
    font-weight: 500;
}

.add-price-btn {
    border-radius: 8px;
    font-weight: 500;
}

.delete-btn {
    transition: transform var(--transition-speed) ease;
}

.delete-btn:hover {
    transform: scale(1.1);
}

.scopes-container {
    margin-top: 16px;
}

.scopes-list {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: var(--border-radius);
    background: white;
}

.scope-item {
    transition: all var(--transition-speed) ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.scope-item:last-child {
    border-bottom: none;
}

.scope-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.scope-item-selected {
    background-color: rgba(25, 118, 210, 0.05);
    border-left: 4px solid var(--color-primary);
}

.scope-toggle-btn {
    transition: all var(--transition-speed) ease;
}

.action-btn {
    border-radius: 8px;
    padding: 8px 20px;
    font-weight: 500;
    min-width: 100px;
}

.service-option {
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.service-option:last-child {
    border-bottom: none;
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .create-plan-dialog {
        max-width: 95vw !important;
    }

    .dialog-content {
        padding: 16px;
    }

    .section-container {
        padding: 16px;
    }
}

@media (max-width: 599px) {
    .dialog-header .text-h5 {
        font-size: 1.25rem;
    }

    .action-btn {
        min-width: 80px;
        padding: 6px 16px;
    }
}
</style>
