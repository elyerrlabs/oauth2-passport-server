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
                        {{ __("Products Management") }}
                    </div>
                    <div class="text-subtitle1 opacity-70">
                        {{ __("Manage your product inventory and catalog") }}
                    </div>
                </div>
                <div class="col-auto">
                    <v-create @created="getProducts" />
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="q-pa-lg">
            <!-- Filter Section -->
            <q-card class="filter-card shadow-3 rounded-borders q-mb-lg">
                <q-expansion-item
                    v-model="filterExpanded"
                    expand-icon-toggle
                    switch-toggle-side
                    :label="filterHeaderText"
                    header-class="text-primary filter-header"
                >
                    <template v-slot:header>
                        <q-item-section avatar>
                            <q-icon name="mdi-filter" size="sm" />
                        </q-item-section>
                        <q-item-section>
                            <div class="text-subtitle1">
                                {{ filterHeaderText }}
                            </div>
                        </q-item-section>
                        <q-item-section side>
                            <q-btn
                                size="sm"
                                flat
                                round
                                icon="mdi-help-circle"
                                @click.stop="showFilterHelp = true"
                            >
                                <q-tooltip>{{ __("Filter Help") }}</q-tooltip>
                            </q-btn>
                        </q-item-section>
                    </template>

                    <q-separator />

                    <q-form @submit.prevent="getProducts" class="q-pa-md">
                        <div class="row q-col-gutter-md">
                            <!-- Search Inputs -->
                            <div class="col-12 col-sm-6 col-md-3">
                                <q-input
                                    filled
                                    v-model="search.name"
                                    :label="__('Product Name')"
                                    clearable
                                    @update:model-value="getProducts"
                                    class="filter-input"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-tag" />
                                    </template>
                                </q-input>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <q-input
                                    filled
                                    v-model="search.category"
                                    :label="__('Category')"
                                    clearable
                                    @update:model-value="getProducts"
                                    class="filter-input"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-folder" />
                                    </template>
                                </q-input>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <q-input
                                    filled
                                    v-model="search.model"
                                    :label="__('Model')"
                                    clearable
                                    @update:model-value="getProducts"
                                    class="filter-input"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-cube" />
                                    </template>
                                </q-input>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <q-input
                                    filled
                                    v-model="search.family"
                                    :label="__('Family')"
                                    clearable
                                    @update:model-value="getProducts"
                                    class="filter-input"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="mdi-group" />
                                    </template>
                                </q-input>
                            </div>

                            <!-- Stock Filter -->
                            <div class="col-12 col-md-6">
                                <div class="text-caption text-grey-7">
                                    {{ __("Stock Level") }}
                                </div>
                                <div class="row">
                                    <q-select
                                        filled
                                        dense
                                        v-model="search.stock_operator"
                                        :options="operators"
                                        emit-value
                                        map-options
                                        class="col-2"
                                    />
                                    <q-input
                                        filled
                                        dense
                                        v-model="search.stock"
                                        type="number"
                                        :placeholder="__('Quantity')"
                                        clearable
                                        @update:model-value="getProducts"
                                        class="col-10"
                                        :rules="[
                                            (val) =>
                                                val >= 0 ||
                                                __('Must be positive'),
                                        ]"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon
                                                name="mdi-package-variant"
                                            />
                                        </template>
                                    </q-input>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="col-12 col-md-6">
                                <div class="text-caption text-grey-7">
                                    {{ __("Price Range") }}
                                </div>
                                <div class="row">
                                    <q-select
                                        filled
                                        dense
                                        v-model="search.price_operator"
                                        :options="operators"
                                        emit-value
                                        map-options
                                        class="col-2"
                                    />
                                    <q-input
                                        filled
                                        dense
                                        v-model="search.price"
                                        :placeholder="__('Amount')"
                                        clearable
                                        @update:model-value="getProducts"
                                        class="col-10"
                                        mask="#.##"
                                        fill-mask="0"
                                        reverse-fill-mask
                                        :rules="[
                                            (val) =>
                                                val >= 0 ||
                                                __('Must be positive'),
                                        ]"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon name="mdi-currency-usd" />
                                        </template>
                                    </q-input>
                                </div>
                            </div>

                            <!-- Active Filters -->
                            <div class="col-12" v-if="activeFilterCount > 0">
                                <div class="text-caption text-grey-">
                                    {{ __("Active Filters") }}
                                </div>
                                <div class="row q-gutter-sm">
                                    <q-badge
                                        v-for="(value, key) in activeFilters"
                                        :key="key"
                                        color="primary"
                                        class="filter-badge"
                                    >
                                        {{ filterLabels[key] }}: {{ value }}
                                        <q-icon
                                            name="mdi-close"
                                            size="xs"
                                            class="q-ml-xs cursor-pointer"
                                            @click="clearFilter(key)"
                                        />
                                    </q-badge>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div
                                class="col-12 flex justify-between items-center q-pt-md"
                            >
                                <q-btn
                                    color="primary"
                                    outline
                                    :label="__('Reset All Filters')"
                                    icon="mdi-refresh"
                                    @click="resetFilters"
                                    class="action-btn"
                                />
                                <div class="text-caption text-grey-6">
                                    {{ filteredResultsText }}
                                </div>
                            </div>
                        </div>
                    </q-form>
                </q-expansion-item>
            </q-card>

            <!-- Products Table Section -->
            <q-card class="products-card shadow-3 rounded-borders">
                <q-card-section class="table-header">
                    <div class="row items-center justify-between">
                        <div class="col">
                            <div class="text-h6 text-weight-bold">
                                {{ __("Product Inventory") }}
                            </div>
                            <div
                                class="text-caption text-grey-6"
                                v-if="!loading"
                            >
                                {{ __("Managing") }}
                                {{ pagination.rowsNumber }} {{ __("products") }}
                            </div>
                        </div>
                        <div class="col-auto row items-center q-gutter-sm">
                            <q-select
                                v-model="search.per_page"
                                :options="[10, 15, 25, 50, 100]"
                                dense
                                filled
                                emit-value
                                map-options
                                :label="__('Items per page')"
                                @update:model-value="getProducts"
                                class="per-page-select"
                            />
                            <q-btn
                                color="primary"
                                icon="mdi-refresh"
                                round
                                dense
                                @click="getProducts"
                                :loading="loading"
                            >
                                <q-tooltip>{{ __("Refresh Data") }}</q-tooltip>
                            </q-btn>
                        </div>
                    </div>
                </q-card-section>

                <q-separator />

                <!-- Products Table -->
                <q-table
                    :rows="groups"
                    :columns="columns"
                    row-key="id"
                    flat
                    bordered
                    :pagination="pagination"
                    :loading="loading"
                    hide-bottom
                    class="products-table"
                    :grid="$q.screen.lt.md"
                    @request="onTableRequest"
                >
                    <!-- Table Header -->
                    <template v-slot:header="props">
                        <q-tr :props="props" class="table-header-row">
                            <q-th
                                v-for="col in props.cols"
                                :key="col.name"
                                :props="props"
                                class="text-weight-bold"
                            >
                                {{ col.label }}
                            </q-th>
                        </q-tr>
                    </template>

                    <!-- Stock Column -->
                    <template v-slot:body-cell-stock="props">
                        <q-td :props="props">
                            <q-badge
                                :color="getStockColor(props.row.stock)"
                                class="stock-badge"
                            >
                                <q-icon
                                    :name="getStockIcon(props.row.stock)"
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{ props.row.stock }}
                            </q-badge>
                        </q-td>
                    </template>

                    <!-- Price Column -->
                    <template v-slot:body-cell-format_price="props">
                        <q-td :props="props">
                            <div class="text-weight-medium text-primary">
                                {{ props.row.currency }}
                                {{ props.row.format_price }}
                            </div>
                        </q-td>
                    </template>

                    <!-- Status Columns -->
                    <template v-slot:body-cell-published="props">
                        <q-td :props="props" class="text-center">
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
                                            ? 'mdi-eye'
                                            : 'mdi-eye-off'
                                    "
                                    size="14px"
                                    class="q-mr-xs"
                                />
                                {{ props.row.published ? __("Yes") : __("No") }}
                            </q-badge>
                        </q-td>
                    </template>

                    <template v-slot:body-cell-featured="props">
                        <q-td :props="props" class="text-center">
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
                                {{ props.row.featured ? __("Yes") : __("No") }}
                            </q-badge>
                        </q-td>
                    </template>

                    <!-- Actions Column -->
                    <template v-slot:body-cell-actions="props">
                        <q-td :props="props" class="actions-cell">
                            <div class="row q-gutter-xs justify-end">
                                <v-create
                                    :item="props.row"
                                    @created="getProducts"
                                    :title="__('Edit')"
                                    class="action-btn"
                                    :searchable="props.row.name"
                                    color="primary"
                                    icon="edit"
                                    size="sm"
                                />
                                <v-delete
                                    :item="props.row"
                                    @deleted="getProducts"
                                    class="action-btn"
                                />
                            </div>
                        </q-td>
                    </template>

                    <!-- Mobile/Grid View -->
                    <template v-slot:item="props">
                        <div class="col-12 col-sm-6 col-md-4 q-pa-sm">
                            <q-card class="product-card hover-card">
                                <q-card-section>
                                    <div class="row items-center no-wrap">
                                        <div class="col">
                                            <div
                                                class="text-h6 text-weight-medium ellipsis"
                                            >
                                                {{ props.row.name }}
                                            </div>
                                            <div
                                                class="text-caption text-grey-6"
                                            >
                                                {{ props.row.category?.name }}
                                            </div>
                                        </div>
                                        <q-badge
                                            :color="
                                                getStockColor(props.row.stock)
                                            "
                                            class="stock-badge-mobile"
                                        >
                                            {{ props.row.stock }}
                                            {{ __("in stock") }}
                                        </q-badge>
                                    </div>
                                </q-card-section>

                                <q-separator />

                                <q-card-section class="q-pt-none">
                                    <div class="row q-col-gutter-sm">
                                        <div class="col-6">
                                            <div class="text-caption">
                                                {{ __("Price") }}
                                            </div>
                                            <div
                                                class="text-weight-bold text-primary"
                                            >
                                                {{ props.row.currency }}
                                                {{ props.row.format_price }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-caption">
                                                {{ __("Status") }}
                                            </div>
                                            <div class="row items-center">
                                                <q-icon
                                                    :name="
                                                        props.row.published
                                                            ? 'mdi-eye-check'
                                                            : 'mdi-eye-off'
                                                    "
                                                    :color="
                                                        props.row.published
                                                            ? 'positive'
                                                            : 'grey-5'
                                                    "
                                                    size="16px"
                                                    class="q-mr-xs"
                                                />
                                                <span class="text-caption">
                                                    {{
                                                        props.row.published
                                                            ? __("Published")
                                                            : __("Hidden")
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </q-card-section>

                                <q-separator />

                                <q-card-actions align="right" class="q-pa-sm">
                                    <v-create
                                        :item="props.row"
                                        @created="getProducts"
                                        :title="__('Edit')"
                                        color="primary"
                                        icon="mdi-pencil"
                                    />
                                    <v-delete
                                        :item="props.row"
                                        @deleted="getProducts"
                                        size="sm"
                                    />
                                </q-card-actions>
                            </q-card>
                        </div>
                    </template>

                    <!-- Empty State -->
                    <template v-slot:no-data>
                        <div
                            class="full-width row flex-center text-grey q-pa-xl"
                        >
                            <div class="text-center">
                                <q-icon
                                    name="mdi-package-variant-closed"
                                    size="64px"
                                    color="grey-4"
                                />
                                <div class="text-h6 q-mt-md">
                                    {{ __("No products found") }}
                                </div>
                                <div class="text-caption q-mb-md">
                                    {{
                                        activeFilterCount > 0
                                            ? __("Try adjusting your filters")
                                            : __(
                                                  "Get started by creating your first product"
                                              )
                                    }}
                                </div>
                                <q-btn
                                    v-if="activeFilterCount > 0"
                                    color="primary"
                                    :label="__('Reset Filters')"
                                    @click="resetFilters"
                                    icon="mdi-filter-remove"
                                />
                                <v-create v-else @created="getProducts" />
                            </div>
                        </div>
                    </template>

                    <!-- Loading State -->
                    <template v-slot:loading>
                        <q-inner-loading showing color="primary">
                            <q-spinner-gears size="50px" color="primary" />
                            <div class="q-mt-md">
                                {{ __("Loading products...") }}
                            </div>
                        </q-inner-loading>
                    </template>
                </q-table>

                <!-- Pagination -->
                <div
                    class="row justify-between items-center q-px-lg q-py-md pagination-section"
                >
                    <div class="text-caption text-grey-6">
                        {{ __("Showing") }} {{ paginationStart }}
                        {{ __("to") }} {{ paginationEnd }} {{ __("of") }}
                        {{ pagination.rowsNumber }} {{ __("products") }}
                    </div>
                    <q-pagination
                        v-model="search.page"
                        :max="pages.total_pages"
                        :max-pages="6"
                        color="primary"
                        size="sm"
                        boundary-numbers
                        direction-links
                        ellipses
                        class="pagination-control"
                    />
                </div>
            </q-card>

            <!-- Filter Help Dialog -->
            <q-dialog v-model="showFilterHelp">
                <q-card class="help-dialog rounded-borders">
                    <q-card-section class="dialog-header bg-primary text-white">
                        <div class="text-h6">{{ __("Filter Help Guide") }}</div>
                    </q-card-section>

                    <q-card-section class="q-pt-lg">
                        <div class="q-gutter-y-md">
                            <div>
                                <div
                                    class="text-subtitle2 text-weight-medium q-mb-xs"
                                >
                                    <q-icon name="mdi-filter" class="q-mr-sm" />
                                    {{ __("Filter Operators") }}
                                </div>
                                <div class="q-pl-md">
                                    <div class="row items-center q-mb-xs">
                                        <q-badge color="primary" class="q-mr-sm"
                                            >=</q-badge
                                        >
                                        <span>{{ __("Equal to") }}</span>
                                    </div>
                                    <div class="row items-center q-mb-xs">
                                        <q-badge color="primary" class="q-mr-sm"
                                            >></q-badge
                                        >
                                        <span>{{ __("Greater than") }}</span>
                                    </div>
                                    <div class="row items-center q-mb-xs">
                                        <q-badge color="primary" class="q-mr-sm"
                                            >>=</q-badge
                                        >
                                        <span>{{
                                            __("Greater than or equal to")
                                        }}</span>
                                    </div>
                                    <div class="row items-center q-mb-xs">
                                        <q-badge color="primary" class="q-mr-sm"
                                            ><</q-badge
                                        >
                                        <span>{{ __("Less than") }}</span>
                                    </div>
                                    <div class="row items-center">
                                        <q-badge color="primary" class="q-mr-sm"
                                            ><=</q-badge
                                        >
                                        <span>{{
                                            __("Less than or equal to")
                                        }}</span>
                                    </div>
                                </div>
                            </div>

                            <q-separator />

                            <div>
                                <div
                                    class="text-subtitle2 text-weight-medium q-mb-xs"
                                >
                                    <q-icon
                                        name="mdi-information"
                                        class="q-mr-sm"
                                    />
                                    {{ __("Tips & Notes") }}
                                </div>
                                <ul class="q-pl-md">
                                    <li>
                                        {{
                                            __(
                                                "Text fields support partial matches"
                                            )
                                        }}
                                    </li>
                                    <li>
                                        {{
                                            __(
                                                "Empty filter fields are ignored"
                                            )
                                        }}
                                    </li>
                                    <li>
                                        {{
                                            __(
                                                "Use the reset button to clear all filters"
                                            )
                                        }}
                                    </li>
                                    <li>
                                        {{
                                            __(
                                                "Stock and price filters work with numeric comparisons"
                                            )
                                        }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </q-card-section>

                    <q-card-actions align="right" class="q-pa-md">
                        <q-btn
                            :label="__('Got It')"
                            color="primary"
                            v-close-popup
                            unelevated
                        />
                    </q-card-actions>
                </q-card>
            </q-dialog>
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
                    label: this.__("Product Name"),
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "stock",
                    label: this.__("Stock Level"),
                    field: "stock",
                    align: "center",
                    sortable: true,
                },
                {
                    name: "format_price",
                    label: this.__("Price"),
                    field: "format_price",
                    align: "right",
                    sortable: true,
                },
                {
                    name: "category",
                    label: this.__("Category"),
                    field: (row) => row.category?.name,
                    align: "left",
                    sortable: true,
                },
                {
                    name: "published",
                    label: this.__("Published"),
                    field: "published",
                    align: "center",
                    sortable: true,
                },
                {
                    name: "featured",
                    label: this.__("Featured"),
                    field: "featured",
                    align: "center",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: this.__("Actions"),
                    align: "center",
                },
            ],
            filterLabels: {
                name: this.__("Name"),
                category: this.__("Category"),
                model: this.__("Model"),
                family: this.__("Family"),
                stock: this.__("Stock"),
                price: this.__("Price"),
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
            return count > 0 ? `Active Filters (${count})` : "Filter Products";
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
        filteredResultsText() {
            if (this.loading) return "Loading...";
            if (this.groups.length === 0)
                return "No products match your filters";
            return `${this.groups.length} of ${this.pagination.rowsNumber} products shown`;
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
        getStockColor(stock) {
            if (stock === null || stock === undefined) return "grey";
            if (stock > 50) return "positive";
            if (stock > 10) return "warning";
            if (stock > 0) return "orange";
            return "negative";
        },

        getStockIcon(stock) {
            if (stock === null || stock === undefined) return "mdi-help-circle";
            if (stock > 50) return "mdi-package-variant";
            if (stock > 10) return "mdi-package-variant-closed";
            if (stock > 0) return "mdi-alert-circle";
            return "mdi-cancel";
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
                page: 1,
            };
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
                    icon: "mdi-alert-circle",
                    position: "top-right",
                    timeout: 3000,
                });
            } finally {
                this.loading = false;
            }
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

.filter-card {
    border: 1px solid rgba(0, 0, 0, 0.08);
}

.filter-header {
    background-color: #f8f9fa;
    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
}

.filter-input :deep(.q-field__control) {
    border-radius: 8px;
}

.stock-filter,
.price-filter {
    border: 1px solid rgba(0, 0, 0, 0.12);
    border-radius: 8px;
    overflow: hidden;
}

.operator-select {
    min-width: 80px;
    border-right: 1px solid rgba(0, 0, 0, 0.12);
}

.operator-select :deep(.q-field__control) {
    border-radius: 0;
}

.filter-badge {
    border-radius: 12px;
    padding: 6px 10px;
    font-size: 0.8rem;
}

.products-card {
    border-radius: var(--border-radius);
    overflow: hidden;
}

.table-header {
    background-color: #f8f9fa;
    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
}

.table-header-row {
    background-color: #f8f9fa;
}

.products-table {
    border: none;
}

.stock-badge {
    border-radius: 12px;
    padding: 6px 10px;
    font-weight: 500;
}

.status-badge {
    border-radius: 12px;
    padding: 6px 10px;
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

.product-card {
    transition: all var(--transition-speed) ease;
    border-radius: 8px;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--card-shadow);
}

.stock-badge-mobile {
    border-radius: 12px;
    padding: 4px 8px;
    font-size: 0.8rem;
}

.pagination-section {
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    background-color: #fafafa;
}

.pagination-control {
    background: white;
    padding: 8px 16px;
    border-radius: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.help-dialog {
    border-radius: var(--border-radius);
    overflow: hidden;
    max-width: 500px;
}

.per-page-select {
    min-width: 120px;
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .header-section {
        border-radius: 0;
        margin-bottom: 16px;
    }

    .filter-card,
    .products-card {
        margin: 0 -8px;
        border-radius: 0;
    }

    .table-header {
        border-radius: 0;
    }
}

@media (max-width: 599px) {
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

    .pagination-control {
        padding: 6px 12px;
    }
}
</style>
