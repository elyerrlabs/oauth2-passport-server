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
        outline
        :round="scope ? true : false"
        color="primary"
        @click="open"
        :icon="icon"
        size="sm"
        class="q-mr-xs"
        :class="{ 'text-white': !scope }"
    >
        <span v-if="!scope">{{ __("Add new scope") }}</span>
        <q-tooltip
            v-if="scope"
            transition-show="scale"
            transition-hide="scale"
            class="bg-primary"
        >
            {{ __("Update scope") }}
        </q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        persistent
        transition-show="jump-up"
        transition-hide="jump-down"
    >
        <div class="dialog-backdrop flex flex-center">
            <q-card class="scope-dialog-card shadow-15">
                <div class="dialog-header bg-primary text-white">
                    <q-card-section class="text-center">
                        <q-icon
                            :name="
                                scope
                                    ? 'mdi-lock-clock'
                                    : 'mdi-lock-open-plus-outline'
                            "
                            size="lg"
                            class="q-mb-sm"
                        />
                        <div class="text-h6">
                            {{
                                scope ? __("Update Scope") : __("Add New Scope")
                            }}
                        </div>
                        <div class="text-caption">
                            {{
                                __("Manage access permissions for this service")
                            }}
                        </div>
                    </q-card-section>
                </div>

                <q-card-section class="q-pt-lg">
                    <div class="q-gutter-y-md">
                        <q-select
                            v-model="form.role_id"
                            :label="__('Role')"
                            :options="roles"
                            option-label="name"
                            option-value="id"
                            outlined
                            color="primary"
                            filter
                            emit-value
                            map-options
                            :error="!!errors.role_id"
                            :loading="loadingRoles"
                            :hint="__('Select the role to assign permissions')"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-account-key" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.role_id" />
                            </template>
                            <template v-slot:no-option>
                                <q-item>
                                    <q-item-section class="text-grey">
                                        {{ __("No roles available") }}
                                    </q-item-section>
                                </q-item>
                            </template>
                        </q-select>

                        <div class="permissions-section">
                            <div class="text-subtitle2 text-grey-8 q-mb-sm">
                                {{ __("Permissions") }}
                            </div>

                            <q-item class="permission-item q-pa-none q-mb-sm">
                                <q-item-section avatar>
                                    <q-checkbox
                                        v-model="form.api_key"
                                        color="blue"
                                        :error="!!errors.api_key"
                                        keep-color
                                    >
                                        <template v-slot:error>
                                            <v-error :error="errors.api_key" />
                                        </template>
                                    </q-checkbox>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium">
                                        {{ __("API Key Access") }}
                                    </q-item-label>
                                    <q-item-label caption class="text-grey-7">
                                        {{
                                            __(
                                                "Allow access via API keys for automated systems"
                                            )
                                        }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>

                            <q-item class="permission-item q-pa-none q-mb-sm">
                                <q-item-section avatar>
                                    <q-checkbox
                                        v-model="form.active"
                                        color="green"
                                        :error="!!errors.active"
                                        keep-color
                                    >
                                        <template v-slot:error>
                                            <v-error :error="errors.active" />
                                        </template>
                                    </q-checkbox>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium">
                                        {{ __("Active") }}
                                    </q-item-label>
                                    <q-item-label caption class="text-grey-7">
                                        {{
                                            __(
                                                "Enable this scope for immediate use"
                                            )
                                        }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>

                            <q-item class="permission-item q-pa-none">
                                <q-item-section avatar>
                                    <q-checkbox
                                        v-model="form.public"
                                        color="orange"
                                        :error="!!errors.public"
                                        keep-color
                                    >
                                        <template v-slot:error>
                                            <v-error :error="errors.public" />
                                        </template>
                                    </q-checkbox>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium">
                                        {{ __("Public Access") }}
                                    </q-item-label>
                                    <q-item-label caption class="text-grey-7">
                                        {{
                                            __(
                                                "Make available to all users without authentication"
                                            )
                                        }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="q-pa-md">
                    <q-btn
                        flat
                        color="grey-7"
                        :label="__('Cancel')"
                        @click="dialog = false"
                        class="q-mr-sm"
                        :disable="loading"
                    />
                    <q-btn
                        color="primary"
                        :label="scope ? __('Update Scope') : __('Add Scope')"
                        @click="addScopes"
                        :loading="loading"
                        :icon="scope ? 'mdi-update' : 'mdi-plus'"
                    />
                </q-card-actions>
            </q-card>
        </div>
    </q-dialog>
</template>

<script>
export default {
    emits: ["created"],

    props: {
        icon: {
            required: false,
            type: String,
            default: "mdi-lock-open-plus-outline",
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
                    this.roles = res.data.data.map((item) => ({
                        ...item,
                        name: this.__(item.name),
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
                    this.$q.notify({
                        type: "positive",
                        message: this.scope
                            ? this.__("Scope updated successfully")
                            : this.__("Scope added successfully"),
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.$emit("created");
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response.status == 422) {
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
    },
};
</script>

<style scoped>
.dialog-backdrop {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.scope-dialog-card {
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.permissions-section {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
}

.permission-item {
    border-bottom: 1px solid #f0f0f0;
    padding: 8px 0;
}

.permission-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
