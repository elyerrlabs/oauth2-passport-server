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
    <!-- Delete Button -->
    <button
        @click="dialog = true"
        class="relative group rounded-full p-2 text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-200"
    >
        <i class="mdi mdi-delete-outline text-lg"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-red-600 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
        >
            {{ __("Delete role") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-red-600"
            ></div>
        </div>
    </button>

    <!-- Delete Confirmation Modal -->
    <v-modal
        v-model="dialog"
        :title="__('Delete Role')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <div class="p-6 space-y-4 text-center">
                <!-- Confirmation Message -->
                <p class="text-gray-700">
                    {{ __("Are you sure you want to delete the role") }}
                    <span class="font-bold text-blue-600"
                        >"{{ item.name }}"</span
                    >?
                </p>

                <!-- Role Info Chips -->
                <div class="flex justify-center gap-2 flex-wrap">
                    <span
                        class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm"
                    >
                        <i class="mdi mdi-identifier"></i>
                        {{ __("ID") }}: {{ item.id }}
                    </span>
                    <span
                        v-if="item.system"
                        class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm"
                    >
                        <i class="mdi mdi-shield-check"></i>
                        {{ __("System Role") }}
                    </span>
                </div>

                <!-- Warning Message -->
                <div
                    :class="[
                        'p-4 rounded-lg border',
                        item.system
                            ? 'bg-orange-50 border-orange-200 text-orange-800'
                            : 'bg-red-50 border-red-200 text-red-800',
                    ]"
                >
                    <div class="flex items-start gap-2">
                        <i class="mdi mdi-alert mt-0.5 flex-shrink-0"></i>
                        <span class="text-sm text-left">
                            {{
                                item.system
                                    ? __(
                                          "Warning: This is a system role. Deleting it may affect application functionality."
                                      )
                                    : __(
                                          "Warning: This action cannot be undone. All associated permissions will be removed."
                                      )
                            }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-center gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl"
            >
                <button
                    @click="dialog = false"
                    :disabled="loading"
                    class="flex items-center gap-2 px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-close-circle"></i>
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="destroy"
                    :disabled="loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors',
                        loading
                            ? 'bg-red-400 cursor-not-allowed'
                            : 'bg-red-600 hover:bg-red-700 focus:ring-red-200',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i v-else class="mdi mdi-delete-forever"></i>
                    {{ __("Delete Role") }}
                </button>
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
            loading: false,
        };
    },

    methods: {
        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    $notify.error(__("Role deleted successfully"));
                    this.$emit("deleted", true);
                    this.dialog = false;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.fixed {
    z-index: 50;
}

.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
