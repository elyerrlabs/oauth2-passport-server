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
    <button
        @click="dialog = true"
        class="bg-red-500 text-white hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-800 p-2 rounded transition-colors"
        data-test="delete-button"
    >
        {{ __("Delete") }}
        <i class="fas fa-trash-alt ml-1"></i>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Confirm Delete')"
        panel-class="w-full lg:w-5xl"
    >
        <template #body>
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
                <div class="flex items-center justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center"
                    >
                        <i
                            class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"
                        ></i>
                    </div>
                </div>

                <h3
                    class="text-lg font-medium text-gray-900 dark:text-white text-center mb-2"
                >
                    {{ __("Delete product?") }}
                </h3>

                <p class="text-gray-600 dark:text-gray-300 text-center mb-6">
                    {{ __("Are you sure you want to delete") }}
                    <span class="font-semibold text-gray-900 dark:text-white"
                        >"{{ item.name }}"</span
                    >?
                    {{ __("This action cannot be undone.") }}
                </p>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button
                        @click="dialog = false"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 rounded-md transition-colors font-medium"
                    >
                        {{ __("Cancel") }}
                    </button>

                    <button
                        @click="confirmDelete"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white rounded-md transition-colors font-medium flex items-center justify-center"
                        :disabled="loading"
                    >
                        <i class="fas fa-trash-alt mr-2"></i>
                        {{ loading ? __("Deleting...") : __("Delete") }}
                    </button>
                </div>
            </div>
        </template>
    </v-modal>

    <!-- Modal de éxito -->
    <v-modal
        v-model="successDialog"
        title="Success"
        panel-class="w-full lg:w-5xl"
    >
        <template #body>
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
                <div class="flex items-center justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center"
                    >
                        <i
                            class="fas fa-check text-green-600 dark:text-green-400 text-2xl"
                        ></i>
                    </div>
                </div>

                <h3
                    class="text-lg font-medium text-gray-900 dark:text-white text-center mb-2"
                >
                    {{ __("Deleted Successfully") }}
                </h3>

                <p class="text-gray-600 dark:text-gray-300 text-center mb-6">
                    {{ __("The product has been successfully deleted.") }}
                </p>

                <div class="flex justify-center">
                    <button
                        @click="successDialog = false"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-md transition-colors font-medium"
                    >
                        {{ __("OK") }}
                    </button>
                </div>
            </div>
        </template>
    </v-modal>

    <!-- Modal de error -->
    <v-modal
        v-model="errorDialog"
        :title="__('Error')"
        panel-class="w-full lg:w-5xl"
    >
        <template #body>
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
                <div class="flex items-center justify-center mb-4">
                    <div
                        class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center"
                    >
                        <i
                            class="fas fa-times text-red-600 dark:text-red-400 text-2xl"
                        ></i>
                    </div>
                </div>

                <h3
                    class="text-lg font-medium text-gray-900 dark:text-white text-center mb-2"
                >
                    {{ __("Delete Failed") }}
                </h3>

                <p class="text-gray-600 dark:text-gray-300 text-center mb-6">
                    {{ errorMessage }}
                </p>

                <div class="flex justify-center">
                    <button
                        @click="errorDialog = false"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white rounded-md transition-colors font-medium"
                    >
                        {{ __("Close") }}
                    </button>
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    components: {
        VModal,
    },

    emits: ["deleted"],

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
            errorDialog: false,
            loading: false,
            errorMessage: "",
        };
    },

    methods: {
        async confirmDelete() {
            this.loading = true;

            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.dialog = false;
                    this.$emit("deleted", true);
                }
            } catch (e) {
                this.dialog = false;
                this.errorMessage =
                    e?.response?.data?.message ||
                    __("Delete failed. Please try again.");
                this.errorDialog = true;
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
