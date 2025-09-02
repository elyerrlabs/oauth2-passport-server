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
        <q-btn
            round
            color="primary"
            @click="dialog = true"
            icon="mdi-plus-circle"
            size="md"
            class="shadow-4 pulse-animation"
        >
            <q-tooltip
                transition-show="scale"
                transition-hide="scale"
                class="bg-primary text-white shadow-6"
            >
                {{ __("Add new service") }}
            </q-tooltip>
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="jump-up"
            transition-hide="jump-down"
            maximized
        >
            <div class="dialog-backdrop flex flex-center">
                <q-card class="dialog-card shadow-15">
                    <div class="dialog-header bg-primary text-white">
                        <q-card-section class="text-center">
                            <q-icon
                                name="mdi-cog-plus"
                                size="md"
                                class="q-mb-sm"
                            />
                            <div class="text-h6">
                                {{ __("Create New Service") }}
                            </div>
                            <div class="text-caption">
                                {{
                                    __(
                                        "Define a new service with specific properties"
                                    )
                                }}
                            </div>
                        </q-card-section>
                    </div>

                    <q-card-section class="q-pt-lg">
                        <div class="q-gutter-y-md">
                            <q-input
                                v-model="form.name"
                                :label="__('Service Name')"
                                outlined
                                color="primary"
                                :error="!!errors.name"
                                class="input-field"
                                :loading="loading"
                                :hint="__('Unique identifier for the service')"
                                :rules="[
                                    (val) =>
                                        !!val || __('Service name is required'),
                                ]"
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-tag-outline" />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.name"></v-error>
                                </template>
                            </q-input>

                            <q-input
                                v-model="form.description"
                                :label="__('Description')"
                                outlined
                                color="primary"
                                :error="!!errors.description"
                                type="textarea"
                                rows="3"
                                class="input-field"
                                :loading="loading"
                                :hint="
                                    __(
                                        'Describe the purpose and functionality of this service'
                                    )
                                "
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-text-box-outline" />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.description" />
                                </template>
                            </q-input>

                            <q-select
                                v-model="form.group_id"
                                :label="__('Group')"
                                :options="groups"
                                option-label="name"
                                option-value="id"
                                outlined
                                color="primary"
                                filter
                                emit-value
                                map-options
                                :error="!!errors.group_id"
                                :loading="loadingGroups"
                                :hint="
                                    __(
                                        'Select the group this service belongs to'
                                    )
                                "
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-account-group" />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.group_id" />
                                </template>
                                <template v-slot:no-option>
                                    <q-item>
                                        <q-item-section class="text-grey">
                                            {{ __("No groups available") }}
                                        </q-item-section>
                                    </q-item>
                                </template>
                            </q-select>

                            <q-select
                                v-model="form.visibility"
                                :options="visibilityOptions"
                                :label="__('Visibility')"
                                outlined
                                color="primary"
                                :error="!!errors.visibility"
                                :hint="
                                    __(
                                        'Set the visibility level for this service'
                                    )
                                "
                            >
                                <template v-slot:prepend>
                                    <q-icon name="mdi-eye" />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.visibility" />
                                </template>
                            </q-select>

                            <q-item class="system-checkbox q-pa-none q-mt-md">
                                <q-item-section avatar>
                                    <q-checkbox
                                        v-model="form.system"
                                        color="orange"
                                        :error="!!errors.system"
                                        keep-color
                                    >
                                        <template v-slot:error>
                                            <v-error :error="errors.system" />
                                        </template>
                                    </q-checkbox>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label class="text-weight-medium">
                                        {{ __("System Service") }}
                                    </q-item-label>
                                    <q-item-label caption class="text-grey-7">
                                        {{
                                            __(
                                                "System services have special permissions and cannot be modified or deleted."
                                            )
                                        }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </div>
                    </q-card-section>

                    <q-card-actions align="right" class="q-pa-md">
                        <q-btn
                            flat
                            color="grey"
                            :label="__('Cancel')"
                            @click="close"
                            class="q-mr-sm"
                            :disable="loading"
                        />
                        <q-btn
                            color="primary"
                            :label="__('Create Service')"
                            @click="create"
                            :loading="loading"
                            icon="mdi-check-circle"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            loading: false,
            loadingGroups: false,
            visibilityOptions: ["private", "public"],
            form: {
                name: null,
                description: null,
                group_id: null,
                system: false,
                visibility: null,
            },
            errors: {},
            groups: [],
        };
    },

    created() {
        this.getGroups();
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close() {
            this.form = {
                name: null,
                description: null,
                group_id: null,
                system: false,
                visibility: null,
            };
            this.errors = {};
            this.dialog = false;
            this.loading = false;
        },

        open() {
            this.form = {
                name: null,
                description: null,
                group_id: null,
                system: false,
                visibility: null,
            };
            this.errors = {};
            this.dialog = true;
        },

        /**
         * Create a new service
         */
        async create() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route.services,
                    this.form
                );

                if (res.status == 201) {
                    this.$q.notify({
                        type: "positive",
                        message: "Service created successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });

                    this.form = {
                        name: null,
                        description: null,
                        group_id: null,
                        system: false,
                        visibility: null,
                    };
                    this.errors = {};
                    this.$emit("created", true);
                    this.dialog = false;
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status == 422 &&
                    e.response.data.errors
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
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: "An unexpected error occurred",
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        async getGroups() {
            this.loadingGroups = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.route.groups,
                    {
                        params: {
                            page: 1,
                            per_page: 1000,
                        },
                    }
                );

                if (res.status == 200) {
                    this.groups = res.data.data;
                }
            } catch (e) {
                this.$q.notify({
                    type: "negative",
                    message: "Failed to load groups",
                    position: "top",
                    icon: "mdi-alert-circle",
                    timeout: 3000,
                });
            } finally {
                this.loadingGroups = false;
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

.dialog-card {
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.input-field {
    transition: all 0.3s ease;
}

.input-field:focus-within {
    transform: translateY(-2px);
}

.pulse-animation {
    animation: pulse 2s infinite;
}

.system-checkbox {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 8px;
    margin-top: 16px;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(25, 118, 210, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(25, 118, 210, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(25, 118, 210, 0);
    }
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
