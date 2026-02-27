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
    <div>
        <button
            @click="open"
            class="p-1 rounded-full text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200"
        >
            <span class="mdi mdi-delete text-xl"></span>
        </button>

        <v-modal
            v-model="dialog"
            panel-class="w-full lg:w-4xl"
            :title="__('Delete delivery Addresses')"
        >
            <template #body>
                <div class="px-4 py-6">
                    <div
                        class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-4"
                    >
                        <h3
                            class="text-lg font-medium text-gray-900 dark:text-white mb-3"
                        >
                            {{ __("Delivery address details") }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Full name") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.full_name || "N/A" }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Country") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.country || "N/A" }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("State/Region") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.state || "N/A" }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("City") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.city || "N/A" }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("District") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.district || "N/A" }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Postal code") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.postal_code || "N/A" }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Address") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.address || "N/A" }}
                                </p>
                            </div>

                            <div
                                v-if="item.address_line_2"
                                class="md:col-span-2"
                            >
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Address line 2") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.address_line_2 }}
                                </p>
                            </div>

                            <div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Phone") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.phone || "N/A" }}
                                </p>
                            </div>

                            <div v-if="item.secondary_phone">
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Secondary phone") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.secondary_phone }}
                                </p>
                            </div>

                            <div v-if="item.references" class="md:col-span-2">
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("References") }}
                                </p>
                                <p
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.references }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <p class="text-gray-600 dark:text-gray-300">
                            {{
                                __(
                                    "Are you sure you want to delete this delivery address? This action cannot be undone.",
                                )
                            }}
                        </p>
                    </div>
                </div>

                <div
                    class="px-4 py-3 bg-gray-50 dark:bg-gray-800 flex justify-end gap-3"
                >
                    <button
                        @click="dialog = false"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                    >
                        {{ __("Cancel") }}
                    </button>
                    <button
                        @click="destroy"
                        class="px-4 py-2 bg-red-600 cursor-pointer hover:bg-red-700 text-white rounded-lg transition-colors duration-200 flex items-center gap-2"
                    >
                        <span class="mdi mdi-delete"></span>
                        {{ __("Delete delivery address") }}
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { ref } from "vue";

const props = defineProps({
    item: {
        type: Object,
        default: () => ({}),
    },
});

const emits = defineEmits(["deleted"]);

const dialog = ref(false);

const open = () => {
    dialog.value = true;
};

const destroy = async () => {
    try {
        const res = await $server.delete(props.item.links.delete);

        if (res.status === 200) {
            $notify.success(__("Delivery address deleted successfully"));
            emits("deleted");
            dialog.value = false;
            errors.value = {};
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};
</script>
