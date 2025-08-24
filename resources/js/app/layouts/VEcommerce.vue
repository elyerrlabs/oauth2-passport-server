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
    <q-layout view="hHh Lpr lff">
        <!-- Header -->
        <q-header elevated>
            <q-toolbar class="q-px-md q-py-sm">
                <!-- App Name and Categories - Left Side -->
                <div class="row items-center q-gutter-sm">
                    <q-btn
                        class="text-white"
                        no-caps
                        flat
                        dense
                        outline
                        round
                        icon="store"
                        size="lg"
                        @click="goTo($page.props.routes['dashboard'])"
                    />
                    <q-toolbar-title class="text-weight-bold">
                        {{ app_name }}
                    </q-toolbar-title>
                    <!-- Categories Dropdown -->
                    <q-btn
                        class="text-white"
                        no-caps
                        dense
                        flat
                        outline
                        size="lg"
                        icon="mdi-view-grid-outline"
                    >
                        <q-tooltip>Categories</q-tooltip>
                        <q-menu
                            class="categories-menu"
                            anchor="bottom left"
                            self="top left"
                        >
                            <q-card style="width: 500px; max-width: 90vw">
                                <q-toolbar class="bg-primary text-white">
                                    <q-toolbar-title
                                        >Categories</q-toolbar-title
                                    >
                                    <q-btn
                                        flat
                                        dense
                                        icon="close"
                                        v-close-popup
                                    />
                                </q-toolbar>
                                <q-scroll-area style="height: 400px">
                                    <q-list>
                                        <q-item
                                            v-for="category in categories"
                                            :key="category.id"
                                            clickable
                                            @click="
                                                goTo(category?.links?.index)
                                            "
                                            class="q-pa-sm category-item"
                                        >
                                            <q-item-section avatar>
                                                <q-icon
                                                    :name="category?.icon?.icon"
                                                    size="md"
                                                    color="primary"
                                                />
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label
                                                    class="text-weight-bold"
                                                >
                                                    {{ category.name }}
                                                </q-item-label>
                                                <q-item-label caption lines="2">
                                                    {{ category.description }}
                                                </q-item-label>
                                            </q-item-section>
                                            <q-item-section
                                                side
                                                v-if="
                                                    category.images &&
                                                    category.images.length > 0
                                                "
                                            >
                                                <div
                                                    class="row no-wrap q-gutter-xs"
                                                >
                                                    <q-img
                                                        v-for="(
                                                            image, idx
                                                        ) in category.images.slice(
                                                            0,
                                                            3
                                                        )"
                                                        :key="idx"
                                                        :src="image.url"
                                                        style="
                                                            width: 60px;
                                                            height: 60px;
                                                        "
                                                        class="rounded-borders"
                                                    >
                                                        <template
                                                            v-slot:loading
                                                        >
                                                            <q-spinner
                                                                color="primary"
                                                            />
                                                        </template>
                                                    </q-img>
                                                    <div
                                                        v-if="
                                                            category.images
                                                                .length > 3
                                                        "
                                                        class="flex flex-center bg-grey-3 rounded-borders"
                                                        style="
                                                            width: 60px;
                                                            height: 60px;
                                                        "
                                                    >
                                                        <q-badge
                                                            color="primary"
                                                        >
                                                            +{{
                                                                category.images
                                                                    .length - 3
                                                            }}
                                                        </q-badge>
                                                    </div>
                                                </div>
                                            </q-item-section>
                                        </q-item>
                                    </q-list>
                                </q-scroll-area>
                                <q-separator />
                                <q-card-actions align="right">
                                    <q-btn
                                        flat
                                        label="View All Categories"
                                        color="primary"
                                    />
                                </q-card-actions>
                            </q-card>
                        </q-menu>
                    </q-btn>
                </div>

                <q-space />

                <!-- Search Input - Center -->
                <q-select
                    class="bg-white rounded-borders size no-border"
                    outlined
                    filled
                    v-model="selectedProduct"
                    use-input
                    hide-selected
                    fill-input
                    input-debounce="300"
                    :options="filteredProducts"
                    @filter="filterProducts"
                    @keyup.enter="handleEnterKey"
                    @click="loadProducts"
                    option-value="name"
                    option-label="name"
                    behavior="dialog"
                    hide-dropdown-icon
                    label="Search products..."
                    clearable
                >
                    <template v-slot:prepend>
                        <q-icon name="search" />
                    </template>
                    <template v-slot:append>
                        <q-icon name="mdi-shopping-search-outline" />
                    </template>
                    <template v-slot:no-option>
                        <q-item>
                            <q-item-section class="text-grey">
                                No products found
                            </q-item-section>
                        </q-item>
                    </template>
                    <template v-slot:option="scope">
                        <q-item
                            v-bind="scope.itemProps"
                            @click="selectProduct(scope.opt)"
                        >
                            <q-item-section avatar>
                                <q-img
                                    :src="scope.opt.images?.[0]?.url"
                                    style="width: 50px; height: 50px"
                                    class="rounded-borders"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{
                                    scope.opt.name
                                }}</q-item-label>
                                <q-item-label caption>
                                    {{ scope.opt.symbol }}
                                    {{ scope.opt.format_price }}
                                </q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                <q-btn
                                    flat
                                    dense
                                    icon="chevron_right"
                                    @click.stop="goToProduct(scope.opt)"
                                />
                            </q-item-section>
                        </q-item>
                    </template>
                </q-select>

                <q-space />

                <!-- Right Icons -->
                <div class="row items-center q-gutter-sm">
                    <v-theme />
                    <v-notification />
                    <v-profile />
                </div>
            </q-toolbar>
        </q-header>

        <!-- Main Content -->
        <q-page-container>
            <q-page>
                <slot />
            </q-page>
        </q-page-container>

        <!-- Footer -->
        <q-footer elevated>
            <q-toolbar>
                <q-toolbar-title class="text-center">
                    <div class="row justify-center q-gutter-xl">
                        <div class="col-auto">
                            <div class="text-h6 q-mb-sm">Customer Service</div>
                            <div class="text-caption">
                                <div>Contact Us</div>
                                <div>FAQs</div>
                                <div>Shipping Policy</div>
                                <div>Returns & Refunds</div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="text-h6 q-mb-sm">About Us</div>
                            <div class="text-caption">
                                <div>Our Story</div>
                                <div>Careers</div>
                                <div>Privacy Policy</div>
                                <div>Terms & Conditions</div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="text-h6 q-mb-sm">Connect With Us</div>
                            <div class="row q-gutter-sm">
                                <q-btn
                                    round
                                    dense
                                    flat
                                    icon="facebook"
                                    color="blue"
                                />
                                <q-btn
                                    round
                                    dense
                                    flat
                                    icon="twitter"
                                    color="light-blue"
                                />
                                <q-btn
                                    round
                                    dense
                                    flat
                                    icon="instagram"
                                    color="purple"
                                />
                                <q-btn
                                    round
                                    dense
                                    flat
                                    icon="youtube"
                                    color="red"
                                />
                            </div>
                            <div class="q-mt-sm text-caption">
                                <div>Phone: +1 (123) 456-7890</div>
                                <div>Email: info@megashop.com</div>
                            </div>
                        </div>
                    </div>
                    <q-separator color="grey-6" class="q-my-md" />
                    <div class="text-caption">
                        &copy; {{ new Date().getFullYear() }}
                        {{ $page.props.org_name }}
                    </div>
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>
    </q-layout>
</template>

<script>
import { debounce } from "quasar";

export default {
    data() {
        return {
            app_name: "",
            searchQuery: "",
            selectedProduct: null,
            email: "",
            categories: [],
            filteredProducts: [],
            allProducts: [],
            searchDebounce: null,
        };
    },

    created() {
        this.getCategories();
        this.app_name = this.$page.props.app_name;
        this.searchDebounce = debounce(this.performSearch, 500);
    },

    methods: {
        goTo(link) {
            if (link) {
                window.location.href = link;
            }
        },

        handleEnterKey() {
            if (this.selectedProduct && this.selectedProduct.links?.show) {
                this.goTo(this.selectedProduct.links.show);
            } else if (this.searchQuery) {
                this.goToSearch();
            }
        },

        selectProduct(product) {
            this.selectedProduct = product;
            this.goTo(product.links?.show);
        },

        goToProduct(product) {
            this.goTo(product.links?.show);
        },

        goToSearch() {
            if (this.searchQuery) {
                this.goTo(
                    `${
                        this.$page.props.routes["search"]
                    }?q=${encodeURIComponent(this.searchQuery)}`
                );
            } else {
                this.goTo(this.$page.props.routes["search"]);
            }
        },

        async getCategories() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["categories"]
                );
                if (res.status == 200) {
                    this.categories = res.data.data;
                }
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },

        async performSearch(query) {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["search"],
                    {
                        params: {
                            q: query,
                            limit: 10,
                        },
                    }
                );

                if (res.status == 200) {
                    this.allProducts = res.data.data;
                }
            } catch (error) {
                console.error("Error searching products:", error);
                this.allProducts = [];
                this.filteredProducts = [];
            }
        },

        async loadProducts(val) {
            const value = val?.target ? "" : val;

            this.searchQuery = value;

            await this.performSearch(value);

            this.filteredProducts = this.allProducts;
        },

        async filterProducts(val, update, abort) {
            const value = val?.target ? "" : val;

            this.searchQuery = value;

            await this.performSearch(value);

            update(() => {
                this.filteredProducts = this.allProducts;
            });
        },
    },
};
</script>

<style scoped>
.size {
    min-width: 50%;
}
.categories-menu {
    .category-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: background 0.3s;

        &:hover {
            background: rgba(0, 0, 0, 0.03);
        }
    }

    .q-img {
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;

        &:hover {
            transform: scale(1.05);
            z-index: 1;
        }
    }
}

/* Estilos responsivos */
@media (max-width: 1023px) {
    .q-toolbar {
        flex-wrap: wrap;
    }

    .q-select {
        order: 1;
        width: 100%;
        margin: 8px 0;
    }
}

@media (max-width: 599px) {
    .q-toolbar-title {
        font-size: 1.1rem;
    }
}
</style>
