<template>
    <v-admin-ecommerce-layout>
        <!-- Collapsible Filter Card -->
        <q-card flat bordered class="q-mb-md shadow-2">
            <q-expansion-item
                v-model="filterExpanded"
                expand-icon-toggle
                switch-toggle-side
                :label="filterHeaderText"
                header-class="text-primary"
                class="filter-expander"
            >
                <template v-slot:header>
                    <q-item-section avatar>
                        <q-icon name="filter_alt" size="sm" />
                    </q-item-section>
                    <q-item-section>
                        <div class="text-subtitle1">{{ filterHeaderText }}</div>
                    </q-item-section>
                    <q-item-section side>
                        <q-btn
                            size="sm"
                            flat
                            dense
                            round
                            icon="help"
                            @click.stop="showFilterHelp = true"
                        >
                            <q-tooltip>Filter help</q-tooltip>
                        </q-btn>
                    </q-item-section>
                </template>

                <q-separator />

                <q-form
                    @submit.prevent="getProducts"
                    class="row q-col-gutter-md q-pa-md"
                >
                    <!-- Search Inputs -->
                    <q-input
                        dense
                        filled
                        v-model="search.name"
                        label="Name"
                        class="col-12 col-sm-6 col-md-3"
                        debounce="500"
                        @update:model-value="getProducts"
                        clearable
                    >
                        <template v-slot:prepend>
                            <q-icon name="search" />
                        </template>
                    </q-input>

                    <q-input
                        dense
                        filled
                        v-model="search.category"
                        label="Category"
                        class="col-12 col-sm-6 col-md-3"
                        debounce="500"
                        @update:model-value="getProducts"
                        clearable
                    />

                    <q-input
                        dense
                        filled
                        v-model="search.model"
                        label="Model"
                        class="col-12 col-sm-6 col-md-3"
                        debounce="500"
                        @update:model-value="getProducts"
                        clearable
                    />

                    <q-input
                        dense
                        filled
                        v-model="search.family"
                        label="Family"
                        class="col-12 col-sm-6 col-md-3"
                        debounce="500"
                        @update:model-value="getProducts"
                        clearable
                    />

                    <!-- Unified Stock Filter -->
                    <div class="col-12 col-md-3">
                        <div class="text-caption text-grey q-pb-xs">
                            Stock Filter
                        </div>
                        <div
                            class="row no-wrap items-stretch rounded-borders overflow-hidden"
                        >
                            <q-select
                                dense
                                filled
                                v-model="search.stock_operator"
                                :options="operators"
                                emit-value
                                map-options
                                @update:model-value="getProducts"
                                style="
                                    min-width: 80px;
                                    border-right: 1px solid rgba(0, 0, 0, 0.12);
                                "
                            />
                            <q-input
                                dense
                                filled
                                v-model="search.stock"
                                type="number"
                                debounce="500"
                                @update:model-value="getProducts"
                                clearable
                                class="flex-grow-1"
                                placeholder="Quantity"
                                :rules="[
                                    (val) => val >= 0 || 'Must be positive',
                                ]"
                            />
                        </div>
                    </div>

                    <!-- Unified Price Filter -->
                    <div class="col-12 col-md-3">
                        <div class="text-caption text-grey q-pb-xs">
                            Price Filter
                        </div>
                        <div
                            class="row no-wrap items-stretch rounded-borders overflow-hidden"
                        >
                            <q-select
                                dense
                                filled
                                v-model="search.price_operator"
                                :options="operators"
                                emit-value
                                map-options
                                @update:model-value="getProducts"
                                style="
                                    min-width: 80px;
                                    border-right: 1px solid rgba(0, 0, 0, 0.12);
                                "
                            />
                            <q-input
                                dense
                                filled
                                v-model="search.price"
                                debounce="500"
                                @update:model-value="getProducts"
                                clearable
                                placeholder="Amount"
                                class="flex-grow-1"
                                mask="#.##"
                                fill-mask="0"
                                reverse-fill-mask
                                :rules="[
                                    (val) => val >= 0 || 'Must be positive',
                                ]"
                            >
                                <template v-slot:prepend>
                                    <q-icon name="attach_money" />
                                </template>
                            </q-input>
                        </div>
                    </div>

                    <!-- Active Filter Badges -->
                    <div class="col-12" v-if="activeFilterCount > 0">
                        <div class="text-caption text-grey q-pb-xs">
                            Active Filters
                        </div>
                        <div class="row q-gutter-sm">
                            <q-badge
                                v-for="(value, key) in activeFilters"
                                :key="key"
                                color="primary"
                                class="q-px-sm"
                            >
                                {{ filterLabels[key] }}: {{ value }}
                                <q-icon
                                    name="cancel"
                                    size="xs"
                                    class="q-ml-xs cursor-pointer"
                                    @click="clearFilter(key)"
                                />
                            </q-badge>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 flex justify-between items-center">
                        <q-btn
                            size="sm"
                            outline
                            color="primary"
                            label="Reset All Filters"
                            icon="restart_alt"
                            @click="resetFilters"
                        />
                        <div class="text-caption">
                            Showing {{ groups.length }} of
                            {{ pagination.rowsNumber }} products
                        </div>
                    </div>
                </q-form>
            </q-expansion-item>
        </q-card>

        <!-- Products Section -->
        <q-card flat bordered class="shadow-2">
            <q-card-section class="row justify-between items-center no-wrap">
                <div class="text-h6 flex items-center no-wrap">
                    <q-icon name="inventory_2" class="q-mr-sm" />
                    <span class="ellipsis">Product Inventory</span>
                    <q-badge
                        v-if="loading"
                        color="primary"
                        class="q-ml-sm"
                        label="Loading..."
                    />
                </div>

                <div class="row items-center q-gutter-sm">
                    <q-select
                        v-model="search.per_page"
                        :options="[10, 15, 25, 50, 100]"
                        dense
                        filled
                        emit-value
                        map-options
                        options-dense
                        style="min-width: 80px"
                        label="Items per page"
                        @update:model-value="getProducts"
                    />
                    <v-create @created="getProducts" />
                </div>
            </q-card-section>

            <q-separator />

            <!-- Enhanced Products Table -->

            <q-table
                :rows="groups"
                :columns="columns"
                row-key="id"
                bordered
                :pagination="pagination"
                :loading="loading"
                :rows-per-page-options="[]"
                hide-bottom
                class="sticky-header-table"
                :grid="$q.screen.lt.md"
                @request="onTableRequest"
            >
                <template v-slot:body-cell-stock="props">
                    <q-td :props="props">
                        <q-badge
                            :color="getStockColor(props.row.stock)"
                            class="q-px-sm"
                        >
                            {{ props.row.stock }}
                        </q-badge>
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td class="flex justify-between">
                        <v-create
                            @created="getProducts"
                            :searchable="props.row.name"
                            title="Edit"
                            color="primary"
                            icon="edit"
                        />
                        <v-delete :item="props.row" @deleted="getProducts" />
                    </q-td>
                </template>
                <!-- Mobile/Grid View - Update to include tags -->
                <template v-slot:item="props">
                    <div class="q-pa-xs col-12 col-sm-6 col-md-4">
                        <q-card bordered class="hover-card">
                            <q-card-section>
                                <div class="row items-center no-wrap">
                                    <div class="col">
                                        <div class="text-h6 ellipsis">
                                            {{ props.row.name }}
                                        </div>
                                    </div>
                                    <q-badge
                                        :color="getStockColor(props.row.stock)"
                                        class="q-ml-sm"
                                    >
                                        {{ props.row.stock }}
                                    </q-badge>
                                </div>
                            </q-card-section>

                            <q-separator />

                            <q-card-section class="q-pt-none">
                                <div class="row q-col-gutter-sm">
                                    <div class="col-6">
                                        <div class="text-caption">Price</div>
                                        <div class="text-weight-medium">
                                            {{ props.row.format_price }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-caption">Category</div>
                                        <q-badge
                                            v-if="props.row.category"
                                            :color="
                                                props.row.category.color ||
                                                'primary'
                                            "
                                            class="q-px-sm"
                                        >
                                            {{ props.row.category.name }}
                                        </q-badge>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-caption">Tags</div>
                                        <div class="q-gutter-xs q-mt-xs">
                                            <q-badge
                                                v-for="tag in props.row.tags"
                                                :key="tag.id"
                                                color="secondary"
                                                text-color="white"
                                                class="q-px-sm"
                                            >
                                                {{ tag.name }}
                                            </q-badge>
                                        </div>
                                    </div>
                                </div>
                            </q-card-section>

                            <q-separator />

                            <q-card-actions align="right" class="q-pa-sm">
                                <v-create
                                    @created="getProducts"
                                    :searchable="props.row.name"
                                    title="Edit"
                                    color="primary"
                                    icon="edit"
                                />
                                <v-delete
                                    :item="props.row"
                                    @deleted="getProducts"
                                />
                            </q-card-actions>
                        </q-card>
                    </div>
                </template>

                <!-- Empty State -->
                <template v-slot:no-data>
                    <div
                        class="full-width row flex-center text-grey q-gutter-sm"
                    >
                        <q-icon name="inventory" size="2em" />
                        <span v-if="!loading">
                            No products found
                            <q-btn
                                flat
                                color="primary"
                                label="Reset filters"
                                @click="resetFilters"
                            />
                        </span>
                        <span v-else>Loading products...</span>
                    </div>
                </template>
            </q-table>

            <!-- Pagination with items per page selector -->
            <div class="row justify-between items-center q-px-md q-py-sm">
                <div class="text-caption text-grey">
                    Showing {{ paginationStart }} to {{ paginationEnd }} of
                    {{ pagination.rowsNumber }} entries
                </div>
                <q-pagination
                    v-model="search.page"
                    :max="pages.total_pages"
                    :max-pages="6"
                    color="primary"
                    size="sm"
                    boundary-numbers
                    direction-links
                    gutter="sm"
                    input
                />
            </div>
        </q-card>

        <!-- Filter Help Dialog -->
        <q-dialog v-model="showFilterHelp">
            <q-card style="min-width: 300px">
                <q-card-section>
                    <div class="text-h6">Filter Help</div>
                </q-card-section>

                <q-separator />

                <q-card-section class="q-pt-none">
                    <p class="q-mb-sm">
                        <strong>Operators:</strong> Use >, <, = to filter
                        numeric values
                    </p>
                    <p class="q-mb-sm">
                        <strong>Text fields:</strong> Partial matches are
                        supported
                    </p>
                    <p>
                        <strong>Empty fields:</strong> Will be ignored in
                        filtering
                    </p>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="OK" color="primary" v-close-popup />
                </q-card-actions>
            </q-card>
        </q-dialog>
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
            filterExpanded: false,
            loading: false,
            showFilterHelp: false,
            groups: [],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
                name: "",
                category: "",
                model: "",
                family: "",
                stock: null,
                stock_operator: "=",
                price: null,
                price_operator: "=",
                order_by: "updated_at",
                order_type: "desc",
            },
            operators: [
                { label: "=", value: "=" },
                { label: ">", value: ">" },
                { label: ">=", value: ">=" },
                { label: "<", value: "<" },
                { label: "<=", value: "<=" },
            ],
            columns: [
                {
                    name: "name",
                    label: "Name",
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "stock",
                    label: "Stock",
                    field: (row) => row.stock,
                    align: "left",
                    sortable: true,
                },
                {
                    name: "format_price",
                    label: "Price",
                    field: (row) => `${row.currency} ${row.format_price}`,
                    align: "left",
                    sortable: true,
                },
                {
                    name: "category",
                    label: "Category",
                    field: (row) => row.category?.name,
                    align: "left",
                    sortable: true,
                },
                {
                    name: "published",
                    label: "Published",
                    field: (row) => (row.published ? "Yes" : "No"),
                    align: "left",
                    sortable: true,
                },
                {
                    name: "featured",
                    label: "Featured",
                    field: (row) => (row.featured ? "Yes" : "No"),
                    align: "left",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "center",
                },
            ],
            filterLabels: {
                name: "Name",
                category: "Category",
                model: "Model",
                family: "Family",
                stock: "Stock",
                price: "Price",
            },
        };
    },

    computed: {
        pagination() {
            return {
                page: this.search.page,
                rowsPerPage: this.search.per_page,
                rowsNumber: this.pages.total_pages * this.search.per_page,
            };
        },
        paginationStart() {
            return (this.search.page - 1) * this.search.per_page + 1;
        },
        paginationEnd() {
            const end = this.search.page * this.search.per_page;
            return end > this.pagination.rowsNumber
                ? this.pagination.rowsNumber
                : end;
        },
        filterHeaderText() {
            const count = this.activeFilterCount;
            return count > 0
                ? `Filters (${count} active)`
                : "Filters (click to expand)";
        },
        activeFilterCount() {
            return Object.keys(this.activeFilters).length;
        },
        activeFilters() {
            const filters = {};
            if (this.search.name) filters.name = this.search.name;
            if (this.search.category) filters.category = this.search.category;
            if (this.search.model) filters.model = this.search.model;
            if (this.search.family) filters.family = this.search.family;
            if (this.search.stock)
                filters.stock = `${this.search.stock_operator} ${this.search.stock}`;
            if (this.search.price)
                filters.price = `${this.search.price_operator} ${this.search.price}`;
            return filters;
        },
    },

    created() {
        this.getProducts();
    },

    watch: {
        "search.per_page"(val) {
            this.search.page = 1;
            this.getProducts();
        },
    },

    methods: {
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },

        formatCurrency(value) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
            }).format(value);
        },

        getStockColor(stock) {
            if (!stock && stock !== 0) return "grey";
            if (stock > 50) return "positive";
            if (stock > 10) return "warning";
            if (stock > 0) return "orange";
            return "negative";
        },

        onTableRequest(props) {
            this.search.page = props.pagination.page;
            this.search.per_page = props.pagination.rowsPerPage;
            this.search.order_by = props.pagination.sortBy;
            this.search.order_type = props.pagination.descending
                ? "desc"
                : "asc";
            this.getProducts();
        },

        clearFilter(key) {
            if (key === "stock") {
                this.search.stock = null;
                this.search.stock_operator = "=";
            } else if (key === "price") {
                this.search.price = null;
                this.search.price_operator = "=";
            } else {
                this.search[key] = "";
            }
            this.getProducts();
        },

        resetFilters() {
            this.search = {
                ...this.search,
                name: "",
                category: "",
                model: "",
                family: "",
                stock: null,
                stock_operator: "=",
                price: null,
                price_operator: "=",
                order_by: "updated_at",
                order_type: "desc",
            };
            this.search.page = 1;
            this.getProducts();
        },

        async getProducts() {
            this.loading = true;
            try {
                const { data } = await this.$server.get(
                    this.$page.props.routes["products"],
                    {
                        params: this.search,
                    }
                );

                this.groups = data.data;
                this.pages = data.meta.pagination;
                this.search.page = data.meta.pagination.current_page;
            } catch (err) {
                console.error("Error loading products:", err);
                this.$q.notify({
                    type: "negative",
                    message: "Failed to load products",
                    actions: [
                        {
                            icon: "refresh",
                            color: "white",
                            handler: this.getProducts,
                        },
                    ],
                });
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.sticky-header-table {
    height: calc(100vh - 320px);
}

.ellipsis {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.hover-card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.filter-expander {
    transition: all 0.3s ease;
}

.filter-expander .q-item {
    padding: 8px 16px;
}

.filter-expander .q-focus-helper {
    background: rgba(0, 0, 0, 0.05);
}

@media (max-width: 600px) {
    .sticky-header-table {
        height: auto;
    }
}
</style>
