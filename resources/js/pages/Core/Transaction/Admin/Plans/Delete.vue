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
            class="group relative flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-br from-red-500 to-red-600 text-white shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300"
        >
            <i
                class="mdi mdi-delete text-lg transition-transform duration-300 group-hover:rotate-12"
            ></i>

            <!-- Tooltip -->
            <span
                class="absolute bottom-14 px-2.5 py-1 text-xs rounded-md bg-gray-800 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
            >
                {{ __("Delete Plan") }}
            </span>
        </button>

        <!-- Confirm Modal -->
        <VModal v-model="dialog" title="Delete Plan">
            <template #body>
                <div class="flex items-center gap-3 text-red-600">
                    <i class="mdi mdi-alert-circle text-3xl"></i>
                    <span class="text-xl font-bold">{{
                        __("Delete Plan")
                    }}</span>
                </div>

                <div class="space-y-5">
                    <div class="flex items-start gap-3">
                        <i
                            class="mdi mdi-alert text-3xl text-red-500 flex-shrink-0 mt-0.5"
                        ></i>
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-1"
                            >
                                {{ __("Are you sure?") }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                {{ __("You are about to delete the plan") }}
                                <strong class="text-red-600"
                                    >"{{ item.name }}"</strong
                                >.
                                {{ __("This action cannot be undone.") }}
                            </p>
                        </div>
                    </div>

                    <!-- Plan Info -->
                    <div
                        class="bg-gray-50 border border-gray-200 rounded-xl p-4"
                    >
                        <h4
                            class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3"
                        >
                            {{ __("Plan Details") }}
                        </h4>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2">
                                <i class="mdi mdi-tag text-gray-500"></i>
                                <span class="text-gray-800 font-medium">{{
                                    __("Name:")
                                }}</span>
                                <span class="ml-1 text-gray-700">{{
                                    item.name
                                }}</span>
                            </li>

                            <li
                                v-if="item.description"
                                class="flex items-start gap-2"
                            >
                                <i class="mdi mdi-text text-gray-500 mt-1"></i>
                                <div>
                                    <span class="text-gray-800 font-medium">{{
                                        __("Description:")
                                    }}</span>
                                    <p
                                        class="ml-1 text-gray-700 text-sm mt-1 line-clamp-2"
                                        v-html="item.description"
                                    ></p>
                                </div>
                            </li>

                            <li class="flex items-center gap-2">
                                <i
                                    class="mdi mdi-check-circle"
                                    :class="
                                        item.active
                                            ? 'text-green-500'
                                            : 'text-gray-400'
                                    "
                                ></i>
                                <span class="text-gray-800 font-medium">{{
                                    __("Status:")
                                }}</span>
                                <span
                                    class="ml-1 font-semibold"
                                    :class="
                                        item.active
                                            ? 'text-green-600'
                                            : 'text-gray-500'
                                    "
                                >
                                    {{
                                        item.active
                                            ? __("Active")
                                            : __("Inactive")
                                    }}
                                </span>
                            </li>
                        </ul>
                    </div>

                    <div
                        class="flex items-center gap-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg p-3"
                    >
                        <i
                            class="mdi mdi-information text-yellow-500 text-lg"
                        ></i>
                        <span>
                            {{
                                __(
                                    "This will permanently remove the plan and all related data."
                                )
                            }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4">
                        <button
                            @click="dialog = false"
                            class="px-5 py-2.5 rounded-xl font-semibold bg-gray-200 hover:bg-gray-300 text-gray-800 transition-all"
                        >
                            <i class="mdi mdi-close mr-1"></i>{{ __("Cancel") }}
                        </button>
                        <button
                            @click="destroy"
                            class="px-5 py-2.5 rounded-xl font-semibold bg-red-600 hover:bg-red-700 text-white transition-all"
                            :disabled="loading"
                        >
                            <i class="mdi mdi-delete mr-1"></i>
                            <span v-if="!loading">{{ __("Delete") }}</span>
                            <span v-else>{{ __("Deleting...") }}</span>
                        </button>
                    </div>
                </div>
            </template>
        </VModal>
    </div>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    components: { VModal },
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

                if (res.status === 200) {
                    this.dialog = false;
                    this.$emit("deleted", true);
                    $notify.success(__("was removed successfully."));
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e?.response?.data?.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
