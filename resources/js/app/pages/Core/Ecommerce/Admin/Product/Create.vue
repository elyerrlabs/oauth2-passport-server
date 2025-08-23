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
            :color="COLORS.primary"
            @click="open"
            icon="mdi-plus-circle"
            class="add-product-btn"
            size="sm"
            outline
            rounded
            data-test="add-product-button"
        >
            {{ title }}
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
                <q-card-section class="dialog-header">
                    <div class="header-content">
                        <q-icon
                            :name="ICONS.edit"
                            :color="COLORS.primary"
                            size="md"
                            class="header-icon"
                        />
                        <div class="text-h5 dialog-title">Product Manager</div>
                    </div>
                    <q-btn
                        flat
                        round
                        :color="COLORS.secondary"
                        icon="mdi-close"
                        @click="close"
                        class="close-btn"
                        data-test="close-dialog-button"
                    />
                </q-card-section>

                <q-separator />

                <q-card-section class="scroll dialog-content">
                    <div class="row q-col-gutter-md">
                        <!-- Product Selection -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-select
                                dense
                                outlined
                                :model-value="form.name"
                                use-input
                                hide-selected
                                fill-input
                                input-debounce="300"
                                :options="products"
                                label="Product *"
                                @filter="filterProduct"
                                @input-value="setProduct"
                                :error="!!errors.name"
                                class="form-field"
                                bg-color="white"
                            >
                                <template v-slot:prepend>
                                    <q-icon
                                        :name="ICONS.product"
                                        :color="COLORS.primary"
                                    />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.name" />
                                </template>
                                <template v-slot:no-option>
                                    <q-item>
                                        <q-item-section class="text-grey">
                                            No products found
                                        </q-item-section>
                                    </q-item>
                                </template>
                            </q-select>
                        </div>

                        <!-- Status Toggles -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="toggle-group">
                                <q-toggle
                                    v-model="form.published"
                                    :color="COLORS.success"
                                    label="Published"
                                    class="form-toggle"
                                    left-label
                                />
                                <q-toggle
                                    v-model="form.featured"
                                    :color="COLORS.accent"
                                    label="Featured"
                                    class="form-toggle"
                                    left-label
                                />
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-select
                                dense
                                outlined
                                :model-value="form.category"
                                use-input
                                hide-selected
                                fill-input
                                input-debounce="300"
                                :options="categories"
                                label="Category *"
                                @filter="filterCategories"
                                @input-value="setCategory"
                                :error="!!errors.category"
                                class="form-field"
                                bg-color="white"
                            >
                                <template v-slot:prepend>
                                    <q-icon
                                        :name="ICONS.category"
                                        :color="COLORS.primary"
                                    />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.category" />
                                </template>
                                <template v-slot:no-option>
                                    <q-item>
                                        <q-item-section class="text-grey">
                                            No categories found
                                        </q-item-section>
                                    </q-item>
                                </template>
                            </q-select>
                        </div>

                        <!-- Icon Input -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-input
                                dense
                                outlined
                                v-model="form.icon"
                                label="Icon"
                                :error="!!errors.icon"
                                hint="Material Design icon name"
                                bottom-slots
                                class="form-field"
                                bg-color="white"
                            >
                                <template v-slot:prepend>
                                    <q-icon
                                        :name="ICONS.icon"
                                        :color="COLORS.primary"
                                    />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.icon" />
                                </template>
                                <template v-slot:append>
                                    <q-btn
                                        dense
                                        flat
                                        round
                                        :icon="ICONS.externalLink"
                                        :color="COLORS.primary"
                                        @click="openIconLibrary"
                                        :title="'View icon library'"
                                        class="icon-library-btn"
                                    />
                                </template>
                            </q-input>
                        </div>

                        <!-- Currency and Price -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-select
                                dense
                                outlined
                                v-model="form.currency"
                                :options="currencies"
                                emit-value
                                label="Currency *"
                                :error="!!errors.currency"
                                class="form-field"
                                bg-color="white"
                            >
                                <template v-slot:prepend>
                                    <q-icon
                                        :name="ICONS.currency"
                                        :color="COLORS.primary"
                                    />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.currency" />
                                </template>
                            </q-select>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <q-input
                                v-model="form.price"
                                label="Price *"
                                dense
                                outlined
                                :error="!!errors.price"
                                mask="#.##"
                                fill-mask="0"
                                reverse-fill-mask
                                class="form-field"
                                bg-color="white"
                            >
                                <template v-slot:prepend>
                                    <q-icon
                                        :name="ICONS.price"
                                        :color="COLORS.primary"
                                    />
                                </template>
                                <template v-slot:error>
                                    <v-error :error="errors.price" />
                                </template>
                            </q-input>
                        </div>

                        <!-- Stock Management -->
                        <template v-if="form?.id">
                            <div class="col-12 col-md-4">
                                <q-input
                                    v-model="current_stock"
                                    label="Current Stock"
                                    dense
                                    outlined
                                    type="number"
                                    :min="0"
                                    :error="!!errors.stock"
                                    disable
                                    class="form-field"
                                    bg-color="grey-3"
                                >
                                    <template v-slot:prepend>
                                        <q-icon
                                            :name="ICONS.stock"
                                            :color="COLORS.primary"
                                        />
                                    </template>
                                    <template v-slot:error>
                                        <v-error :error="errors.stock" />
                                    </template>
                                </q-input>
                            </div>

                            <div class="col-12 col-md-4">
                                <q-input
                                    v-model.number="update_stock"
                                    label="Stock Adjustment"
                                    type="number"
                                    :min="0"
                                    dense
                                    outlined
                                    class="form-field"
                                    bg-color="white"
                                >
                                    <template v-slot:prepend>
                                        <q-icon
                                            :name="ICONS.adjustment"
                                            :color="COLORS.primary"
                                        />
                                    </template>
                                </q-input>
                            </div>

                            <div class="col-12 col-md-4">
                                <q-toggle
                                    v-model="decrease"
                                    :color="COLORS.danger"
                                    label="Decrease stock"
                                    class="form-toggle stock-toggle"
                                    left-label
                                />
                            </div>
                        </template>
                        <template v-else>
                            <div class="col-12 col-md-6 col-lg-4">
                                <q-input
                                    v-model="current_stock"
                                    label="Initial Stock *"
                                    dense
                                    outlined
                                    type="number"
                                    :min="0"
                                    :error="!!errors.stock"
                                    class="form-field"
                                    bg-color="white"
                                >
                                    <template v-slot:prepend>
                                        <q-icon
                                            :name="ICONS.stock"
                                            :color="COLORS.primary"
                                        />
                                    </template>
                                    <template v-slot:error>
                                        <v-error :error="errors.stock" />
                                    </template>
                                </q-input>
                            </div>
                        </template>

                        <!-- Descriptions -->
                        <div class="col-12">
                            <div class="section-title">
                                <q-icon
                                    :name="ICONS.description"
                                    :color="COLORS.primary"
                                />
                                <span>Descriptions</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <v-editor
                                v-model="form.short_description"
                                label="Short description"
                                class="editor-field"
                            />
                            <v-error :error="errors.short_description" />
                        </div>

                        <div class="col-12">
                            <v-editor
                                v-model="form.description"
                                label="Description"
                                class="editor-field"
                            />
                            <v-error :error="errors.description" />
                        </div>

                        <div class="col-12">
                            <v-editor
                                v-model="form.specification"
                                label="Specification"
                                class="editor-field"
                            />
                            <v-error :error="errors.specification" />
                        </div>

                        <!-- Attributes & Tags -->
                        <div class="col-12">
                            <div class="section-title">
                                <q-icon
                                    :name="ICONS.attributes"
                                    :color="COLORS.primary"
                                />
                                <span>Attributes & Tags</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <v-attribute
                                v-model="form.attributes"
                                class="attribute-field"
                            />
                            <v-error :error="errors.attributes" />
                        </div>

                        <div class="col-12">
                            <v-tag
                                v-model="form.tags"
                                :max-tags="5"
                                class="tag-field"
                            />
                            <v-error :error="errors.tags" />
                        </div>

                        <!-- Image Upload -->
                        <div class="col-12">
                            <div class="section-title">
                                <q-icon
                                    :name="ICONS.images"
                                    :color="COLORS.primary"
                                />
                                <span>Product Images</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <v-file-uploader
                                type="images"
                                @uploaded="loadImages"
                                class="uploader-field"
                            ></v-file-uploader>
                            <v-error :error="errors.images" />
                        </div>

                        <div class="col-12">
                            <v-gallery
                                :images="images"
                                class="gallery-field"
                            ></v-gallery>
                        </div>
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right" class="dialog-actions">
                    <q-btn
                        flat
                        :color="COLORS.secondary"
                        label="Cancel"
                        @click="close"
                        class="action-btn"
                        data-test="cancel-button"
                    />
                    <q-btn
                        unelevated
                        :color="COLORS.primary"
                        :label="form.id ? 'Update Product' : 'Create Product'"
                        @click="create"
                        class="action-btn submit-btn"
                        data-test="submit-button"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
// Color variables for theming
const COLORS = {
    primary: "primary",
    secondary: "grey-7",
    success: "positive",
    danger: "negative",
    accent: "teal",
    warning: "warning",
};

// Icon constants
const ICONS = {
    edit: "mdi-pencil-outline",
    product: "mdi-package-variant",
    category: "mdi-shape-outline",
    icon: "mdi-emoticon-outline",
    currency: "mdi-currency-usd",
    price: "mdi-cash",
    stock: "mdi-package",
    adjustment: "mdi-plus-minus",
    description: "mdi-text",
    attributes: "mdi-tag-multiple",
    images: "mdi-image-multiple",
    externalLink: "mdi-open-in-new",
};

export default {
    emits: ["created"],

    props: {
        title: {
            required: false,
            type: String,
            default: "Add New Product",
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
                name: "",
                short_description: "",
                description: "",
                specification: "",
                category: null,
                price: "",
                stock: "",
                images: [],
                icon: "",
                published: false,
                featured: false,
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
            COLORS,
            ICONS,
        };
    },

    watch: {
        update_stock(value) {
            this.updateStock(value);
        },

        decrease(value) {
            this.updateStock(this.update_stock, value);
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
        updateStock(value, isDecrease = this.decrease) {
            let stock = Number(this.form.stock);

            if (isDecrease) {
                stock -= value;
            } else {
                stock += value;
            }

            this.current_stock = stock > 0 ? stock : 0;
        },

        close() {
            this.dialog = false;
            this.resetForm();
            this.errors = {};
        },

        resetForm() {
            this.form = {
                name: "",
                short_description: "",
                description: "",
                specification: "",
                category: null,
                price: "",
                stock: 0,
                icon: "",
                published: false,
                featured: false,
                images: [],
                currency: "",
                attributes: [],
                tags: [],
            };
            this.update_stock = 0;
            this.current_stock = 0;
            this.decrease = false;
        },

        getTags(item) {
            this.form.tags = item;
        },

        getAttributes(item) {
            this.form.attributes = item;
        },

        openIconLibrary() {
            window.open("https://pictogrammers.com/library/mdi/", "_blank");
        },

        open() {
            this.dialog = true;
            this.resetForm();
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
            this.form.category = product?.category?.name;
            this.form.currency = product.currency;
            this.form.price = product.price;
            this.form.stock = product.stock;
            this.form.icon = product?.icon?.icon;
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
            } catch (error) {
                console.error("Failed to fetch currencies:", error);
            }
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
                        value: cat.name,
                        ...cat,
                    }));
                }
            } catch (e) {
                console.error("Failed to fetch categories:", e);
                this.showNotification("Failed to load categories", "negative");
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
            const category = this.categories.find((item) => item.label == val);
            if (category && category.icon) {
                this.form.icon = category.icon.icon;
            }
        },

        async create() {
            const payload = new FormData();

            // Simple fields
            payload.append("name", this.form.name);
            payload.append("short_description", this.form.short_description);
            payload.append("description", this.form.description);
            payload.append("specification", this.form.specification);
            payload.append("stock", this.current_stock);
            payload.append("category", this.form.category);
            payload.append("currency", this.form.currency);
            payload.append("price", this.form.price);
            payload.append("icon", this.form.icon);
            payload.append("published", this.form.published ? 1 : 0);
            payload.append("featured", this.form.featured ? 1 : 0);

            // Attributes
            if (this.form.attributes?.length > 0) {
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
            }

            // Tags
            if (this.form.tags?.length > 0) {
                this.form.tags.forEach((tag, index) => {
                    payload.append(`tags[${index}][name]`, tag.name);
                });
            }

            // Images
            if (this.form.images?.length > 0) {
                this.form.images.forEach((file) => {
                    payload.append("images[]", file);
                });
            }

            try {
                const url = this.form.id
                    ? `${this.$page.props.routes["products"]}/${this.form.id}`
                    : this.$page.props.routes["products"];

                const method = this.form.id ? "put" : "post";

                const res = await this.$server[method](url, payload, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (res.status === 201 || res.status === 200) {
                    const message = this.form.id
                        ? "Product successfully updated."
                        : "Product successfully created.";

                    this.showNotification(message, "positive");
                    this.close();
                    this.$emit("created", true);
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                } else {
                    this.showNotification(
                        "An error occurred while saving the product",
                        "negative"
                    );
                }
            }
        },

        showNotification(message, type) {
            this.$q.notify({
                type,
                message,
                timeout: 3000,
                icon: type === "positive" ? "check_circle" : "error",
                position: "top-right",
            });
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

                        if (selected_product) {
                            this.selectProduct(selected_product);
                        }
                    }
                }
            } catch (e) {
                console.error("Failed to fetch products:", e);
                this.showNotification("Failed to load products", "negative");
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
        overflow: hidden;

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
            max-height: calc(100vh - 200px);

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

            .section-title {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 16px;
                padding-bottom: 8px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                font-weight: 500;
                color: var(--text-color);
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
    .add-product-btn {
        width: 100%;
        justify-content: center;
    }

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
