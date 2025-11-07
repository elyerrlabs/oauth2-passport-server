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
            class="w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-full flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
        >
            <i class="mdi mdi-delete-outline text-lg"></i>
        </button>

        <v-modal
            v-model="dialog"
            :title="__('Confirm Deletion')"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <!-- Content -->
                <div class="p-6 text-center">
                    <p class="text-gray-700 mb-4">
                        {{ __("Are you sure you want to delete the group") }}
                        <span class="font-bold text-blue-600">
                            "{{ item.name }}" </span
                        >?
                    </p>

                    <div class="flex justify-center mb-4">
                        <span
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium flex items-center"
                        >
                            <i class="mdi mdi-identifier mr-1 text-sm"></i>
                            {{ __("ID") }}: {{ item.id }}
                        </span>
                    </div>

                    <div
                        class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4"
                    >
                        <div class="flex items-start">
                            <i
                                class="mdi mdi-alert text-red-500 mt-0.5 mr-2"
                            ></i>
                            <span class="text-sm">
                                {{
                                    __(
                                        "Warning: This will permanently remove the group and all associated data."
                                    )
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-center space-x-3 p-6 border-t border-gray-200"
                >
                    <button
                        @click="dialog = false"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-lg flex items-center space-x-2 transition-colors duration-200"
                    >
                        <i class="mdi mdi-close-circle"></i>
                        <span>{{ __("Cancel") }}</span>
                    </button>
                    <button
                        @click="destroy"
                        :disabled="loading"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg flex items-center space-x-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="mdi mdi-delete-forever"></i>
                        <span>{{ __("Delete Group") }}</span>
                        <div
                            v-if="loading"
                            class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
                        ></div>
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
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
            loading: false,
        };
    },

    methods: {
        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$q?.notify?.({
                        type: "positive",
                        message: "Group deleted successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                    });
                    this.$emit("deleted", true);
                    this.dialog = false;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.success(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
