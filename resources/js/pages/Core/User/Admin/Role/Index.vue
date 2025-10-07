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
                        {{ __("Roles Management") }}
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        {{ __("Manage user roles and permissions") }}
                    </div>
                </div>

                <div class="row items-center q-gutter-sm">
                    <!-- Create Button -->
                    <v-create @created="getRoles" />

                    <!-- View Toggle -->
                    <q-btn-toggle
                        v-model="viewMode"
                        dense
                        toggle-color="primary"
                        :options="[
                            {
                                value: 'list',
                                icon: 'mdi-format-list-bulleted',
                                label: __('List'),
                            },
                            {
                                value: 'grid',
                                icon: 'mdi-view-grid-outline',
                                label: __('Grid'),
                            },
                        ]"
                        unelevated
                        class="view-toggle"
                    />
                </div>
            </div>

            <!-- Filter Component -->
            <v-filter :params="params" @change="searching" class="q-mb-md" />
        </div>

        <!-- Stats Overview -->
        <div class="row q-col-gutter-md q-mb-md q-mt-sm">
            <div class="col-xs-12 col-sm-6 col-md-3" v-if="roles.length > 0">
                <q-card flat class="bg-blue-1 text-blue-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ roles.length }} {{ __("Role")
                            }}{{ roles.length !== 1 ? __("s") : "" }}
                        </div>
                        <q-icon name="mdi-account-group" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div
                class="col-xs-12 col-sm-6 col-md-3"
                v-if="systemRolesCount > 0"
            >
                <q-card flat class="bg-orange-1 text-orange-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ systemRolesCount }} {{ __("System Role")
                            }}{{ systemRolesCount !== 1 ? "s" : "" }}
                        </div>
                        <q-icon name="mdi-shield-account" size="md" />
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && roles.length > 0"
            class="row q-col-gutter-md"
        >
            <div
                v-for="role in roles"
                :key="role.id"
                class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
            >
                <q-card flat bordered class="role-card shadow-3">
                    <q-card-section class="bg-primary text-white">
                        <div class="text-h6 text-weight-bold text-truncate">
                            {{ __(role.name) }}
                        </div>
                        <div class="text-caption opacity-80">
                            {{ role.slug }}
                        </div>
                    </q-card-section>

                    <q-card-section class="q-pt-md">
                        <div class="text-body2 ellipsis-3-lines q-mb-sm">
                            {{
                                role.description ||
                                __("No description provided")
                            }}
                        </div>

                        <q-badge
                            :color="role.system ? 'orange' : 'blue'"
                            :icon="
                                role.system
                                    ? 'mdi-shield-check'
                                    : 'mdi-account-cog'
                            "
                            class="q-mb-sm"
                        >
                            {{
                                role.system
                                    ? __("System Role")
                                    : __("Custom Role")
                            }}
                        </q-badge>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right" class="q-pa-sm">
                        <v-update @updated="getRoles" :item="role" />
                        <v-delete
                            v-if="!role.system"
                            @deleted="getRoles"
                            :item="role"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && roles.length === 0"
            class="text-center q-pa-xl"
        >
            <q-icon name="mdi-account-off-outline" size="xl" color="grey-4" />
            <div class="text-h6 text-grey-6 q-mt-md">
                {{ __("No roles found") }}
            </div>
            <div class="text-grey-5">
                {{ __("Create your first role to get started") }}
            </div>
        </div>

        <!-- List View -->
        <q-table
            v-else
            :rows="roles"
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
                        <div class="text-weight-bold">
                            {{ __(props.row.name) }}
                        </div>
                        <div class="text-caption text-grey-7">
                            {{ __(props.row.slug) }}
                        </div>
                        <q-tooltip>
                            {{ __(props.row.description) }}
                        </q-tooltip>
                    </q-td>
                    <!--
                        <q-td key="description" :props="props">
                            <div class="ellipsis-2-lines">
                                {{ __(props.row.description) || __("—") }}
                            </div>
                        </q-td>
                    -->

                    <q-td key="system" :props="props">
                        <q-badge
                            :color="props.row.system ? 'orange' : 'blue'"
                            :icon="
                                props.row.system
                                    ? 'mdi-shield-check'
                                    : 'mdi-account-cog'
                            "
                        >
                            {{ props.row.system ? __("Yes") : __("No") }}
                        </q-badge>
                    </q-td>

                    <q-td key="actions" :props="props" auto-width>
                        <div class="row q-gutter-xs justify-end">
                            <v-update @updated="getRoles" :item="props.row" />
                            <v-delete
                                v-if="!props.row.system"
                                @deleted="getRoles"
                                :item="props.row"
                            />
                        </div>
                    </q-td>
                </q-tr>
            </template>

            <template v-slot:no-data>
                <div class="full-width row flex-center text-grey-6 q-pa-xl">
                    <q-icon name="mdi-account-off-outline" size="xl" />
                    <div class="q-ml-sm">{{ __("No roles available") }}</div>
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
                :label="__('Items per page')"
                dense
                outlined
                class="q-ml-md"
                style="min-width: 140px"
                @update:model-value="getRoles"
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
            roles: [],
            loading: false,
            viewMode: "list",
            params: [{ key: "Name", value: "name" }],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            pagination: {
                sortBy: "name",
                descending: false,
                page: 1,
                rowsPerPage: 15,
            },
            columns: [
                {
                    name: "name",
                    label: this.__("Role"),
                    field: "name",
                    sortable: true,
                    align: "left",
                },
                /* {
                    name: "description",
                    label: this.__("Description"),
                    field: "description",
                    sortable: false,
                    align: "left",
                },*/
                {
                    name: "system",
                    label: this.__("System Role"),
                    field: "system",
                    sortable: true,
                    align: "center",
                },
                {
                    name: "actions",
                    label: this.__("Actions"),
                    field: "actions",
                    align: "right",
                    sortable: false,
                },
            ],
        };
    },

    computed: {
        systemRolesCount() {
            return this.roles.filter((role) => role.system).length;
        },
    },

    created() {
        this.getRoles();
    },

    watch: {
        "search.page"(value) {
            this.getRoles();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getRoles();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getRoles(event);
        },

        getRoles(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.roles = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {
                    console.error("Error fetching roles:", e);
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: "Copied to clipboard",
                    timeout: 2000,
                    icon: "mdi-check-circle",
                    position: "top",
                });
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: "Failed to copy",
                    timeout: 2000,
                    icon: "mdi-alert-circle",
                    position: "top",
                });
            }
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

.role-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 12px;
}

.role-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -10px rgba(0, 0, 0, 0.15) !important;
}

.view-toggle {
    border-radius: 8px;
    overflow: hidden;
}

.opacity-80 {
    opacity: 0.8;
}
</style>
