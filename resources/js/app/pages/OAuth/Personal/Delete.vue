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
    <!-- Delete Button -->
    <q-btn
        color="negative"
        icon="mdi-delete-outline"
        round
        flat
        size="sm"
        @click="openDialog"
        class="delete-btn"
    >
        <q-tooltip>Delete API Key</q-tooltip>
    </q-btn>

    <!-- Confirmation Dialog -->
    <q-dialog
        v-model="dialog"
        persistent
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card class="delete-dialog">
            <!-- Header -->
            <q-card-section class="dialog-header">
                <div class="row items-center">
                    <q-icon
                        name="mdi-alert-circle"
                        color="negative"
                        size="28px"
                        class="q-mr-md"
                    />
                    <div class="text-h6 text-weight-bold text-grey-8">
                        Confirm Deletion
                    </div>
                </div>
            </q-card-section>

            <!-- Content -->
            <q-card-section class="dialog-content">
                <div class="confirmation-message">
                    <p class="text-body1 text-grey-8 q-mb-md">
                        Are you sure you want to permanently delete the API key
                        with name
                    </p>
                    <div class="token-display q-mb-lg">
                        <q-chip
                            color="blue"
                            text-color="white"
                            class="token-chip"
                            icon="mdi-key"
                        >
                            <span class="text-weight-bold">{{
                                item.name
                            }}</span>
                        </q-chip>
                    </div>
                    <div class="warning-alert bg-red-1 rounded-borders q-pa-md">
                        <div class="row items-center">
                            <q-icon
                                name="mdi-alert"
                                color="negative"
                                size="20px"
                                class="q-mr-sm"
                            />
                            <span class="text-negative text-weight-medium"
                                >This action cannot be undone.</span
                            >
                        </div>
                        <div class="text-caption text-negative q-mt-xs">
                            Any applications using this API key will immediately
                            lose access.
                        </div>
                    </div>
                </div>
            </q-card-section>

            <!-- Actions -->
            <q-card-actions class="dialog-actions" align="right">
                <q-btn
                    label="Cancel"
                    color="grey-6"
                    flat
                    @click="closeDialog"
                    class="cancel-btn"
                    no-caps
                />
                <q-btn
                    label="Delete API Key"
                    color="negative"
                    @click="destroy"
                    :loading="loading"
                    icon="mdi-delete"
                    class="delete-confirm-btn"
                    no-caps
                >
                    <template v-slot:loading>
                        <q-spinner-hourglass class="on-left" />
                        Deleting...
                    </template>
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
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
            loading: false,
        };
    },

    methods: {
        openDialog() {
            this.dialog = true;
        },

        closeDialog() {
            this.dialog = false;
        },

        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);
                if (res.status === 200) {
                    this.$emit("deleted", true);
                    this.showSuccessNotification();
                    this.dialog = false;
                }
            } catch (error) {
                this.showErrorNotification(error);
            } finally {
                this.loading = false;
            }
        },

        showSuccessNotification() {
            this.$q.notify({
                type: "positive",
                message: "API key deleted successfully",
                icon: "mdi-check-circle",
                position: "top-right",
                timeout: 3000,
                progress: true,
            });
        },

        showErrorNotification(error) {
            let message = "Failed to delete API key";

            if (error.response?.status === 404) {
                message = "API key not found or already deleted";
            } else if (error.response?.status === 403) {
                message = "You don't have permission to delete this API key";
            }

            this.$q.notify({
                type: "negative",
                message: message,
                icon: "mdi-alert-circle",
                position: "top-right",
                timeout: 5000,
                progress: true,
            });
        },
    },
};
</script>

<style lang="scss" scoped>
.delete-btn {
    transition: all 0.3s ease;

    &:hover {
        background: rgba(244, 67, 54, 0.1);
        transform: scale(1.1);
    }

    &:active {
        transform: scale(0.95);
    }
}

.delete-dialog {
    border-radius: 16px;
    max-width: 500px;
    width: 90vw;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);

    .dialog-header {
        background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
        border-bottom: 1px solid #fed7d7;
        padding: 20px 24px;

        .text-h6 {
            color: #e53e3e;
        }
    }

    .dialog-content {
        padding: 24px;

        .confirmation-message {
            text-align: center;

            .token-display {
                .token-chip {
                    font-size: 1rem;
                    padding: 8px 16px;

                    ::v-deep .q-chip__icon {
                        font-size: 18px;
                    }
                }
            }

            .warning-alert {
                border-left: 4px solid #e53e3e;
                text-align: left;

                .text-negative {
                    color: #e53e3e !important;
                }
            }
        }
    }

    .dialog-actions {
        padding: 20px 24px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        background: #f8f9fa;

        .cancel-btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .delete-confirm-btn {
            padding: 8px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.5px;

            &:hover {
                background: #d32f2f !important;
            }
        }
    }
}

// Animation for dialog
.delete-dialog {
    animation: dialogEnter 0.3s ease;
}

@keyframes dialogEnter {
    from {
        transform: scale(0.9);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

// Responsive adjustments
@media (max-width: 599px) {
    .delete-dialog {
        width: 95vw;
        margin: 8px;

        .dialog-header {
            padding: 16px 20px;

            .text-h6 {
                font-size: 1.25rem;
            }
        }

        .dialog-content {
            padding: 20px;
        }

        .dialog-actions {
            padding: 16px 20px;
            flex-direction: column;
            gap: 12px;

            .q-btn {
                width: 100%;
            }
        }
    }
}

// Dark mode support (optional)
.body--dark {
    .delete-dialog {
        .dialog-header {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            border-bottom-color: #4a5568;
        }

        .dialog-actions {
            background: #2d3748;
        }
    }
}
</style>
