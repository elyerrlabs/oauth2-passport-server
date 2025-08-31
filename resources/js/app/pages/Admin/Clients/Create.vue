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
    <div class="create-client-container">
        <!-- Create Button -->
        <q-btn
            outline
            color="primary"
            @click="open"
            icon="mdi-plus-circle"
            size="md"
            label="Create New OAuth Clien"
            class="create-btn"
        >
        </q-btn>

        <!-- Creation Dialog -->
        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
            class="client-dialog"
        >
            <q-card class="client-card">
                <!-- Header -->
                <q-card-section class="dialog-header">
                    <div class="header-content">
                        <q-icon
                            name="mdi-application-plus"
                            size="32px"
                            color="primary"
                            class="header-icon"
                        />
                        <div class="text-h5 text-weight-bold text-grey-8">
                            Create New OAuth Client
                        </div>
                    </div>
                    <div class="text-subtitle2 text-grey-6">
                        Register a new OAuth 2.0 client application
                    </div>
                </q-card-section>

                <!-- Form Content -->
                <q-card-section class="dialog-content">
                    <!-- Client Name -->
                    <div class="form-section q-mb-lg">
                        <div
                            class="input-label text-weight-medium text-grey-8 q-mb-xs"
                        >
                            <q-icon
                                name="mdi-tag"
                                size="18px"
                                class="q-mr-sm"
                            />
                            Client Name
                        </div>
                        <q-input
                            v-model="form.name"
                            placeholder="Enter a descriptive name for your client application"
                            dense
                            outlined
                            :error="!!errors.name"
                            class="name-input"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.name"></v-error>
                            </template>
                        </q-input>
                        <div
                            class="input-hint text-caption text-grey-6 q-mt-xs"
                        >
                            This will help you identify the client later
                        </div>
                    </div>

                    <!-- Redirect URI -->
                    <div class="form-section q-mb-lg">
                        <div
                            class="input-label text-weight-medium text-grey-8 q-mb-xs"
                        >
                            <q-icon
                                name="mdi-redirect"
                                size="18px"
                                class="q-mr-sm"
                            />
                            Redirect URI
                        </div>
                        <q-input
                            v-model="form.redirect"
                            placeholder="https://yourapp.com/oauth/callback"
                            dense
                            outlined
                            :error="!!errors.redirect"
                            class="redirect-input"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.redirect"></v-error>
                            </template>
                        </q-input>
                        <div
                            class="input-hint text-caption text-grey-6 q-mt-xs"
                        >
                            The URI where users will be redirected after
                            authorization
                        </div>
                    </div>

                    <!-- Confidential Client -->
                    <div class="form-section q-mb-lg">
                        <q-checkbox
                            v-model="form.confidential"
                            label="Confidential Client"
                            color="primary"
                            :error="!!errors.confidential"
                            class="confidential-checkbox"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.confidential"></v-error>
                            </template>
                        </q-checkbox>
                        <div
                            class="input-hint text-caption text-grey-6 q-mt-xs"
                        >
                            <q-icon
                                name="mdi-information"
                                size="16px"
                                class="q-mr-xs"
                            />
                            Confidential clients can keep secrets secure
                            (server-side applications). Uncheck for public
                            clients (SPA, mobile apps).
                        </div>
                    </div>
                </q-card-section>

                <!-- Actions -->
                <q-card-actions class="dialog-actions" align="right">
                    <q-btn
                        label="Close"
                        color="grey-6"
                        flat
                        @click="close"
                        class="cancel-btn"
                        no-caps
                    />
                    <q-btn
                        label="Create Client"
                        color="primary"
                        @click="create"
                        :loading="loading"
                        icon="mdi-check"
                        unelevated
                        class="create-confirm-btn"
                        no-caps
                    >
                        <template v-slot:loading>
                            <q-spinner-hourglass class="on-left" />
                            Creating...
                        </template>
                    </q-btn>
                </q-card-actions>

                <!-- Credentials Display -->
                <q-card-section
                    v-if="client && Object.keys(client).length"
                    class="credentials-section"
                >
                    <div
                        class="credentials-alert bg-red-1 rounded-borders q-pa-md"
                    >
                        <div class="alert-header row items-center q-mb-md">
                            <q-icon
                                name="mdi-alert-circle"
                                color="negative"
                                size="24px"
                                class="q-mr-sm"
                            />
                            <div class="text-h6 text-negative text-weight-bold">
                                Important Security Notice
                            </div>
                        </div>

                        <div class="alert-content">
                            <p class="text-body2 text-negative q-mb-md">
                                These credentials will only be shown once.
                                Please store them securely immediately.
                            </p>

                            <div
                                class="credentials-actions row items-center justify-between"
                            >
                                <div class="text-caption text-negative">
                                    <q-icon
                                        name="mdi-shield-key"
                                        class="q-mr-xs"
                                    />
                                    Download your credentials for safe keeping
                                </div>

                                <q-btn
                                    label="Download Credentials"
                                    color="negative"
                                    icon="mdi-download"
                                    unelevated
                                    @click="downloadJsonFile"
                                    class="download-btn"
                                    no-caps
                                >
                                    <q-tooltip>Download as JSON file</q-tooltip>
                                </q-btn>
                            </div>
                        </div>
                    </div>

                    <!-- Client Details -->
                    <div class="client-details q-mt-md">
                        <div
                            class="text-subtitle2 text-weight-medium text-grey-8 q-mb-sm"
                        >
                            Client Details:
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Client ID:</span>
                                <span class="detail-value">{{
                                    client.id
                                }}</span>
                            </div>
                            <div v-if="client.secret" class="detail-item">
                                <span class="detail-label">Client Secret:</span>
                                <span class="detail-value text-red">{{
                                    client.secret
                                }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Name:</span>
                                <span class="detail-value">{{
                                    client.name
                                }}</span>
                            </div>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["created"],

    data() {
        return {
            dialog: false,
            form: {
                name: "",
                redirect: "",
                confidential: false,
            },
            errors: {},
            client: {},
            loading: false,
        };
    },

    methods: {
        close() {
            this.clean();
            this.dialog = false;
        },

        clean() {
            this.client = {};
            this.errors = {};
            this.form = {
                name: "",
                redirect: "",
                confidential: false,
            };
            this.loading = false;
        },

        open() {
            this.clean();
            this.dialog = true;
        },

        downloadJsonFile() {
            const clientCopy = { ...this.client };
            // Remove unnecessary properties
            delete clientCopy.links;
            delete clientCopy.revoked;
            delete clientCopy.provider;
            delete clientCopy.redirect;

            const filename = `${clientCopy.name || "client"}-credentials.json`;
            const jsonString = JSON.stringify(clientCopy, null, 2);
            const blob = new Blob([jsonString], { type: "application/json" });
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);

            this.$q.notify({
                message: "Credentials downloaded successfully",
                color: "positive",
                icon: "mdi-check",
                position: "top-right",
                timeout: 2000,
            });
        },

        async create() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.routes.clients,
                    this.form
                );

                if (res.status === 201) {
                    this.client = res.data.data;
                    this.$emit("created", true);

                    this.$q.notify({
                        message: "OAuth client created successfully",
                        color: "positive",
                        icon: "mdi-check-circle",
                        position: "top-right",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        message: "Please fix the form errors",
                        color: "negative",
                        icon: "mdi-alert-circle",
                        position: "top",
                    });
                } else {
                    this.$q.notify({
                        message: "Failed to create OAuth client",
                        color: "negative",
                        icon: "error",
                        position: "top",
                    });
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.create-client-container {
    .create-btn {
        transition: all 0.3s ease;

        &:hover {
            transform: scale(1.1);
            background: rgba(0, 123, 255, 0.1);
        }
    }
}

.client-dialog {
    .client-card {
        border-radius: 16px;
        max-width: 600px;
        width: 90vw;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);

        .dialog-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            padding: 24px;

            .header-content {
                display: flex;
                align-items: center;
                margin-bottom: 8px;

                .header-icon {
                    background: rgba(0, 0, 0, 0.05);
                    padding: 8px;
                    border-radius: 50%;
                    margin-right: 12px;
                }
            }
        }

        .dialog-content {
            padding: 24px;

            .form-section {
                .input-label {
                    display: flex;
                    align-items: center;
                    margin-bottom: 8px;
                }

                .name-input,
                .redirect-input {
                    :deep(.q-field__control) {
                        border-radius: 8px;
                    }
                }

                .confidential-checkbox {
                    :deep(.q-checkbox__inner) {
                        font-size: 18px;
                    }
                }

                .input-hint {
                    line-height: 1.4;
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

            .create-confirm-btn {
                padding: 8px 24px;
                border-radius: 8px;
                font-weight: 600;
            }
        }

        .credentials-section {
            padding: 24px;
            background: #fffaf5;

            .credentials-alert {
                border-left: 4px solid #e53e3e;

                .alert-header {
                    .text-negative {
                        color: #e53e3e !important;
                    }
                }

                .download-btn {
                    border-radius: 8px;
                    font-weight: 600;
                }
            }

            .client-details {
                .detail-grid {
                    display: grid;
                    gap: 12px;

                    .detail-item {
                        display: flex;
                        align-items: center;
                        padding: 12px;
                        background: white;
                        border-radius: 8px;
                        border: 1px solid #e2e8f0;

                        .detail-label {
                            font-weight: 600;
                            color: #4a5568;
                            min-width: 120px;
                        }

                        .detail-value {
                            font-family: "Monaco", "Menlo", "Ubuntu Mono",
                                monospace;
                            font-size: 0.9rem;
                            word-break: break-all;
                        }
                    }
                }
            }
        }
    }
}

// Animation for dialog
.client-card {
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
    .client-card {
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

        .credentials-section {
            padding: 20px;

            .credentials-actions {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;

                .download-btn {
                    width: 100%;
                }
            }

            .detail-grid {
                .detail-item {
                    flex-direction: column;
                    align-items: flex-start !important;
                    gap: 4px;
                }
            }
        }
    }
}
</style>
