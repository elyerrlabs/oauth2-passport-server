<template>
    <v-admin-layout>
        <!-- Header Section -->
        <div class="bg-white q-pa-md shadow-2 rounded-borders">
            <div class="row items-center justify-between q-mb-md">
                <div>
                    <div class="text-h4 text-primary text-weight-bold">
                        {{ __("OAuth Clients Management") }}
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        {{ __("Manage your application's OAuth 2.0 clients") }}
                    </div>
                </div>

                <div class="row items-center q-gutter-sm">
                    <v-personal-client @created="getClients" />
                    <v-create @created="getClients" />
                </div>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div
            class="row q-col-gutter-md q-mb-md q-mt-sm"
            v-if="clients.length > 0"
        >
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-blue-1 text-blue-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ clients.length }} {{ __("Client")
                            }}{{ clients.length !== 1 ? "s" : "" }}
                        </div>
                        <q-icon name="mdi-application" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-green-1 text-green-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ confidentialClientsCount }}
                            {{ __("Confidential") }}
                        </div>
                        <q-icon name="mdi-shield-lock" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-orange-1 text-orange-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ publicClientsCount }} {{ __("Public") }}
                        </div>
                        <q-icon name="mdi-earth" size="md" />
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <q-card flat class="bg-purple-1 text-purple-8">
                    <q-card-section class="text-center">
                        <div class="text-h6">
                            {{ totalGrantTypes }} {{ __("Grant Types") }}
                        </div>
                        <q-icon name="mdi-key-chain" size="md" />
                    </q-card-section>
                </q-card>
            </div>
        </div>

        <!-- Clients Table -->
        <q-table
            flat
            bordered
            :rows="clients"
            :columns="columns"
            row-key="id"
            :loading="loading"
            :pagination="pagination"
            hide-pagination
            class="shadow-1 rounded-borders q-mt-md"
        >
            <template v-slot:body="props">
                <q-tr :props="props" class="q-hoverable">
                    <q-td key="name" :props="props">
                        <div class="text-weight-bold text-primary">
                            {{ props.row.name }}
                        </div>
                        <div class="text-caption text-grey-7">
                            {{ __("ID:") }} {{ props.row.id }}
                        </div>
                    </q-td>

                    <q-td key="created_at" :props="props">
                        <div class="text-caption">
                            {{ formatDate(props.row.created_at) }}
                        </div>
                    </q-td>

                    <q-td key="confidential" :props="props">
                        <q-badge
                            :color="props.row.confidential ? 'green' : 'orange'"
                            :icon="
                                props.row.confidential
                                    ? 'mdi-shield-lock'
                                    : 'mdi-earth'
                            "
                        >
                            {{ props.row.confidential ? __("Yes") : __("No") }}
                        </q-badge>
                    </q-td>

                    <q-td key="created_by" :props="props">
                        <div class="text-caption">
                            {{ props.row.created_by?.email || __("System") }}
                        </div>
                    </q-td>

                    <q-td key="grant_types" :props="props">
                        <div class="q-gutter-xs">
                            <q-badge
                                v-for="(grant, index) in formatGrantTypes(
                                    props.row.grant_types
                                )"
                                :key="index"
                                color="purple"
                                class="q-pa-xs grant-badge"
                            >
                                {{ grant }}
                            </q-badge>
                        </div>
                    </q-td>

                    <q-td key="actions" :props="props" auto-width>
                        <div class="row q-gutter-xs justify-end">
                            <v-update :item="props.row" @updated="getClients" />
                            <v-delete :item="props.row" @deleted="getClients" />
                        </div>
                    </q-td>
                </q-tr>
            </template>

            <template v-slot:no-data>
                <div class="full-width row flex-center text-grey-6 q-pa-xl">
                    <q-icon name="mdi-application-off" size="xl" />
                    <div class="q-ml-sm">{{ __("No clients available") }}</div>
                    <div class="text-caption text-grey-5 q-mt-sm">
                        {{
                            __("Create your first OAuth client to get started")
                        }}
                    </div>
                </div>
            </template>

            <template v-slot:loading>
                <q-inner-loading showing color="primary" />
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
                @update:model-value="getClients"
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
import VPersonalClient from "./PersonalClient.vue";

export default {
    components: {
        VCreate,
        VUpdate,
        VDelete,
        VPersonalClient,
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
            pagination: {
                sortBy: "name",
                descending: false,
                page: 1,
                rowsPerPage: 15,
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
                /*{
                    name: "provider",
                    required: true,
                    label: "Provider",
                    align: "left",
                    field: (row) => row.provider,
                    sortable: true,
                },*/
                {
                    name: "created_at",
                    label: "Created Date",
                    align: "left",
                    field: (row) => row.created_at,
                    sortable: true,
                },
                {
                    name: "confidential",
                    label: "Confidential",
                    align: "center",
                    field: (row) => row.confidential,
                    sortable: true,
                },
                {
                    name: "created_by",
                    label: "Created By",
                    align: "left",
                    field: (row) => row.created_by?.email,
                    sortable: true,
                },
                {
                    name: "grant_types",
                    label: "Grant Types",
                    align: "left",
                    field: (row) => row.grant_types,
                    sortable: false,
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "right",
                    field: (row) => row.id,
                    sortable: false,
                },
            ],
        };
    },

    computed: {
        confidentialClientsCount() {
            return this.clients.filter((client) => client.confidential).length;
        },
        publicClientsCount() {
            return this.clients.filter((client) => !client.confidential).length;
        },
        totalGrantTypes() {
            return this.clients.reduce((total, client) => {
                const grants = this.formatGrantTypes(client.grant_types);
                return total + grants.length;
            }, 0);
        },
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
        formatDate(dateString) {
            if (!dateString) return "";
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },

        formatGrantTypes(grantTypes) {
            if (!grantTypes) return [];

            // Handle different formats: string, array, or comma-separated string
            if (Array.isArray(grantTypes)) {
                return grantTypes;
            }

            if (typeof grantTypes === "string") {
                // Handle comma-separated string
                if (grantTypes.includes(",")) {
                    return grantTypes
                        .split(",")
                        .map((g) => g.trim())
                        .filter((g) => g);
                }
                // Handle single grant type
                return [grantTypes.trim()];
            }

            return [];
        },

        getClients(param = null) {
            this.loading = true;
            const params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.routes.clients, {
                    params: params,
                })
                .then((res) => {
                    this.clients = res.data.data;
                    const meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                })
                .catch((e) => {
                    console.error("Error fetching clients:", e);
                    this.$q.notify({
                        type: "negative",
                        message: "Failed to load clients",
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });

                    if (e?.response?.status == 403) {
                        this.$q.notify({
                            type: "negative",
                            message: e.response.data.message,
                        });
                    }
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
                    message: __("Copied to clipboard"),
                    position: "top",
                    icon: "mdi-check-circle",
                    timeout: 2000,
                });
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: __("Failed to copy"),
                    position: "top",
                    icon: "mdi-alert-circle",
                    timeout: 2000,
                });
            }
        },
    },
};
</script>

<style lang="css" scoped>
.rounded-borders {
    border-radius: 8px;
}

.shadow-1 {
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2), 0 2px 2px rgba(0, 0, 0, 0.14),
        0 3px 1px -2px rgba(0, 0, 0, 0.12);
}

.shadow-2 {
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2), 0 3px 3px rgba(0, 0, 0, 0.14),
        0 1px 7px 0 rgba(0, 0, 0, 0.12);
}

.grant-badge {
    font-size: 0.7em;
    margin: 2px;
}

.q-hoverable:hover {
    background-color: #f5f5f5;
    transition: background-color 0.3s ease;
}
</style>
