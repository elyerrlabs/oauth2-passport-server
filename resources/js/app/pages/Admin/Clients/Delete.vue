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
        round
        outline
        color="negative"
        @click="openDialog"
        icon="mdi-delete-outline"
    >
        <q-tooltip>{{ __("Delete Client") }}</q-tooltip>
    </q-btn>

    <!-- Confirmation Dialog -->
    <q-dialog
        v-model="dialog"
        persistent
        transition-show="scale"
        transition-hide="scale"
        class="delete-dialog"
    >
        <q-card class="delete-card">
            <!-- Header -->
            <q-card-section class="dialog-header">
                <div class="header-content">
                    <q-icon
                        name="mdi-alert-circle"
                        color="negative"
                        size="32px"
                        class="header-icon"
                    />
                    <div class="text-h5 text-weight-bold text-grey-8">
                        {{ __("Delete OAuth Client") }}
                    </div>
                </div>
            </q-card-section>

            <!-- Content -->
            <q-card-section class="dialog-content">
                <div class="confirmation-message">
                    <p class="text-body1 text-grey-8 q-mb-lg">
                        {{
                            __(
                                "Are you sure you want to permanently delete the OAuth client"
                            )
                        }}
                    </p>

                    <div class="client-details q-mb-lg">
                        <div class="detail-item q-mb-sm">
                            <div class="detail-label text-caption text-grey-6">
                                {{ __("Client Name") }}
                            </div>
                            <div
                                class="detail-value text-weight-bold text-primary"
                            >
                                <q-icon
                                    name="mdi-application"
                                    size="18px"
                                    class="q-mr-sm"
                                />
                                {{ item.name }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label text-caption text-grey-6">
                                {{ __("Client ID") }}
                            </div>
                            <div
                                class="detail-value text-weight-medium text-grey-8"
                            >
                                <q-icon
                                    name="mdi-key"
                                    size="16px"
                                    class="q-mr-sm"
                                />
                                {{ item.id }}
                            </div>
                        </div>
                    </div>

                    <div class="warning-alert bg-red-1 rounded-borders q-pa-md">
                        <div class="row items-center q-mb-sm">
                            <q-icon
                                name="mdi-alert"
                                color="negative"
                                size="20px"
                                class="q-mr-sm"
                            />
                            <span class="text-negative text-weight-medium">{{
                                __("This action cannot be undone")
                            }}</span>
                        </div>
                        <div class="text-caption text-negative">
                            {{
                                __(
                                    "All applications using this client will immediately lose access. Any active sessions will be terminated."
                                )
                            }}
                        </div>
                    </div>
                </div>
            </q-card-section>

            <!-- Actions -->
            <q-card-actions class="dialog-actions" align="right">
                <q-btn
                    :label="__('Cancel')"
                    color="grey-6"
                    flat
                    @click="closeDialog"
                    class="cancel-btn"
                    no-caps
                />
                <q-btn
                    :label="__('Delete Client')"
                    color="negative"
                    @click="destroy"
                    :loading="loading"
                    icon="mdi-delete"
                    unelevated
                    class="delete-confirm-btn"
                    no-caps
                >
                    <template v-slot:loading>
                        <q-spinner-hourglass class="on-left" />
                        {{ __("Deleting...") }}
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
            this.loading = false;
        },

        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.dialog = false;
                    this.showSuccessNotification();
                }
            } catch (error) {
                this.showErrorNotification(error);
            } finally {
                this.loading = false;
            }
        },

        showSuccessNotification() {
            this.$q.notify({
                message: "OAuth client deleted successfully",
                color: "positive",
                icon: "mdi-check-circle",
                position: "top-right",
                timeout: 3000,
                progress: true,
            });
        },

        showErrorNotification(error) {
            let message = "Failed to delete OAuth client";

            if (error.response?.status === 404) {
                message = "Client not found or already deleted";
            } else if (error.response?.status === 403) {
                message = "You don't have permission to delete this client";
            } else if (error.response?.status === 409) {
                message = "Cannot delete client with active sessions";
            }

            this.$q.notify({
                message: message,
                color: "negative",
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
    .delete-card {
        border-radius: 16px;
        max-width: 500px;
        width: 90vw;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);

        .dialog-header {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border-bottom: 1px solid #fed7d7;
            padding: 24px;

            .header-content {
                display: flex;
                align-items: center;

                .header-icon {
                    background: rgba(244, 67, 54, 0.2);
                    padding: 8px;
                    border-radius: 50%;
                    margin-right: 12px;
                }

                .text-h5 {
                    color: #e53e3e;
                }
            }
        }

        .dialog-content {
            padding: 24px;

            .confirmation-message {
                text-align: center;

                .client-details {
                    .detail-item {
                        padding: 12px;
                        background: #f8f9fa;
                        border-radius: 8px;
                        border: 1px solid #e2e8f0;

                        .detail-label {
                            margin-bottom: 4px;
                        }

                        .detail-value {
                            display: flex;
                            align-items: center;
                            font-size: 1rem;
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

                &:hover {
                    background: #d32f2f !important;
                }
            }
        }
    }
}

// Animation for dialog
.delete-card {
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
    .delete-card {
        margin: 8px;

        .dialog-header {
            padding: 20px;

            .text-h5 {
                font-size: 1.5rem;
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
    .delete-card {
        .dialog-header {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            border-bottom-color: #4a5568;
        }

        .dialog-content {
            .client-details .detail-item {
                background: #2d3748;
                border-color: #4a5568;
            }
        }

        .dialog-actions {
            background: #2d3748;
        }
    }
}
</style>
