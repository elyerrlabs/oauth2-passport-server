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
        <!-- Action Button -->
        <v-button
            :label="item?.id ? '' : __('Add new role')"
            :title="item?.id ? __('Updated new role') : __('Add new role')"
            :round="item?.id ? true : false"
            icon="mdi mdi-plus-circle text-3xl"
            @click="open"
            :variant="item?.id ? 'success' : 'secondary'"
        />

        <!-- Modal -->
        <v-modal
            v-model="dialog"
            :title="!item?.id ? __('Create New Role') : __('Update Role')"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <v-input
                        :label="__('Name')"
                        v-model="form.name"
                        :placeholder="__('Enter role name')"
                        :required="true"
                        :error="form.errors.name"
                        :disabled="item?.system"
                    />

                    <div class="flex items-end">
                        <v-switch
                            v-show="item?.id | item?.system"
                            :label="__('System Role')"
                            v-model="form.system"
                            :error="form.errors.system"
                            :placeholder="
                                __(
                                    'System roles have special permissions and cannot be deleted.',
                                )
                            "
                            :disabled="item?.id"
                        />
                    </div>
                </div>

                <div class="px-6 pb-6 grid grid-cols-1">
                    <v-textarea
                        :label="__('Description')"
                        v-model="form.description"
                        :placeholder="__('Enter role description...')"
                        :required="true"
                        :error="form.errors.description"
                        :disabled="item?.system"
                    />
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl"
                >
                    <v-button
                        :label="__('Cancel')"
                        :title="__('Cancel')"
                        :disabled="loading"
                        variant="danger"
                        @click="dialog = false"
                        icon="mdi mdi-close-circle"
                    />

                    <v-button
                        @click="handleAction"
                        :disabled="loading"
                        variant="success"
                        :icon="
                            item?.id
                                ? 'mdi mdi-content-save'
                                : 'mdi mdi-check-circle'
                        "
                        :label="
                            item?.id ? __('Update role') : __('Create role')
                        "
                        :title="
                            item?.id ? __('Update role') : __('Create role')
                        "
                    />
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VSwitch from "@/components/VSwitch.vue";
import VButton from "@/components/VButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();

const emits = defineEmits(["created", "updated"]);

const props = defineProps({
    item: {
        type: Object,
        default: null,
    },
});

const dialog = ref(false);
const loading = ref(false);
const form = useForm({
    name: null,
    description: null,
    system: false,
});

// methods
const open = () => {
    dialog.value = true;
    form.resetAndClearErrors();
    if (props.item?.id) {
        form.name = props.item.name;
        form.description = props.item.description;
        form.system = props.item.system;
    }
};

const createRole = () => {
    form.post(page.props.routes.roles, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            $notify.success(__("Role created successfully"));
            dialog.value = false;
            form.errors.value = {};
            emits("created");
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const updateRole = async () => {
    form.put(props.item.links.update, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            $notify.success(__("Role updated successfully"));
            dialog.value = false;
            form.errors.value = {};
            form.value = {};
            emits("updated");
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

const handleAction = () => {
    loading.value = true;
    if (props.item?.id) {
        updateRole();
    } else {
        createRole();
    }
};
</script>
