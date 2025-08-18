<template>
    <div>
        <q-btn
            size="sm"
            outline
            color="positive"
            @click="open"
            icon="mdi-plus-circle"
        >
            {{ title }}
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            :maximized="true"
            transition-show="slide-up"
            transition-hide="slide-down"
        >
            <q-card>
                <q-card-section class="text-center">
                    <h6>
                        <q-icon
                            name="mdi-pencil-outline"
                            size="lg"
                            round
                            color="primary"
                        />
                        Product manager
                    </h6>
                </q-card-section>

                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <q-select
                            class="col-12 col-md-4 col-lg-4 col-xl-3"
                            dense
                            outlined
                            :model-value="form.name"
                            use-input
                            hide-selected
                            fill-input
                            input-debounce="0"
                            :options="products"
                            label="Product"
                            @filter="filterProduct"
                            @input-value="setProduct"
                            :error="!!errors.name"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.name" />
                            </template>
                            <template v-slot:no-option>
                                <q-item>
                                    <q-item-section>
                                        No results
                                    </q-item-section>
                                </q-item>
                            </template>
                        </q-select>

                        <q-checkbox
                            class="col-12 col-md-4 col-lg-4 col-xl-3"
                            v-model="form.published"
                            :val="false"
                            label="Published"
                        />

                        <q-checkbox
                            class="col-12 col-md-4 col-lg-4 col-xl-3"
                            v-model="form.featured"
                            :val="false"
                            label="Featured"
                            color="teal"
                        />

                        <q-select
                            class="col-12 col-md-6 col-lg-4 col-xl-3"
                            dense
                            outlined
                            :model-value="form.category"
                            use-input
                            hide-selected
                            fill-input
                            input-debounce="0"
                            :options="categories"
                            label="Category"
                            @filter="filterCategories"
                            @input-value="setCategory"
                            :error="!!errors.category"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.category" />
                            </template>
                            <template v-slot:no-option>
                                <q-item>
                                    <q-item-section>
                                        No results
                                    </q-item-section>
                                </q-item>
                            </template>
                        </q-select>

                        <q-input
                            class="col-12 col-md-6 col-lg-4 col-xl-3"
                            dense
                            outlined
                            v-model="form.icon"
                            label="Icon"
                            :error="!!errors.icon"
                            hint="Choose a Material Design icon name"
                            bottom-slots
                        >
                            <template v-slot:error>
                                <v-error :error="errors.icon" />
                            </template>

                            <template v-slot:append>
                                <q-btn
                                    dense
                                    flat
                                    round
                                    icon="mdi-link-variant"
                                    color="primary"
                                    @click="openIconLibrary"
                                    :title="'View icon library'"
                                />
                            </template>
                        </q-input>

                        <q-select
                            class="col-12 col-md-6 col-lg-4 col-xl-3"
                            dense
                            v-model="form.currency"
                            :options="currencies"
                            emit-value
                            label="Currency"
                            :error="!!errors.currency"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.currency" />
                            </template>
                        </q-select>

                        <q-input
                            class="col-12 col-md-6 col-lg-4 col-xl-3"
                            v-model="form.price"
                            label="Price"
                            dense
                            :error="!!errors.price"
                            mask="#.##"
                            fill-mask="0"
                            reverse-fill-mask
                        >
                            <template v-slot:error>
                                <v-error :error="errors.price" />
                            </template>
                        </q-input>

                        <q-input
                            class="col-12 col-md-4 col-lg-4 col-xl-3"
                            v-model="current_stock"
                            label="Stock"
                            dense
                            type="number"
                            :min="0"
                            :error="!!errors.stock"
                            :disable="form?.id ? true : false"
                        >
                            <template v-slot:error>
                                <v-error :error="errors.stock" />
                            </template>
                        </q-input>

                        <q-input
                            class="col-12 col-md-4 col-xl-3"
                            v-model.number="update_stock"
                            label="Update Stock"
                            type="number"
                            :min="0"
                            dense
                            v-if="form?.id ? true : false"
                        />

                        <q-checkbox
                            v-model="decrease"
                            label="Decrease stock"
                            class="col-12 col-md-4 col-xl-3"
                            v-if="form?.id ? true : false"
                        />

                        <div class="col-12">
                            <v-editor
                                v-model="form.short_description"
                                label="Short description"
                            />
                            <v-error :error="errors.short_description" />
                        </div>

                        <div class="col-12">
                            <v-editor
                                v-model="form.description"
                                label="Description"
                            />
                            <v-error :error="errors.description" />
                        </div>

                        <div class="col-12">
                            <v-editor
                                v-model="form.specification"
                                label="Specification"
                            />
                            <v-error :error="errors.specification" />
                        </div>

                        <div class="col-12">
                            <v-attribute v-model="form.attributes" />
                            <v-error :error="errors.attributes" />
                        </div>

                        <div class="col-12">
                            <v-tag v-model="form.tags" :max-tags="5" />
                            <v-error :error="errors.tags" />
                        </div>

                        <div class="col-12">
                            <v-file-uploader
                                type="images"
                                @uploaded="loadImages"
                            ></v-file-uploader>
                            <v-error :error="errors.images" />
                        </div>

                        <div class="col-12">
                            <v-gallery :images="images"></v-gallery>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn
                        outline
                        color="positive"
                        label="Accept"
                        @click="create"
                    />
                    <q-btn
                        outline
                        color="secondary"
                        label="Close"
                        @click="close"
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
            this.errors = {};
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
            this.form = {
                name: "",
                short_description: "",
                description: "",
                specification: "",
                category: null,
                currency: "",
                price: "",
                stock: 0,
                icon: "",
                published: false,
                featured: false,
                images: [],
                attributes: [],
                tags: [],
            };

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
                    "/api/public/payments/currencies"
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
                        value: cat.name,
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
            this.form.icon = this.categories.find(
                (item) => item.label == val
            )?.icon?.icon;
        },

        async create() {
            const payload = new FormData();

            // Campos simples
            payload.append("name", this.form.name);
            payload.append("short_description", this.form.short_description);
            payload.append("description", this.form.description);
            payload.append("specification", this.form.specification);
            payload.append("stock", this.current_stock);
            payload.append("category", this.form.category);
            payload.append("currency", this.form.currency);
            payload.append("price", this.form.price);
            payload.append("icon", this.form.icon);
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
