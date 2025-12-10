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
        <!-- Header -->
        <div
            class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4 sm:py-6 px-3 sm:px-6"
        >
            <!-- Page Header -->
            <div
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center"
                    >
                        <i
                            class="fas fa-edit text-blue-600 dark:text-blue-400"
                        ></i>
                    </div>
                    <div>
                        <h1
                            class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{
                                form.id
                                    ? __("Update Product")
                                    : __("Create Product")
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
                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm transition-colors"
                >
                    {{ __("Preview") }}
                </a>
            </div>

            <!-- Main Content -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
            >
                <!-- Basic Information -->
                <div
                    class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600"
                >
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
                    >
                        {{ __("Basic Information") }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Product Name -->
                        <div>
                            <v-input
                                :label="__('Product Name')"
                                v-model="form.name"
                                :error="errors.name"
                                :placeholder="__('Enter product name')"
                                :required="true"
                            />
                        </div>

                        <!-- Category Select -->
                        <div>
                            <v-select
                                v-model="form.category"
                                :options="categories"
                                :label="__('Category')"
                                :required="true"
                                :error="errors.category"
                                searchable
                                @search="searchCategories"
                            >
                                <template #selected="{ option }">
                                    <div
                                        v-if="option"
                                        class="flex items-center"
                                    >
                                        <span
                                            class="font-medium text-gray-800 dark:text-gray-200"
                                        >
                                            {{ option?.name }}
                                        </span>
                                    </div>
                                    <span
                                        v-else
                                        class="text-gray-500 dark:text-gray-400"
                                    >
                                        {{ __("Select category") }}
                                    </span>
                                </template>

                                <template #option="{ option }">
                                    <div
                                        class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                    >
                                        <span
                                            class="font-medium text-gray-800 dark:text-gray-200"
                                        >
                                            {{ option.name }}
                                        </span>
                                    </div>
                                </template>
                            </v-select>
                        </div>
                    </div>

                    <!-- Toggles -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
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

                <!-- Related Products -->
                <div
                    class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600"
                >
                    <div class="mb-4">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Related Products") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{ __("Select related products for this item") }}
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
                        class="w-full mb-4"
                    >
                        <template #option="{ option }">
                            <div
                                v-if="option.name"
                                class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            >
                                <img
                                    class="w-8 h-8 rounded"
                                    :src="option.images[0]?.links?.show"
                                    :alt="option.name"
                                />
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="font-medium text-gray-800 dark:text-gray-200 truncate"
                                    >
                                        {{ option.name }}
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-400 truncate"
                                    >
                                        {{ option.category?.name }}
                                    </p>
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

                    <!-- Selected Products -->
                    <div v-if="form.children.length" class="mt-6">
                        <h4
                            class="text-base font-medium text-gray-700 dark:text-gray-300 mb-3"
                        >
                            {{ __("Selected Related Products") }}
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div
                                v-for="(item, index) in form.children"
                                :key="item.id"
                                class="flex items-center justify-between gap-3 p-3 rounded border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700"
                            >
                                <div
                                    class="flex items-center gap-3 flex-1 min-w-0"
                                >
                                    <img
                                        :src="item.images[0]?.url"
                                        :alt="item.name"
                                        class="w-10 h-10 rounded"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="font-medium text-gray-800 dark:text-gray-200 truncate"
                                        >
                                            {{ item.name }}
                                        </p>
                                        <p
                                            class="text-blue-600 dark:text-blue-400 font-bold text-sm"
                                        >
                                            {{ item.symbol }}
                                            {{ item.format_price }}
                                        </p>
                                    </div>
                                </div>
                                <button
                                    @click="deleteRelatedProduct(item, index)"
                                    class="w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors"
                                    :title="__('Remove')"
                                >
                                    <i class="mdi mdi-delete text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attributes -->
                <div
                    class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600"
                >
                    <v-attributes
                        v-model="form.attributes"
                        :error="errors.attributes"
                    />
                </div>

                <!-- Variants -->
                <div
                    class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600"
                >
                    <v-variant
                        v-model="form.variants"
                        :error="errors.variants"
                    />
                </div>

                <!-- Descriptions -->
                <div
                    class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600"
                >
                    <div class="space-y-4">
                        <v-editor
                            v-model="form.short_description"
                            :label="__('Short Description')"
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
                            :label="__('Full Description')"
                            :error="errors.description"
                            :required="true"
                        />
                    </div>
                </div>

                <!-- Images -->
                <div
                    class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600"
                >
                    <v-file-uploader
                        v-model="form.images"
                        :error="errors.images"
                    />
                </div>

                <!-- Actions -->
                <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-700/50">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                    >
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Fill all required fields to continue") }}
                        </div>
                        <div>
                            <button
                                @click="create"
                                :disabled="disabled"
                                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium flex items-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i
                                    class="fas mr-2"
                                    :class="
                                        disabled
                                            ? 'fa-spinner fa-spin'
                                            : 'fa-check'
                                    "
                                ></i>
                                {{
                                    form.id
                                        ? __("Update Product")
                                        : __("Create Product")
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Viewer -->
            <div v-if="form.id" class="mt-6">
                <v-file-viewer v-model="current_images" />
            </div>
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

    // Basic fields
    Object.keys(form.value).forEach((key) => {
        if (key !== "attributes" && key !== "images") {
            payload.append(key, form.value[key]);
        }
    });

    // Attributes
    form.value.attributes.forEach((attr, index) => {
        Object.keys(attr).forEach((key) => {
            payload.append(`attributes[${index}][${key}]`, attr[key]);
        });
    });

    // Images
    form.value.images.forEach((file) => {
        payload.append("images[]", file);
    });

    // Variants
    form.value.variants.forEach((variant, index) => {
        Object.keys(variant).forEach((key) => {
            payload.append(`variants[${index}][${key}]`, variant[key]);
        });
    });

    // Related products
    form.value.children_id.forEach((item) => {
        payload.append("children_id[]", item);
    });

    try {
        const res = await $server.post(page.props.admin.products, payload, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        if (res.status == 201) {
            $notify.success(__("Product created successfully"));
            const url = `${page.props.routes.index}/${res.data.id}/edit`;
            router.visit(url);
            return;
        }

        if (res.status == 200) {
            loadData(res.data);
            getProducts();
            $notify.success(__("Product updated successfully"));
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
    try {
        const res = await $server.get(page.props.admin.products, {
            params: {
                name: searchTermProduct.value,
                per_page: 20,
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
            cancelButtonText: __("Cancel"),
            confirmButtonColor: "#d33",
        });

        if (result.isConfirmed) {
            const res = await $server.delete(item.links.delete);

            if (res.status === 200) {
                form.value.children.splice(index, 1);
                getProducts();
                $notify.success(__("Related product removed successfully"));
            }
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};
</script>
