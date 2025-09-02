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
        color="primary"
        icon="mdi-account-key"
        @click="open"
        class="personal-client-btn"
        unelevated
    >
        {{ __("Create Personal Access Client") }}
        <q-tooltip
            transition-show="scale"
            transition-hide="scale"
            class="bg-primary text-white"
        >
            {{ __("Create a personal access client for API authentication") }}
        </q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        persistent
        transition-show="jump-up"
        transition-hide="jump-down"
    >
        <div class="dialog-backdrop flex flex-center">
            <q-card class="personal-client-dialog-card shadow-15">
                <div class="dialog-header bg-primary text-white">
                    <q-card-section class="text-center">
                        <q-icon
                            name="mdi-key-chain"
                            size="lg"
                            class="q-mb-sm"
                        />
                        <div class="text-h5">
                            {{ __("Create Personal Access Client") }}
                        </div>
                        <div class="text-caption">
                            {{
                                __(
                                    "Generate API keys for secure authentication"
                                )
                            }}
                        </div>
                    </q-card-section>
                </div>

                <q-card-section class="q-pt-lg">
                    <div class="q-gutter-y-md">
                        <div
                            class="info-section bg-blue-1 text-blue-8 rounded-borders q-pa-md"
                        >
                            <div class="row items-center">
                                <q-icon
                                    name="mdi-information"
                                    size="md"
                                    class="q-mr-sm"
                                />
                                <div class="text-caption">
                                    {{
                                        __(
                                            "Personal access clients allow your applications to authenticate with the API using generated tokens."
                                        )
                                    }}
                                </div>
                            </div>
                        </div>

                        <q-input
                            v-model="form.name"
                            :label="__('Client Name')"
                            outlined
                            color="primary"
                            :error="!!errors.name"
                            autofocus
                            :hint="
                                __(
                                    'Enter a descriptive name for your personal access client'
                                )
                            "
                            class="input-field"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-tag" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.name"></v-error>
                            </template>
                        </q-input>

                        <div
                            class="security-notice bg-green-1 text-green-8 rounded-borders q-pa-md"
                        >
                            <div class="row items-center">
                                <q-icon
                                    name="mdi-shield-check"
                                    size="sm"
                                    class="q-mr-sm"
                                />
                                <div class="text-caption">
                                    <strong>{{ __("Security Note:") }}</strong>
                                    {{
                                        __(
                                            "This client will be used to generate API tokens. Keep your tokens secure and never share them publicly."
                                        )
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="q-pa-lg">
                    <q-btn
                        :label="__('Cancel')"
                        color="grey-7"
                        @click="dialog = false"
                        flat
                        class="q-mr-sm"
                        icon="mdi-close-circle"
                    />
                    <q-btn
                        :label="__('Create Client')"
                        color="primary"
                        @click="createPersonalAccessClient"
                        unelevated
                        icon="mdi-key-plus"
                        :loading="loading"
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
            loading: false,
            form: {
                name: null,
            },
            errors: {},
        };
    },

    methods: {
        open() {
            this.form.name = null;
            this.errors = {};
            this.dialog = true;
        },

        async createPersonalAccessClient() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route["personal"],
                    this.form
                );

                if (res.status == 201) {
                    this.$q.notify({
                        type: "positive",
                        message: "Personal access client created successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 5000,
                    });
                    this.$emit("created");
                    this.dialog = false;
                }
            } catch (err) {
                if (err?.response?.status == 422) {
                    this.errors = err.response.data.errors;
                    this.$q.notify({
                        type: "negative",
                        message: "Please check the form for errors",
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message:
                            err.response?.data?.message ||
                            "Error creating personal access client",
                        position: "top",
                        icon: "mdi-alert-circle",
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

.personal-client-dialog-card {
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

.info-section {
    border-left: 4px solid #1976d2;
}

.security-notice {
    border-left: 4px solid #4caf50;
}

.personal-client-btn {
    transition: transform 0.2s ease;
}

.personal-client-btn:hover {
    transform: translateY(-2px);
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
