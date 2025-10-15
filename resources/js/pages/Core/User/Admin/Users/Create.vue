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
    <!-- Create Button -->
    <button
        @click="open"
        class="create-user-btn bg-transparent border border-blue-600 text-blue-600 rounded-full p-3 hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        :title="__('Add new user')"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                clip-rule="evenodd"
            />
        </svg>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Create New User')"
        panel-class="w-full lg:w-6xl"
    >
        <template #body>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <v-input
                    v-model="form.name"
                    :label="__('Name')"
                    :error="errors.name"
                    :required="true"
                />

                <v-input
                    v-model="form.last_name"
                    :label="__('Last Name')"
                    :error="errors.last_name"
                    :required="true"
                />

                <v-input
                    :label="__('Email')"
                    v-model="form.email"
                    :error="errors.email"
                    :required="true"
                />

                <v-select
                    :label="__('Country')"
                    v-model="form.country"
                    :error="errors.country"
                    :options="countries"
                    :required="true"
                    label-key="name_en"
                    value-key="name_en"
                >
                    <template #selected="{ option }">
                        <span class="text-gray-700 p-4">
                            {{
                                option
                                    ? `${option.emoji} - ${option.name_en}`
                                    : __("select")
                            }}
                        </span>
                    </template>
                    <template #option="{ option }">
                        <span class="text-gray-700 p-4">
                            {{ option.emoji }} - {{ option.name_en }}
                        </span>
                    </template>
                </v-select>

                <v-select
                    :label="__('Dial code')"
                    v-model="form.dial_code"
                    :options="dial_codes"
                    :error="errors.dial_codes"
                    :required="true"
                    label-key="name_en"
                    value-key="dial_code"
                >
                    <template #selected="{ option }">
                        <span class="text-gray-700 p-4">
                            {{
                                option
                                    ? `${option.emoji} - ${option.name_en}   ${option.dial_code}`
                                    : __("select")
                            }}
                        </span>
                    </template>
                    <template #option="{ option }">
                        <span class="text-gray-700 p-4 m-2">
                            {{ option.emoji }} - {{ option.name_en }} -
                            {{ option.dial_code }}
                        </span>
                    </template>
                </v-select>

                <v-input
                    :label="__('Phone')"
                    v-model="form.phone"
                    :error="errors.phone"
                    :required="true"
                />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <svg
                            class="w-4 h-4 inline mr-1"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"
                            />
                            <path d="M9 13h2v5a1 1 0 11-2 0v-5z" />
                        </svg>
                        {{ __("Birthday") }}
                    </label>
                    <VueDatePicker
                        v-model="form.birthday"
                        :enable-time-picker="false"
                        :max-date="new Date()"
                        format="yyyy-MM-dd"
                        model-type="format"
                        :placeholder="__('Select birthday')"
                        class="date-picker w-full"
                    />
                    <v-error :error="errors.birthday" class="mt-1"></v-error>
                </div>
            </div>
            <div
                class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200"
            >
                <button
                    @click="dialog = false"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                >
                    <svg
                        class="w-4 h-4 inline mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="create"
                    class="px-4 py-2 text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    <svg
                        class="w-4 h-4 inline mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"
                        />
                    </svg>
                    {{ __("Create User") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VCountry from "@/components/VCountry.vue";
import VSelect from "@/components/VSelect.vue";
import VInput from "@/components/VInput.vue";
import VError from "@/components/VError.vue";

export default {
    components: {
        VModal,
        VCountry,
        VSelect,
        VInput,
        VError,
    },
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
            errors: {},
            countries: [],
            dial_codes: [],
            formFields: {
                name: { label: "Name", type: "text" },
                last_name: { label: "Last Name", type: "text" },
                email: { label: "Email", type: "email" },
                phone: { label: "Phone Number", type: "text" },
            },
        };
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

        async create() {
            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );
                if (res.status === 201) {
                    this.clean();
                    $notify.success(__("User created successfully"));
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
                }

                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },

        filteredCountries(val) {
            console.log(val);

            return this.countries.filter(
                (item) => item.name_en == this.form.name
            );
        },

        filteredDialCodes() {},

        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: {
                        order_by: "name_en",
                        order_type: "asc",
                    },
                });
                if (res.status === 200) {
                    this.countries = res.data;
                    this.dial_codes = res.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            }
        },
    },
};
</script>
