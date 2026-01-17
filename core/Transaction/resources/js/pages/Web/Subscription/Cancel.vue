<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <button
        @click="dialog = true"
        class="w-full mt-2 px-4 py-2 border border-red-600 dark:border-red-300 text-red-600 dark:text-white rounded-lg font-medium hover:bg-red-50 dark:hover:bg-red-400 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center space-x-2"
    >
        <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
        </svg>
        <span>{{ __("Cancel payment") }}</span>
    </button>

    <v-modal v-model="dialog" panel-class="w-full lg:w-5xl">
        <template #body>
            <!-- Header -->
            <div
                class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-800"
            >
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __("Cancel operation") }}
                </h3>
                <button
                    @click="dialog = false"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6">
                <p class="text-gray-700 dark:text-white">
                    {{
                        __(
                            "Are you sure you want to cancel the process to payment?"
                        )
                    }}
                </p>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-between p-6 border-t border-gray-700 dar:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl"
            >
                <button
                    @click="cancel"
                    class="px-6 py-2 border border-green-600 text-green-600 rounded-lg font-medium hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200"
                >
                    {{ __("Accept") }}
                </button>
                <button
                    @click="dialog = false"
                    class="px-6 py-2 border border-red-600 text-red-600 rounded-lg font-medium hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200"
                >
                    {{ __("Cancel") }}
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
    emits: ["success"],

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
        async cancel() {
            try {
                const res = await this.$server.put(this.item.links.cancel);

                if (res.status == 200) {
                    this.$emit("success");
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
