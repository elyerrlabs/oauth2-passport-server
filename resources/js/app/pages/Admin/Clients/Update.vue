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
    <!-- Edit Button -->
    <q-btn
        round
        outline
        color="primary"
        @click="open(item)"
        icon="mdi-pencil"
        class="edit-btn"
    >
        <q-tooltip>Edit Client</q-tooltip>
    </q-btn>

    <!-- Update Dialog -->
    <q-dialog
        v-model="dialog"
        persistent
        transition-show="scale"
        transition-hide="scale"
        class="update-dialog"
    >
        <q-card class="update-card">
            <!-- Header -->
            <q-card-section class="dialog-header">
                <div class="header-content">
                    <q-icon
                        name="mdi-application-edit"
                        size="28px"
                        color="primary"
                        class="header-icon"
                    />
                    <div class="text-h5 text-weight-bold text-grey-8">
                        Update OAuth Client
                    </div>
                </div>
                <div class="text-subtitle2 text-grey-6">
                    Modify your OAuth 2.0 client application settings
                </div>
            </q-card-section>

            <!-- Form Content -->
            <q-card-section class="dialog-content">
                <!-- Client Name -->
                <div class="form-section q-mb-lg">
                    <div
                        class="input-label text-weight-medium text-grey-8 q-mb-xs"
                    >
                        <q-icon name="mdi-tag" size="18px" class="q-mr-sm" />
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
                        <template v-slot:prepend>
                            <q-icon name="mdi-application" color="grey-6" />
                        </template>
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>
                    <div class="input-hint text-caption text-grey-6 q-mt-xs">
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
                        <template v-slot:prepend>
                            <q-icon name="mdi-link" color="grey-6" />
                        </template>
                        <template v-slot:error>
                            <v-error :error="errors.redirect"></v-error>
                        </template>
                    </q-input>
                    <div class="input-hint text-caption text-grey-6 q-mt-xs">
                        The URI where users will be redirected after
                        authorization
                    </div>
                </div>

                <!-- Client ID Display (Read-only) -->
                <div class="form-section q-mb-lg" v-if="form.id">
                    <div
                        class="input-label text-weight-medium text-grey-8 q-mb-xs"
                    >
                        <q-icon name="mdi-key" size="18px" class="q-mr-sm" />
                        Client ID
                    </div>
                    <q-input
                        :model-value="form.id"
                        label="Client ID"
                        dense
                        outlined
                        readonly
                        class="client-id-input"
                    >
                        <template v-slot:prepend>
                            <q-icon name="mdi-identifier" color="grey-6" />
                        </template>
                        <template v-slot:append>
                            <q-btn
                                icon="mdi-content-copy"
                                size="sm"
                                flat
                                round
                                @click="copyToClipboard(form.id)"
                            >
                                <q-tooltip>Copy Client ID</q-tooltip>
                            </q-btn>
                        </template>
                    </q-input>
                    <div class="input-hint text-caption text-grey-6 q-mt-xs">
                        This identifier cannot be changed
                    </div>
                </div>
            </q-card-section>

            <!-- Actions -->
            <q-card-actions class="dialog-actions" align="right">
                <q-btn
                    label="Cancel"
                    color="grey-6"
                    flat
                    @click="close"
                    class="cancel-btn"
                    no-caps
                />
                <q-btn
                    label="Update Client"
                    color="primary"
                    @click="updateClient"
                    :loading="loading"
                    icon="mdi-check"
                    unelevated
                    class="update-btn"
                    no-caps
                >
                    <template v-slot:loading>
                        <q-spinner-hourglass class="on-left" />
                        Updating...
                    </template>
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script>
export default {
    emits: ["updated"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            errors: {
                name: "",
                redirect: "",
            },
            form: {},
            dialog: false,
            loading: false,
        };
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
            this.loading = false;
        },

        open(item) {
            this.form = { ...item };
            this.errors = {};
            this.dialog = true;
        },

        async updateClient() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.$emit("updated", true);
                    this.dialog = false;

                    this.$q.notify({
                        message: "OAuth client updated successfully",
                        color: "positive",
                        icon: "mdi-check-circle",
                        position: "top-right",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        message: "Please fix the form errors",
                        color: "negative",
                        icon: "mdi-alert-circle",
                        position: "top",
                    });
                } else {
                    this.$q.notify({
                        message: "Failed to update OAuth client",
                        color: "negative",
                        icon: "error",
                        position: "top",
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    message: "Client ID copied to clipboard",
                    color: "positive",
                    icon: "mdi-check",
                    position: "top-right",
                    timeout: 2000,
                });
            } catch (err) {
                this.$q.notify({
                    message: "Failed to copy to clipboard",
                    color: "negative",
                    icon: "error",
                    position: "top",
                });
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.edit-btn {
    transition: all 0.3s ease;

    &:hover {
        background: rgba(0, 123, 255, 0.1);
        transform: scale(1.1);
    }
}

.update-dialog {
    .update-card {
        border-radius: 16px;
        max-width: 500px;
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
                .redirect-input,
                .client-id-input {
                    :deep(.q-field__control) {
                        border-radius: 8px;
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

            .update-btn {
                padding: 8px 24px;
                border-radius: 8px;
                font-weight: 600;
            }
        }
    }
}

// Animation for dialog
.update-card {
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
    .update-card {
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
    .update-card {
        .dialog-header {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        }

        .dialog-actions {
            background: #2d3748;
        }
    }
}
</style>
