<template>
    <v-partner-layout>
        <div class="bg-primary text-white q-pa-md">
            <div class="row items-center">
                <div class="col">
                    <div class="text-h5 text-weight-bold">
                        Partner Management
                    </div>
                    <div class="text-subtitle1">
                        Manage your partners and their commissions
                    </div>
                </div>
                <div class="col-auto">
                    <q-icon name="people" size="48px" class="q-mr-sm" />
                </div>
            </div>
        </div>

        <q-card flat class="q-mb-md q-mx-md">
            <q-card-section>
                <div class="row items-center justify-between">
                    <div class="text-h6 text-primary">Filters</div>
                    <div class="row items-center q-gutter-sm">
                        <q-toggle
                            v-model="showFilters"
                            label="Show Filters"
                            color="primary"
                        />
                        <q-btn
                            icon="refresh"
                            color="primary"
                            flat
                            round
                            @click="resetFilters"
                        >
                            <q-tooltip>Reset filters</q-tooltip>
                        </q-btn>
                    </div>
                </div>
            </q-card-section>

            <q-slide-transition>
                <div v-show="showFilters">
                    <q-card-section class="row q-col-gutter-md">
                        <div class="col-12 col-sm-6 col-md-3">
                            <q-input
                                v-model="search.name"
                                dense
                                outlined
                                label="Name"
                                debounce="500"
                                @update:model-value="getPartners"
                                clearable
                            >
                                <template v-slot:prepend>
                                    <q-icon name="person" />
                                </template>
                            </q-input>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <q-input
                                v-model="search.last_name"
                                dense
                                outlined
                                label="Last Name"
                                debounce="500"
                                @update:model-value="getPartners"
                                clearable
                            >
                                <template v-slot:prepend>
                                    <q-icon name="badge" />
                                </template>
                            </q-input>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <q-input
                                v-model="search.email"
                                dense
                                outlined
                                label="Email"
                                debounce="500"
                                @update:model-value="getPartners"
                                clearable
                            >
                                <template v-slot:prepend>
                                    <q-icon name="email" />
                                </template>
                            </q-input>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <q-input
                                v-model="search.code"
                                dense
                                outlined
                                label="Code"
                                debounce="500"
                                @update:model-value="getPartners"
                                clearable
                            >
                                <template v-slot:prepend>
                                    <q-icon name="qr_code" />
                                </template>
                            </q-input>
                        </div>
                    </q-card-section>
                </div>
            </q-slide-transition>
        </q-card>

        <div class="row q-px-md q-mb-md items-center">
            <div class="col-12 col-md-6">
                <q-banner inline-actions rounded class="bg-blue-1 text-primary">
                    <template v-slot:avatar>
                        <q-icon name="info" color="primary" />
                    </template>
                    Showing {{ users.length }} of
                    {{ pages.total_count || 0 }} partners
                </q-banner>
            </div>

            <div
                class="col-12 col-md-6 row justify-end items-center q-gutter-sm"
            >
                <q-select
                    v-model="search.per_page"
                    :options="[10, 15, 25, 50, 100]"
                    label="Items per page"
                    dense
                    outlined
                    style="min-width: 140px"
                    @update:model-value="getPartners"
                />

                <q-btn-toggle
                    v-model="viewMode"
                    toggle-color="primary"
                    :options="[
                        { value: 'list', icon: 'list', label: 'List' },
                        { value: 'grid', icon: 'grid_on', label: 'Grid' },
                    ]"
                    spread
                    unelevated
                />
            </div>
        </div>

        <div
            v-if="viewMode === 'grid' && users.length"
            class="row q-col-gutter-md q-px-md"
        >
            <div
                class="col-12 col-sm-6 col-md-4 col-lg-3"
                v-for="user in users"
                :key="user.id"
            >
                <q-card class="partner-card shadow-5" flat>
                    <q-card-section class="bg-primary text-white">
                        <div class="row items-center no-wrap">
                            <div>
                                <div
                                    class="text-h6 text-weight-bold text-ellipsis"
                                >
                                    {{ user.name }} {{ user.last_name }}
                                </div>
                                <div class="text-subtitle2 text-ellipsis">
                                    {{ user.email }}
                                </div>
                            </div>
                            <q-space />
                            <q-avatar
                                color="white"
                                text-color="primary"
                                size="48px"
                            >
                                {{ userInitials(user) }}
                            </q-avatar>
                        </div>
                    </q-card-section>

                    <q-card-section class="q-pt-none">
                        <div class="partner-details">
                            <div class="detail-item">
                                <q-icon
                                    name="fingerprint"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>ID: {{ user.id }}</span>
                            </div>
                            <div class="detail-item">
                                <q-icon
                                    name="qr_code"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>Code: {{ user.code }}</span>
                            </div>
                            <div class="detail-item" v-if="user.country">
                                <q-icon
                                    name="public"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>Country: {{ user.country }}</span>
                            </div>
                            <div class="detail-item" v-if="user.phone">
                                <q-icon
                                    name="phone"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span>Phone: {{ user.phone }}</span>
                            </div>
                            <div class="detail-item">
                                <q-icon
                                    name="payments"
                                    size="16px"
                                    class="q-mr-xs"
                                />
                                <span
                                    >Commission:
                                    {{ user.commission_rate }}%</span
                                >
                            </div>
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions align="right">
                        <v-update
                            v-if="!user.disabled"
                            @updated="getPartners"
                            :item="user"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </div>

        <div
            v-else-if="viewMode === 'grid' && !users.length"
            class="flex flex-center q-pa-xl"
        >
            <q-banner
                inline-actions
                rounded
                class="bg-grey-3 full-width text-center"
            >
                <template v-slot:avatar>
                    <q-icon name="search_off" color="grey" size="32px" />
                </template>
                No partners found matching your criteria
                <template v-slot:action>
                    <q-btn
                        flat
                        color="primary"
                        label="Clear Filters"
                        @click="resetFilters"
                    />
                </template>
            </q-banner>
        </div>

        <div v-if="viewMode === 'list'" class="q-px-md">
            <q-table
                :rows="users"
                :columns="columns"
                row-key="id"
                class="shadow-2 rounded-borders"
                flat
                bordered
                :loading="loading"
                :rows-per-page-options="[0]"
                hide-pagination
                :grid="false"
            >
                <template v-slot:loading>
                    <q-inner-loading showing color="primary" />
                </template>

                <template v-slot:body-cell-name="props">
                    <q-td :props="props">
                        <div class="row items-center">
                            <q-avatar
                                color="primary"
                                text-color="white"
                                size="32px"
                                class="q-mr-sm"
                            >
                                {{ userInitials(props.row) }}
                            </q-avatar>
                            <div>
                                <div class="text-weight-medium">
                                    {{ props.row.name }}
                                    {{ props.row.last_name }}
                                </div>
                                <div class="text-caption text-grey-7">
                                    {{ props.row.email }}
                                </div>
                            </div>
                        </div>
                    </q-td>
                </template>

                <template v-slot:body-cell-commission_rate="props">
                    <q-td :props="props">
                        <q-badge color="blue" outline>
                            {{ props.value }}%
                        </q-badge>
                    </q-td>
                </template>

                <template v-slot:body-cell-actions="props">
                    <q-td align="right">
                        <v-update :item="props.row" @updated="getPartners" />
                    </q-td>
                </template>

                <template v-slot:no-data>
                    <div
                        class="full-width row flex-center text-grey q-gutter-sm q-pa-lg"
                    >
                        <q-icon name="search_off" size="2em" />
                        <span>No partners found</span>
                        <q-btn
                            flat
                            color="primary"
                            label="Clear Filters"
                            @click="resetFilters"
                        />
                    </div>
                </template>
            </q-table>
        </div>

        <div
            class="row justify-between items-center q-pa-md"
            v-if="pages.total_pages > 1"
        >
            <div class="text-caption text-grey-7">
                Page {{ search.page }} of {{ pages.total_pages }} •
                {{ pages.total_count }} total partners
            </div>

            <q-pagination
                v-model="search.page"
                color="primary"
                :max="pages.total_pages"
                :max-pages="6"
                boundary-numbers
                direction-links
                unelevated
            />
        </div>
    </v-partner-layout>
</template>

<script>
import VUpdate from "./Update.vue";

export default {
    components: { VUpdate },

    data() {
        return {
            viewMode: "list",
            showFilters: true,
            loading: false,
            columns: [
                {
                    name: "name",
                    label: "Partner",
                    field: (row) => `${row.name} ${row.last_name}`,
                    align: "left",
                    sortable: true,
                    headerClasses: "text-weight-bold",
                },

                {
                    name: "code",
                    label: "Code",
                    field: "code",
                    sortable: true,
                    align: "left",
                    headerClasses: "text-weight-bold",
                },

                {
                    name: "commission_rate",
                    label: "Commission",
                    field: "commission_rate",
                    align: "center",
                    headerClasses: "text-weight-bold",
                },
                {
                    name: "actions",
                    label: "Actions",
                    field: "actions",
                    align: "center",
                    headerClasses: "text-weight-bold",
                },
            ],
            users: [],
            pages: {
                total_pages: 0,
                total_count: 0,
            },
            search: {
                page: 1,
                per_page: 15,
                name: "",
                last_name: "",
                email: "",
                code: "",
            },
        };
    },

    created() {
        this.getPartners();
    },

    watch: {
        "search.page"(value) {
            this.getPartners();
        },
        "search.per_page"(value) {
            if (value) {
                this.search.per_page = value;
                this.getPartners();
            }
        },
    },

    methods: {
        async getPartners(param = null) {
            this.loading = true;
            const params = { ...this.search, ...param };

            try {
                const res = await this.$server.get(
                    this.$page.props.routes.partners,
                    { params }
                );

                if (res.status === 200 && res.data.data.length) {
                    this.users = res.data.data;
                    const meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.current_page = meta.pagination.current_page;
                } else {
                    this.users = [];
                    this.pages = {
                        total_pages: 0,
                        total_count: 0,
                    };
                }
            } catch (e) {
                console.error(e);
                this.$q.notify({
                    type: "negative",
                    message: "Error loading partners",
                });
            } finally {
                this.loading = false;
            }
        },

        resetFilters() {
            this.search = {
                page: 1,
                per_page: 15,
                name: "",
                last_name: "",
                email: "",
                code: "",
            };
            this.getPartners();
        },

        userInitials(user) {
            return (
                `${user.name?.charAt(0) || ""}${
                    user.last_name?.charAt(0) || ""
                }`.toUpperCase() || "U"
            );
        },
    },
};
</script>

<style scoped>
.partner-card {
    border-radius: 12px;
    transition: transform 0.3s, box-shadow 0.3s;
}

.partner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.partner-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.text-ellipsis {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

::v-deep .q-table__top {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

::v-deep .q-table__container {
    border-radius: 12px;
}

::v-deep .q-table thead tr {
    height: 60px;
}

::v-deep .q-table thead th {
    font-size: 1rem;
    font-weight: 600;
}

::v-deep .q-table tbody td {
    font-size: 0.95rem;
    height: 64px;
}

::v-deep .q-pagination .q-btn {
    border-radius: 8px;
}
</style>
