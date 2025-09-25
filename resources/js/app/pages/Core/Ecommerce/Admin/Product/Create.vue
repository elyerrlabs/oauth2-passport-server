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
        <div
            class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-6 px-4 sm:px-6 lg:px-3"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Page Header -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8"
                >
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm"
                        >
                            <i class="fas fa-edit text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{
                                    form.id
                                        ? __("Update product")
                                        : __("Add new product")
                                }}
                            </h1>
                            <p class="text-gray-600 text-sm mt-1">
                                {{
                                    __(
                                        "Manage product information and inventory"
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-300 overflow-hidden"
                >
                    <!-- Basic Information Section -->
                    <div class="p-4 border-b border-gray-300">
                        <h3
                            class="text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2"
                        >
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span>{{ __("Basic Information") }}</span>
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Search -->
                            <div>
                                <v-input
                                    :label="__('Name')"
                                    v-model="form.name"
                                    :error="errors.name"
                                    :placeholder="__('Enter product name...')"
                                    :required="true"
                                />
                            </div>

                            <div>
                                <!---->
                                <v-select
                                    v-model="form.category"
                                    :options="categories"
                                    :label="__('Categories')"
                                    :required="true"
                                    :error="errors.category"
                                />
                            </div>

                            <!-- Currency -->
                            <div>
                                <v-select
                                    v-model="form.currency"
                                    :options="currencies"
                                    :label="__('Currency')"
                                    value-key="code"
                                    label-key="name"
                                    :required="true"
                                    :error="errors.currency"
                                />
                            </div>

                            <!-- Pricing & Stock -->
                            <div class="space-y-4">
                                <!-- Price -->
                                <v-input
                                    :label="__('Price')"
                                    v-model="form.price"
                                    :error="errors.price"
                                    type="money"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 border-b border-gray-300 gap-6 py-4"
                        >
                            <v-input
                                :label="__('Initial Stock')"
                                v-model="current_stock"
                                :error="errors.current_stock"
                                type="number"
                                :disabled="form.id ? true : false"
                            />

                            <div
                                v-if="form.id"
                                class="flex justify-between gap-2"
                            >
                                <v-input
                                    :label="__('Stock Adjustment')"
                                    v-model="update_stock"
                                    :error="errors.stock"
                                    type="number"
                                />

                                <v-switch
                                    :label="
                                        decrease
                                            ? __('Decrease Stock')
                                            : __('Increase Stock')
                                    "
                                    v-model="decrease"
                                    inactive-label=""
                                    active-label=""
                                    active-text=""
                                    inactive-text=""
                                />
                            </div>
                        </div>

                        <!-- Toggle Switches -->
                        <div class="grid grid-cols-2 gap-2 my-4">
                            <v-switch
                                v-model="form.published"
                                :label="__('Published')"
                                :error="errors.published"
                            />
                            <v-switch
                                v-model="form.featured"
                                :label="__('Featured')"
                                :error="errors.featured"
                            />
                        </div>
                    </div>

                    <div class="p-2 border-b border-gray-200">
                        <v-attributes
                            v-model="form.attributes"
                            :error="errors.attributes"
                        />
                    </div>
                    <div class="p-2 border-b border-gray-200">
                        <v-tags v-model="form.tags" :error="errors.tags" />
                    </div>

                    <!-- Descriptions Section -->
                    <div class="p-2 border-b border-gray-200">
                        <div class="space-y-6">
                            <v-editor
                                v-model="form.short_description"
                                :label="__('Short description')"
                                :error="errors.short_description"
                                :required="true"
                            />
                            <v-editor
                                v-model="form.specification"
                                :label="__('Specifications')"
                                :error="errors.specification"
                                :required="true"
                            />

                            <v-editor
                                v-model="form.description"
                                :label="__('Full description')"
                                :error="errors.description"
                                :required="true"
                            />
                        </div>
                    </div>

                    <div class="p-2 border-b border-gray-200">
                        <v-file-uploader
                            v-model="form.images"
                            :error="errors.images"
                        />
                    </div>

                    <div class="p-2 border-b border-gray-200" v-if="form.id">
                        <v-file-viewer v-model="current_images" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-6 bg-gray-50">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
                        >
                            <div
                                class="text-sm text-gray-600 flex items-center space-x-2"
                            >
                                <i class="fas fa-info-circle text-blue-400"></i>
                                <span>{{
                                    __(
                                        "Fill in all required fields to save the product"
                                    )
                                }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button
                                    @click="create"
                                    :disabled="disabled"
                                    :class="[
                                        'px-6 py-3 rounded-lg cursor-pointer font-medium flex items-center space-x-2 transition-all duration-200 shadow-sm hover:shadow',
                                        disabled
                                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                            : 'bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700',
                                    ]"
                                >
                                    <i
                                        class="fas"
                                        :class="
                                            disabled
                                                ? 'fa-spinner fa-spin'
                                                : 'fa-check'
                                        "
                                    ></i>
                                    <span>{{
                                        form.id
                                            ? __("Update Product")
                                            : __("Create Product")
                                    }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VSwitch from "../../Components/VSwitch.vue";
import VInput from "../../Components/VInput.vue";
import VEditor from "../../Components/VEditor.vue";
import VAttributes from "../../Components/VAttributes.vue";
import VTags from "../../Components/VTags.vue";
import VFileUploader from "../../Components/VFileUploader.vue";
import VFileViewer from "../../Components/VFileViewer.vue";
import VSelect from "../../Components/VSelect.vue";

export default {
    components: {
        VEditor,
        VAdminLayout,
        VSwitch,
        VInput,
        VAttributes,
        VTags,
        VFileUploader,
        VFileViewer,
        VSelect,
    },

    emits: ["created"],

    props: {
        model: {
            type: Object,
            default: {},
        },
    },

    data() {
        return {
            form: {
                id: "",
                name: "",
                short_description: "",
                description: "",
                specification: "",
                category: "",
                price: "",
                stock: 0,
                published: false,
                featured: false,
                currency: "",
                attributes: [],
                tags: [],
                images: [],
            },
            current_images: [],
            currencies: [],
            categories: [],
            products: [],
            current_stock: 0,
            update_stock: 0,
            decrease: false,
            errors: {},
            disabled: false,
        };
    },

    watch: {
        update_stock(value) {
            console.log(value);

            this.calculateStock();
        },

        decrease(value) {
            this.calculateStock();
        },
    },

    created() {
        this.getCategories();
        this.getCurrencies();

        this.loadData(this.$page.props.model);
    },

    methods: {
        calculateStock() {
            let stock = Number(this.form.stock);
            const adjustment = Number(this.update_stock) || 0;

            if (this.decrease) {
                stock -= adjustment;
            } else {
                stock += adjustment;
            }

            this.current_stock = Math.max(0, stock);
        },

        loadData(model) {
            if (model?.id) {
                this.current_images = model.images;
                this.form = {
                    ...model,
                    category: model.category.id,
                    images: [],
                };

                this.current_stock = 0;
                this.update_stock = 0;
                this.decrease = false;
                this.errors = {};
            }
            this.calculateStock();
        },

        async getCurrencies() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.currencies
                );
                if (res.status == 200) {
                    this.currencies = res.data.data.map((item) => ({
                        code: item.code,
                        name: `${item.code} - ${item.name}`,
                    }));
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            }
        },

        // Get Categories
        async getCategories() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.categories
                );
                if (res.status === 200) {
                    this.categories = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            }
        },

        async create() {
            this.disabled = true;
            const payload = new FormData();

            // Append basic fields
            Object.keys(this.form).forEach((key) => {
                if (
                    key !== "attributes" &&
                    key !== "tags" &&
                    key !== "images"
                ) {
                    const value =
                        key === "stock" ? this.current_stock : this.form[key];
                    payload.append(key, value);
                }
            });

            // Append attributes
            this.form.attributes.forEach((attr, index) => {
                Object.keys(attr).forEach((prop) => {
                    payload.append(`attributes[${index}][${prop}]`, attr[prop]);
                });
            });

            // Append tags
            this.form.tags.forEach((tag, index) => {
                payload.append(`tags[${index}][name]`, tag.name);
            });

            // Append images
            this.form.images.forEach((file) => {
                payload.append("images[]", file);
            });

            try {
                const res = await this.$server.post(
                    this.$page.props.routes.store,
                    payload,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );

                // Redirect after creation product
                if (res.status == 201) {
                    this.$notify.success(
                        __("New product has been created successfully.")
                    );

                    window.location.href = res.data.links.edit;
                }

                if (res.status == 200) {
                    this.loadData(res.data.data);
                    this.$notify.success(
                        __("New product has been updated successfully.")
                    );
                }
            } catch (e) {
                if (e.response?.data?.errors) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            } finally {
                this.disabled = false;
            }
        },
    },
};
</script>

<style scoped>
/* Custom scrollbar for dropdowns */
.scrollbar-thin::-webkit-scrollbar {
    width: 4px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
