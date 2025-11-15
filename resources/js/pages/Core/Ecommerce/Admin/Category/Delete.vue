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
        <!-- Delete Button -->
        <button
            @click="dialog = true"
            class="delete-btn bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200 flex items-center space-x-2 hover:shadow-lg"
        >
            <i class="fas fa-trash"></i>
            <span>{{ __("Delete") }}</span>
        </button>

        <!-- Delete Confirmation Modal -->
        <v-modal
            v-model="dialog"
            panel-class="w-full lg:w-4xl"
            :title="__('Delete Category')"
        >
            <template #header>
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center"
                    >
                        <i
                            class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-lg"
                        ></i>
                    </div>
                    <div>
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            {{ __("Delete Category") }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("This action cannot be undone") }}
                        </p>
                    </div>
                </div>
            </template>

            <template #body>
                <!-- Warning Alert -->
                <div
                    class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
                >
                    <div class="flex items-start space-x-3">
                        <i
                            class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"
                        ></i>
                        <div>
                            <h4
                                class="font-medium text-red-800 dark:text-red-300"
                            >
                                {{ __("Warning: Irreversible Action") }}
                            </h4>
                            <p
                                class="text-sm text-red-700 dark:text-red-400 mt-1"
                            >
                                {{
                                    __(
                                        "This will permanently delete the category and all associated data. This action cannot be undone."
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Category Details -->
                <div
                    class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"
                >
                    <h4
                        class="font-semibold text-gray-900 dark:text-white mb-3"
                    >
                        {{ __("Category Details") }}
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    class="fas fa-tag text-blue-600 dark:text-blue-400 text-sm"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Name") }}
                                </p>
                                <p
                                    class="text-gray-900 dark:text-white font-semibold"
                                >
                                    {{ item.name }}
                                </p>
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    class="fas fa-link text-gray-600 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Slug") }}
                                </p>
                                <p
                                    class="text-gray-900 dark:text-white font-mono text-sm"
                                >
                                    {{ item.slug }}
                                </p>
                            </div>
                        </div>

                        <!-- Icon -->
                        <div
                            v-if="item.icon?.icon"
                            class="flex items-center space-x-3"
                        >
                            <div
                                class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="`mdi ${item.icon.icon} text-purple-600 dark:text-purple-400`"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Icon") }}
                                </p>
                                <p
                                    class="text-gray-900 dark:text-white font-semibold"
                                >
                                    {{ item.icon.icon }}
                                </p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="`fas ${
                                        item.published
                                            ? 'fa-eye text-green-600 dark:text-green-400'
                                            : 'fa-eye-slash text-gray-400'
                                    }`"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Status") }}
                                </p>
                                <p
                                    class="text-gray-900 dark:text-white font-semibold"
                                >
                                    {{
                                        item.published
                                            ? __("Published")
                                            : __("Hidden")
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- Featured -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    :class="`fas fa-star ${
                                        item.featured
                                            ? 'text-yellow-600 dark:text-yellow-400'
                                            : 'text-gray-400'
                                    }`"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Featured") }}
                                </p>
                                <p
                                    class="text-gray-900 dark:text-white font-semibold"
                                >
                                    {{ item.featured ? __("Yes") : __("No") }}
                                </p>
                            </div>
                        </div>

                        <!-- Children Count -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    class="fas fa-sitemap text-blue-600 dark:text-blue-400 text-sm"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Subcategories") }}
                                </p>
                                <p
                                    class="text-gray-900 dark:text-white font-semibold"
                                >
                                    {{ item.children?.length || 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Category -->
                    <div
                        v-if="item.parent?.id"
                        class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600"
                    >
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center"
                            >
                                <i
                                    class="fas fa-level-up-alt rotate-90 text-gray-600 dark:text-gray-400 text-sm"
                                ></i>
                            </div>
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Parent Category") }}
                                </p>
                                <p
                                    class="text-gray-900 dark:text-white font-semibold"
                                >
                                    {{ item.parent.name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Impact Warning -->
                <div
                    class="mb-6 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg"
                >
                    <div class="flex items-start space-x-3">
                        <i
                            class="fas fa-info-circle text-orange-500 dark:text-orange-400 mt-0.5"
                        ></i>
                        <div class="space-y-2">
                            <h4
                                class="font-medium text-orange-800 dark:text-orange-300"
                            >
                                {{ __("What will be affected?") }}
                            </h4>
                            <ul
                                class="text-sm text-orange-700 dark:text-orange-400 space-y-1 list-disc list-inside"
                            >
                                <li>
                                    {{
                                        __(
                                            "All subcategories under this category will be orphaned"
                                        )
                                    }}
                                </li>
                                <li>
                                    {{
                                        __(
                                            "Products in this category may need to be reassigned"
                                        )
                                    }}
                                </li>
                                <li>
                                    {{
                                        __(
                                            "Any associated metadata will be permanently removed"
                                        )
                                    }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <button
                        @click="dialog = false"
                        class="px-6 py-2.5 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-all duration-200 hover:shadow-md"
                    >
                        {{ __("Cancel") }}
                    </button>
                    <button
                        @click="destroy"
                        :disabled="loading"
                        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white rounded-lg font-medium transition-all duration-200 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                    >
                        <i v-if="loading" class="fas fa-spinner fa-spin"></i>
                        <i v-else class="fas fa-trash"></i>
                        <span>{{
                            loading ? __("Deleting...") : __("Delete Category")
                        }}</span>
                    </button>
                </div>
            </template>
        </v-modal>

        <!-- Success Modal -->
        <v-modal v-model="successDialog" panel-class="w-full max-w-sm">
            <template #body>
                <div class="text-center p-6">
                    <div
                        class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <i
                            class="fas fa-check text-green-600 dark:text-green-400 text-2xl"
                        ></i>
                    </div>
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
                    >
                        {{ __("Deleted Successfully!") }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        {{ __("The category has been permanently deleted.") }}
                    </p>
                </div>
            </template>

            <template #footer>
                <div class="flex justify-center">
                    <button
                        @click="successDialog = false"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-lg font-medium transition-colors duration-200"
                    >
                        {{ __("Close") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    emits: ["deleted"],

    components: {
        VModal,
    },

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
            successDialog: false,
            loading: false,
        };
    },

    methods: {
        async destroy() {
            this.loading = true;

            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.dialog = false;
                    this.successDialog = true;
                    this.$emit("deleted", true);

                    // Auto-close success modal after 3 seconds
                    setTimeout(() => {
                        if (this.successDialog) {
                            this.successDialog = false;
                        }
                    }, 3000);
                }
            } catch (error) {
                let errorMessage = __(
                    "An error occurred while deleting the category"
                );

                if (error?.response?.data?.message) {
                    errorMessage = __(error.response.data.message);
                }

                // Show error notification
                this.$notify.error(errorMessage);
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.delete-btn:hover {
    transform: translateY(-1px);
}

/* Smooth transitions */
* {
    transition-property: color, background-color, border-color, transform,
        opacity;
    transition-duration: 200ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
