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
    <div class="p-4">
        <!-- Update Button -->
        <button
            @click="open"
            class="rounded-full w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white p-3 shadow-md transition"
            title="Update Plan"
        >
            <i class="mdi mdi-pencil text-lg"></i>
        </button>

        <v-modal
            :title="__('Update Plan')"
            v-model="dialog"
            panel-class="min-h-screen min-w-full m-4 p-4 max-w-7xl"
        >
            <template #body>
                <!-- Content -->
                <div class="space-y-6">
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
                            <div class="grid grid-cols-1 md:grid-cols-2">
                                <v-select
                                    :label="__('Service')"
                                    :options="services"
                                    v-model="service"
                                    :return-object="true"
                                    :error="errors?.scopes"
                                />
                            </div>
                        </div>

                        <!-- Scopes List -->
                        <div v-if="scopes.length" class="scopes-container">
                            <div class="text-sm font-medium mb-2">
                                {{ __("Available Roles for") }}
                                {{ service?.name }}
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
                                            {{ item.role.name }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ item.role.description }}
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
                    </div>
                </div>
                <!-- Actions -->
                <div
                    class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50"
                >
                    <button
                        class="bg-red-500 hover:bg-red-600 cursor-pointe text-white rounded-md px-4 py-2 mr-2 transition-colors"
                        @click="close"
                    >
                        {{ __("Cancel") }}
                    </button>

                    <button
                        @click="update"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition"
                    >
                        <i class="mdi mdi-check mr-1"></i>
                        {{ __("Update Plan") }}
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
    props: ["item"],
    emits: ["updated"],

    data() {
        return {
            dialog: false,
            form: {},
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
            value ? this.getServicesScope() : (this.scopes = []);
        },
    },

    methods: {
        open() {
            this.form = { ...this.item };
            this.form.scopes = this.form.scopes.map((s) => s.id);
            this.errors = {};
            this.getServices();
            this.getBillingPeriod();
            this.getCurrencies();
            this.dialog = true;
        },
        close() {
            this.dialog = false;
            this.errors = {};
            this.service = null;
            this.scopes = [];
        },
        addPrice() {
            this.form.prices.push({
                billing_period: this.billing_periods[0]?.value ?? "",
                currency: this.currencies[0]?.value ?? "",
                amount: null,
            });
        },
        async update() {
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );
                if (res.status === 200) {
                    this.$emit("updated", true);
                    this.close();
                    this.$notify.success("Plan updated successfully");
                }
            } catch (e) {
                this.errors = e?.response?.data?.errors ?? {};
                this.$notify.error(e?.response?.data?.message);
            }
        },
        async getServices() {
            const res = await this.$server.get(
                this.$page.props.route.services,
                { params: { per_page: 500, visibility: "public" } }
            );
            this.services = res.data.data;
        },
        async getBillingPeriod() {
            const res = await this.$server.get(
                this.$page.props.route["billing_period"]
            );
            this.billing_periods = res.data.data;
        },
        async getCurrencies() {
            const res = await this.$server.get(
                this.$page.props.route["currencies"]
            );
            this.currencies = res.data.data;
        },
        async getServicesScope() {
            if (!this.service?.links?.scopes) return;
            const res = await this.$server.get(this.service.links.scopes, {
                params: { per_page: 500 },
            });
            this.scopes = res.data.data;
        },
        hasScope(id) {
            return this.form.scopes.includes(id);
        },
        toggleScope(id) {
            const idx = this.form.scopes.indexOf(id);
            idx === -1
                ? this.form.scopes.push(id)
                : this.form.scopes.splice(idx, 1);
        },
    },
};
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
.animate-fadeIn {
    animation: fadeIn 0.25s ease-in-out;
}
</style>
