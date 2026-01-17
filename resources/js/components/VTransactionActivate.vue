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
        class="inline-flex items-center px-4 py-2 border border-orange-500 dark:border-orange-400 text-orange-600 dark:text-orange-300 rounded-lg font-medium hover:bg-orange-50 dark:hover:bg-orange-900/20 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors duration-200"
    >
        <svg
            class="w-4 h-4 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 10V3L4 14h7v7l9-11h-7z"
            />
        </svg>
        {{ __("Activate Transaction") }}
    </button>

    <v-modal
        v-model="dialog"
        panel-class="w-full lg:w-3xl"
        :title="__('Activate Transaction')"
    >
        <template #body>
            <!-- Content -->
            <div class="p-6">
                <div class="flex items-start mb-4">
                    <svg
                        class="w-6 h-6 text-orange-500 dark:text-orange-400 mr-3 mt-0.5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                    </svg>
                    <span
                        class="text-gray-900 dark:text-gray-100 font-medium leading-relaxed"
                    >
                        {{
                            __(
                                "Are you sure you want to activate this transaction?"
                            )
                        }}
                    </span>
                </div>

                <hr class="my-4 border-gray-200 dark:border-gray-700" />

                <div
                    v-if="item.description"
                    class="bg-white dark:bg-gray-800 rounded-lg p-4"
                >
                    <div
                        class="text-sm text-gray-600 dark:text-gray-400 font-medium mb-1"
                    >
                        {{ __("Transaction Details") }}
                    </div>
                    <div class="text-gray-800 dark:text-gray-200">
                        {{ item.description }}
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-between items-center px-6 py-4 bg-white dark:bg-gray-800 rounded-b-2xl border-t border-gray-200 dark:border-gray-700"
            >
                <button
                    @click="dialog = false"
                    class="px-6 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="activateTransaction"
                    :disabled="disable"
                    class="inline-flex items-center px-6 py-2 bg-green-600 dark:bg-green-700 text-white font-medium rounded-lg hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>
                    {{ __("Activate") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { ref } from "vue";

const emits = defineEmits(["updated"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const dialog = ref(false);
const disable = ref(false);

const activateTransaction = async () => {
    disable.value = true;

    try {
        const res = await $server.put(props.item.links.activate);

        if (res.status == 200) {
            dialog.value = false;

            $notify.success(__("Transaction has been activated successfully"));

            emits("updated");
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        disable.value = false;
        dialog.value = false;
    }
};
</script>
