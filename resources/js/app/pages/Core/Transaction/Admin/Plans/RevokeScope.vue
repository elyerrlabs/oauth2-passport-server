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
    <div>
        <!-- Revoke Scope Button -->
        <q-btn
            round
            color="warning"
            @click="dialog = true"
            icon="mdi-key-remove"
            size="sm"
            class="revoke-scope-btn shadow-3"
        >
            <q-tooltip class="bg-warning">{{ __("Revoke Scope") }}</q-tooltip>
        </q-btn>

        <!-- Revoke Confirmation Dialog -->
        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="revoke-scope-dialog rounded-borders">
                <!-- Dialog Header -->
                <q-card-section class="dialog-header bg-warning text-dark">
                    <div class="row items-center">
                        <q-icon
                            name="mdi-key-minus"
                            size="28px"
                            class="q-mr-sm"
                        />
                        <div class="text-h5 text-weight-bold">
                            {{ __("Revoke Access Scope") }}
                        </div>
                        <q-space />
                        <q-btn
                            icon="close"
                            flat
                            round
                            dense
                            v-close-popup
                            class="text-dark"
                            @click="dialog = false"
                        />
                    </div>
                </q-card-section>

                <!-- Warning Content -->
                <q-card-section class="warning-section">
                    <div class="row items-center q-mb-md">
                        <q-icon
                            name="mdi-alert"
                            color="warning"
                            size="48px"
                            class="q-mr-md"
                        />
                        <div class="text-h6 text-weight-medium">
                            {{ __("Confirm Scope Revocation") }}
                        </div>
                    </div>

                    <div class="text-body1 q-mb-lg">
                        {{
                            __(
                                "You are about to revoke the following access scope. This will remove permissions from the associated plan."
                            )
                        }}
                    </div>

                    <!-- Scope Details -->
                    <q-card flat bordered class="bg-grey-2 scope-details-card">
                        <q-card-section>
                            <div class="text-subtitle2 text-grey-8 q-mb-sm">
                                {{ __("Scope Details") }}
                            </div>

                            <div class="row items-center q-mb-xs">
                                <q-icon
                                    name="mdi-account-key"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">{{
                                    __("Role:")
                                }}</span>
                                <span
                                    class="q-ml-sm text-warning text-weight-bold"
                                    >{{ item.role.name }}</span
                                >
                            </div>

                            <div class="row items-center q-mb-xs">
                                <q-icon
                                    name="mdi-server"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">{{
                                    __("Service:")
                                }}</span>
                                <span class="q-ml-sm">{{
                                    item.service.name
                                }}</span>
                            </div>

                            <div class="row items-center q-mb-xs">
                                <q-icon
                                    name="mdi-account-group"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">{{
                                    __("Group:")
                                }}</span>
                                <span class="q-ml-sm">{{
                                    item.service.group.name
                                }}</span>
                            </div>

                            <div
                                v-if="item.role.description"
                                class="row items-start q-mt-sm"
                            >
                                <q-icon
                                    name="mdi-information"
                                    size="16px"
                                    class="q-mr-sm text-grey-6 q-mt-xs"
                                />
                                <span class="text-weight-medium">{{
                                    __("Description:")
                                }}</span>
                                <span class="q-ml-sm text-caption">{{
                                    item.role.description
                                }}</span>
                            </div>
                        </q-card-section>
                    </q-card>

                    <div class="text-caption text-warning q-mt-md">
                        <q-icon
                            name="mdi-alert-circle"
                            size="16px"
                            class="q-mr-xs"
                        />
                        {{
                            __(
                                "Users with this plan will lose access to these permissions immediately."
                            )
                        }}
                    </div>
                </q-card-section>

                <!-- Dialog Actions -->
                <q-card-actions align="right" class="dialog-actions q-pa-md">
                    <q-btn
                        :label="__('Cancel')"
                        color="grey"
                        @click="dialog = false"
                        outline
                        class="action-btn"
                        icon="mdi-close"
                    />
                    <q-btn
                        :label="__('Revoke Scope')"
                        color="warning"
                        @click="destroy"
                        class="action-btn"
                        icon="mdi-key-remove"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["revoked"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.revoke);

                if (res.status == 200) {
                    this.$emit("revoked", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Scope has been revoked successfully",
                        timeout: 3000,
                        icon: "mdi-check-circle",
                        position: "top-right",
                    });
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
    },
};
</script>

<style scoped>
/* CSS Variables for Theme Consistency */
:root {
    --color-primary: #1976d2;
    --color-secondary: #26a69a;
    --color-warning: #f2c037;
    --color-negative: #c10015;
    --color-dark: #1d1d1d;
    --color-light: #f5f5f5;
    --border-radius: 12px;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition-speed: 0.3s;
}

.revoke-scope-btn {
    transition: transform var(--transition-speed) ease,
        box-shadow var(--transition-speed) ease;
}

.revoke-scope-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
}

.revoke-scope-dialog {
    border-radius: var(--border-radius);
    overflow: hidden;
    max-width: 550px;
    width: 100%;
}

.dialog-header {
    padding: 20px 24px;
}

.warning-section {
    padding: 24px;
}

.scope-details-card {
    border-left: 4px solid var(--color-warning);
}

.dialog-actions {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    background: var(--color-light);
}

.action-btn {
    border-radius: 8px;
    padding: 8px 20px;
    font-weight: 500;
    min-width: 130px;
}

/* Responsive adjustments */
@media (max-width: 599px) {
    .revoke-scope-dialog {
        max-width: 95vw;
    }

    .dialog-header .text-h5 {
        font-size: 1.25rem;
    }

    .warning-section {
        padding: 16px;
    }

    .action-btn {
        min-width: 110px;
        padding: 6px 16px;
    }

    .scope-details-card {
        margin: 0 -8px;
    }
}
</style>
