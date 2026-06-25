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
        <!-- Button -->
        <v-button
            @click="open"
            :icon="icon"
            :label="scope?.id ? '' : __('Add Scope')"
            :round="scope?.id ? true : false"
        />

        <v-modal
            v-model="dialog"
            :title="scope ? __('Update Scope') : __('Add New Scope')"
            panel-class="w-full lg:w-6xl"
        >
            <template #body>
                <!-- Form Content -->
                <div class="p-6 space-y-6">
                    <!-- Role Select -->
                    <div class="space-y-2">
                        <label
                            class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            <i
                                class="mdi mdi-account-key text-gray-500 dark:text-gray-400"
                            ></i>
                            {{ __("Role") }}
                        </label>
                        <div class="relative">
                            <v-select
                                :label="__('Select Role')"
                                v-model="form.role_id"
                                :error="form.errors.role_id"
                                :options="roles"
                                :required="true"
                                searchable
                            />
                        </div>
                    </div>
                    <!-- Permissions Section -->
                    <div class="space-y-4">
                        <h4
                            class="text-lg font-medium text-gray-800 dark:text-gray-200"
                        >
                            {{ __("Permissions") }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- API Key Access -->
                            <v-switch
                                v-model="form.api_key"
                                :label="__('API Key Access')"
                                :error="form.errors.api_key"
                                :required="true"
                                :placeholder="
                                    __(
                                        'Allow access via API keys for automated systems',
                                    )
                                "
                            />

                            <!-- Web Access -->
                            <v-switch
                                v-model="form.web"
                                :label="__('Web Access')"
                                :error="form.errors.web"
                                :required="true"
                                :placeholder="
                                    __(
                                        'Make available for web routes (requires login)',
                                    )
                                "
                            />

                            <!-- Active -->
                            <v-switch
                                v-model="form.active"
                                :label="__('Active')"
                                :error="form.errors.active"
                                :required="true"
                                :placeholder="
                                    __('Enable this scope for immediate use')
                                "
                            />

                            <!-- Public Access -->
                            <v-switch
                                v-model="form.public"
                                :label="__('Public Access')"
                                :error="form.errors.public"
                                :required="true"
                                :placeholder="
                                    __(
                                        'Make available to all users without authentication',
                                    )
                                "
                            />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl transition-colors duration-200"
                >
                    <v-button
                        @click="dialog = false"
                        :label="__('Cancel')"
                        :disabled="loading"
                        variant="danger"
                        icon="mdi mdi-close-circle"
                    />

                    <v-button
                        @click="addScopes"
                        :disabled="loading"
                        :label="
                            scope?.id ? __('Update scope') : __('Add scope')
                        "
                        :icon="scope?.id ? 'mdi mdi-update' : 'mdi mdi-plus'"
                    />
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref } from "vue";
import VModal from "@/components/VModal.vue";
import VSelect from "@/components/VSelect.vue";
import VSwitch from "@/components/VSwitch.vue";
import VButton from "@/components/VButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const emits = defineEmits(["created"]);
const page = usePage();

const props = defineProps({
    icon: {
        type: String,
        default: "mdi mdi-lock-open-plus-outline",
    },
    scope: {
        type: Object,
        required: false,
    },
    link: {
        type: String,
        required: true,
    },
});

const dialog = ref(false);
const loading = ref(false);
const roles = ref([]);

const form = useForm({
    api_key: false,
    web: false,
    active: false,
    public: false,
    role_id: "",
});

const resetForm = () => {
    form.api_key = false;
    form.web = false;
    form.active = false;
    form.public = false;
    form.role_id = "";
};

const open = async () => {
    resetForm();
    if (props.scope?.id) {
        form.api_key = props.scope.api_key;
        form.web = props.scope.web;
        form.active = props.scope.active;
        form.public = props.scope.public;
        form.role_id = props.scope?.role?.id;
    }

    dialog.value = true;
    await getRoles();
};

const getRoles = async () => {
    try {
        const res = await $server.get(page.props.api.roles, {
            params: {
                per_page: 150,
            },
        });
        if (res.status == 200) {
            roles.value = res.data.data;
        }
    } catch (error) {
        if (error?.response?.data?.message) {
            $notify.error(error.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};

const addScopes = async () => {
    loading.value = true;

    form.post(props.link, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(
                props.scope?.id
                    ? __("Scope updated successfully")
                    : __("Scope added successfully"),
            );
            emits("created");
            dialog.value = false;
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
