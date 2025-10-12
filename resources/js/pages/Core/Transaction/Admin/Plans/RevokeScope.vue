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
        <button
            @click="dialog = true"
            class="flex items-center justify-center w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-gray-900 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110"
            :title="__('Revoke Scope')"
        >
            <i class="mdi mdi-key-remove text-sm"></i>
        </button>

        <v-modal v-model="dialog" :title="__('Revoke Access Scope')">
            <template #body>
                <div class="text-left space-y-6">
                    <div class="flex items-start gap-4">
                        <i
                            class="mdi mdi-alert text-3xl text-yellow-500 mt-1 flex-shrink-0"
                        ></i>
                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-800 mb-1"
                            >
                                {{ __("Confirm Scope Revocation") }}
                            </h3>
                            <p class="text-gray-600 text-sm">
                                {{
                                    __(
                                        "You are about to revoke the following access scope. This will remove permissions from the associated plan."
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="bg-gray-50 border-l-4 border-yellow-500 border rounded-xl p-4"
                    >
                        <h4
                            class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide"
                        >
                            {{ __("Scope Details") }}
                        </h4>

                        <div class="space-y-3 text-sm">
                            <!-- Role -->
                            <div class="flex items-center gap-3">
                                <i
                                    class="mdi mdi-account-key text-gray-500"
                                ></i>
                                <div>
                                    <span class="font-medium text-gray-700">{{
                                        __("Role:")
                                    }}</span>
                                    <span
                                        class="ml-2 text-yellow-700 font-bold"
                                    >
                                        {{ item.role.name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Service -->
                            <div class="flex items-center gap-3">
                                <i class="mdi mdi-server text-gray-500"></i>
                                <div>
                                    <span class="font-medium text-gray-700">{{
                                        __("Service:")
                                    }}</span>
                                    <span class="ml-2 text-gray-900">{{
                                        item.service.name
                                    }}</span>
                                </div>
                            </div>

                            <!-- Group -->
                            <div class="flex items-center gap-3">
                                <i
                                    class="mdi mdi-account-group text-gray-500"
                                ></i>
                                <div>
                                    <span class="font-medium text-gray-700">{{
                                        __("Group:")
                                    }}</span>
                                    <span class="ml-2 text-gray-900">{{
                                        item.service.group.name
                                    }}</span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div
                                v-if="item.role.description"
                                class="flex items-start gap-3"
                            >
                                <i
                                    class="mdi mdi-information text-gray-500 mt-1"
                                ></i>
                                <div class="flex-1">
                                    <span class="font-medium text-gray-700">{{
                                        __("Description:")
                                    }}</span>
                                    <div
                                        class="ml-2 text-gray-600 text-sm mt-1 bg-white p-2 rounded border border-gray-200"
                                    >
                                        {{ item.role.description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg p-3"
                    >
                        <i
                            class="mdi mdi-alert-circle text-yellow-500 mt-0.5 flex-shrink-0"
                        ></i>
                        <span>
                            {{
                                __(
                                    "Users with this plan will lose access to these permissions immediately."
                                )
                            }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button
                        @click="dialog = false"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold"
                    >
                        <i class="mdi mdi-close mr-1"></i>
                        {{ __("Cancel") }}
                    </button>

                    <button
                        @click="revoke"
                        class="px-4 py-2 rounded-lg bg-yellow-600 hover:bg-yellow-700 text-white font-semibold flex items-center gap-2"
                    >
                        <i class="mdi mdi-key-remove"></i>
                        {{ __("Revoke Scope") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VModal from "@/components/VModal.vue";

export default {
    components: { VModal },
    emits: ["revoked"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        async revoke() {
            try {
                const res = await this.$server.delete(this.item.links.revoke);

                if (res.status === 200) {
                    this.dialog = false;
                    this.$emit("revoked", true);

                    $notify.success(__("Scope revoked successfully"));
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e?.response?.data?.message);
                }
            }
        },
    },
};
</script>

<style scoped>
button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
button:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 20px -5px rgba(217, 119, 6, 0.4);
}
</style>
