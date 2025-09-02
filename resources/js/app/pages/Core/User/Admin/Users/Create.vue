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
    <q-btn
        color="primary"
        outline
        round
        icon="mdi-plus"
        @click="open"
        class="create-user-btn"
        size="md"
    >
        <q-tooltip
            transition-show="scale"
            transition-hide="scale"
            class="bg-primary text-white"
        >
            {{ __("Add new user") }}
        </q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        persistent
        transition-show="jump-up"
        transition-hide="jump-down"
    >
        <div class="dialog-backdrop flex flex-center">
            <q-card class="user-dialog-card shadow-15">
                <div class="dialog-header bg-primary text-white">
                    <q-card-section class="text-center">
                        <q-icon
                            name="mdi-account-plus"
                            size="lg"
                            class="q-mb-sm"
                        />
                        <div class="text-h5">{{ __("Create New User") }}</div>
                        <div class="text-caption">
                            {{ __("Add a new user to the system") }}
                        </div>
                    </q-card-section>
                </div>

                <q-card-section class="q-pt-lg">
                    <div class="q-gutter-y-lg">
                        <!-- Personal Information Section -->
                        <div class="section-header">
                            <q-icon
                                name="mdi-account-details"
                                color="primary"
                                class="q-mr-sm"
                            />
                            <span class="text-subtitle1 text-primary">{{
                                __("Personal Information")
                            }}</span>
                        </div>

                        <div class="row q-col-gutter-md">
                            <div class="col-12 col-sm-6">
                                <q-input
                                    outlined
                                    v-model="form.name"
                                    dense
                                    :label="__('First Name')"
                                    :error="!!errors.name"
                                    color="primary"
                                    class="input-field"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-account" />
                                    </template>
                                    <template v-slot:error>
                                        <v-error :error="errors.name"></v-error>
                                    </template>
                                </q-input>
                            </div>
                            <div class="col-12 col-sm-6">
                                <q-input
                                    outlined
                                    v-model="form.last_name"
                                    dense
                                    :label="__('Last Name')"
                                    :error="!!errors.last_name"
                                    color="primary"
                                    class="input-field"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-account" />
                                    </template>
                                    <template v-slot:error>
                                        <v-error
                                            :error="errors.last_name"
                                        ></v-error>
                                    </template>
                                </q-input>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="section-header q-mt-xl">
                            <q-icon
                                name="mdi-contact-mail"
                                color="primary"
                                class="q-mr-sm"
                            />
                            <span class="text-subtitle1 text-primary">{{
                                __("Contact Information")
                            }}</span>
                        </div>

                        <q-input
                            outlined
                            v-model="form.email"
                            dense
                            :label="__('Email Address')"
                            :error="!!errors.email"
                            color="primary"
                            class="input-field"
                            type="email"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-email" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.email"></v-error>
                            </template>
                        </q-input>

                        <div class="row q-col-gutter-md">
                            <div class="col-12 col-sm-6">
                                <q-select
                                    v-model="form.country"
                                    dense
                                    outlined
                                    use-input
                                    fill-input
                                    hide-selected
                                    emit-value
                                    map-options
                                    input-debounce="300"
                                    :options="filteredCountries"
                                    :label="__('Country')"
                                    :error="!!errors.country"
                                    @filter="filterCountries"
                                    color="primary"
                                    class="input-field"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-earth" />
                                    </template>
                                    <template v-slot:error>
                                        <v-error
                                            :error="errors.country"
                                        ></v-error>
                                    </template>
                                    <template v-slot:option="scope">
                                        <q-item v-bind="scope.itemProps">
                                            <q-item-section avatar>
                                                <span>{{
                                                    scope.opt.label.split(
                                                        " "
                                                    )[0]
                                                }}</span>
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>{{
                                                    scope.opt.label.replace(
                                                        /^.*? /,
                                                        ""
                                                    )
                                                }}</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </template>
                                </q-select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <q-select
                                    v-model="form.dial_code"
                                    dense
                                    outlined
                                    use-input
                                    fill-input
                                    hide-selected
                                    emit-value
                                    map-options
                                    input-debounce="300"
                                    :options="filteredDialCodes"
                                    :label="__('Dial Code')"
                                    :error="!!errors.dial_code"
                                    @filter="filterDialCodes"
                                    color="primary"
                                    class="input-field"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-phone" />
                                    </template>
                                    <template v-slot:error>
                                        <v-error
                                            :error="errors.dial_code"
                                        ></v-error>
                                    </template>
                                </q-select>
                            </div>
                        </div>

                        <q-input
                            outlined
                            v-model="form.phone"
                            dense
                            :label="__('Phone Number')"
                            :error="!!errors.phone"
                            color="primary"
                            class="input-field"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-phone" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.phone"></v-error>
                            </template>
                        </q-input>

                        <!-- Additional Information Section -->
                        <div class="section-header q-mt-xl">
                            <q-icon
                                name="mdi-calendar-account"
                                color="primary"
                                class="q-mr-sm"
                            />
                            <span class="text-subtitle1 text-primary">{{
                                __("Additional Information")
                            }}</span>
                        </div>

                        <div class="birthday-field">
                            <label class="field-label">
                                <q-icon
                                    name="mdi-cake-variant"
                                    class="q-mr-sm"
                                />
                                {{ __("Birthday") }}
                            </label>
                            <VueDatePicker
                                v-model="form.birthday"
                                :enable-time-picker="false"
                                :max-date="new Date()"
                                format="yyyy-MM-dd"
                                model-type="format"
                                :placeholder="__('Select birthday')"
                                class="date-picker"
                            />
                            <v-error :error="errors.birthday"></v-error>
                        </div>

                        <q-checkbox
                            v-model="form.verify_email"
                            :label="__('Mark user email as verified')"
                            color="primary"
                            class="verify-checkbox"
                            :error="!!errors.groups"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.groups"></v-error>
                            </template>
                        </q-checkbox>
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="q-pa-lg">
                    <q-btn
                        :label="__('Cancel')"
                        icon="mdi-close-circle"
                        color="grey-7"
                        @click="dialog = false"
                        flat
                        class="q-mr-sm"
                    />
                    <q-btn
                        :label="__('Create User')"
                        icon="mdi-account-plus"
                        color="primary"
                        @click="create"
                        unelevated
                    />
                </q-card-actions>
            </q-card>
        </div>
    </q-dialog>
</template>

<script>
export default {
    emits: ["created"],
    data() {
        return {
            dialog: false,
            form: {
                name: null,
                last_name: null,
                email: null,
                country: null,
                dial_code: null,
                phone: null,
                birthday: null,
                groups: [],
                verify_email: false,
            },
            selected_groups: [],
            errors: {},
            countries: [],
            groups: [],
            formFields: {
                name: { label: "Name", type: "text" },
                last_name: { label: "Last Name", type: "text" },
                email: { label: "Email", type: "email" },
                phone: { label: "Phone Number", type: "text" },
            },
            filteredCountries: [],
            filteredDialCodes: [],
        };
    },

    watch: {
        selected_groups(value) {
            this.form.groups = value.map((item) => item.value);
        },
    },

    methods: {
        async open() {
            this.clean();
            this.dialog = true;
            await this.getCountries();
        },

        clean() {
            this.form = {
                name: null,
                last_name: null,
                email: null,
                country: null,
                dial_code: null,
                phone: null,
                birthday: null,
                groups: [],
                verify_email: false,
            };
            this.errors = {};
        },

        close() {
            this.dialog = false;
            this.clean();
        },

        filterCountries(val, update) {
            if (!val) {
                update(() => {
                    this.filteredCountries = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
                });
                return;
            }

            const needle = val.toLowerCase();
            update(() => {
                this.filteredCountries = this.countries
                    .filter((c) => c.name_en.toLowerCase().includes(needle))
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
            });
        },

        filterDialCodes(val, update) {
            if (!val) {
                update(() => {
                    this.filteredDialCodes = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
                });
                return;
            }

            const needle = val.toLowerCase();
            update(() => {
                this.filteredDialCodes = this.countries
                    .filter((c) =>
                        `${c.name_en} ${c.dial_code}`
                            .toLowerCase()
                            .includes(needle)
                    )
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
            });
        },

        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );
                if (res.status === 201) {
                    this.clean();
                    this.$q.notify({
                        type: "positive",
                        message: "User created successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.$emit("created", true);
                    this.dialog = false;
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        type: "negative",
                        message: "Please check the form for errors",
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                } else if (
                    e.response &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                }
            }
        },

        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: { order_by: "name_en", order_type: "asc" },
                });
                if (res.status === 200) this.countries = res.data;
            } catch (e) {
                this.$q.notify({
                    type: "negative",
                    message: "Failed to load countries",
                    position: "top",
                    icon: "mdi-alert-circle",
                    timeout: 3000,
                });
            }

            this.filteredCountries = this.countries.map((c) => ({
                label: `${c.emoji} ${c.name_en}`,
                value: c.name_en,
            }));
            this.filteredDialCodes = this.countries.map((c) => ({
                label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                value: c.dial_code,
            }));
        },
    },
};
</script>

<style scoped>
.dialog-backdrop {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.user-dialog-card {
    width: 100%;
    max-width: 600px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.section-header {
    display: flex;
    align-items: center;
    padding: 8px 0;
    border-bottom: 2px solid #f0f0f0;
    margin-bottom: 16px;
}

.input-field {
    transition: all 0.3s ease;
}

.input-field:focus-within {
    transform: translateY(-2px);
}

.birthday-field {
    margin: 16px 0;
}

.field-label {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-weight: 500;
    color: #1976d2;
}

.date-picker {
    width: 100%;
}

.verify-checkbox {
    margin-top: 16px;
    padding: 12px;
    border-radius: 8px;
    background: #fafafa;
    border: 1px solid #e0e0e0;
}

.create-user-btn {
    transition: transform 0.2s ease;
}

.create-user-btn:hover {
    transform: scale(1.1);
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
