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
        round
        icon="mdi-plus"
        color="positive"
        @click="open"
        class="create-btn"
        size="md"
    >
        <q-tooltip>Create New API Key</q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        maximized
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card class="api-key-dialog">
            <!-- Header -->
            <q-card-section class="dialog-header">
                <q-toolbar class="header-toolbar">
                    <q-icon
                        name="mdi-key-plus"
                        size="28px"
                        color="primary"
                        class="q-mr-md"
                    />
                    <q-toolbar-title
                        class="text-h5 text-weight-bold text-grey-8"
                    >
                        Generate New API Key
                    </q-toolbar-title>
                    <q-btn
                        flat
                        dense
                        icon="mdi-close"
                        @click="close"
                        class="close-btn"
                    />
                </q-toolbar>
            </q-card-section>

            <q-card-section class="dialog-content">
                <!-- Form Section -->
                <div class="form-section q-mb-xl">
                    <div class="text-h6 text-weight-medium text-grey-8 q-mb-md">
                        <q-icon name="mdi-form-textbox" class="q-mr-sm" />
                        Key Details
                    </div>

                    <q-input
                        v-model="form.name"
                        label="API Key Name"
                        outlined
                        :error="!!errors.name"
                        placeholder="Enter a descriptive name for your API key"
                        class="name-input"
                        dense
                    >
                        <template v-slot:prepend>
                            <q-icon name="mdi-tag" color="grey-6" />
                        </template>
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>

                    <div class="action-buttons q-mt-lg">
                        <q-btn
                            @click="create"
                            color="primary"
                            icon="mdi-key-variant"
                            label="Generate API Key"
                            unelevated
                            :loading="loading"
                            class="generate-btn"
                        >
                            <template v-slot:loading>
                                <q-spinner-hourglass class="on-left" />
                                Generating...
                            </template>
                        </q-btn>
                    </div>

                    <!-- Token Display -->
                    <div v-if="token" class="token-display q-mt-lg">
                        <div
                            class="text-h6 text-weight-medium text-green-8 q-mb-sm"
                        >
                            <q-icon name="mdi-key-chain" class="q-mr-sm" />
                            API Key Generated Successfully!
                        </div>
                        <div
                            class="token-alert bg-green-1 rounded-borders q-pa-md"
                        >
                            <div class="text-caption text-green-8 q-mb-sm">
                                <q-icon
                                    name="mdi-alert-circle"
                                    size="18px"
                                    class="q-mr-xs"
                                />
                                Please copy this token and store it securely.
                                You won't be able to see it again.
                            </div>
                            <div
                                class="token-value-container"
                                @click="copyToClipboard(token.accessToken)"
                            >
                                <code class="token-value">{{
                                    token.accessToken
                                }}</code>
                                <q-btn
                                    color="green"
                                    icon="mdi-content-copy"
                                    round
                                    flat
                                    size="sm"
                                    class="copy-btn"
                                >
                                    <q-tooltip>Copy to clipboard</q-tooltip>
                                </q-btn>
                            </div>
                            <div class="text-caption text-green-7 q-mt-xs">
                                Click anywhere on the token to copy it
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scopes Section -->
                <div class="scopes-section">
                    <div class="text-h6 text-weight-medium text-grey-8 q-mb-md">
                        <q-icon name="mdi-shield-account" class="q-mr-sm" />
                        API Permissions
                    </div>
                    <div class="text-caption text-grey-7 q-mb-lg">
                        Select the permissions you want to grant to this API
                        key. Choose only the necessary scopes for security best
                        practices.
                    </div>

                    <div
                        v-for="(services, group) in groupedScopes"
                        :key="group"
                        class="scope-group q-mb-lg"
                    >
                        <q-expansion-item
                            expand-separator
                            :label="group"
                            v-model="expanded"
                            header-class="scope-group-header text-primary"
                            class="scope-expansion"
                        >
                            <template v-slot:header>
                                <q-item-section>
                                    <div class="text-weight-medium">
                                        {{ group }}
                                    </div>
                                </q-item-section>
                                <q-item-section side>
                                    <q-badge color="primary" rounded>
                                        {{ getGroupScopeCount(services) }}
                                        permissions
                                    </q-badge>
                                </q-item-section>
                            </template>

                            <div
                                v-for="(roles, service) in services"
                                :key="service"
                                class="service-section q-pa-md bg-grey-1 rounded-borders q-mb-md"
                            >
                                <h6
                                    class="text-weight-medium text-grey-8 service-title"
                                >
                                    <q-icon
                                        name="mdi-cog"
                                        size="18px"
                                        class="q-mr-sm"
                                    />
                                    {{ service }}
                                </h6>
                                <q-option-group
                                    v-model="form.scopes"
                                    :options="
                                        roles.map((role) => ({
                                            label: role.name,
                                            value: role.id,
                                            description:
                                                role.description ||
                                                `Access to ${role.name} functionality`,
                                        }))
                                    "
                                    color="primary"
                                    type="checkbox"
                                    class="scope-checkbox-grid"
                                >
                                    <template v-slot:label="opt">
                                        <div class="scope-option">
                                            <div
                                                class="scope-label text-weight-medium"
                                            >
                                                {{ opt.label }}
                                            </div>
                                            <div
                                                class="scope-description text-caption text-grey-7"
                                            >
                                                {{ opt.description }}
                                            </div>
                                        </div>
                                    </template>
                                </q-option-group>
                            </div>
                        </q-expansion-item>
                    </div>
                </div>
            </q-card-section>

            <!-- Footer Actions -->
            <q-card-actions class="dialog-footer" align="right">
                <q-btn
                    label="Cancel"
                    color="grey"
                    flat
                    @click="close"
                    class="q-mr-sm"
                />
                <q-btn
                    @click="create"
                    color="primary"
                    icon="mdi-key-variant"
                    label="Generate Key"
                    unelevated
                    :loading="loading"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script>
export default {
    emits: ["created"],
    data() {
        return {
            form: {
                name: "",
                scopes: [],
                expiration_date: "",
            },
            errors: {},
            scopes: [],
            dialog: false,
            expanded: true,
            loading: false,
            token: null,
        };
    },
    computed: {
        groupedScopes() {
            const grouped = {};
            this.scopes.forEach((scope) => {
                if (scope.id) {
                    const [group, service, role] = scope.id.split(":");
                    if (!grouped[group]) grouped[group] = {};
                    if (!grouped[group][service]) grouped[group][service] = [];
                    Object.assign(scope, { name: role });
                    grouped[group][service].push(scope);
                }
            });
            return grouped;
        },
    },

    methods: {
        async open() {
            this.getScopes();
            this.token = null;
            this.errors = {};
            this.dialog = true;
        },
        close() {
            this.token = null;
            this.errors = {};
            this.dialog = false;
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "API key copied to clipboard",
                    icon: "mdi-check",
                    position: "top",
                    timeout: 2000,
                });
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: "Failed to copy to clipboard",
                    icon: "mdi-alert",
                    position: "top",
                });
            }
        },

        async create() {
            this.loading = true;
            this.token = null;
            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );
                if (res.status == 200) {
                    this.errors = {};
                    this.token = res.data;
                    this.$emit("created");
                    this.$q.notify({
                        type: "positive",
                        message: "API key generated successfully",
                        icon: "mdi-check-circle",
                        position: "top",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        type: "negative",
                        message: "Please fix the form errors",
                        icon: "mdi-alert-circle",
                        position: "top",
                    });
                } else if (e?.response?.status == 404) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        icon: "mdi-alert",
                        position: "top",
                        timeout: 5000,
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: "Failed to create API key",
                        icon: "mdi-alert",
                        position: "top",
                    });
                }
            } finally {
                this.loading = false;
            }
        },

        getScopes() {
            this.$server
                .get("/oauth/scopes")
                .then((res) => {
                    this.scopes = res.data;
                })
                .catch(() => {
                    this.$q.notify({
                        type: "negative",
                        message: "Failed to load permissions",
                        icon: "mdi-alert",
                        position: "top",
                    });
                });
        },

        getGroupScopeCount(services) {
            return Object.values(services).reduce(
                (total, roles) => total + roles.length,
                0
            );
        },
    },
};
</script>

<style lang="scss" scoped>
.create-btn {
    transition: all 0.3s ease;

    &:hover {
        transform: scale(1.1);
        background: rgba(0, 255, 0, 0.1);
    }
}

.api-key-dialog {
    border-radius: 16px;
    max-width: 1000px;
    margin: 0 auto;

    .dialog-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        padding: 20px 24px;

        .header-toolbar {
            padding: 0;
        }

        .close-btn {
            margin-right: -8px;
        }
    }

    .dialog-content {
        padding: 24px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .form-section {
        .name-input {
            :deep(.q-field__control) {
                border-radius: 8px;
            }
        }

        .action-buttons {
            .generate-btn {
                height: 48px;
                border-radius: 8px;
                font-weight: 600;
                text-transform: none;
                letter-spacing: 0.5px;
            }
        }

        .token-display {
            .token-alert {
                border-left: 4px solid #4caf50;
            }

            .token-value-container {
                display: flex;
                align-items: center;
                background: white;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid #e0e0e0;
                cursor: pointer;
                transition: all 0.3s ease;

                &:hover {
                    border-color: #4caf50;
                    background: #f8fff8;
                }

                .token-value {
                    flex: 1;
                    font-family: "Monaco", "Menlo", "Ubuntu Mono", monospace;
                    font-size: 0.85rem;
                    word-break: break-all;
                    color: #2d3748;
                }

                .copy-btn {
                    flex-shrink: 0;
                    opacity: 0.7;
                    transition: opacity 0.3s ease;

                    &:hover {
                        opacity: 1;
                    }
                }
            }
        }
    }

    .scopes-section {
        .scope-group {
            .scope-group-header {
                font-size: 1.1rem;
                padding: 16px;
                background: rgba(0, 0, 0, 0.02);
                border-radius: 8px;

                :deep(.q-item__section--side) {
                    display: flex;
                    align-items: center;
                }
            }

            .service-section {
                border: 1px solid rgba(0, 0, 0, 0.05);

                .service-title {
                    margin-bottom: 16px;
                    padding-bottom: 8px;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                }
            }

            .scope-checkbox-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 16px;

                .scope-option {
                    padding: 12px;
                    border: 1px solid rgba(0, 0, 0, 0.1);
                    border-radius: 8px;
                    transition: all 0.3s ease;
                    cursor: pointer;

                    &:hover {
                        border-color: var(--q-primary);
                        background: rgba(0, 123, 255, 0.05);
                    }

                    .scope-label {
                        margin-bottom: 4px;
                        font-size: 0.95rem;
                    }

                    .scope-description {
                        line-height: 1.4;
                    }
                }
            }
        }
    }

    .dialog-footer {
        padding: 20px 24px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        background: #f8f9fa;
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .api-key-dialog {
        margin: 16px;

        .dialog-content {
            padding: 20px;
        }

        .scope-checkbox-grid {
            grid-template-columns: 1fr !important;
        }
    }
}

@media (max-width: 599px) {
    .api-key-dialog {
        margin: 8px;

        .dialog-header {
            padding: 16px 20px;

            .text-h5 {
                font-size: 1.5rem;
            }
        }

        .dialog-content {
            padding: 16px;
        }

        .token-value-container {
            flex-direction: column;
            align-items: stretch !important;

            .copy-btn {
                align-self: flex-end;
                margin-top: 8px;
            }
        }
    }
}

// Animation for dialog
.api-key-dialog {
    animation: slideInUp 0.3s ease;
}

@keyframes slideInUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
