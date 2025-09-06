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
    <v-user-layout>
        <div class="api-keys-page">
            <!-- Header Section -->
            <div class="page-header">
                <q-toolbar class="header-toolbar">
                    <q-icon
                        name="mdi-key-chain"
                        size="32px"
                        color="primary"
                        class="header-icon"
                    />
                    <q-toolbar-title
                        class="text-h4 text-weight-bold text-grey-8"
                    >
                        {{ __("API Keys Management") }}
                    </q-toolbar-title>
                    <q-space />
                    <v-create @created="getPersonalAccessToken()" />
                </q-toolbar>
                <div class="text-subtitle1 text-grey-7 q-mt-sm header-subtitle">
                    {{
                        __(
                            "Manage your API keys for secure application integration"
                        )
                    }}
                </div>
            </div>

            <!-- API Keys Table -->
            <div class="api-keys-container q-pa-md">
                <q-card flat bordered class="api-keys-card">
                    <q-table
                        flat
                        bordered
                        :rows="tokens"
                        :columns="columns"
                        row-key="name"
                        hide-bottom
                        :rows-per-page-options="[search.per_page]"
                        :loading="loading"
                        class="api-keys-table"
                    >
                        <!-- Loading State -->
                        <template v-slot:loading>
                            <q-inner-loading showing color="primary" />
                        </template>

                        <!-- Name Column -->
                        <template v-slot:body-cell-name="props">
                            <q-td class="name-cell">
                                <div
                                    class="key-name text-weight-bold text-primary"
                                >
                                    <q-icon
                                        name="mdi-key"
                                        size="18px"
                                        class="q-mr-sm"
                                    />
                                    {{ props.row.name }}
                                </div>
                                <div
                                    class="key-id text-caption text-grey-6"
                                    v-if="props.row.id"
                                >
                                    {{ __("ID:") }} {{ props.row.id }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Created Date Column -->
                        <template v-slot:body-cell-created="props">
                            <q-td class="date-cell">
                                <div class="date-label">
                                    {{ __("Created") }}
                                </div>
                                <div class="date-value text-weight-medium">
                                    <q-icon
                                        name="mdi-calendar-plus"
                                        size="16px"
                                        class="q-mr-xs"
                                    />
                                    {{ formatDate(props.row.created) }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Expires Date Column -->
                        <template v-slot:body-cell-expires="props">
                            <q-td class="date-cell">
                                <div class="date-label">
                                    {{ __("Expires") }}
                                </div>
                                <div
                                    class="date-value text-weight-medium"
                                    :class="
                                        getExpirationClass(props.row.expires)
                                    "
                                >
                                    <q-icon
                                        :name="
                                            getExpirationIcon(props.row.expires)
                                        "
                                        size="16px"
                                        class="q-mr-xs"
                                    />
                                    {{
                                        formatDate(props.row.expires) ||
                                        __("Never")
                                    }}
                                </div>
                                <div
                                    v-if="isExpiringSoon(props.row.expires)"
                                    class="expiration-warning text-caption text-warning"
                                >
                                    {{ __("Expiring soon") }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Actions Column -->
                        <template v-slot:body-cell-actions="props">
                            <q-td class="actions-cell">
                                <div class="action-buttons">
                                    <v-delete
                                        @deleted="getPersonalAccessToken"
                                        :item="props.row"
                                        class="delete-btn"
                                    />
                                    <q-btn
                                        v-if="props.row.token"
                                        color="primary"
                                        size="sm"
                                        outline
                                        icon="mdi-content-copy"
                                        @click="
                                            copyToClipboard(props.row.token)
                                        "
                                        class="copy-btn"
                                    >
                                        <q-tooltip>{{
                                            __("Copy API Key")
                                        }}</q-tooltip>
                                    </q-btn>
                                </div>
                            </q-td>
                        </template>

                        <!-- Empty State -->
                        <template v-slot:no-data>
                            <div class="empty-state text-center q-pa-xl">
                                <q-icon
                                    name="mdi-key-remove"
                                    size="64px"
                                    color="grey-4"
                                    class="empty-icon"
                                />
                                <div
                                    class="empty-title text-h6 text-grey-7 q-mt-md"
                                >
                                    {{ __("No API Keys Found") }}
                                </div>
                                <div class="empty-subtitle text-grey-5">
                                    {{
                                        __(
                                            "Create your first API key to get started"
                                        )
                                    }}
                                </div>
                            </div>
                        </template>
                    </q-table>
                </q-card>
            </div>

            <!-- Pagination -->
            <div class="pagination-section row justify-center q-mt-lg q-mb-xl">
                <q-pagination
                    v-model="search.page"
                    color="primary"
                    :max="pages.total_pages"
                    :max-pages="6"
                    size="md"
                    direction-links
                    boundary-numbers
                    unelevated
                    class="custom-pagination"
                />
            </div>
        </div>
    </v-user-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VDelete,
    },

    data() {
        return {
            tokens: [],
            loading: false,
            showDetailsDialog: false,
            selectedToken: null,
            columns: [
                {
                    name: "name",
                    label: this.__("Key Name"),
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "created",
                    label: this.__("Created Date"),
                    field: "created",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "expires",
                    label: this.__("Expiration Date"),
                    field: "expires",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: this.__("Actions"),
                    field: "actions",
                    align: "right",
                },
            ],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    created() {
        this.getPersonalAccessToken();
    },

    methods: {
        getPersonalAccessToken() {
            this.loading = true;
            this.$server
                .get(this.$page.props.route)
                .then((res) => {
                    this.tokens = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.search.total_pages =
                        res.data.meta.pagination.total_pages;
                })
                .catch((e) => {
                    if (e?.response?.data?.message) {
                        this.$q.notify({
                            type: "negative",
                            message: e.response.data.message,
                            timeout: 3000,
                        });
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        formatDate(dateString) {
            if (!dateString) return null;
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        },

        getExpirationClass(expirationDate) {
            if (!expirationDate) return "text-positive";

            const expDate = new Date(expirationDate);
            const now = new Date();
            const sevenDaysFromNow = new Date(
                now.getTime() + 7 * 24 * 60 * 60 * 1000
            );

            if (expDate < now) return "text-negative";
            if (expDate < sevenDaysFromNow) return "text-warning";
            return "text-positive";
        },

        getExpirationIcon(expirationDate) {
            if (!expirationDate) return "mdi-infinity";

            const expDate = new Date(expirationDate);
            const now = new Date();

            if (expDate < now) return "mdi-alert-circle";
            return "mdi-calendar-clock";
        },

        isExpiringSoon(expirationDate) {
            if (!expirationDate) return false;

            const expDate = new Date(expirationDate);
            const now = new Date();
            const sevenDaysFromNow = new Date(
                now.getTime() + 7 * 24 * 60 * 60 * 1000
            );

            return expDate > now && expDate < sevenDaysFromNow;
        },

        copyToClipboard(text) {
            navigator.clipboard
                .writeText(text)
                .then(() => {
                    this.$q.notify({
                        message: "API key copied to clipboard",
                        color: "positive",
                        icon: "mdi-check",
                        position: "top",
                        timeout: 2000,
                    });
                })
                .catch(() => {
                    this.$q.notify({
                        message: "Failed to copy to clipboard",
                        color: "negative",
                        icon: "error",
                        position: "top",
                    });
                });
        },

        showTokenDetails(token) {
            this.selectedToken = token;
            this.showDetailsDialog = true;
        },
    },
};
</script>

<style lang="scss" scoped>
.api-keys-page {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

.page-header {
    background: white;
    padding: 24px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);

    .header-toolbar {
        padding: 0;

        .header-icon {
            background: rgba(0, 0, 0, 0.05);
            padding: 12px;
            border-radius: 50%;
        }
    }

    .header-subtitle {
        max-width: 800px;
    }
}

.api-keys-container {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

.api-keys-card {
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.api-keys-table {
    :deep(.q-table__top) {
        padding: 20px 24px;
        background: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    :deep(.q-table thead tr) {
        background: rgba(0, 0, 0, 0.02);

        th {
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
            padding: 16px 12px;

            &.sortable:hover {
                color: var(--q-primary);
            }
        }
    }

    :deep(.q-table tbody td) {
        padding: 16px 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    }

    :deep(.q-table tbody tr:hover) {
        background: rgba(0, 123, 255, 0.03) !important;
    }
}

// Custom cell styles
.name-cell {
    .key-name {
        font-size: 1rem;
        display: flex;
        align-items: center;
        margin-bottom: 4px;
    }

    .key-id {
        font-size: 0.75rem;
    }
}

.date-cell {
    .date-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 2px;
    }

    .date-value {
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .expiration-warning {
        margin-top: 4px;
        display: flex;
        align-items: center;
    }
}

.actions-cell {
    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;

        .delete-btn,
        .copy-btn,
        .view-btn {
            min-width: auto;
            padding: 6px 8px;
        }
    }
}

.empty-state {
    .empty-icon {
        opacity: 0.5;
    }

    .empty-title {
        font-weight: 500;
    }

    .empty-subtitle {
        font-size: 0.9rem;
    }
}

.pagination-section {
    .custom-pagination {
        :deep(.q-btn) {
            border-radius: 8px;
            margin: 0 4px;

            &.q-btn--active {
                background: var(--q-primary);
                color: white;
            }
        }
    }
}

// Token Details Dialog
.token-details-dialog {
    border-radius: 16px;

    .dialog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        padding: 20px 24px;
    }

    .dialog-content {
        padding: 24px;

        .token-details {
            .detail-item {
                .detail-label {
                    margin-bottom: 4px;
                    font-weight: 500;
                }

                .detail-value {
                    font-size: 1rem;
                }
            }

            .token-value-container {
                display: flex;
                align-items: center;
                gap: 8px;
                background: #f8f9fa;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid #e9ecef;

                .token-value {
                    flex: 1;
                    font-family: "Monaco", "Menlo", "Ubuntu Mono", monospace;
                    font-size: 0.85rem;
                    word-break: break-all;
                    color: #495057;
                }

                .copy-token-btn {
                    flex-shrink: 0;
                }
            }
        }
    }

    .dialog-actions {
        padding: 16px 24px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .page-header {
        padding: 20px;

        .text-h4 {
            font-size: 1.75rem;
        }
    }

    .api-keys-container {
        padding: 16px;
    }
}

@media (max-width: 767px) {
    .page-header {
        padding: 16px;

        .text-h4 {
            font-size: 1.5rem;
        }

        .header-toolbar {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
    }

    .api-keys-container {
        padding: 12px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 8px;
    }

    .token-details-dialog {
        width: 95vw !important;

        .token-value-container {
            flex-direction: column;
            align-items: stretch;

            .copy-token-btn {
                align-self: flex-end;
            }
        }
    }
}
</style>
