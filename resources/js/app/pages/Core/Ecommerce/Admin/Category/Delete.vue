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
        <!-- Delete Button -->
        <q-btn
            color="negative"
            @click="dialog = true"
            icon="mdi-delete"
            size="sm"
            class="delete-btn shadow-3"
            unelevated
        >
            Delete
            <q-tooltip class="bg-negative">Delete Category</q-tooltip>
        </q-btn>

        <!-- Delete Confirmation Dialog -->
        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="delete-category-dialog rounded-borders">
                <!-- Dialog Header -->
                <q-card-section class="dialog-header bg-negative text-white">
                    <div class="row items-center">
                        <q-icon
                            name="mdi-delete-alert"
                            size="28px"
                            class="q-mr-sm"
                        />
                        <div class="text-h5 text-weight-bold">
                            Delete Category
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
                            name="mdi-alert-circle"
                            color="negative"
                            size="48px"
                            class="q-mr-md"
                        />
                        <div class="text-h6 text-weight-medium">
                            Confirm Deletion
                        </div>
                    </div>

                    <div class="text-body1 q-mb-lg">
                        You are about to delete the category
                        <strong class="text-negative">"{{ item.name }}"</strong
                        >. This action cannot be undone and will remove all
                        associated data.
                    </div>

                    <!-- Category Details -->
                    <q-card
                        flat
                        bordered
                        class="bg-grey-2 category-details-card"
                    >
                        <q-card-section>
                            <div class="text-subtitle2 text-grey-8 q-mb-sm">
                                Category Details
                            </div>

                            <div class="row items-center q-mb-xs">
                                <q-icon
                                    name="mdi-tag"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">Name:</span>
                                <span class="q-ml-sm">{{ item.name }}</span>
                            </div>

                            <div
                                class="row items-center q-mb-xs"
                                v-if="item.icon?.icon"
                            >
                                <q-icon
                                    name="mdi-emoticon"
                                    size="16px"
                                    class="q-mr-sm text-grey-6"
                                />
                                <span class="text-weight-medium">Icon:</span>
                                <span class="q-ml-sm">
                                    <q-icon
                                        :name="item.icon.icon"
                                        size="20px"
                                        class="text-primary q-mr-xs"
                                    />
                                    {{ item.icon.icon }}
                                </span>
                            </div>

                            <div class="row items-center">
                                <q-icon
                                    name="mdi-eye"
                                    size="16px"
                                    :color="
                                        item.published ? 'positive' : 'grey-5'
                                    "
                                    class="q-mr-sm"
                                />
                                <span class="text-weight-medium">Status:</span>
                                <span class="q-ml-sm">{{
                                    item.published ? "Published" : "Hidden"
                                }}</span>
                            </div>

                            <div class="row items-center q-mt-xs">
                                <q-icon
                                    name="mdi-star"
                                    size="16px"
                                    :color="item.featured ? 'accent' : 'grey-5'"
                                    class="q-mr-sm"
                                />
                                <span class="text-weight-medium"
                                    >Featured:</span
                                >
                                <span class="q-ml-sm">{{
                                    item.featured ? "Yes" : "No"
                                }}</span>
                            </div>
                        </q-card-section>
                    </q-card>

                    <div class="text-caption text-negative q-mt-md">
                        <q-icon name="mdi-alert" size="16px" class="q-mr-xs" />
                        Warning: This will permanently delete the category and
                        cannot be recovered.
                    </div>
                </q-card-section>

                <!-- Dialog Actions -->
                <q-card-actions align="right" class="dialog-actions q-pa-md">
                    <q-btn
                        label="Cancel"
                        color="grey"
                        @click="dialog = false"
                        outline
                        class="action-btn"
                        icon="mdi-close"
                    />
                    <q-btn
                        label="Delete Category"
                        color="negative"
                        @click="destroy"
                        class="action-btn"
                        icon="mdi-delete"
                        unelevated
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
                        message: "Category has been deleted successfully",
                        timeout: 3000,
                        icon: "mdi-check-circle",
                        position: "top-right",
                        actions: [{ icon: "mdi-close", color: "white" }],
                    });
                }
            } catch (err) {
                if (err?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: err.response.data.message,
                        timeout: 3000,
                        icon: "mdi-alert-circle",
                        position: "top-right",
                        actions: [{ icon: "mdi-close", color: "white" }],
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: "Failed to delete category. Please try again.",
                        timeout: 3000,
                        icon: "mdi-alert-circle",
                        position: "top-right",
                        actions: [{ icon: "mdi-close", color: "white" }],
                    });
                }
            } finally {
                this.dialog = false;
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

.delete-btn {
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 500;
    transition: transform var(--transition-speed) ease,
        box-shadow var(--transition-speed) ease;
}

.delete-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
}

.delete-category-dialog {
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

.category-details-card {
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
    min-width: 140px;
    transition: transform var(--transition-speed) ease;
}

.action-btn:hover {
    transform: scale(1.02);
}

/* Responsive adjustments */
@media (max-width: 599px) {
    .delete-category-dialog {
        max-width: 95vw;
    }

    .dialog-header .text-h5 {
        font-size: 1.25rem;
    }

    .warning-section {
        padding: 16px;
    }

    .action-btn {
        min-width: 120px;
        padding: 6px 16px;
        font-size: 0.9rem;
    }

    .category-details-card {
        margin: 0 -8px;
    }
}
</style>
