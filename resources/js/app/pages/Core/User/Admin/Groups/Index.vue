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
    <v-admin-layout>
        <!-- Header Section -->
        <div class="page-header">
            <q-toolbar class="header-toolbar">
                <q-icon
                    name="mdi-account-group"
                    size="32px"
                    color="primary"
                    class="header-icon"
                />
                <q-toolbar-title class="text-h4 text-weight-bold text-grey-8">
                    {{ __("Groups Management") }}
                </q-toolbar-title>
                <q-space />
                <div class="header-actions">
                    <v-filter
                        :params="params"
                        @change="searching"
                        class="q-mr-md"
                    />
                    <v-create @created="getGroups" class="q-mr-md" />
                </div>
            </q-toolbar>
            <div class="text-subtitle1 text-grey-7 q-mt-sm">
                {{ __("Manage user groups and permissions") }}
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-overview row q-col-gutter-md q-mb-xl">
            <div class="col-12 col-sm-6 col-md-3">
                <q-card flat bordered class="stats-card">
                    <q-card-section>
                        <div class="row items-center">
                            <q-avatar
                                size="48px"
                                color="blue-1"
                                text-color="blue-8"
                                icon="mdi-account-group"
                            />
                            <div class="q-ml-md">
                                <div
                                    class="text-h5 text-weight-bold text-blue-8"
                                >
                                    {{ groups.length }}
                                </div>
                                <div class="text-caption text-grey-7">
                                    {{ __("Total Groups") }}
                                </div>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <q-card flat bordered class="stats-card">
                    <q-card-section>
                        <div class="row items-center">
                            <q-avatar
                                size="48px"
                                color="green-1"
                                text-color="green-8"
                                icon="mdi-shield-account"
                            />
                            <div class="q-ml-md">
                                <div
                                    class="text-h5 text-weight-bold text-green-8"
                                >
                                    {{ systemGroupsCount }}
                                </div>
                                <div class="text-caption text-grey-7">
                                    {{ __("System Groups") }}
                                </div>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <q-card flat bordered class="stats-card">
                    <q-card-section>
                        <div class="row items-center">
                            <q-avatar
                                size="48px"
                                color="orange-1"
                                text-color="orange-8"
                                icon="mdi-account"
                            />
                            <div class="q-ml-md">
                                <div
                                    class="text-h5 text-weight-bold text-orange-8"
                                >
                                    {{ userGroupsCount }}
                                </div>
                                <div class="text-caption text-grey-7">
                                    {{ __("User Groups") }}
                                </div>
                            </div>
                        </div>
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Grid & List Views -->
        <div
            v-if="viewMode === 'grid'"
            class="groups-grid row q-col-gutter-xl q-mb-lg"
        >
            <div
                v-for="group in groups"
                :key="group.id"
                class="col-12 col-sm-6 col-md-4 col-lg-3"
            >
                <q-card flat bordered class="group-card">
                    <q-card-section class="card-header">
                        <div class="group-icon">
                            <q-avatar
                                size="56px"
                                color="primary"
                                text-color="white"
                                icon="mdi-account-group"
                            />
                        </div>
                        <div
                            class="group-title text-h6 text-weight-bold text-primary text-center"
                        >
                            {{ group.name }}
                        </div>
                        <div
                            class="group-slug text-caption text-grey-7 text-center"
                        >
                            @{{ group.slug }}
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-section class="card-content">
                        <div
                            class="group-description text-body2 text-grey-8 q-mb-md"
                        >
                            {{
                                group.description ||
                                __("No description provided")
                            }}
                        </div>

                        <div class="group-meta">
                            <q-badge
                                :color="group.system ? 'blue' : 'grey'"
                                :text-color="group.system ? 'white' : 'dark'"
                                class="group-type-badge"
                            >
                                <q-icon
                                    :name="
                                        group.system
                                            ? 'mdi-shield-check'
                                            : 'mdi-account'
                                    "
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{
                                    group.system
                                        ? __("System Group")
                                        : __("User Group")
                                }}
                            </q-badge>

                            <div class="text-caption text-grey-6 q-mt-xs">
                                <q-icon
                                    name="mdi-calendar"
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{ __("Created") }}
                                {{ formatDate(group.created_at) }}
                            </div>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="center" class="card-actions">
                        <v-update
                            @updated="getGroups"
                            :item="group"
                            class="action-btn"
                        />
                        <v-delete
                            v-if="!group.system"
                            @deleted="getGroups"
                            :item="group"
                            class="action-btn"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <div v-else class="groups-list q-mb-lg">
            <q-card flat bordered>
                <q-table
                    :rows="groups"
                    :columns="columns"
                    row-key="id"
                    flat
                    bordered
                    hide-bottom
                    :rows-per-page-options="[search.per_page]"
                    :loading="loading"
                    class="groups-table"
                >
                    <template v-slot:loading>
                        <q-inner-loading showing color="primary" />
                    </template>

                    <template v-slot:body-cell-description="props">
                        <q-td class="description-cell">
                            <div class="text-body2 line-clamp-2">
                                {{
                                    props.row.description ||
                                    __("No description")
                                }}
                            </div>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-system="props">
                        <q-td class="system-cell">
                            <q-badge
                                :color="props.row.system ? 'blue-1' : 'grey-3'"
                                :text-color="
                                    props.row.system ? 'blue-8' : 'grey-8'
                                "
                                class="system-badge"
                            >
                                <q-icon
                                    :name="
                                        props.row.system
                                            ? 'mdi-shield-check'
                                            : 'mdi-account'
                                    "
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{
                                    props.row.system ? __("System") : __("User")
                                }}
                            </q-badge>
                        </q-td>
                    </template>

                    <template v-slot:no-data>
                        <div class="empty-state text-center q-pa-xl">
                            <q-icon
                                name="mdi-account-group-off"
                                size="64px"
                                color="grey-4"
                                class="empty-icon"
                            />
                            <div
                                class="empty-title text-h6 text-grey-7 q-mt-md"
                            >
                                {{ __("No groups found") }}
                            </div>
                            <div class="empty-subtitle text-grey-5">
                                {{
                                    __("Create your first group to get started")
                                }}
                            </div>
                        </div>
                    </template>
                </q-table>
            </q-card>
        </div>

        <!-- Pagination -->
        <div class="pagination-section row justify-center q-mb-xl">
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
    </v-admin-layout>
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
            groups: [],
            viewMode: "list",
            loading: false,
            params: [{ key: "Name", value: "name" }],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                {
                    name: "name",
                    label: this.__("Group Name"),
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "slug",
                    label: this.__("Slug"),
                    field: "slug",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "description",
                    label: this.__("Description"),
                    field: "description",
                    align: "left",
                    sortable: false,
                },
                {
                    name: "system",
                    label: this.__("Type"),
                    field: (row) => (row.system ? "System" : "User"),
                    align: "center",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: this.__("Actions"),
                    align: "center",
                    sortable: false,
                },
            ],
        };
    },

    computed: {
        systemGroupsCount() {
            return this.groups.filter((group) => group.system).length;
        },
        userGroupsCount() {
            return this.groups.filter((group) => !group.system).length;
        },
    },

    created() {
        this.getGroups();
    },

    watch: {
        "search.page"(value) {
            this.getGroups();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getGroups();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getGroups(event);
        },

        getGroups(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.groups = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {
                    this.$q.notify({
                        message: this.__("Failed to load groups"),
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
    },
};
</script>

<style lang="scss" scoped>
.page-header {
    margin-bottom: 32px;

    .header-toolbar {
        padding: 0;

        .header-icon {
            background: rgba(0, 0, 0, 0.05);
            padding: 12px;
            border-radius: 50%;
            margin-right: 16px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;

            .view-toggle {
                border-radius: 8px;
            }
        }
    }
}

.stats-overview {
    .stats-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;

        &:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .q-card__section {
            padding: 20px;
        }
    }
}

.groups-grid {
    .group-card {
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;

        &:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            text-align: center;
            padding: 24px 20px 16px;

            .group-icon {
                margin-bottom: 16px;
            }

            .group-title {
                margin-bottom: 4px;
            }
        }

        .card-content {
            padding: 20px;

            .group-description {
                line-height: 1.5;
                min-height: 60px;
            }

            .group-meta {
                .group-type-badge {
                    padding: 6px 10px;
                    border-radius: 16px;
                    font-weight: 500;
                }
            }
        }

        .card-actions {
            padding: 16px 20px;

            .action-btn {
                margin: 0 4px;
            }
        }
    }
}

.groups-list {
    .groups-table {
        border-radius: 16px;
        overflow: hidden;

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
            }
        }

        .name-cell {
            .group-name {
                display: flex;
                align-items: center;
            }

            .group-slug {
                font-size: 0.75rem;
            }
        }

        .description-cell {
            max-width: 300px;
        }

        .system-cell {
            .system-badge {
                padding: 6px 10px;
                border-radius: 16px;
                font-weight: 500;
            }
        }

        .actions-cell {
            .action-buttons {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
        }
    }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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

// Responsive adjustments
@media (max-width: 1023px) {
    .page-header {
        .header-toolbar {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }
    }

    .groups-grid {
        .group-card {
            .card-header {
                padding: 20px 16px 12px;
            }

            .card-content {
                padding: 16px;
            }
        }
    }
}

@media (max-width: 599px) {
    .stats-overview {
        .stats-card {
            .q-card__section {
                padding: 16px;
            }
        }
    }

    .groups-table {
        :deep(.q-table thead) {
            display: none;
        }

        :deep(.q-table tbody tr) {
            display: block;
            margin-bottom: 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 16px;
        }

        :deep(.q-table tbody td) {
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
