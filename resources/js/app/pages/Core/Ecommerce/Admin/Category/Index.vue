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
    <v-admin-ecommerce-layout>
        <!-- Header Section -->
        <div class="header-section q-pa-lg bg-primary text-white">
            <div class="row items-center justify-between">
                <div class="col">
                    <div class="text-h4 text-weight-bold">
                        {{ __("Categories Management") }}
                    </div>
                    <div class="text-subtitle1 opacity-70">
                        {{ __("Manage product categories and organization") }}
                    </div>
                </div>
                <div class="col-auto">
                    <v-create @created="getCategories" />
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="q-pa-lg">
            <!-- Search and Filters (if needed in the future) -->
            <div class="row q-mb-md">
                <div class="col-12">
                    <q-input
                        outlined
                        v-model="searchTerm"
                        :placeholder="__('Search categories...')"
                        clearable
                        class="search-input"
                        @update:model-value="handleSearch"
                    >
                        <template v-slot:prepend>
                            <q-icon name="mdi-magnify" />
                        </template>
                    </q-input>
                </div>
            </div>

            <!-- Categories Table -->
            <q-card class="categories-card shadow-3 rounded-borders">
                <q-table
                    :rows="groups"
                    :columns="columns"
                    row-key="id"
                    flat
                    bordered
                    :pagination="{
                        page: search.page,
                        rowsPerPage: search.per_page,
                    }"
                    hide-pagination
                    class="categories-table"
                    :loading="loading"
                >
                    <!-- Header Slot -->
                    <template v-slot:header="props">
                        <q-tr :props="props">
                            <q-th
                                v-for="col in props.cols"
                                :key="col.name"
                                :props="props"
                                class="text-weight-bold table-header"
                            >
                                {{ col.label }}
                            </q-th>
                        </q-tr>
                    </template>

                    <!-- Body Slots -->
                    <template v-slot:body-cell-name="props">
                        <q-td :props="props" class="name-cell">
                            <div class="row items-center">
                                <q-icon
                                    v-if="props.row.icon?.icon"
                                    :name="props.row.icon.icon"
                                    size="20px"
                                    class="q-mr-sm text-primary"
                                />
                                <span class="text-weight-medium">{{
                                    props.row.name
                                }}</span>
                            </div>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-slug="props">
                        <q-td :props="props" class="slug-cell">
                            <q-badge
                                color="blue-grey-1"
                                text-color="blue-grey-9"
                                class="slug-badge"
                            >
                                {{ props.row.slug }}
                            </q-badge>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-icon="props">
                        <q-td :props="props" class="icon-cell text-center">
                            <q-icon
                                v-if="props.row.icon?.icon"
                                :name="props.row.icon.icon"
                                size="24px"
                                class="text-primary"
                            />
                            <q-icon
                                v-else
                                name="mdi-help-circle"
                                size="24px"
                                class="text-grey-4"
                            />
                        </q-td>
                    </template>

                    <template v-slot:body-cell-published="props">
                        <q-td :props="props" class="status-cell text-center">
                            <q-badge
                                :color="
                                    props.row.published ? 'positive' : 'grey-5'
                                "
                                :text-color="
                                    props.row.published ? 'white' : 'dark'
                                "
                                class="status-badge"
                            >
                                <q-icon
                                    :name="
                                        props.row.published
                                            ? 'mdi-check-circle'
                                            : 'mdi-close-circle'
                                    "
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{
                                    props.row.published
                                        ? __("Published")
                                        : __("Hidden")
                                }}
                            </q-badge>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-featured="props">
                        <q-td :props="props" class="status-cell text-center">
                            <q-badge
                                :color="
                                    props.row.featured ? 'accent' : 'grey-5'
                                "
                                :text-color="
                                    props.row.featured ? 'white' : 'dark'
                                "
                                class="status-badge"
                            >
                                <q-icon
                                    :name="
                                        props.row.featured
                                            ? 'mdi-star'
                                            : 'mdi-star-outline'
                                    "
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{
                                    props.row.featured
                                        ? __("Featured")
                                        : __("Standard")
                                }}
                            </q-badge>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-actions="props">
                        <q-td :props="props" class="actions-cell">
                            <div class="row q-gutter-xs justify-end">
                                <v-create
                                    :item="props.row"
                                    @created="getCategories"
                                    :title="__('Update')"
                                    class="action-btn"
                                />
                                <v-delete
                                    :item="props.row"
                                    @deleted="getCategories"
                                    class="action-btn"
                                />
                            </div>
                        </q-td>
                    </template>

                    <!-- Empty State -->
                    <template v-slot:no-data>
                        <div
                            class="full-width row flex-center text-grey-6 q-pa-lg"
                        >
                            <q-icon
                                name="mdi-folder-open"
                                size="48px"
                                class="q-mb-sm"
                            />
                            <div class="text-h6">
                                {{ __("No categories found") }}
                            </div>
                            <div class="text-caption">
                                {{
                                    __(
                                        "Create your first category to get started"
                                    )
                                }}
                            </div>
                        </div>
                    </template>

                    <!-- Loading State -->
                    <template v-slot:loading>
                        <q-inner-loading showing color="primary" />
                    </template>
                </q-table>
            </q-card>

            <!-- Pagination -->
            <div class="row justify-center q-mt-lg">
                <q-pagination
                    v-model="search.page"
                    :max="pages.total_pages"
                    color="primary"
                    :max-pages="6"
                    size="md"
                    boundary-numbers
                    direction-links
                    ellipses
                    class="pagination-control"
                />
            </div>

            <!-- Results Count -->
            <div class="row justify-center q-mt-md">
                <div class="text-caption text-grey-6">
                    {{ __("Showing") }} {{ groups.length }} {{ __("of") }}
                    {{ pages.total || 0 }} {{ __("categories") }}
                </div>
            </div>
        </div>
    </v-admin-ecommerce-layout>
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
            groups: [],
            loading: false,
            searchTerm: "",
            pages: {
                total_pages: 0,
                total: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                {
                    name: "name",
                    label: "Category Name",
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "slug",
                    label: "Slug",
                    field: "slug",
                    align: "center",
                    sortable: true,
                },
                {
                    name: "icon",
                    label: "Icon",
                    field: (row) => row.icon?.icon,
                    align: "center",
                },
                {
                    name: "published",
                    label: "Status",
                    field: (row) => (row.published ? "Yes" : "No"),
                    align: "center",
                    sortable: true,
                },
                {
                    name: "featured",
                    label: "Featured",
                    field: (row) => (row.featured ? "Yes" : "No"),
                    align: "center",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "center",
                    sortable: false,
                },
            ],
        };
    },

    created() {
        this.getCategories();
    },

    watch: {
        "search.page"(value) {
            this.getCategories();
        },
        "search.per_page"(value) {
            this.getCategories();
        },
    },

    methods: {
        handleSearch() {
            // Debounce search implementation
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.getCategories({ search: this.searchTerm });
            }, 300);
        },

        getCategories(param = null) {
            this.loading = true;
            const params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.groups = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.page = meta.pagination.current_page;
                })
                .catch((err) => {
                    console.error("Error loading categories:", err);
                    this.$q.notify({
                        type: "negative",
                        message: "Failed to load categories",
                        icon: "mdi-alert-circle",
                        position: "top-right",
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped>
/* CSS Variables for Theme Consistency */
:root {
    --color-primary: #1976d2;
    --color-secondary: #26a69a;
    --color-accent: #ff6b35;
    --color-positive: #21ba45;
    --color-negative: #c10015;
    --color-warning: #f2c037;
    --border-radius: 12px;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition-speed: 0.3s;
}

.header-section {
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    margin-bottom: 24px;
}

.categories-card {
    border-radius: var(--border-radius);
    overflow: hidden;
}

.categories-table {
    border: none;
}

.table-header {
    background-color: #f8f9fa;
    font-size: 0.9rem;
    padding: 16px 12px;
}

.name-cell {
    font-weight: 500;
}

.slug-badge {
    border-radius: 16px;
    padding: 4px 12px;
    font-family: monospace;
    font-size: 0.8rem;
}

.status-badge {
    border-radius: 16px;
    padding: 6px 12px;
    font-weight: 500;
}

.actions-cell {
    padding: 8px 16px;
}

.action-btn {
    transition: transform var(--transition-speed) ease;
}

.action-btn:hover {
    transform: scale(1.1);
}

.search-input {
    border-radius: 8px;
}

.search-input :deep(.q-field__control) {
    border-radius: 8px;
}

.pagination-control {
    background: white;
    padding: 12px 20px;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .header-section {
        border-radius: 0;
        margin-bottom: 16px;
    }

    .categories-card {
        margin: 0 -8px;
    }

    .table-header {
        font-size: 0.8rem;
        padding: 12px 8px;
    }
}

@media (max-width: 600px) {
    .header-section .text-h4 {
        font-size: 1.5rem;
    }

    .header-section .text-subtitle1 {
        font-size: 0.9rem;
    }

    .actions-cell {
        padding: 4px 8px;
    }

    .action-btn {
        transform: scale(0.9);
    }

    .action-btn:hover {
        transform: scale(1);
    }
}
</style>
