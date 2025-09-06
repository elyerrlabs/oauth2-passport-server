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
        <!-- Delete Price Button -->
        <q-btn
            round
            color="negative"
            @click="dialog = true"
            icon="mdi-close"
            size="sm"
            class="delete-price-btn shadow-3"
        >
            <q-tooltip class="bg-negative">{{ __("Delete Price") }}</q-tooltip>
        </q-btn>

        <!-- Delete Confirmation Dialog -->
        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="delete-price-dialog rounded-borders">
                <!-- Dialog Header -->
                <q-card-section class="dialog-header bg-negative text-white">
                    <div class="row items-center">
                        <q-icon
                            name="mdi-currency-usd-off"
                            size="28px"
                            class="q-mr-sm"
                        />
                        <div class="text-h5 text-weight-bold">
                            {{ __("Delete Price") }}
                        </div>
                        <q-space />
                        <q-btn
                            icon="close"
                            flat
                            round
                            dense
                            v-close-popup
                            class="text-white"
                            @click="dialog = false"
                        />
                    </div>
                </q-card-section>

                <!-- Warning Content -->
                <q-card-section class="warning-section">
                    <div class="row items-center q-mb-md">
                        <q-icon
                            name="mdi-alert"
                            color="negative"
                            size="48px"
                            class="q-mr-md"
                        />
                        <div class="text-h6 text-weight-medium">
                            {{ __("Confirm Deletion") }}
                        </div>
                    </div>

                    <div class="text-body1 q-mb-lg">
                        {{
                            __(
                                "You are about to delete the following price option. This action cannot be undone."
                            )
                        }}
                    </div>

                    <!-- Price Details -->
                    <q-card flat bordered class="bg-grey-2 price-details-card">
                        <q-card-section>
                            <div class="text-subtitle2 text-grey-8 q-mb-sm">
                                {{ __("Price Details") }}
                            </div>

                            <div class="row items-center q-mb-xs">
                                <q-icon
                                    name="mdi-cash"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">{{
                                    __("Amount:")
                                }}</span>
                                <span
                                    class="q-ml-sm text-negative text-weight-bold"
                                    >{{ item.amount_format }}</span
                                >
                                <span class="q-ml-xs text-grey-7"
                                    >({{ item.currency }})</span
                                >
                            </div>

                            <div class="row items-center q-mb-xs">
                                <q-icon
                                    name="mdi-calendar-refresh"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">{{
                                    __("Billing Period:")
                                }}</span>
                                <span class="q-ml-sm text-capitalize">{{
                                    item.billing_period
                                }}</span>
                            </div>

                            <div
                                v-if="item.expiration"
                                class="row items-center"
                            >
                                <q-icon
                                    name="mdi-clock-alert"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">{{
                                    __("Expiration:")
                                }}</span>
                                <span class="q-ml-sm">{{
                                    item.expiration
                                }}</span>
                            </div>
                        </q-card-section>
                    </q-card>

                    <div class="text-caption text-grey-7 q-mt-md">
                        <q-icon
                            name="mdi-information"
                            size="16px"
                            class="q-mr-xs"
                        />
                        {{
                            __(
                                "This price will be permanently removed from the system."
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
                        :label="__('Delete Price')"
                        color="negative"
                        @click="destroy"
                        class="action-btn"
                        icon="mdi-delete"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["deleted"],

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
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Price has been deleted successfully",
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
    --color-negative: #c10015;
    --color-warning: #f2c037;
    --color-dark: #1d1d1d;
    --color-light: #f5f5f5;
    --border-radius: 12px;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition-speed: 0.3s;
}

.delete-price-btn {
    transition: transform var(--transition-speed) ease,
        box-shadow var(--transition-speed) ease;
}

.delete-price-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
}

.delete-price-dialog {
    border-radius: var(--border-radius);
    overflow: hidden;
    max-width: 500px;
    width: 100%;
}

.dialog-header {
    padding: 20px 24px;
}

.warning-section {
    padding: 24px;
}

.price-details-card {
    border-left: 4px solid var(--color-negative);
}

.dialog-actions {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    background: var(--color-light);
}

.action-btn {
    border-radius: 8px;
    padding: 8px 20px;
    font-weight: 500;
    min-width: 120px;
}

/* Responsive adjustments */
@media (max-width: 599px) {
    .delete-price-dialog {
        max-width: 95vw;
    }

    .dialog-header .text-h5 {
        font-size: 1.25rem;
    }

    .warning-section {
        padding: 16px;
    }

    .action-btn {
        min-width: 100px;
        padding: 6px 16px;
    }

    .price-details-card {
        margin: 0 -8px;
    }
}
</style>
