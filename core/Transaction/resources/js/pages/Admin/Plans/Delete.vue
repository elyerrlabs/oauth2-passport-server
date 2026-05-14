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
        <!-- Delete Button -->
        <v-button
            @click="dialog = true"
            icon="mdi mdi-trash-can-outline"
            round
            :title="__('Delete plan')"
            variant="danger"
        />

        <!-- Confirm Modal -->
        <VModal
            v-model="dialog"
            :title="__('Delete Subscription Plan')"
            panel-class="w-full lg:w-5xl"
        >
            <template #body>
                <div class="space-y-6">
                    <!-- Warning Header -->
                    <div class="flex items-start gap-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-alert-circle-outline text-2xl text-red-600 dark:text-red-400"
                            ></i>
                        </div>
                        <div class="flex-1">
                            <h3
                                class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2"
                            >
                                {{ __("Delete Subscription Plan") }}
                            </h3>
                            <p
                                class="text-gray-600 dark:text-gray-300 leading-relaxed"
                            >
                                {{
                                    __(
                                        "You are about to permanently delete the plan",
                                    )
                                }}
                                <strong
                                    class="text-red-600 dark:text-red-400 font-semibold"
                                    >"{{ item.name }}"</strong
                                >.
                                {{
                                    __(
                                        "This action cannot be undone and will affect all associated data.",
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Plan Details Card -->
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl p-6 shadow-sm"
                    >
                        <div
                            class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700"
                        >
                            <i
                                class="mdi mdi-information text-gray-500 dark:text-gray-400"
                            ></i>
                            <h4
                                class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wide"
                            >
                                {{ __("Plan Information") }}
                            </h4>
                        </div>

                        <div class="space-y-4">
                            <!-- Name -->
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-tag text-blue-600 dark:text-blue-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 text-sm mb-1"
                                    >
                                        {{ __("Plan Name") }}
                                    </div>
                                    <div
                                        class="text-gray-900 dark:text-gray-100 font-semibold"
                                    >
                                        {{ item.name }}
                                    </div>
                                    <div
                                        v-if="item.slug"
                                        class="text-xs text-gray-500 dark:text-gray-400 font-mono mt-1"
                                    >
                                        {{ item.slug }}
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-check-circle text-gray-600 dark:text-gray-400 text-sm"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <div
                                        class="font-medium text-gray-700 dark:text-gray-300 text-sm mb-1"
                                    >
                                        {{ __("Status") }}
                                    </div>
                                    <span
                                        :class="[
                                            'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border',
                                            item.active
                                                ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800'
                                                : 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
                                        ]"
                                    >
                                        <i
                                            :class="[
                                                'mdi mr-1.5 text-base',
                                                item.active
                                                    ? 'mdi-check-circle'
                                                    : 'mdi-close-circle',
                                            ]"
                                        ></i>
                                        {{
                                            item.active
                                                ? __("Active")
                                                : __("Inactive")
                                        }}
                                    </span>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-gray-100 dark:border-gray-700"
                            >
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <i class="mdi mdi-cash-multiple"></i>
                                    <span
                                        >{{ __("Prices:") }}
                                        <strong
                                            class="text-gray-800 dark:text-gray-200"
                                            >{{
                                                item.prices?.length || 0
                                            }}</strong
                                        ></span
                                    >
                                </div>
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <i class="mdi mdi-key-chain"></i>
                                    <span
                                        >{{ __("Scopes:") }}
                                        <strong
                                            class="text-gray-800 dark:text-gray-200"
                                            >{{
                                                item.scopes?.length || 0
                                            }}</strong
                                        ></span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Critical Warning -->
                    <div
                        class="flex items-start gap-4 p-4 rounded-xl border-2"
                        :class="[
                            'bg-red-50 dark:bg-red-900/20',
                            'border-red-200 dark:border-red-700',
                            'text-red-800 dark:text-red-200',
                        ]"
                    >
                        <i
                            class="mdi mdi-alert-octagon text-red-500 dark:text-red-400 mt-0.5 shrink-0 text-xl"
                        ></i>
                        <div class="flex-1">
                            <div
                                class="font-bold text-red-900 dark:text-red-100 mb-2"
                            >
                                {{ __("Critical: Permanent Deletion") }}
                            </div>
                            <p
                                class="text-red-700 dark:text-red-200/90 leading-relaxed"
                            >
                                {{
                                    __(
                                        "This will permanently delete the plan, all associated pricing configurations, and access scopes. This action cannot be undone and may affect existing subscriptions.",
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div
                        class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700"
                    >
                        <v-button
                            @click="dialog = false"
                            icon="mdi mdi-close"
                            variant="danger"
                            :label="__('Cancel')"
                        />

                        <v-button
                            @click="destroy"
                            :disabled="form.processing"
                            :icon="
                                form.processing
                                    ? 'mdi mdi-loading mdi-spin text-lg'
                                    : 'mdi mdi-trash-can-outline text-lg'
                            "
                            :label="
                                form.processing
                                    ? __('Deleting...')
                                    : __('Delete Plan')
                            "
                        />
                    </div>
                </div>
            </template>
        </VModal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VButton from "@/components/VButton.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const emits = defineEmits(["deleted"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const dialog = ref(false);

const form = useForm({});

const destroy = () => {
    form.delete(props.item.links.destroy, {
        onSuccess: () => {
            emits("deleted");
            dialog.value = false;
            $notify.success(__("Plan was deleted successfully"));
        },
        onError: (errors) => {
            $notify.error(__("Failed to delete plan"));
        },
    });
};
</script>
