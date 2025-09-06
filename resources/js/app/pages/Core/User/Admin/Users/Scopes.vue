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
    <div class="row q-gutter-xs">
        <q-btn
            outline
            round
            icon="mdi-shield-plus-outline"
            color="primary"
            @click="openDialog"
            class="q-mr-xs"
        >
            <q-tooltip
                transition-show="scale"
                transition-hide="scale"
                class="bg-primary text-white"
            >
                {{ __("Add access scopes") }}
            </q-tooltip>
        </q-btn>

        <q-dialog
            v-model="dialog"
            full-width
            persistent
            transition-show="jump-up"
            transition-hide="jump-down"
        >
            <div class="dialog-backdrop">
                <q-card class="scopes-dialog-card shadow-15">
                    <div class="dialog-header bg-primary text-white">
                        <q-card-section>
                            <div class="row items-center justify-between">
                                <div>
                                    <div class="text-h4 text-weight-bold">
                                        {{ __("Manage Access Scopes") }}
                                    </div>
                                    <div class="text-subtitle1">
                                        {{ __("Add permissions for:") }}
                                        <span class="text-weight-bold"
                                            >{{ item.name }}
                                            {{ item.last_name }}</span
                                        >
                                    </div>
                                    <div class="text-caption opacity-80">
                                        {{ item.email }}
                                    </div>
                                </div>
                                <q-btn
                                    flat
                                    round
                                    icon="mdi-close"
                                    @click="dialog = false"
                                    size="md"
                                    class="close-btn"
                                />
                            </div>
                        </q-card-section>
                    </div>

                    <q-card-section class="q-pt-xl">
                        <!-- User Summary -->
                        <div
                            class="user-summary bg-grey-1 rounded-borders q-pa-md q-mb-lg"
                        >
                            <div class="row items-center">
                                <q-avatar
                                    size="lg"
                                    color="blue"
                                    text-color="white"
                                    class="q-mr-md"
                                >
                                    {{
                                        item.name
                                            ? item.name.charAt(0).toUpperCase()
                                            : "U"
                                    }}
                                </q-avatar>
                                <div>
                                    <div class="text-h6 text-primary">
                                        {{ item.name }} {{ item.last_name }}
                                    </div>
                                    <div class="text-caption text-grey-7">
                                        {{ item.email }}
                                    </div>
                                    <div class="text-caption">
                                        <q-badge
                                            :color="
                                                item.disabled
                                                    ? 'orange'
                                                    : 'green'
                                            "
                                        >
                                            {{
                                                item.disabled
                                                    ? __("Inactive")
                                                    : __("Active")
                                            }}
                                        </q-badge>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scopes Component -->
                        <div class="scopes-container">
                            <v-scopes
                                :default_roles="user_roles"
                                @checked="checkedRoles"
                                :loading="loading"
                            />
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
                            :label="__('Save Permissions')"
                            color="primary"
                            @click="add"
                            :loading="loading"
                            unelevated
                            icon="mdi-shield-check"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </q-dialog>
    </div>
</template>
<script>
export default {
    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            user_roles: [],
            dialog: false,
            loading: false,
            form: {
                scopes: [],
                end_date: "",
            },
            errors: {},
        };
    },

    methods: {
        async openDialog() {
            this.dialog = true;
            this.loading = true;
            await this.userRoles();
            this.loading = false;
        },

        async userRoles() {
            try {
                const res = await this.$server.get(this.item.links.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status == 200) {
                    this.user_roles = res.data.data;
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

        checkedRoles(event) {
            this.form.scopes = event;
        },

        async add() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.item.links.scopes,
                    this.form
                );

                if (res.status == 201) {
                    this.$q.notify({
                        type: "positive",
                        message: "Permissions updated successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.errors = {};
                    this.userRoles();
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response?.status == 422) {
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
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 2rem;
    min-height: 100vh;
}

.scopes-dialog-card {
    width: 100%;
    max-width: 900px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.user-summary {
    border: 1px solid #e0e0e0;
}

.scopes-container {
    min-height: 300px;
    border: 1px solid #f0f0f0;
    border-radius: 8px;
    padding: 1rem;
    background: #fafafa;
}

.additional-options {
    border-top: 1px solid #f0f0f0;
    padding-top: 1rem;
}

.close-btn {
    transition: transform 0.2s ease;
}

.close-btn:hover {
    transform: rotate(90deg);
}

.opacity-80 {
    opacity: 0.8;
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
