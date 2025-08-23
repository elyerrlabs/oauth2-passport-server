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
        <div class="clients-page">
            <!-- Header Section -->
            <div class="page-header">
                <q-toolbar class="header-toolbar">
                    <q-icon
                        name="mdi-application-cog"
                        size="32px"
                        color="primary"
                        class="header-icon"
                    />
                    <q-toolbar-title
                        class="text-h4 text-weight-bold text-grey-8"
                    >
                        OAuth Clients
                    </q-toolbar-title>
                    <q-space />
                    <v-create @created="getClients()" class="create-btn" />
                </q-toolbar>
                <div class="text-subtitle1 text-grey-7 q-mt-sm header-subtitle">
                    Manage your OAuth 2.0 clients and applications
                </div>
            </div>

            <!-- Clients Table -->
            <div class="clients-container q-pa-md">
                <q-card flat bordered class="clients-card">
                    <q-table
                        flat
                        bordered
                        :rows="clients"
                        :columns="columns"
                        row-key="id"
                        hide-bottom
                        :rows-per-page-options="[search.per_page]"
                        :loading="loading"
                        class="clients-table"
                    >
                        <!-- Loading State -->
                        <template v-slot:loading>
                            <q-inner-loading showing color="primary" />
                        </template>

                        <!-- Name Column -->
                        <template v-slot:body-cell-name="props">
                            <q-td class="name-cell">
                                <div
                                    class="client-name text-weight-bold text-primary"
                                >
                                    <q-icon
                                        name="mdi-application"
                                        size="18px"
                                        class="q-mr-sm"
                                    />
                                    {{ props.row.name }}
                                </div>
                                <div
                                    class="client-id text-caption text-grey-6"
                                    v-if="props.row.id"
                                >
                                    ID: {{ props.row.id }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Created Date Column -->
                        <template v-slot:body-cell-created_at="props">
                            <q-td class="date-cell">
                                <div class="date-label">Created</div>
                                <div class="date-value text-weight-medium">
                                    <q-icon
                                        name="mdi-calendar"
                                        size="16px"
                                        class="q-mr-xs"
                                    />
                                    {{ formatDate(props.row.created_at) }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Confidential Column -->
                        <template v-slot:body-cell-confidential="props">
                            <q-td class="confidential-cell">
                                <q-badge
                                    :color="
                                        props.row.confidential ? 'blue' : 'grey'
                                    "
                                    :text-color="
                                        props.row.confidential
                                            ? 'white'
                                            : 'dark'
                                    "
                                    class="confidential-badge"
                                >
                                    <q-icon
                                        :name="
                                            props.row.confidential
                                                ? 'mdi-lock'
                                                : 'mdi-lock-open'
                                        "
                                        size="14px"
                                        class="q-mr-xs"
                                    />
                                    {{ props.row.confidential ? "Yes" : "No" }}
                                </q-badge>
                                <div class="text-caption text-grey-6 q-mt-xs">
                                    {{
                                        props.row.confidential
                                            ? "Confidential client"
                                            : "Public client"
                                    }}
                                </div>
                            </q-td>
                        </template>

                        <!-- Grant Types Column -->
                        <template v-slot:body-cell-grant_types="props">
                            <q-td class="grants-cell">
                                <div class="grants-container">
                                    <q-chip
                                        v-for="(grant, index) in getGrantTypes(
                                            props.row.grant_types
                                        )"
                                        :key="index"
                                        color="primary"
                                        text-color="white"
                                        size="sm"
                                        class="grant-chip"
                                    >
                                        <q-icon
                                            :name="getGrantIcon(grant)"
                                            size="14px"
                                            class="q-mr-xs"
                                        />
                                        {{ formatGrantType(grant) }}
                                    </q-chip>
                                </div>
                            </q-td>
                        </template>

                        <!-- Actions Column -->
                        <template v-slot:body-cell-actions="props">
                            <q-td class="actions-cell">
                                <div class="action-buttons">
                                    <v-update
                                        :item="props.row"
                                        @updated="getClients"
                                        class="action-btn"
                                    />
                                    <v-delete
                                        :item="props.row"
                                        @deleted="getClients"
                                        class="action-btn"
                                    />
                                </div>
                            </q-td>
                        </template>

                        <!-- Empty State -->
                        <template v-slot:no-data>
                            <div class="empty-state text-center q-pa-xl">
                                <q-icon
                                    name="mdi-application-import"
                                    size="64px"
                                    color="grey-4"
                                    class="empty-icon"
                                />
                                <div
                                    class="empty-title text-h6 text-grey-7 q-mt-md"
                                >
                                    No OAuth Clients
                                </div>
                                <div class="empty-subtitle text-grey-5">
                                    Create your first OAuth client to get
                                    started
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
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
    },

    data() {
        return {
            clients: [],
            loading: false,
            pages: {
                total_pages: 1,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                {
                    name: "name",
                    required: true,
                    label: "Client Name",
                    align: "left",
                    field: (row) => row.name,
                    sortable: true,
                },
                {
                    name: "created_at",
                    label: "Created Date",
                    align: "left",
                    field: (row) => row.created_at,
                    sortable: true,
                },
                {
                    name: "confidential",
                    label: "Client Type",
                    align: "center",
                    field: (row) => row.confidential,
                    sortable: true,
                },
                {
                    name: "grant_types",
                    label: "Grant Types",
                    align: "left",
                    field: (row) => row.grant_types,
                    sortable: true,
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "right",
                    field: (row) => row.id,
                },
            ],
        };
    },

    watch: {
        "search.page"(value) {
            this.getClients();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getClients();
            }
        },
    },

    created() {
        this.getClients();
    },

    methods: {
        getClients(param = null) {
            this.loading = true;
            const params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.clients = res.data.data;
                    const meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {
                    console.error("Failed to load clients:", e);
                    this.$q.notify({
                        message: "Failed to load OAuth clients",
                        color: "negative",
                        icon: "error",
                        position: "top",
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        formatDate(dateString) {
            if (!dateString) return "N/A";
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        getGrantTypes(grantTypes) {
            if (!grantTypes) return [];
            if (Array.isArray(grantTypes)) return grantTypes;
            if (typeof grantTypes === "string") return grantTypes.split(",");
            return [];
        },

        formatGrantType(grant) {
            const grantMap = {
                authorization_code: "Authorization Code",
                password: "Password",
                client_credentials: "Client Credentials",
                implicit: "Implicit",
                refresh_token: "Refresh Token",
            };
            return grantMap[grant] || grant;
        },

        getGrantIcon(grant) {
            const grantIcons = {
                authorization_code: "mdi-shield-account",
                password: "mdi-form-textbox-password",
                client_credentials: "mdi-account-key",
                implicit: "mdi-flash",
                refresh_token: "mdi-autorenew",
            };
            return grantIcons[grant] || "mdi-cog";
        },
    },
};
</script>

<style lang="scss" scoped>
.clients-page {
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

    .create-btn {
        ::v-deep .q-btn {
            border-radius: 8px;
            font-weight: 600;
        }
    }
}

.clients-container {
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.clients-card {
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.clients-table {
    ::v-deep .q-table__top {
        padding: 20px 24px;
        background: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    ::v-deep .q-table thead tr {
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

    ::v-deep .q-table tbody td {
        padding: 16px 12px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
    }

    ::v-deep .q-table tbody tr:hover {
        background: rgba(0, 123, 255, 0.03) !important;
    }
}

// Custom cell styles
.name-cell {
    .client-name {
        font-size: 1rem;
        display: flex;
        align-items: center;
        margin-bottom: 4px;
    }

    .client-id {
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
}

.confidential-cell {
    .confidential-badge {
        padding: 6px 10px;
        border-radius: 16px;
        font-weight: 500;
    }
}

.grants-cell {
    .grants-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;

        .grant-chip {
            font-weight: 500;

            ::v-deep .q-chip__icon {
                font-size: 16px;
            }
        }
    }
}

.actions-cell {
    .action-buttons {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;

        .action-btn {
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
        ::v-deep .q-btn {
            border-radius: 8px;
            margin: 0 4px;

            &.q-btn--active {
                background: var(--q-primary);
                color: white;
            }
        }
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

    .clients-container {
        padding: 16px;
    }

    .grants-container {
        flex-direction: column;
        align-items: flex-start;
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

    .clients-container {
        padding: 12px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 8px;
    }

    .clients-table {
        ::v-deep .q-table thead {
            display: none;
        }

        ::v-deep .q-table tbody tr {
            display: block;
            margin-bottom: 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 16px;
        }

        ::v-deep .q-table tbody td {
            display: block;
            text-align: left !important;
            border: none;
            padding: 8px 0;

            &:before {
                content: attr(data-label);
                font-weight: 600;
                color: #374151;
                display: block;
                margin-bottom: 4px;
                font-size: 0.8rem;
            }
        }
    }
}
</style>
