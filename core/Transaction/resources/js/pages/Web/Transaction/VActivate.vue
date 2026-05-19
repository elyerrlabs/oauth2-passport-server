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
    <div v-if="item.status == 'pending'">
        <v-button
            @click="dialog = true"
            :title="__('Activate Transaction')"
            :label="round ? '' : __('Activate Transaction')"
            icon="mdi mdi-check-circle"
            :round="round"
            variant="warning"
            size="xs"
        />

        <v-modal
            v-model="dialog"
            panel-class="w-full lg:w-2xl"
            :title="__('Activate Transaction')"
        >
            <template #body>
                <div class="p-6">
                    <!-- Advertencia -->
                    <div class="flex items-start mb-5">
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
                                    "Are you sure you want to activate this transaction?",
                                )
                            }}
                        </span>
                    </div>

                    <!-- Detalles de la transacción -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 mb-4 border border-gray-200 dark:border-gray-700"
                    >
                        <h4
                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2"
                        >
                            <i class="mdi mdi-receipt-text-outline"></i>
                            {{ __("Transaction Details") }}
                        </h4>

                        <dl class="space-y-2 text-sm">
                            <div class="flex flex-wrap gap-1">
                                <dt
                                    class="w-28 text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Code") }}:
                                </dt>
                                <dd
                                    class="font-mono text-gray-800 dark:text-gray-200"
                                >
                                    {{ item.code }}
                                </dd>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <dt
                                    class="w-28 text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Total") }}:
                                </dt>
                                <dd
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{ item.total }}
                                    {{ item.currency }}
                                </dd>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <dt
                                    class="w-28 text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Product") }}:
                                </dt>
                                <dd class="text-gray-800 dark:text-gray-200">
                                    {{ productName }}
                                </dd>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <dt
                                    class="w-28 text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Period") }}:
                                </dt>
                                <dd
                                    class="capitalize text-gray-800 dark:text-gray-200"
                                >
                                    {{ item.billing_period || "—" }}
                                </dd>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <dt
                                    class="w-28 text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Customer") }}:
                                </dt>
                                <dd class="text-gray-800 dark:text-gray-200">
                                    {{ item.owner?.name }}
                                    {{ item.owner?.last_name }}
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 block"
                                        >{{ item.owner?.email }}</span
                                    >
                                </dd>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                <dt
                                    class="w-28 text-gray-500 dark:text-gray-400"
                                >
                                    {{ __("Created") }}:
                                </dt>
                                <dd class="text-gray-800 dark:text-gray-200">
                                    {{ formatDate(item.created) }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Mensaje adicional si es renovación -->
                    <div
                        v-if="item.renew"
                        class="text-sm text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg flex items-start gap-2"
                    >
                        <i
                            class="mdi mdi-refresh-circle mdi-18px flex-shrink-0 mt-0.5"
                        ></i>
                        <span>{{
                            __(
                                "This transaction represents a renewal. Activating it will extend the existing subscription.",
                            )
                        }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center px-6 py-4">
                    <v-button
                        @click="dialog = false"
                        :disabled="loading"
                        :label="__('Cancel')"
                        variant="danger"
                    />
                    <v-button
                        @click="activateTransaction"
                        :disabled="loading || !hasActivateLink"
                        :label="loading ? __('Activating...') : __('Activate')"
                    />
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VButton from "@/components/VButton.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const emits = defineEmits(["updated"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    round: {
        type: Boolean,
        default: true,
    },
});

const form = useForm({});
const dialog = ref(false);
const loading = ref(false);

const hasActivateLink = computed(() => !!props.item?.links?.activate);

// Nombre del producto (prioriza meta.meta.name, luego meta.description o vacío)
const productName = computed(() => {
    const meta = props.item?.meta?.meta;
    if (meta?.name) return meta.name;
    if (meta?.description)
        return meta.description.replace(/<[^>]*>/g, "").substring(0, 60);
    return __("Unknown product");
});

// Formatear fecha
const formatDate = (dateString) => {
    if (!dateString) return "—";
    try {
        return new Intl.DateTimeFormat(undefined, {
            dateStyle: "medium",
            timeStyle: "short",
        }).format(new Date(dateString));
    } catch {
        return dateString;
    }
};

const activateTransaction = async () => {
    if (!hasActivateLink.value) {
        $notify.error(__("Activation link not available for this item."));
        return;
    }

    loading.value = true;

    form.put(props.item.links.activate, {
        onSuccess: (response) => {},
        onError: (errors) => {},
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>
