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
    <div>
        <q-btn
            size="sm"
            rounded
            outline
            color="positive"
            @click="open"
            icon="mdi-plus-circle"
            :class="{ 'text-white': !searchable }"
        >
            {{ __(title) }}
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            :maximized="$q.screen.lt.md"
            transition-show="slide-up"
            transition-hide="slide-down"
            class="product-manager-dialog"
            full-width
        >
            <q-card class="product-manager-card">
                <q-card-section class="dialog-header text-center">
                    <div class="header-content">
                        <q-icon
                            name="mdi-pencil-outline"
                            size="lg"
                            color="primary"
                            class="header-icon"
                        />
                        <h6 class="dialog-title">
                            {{ __("Product Manager") }}
                        </h6>
                    </div>
                    <q-btn
                        outline
                        icon="mdi-trash-can-outline"
                        @click="clean"
                        rounded
                        color="warning"
                    >
                        {{ __("Clean form") }}
                    </q-btn>
                    <q-btn
                        flat
                        round
                        icon="mdi-close"
                        color="grey-7"
                        @click="close"
                        class="close-btn"
                    />
                </q-card-section>

                <q-separator />

                <q-card-section class="dialog-content">
                    <div class="row q-col-gutter-md">
                        <q-select
                            class="col-12 col-md-4 col-lg-4 col-xl-3 form-field"
                            dense
                            outlined
                            :model-value="form.name"
                            use-input
                            hide-selected
                            fill-input
                            input-debounce="0"
                            :options="products"
                            :label="__('Product')"
                            @filter="filterProduct"
                            @input-value="setProduct"
                            :error="!!errors.name"
                        >
                            <template v-slot:prepend>
                                <q-icon
                                    name="mdi-package-variant"
                                    color="primary"
                                />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.name" />
                            </template>
                            <template v-slot:no-option>
                                <q-item>
                                    <q-item-section class="text-grey">
                                        {{ __("No results found") }}
                                    </q-item-section>
                                </q-item>
                            </template>
                        </q-select>

                        <div
                            class="col-12 col-md-4 col-lg-4 col-xl-3 toggle-group"
                        >
                            <q-checkbox
                                v-model="form.published"
                                :val="false"
                                :label="__('Published')"
                                color="positive"
                                class="form-toggle"
                            />
                        </div>
                        <div
                            class="col-12 col-md-4 col-lg-4 col-xl-3 toggle-group"
                        >
                            <q-checkbox
                                v-model="form.featured"
                                :val="false"
                                :label="__('Featured')"
                                color="teal"
                                class="form-toggle"
                            />
                        </div>

                        <q-select
                            v-model="form.category"
                            class="col-12 col-md-6 col-lg-4 col-xl-3 form-field"
                            dense
                            outlined
                            use-input
                            hide-selected
                            fill-input
                            emit-value
                            map-options
                            input-debounce="0"
                            :options="categories"
                            :label="__('Category')"
                            @filter="filterCategories"
                            :error="!!errors.category"
                        >
                            <template v-slot:prepend>
                                <q-icon
                                    name="mdi-shape-outline"
                                    color="primary"
                                />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.category" />
                            </template>
                            <template v-slot:no-option>
                                <q-item>
                                    <q-item-section class="text-grey">
                                        {{ __("No results found") }}
                                    </q-item-section>
                                </q-item>
                            </template>
                        </q-select>

                        <q-select
                            class="col-12 col-md-6 col-lg-4 col-xl-3 form-field"
                            dense
                            v-model="form.currency"
                            :options="currencies"
                            emit-value
                            :label="__('Currency')"
                            :error="!!errors.currency"
                        >
                            <template v-slot:prepend>
                                <q-icon
                                    name="mdi-currency-usd"
                                    color="primary"
                                />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.currency" />
                            </template>
                        </q-select>

                        <q-input
                            class="col-12 col-md-6 col-lg-4 col-xl-3 form-field"
                            v-model="form.price"
                            :label="__('Price')"
                            dense
                            :error="!!errors.price"
                            mask="#.##"
                            fill-mask="0"
                            reverse-fill-mask
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-cash" color="primary" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.price" />
                            </template>
                        </q-input>

                        <q-input
                            class="col-12 col-md-4 col-lg-4 col-xl-3 form-field"
                            v-model="current_stock"
                            :label="__('Stock')"
                            dense
                            type="number"
                            :min="0"
                            :error="!!errors.stock"
                            :disable="form?.id ? true : false"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-package" color="primary" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.stock" />
                            </template>
                        </q-input>

                        <q-input
                            class="col-12 col-md-4 col-xl-3 form-field"
                            v-model.number="update_stock"
                            :label="__('Update Stock')"
                            type="number"
                            :min="0"
                            dense
                            v-if="form?.id ? true : false"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-plus-minus" color="primary" />
                            </template>
                        </q-input>

                        <q-checkbox
                            v-model="decrease"
                            :label="__('Decrease stock')"
                            class="col-12 col-md-4 col-xl-3 form-toggle"
                            color="negative"
                            v-if="form?.id ? true : false"
                        />

                        <div class="col-12 section-divider">
                            <q-separator />
                            <div class="section-title">
                                <q-icon name="mdi-text" color="primary" />
                                <span>{{ __("Descriptions") }}</span>
                            </div>
                        </div>

                        <div class="col-12 editor-field">
                            <v-editor
                                v-model="form.short_description"
                                :label="__('Short description')"
                            />
                            <v-error :error="errors.short_description" />
                        </div>

                        <div class="col-12 editor-field">
                            <v-editor
                                v-model="form.description"
                                :label="__('Description')"
                            />
                            <v-error :error="errors.description" />
                        </div>

                        <div class="col-12 editor-field">
                            <v-editor
                                v-model="form.specification"
                                :label="__('Specification')"
                            />
                            <v-error :error="errors.specification" />
                        </div>

                        <div class="col-12 section-divider">
                            <q-separator />
                            <div class="section-title">
                                <q-icon
                                    name="mdi-tag-multiple"
                                    color="primary"
                                />
                                <span>{{ __("Attributes & Tags") }}</span>
                            </div>
                        </div>

                        <div class="col-12 attribute-field">
                            <v-attribute v-model="form.attributes" />
                            <v-error :error="errors.attributes" />
                        </div>

                        <div class="col-12 tag-field">
                            <v-tag v-model="form.tags" :max-tags="5" />
                            <v-error :error="errors.tags" />
                        </div>

                        <div class="col-12 section-divider">
                            <q-separator />
                            <div class="section-title">
                                <q-icon
                                    name="mdi-image-multiple"
                                    color="primary"
                                />
                                <span>{{ __("Product Images") }}</span>
                            </div>
                        </div>

                        <div class="col-12 uploader-field">
                            <v-file-uploader
                                type="images"
                                @uploaded="loadImages"
                            ></v-file-uploader>
                            <v-error :error="errors.images" />
                        </div>

                        <div class="col-12 gallery-field">
                            <v-gallery :images="images"></v-gallery>
                        </div>
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right" class="dialog-actions">
                    <q-btn
                        outline
                        color="grey-7"
                        :label="__('Close')"
                        @click="close"
                        class="action-btn"
                    />
                    <q-btn
                        unelevated
                        color="positive"
                        :label="__('Accept')"
                        @click="create"
                        class="action-btn submit-btn"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["created"],

    props: {
        title: {
            required: false,
            type: String,
            default: "Add new product",
        },

        searchable: {
            required: false,
            type: [String, null],
            default: null,
        },
    },

    data() {
        return {
            dialog: false,
            uploaderRef: null,
            form: {
                id: "",
                name: "",
                short_description: "",
                description: "",
                specification: "",
                category: null,
                price: "",
                stock: 0,
                images: [],
                icon: "",
                published: false,
                attributes: [],
                tags: [],
                currency: "",
            },
            currencies: [],
            billing_periods: [],
            current_tags: [],
            current_attributes: [],
            update_stock: 0,
            current_stock: 0,
            decrease: false,
            errors: {},
            categories: [],
            filtered_categories: [],
            products: [],
            filtered_products: [],
            images: [],
        };
    },

    watch: {
        update_stock(value) {
            var stock = Number(this.form.stock);
            if (this.decrease) {
                stock -= value;
            } else {
                stock += value;
            }
            this.current_stock = stock > 0 ? stock : 0;
        },

        decrease(value) {
            var stock = Number(this.form.stock);
            if (value) {
                stock -= this.update_stock;
            } else {
                stock += this.update_stock;
            }
            this.current_stock = stock > 0 ? stock : 0;
        },

        "form.name"(val) {
            const selected_product = this.products.find(
                (item) => item.name === val
            );

            if (selected_product) {
                this.selectProduct(selected_product);
            }
        },
    },

    methods: {
        close() {
            this.dialog = false;
            this.clean();
            this.errors = {};
        },

        getTags(item) {
            this.form.tags = item;
        },

        getAttributes(item) {
            this.form.attributes = item;
        },

        clean() {
            this.form = {
                id: "",
                name: "",
                short_description: "",
                description: "",
                specification: "",
                category: null,
                currency: "",
                price: "",
                stock: 0,
                published: false,
                featured: false,
                images: [],
                attributes: [],
                tags: [],
            };
            this.update_stock = 0;
            this.current_stock = 0;
            this.images = [];
            this.errors = {};
        },

        open() {
            this.dialog = true;
            this.clean();
            this.getCategories();
            this.getCurrencies();

            if (this.searchable) {
                this.getProducts(this.searchable);
            } else {
                this.getProducts();
            }
        },

        selectProduct(product) {
            this.form.id = product.id;
            this.form.name = product.name;
            this.form.short_description = product.short_description;
            this.form.description = product.description;
            this.form.specification = product.specification;
            this.form.category = product?.category?.id;
            this.form.currency = product.currency;
            this.form.price = product.price;
            this.form.stock = product.stock;
            this.form.published = product.published;
            this.form.featured = product.featured;
            this.images = product?.images ?? [];
            this.form.attributes = product?.attributes ?? [];
            this.form.tags = product?.tags ?? [];
            this.current_stock = this.form.stock;
        },

        async getCurrencies() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.currencies
                );

                if (res.status == 200) {
                    this.currencies = res.data.data.map((item) => ({
                        label: `${item.code} - ${item.name}`,
                        value: item.code,
                    }));
                }
            } catch (error) {}
        },

        loadImages(files) {
            this.form.images = files;
        },

        // ------ Categories -------//
        async getCategories(name = "") {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["categories"],
                    {
                        params: { name },
                    }
                );

                if (res.status === 200) {
                    this.categories = res.data.data.map((cat) => ({
                        label: cat.name,
                        value: cat.id,
                        ...cat,
                    }));
                }
            } catch (e) {
                console.error(e?.response);
            }
        },

        async filterCategories(val, update, abort) {
            await this.getCategories(val);

            update(() => {
                this.filtered_categories = this.categories.filter(
                    (item) => item.label == val
                );
            });
        },

        setCategory(val) {
            this.form.category = val;
        },

        async create() {
            const payload = new FormData();

            // Campos simples
            payload.append("id", this.form.id);
            payload.append("name", this.form.name);
            payload.append("short_description", this.form.short_description);
            payload.append("description", this.form.description);
            payload.append("specification", this.form.specification);
            payload.append("stock", this.current_stock);
            payload.append("category", this.form.category);
            payload.append("currency", this.form.currency);
            payload.append("price", this.form.price);
            payload.append("published", this.form.published);
            payload.append("featured", this.form.featured);

            if (this.form?.attributes?.length > 0) {
                this.form.attributes.forEach((tag, index) => {
                    this.form.attributes.forEach((attribute, index) => {
                        payload.append(
                            `attributes[${index}][name]`,
                            attribute.name
                        );
                        payload.append(
                            `attributes[${index}][type]`,
                            attribute.type
                        );
                        payload.append(
                            `attributes[${index}][value]`,
                            attribute.value
                        );
                        payload.append(
                            `attributes[${index}][widget]`,
                            attribute.widget
                        );
                        payload.append(
                            `attributes[${index}][multiple]`,
                            attribute.multiple
                        );
                        payload.append(
                            `attributes[${index}][stock]`,
                            attribute.stock
                        );
                    });
                });
            }

            if (this.form?.tags?.length > 0) {
                this.form.tags.forEach((tag, index) => {
                    payload.append(`tags[${index}][name]`, tag.name);
                });
            }

            if (this.form?.images?.length > 0) {
                this.form.images.forEach((file) => {
                    payload.append("images[]", file);
                });
            }

            try {
                const res = await this.$server.post(
                    this.$page.props.routes["products"],
                    payload,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (res.status === 201) {
                    this.$q.notify({
                        type: "positive",
                        message: "Product successfully created.",
                        timeout: 3000,
                        icon: "check_circle",
                    });
                    this.close();
                    this.$emit("created", true);
                } else if (res.status === 200) {
                    this.$q.notify({
                        type: "info",
                        message: "Product inventory successfully updated.",
                        timeout: 3000,
                        icon: "update",
                    });
                    this.close();
                    this.$emit("created", true);
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        type: "warning",
                        message: "Please check the input fields",
                        timeout: 3000,
                        icon: "update",
                    });
                }

                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },

        // ------- Product ---------//

        async getProducts(name = "") {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes["products"],
                    {
                        params: { name },
                    }
                );

                if (res.status === 200) {
                    this.products = res.data.data.map((product) => ({
                        label: product.name,
                        value: product.name,
                        ...product,
                    }));

                    if (this.searchable) {
                        const selected_product = this.products.find(
                            (item) => item.name == this.searchable
                        );

                        this.selectProduct(selected_product);
                    }
                }
            } catch (e) {
                console.error(e?.response);
            }
        },

        async filterProduct(val, update, abort) {
            await this.getProducts(val);

            update(() => {
                this.filtered_products = this.products.filter(
                    (item) => item.label == val
                );
            });
        },

        setProduct(val) {
            this.form.name = val;
        },
    },
};
</script>

<style lang="scss" scoped>
// CSS variables for theming
:root {
    --primary-color: #1976d2;
    --primary-light: #e3f2fd;
    --secondary-color: #9e9e9e;
    --success-color: #4caf50;
    --danger-color: #f44336;
    --accent-color: #009688;
    --warning-color: #ff9800;
    --text-color: #333;
    --light-bg: #f5f5f5;
    --border-radius: 8px;
    --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

.add-product-btn {
    font-weight: 500;
    border-radius: 6px;
    padding: 8px 16px;
    transition: var(--transition);

    &:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow);
    }
}

.product-manager-dialog {
    .product-manager-card {
        border-radius: var(--border-radius);

        .dialog-header {
            padding: 16px 24px;
            background-color: var(--primary-light);
            display: flex;
            justify-content: space-between;
            align-items: center;

            .header-content {
                display: flex;
                align-items: center;
                gap: 12px;

                .header-icon {
                    background: white;
                    padding: 8px;
                    border-radius: 50%;
                    box-shadow: var(--box-shadow);
                }

                .dialog-title {
                    margin: 0;
                    font-weight: 600;
                    color: var(--text-color);
                }
            }

            .close-btn {
                font-size: 24px;
            }
        }

        .dialog-content {
            padding: 24px;

            .form-field,
            .editor-field,
            .attribute-field,
            .tag-field,
            .uploader-field,
            .gallery-field {
                margin-bottom: 16px;
            }

            .toggle-group {
                background: white;
                padding: 16px;
                border-radius: var(--border-radius);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);

                .form-toggle {
                    margin-bottom: 8px;

                    &:last-child {
                        margin-bottom: 0;
                    }
                }
            }

            .section-divider {
                margin: 24px 0 16px;
                position: relative;

                .section-title {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    position: absolute;
                    top: -10px;
                    left: 16px;
                    background: white;
                    padding: 0 8px;
                    font-weight: 500;
                    color: var(--text-color);
                }
            }

            .icon-library-btn {
                transition: var(--transition);

                &:hover {
                    transform: scale(1.1);
                }
            }
        }

        .dialog-actions {
            padding: 16px 24px;
            background-color: var(--light-bg);

            .action-btn {
                border-radius: 6px;
                padding: 8px 20px;
                font-weight: 500;
                transition: var(--transition);

                &.submit-btn {
                    &:hover {
                        transform: translateY(-2px);
                        box-shadow: var(--box-shadow);
                    }
                }
            }
        }
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .product-manager-dialog .product-manager-card {
        .dialog-header {
            padding: 12px 16px;

            .header-content .dialog-title {
                font-size: 1.25rem;
            }
        }

        .dialog-content {
            padding: 16px;
        }

        .dialog-actions {
            padding: 12px 16px;

            .action-btn {
                padding: 6px 16px;
            }
        }
    }
}

@media (max-width: 599px) {
    .product-manager-dialog .product-manager-card {
        .dialog-header {
            flex-direction: column;
            gap: 12px;
            text-align: center;

            .header-content {
                flex-direction: column;
                gap: 8px;
            }
        }

        .dialog-actions {
            flex-direction: column-reverse;
            gap: 12px;
            .action-btn {
                width: 100%;
            }
        }
    }
}
</style>
