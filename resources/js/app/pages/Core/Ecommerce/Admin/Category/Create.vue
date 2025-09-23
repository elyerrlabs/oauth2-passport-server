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
        <div class="min-h-screen bg-gray-50 py-2 px-4 sm:px-6 lg:px-2">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{
                            form.id
                                ? __("Update category")
                                : __("Create new category")
                        }}
                    </h1>
                    <p class="text-gray-600">
                        {{ __("Manage your product categories with ease") }}
                    </p>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Form Content -->
                    <div class="p-4 space-y-8">
                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i
                                        class="mdi mdi-information text-blue-600 text-xl"
                                    ></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">
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
                                        class="text-blue-600"
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
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i
                                        class="mdi mdi-text text-blue-600 text-xl"
                                    ></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ __("Description") }}
                                </h3>
                            </div>
                            <div class="space-y-2">
                                <v-editor
                                    v-model="form.description"
                                    class="border border-gray-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all duration-200"
                                />
                            </div>
                        </div>
                        <!-- Images Section -->
                        <div class="space-y-6">
                            <v-file-uploader v-model="form.images" />
                            <v-error :error="errors.images" />
                        </div>
                        <div class="space-y-6" v-if="current_images.length">
                            <v-file-viewer v-model="current_images" />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                        <div
                            class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4"
                        >
                            <button
                                @click="create"
                                :disabled="disabled"
                                :class="[
                                    'px-6 py-3 rounded-xl transition-all duration-200 flex items-center cursor-pointer justify-center space-x-2 font-medium',
                                    disabled
                                        ? 'bg-gray-400 text-white cursor-not-allowed'
                                        : 'bg-gradient-to-r from-blue-600 to-indigo-700 text-white hover:from-blue-700 hover:to-indigo-800 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5',
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
                                            ? "Update Category"
                                            : "Create Category"
                                    }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-admin-layout>
</template>

<script>
import VAdminLayout from "../../Components/VAdminLayout.vue";
import VEditor from "../../Components/VEditor.vue";
import VFileUploader from "../../Components/VFileUploader.vue";
import VInput from "../../Components/VInput.vue";
import VSwitch from "../../Components/VSwitch.vue";
import VFileViewer from "../../Components/VFileViewer.vue";
import VError from "../../Components/VError.vue";

export default {
    components: {
        VAdminLayout,
        VEditor,
        VFileUploader,
        VInput,
        VSwitch,
        VFileViewer,
        VError,
    },

    data() {
        return {
            form: {
                id: "",
                name: "",
                icon: "",
                description: "",
                published: false,
                featured: false,
                images: [],
            },
            errors: {},
            current_images: [],
            disabled: false,
        };
    },

    created() {
        this.loadData(this.$page.props.model);
    },

    methods: {
        loadData(model) {
            if (model?.id) {
                this.form = { ...model, images: [] };
                this.current_images = model.images;
                this.form.icon = model.icon.icon;
            }
        },

        async create() {
            this.disabled = true;
            const payload = new FormData();

            payload.append("id", this.form.id);
            payload.append("name", this.form.name);
            payload.append("description", this.form.description);
            payload.append("icon", this.form.icon);
            payload.append("featured", this.form.featured ? 1 : 0);
            payload.append("published", this.form.published ? 1 : 0);

            if (this.form?.images?.length > 0) {
                this.form.images.forEach((file) => {
                    payload.append("images[]", file);
                });
            }

            try {
                const res = await this.$server.post(
                    this.$page.props.routes.store,
                    payload,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (res.status == 201) {
                    window.location.href = res.data.data.links.edit;
                }

                if (res.status === 200) {
                    this.$notify.success(__("Category updated successfully"));

                    this.loadData(res.data.data);
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    this.$notify.error(__(e.response.data.message));
                }
            } finally {
                this.disabled = false;
            }
        },
    },
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

.form-content::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.form-content::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
