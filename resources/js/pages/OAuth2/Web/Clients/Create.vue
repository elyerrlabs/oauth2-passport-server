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
    <!-- Create Button -->
    <v-button
        @click="open"
        :label="item?.id ? '' : __('Create New OAuth Client')"
        :variant="item?.id ? 'success' : 'secondary'"
        :icon="item?.id ? 'mdi mdi-pencil' : 'mdi mdi-plus-circle'"
        :round="item?.id"
    />

    <!-- Creation Dialog -->
    <v-modal
        v-model="dialog"
        :title="
            item?.id ? __('Update OAuth Client') : __('Create New OAuth Client')
        "
        panel-class="w-full lg:w-5xl"
    >
        <template #body>
            <!-- Header -->
            <div v-if="!client?.id">
                <div class="mb-6">
                    <div class="text-gray-600 dark:text-gray-400 text-sm">
                        {{
                            item?.id
                                ? __(
                                      "Modify your OAuth 2.0 client application settings",
                                  )
                                : __(
                                      "Register a new OAuth 2.0 client application",
                                  )
                        }}
                    </div>
                </div>

                <!-- Form Content -->
                <div class="grid grid-cols-1 gap-6 mb-4">
                    <!-- Client Name -->
                    <v-input
                        v-model="form.name"
                        :label="__('Name')"
                        :required="true"
                        :error="errors.name"
                        :placeholder="
                            __(
                                'Enter a descriptive name for your client application',
                            )
                        "
                        :disabled="loading"
                    />

                    <!-- Redirect URI -->
                    <v-input
                        v-model="form.redirect"
                        :label="__('Redirect URI')"
                        :required="true"
                        :error="errors.redirect"
                        placeholder="https://yourapp.com/oauth/callback"
                        :disabled="loading"
                    />

                    <!-- Confidential Switch -->
                    <div class="space-y-3">
                        <v-switch
                            v-model="form.confidential"
                            :label="__('Confidential Client')"
                            :error="errors.confidential"
                            :disabled="loading || item?.id"
                        />

                        <!-- Switch Description -->
                        <div
                            :class="[
                                'flex items-start space-x-3 text-sm rounded-lg p-3 border transition-colors duration-200',
                                form.confidential
                                    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800'
                                    : 'bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700',
                            ]"
                        >
                            <svg
                                class="w-4 h-4 text-blue-500 dark:text-blue-400 mt-0.5 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <span>
                                {{
                                    form.confidential
                                        ? __(
                                              "Confidential clients can keep secrets secure (recommended for server-side applications).",
                                          )
                                        : __(
                                              "Public clients cannot keep secrets secure (suitable for SPA and mobile apps).",
                                          )
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700"
                >
                    <v-button
                        @click="close"
                        :disabled="loading"
                        :label="__('Cancel')"
                        variant="danger"
                    />

                    <v-button
                        @click="handleAction"
                        :disabled="loading || !isFormValid"
                        :label="
                            item?.id ? __('Update Client') : __('Create Client')
                        "
                        variant="success"
                    />
                </div>
            </div>

            <!-- Credentials Display -->
            <div
                v-else
                class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 animate-fade-in"
            >
                <!-- Security Alert -->
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6"
                >
                    <div class="flex items-center mb-3">
                        <svg
                            class="w-5 h-5 text-red-600 dark:text-red-400 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div
                            class="text-lg font-bold text-red-800 dark:text-red-300"
                        >
                            {{ __("Important Security Notice") }}
                        </div>
                    </div>

                    <p class="text-red-700 dark:text-red-300 text-sm mb-4">
                        {{
                            __(
                                "These credentials will only be shown once. Please store them securely immediately.",
                            )
                        }}
                    </p>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0"
                    >
                        <div
                            class="text-red-600 dark:text-red-400 text-sm flex items-center"
                        >
                            <svg
                                class="w-4 h-4 mr-2"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            {{
                                __("Download your credentials for safe keeping")
                            }}
                        </div>

                        <div class="flex space-x-2">
                            <v-button
                                @click="copyToClipboard(client.id, 'Client ID')"
                                :label="__('Copy Client ID to clipboard')"
                                variant="primary"
                            />

                            <v-button
                                v-if="client.secret"
                                @click="
                                    copyToClipboard(
                                        client.secret,
                                        'Client Secret',
                                    )
                                "
                                :label="__('Copy Client Secret to clipboard')"
                                variant="warning"
                            />

                            <v-button
                                v-if="client.secret"
                                @click="downloadJsonFile"
                                :label="__('Download as JSON file')"
                                variant="success"
                            />
                        </div>
                    </div>
                </div>

                <!-- Client Details -->
                <div>
                    <div
                        class="text-sm font-medium text-gray-900 dark:text-white mb-3"
                    >
                        {{ __("Client Details:") }}
                    </div>
                    <div
                        class="space-y-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                    >
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Client ID:") }}</span
                            >
                            <div
                                class="flex items-center space-x-2 mt-1 sm:mt-0"
                            >
                                <span
                                    class="text-sm text-gray-900 dark:text-white font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"
                                    >{{ client.id }}</span
                                >
                            </div>
                        </div>
                        <div
                            v-if="client.secret"
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Client Secret:") }}</span
                            >
                            <div
                                class="flex items-center space-x-2 mt-1 sm:mt-0"
                            >
                                <span
                                    class="text-sm text-red-600 dark:text-red-400 font-mono bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded border border-red-100 dark:border-red-800"
                                    >{{ client.secret }}</span
                                >
                            </div>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Name:") }}</span
                            >
                            <span
                                class="text-sm text-gray-900 dark:text-white mt-1 sm:mt-0"
                                >{{ client.name }}</span
                            >
                        </div>
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2"
                        >
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >{{ __("Type:") }}</span
                            >
                            <span
                                :class="[
                                    'text-sm font-medium mt-1 sm:mt-0',
                                    client.confidential
                                        ? 'text-green-600 dark:text-green-400'
                                        : 'text-orange-600 dark:text-orange-400',
                                ]"
                            >
                                {{
                                    client.confidential
                                        ? __("Confidential")
                                        : __("Public")
                                }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VSwitch from "@/components/VSwitch.vue";
import VButton from "@/components/VButton.vue";
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const props = defineProps({
    item: {
        type: Object,
        default: () => [],
    },
});
const emits = defineEmits(["created", "updated"]);
const dialog = ref(false);
const form = ref({
    name: "",
    redirect: "",
    confidential: false, // Default to true for better security
});
const errors = ref({});
const client = ref({});
const loading = ref(false);

const isFormValid = computed(() => {
    return form.value.name.trim() && form.value.redirect.trim();
});

const close = () => {
    clean();
    dialog.value = false;
};

const clean = () => {
    client.value = {};
    errors.value = {};
    form.value = {
        name: "",
        redirect: "",
        confidential: false,
    };
    loading.value = false;
};

const open = () => {
    clean();

    if (props.item?.id) {
        form.value.name = props.item.name;
        form.value.redirect = props.item.redirect;
        form.value.confidencial = props.item.confidencial;
    }

    dialog.value = true;
};

const copyToClipboard = async (text, type = "") => {
    try {
        await navigator.clipboard.writeText(text);
        $notify.success(
            __(`:type copied to clipboard`, {
                type: type || "Text",
            }),
        );
    } catch (e) {
        $notify.error(__("Failed to copy to clipboard"));
    }
};

const downloadJsonFile = () => {
    const clientCopy = { ...client.value };
    // Remove unnecessary properties
    delete clientCopy.links;
    delete clientCopy.revoked;
    delete clientCopy.provider;
    delete clientCopy.redirect;

    const filename = `${clientCopy.name || "client"}-credentials.json`;
    const jsonString = JSON.stringify(clientCopy, null, 2);
    const blob = new Blob([jsonString], { type: "application/octet-stream" });
    const url = URL.createObjectURL(blob);

    const link = document.createElement("a");
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    $notify.success(__("Credentials downloaded successfully"));
};

const handleAction = async () => {
    if (props.item?.id) {
        await updateClient();
    } else {
        await create();
    }
};

const create = async () => {
    if (!isFormValid.value) return;

    loading.value = true;
    errors.value = {};

    try {
        const res = await $server.post(page.props.route, form.value);

        if (res.status === 201) {
            client.value = res.data;
            emits("created");
            $notify.success(__("OAuth client created successfully"));
        }
    } catch (e) {
        if (e?.response?.status == 422) {
            errors.value = e.response.data.errors;
        }

        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};

const updateClient = async () => {
    if (!isFormValid.value) return;
    loading.value = true;
    errors.value = {};

    try {
        const res = await $server.put(props.item.links.update, form.value);

        if (res.status == 200) {
            emits("updated");
            dialog.value = false;

            $notify.success(__("OAuth client updated successfully"));
        }
    } catch (e) {
        if (e?.response?.status == 422) {
            errors.value = e.response.data.errors;
        } else if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
