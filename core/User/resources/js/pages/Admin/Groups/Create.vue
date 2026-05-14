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
                        :error="errors.name"
                        :required="true"
                        :disabled="item?.system"
                    />

                    <v-textarea
                        :label="__('Description')"
                        v-model="form.description"
                        :error="errors.description"
                        :placeholder="__('Write a short description...')"
                        :required="true"
                        :disabled="item?.system"
                    />

                    <v-switch
                        v-show="item?.id | item?.system"
                        :label="__('System Group')"
                        v-model="form.system"
                        :disabled="item?.system"
                        :error="errors.system"
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
                        :label="item?.id ? '' : __('New group')"
                        :icon="
                            isEdit
                                ? 'mdi mdi-content-save'
                                : 'mdi mdi-check-circle'
                        "
                        size="md"
                        :round="!!item?.id"
                        :disabled="processing"
                        :variant="item?.id ? 'success' : 'secondary'"
                        :title="
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
import { usePage } from "@inertiajs/vue3";
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

const form = ref({
    name: null,
    description: null,
    system: false,
});

const errors = ref({});

// Methods
const open = () => {
    errors.value = {};
    if (props.item?.id) {
        form.value = { ...props.item };
    }
    dialog.value = true;
};

const close = () => {
    errors.value = {};
    dialog.value = false;
};

const submit = () => {
    processing.value = true;
    if (isEdit.value) updateGroup();
    else createGroup();
};

const createGroup = async () => {
    try {
        const res = await $server.post(page.props.api.groups, form.value);

        if (res.status == 201) {
            $notify.success(__("Group created successfully"));
            dialog.value = false;
            emit("created");
        }
    } catch (error) {
        if (error?.response?.status == 422) {
            errors.value = error.response.data.errors;
        }

        if (error?.response?.data?.message) {
            $notify.error(__(error.response.data.message));
        }
    } finally {
        processing.value = false;
    }
};

const updateGroup = async () => {
    try {
        const res = await $server.put(props.item.links.update, form.value);

        if (res.status == 200) {
            $notify.success(__("Group updated successfully"));
            dialog.value = false;
            emit("updated");
        }
    } catch (error) {
        if (error?.response?.status == 422) {
            errors.value = error.response.data.errors;
        }

        if (error?.response?.data?.message) {
            $notify.error(__(error.response.data.message));
        }
    } finally {
        processing.value = false;
    }
};
</script>
