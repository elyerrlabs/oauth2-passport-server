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
    <v-layout>
        <template #aside>
            <v-item-menu
                :items="page.props.menus"
                icon="mdi mdi-apps text-2xl"
                :collapse="true"
            />
        </template>
        <template #main>
            <!-- Header -->
            <v-head
                :title="data.name"
                :description="__('Plan details and configuration')"
            >
                <template #actions>
                    <div class="flex items-center gap-2">
                        <v-button
                            :label="__('Back to Plans')"
                            icon="mdi mdi-arrow-left"
                            variant="secondary"
                            size="sm"
                            @click="goToIndex"
                        />
                        <v-button
                            :label="__('Edit Plan')"
                            icon="mdi mdi-pencil"
                            variant="warning"
                            size="sm"
                            @click="goToEdit"
                        />
                        <span
                            :class="[
                                'px-3 py-1 text-xs font-medium rounded-full',
                                data.active
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                            ]"
                        >
                            {{ data.active ? __("Active") : __("Inactive") }}
                        </span>
                    </div>
                </template>
            </v-head>

            <!-- Basic Information -->
            <div
                class="p-6 my-4 border border-gray-200 dark:border-gray-400 shadow rounded bg-white dark:bg-gray-800"
            >
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4"
                >
                    {{ __("Basic Information") }}
                </h3>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Plan Name") }}
                        </p>
                        <p
                            class="text-base font-medium text-gray-800 dark:text-gray-200"
                        >
                            {{ data.name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Slug") }}
                        </p>
                        <p
                            class="text-base font-medium text-gray-800 dark:text-gray-200"
                        >
                            {{ data.slug }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Status") }}
                        </p>
                        <span
                            :class="[
                                'inline-block px-2 py-0.5 text-xs font-medium rounded',
                                data.active
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                            ]"
                        >
                            {{ data.active ? __("Active") : __("Inactive") }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Trial") }}
                        </p>
                        <p
                            class="text-base font-medium text-gray-800 dark:text-gray-200"
                        >
                            {{
                                data.trial_enabled
                                    ? `${data.trial_duration} days`
                                    : __("Not available")
                            }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Bonus") }}
                        </p>
                        <p
                            class="text-base font-medium text-gray-800 dark:text-gray-200"
                        >
                            {{
                                data.bonus_enabled
                                    ? `${data.bonus_duration} days`
                                    : __("Not available")
                            }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Created") }}
                        </p>
                        <p
                            class="text-base font-medium text-gray-800 dark:text-gray-200"
                        >
                            {{ data.created }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __("Updated") }}
                        </p>
                        <p
                            class="text-base font-medium text-gray-800 dark:text-gray-200"
                        >
                            {{ data.updated }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div
                v-if="data.description"
                class="p-6 my-4 border border-gray-200 dark:border-gray-400 shadow rounded bg-white dark:bg-gray-800"
            >
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4"
                >
                    {{ __("Description") }}
                </h3>
                <div
                    class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300"
                    v-html="data.description"
                ></div>
            </div>

            <!-- Pricing -->
            <div
                class="p-6 my-4 border border-gray-200 dark:border-gray-400 shadow rounded bg-white dark:bg-gray-800"
            >
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4"
                >
                    {{ __("Pricing") }}
                </h3>

                <div v-if="data.prices?.length" class="space-y-2">
                    <div
                        v-for="price in data.prices"
                        :key="price.id"
                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"
                    >
                        <div class="flex items-center gap-4">
                            <span
                                class="text-sm font-medium text-gray-600 dark:text-gray-400 capitalize"
                            >
                                {{ price.billing_period }}
                            </span>
                            <span
                                class="text-xs text-gray-500 dark:text-gray-500"
                            >
                                {{ price.currency }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span
                                class="text-lg font-bold text-gray-800 dark:text-gray-200"
                            >
                                {{ price.amount_format }}
                            </span>
                            <span
                                v-if="price.expiration"
                                class="text-xs text-gray-500 dark:text-gray-400"
                            >
                                {{ __("Until") }} {{ price.expiration }}
                            </span>
                        </div>
                        <div>
                            <v-delete-price :item="price" />
                        </div>
                    </div>
                </div>

                <div v-else class="text-gray-500 dark:text-gray-400 text-sm">
                    {{ __("No pricing configured") }}
                </div>
            </div>

            <!-- Scopes -->
            <div
                class="p-6 my-4 border border-gray-200 dark:border-gray-400 shadow rounded bg-white dark:bg-gray-800"
            >
                <h3
                    class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4"
                >
                    {{ __("Access Scopes") }}
                </h3>

                <div v-if="data.scopes?.length">
                    <div
                        v-for="group in groupedScopes"
                        :key="group.service.id"
                        class="mb-6 last:mb-0"
                    >
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase"
                            >
                                {{ group.service.name }}
                            </span>
                            <span
                                class="text-xs text-gray-400 dark:text-gray-500"
                            >
                                {{ group.service.group?.name }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div
                                v-for="scope in group.scopes"
                                :key="scope.id"
                                class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"
                            >
                                <div class="flex justify-between">
                                    <div class="flex-1">
                                        <p
                                            class="font-medium text-gray-800 dark:text-gray-200"
                                        >
                                            {{ scope.gsr_id }}
                                        </p>
                                        <p
                                            class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                                        >
                                            {{ scope.role?.name }} -
                                            {{ scope.role?.description }}
                                        </p>
                                    </div>
                                    <div class="shrink-0">
                                        <v-revoke-scope :item="scope" />
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <span
                                        v-if="scope.api_key"
                                        class="text-xs px-2 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded"
                                    >
                                        API
                                    </span>
                                    <span
                                        v-if="scope.web"
                                        class="text-xs px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded"
                                    >
                                        Web
                                    </span>
                                    <span
                                        :class="[
                                            'text-xs px-2 py-0.5 rounded',
                                            scope.active
                                                ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300'
                                                : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300',
                                        ]"
                                    >
                                        {{
                                            scope.active
                                                ? __("Active")
                                                : __("Inactive")
                                        }}
                                    </span>
                                    <span
                                        :class="[
                                            'text-xs px-2 py-0.5 rounded',
                                            scope.public
                                                ? 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
                                        ]"
                                    >
                                        {{
                                            scope.public
                                                ? __("Public")
                                                : __("Private")
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-gray-500 dark:text-gray-400 text-sm">
                    {{ __("No scopes assigned") }}
                </div>
            </div>

            <!-- Footer Actions -->
            <div
                class="flex justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-b"
            >
                <v-button
                    :label="__('Back to Plans')"
                    icon="mdi mdi-arrow-left"
                    variant="secondary"
                    @click="goToIndex"
                />
                <v-button
                    :label="__('Edit Plan')"
                    icon="mdi mdi-pencil"
                    variant="warning"
                    @click="goToEdit"
                />
            </div>
        </template>
    </v-layout>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import VHead from "@/components/VHead.vue";
import VButton from "@/components/VButton.vue";
import { usePage } from "@inertiajs/vue3";
import VDeletePrice from "./DeletePrice.vue";
import VRevokeScope from "./RevokeScope.vue";

const page = usePage();

const props = defineProps({
    data: {
        type: Object,
        default: () => {},
    },
});

const groupedScopes = computed(() => {
    if (!props.data.scopes?.length) return [];

    const groups = new Map();

    props.data.scopes.forEach((scope) => {
        const serviceId = scope.service?.id;
        if (!serviceId) return;

        if (!groups.has(serviceId)) {
            groups.set(serviceId, {
                service: scope.service,
                scopes: [],
            });
        }
        groups.get(serviceId).scopes.push(scope);
    });

    return Array.from(groups.values());
});

const goToIndex = () => {
    if (props.data.links?.parent) {
        router.visit(props.data.links.parent);
    }
};

const goToEdit = () => {
    if (props.data.links?.edit) {
        router.visit(props.data.links.edit);
    }
};
</script>

<style scoped></style>
