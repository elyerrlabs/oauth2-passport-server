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
            class="min-h-screen bg-linear-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-blue-900/20 py-6 px-4 sm:px-6 lg:px-3 transition-colors duration-300"
        >
            <!-- Page Header -->
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8"
            >
                <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                    <div
                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center shadow-sm"
                    >
                        <i
                            class="fas fa-edit text-blue-600 dark:text-blue-400 text-xl"
                        ></i>
                    </div>
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{
                                form.id
                                    ? __("Update product")
                                    : __("Add new product")
                            }}
                        </h1>
                        <p
                            class="text-gray-600 dark:text-gray-300 text-sm mt-1"
                        >
                            {{ __("Manage product information and inventory") }}
                        </p>
                    </div>
                </div>
                <a
                    v-if="form?.id"
                    target="_blank"
                    :href="$page.props.routes.preview"
                    class="text-gray-200 font-medium bg-blue-600 px-3 py-2 rounded"
                >
                    {{ __("Preview") }}
                </a>
            </div>

            <!-- Main Content Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-300 dark:border-gray-600 overflow-hidden transition-colors duration-300"
            >
                <!-- Basic Information Section -->
                <div class="p-4 border-b border-gray-300 dark:border-gray-600">
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center space-x-2"
                    >
                        <i
                            class="fas fa-info-circle text-blue-500 dark:text-blue-400"
                        ></i>
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
                                searchable
                                @search="searchCategories"
                            >
                                <!-- Selected items -->
                                <template #selected="{ option }">
                                    <div
                                        v-if="option"
                                        class="flex items-center"
                                    >
                                        <div
                                            class="font-semibold text-gray-800 dark:text-gray-200"
                                        >
                                            <i
                                                v-if="option.icon?.icon"
                                                :class="[
                                                    'mdi me-3',
                                                    option.icon.icon,
                                                ]"
                                            ></i>
                                            <span>{{ option?.name }}</span>
                                        </div>
                                    </div>

                                    <span
                                        v-else
                                        class="font-semibold text-gray-800 dark:text-gray-200 block"
                                    >
                                        {{ __("Select menu") }}
                                    </span>
                                </template>

                                <!-- Option list -->
                                <template #option="{ option }">
                                    <div
                                        class="flex items-center gap-3 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"
                                    >
                                        <div>
                                            <span
                                                class="font-semibold text-gray-800 dark:text-gray-200 block"
                                            >
                                                {{ option.name }}
                                            </span>
                                            <div
                                                class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400"
                                            >
                                                <i
                                                    v-if="option.icon?.icon"
                                                    :class="[
                                                        'mdi',
                                                        option.icon.icon,
                                                    ]"
                                                ></i>
                                                <span>{{ option?.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </v-select>
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

                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <h1
                                class="text-2xl font-bold text-gray-800 dark:text-white mb-2"
                            >
                                {{ __("Add Related Products") }}
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                {{
                                    __(
                                        "Select one or more products to associate as related items."
                                    )
                                }}
                            </p>
                        </div>

                        <!-- Product Selector -->
                        <v-select
                            clearable
                            searchable
                            :multiple="true"
                            :options="products"
                            v-model="form.children_id"
                            @search="searchProduct"
                            :placeholder="__('Search products...')"
                            class="w-full"
                        >
                            <!-- Option list -->
                            <template #option="{ option }">
                                <div
                                    v-if="option.name"
                                    class="flex items-center justify-between gap-4 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors rounded-md"
                                >
                                    <div class="flex items-center gap-3">
                                        <img
                                            class="w-10 h-10 rounded"
                                            :src="option.images[0]?.url"
                                            :alt="option.name"
                                        />
                                        <div>
                                            <span
                                                class="font-semibold text-gray-800 dark:text-gray-200 block"
                                            >
                                                {{ option.name }}
                                            </span>
                                            <div
                                                class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400"
                                            >
                                                <i
                                                    v-if="
                                                        option.category?.icon
                                                            ?.icon
                                                    "
                                                    :class="[
                                                        'mdi',
                                                        option.category.icon
                                                            .icon,
                                                    ]"
                                                ></i>
                                                <span>{{
                                                    option.category?.name
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span
                                        class="font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap"
                                    >
                                        {{ option.symbol }}
                                        {{ option.format_price }}
                                    </span>
                                </div>
                            </template>
                        </v-select>

                        <!-- Related Products List -->
                        <div v-if="form.children.length" class="mt-6">
                            <h2
                                class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3"
                            >
                                {{ __("Selected Related Products") }}
                            </h2>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="(item, index) in form.children"
                                    :key="item.id"
                                    class="flex items-center justify-between gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-md dark:hover:shadow-gray-700/30 transition-all bg-white dark:bg-gray-700"
                                >
                                    <div class="flex items-center gap-3">
                                        <img
                                            :src="item.images[0]?.url"
                                            :alt="item.name"
                                            class="w-12 h-12 rounded"
                                        />
                                        <div>
                                            <p
                                                class="font-semibold text-gray-800 dark:text-gray-200"
                                            >
                                                {{ item.name }}
                                            </p>
                                            <p
                                                class="text-blue-600 dark:text-blue-400 font-bold text-sm"
                                            >
                                                {{ item.symbol }}
                                                {{ item.format_price }}
                                            </p>
                                            <div
                                                v-if="item.category"
                                                class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400"
                                            >
                                                <i
                                                    v-if="
                                                        item.category.icon?.icon
                                                    "
                                                    :class="[
                                                        'mdi',
                                                        item.category.icon.icon,
                                                    ]"
                                                ></i>
                                                <span>{{
                                                    item.category.name
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        @click="
                                            deleteRelatedProduct(item, index)
                                        "
                                        class="flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-sm transition-colors"
                                        title="Remove"
                                    >
                                        <i class="mdi mdi-delete text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                    <v-attributes
                        v-model="form.attributes"
                        :error="errors.attributes"
                    />
                </div>

                <div class="p-2 border-b border-gray-300 dark:border-gray-600">
                    <v-variant
                        v-model="form.variants"
                        :error="errors.variants"
                    />
                </div>

                <!-- Descriptions Section -->
                <div class="p-2 border-b border-gray-200 dark:border-gray-600">
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

                <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                    <v-file-uploader
                        v-model="form.images"
                        :error="errors.images"
                    />
                </div>

                <!-- Action Buttons -->
                <div class="p-6 bg-gray-50 dark:bg-gray-700/50">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
                    >
                        <div
                            class="text-sm text-gray-600 dark:text-gray-400 flex items-center space-x-2"
                        >
                            <i
                                class="fas fa-info-circle text-blue-400 dark:text-blue-300"
                            ></i>
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
                                        ? 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'
                                        : 'bg-linear-to-r from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 text-white hover:from-green-600 hover:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800',
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
        <div
            class="p-2 border mt-8 rounded-full border-gray-200 dark:border-gray-600"
            v-if="form.id"
        >
            <v-file-viewer v-model="current_images" />
        </div>
    </v-admin-layout>
</template>

<script setup>
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VSwitch from "@/components/VSwitch.vue";
import VInput from "@/components/VInput.vue";
import VEditor from "@/components/VEditor.vue";
import VSelect from "@/components/VSelect.vue";
import VAttributes from "../../Components/VAttributes.vue";
import VFileUploader from "../../Components/VFileUploader.vue";
import VFileViewer from "../../Components/VFileViewer.vue";
import VVariant from "../../Components/VVariant.vue";
import { usePage, router } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import Swal from "sweetalert2";

const page = usePage();

const form = ref({
    id: "",
    name: "",
    short_description: "",
    description: "",
    specification: "",
    category: "",
    published: false,
    featured: false,
    currency: "",
    attributes: [],
    images: [],
    variants: [],
    children_id: [],
    children: [],
});

const current_images = ref([]);
const categories = ref([]);
const searchTermCategory = ref("");
const searchTermProduct = ref("");
const products = ref([]);
const errors = ref({});
const disabled = ref(false);

onMounted(() => {
    getCategories();
    getCurrencies();
    loadData(page.props?.data);
    getProducts();
});

const loadData = (model) => {
    if (model?.id) {
        current_images.value = model.images;
        form.value = {
            ...model,
            category: model.category.id,
            images: [],
            children_id: [],
        };

        errors.value = {};
    }
};

const searchCategories = (val) => {
    searchTermCategory.value = val;
    getCategories();
};

const searchProduct = (val) => {
    searchTermProduct.value = val;
    getProducts();
};

const getCurrencies = async () => {
    try {
        const res = await $server.get("/api/transaction/payments/currencies");
        if (res.status == 200) {
            currencies = res.data.data.map((item) => ({
                code: item.code,
                name: `${item.code} - ${item.name}`,
            }));
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

// Get Categories
const getCategories = async () => {
    try {
        const res = await $server.get(page.props.admin.categories, {
            params: {
                name: searchTermCategory.value,
                per_page: 20,
            },
        });
        if (res.status === 200) {
            categories.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const create = async () => {
    disabled.value = true;
    const payload = new FormData();

    // Append basic fields
    Object.keys(form.value).forEach((key) => {
        if (key !== "attributes" && key !== "images") {
            //const value = key === "stock" ? current_stock.value : form.value[key];
            payload.append(key, form.value[key]);
        }
    });

    // Append attributes
    form.value.attributes.forEach((attr, index) => {
        Object.keys(attr).forEach((key) => {
            payload.append(`attributes[${index}][${key}]`, attr[key]);
        });
    });

    // Append images
    form.value.images.forEach((file) => {
        payload.append("images[]", file);
    });

    form.value.variants.forEach((variant, index) => {
        Object.keys(variant).forEach((key) => {
            payload.append(`variants[${index}][${key}]`, variant[key]);
        });
    });

    // add related products
    form.value.children_id.forEach((item) => {
        payload.append("children_id[]", item);
    });

    try {
        const res = await $server.post(page.props.admin.products, payload, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        // Redirect after creation product
        if (res.status == 201) {
            $notify.success(__("New product has been created successfully."));
            const url = `${page.props.routes.index}/${res.data.id}/edit`;
            router.visit(url);
            return;
        }

        if (res.status == 200) {
            loadData(res.data);
            getProducts();
            $notify.success(__("New product has been updated successfully."));
        }
    } catch (e) {
        if (e.response?.data?.errors) {
            errors.value = e.response.data.errors;
        }

        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        disabled.value = false;
    }
};

const getProducts = async () => {
    //  const except_ids = await form.children.map((item) => item.id);
    try {
        const res = await $server.get(page.props.admin.products, {
            params: {
                name: searchTermProduct.value,
                per_page: 20,
                //      except_id: except_ids,
            },
        });

        if (res.status == 200) {
            products.value = res.data.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const deleteRelatedProduct = async (item, index) => {
    try {
        const result = await Swal.fire({
            title: __("Are you sure?"),
            text: __("This related product will be permanently removed."),
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: __("Yes, delete it"),
            cancelButtonText: "Cancel",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
        });

        if (result.isConfirmed) {
            const res = await $server.delete(item.links.delete);

            if (res.status === 200) {
                form.value.children.splice(index, 1);
                getProducts();

                $notify.success(
                    __("The related product has been successfully removed.")
                );
            }
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};
</script>
