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
                        {{ __("User Management") }}
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        {{ __("Manage system users and their permissions") }}
                    </div>
                </div>

                <div class="row items-center q-gutter-sm">
                    <!-- Create Button -->
                    <v-create @created="getUsers" />

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
            <v-filter :params="params" @change="searching" class="q-mb-sm" />
        </div>

        <!-- Stats Overview -->
        <div
            class="row q-col-gutter-md q-mb-md q-mt-sm"
            v-if="users.length > 0"
        >
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-blue-1 text-blue-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ users.length }} {{ __("User")
                            }}{{ users.length !== 1 ? "s" : "" }}
                        </div>
                        <q-icon name="mdi-account-group" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-green-1 text-green-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ activeUsersCount }} {{ __("Active") }}
                        </div>
                        <q-icon name="mdi-account-check" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-orange-1 text-orange-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ inactiveUsersCount }} {{ __("Inactive") }}
                        </div>
                        <q-icon name="mdi-account-off" size="md" />
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Grid View -->
        <div
            v-if="viewMode === 'grid' && users.length > 0"
            class="row q-col-gutter-md q-pa-sm"
        >
            <div
                class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
                v-for="user in users"
                :key="user.id"
            >
                <q-card bordered class="user-card shadow-3">
                    <q-card-section class="bg-primary text-white">
                        <div class="text-h6 text-weight-bold text-truncate">
                            {{ user.name }} {{ user.last_name }}
                        </div>
                        <div class="text-caption opacity-80">
                            {{ user.email }}
                        </div>
                    </q-card-section>

                    <q-card-section class="q-pt-md">
                        <div class="row q-gutter-sm q-mb-sm">
                            <q-badge
                                :color="user.disabled ? 'orange' : 'green'"
                                :icon="
                                    user.disabled
                                        ? 'mdi-account-off'
                                        : 'mdi-account-check'
                                "
                            >
                                {{
                                    user.disabled
                                        ? __("Inactive")
                                        : __("Active")
                                }}
                            </q-badge>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions
                        align="center"
                        class="q-pa-sm flex justify-between"
                    >
                        <v-update
                            v-if="!user.disabled"
                            @updated="getUsers"
                            :item="user"
                        />
                        <v-scopes
                            v-if="!user.disabled"
                            :item="user"
                            class="q-mx-xs"
                        />
                        <v-revoke
                            v-if="!user.disabled"
                            :item="user"
                            class="q-mx-xs"
                        />
                        <v-status
                            :item="user"
                            @updated="getUsers"
                            class="q-mx-xs"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <!-- Empty State for Grid View -->
        <div
            v-else-if="viewMode === 'grid' && users.length === 0"
            class="text-center q-pa-xl"
        >
            <q-icon name="mdi-account-off-outline" size="xl" color="grey-4" />
            <div class="text-h6 text-grey-6 q-mt-md">
                {{ __("No users found") }}
            </div>
            <div class="text-grey-5">
                {{ __("Create your first user to get started") }}
            </div>
        </div>

        <!-- List View -->
        <q-table
            v-else
            :rows="users"
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
                            {{ props.row.name }} {{ props.row.last_name }}
                        </div>
                    </q-td>

                    <q-td key="email" :props="props">
                        <div class="text-body1">
                            {{ props.row.email }}
                        </div>
                    </q-td>

                    <q-td key="status" :props="props">
                        <q-badge
                            :color="props.row.disabled ? 'orange' : 'green'"
                            :icon="
                                props.row.disabled
                                    ? 'mdi-account-off'
                                    : 'mdi-account-check'
                            "
                        >
                            {{
                                props.row.disabled
                                    ? __("Inactive")
                                    : __("Active")
                            }}
                        </q-badge>
                    </q-td>

                    <q-td key="actions" :props="props" width="250">
                        <div class="row q-gutter-xs flex justify-between">
                            <v-update
                                v-if="!props.row.disabled"
                                :item="props.row"
                                @updated="getUsers"
                            />
                            <v-scopes
                                v-if="!props.row.disabled"
                                :item="props.row"
                            />
                            <v-revoke
                                v-if="!props.row.disabled"
                                :item="props.row"
                            />
                            <v-status :item="props.row" @updated="getUsers" />
                        </div>
                    </q-td>
                </q-tr>
            </template>

            <template v-slot:no-data>
                <div class="full-width row flex-center text-grey-6 q-pa-xl">
                    <q-icon name="mdi-account-off-outline" size="xl" />
                    <div class="q-ml-sm">{{ __("No users available") }}</div>
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
                @update:model-value="getUsers"
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
import VScopes from "./Scopes.vue";
import VStatus from "./Status.vue";
import VRevoke from "./Revoke.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VScopes,
        VStatus,
        VRevoke,
    },

    data() {
        return {
            viewMode: "list",
            loading: false,
            columns: [
                {
                    name: "name",
                    label: this.__("Name"),
                    field: (row) => `${row.name} ${row.last_name}`,
                    sortable: true,
                    align: "left",
                },
                {
                    name: "email",
                    label: this.__("Email"),
                    field: "email",
                    sortable: true,
                    align: "left",
                },
                {
                    name: "status",
                    label: this.__("Status"),
                    field: "disabled",
                    sortable: true,
                    align: "center",
                    format: (val) => (val ? "Inactive" : "Active"),
                },
                {
                    name: "actions",
                    label: this.__("Actions"),
                    field: "actions",
                    sortable: false,
                    align: "right",
                },
            ],
            params: [
                { key: "Name", value: "name" },
                { key: "Last Name", value: "last_name" },
                { key: "Email", value: "email" },
                { key: "Created", value: "created" },
                { key: "Updated", value: "updated" },
            ],
            users: [],
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
        };
    },

    computed: {
        activeUsersCount() {
            return this.users.filter((user) => !user.disabled).length;
        },
        inactiveUsersCount() {
            return this.users.filter((user) => user.disabled).length;
        },
    },

    created() {
        this.getUsers();
    },

    watch: {
        "search.page"(value) {
            this.getUsers();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getUsers();
            }
        },
    },

    methods: {
        changePage(event) {
            this.search.page = event;
        },

        searching(event) {
            this.getUsers(event);
        },

        async getUsers(param = null) {
            this.loading = true;
            var params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: params,
                });

                if (res.status == 200) {
                    this.users = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                }
            } catch (e) {
                console.error("Error fetching users:", e);
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style lang="css" scoped>
.user-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 12px;
}

.user-card:hover {
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
