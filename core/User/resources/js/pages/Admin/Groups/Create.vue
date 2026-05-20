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
        <!-- Trigger Button -->
        <v-button
            @click="open"
            :label="item?.id ? '' : __('Add group')"
            :icon="item?.id ? 'mdi mdi-pencil text-lg' : 'mdi mdi-plus-circle'"
            size="md"
            :round="!!item?.id"
            :variant="item?.id ? 'success' : 'secondary'"
            :aria-label="item?.id ? __('Update group') : __('Add new group')"
            :title="item?.id ? __('Update group') : __('Add new group')"
        />

        <!-- Modal -->
        <v-modal
            v-model="dialog"
            :title="isEdit ? __('Update Group') : __('Create New Group')"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <!-- Form -->
                <div class="p-6 space-y-6">
                    <v-input
                        :label="__('Group Name')"
                        :placeholder="__('Enter group name')"
                        v-model="form.name"
                        :error="form.errors.name"
                        :required="true"
                        :disabled="item?.system"
                    />

                    <v-textarea
                        :label="__('Description')"
                        v-model="form.description"
                        :error="form.errors.description"
                        :placeholder="__('Write a short description...')"
                        :required="true"
                        :disabled="item?.system"
                    />

                    <v-switch
                        v-show="item?.id | item?.system"
                        :label="__('System Group')"
                        v-model="form.system"
                        :disabled="item?.system"
                        :error="form.errors.system"
                        :placeholder="
                            __(
                                'System groups have special permissions and cannot be deleted.',
                            )
                        "
                    />
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50"
                >
                    <v-button
                        :label="__('Cancel')"
                        :disabled="processing"
                        @click="close"
                        icon="mdi mdi-close-circle"
                        variant="danger"
                    />

                    <v-button
                        @click="submit"
                        :icon="
                            isEdit
                                ? 'mdi mdi-content-save'
                                : 'mdi mdi-check-circle'
                        "
                        size="md"
                        :disabled="processing"
                        :variant="item?.id ? 'success' : 'secondary'"
                        :label="
                            processing
                                ? isEdit
                                    ? __('Updating...')
                                    : __('Creating...')
                                : isEdit
                                  ? __('Update Group')
                                  : __('Create Group')
                        "
                    />
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VSwitch from "@/components/VSwitch.vue";
import VButton from "@/components/VButton.vue";

// Emits
const emit = defineEmits(["created", "updated"]);

// Props
const props = defineProps({
    item: {
        type: Object,
        default: () => {},
    },
});

// Reactive State
const dialog = ref(false);
const page = usePage();
const isEdit = computed(() => !!props.item);

const processing = ref(false);

const form = useForm({
    name: null,
    description: null,
    system: false,
});

const errors = ref({});

// Methods
const open = () => {
    form.errors.value = {};
    if (props.item?.id) {
        form.name = props.item.name;
        form.description = props.item.description;
        form.system = props.item.system;
    }
    dialog.value = true;
};

const close = () => {
    form.errors.value = {};
    dialog.value = false;
};

const submit = () => {
    processing.value = true;
    if (isEdit.value) updateGroup();
    else createGroup();
};

const createGroup = () => {
    form.post(page.props.routes.groups, {
        forceFormData: true,
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            $notify.success(__("Group created successfully"));
            dialog.value = false;
            emit("created");
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};

const updateGroup = async () => {
    form.put(props.item.links.update, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            $notify.success(__("Group updated successfully"));
            dialog.value = false;
            emit("updated");
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>
