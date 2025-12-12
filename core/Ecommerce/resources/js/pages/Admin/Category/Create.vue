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
        <div
            class="min-h-screen bg-white dark:bg-gray-900 py-2 px-4 sm:px-6 lg:px-2 transition-colors duration-300"
        >
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h1
                        class="text-3xl font-bold text-gray-900 dark:text-white mb-2"
                    >
                        {{
                            form.id
                                ? __("Update category")
                                : __("Create new category")
                        }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __("Manage your product categories with ease") }}
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                >
                    <!-- Form Content -->
                    <div class="p-4 space-y-8">
                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg"
                                >
                                    <i
                                        class="mdi mdi-information text-blue-600 dark:text-blue-400 text-xl"
                                    ></i>
                                </div>
                                <h3
                                    class="text-lg font-semibold text-gray-800 dark:text-white"
                                >
                                    {{ __("Basic Information") }}
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name Field -->
                                <div class="space-y-2">
                                    <v-input
                                        :label="__('Category Name')"
                                        v-model="form.name"
                                        :placeholder="__('Enter category name')"
                                        :error="errors.name"
                                        :required="true"
                                    />
                                </div>

                                <!-- Icon Field -->
                                <div class="space-y-2">
                                    <v-input
                                        :label="__('Icon')"
                                        v-model="form.icon"
                                        :placeholder="__('Enter category name')"
                                        :error="errors.icon"
                                        :required="true"
                                        placeholder="mdi-image"
                                    />

                                    <a
                                        href="https://pictogrammers.com/library/mdi/"
                                        target="_blank"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200"
                                    >
                                        <i class="mdi mdi-launch">
                                            View Icon Library
                                        </i>
                                    </a>
                                </div>

                                <!-- Status Toggles -->
                                <div class="space-y-4">
                                    <v-switch
                                        v-model="form.published"
                                        :label="__('Published')"
                                        :required="true"
                                        :error="errors.published"
                                    />
                                </div>

                                <div class="space-y-4">
                                    <v-switch
                                        v-model="form.featured"
                                        :label="__('Featured')"
                                        :required="true"
                                        :error="errors.featured"
                                    />
                                </div>

                                <div class="space-y-4">
                                    <v-select
                                        :label="__('Add to the parent menu')"
                                        :description="
                                            __(
                                                'When you assign a parent menu, this item will automatically become a submenu under it.'
                                            )
                                        "
                                        :options="categories"
                                        v-model="form.parent_id"
                                        clearable
                                    >
                                        <template #selected="{ option }">
                                            <div v-if="option?.icon">
                                                <span
                                                    :class="[
                                                        'mdi',
                                                        option.icon?.icon,
                                                        'text-blue-600 dark:text-blue-400',
                                                    ]"
                                                ></span>
                                                <span
                                                    class="text-gray-700 dark:text-gray-300 p-4"
                                                >
                                                    {{ option.name }}
                                                </span>
                                            </div>
                                            <span
                                                v-else
                                                class="text-gray-500 dark:text-gray-400 p-4"
                                            >
                                                {{ __("Select ") }}
                                            </span>
                                        </template>
                                        <template #option="{ option }">
                                            <div class="p-4">
                                                <span
                                                    :class="[
                                                        'mdi',
                                                        option.icon?.icon,
                                                        'text-blue-600 dark:text-blue-400',
                                                    ]"
                                                ></span>
                                                <span
                                                    class="text-gray-700 dark:text-gray-300 p-4"
                                                >
                                                    {{ option.name }}
                                                </span>
                                            </div>
                                        </template>
                                    </v-select>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg"
                                >
                                    <i
                                        class="mdi mdi-text text-blue-600 dark:text-blue-400 text-xl"
                                    ></i>
                                </div>
                                <h3
                                    class="text-lg font-semibold text-gray-800 dark:text-white"
                                >
                                    {{ __("Description") }}
                                </h3>
                            </div>
                            <div class="space-y-2">
                                <v-editor
                                    v-model="form.description"
                                    class="border border-gray-300 dark:border-gray-600 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all duration-200 bg-white dark:bg-gray-700"
                                />
                            </div>
                        </div>
                        <!-- Images Section -->
                        <div class="space-y-6">
                            <v-file-uploader
                                v-model="form.images"
                                :error="errors.images"
                            />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="px-6 py-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600 transition-colors duration-300"
                    >
                        <div
                            class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4"
                        >
                            <button
                                @click="create"
                                :disabled="disabled"
                                :class="[
                                    'px-6 py-3 rounded-xl transition-all duration-200 flex items-center cursor-pointer justify-center space-x-2 font-medium',
                                    disabled
                                        ? 'bg-gray-400 dark:bg-gray-600 text-white cursor-not-allowed'
                                        : 'bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-700 dark:to-indigo-800 text-white hover:from-blue-700 hover:to-indigo-800 dark:hover:from-blue-800 dark:hover:to-indigo-900 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5',
                                ]"
                            >
                                <i
                                    :class="
                                        form?.id
                                            ? 'mdi mdi-check'
                                            : 'mdi mdi-plus'
                                    "
                                ></i>
                                <span>
                                    {{
                                        form?.id
                                            ? __("Update Category")
                                            : __("Create Category")
                                    }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-y-6 mt-8" v-if="current_images.length">
            <v-file-viewer v-model="current_images" />
        </div>
    </v-admin-layout>
</template>

<script setup>
import VAdminLayout from "@Ecommerce/components/VAdminLayout.vue";
import VEditor from "@/components/VEditor.vue";
import VFileUploader from "@Ecommerce/components/VFileUploader.vue";
import VInput from "@/components/VInput.vue";
import VSwitch from "@/components/VSwitch.vue";
import VFileViewer from "@Ecommerce/components/VFileViewer.vue";
import VSelect from "@/components/VSelect.vue";
import { router, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const page = usePage();

const form = ref({
    id: "",
    name: "",
    icon: "",
    description: "",
    published: false,
    featured: false,
    images: [],
    parent_id: "",
});

const errors = ref({});
const current_images = ref([]);
const disabled = ref(false);
const categories = ref([]);
const category_name = ref("");

onMounted(async () => {
    await getCategories();
    loadData(page.props.data);
});

const loadData = (model) => {
    if (model?.id) {
        form.value = { ...model, images: [] };
        current_images.value = model.images;
        form.value.icon = model.icon.icon;
        form.value.parent_id = model.parent?.id;
    }
};

// Create or update category
const create = async () => {
    disabled.value = true;

    const payload = new FormData();
    payload.append("id", form.value.id);
    payload.append("name", form.value.name);
    payload.append("description", form.value.description);
    payload.append("icon", form.value.icon);
    payload.append("featured", form.value.featured ? 1 : 0);
    payload.append("published", form.value.published ? 1 : 0);
    payload.append("parent_id", form.value.parent_id);

    if (form.value?.images?.length > 0) {
        form.value.images.forEach((file) => {
            payload.append("images[]", file);
        });
    }

    try {
        const res = await $server.post(page.props.api.categories, payload, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        if (res.status == 201) {
            router.visit(`${page.props.routes.index}/${res.data.id}/edit`);
        }

        if (res.status === 200) {
            $notify.success(__("Category updated successfully"));

            loadData(res.data);
            errors.value = {};
        }
    } catch (e) {
        if (e?.response?.status == 422) {
            errors.value = e.response.data.errors;
        }

        if (e?.response?.data?.message) {
            $notify.error(__(e.response.data.message));
        }
    } finally {
        disabled.value = false;
    }
};

const getCategories = async () => {
    try {
        const res = await $server.get(page.props.api.categories, {
            params: {
                name: category_name.value,
                except_id: page.props?.data?.id ?? "",
                per_page: 30,
            },
        });

        if (res.status == 200) {
            categories.value = res.data.data;
        }
    } catch (error) {
        if (e?.response?.data?.message) {
            $notify(e.response.data.message);
        }
    }
};
</script>

<style>
/* Smooth transitions for all interactive elements */
* {
    transition-property: color, background-color, border-color, transform,
        box-shadow;
    transition-duration: 200ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar for the form */
.form-content::-webkit-scrollbar {
    width: 6px;
}

.form-content::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.dark .form-content::-webkit-scrollbar-track {
    background: #374151;
}

.form-content::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.dark .form-content::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.form-content::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .form-content::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}
</style>
