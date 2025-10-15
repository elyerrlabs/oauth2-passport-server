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
    <!-- Button -->
    <button
        @click="open"
        :class="[
            'flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors',
            scope
                ? 'rounded-full p-2 text-blue-600 hover:bg-blue-50 hover:text-blue-700'
                : 'rounded-lg border border-blue-600 px-4 py-2 text-blue-600 hover:bg-blue-600 hover:text-white',
        ]"
    >
        <i :class="icon"></i>
        <span v-if="!scope">{{ __("Add new scope") }}</span>

        <!-- Tooltip for scope mode -->
        <div
            v-if="scope"
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-blue-600 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
        >
            {{ __("Update scope") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-blue-600"
            ></div>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="scope ? __('Update Scope') : __('Add New Scope')"
        panel-class="w-full lg:w-4xl"
        z-index="z-90"
    >
        <template #body>
            <!-- Form Content -->
            <div class="p-6 space-y-6">
                <!-- Role Select -->
                <div class="space-y-2">
                    <label
                        class="flex items-center gap-2 text-sm font-medium text-gray-700"
                    >
                        <i class="mdi mdi-account-key text-gray-500"></i>
                        {{ __("Role") }}
                    </label>
                    <div class="relative">
                        <v-select
                            :label="__('Select role')"
                            v-model="form.role_id"
                            :error="errors.role_id"
                            :options="roles"
                            :required="true"
                        />
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="space-y-4">
                    <h4 class="text-lg font-medium text-gray-800">
                        {{ __("Permissions") }}
                    </h4>

                    <!-- API Key Access -->
                    <div
                        class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg"
                    >
                        <v-switch
                            v-model="form.api_key"
                            :label="__('API Key Access')"
                            :error="errors.api_key"
                            :required="true"
                            :placeholder="
                                __(
                                    'Allow access via API keys for automated systems'
                                )
                            "
                        />
                    </div>

                    <!-- Active -->
                    <div
                        class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg"
                    >
                        <v-switch
                            v-model="form.active"
                            :label="__('Active')"
                            :error="errors.active"
                            :required="true"
                            :placeholder="
                                __('Enable this scope for immediate use')
                            "
                        />
                    </div>

                    <!-- Public Access -->
                    <div
                        class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg"
                    >
                        <v-switch
                            v-model="form.public"
                            :label="__('Public Access')"
                            :error="errors.public"
                            :required="true"
                            :placeholder="
                                __(
                                    'Make available to all users without authentication'
                                )
                            "
                        />
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl"
            >
                <button
                    @click="dialog = false"
                    :disabled="loading"
                    class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="addScopes"
                    :disabled="loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors',
                        loading
                            ? 'bg-blue-400 cursor-not-allowed'
                            : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-200',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i
                        v-else
                        :class="scope ? 'mdi mdi-update' : 'mdi mdi-plus'"
                    ></i>
                    {{ scope ? __("Update Scope") : __("Add Scope") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VSwitch from "@/components/VSwitch.vue";

export default {
    components: {
        VModal,
        VInput,
        VSelect,
        VSwitch,
    },
    emits: ["created"],

    props: {
        icon: {
            required: false,
            type: String,
            default: "mdi mdi-lock-open-plus-outline",
        },
        scope: {
            required: false,
            type: Object,
        },
        link: {
            required: true,
            type: String,
        },
    },

    data() {
        return {
            dialog: false,
            loading: false,
            loadingRoles: false,
            roles: [],
            form: {
                api_key: false,
                active: false,
                public: false,
                role_id: "",
            },
            errors: {},
        };
    },

    methods: {
        async open() {
            this.form = {
                api_key: false,
                active: false,
                public: false,
                role_id: "",
            };
            this.errors = {};

            await this.getRoles();

            if (this.scope) {
                this.form = {
                    ...this.scope,
                    role_id: this.scope.role?.id || "",
                };
            }

            this.dialog = true;
        },

        async getRoles() {
            this.loadingRoles = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.route.roles,
                    {
                        params: {
                            per_page: 100,
                        },
                    }
                );

                if (res.status == 200) {
                    this.roles = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loadingRoles = false;
            }
        },

        async addScopes() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(this.link, this.form);

                if (res.status == 201) {
                    $notify.success(
                        this.scope
                            ? __("Scope updated successfully")
                            : __("Scope added successfully")
                    );
                    this.$emit("created");
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    $notify.success(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
