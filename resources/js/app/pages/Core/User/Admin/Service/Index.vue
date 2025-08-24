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
        <div class="bg-white q-pa-md shadow-2 rounded-borders">
            <div class="row items-center justify-between q-mb-md">
                <div>
                    <div class="text-h4 text-primary text-weight-bold">
                        Services Management
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        Manage and organize your application services
                    </div>
                </div>

                <div class="row items-center q-gutter-sm">
                    <!-- Group Filter -->
                    <q-select
                        filled
                        dense
                        clearable
                        label="Filter by Group"
                        v-model="group"
                        use-input
                        input-debounce="300"
                        :options="groups"
                        option-label="name"
                        option-value="slug"
                        @update:model-value="filterByGroup"
                        style="min-width: 200px"
                        class="filter-select"
                    >
                        <template v-slot:prepend>
                            <q-icon name="mdi-filter" />
                        </template>
                    </q-select>

                    <!-- Create Button -->
                    <v-create @created="getServices" />

                    <!-- View Toggle -->
                    <q-btn-toggle
                        v-model="viewMode"
                        dense
                        toggle-color="primary"
                        :options="[
                            {
                                value: 'list',
                                icon: 'mdi-format-list-bulleted',
                                label: 'List',
                            },
                            {
                                value: 'grid',
                                icon: 'mdi-view-grid-outline',
                                label: 'Grid',
                            },
                        ]"
                        unelevated
                        class="view-toggle"
                    />
                </div>
            </div>

            <!-- Filter Component -->
            <v-filter :params="params" @change="searching" class="q-mb-sm" />
        </div>

        <!-- Stats Overview -->
        <div
            class="row q-col-gutter-md q-mb-md q-mt-sm"
            v-if="services.length > 0"
        >
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-blue-1 text-blue-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ services.length }} Service{{
                                services.length !== 1 ? "s" : ""
                            }}
                        </div>
                        <q-icon name="mdi-cog" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-green-1 text-green-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ systemServicesCount }} System Service{{
                                systemServicesCount !== 1 ? "s" : ""
                            }}
                        </div>
                        <q-icon name="mdi-shield-cog" size="md" />
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && services.length > 0"
            class="row q-col-gutter-md q-pa-sm"
        >
            <div
                v-for="service in services"
                :key="service.id"
                class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
            >
                <q-card bordered class="service-card shadow-3">
                    <q-card-section class="bg-primary text-white">
                        <div class="row justify-between items-start">
                            <div>
                                <div
                                    class="text-h6 text-weight-bold text-truncate"
                                >
                                    {{ service.name }}
                                </div>
                                <div class="text-caption opacity-80">
                                    {{ service.group.name }}
                                </div>
                            </div>
                            <v-detail :service="service" />
                        </div>
                    </q-card-section>

                    <q-card-section class="q-pt-md">
                        <div class="text-body2 ellipsis-3-lines q-mb-sm">
                            {{
                                service.description || "No description provided"
                            }}
                        </div>

                        <div class="row q-gutter-sm q-mb-sm">
                            <q-badge
                                :color="service.system ? 'green' : 'orange'"
                                :icon="
                                    service.system
                                        ? 'mdi-shield-check'
                                        : 'mdi-cog'
                                "
                            >
                                {{ service.system ? "System" : "Custom" }}
                            </q-badge>

                            <q-badge color="blue" icon="mdi-eye">
                                {{ service.visibility }}
                            </q-badge>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right" class="q-pa-sm">
                        <v-update :item="service" @updated="getServices" />
                        <v-delete
                            v-if="!service.system"
                            :item="service"
                            @deleted="getServices"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && services.length === 0"
            class="text-center q-pa-xl"
        >
            <q-icon name="mdi-cog-off" size="xl" color="grey-4" />
            <div class="text-h6 text-grey-6 q-mt-md">No services found</div>
            <div class="text-grey-5" v-if="group">
                Try changing your group filter or
            </div>
            <div class="text-grey-5">
                create your first service to get started
            </div>
        </div>

        <!-- List View -->
        <q-table
            v-else
            :rows="services"
            :columns="columns"
            row-key="id"
            flat
            bordered
            :loading="loading"
            :pagination="pagination"
            hide-pagination
            class="shadow-1 rounded-borders"
        >
            <template v-slot:body="props">
                <q-tr :props="props" class="q-hoverable">
                    <q-td key="name" :props="props">
                        <div class="text-weight-bold text-primary">
                            {{ props.row.name }}
                        </div>
                        <div class="text-caption text-grey-7">
                            {{ props.row.group.name }}
                        </div>
                    </q-td>

                    <q-td key="description" :props="props">
                        <div class="ellipsis-2-lines">
                            {{ props.row.description || "—" }}
                        </div>
                    </q-td>

                    <q-td key="system" :props="props">
                        <q-badge
                            :color="props.row.system ? 'green' : 'orange'"
                            :icon="
                                props.row.system
                                    ? 'mdi-shield-check'
                                    : 'mdi-cog'
                            "
                        >
                            {{ props.row.system ? "Yes" : "No" }}
                        </q-badge>
                    </q-td>

                    <q-td key="visibility" :props="props">
                        <q-badge color="blue" icon="mdi-eye">
                            {{ props.row.visibility }}
                        </q-badge>
                    </q-td>

                    <q-td key="actions" :props="props" auto-width>
                        <div class="row q-gutter-xs justify-end">
                            <v-detail :service="props.row" />
                            <v-update
                                :item="props.row"
                                @updated="getServices"
                            />
                            <v-delete
                                v-if="!props.row.system"
                                :item="props.row"
                                @deleted="getServices"
                            />
                        </div>
                    </q-td>
                </q-tr>
            </template>

            <template v-slot:no-data>
                <div class="full-width row flex-center text-grey-6 q-pa-xl">
                    <q-icon name="mdi-cog-off" size="xl" />
                    <div class="q-ml-sm">No services available</div>
                </div>
            </template>
        </q-table>

        <!-- Pagination -->
        <div class="row justify-center q-my-lg" v-if="pages.total_pages > 1">
            <q-pagination
                v-model="search.page"
                color="primary"
                :max="pages.total_pages"
                :max-pages="6"
                boundary-numbers
                direction-links
                ellipses
                class="q-pa-sm bg-white rounded-borders shadow-1"
            />

            <q-select
                v-model="search.per_page"
                :options="[10, 15, 25, 50]"
                label="Items per page"
                dense
                outlined
                class="q-ml-md"
                style="min-width: 140px"
                @update:model-value="getServices"
            >
                <template v-slot:prepend>
                    <q-icon name="mdi-format-list-numbered" />
                </template>
            </q-select>
        </div>
    </v-admin-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VUpdate from "./Update.vue";
import VDelete from "./Delete.vue";
import VDetail from "./Scope.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VDetail,
    },

    data() {
        return {
            viewMode: "list",
            services: [],
            loading: false,
            params: [
                { key: "Name", value: "name" },
                { key: "Group", value: "group" },
                { key: "Visibility", value: "visibility" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            groups: [],
            group: null,
            pagination: {
                sortBy: "name",
                descending: false,
                page: 1,
                rowsPerPage: 15,
            },
            columns: [
                {
                    name: "name",
                    label: "Service",
                    field: "name",
                    sortable: true,
                    align: "left",
                },
                {
                    name: "description",
                    label: "Description",
                    field: "description",
                    sortable: false,
                    align: "left",
                },
                {
                    name: "system",
                    label: "System",
                    field: "system",
                    sortable: true,
                    align: "center",
                },
                {
                    name: "visibility",
                    label: "Visibility",
                    field: "visibility",
                    sortable: true,
                    align: "center",
                },
                {
                    name: "actions",
                    label: "Actions",
                    field: "actions",
                    align: "right",
                    sortable: false,
                },
            ],
        };
    },

    computed: {
        systemServicesCount() {
            return this.services.filter((service) => service.system).length;
        },
    },

    created() {
        this.getServices();
        this.getGroups();
    },

    watch: {
        "search.page"(value) {
            this.getServices();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getServices();
            }
        },
    },

    methods: {
        filterByGroup(value) {
            this.search.group = value?.name || null;
            this.getServices();
        },

        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getServices(event);
        },

        async getGroups() {
            try {
                const res = await this.$server.get(
                    this.$page.props.route["groups"]
                );

                if (res.status == 200) {
                    this.groups = res.data.data;
                }
            } catch (err) {
                console.error("Error fetching groups:", err);
            }
        },

        getServices(param = null) {
            this.loading = true;
            var params = {
                ...this.search,
                ...param,
            };

            this.$server
                .get(this.$page.props.route.services, {
                    params: params,
                })
                .then((res) => {
                    this.services = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {
                    console.error("Error fetching services:", e);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style lang="css" scoped>
.ellipsis-2-lines {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ellipsis-3-lines {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.service-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 12px;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -10px rgba(0, 0, 0, 0.15) !important;
}

.view-toggle {
    border-radius: 8px;
    overflow: hidden;
}

.filter-select {
    border-radius: 8px;
}

.opacity-80 {
    opacity: 0.8;
}
</style>
