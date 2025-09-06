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
    <v-user-layout>
        <q-page class="account-info-page">
            <div class="page-container">
                <!-- Header Section -->
                <div class="page-header">
                    <div class="header-content">
                        <q-icon
                            name="mdi-account-circle"
                            size="36px"
                            color="primary"
                            class="header-icon"
                        />
                        <q-toolbar-title
                            class="text-h4 text-weight-bold text-grey-8"
                        >
                            {{ __("Account Information") }}
                        </q-toolbar-title>
                    </div>
                    <div class="text-subtitle1 text-grey-7 q-mt-sm">
                        {{
                            __(
                                "Manage your personal information and contact details"
                            )
                        }}
                    </div>
                </div>

                <!-- Account Form Card -->
                <q-card class="account-form-card" flat bordered>
                    <q-card-section>
                        <div
                            class="text-h6 text-weight-medium text-primary q-mb-md section-title"
                        >
                            <q-icon
                                name="mdi-account-details"
                                class="q-mr-sm"
                            />
                            {{ __("Personal Details") }}
                        </div>

                        <div class="row q-col-gutter-lg">
                            <!-- First Name -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("First Name") }}
                                        <span class="text-red">*</span>
                                    </div>
                                    <q-input
                                        filled
                                        dense
                                        v-model="form.name"
                                        :placeholder="
                                            __('Enter your first name')
                                        "
                                        counter
                                        maxlength="100"
                                        :error="!!errors.name"
                                        class="custom-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                name="mdi-account"
                                                color="grey-5"
                                            />
                                        </template>
                                    </q-input>
                                    <div class="input-hint">
                                        {{ __("Maximum 100 characters") }}
                                    </div>
                                    <v-error :error="errors.name" />
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Last Name") }}
                                        <span class="text-red">*</span>
                                    </div>
                                    <q-input
                                        filled
                                        dense
                                        v-model="form.last_name"
                                        :placeholder="
                                            __('Enter your last name')
                                        "
                                        counter
                                        maxlength="100"
                                        :error="!!errors.last_name"
                                        class="custom-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                name="mdi-account"
                                                color="grey-5"
                                            />
                                        </template>
                                    </q-input>
                                    <div class="input-hint">
                                        {{ __("Maximum 100 characters") }}
                                    </div>
                                    <v-error :error="errors.last_name" />
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Email") }}
                                        <span class="text-red">*</span>
                                    </div>
                                    <q-input
                                        filled
                                        dense
                                        v-model="form.email"
                                        type="email"
                                        :placeholder="
                                            __('Enter your email address')
                                        "
                                        maxlength="100"
                                        :error="!!errors.email"
                                        class="custom-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                name="mdi-email"
                                                color="grey-5"
                                            />
                                        </template>
                                    </q-input>
                                    <div class="input-hint">
                                        {{
                                            __(
                                                "Must be unique and valid. Max 100 characters"
                                            )
                                        }}
                                    </div>
                                    <v-error :error="errors.email" />
                                </div>
                            </div>

                            <!-- Country -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Country") }}
                                    </div>
                                    <q-select
                                        v-model="form.country"
                                        dense
                                        emit-value
                                        map-options
                                        :options="countryOptions"
                                        :label="__('Select your country')"
                                        outlined
                                        :error="!!errors.country"
                                        class="custom-select"
                                        use-input
                                        input-debounce="300"
                                        @filter="filterCountryOptions"
                                        behavior="menu"
                                    >
                                        <template v-slot:selected>
                                            <div
                                                v-if="form.country"
                                                class="selected-option"
                                            >
                                                <span class="option-emoji">{{
                                                    getCountryEmoji(
                                                        form.country
                                                    )
                                                }}</span>
                                                {{ form.country }}
                                            </div>
                                            <div v-else class="text-grey-6">
                                                {{ __("Select your country") }}
                                            </div>
                                        </template>
                                        <template v-slot:option="scope">
                                            <q-item v-bind="scope.itemProps">
                                                <q-item-section avatar>
                                                    <span
                                                        class="option-emoji"
                                                        >{{
                                                            scope.opt.label.split(
                                                                " "
                                                            )[0]
                                                        }}</span
                                                    >
                                                </q-item-section>
                                                <q-item-section>
                                                    <q-item-label>{{
                                                        scope.opt.label.replace(
                                                            scope.opt.label.split(
                                                                " "
                                                            )[0] + " ",
                                                            ""
                                                        )
                                                    }}</q-item-label>
                                                </q-item-section>
                                            </q-item>
                                        </template>
                                        <template v-slot:no-option>
                                            <q-item>
                                                <q-item-section
                                                    class="text-grey"
                                                >
                                                    {{
                                                        __("No countries found")
                                                    }}
                                                </q-item-section>
                                            </q-item>
                                        </template>
                                    </q-select>
                                    <v-error :error="errors.country" />
                                </div>
                            </div>

                            <!-- City -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("City") }}
                                    </div>
                                    <q-input
                                        filled
                                        dense
                                        v-model="form.city"
                                        :placeholder="__('Enter your city')"
                                        maxlength="100"
                                        :error="!!errors.city"
                                        class="custom-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                name="mdi-city"
                                                color="grey-5"
                                            />
                                        </template>
                                    </q-input>
                                    <div class="input-hint">
                                        {{ __("Optional. Max 100 characters") }}
                                    </div>
                                    <v-error :error="errors.city" />
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Address") }}
                                    </div>
                                    <q-input
                                        filled
                                        dense
                                        v-model="form.address"
                                        :placeholder="
                                            __('Enter your full address')
                                        "
                                        maxlength="150"
                                        :error="!!errors.address"
                                        class="custom-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                name="mdi-home"
                                                color="grey-5"
                                            />
                                        </template>
                                    </q-input>
                                    <div class="input-hint">
                                        {{ __("Optional. Max 150 characters") }}
                                    </div>
                                    <v-error :error="errors.address" />
                                </div>
                            </div>

                            <!-- Dial Code -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Dial Code") }}
                                    </div>
                                    <q-select
                                        v-model="form.dial_code"
                                        dense
                                        emit-value
                                        map-options
                                        :options="dialCodeOptions"
                                        :label="__('Select dial code')"
                                        outlined
                                        :error="!!errors.dial_code"
                                        class="custom-select"
                                        use-input
                                        input-debounce="300"
                                        @filter="filterDialCodeOptions"
                                        behavior="menu"
                                    >
                                        <template v-slot:selected>
                                            <div
                                                v-if="form.dial_code"
                                                class="selected-option"
                                            >
                                                {{ form.dial_code }}
                                            </div>
                                            <div v-else class="text-grey-6">
                                                {{ __("Select dial code") }}
                                            </div>
                                        </template>
                                        <template v-slot:option="scope">
                                            <q-item v-bind="scope.itemProps">
                                                <q-item-section>
                                                    <q-item-label>{{
                                                        scope.opt.label
                                                    }}</q-item-label>
                                                </q-item-section>
                                            </q-item>
                                        </template>
                                        <template v-slot:no-option>
                                            <q-item>
                                                <q-item-section
                                                    class="text-grey"
                                                >
                                                    {{
                                                        __(
                                                            "No dial codes found"
                                                        )
                                                    }}
                                                </q-item-section>
                                            </q-item>
                                        </template>
                                    </q-select>
                                    <v-error :error="errors.dial_code" />
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Phone Number") }}
                                    </div>
                                    <q-input
                                        filled
                                        dense
                                        v-model="form.phone"
                                        :placeholder="
                                            __('Enter your phone number')
                                        "
                                        maxlength="25"
                                        :error="!!errors.phone"
                                        class="custom-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                name="mdi-phone"
                                                color="grey-5"
                                            />
                                        </template>
                                    </q-input>
                                    <div class="input-hint">
                                        {{
                                            __(
                                                "Required if dial code is filled. Must be unique. Max 25 characters"
                                            )
                                        }}
                                    </div>
                                    <v-error :error="errors.phone" />
                                </div>
                            </div>

                            <!-- Birthday -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Birthday") }}
                                    </div>
                                    <div class="date-picker-container">
                                        <VueDatePicker
                                            v-model="form.birthday"
                                            :enable-time-picker="false"
                                            :max-date="new Date()"
                                            format="yyyy-MM-dd"
                                            model-type="format"
                                            :placeholder="
                                                __('Select your birthday')
                                            "
                                            :class="{
                                                'error-border':
                                                    !!errors.birthday,
                                            }"
                                        />
                                        <q-icon
                                            name="mdi-calendar"
                                            color="grey-5"
                                            class="date-picker-icon"
                                        />
                                    </div>
                                    <div class="input-hint">
                                        {{
                                            __(
                                                "Optional. Format: YYYY-MM-DD. Must be a past date"
                                            )
                                        }}
                                    </div>
                                    <v-error :error="errors.birthday" />
                                </div>
                            </div>
                        </div>
                    </q-card-section>

                    <!-- Submit Button -->
                    <q-card-actions class="q-px-md q-pb-md">
                        <q-btn
                            :label="__('Update Information')"
                            color="primary"
                            unelevated
                            no-caps
                            @click="update"
                            class="submit-button"
                            :loading="loading"
                        >
                            <template v-slot:loading>
                                <q-spinner-hourglass class="on-left" />
                                {{ __("Updating...") }}
                            </template>
                        </q-btn>
                    </q-card-actions>
                </q-card>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: "",
                last_name: "",
                email: "",
                country: "",
                city: "",
                address: "",
                dial_code: "",
                phone: "",
                birthday: "",
            },
            countries: [],
            filteredCountryOptions: [],
            filteredDialCodeOptions: [],
            errors: {},
            loading: false,
        };
    },

    computed: {
        countryOptions() {
            return this.filteredCountryOptions.length > 0
                ? this.filteredCountryOptions
                : this.countries.map((c) => ({
                      label: `${c.emoji} ${c.name_en}`,
                      value: c.name_en,
                  }));
        },
        dialCodeOptions() {
            return this.filteredDialCodeOptions.length > 0
                ? this.filteredDialCodeOptions
                : this.countries.map((c) => ({
                      label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                      value: c.dial_code,
                  }));
        },
    },

    created() {
        this.getCountries();
    },

    mounted() {
        this.form = { ...this.$page.props.user };
        this.filteredCountryOptions = this.countryOptions;
        this.filteredDialCodeOptions = this.dialCodeOptions;
    },

    methods: {
        async update() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status === 200) {
                    this.form = res.data.data;

                    this.$q.notify({
                        type: "positive",
                        message: "Information has been updated successfully",
                        timeout: 3000,
                        position: "top-right",
                        icon: "mdi-check-circle",
                        progress: true,
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: { order_by: "name_en", order_type: "asc" },
                });
                if (res.status === 200) {
                    this.countries = res.data;
                    this.filteredCountryOptions = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
                    this.filteredDialCodeOptions = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
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

        getCountryEmoji(countryName) {
            const country = this.countries.find(
                (c) => c.name_en === countryName
            );
            return country ? country.emoji : "";
        },

        filterCountryOptions(val, update) {
            if (val === "") {
                update(() => {
                    this.filteredCountryOptions = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
                });
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                this.filteredCountryOptions = this.countries
                    .filter((c) => c.name_en.toLowerCase().includes(needle))
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
            });
        },

        filterDialCodeOptions(val, update) {
            if (val === "") {
                update(() => {
                    this.filteredDialCodeOptions = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
                });
                return;
            }

            update(() => {
                const needle = val.toLowerCase();
                this.filteredDialCodeOptions = this.countries
                    .filter(
                        (c) =>
                            c.name_en.toLowerCase().includes(needle) ||
                            c.dial_code.includes(needle)
                    )
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
            });
        },
    },
};
</script>

<style lang="scss" scoped>
.account-info-page {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 24px;
}

.page-container {
    max-width: 1400px;
    width: 100%;
}

.page-header {
    text-align: center;
    margin-bottom: 32px;

    .header-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-bottom: 8px;
    }

    .header-icon {
        background: rgba(0, 0, 0, 0.05);
        padding: 16px;
        border-radius: 50%;
    }
}

.account-form-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;

    .q-card__section {
        padding: 32px;
    }

    .section-title {
        padding-bottom: 16px;
        border-bottom: 2px solid rgba(0, 0, 0, 0.06);
    }
}

.input-section {
    margin-bottom: 24px;

    .input-label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #2d3748;
        font-size: 0.95rem;
    }

    .input-hint {
        font-size: 0.75rem;
        color: #718096;
        margin-top: 4px;
    }

    .custom-input {
        :deep(.q-field__control) {
            border-radius: 10px;
            height: 48px;
        }

        :deep(.q-field__native) {
            padding-top: 6px;
            padding-bottom: 6px;
        }
    }

    .custom-select {
        :deep(.q-field__control) {
            border-radius: 10px;
            height: 48px;
        }

        :deep(.q-field__native) {
            min-height: 48px;
        }
    }

    .date-picker-container {
        position: relative;

        .vue-date-picker {
            width: 100%;

            :deep(.dp__input) {
                border: 1px solid #cbd5e0;
                border-radius: 10px;
                padding: 12px 16px;
                height: 48px;
                font-size: 1rem;

                &:focus {
                    border-color: var(--q-primary);
                }
            }

            &.error-border :deep(.dp__input) {
                border-color: #f56565;
            }
        }

        .date-picker-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
    }
}

.selected-option {
    display: flex;
    align-items: center;
    gap: 8px;
}

.option-emoji {
    font-size: 1.2rem;
}

.submit-button {
    min-width: 200px;
    height: 48px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    text-transform: none;
    letter-spacing: 0.5px;
}

// Enhanced select dropdown styling
:deep(.q-menu) {
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);

    .q-item {
        padding: 12px 16px;
        border-radius: 6px;
        margin: 4px;

        &:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        &.q-item--active {
            background: rgba(0, 0, 0, 0.08);
            color: var(--q-primary);
        }
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .account-info-page {
        padding: 16px;
    }

    .account-form-card .q-card__section {
        padding: 24px;
    }

    .page-header {
        .text-h4 {
            font-size: 1.75rem;
        }

        .header-icon {
            padding: 12px;
            font-size: 28px;
        }
    }
}

@media (max-width: 599px) {
    .account-info-page {
        padding: 12px;
    }

    .account-form-card .q-card__section {
        padding: 20px;
    }

    .page-header {
        .text-h4 {
            font-size: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            gap: 12px;
        }
    }

    .submit-button {
        width: 100%;
    }
}
</style>
