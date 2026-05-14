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
    <!-- Create Personal Access Client Button -->

    <v-button
        @click="open"
        :title="__('Create a personal access client for API authentication')"
        :disabled="loading"
        :label="__('Create Personal Access Client')"
        icon="mdi mdi-lock"
    />

    <!-- Dialog -->
    <v-modal
        v-model="dialog"
        :title="__('Create Personal Access Client')"
        panel-class="w-full lg:w-3xl"
    >
        <template #body>
            <v-head
                :title="__('Api Token')"
                :description="
                    __(
                        'Personal access clients allow your applications to authenticate with the API using generated tokens.',
                    )
                "
            >
            </v-head>

            <!-- Form Content -->
            <div class="space-y-6 mt-4">
                <!-- Client Name Input -->
                <div class="space-y-3">
                    <v-input
                        :label="__('Client Name')"
                        v-model="form.name"
                        :error="errors.name"
                        :required="true"
                        :placeholder="
                            __('e.g., My API Client, Mobile App, etc.')
                        "
                        :disabled="loading"
                    />
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{
                            __(
                                "Choose a descriptive name to identify this personal access client",
                            )
                        }}
                    </p>
                </div>

                <!-- Security Notice -->
                <div
                    class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-lg p-4 border border-green-200 dark:border-green-800 transition-colors duration-200"
                >
                    <div class="flex items-start">
                        <svg
                            class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="text-sm">
                            <strong class="font-medium">{{
                                __("Security Note:")
                            }}</strong>
                            {{
                                __(
                                    "This client will be used to generate API tokens. Keep your tokens secure and never share them publicly.",
                                )
                            }}
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4"
                >
                    <div class="flex items-start space-x-3">
                        <svg
                            class="w-5 h-5 text-blue-500 dark:text-blue-400 mt-0.5 flex-shrink-0"
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
                        <div class="text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-medium mb-1">
                                {{ __("About Personal Access Clients") }}
                            </p>
                            <p>
                                {{
                                    __(
                                        "Personal access clients are suitable for first-party applications where you control both the client and the resource server. They use the Password Grant or Personal Access Token flow.",
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700"
            >
                <v-button
                    @click="close"
                    :disabled="loading"
                    :label="__('Cancel')"
                    variant="danger"
                />

                <v-button
                    @click="createPersonalAccessClient"
                    :disabled="loading || !isFormValid"
                    :label="loading ? __('Creating...') : __('Create Client')"
                />
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, computed } from "vue";
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VButton from "@/components/VButton.vue";
import VHead from "@/components/VHead.vue";

const emits = defineEmits(["created"]);

const dialog = ref(false);
const loading = ref(false);
const form = ref({
    name: "",
});
const errors = ref({});

const isFormValid = computed(() => {
    return form.value.name?.trim();
});

const open = () => {
    form.value.name = "";
    errors.value = {};
    dialog.value = true;
};

const close = () => {
    dialog.value = false;
    loading.value = false;
    form.value.name = "";
    errors.value = {};
};

const createPersonalAccessClient = async () => {
    if (!isFormValid.value) return;

    loading.value = true;
    errors.value = {};

    try {
        const res = await $server.post(page.props.routes.personal, form.value);

        if (res.status == 201) {
            $notify.success(__("Personal access client created successfully"));

            emits("created");
            close();
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
