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
        <button
            v-if="!item"
            @click="open()"
            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 px-4 py-2 text-white rounded-lg flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
            :class="{ 'animate-pulse': !dialog }"
        >
            <i class="mdi mdi-plus-circle text-xl mr-2"></i>
            {{ __("Create New") }}
        </button>

        <button
            v-else
            @click="open()"
            class="w-8 h-8 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-opacity-50"
        >
            <i class="mdi mdi-pencil text-lg"></i>
        </button>

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
                        :placeholder="
                            __(
                                'System groups have special permissions and cannot be deleted.'
                            )
                        "
                    />
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50"
                >
                    <button
                        @click="close"
                        :disabled="form.processing"
                        class="px-6 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium rounded-lg flex items-center space-x-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="mdi mdi-close-circle"></i>
                        <span>{{ __("Cancel") }}</span>
                    </button>

                    <button
                        @click="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-blue-600 dark:bg-blue-700 hover:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium rounded-lg flex items-center space-x-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i
                            class="mdi"
                            :class="
                                isEdit ? 'mdi-content-save' : 'mdi-check-circle'
                            "
                            v-if="!form.processing"
                        ></i>
                        <div
                            v-else
                            class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
                        ></div>
                        <span>
                            {{
                                form.processing
                                    ? isEdit
                                        ? __("Updating...")
                                        : __("Creating...")
                                    : isEdit
                                    ? __("Update Group")
                                    : __("Create Group")
                            }}
                        </span>
                    </button>
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

// Emits
const emit = defineEmits(["created", "updated"]);

// Props
const props = defineProps({
    item: {
        type: Object,
        default: null,
    },
});

// Reactive State
const dialog = ref(false);
const page = usePage();
const isEdit = computed(() => !!props.item);

// useForm (shared for create & edit)
const form = useForm({
    name: null,
    description: null,
    system: false,
});

// Methods
const open = () => {
    form.resetAndClearErrors();
    if (props.item?.id) {
        form.name = props.item.name;
        form.description = props.item.description;
        form.system = props.item.system;
    }
    dialog.value = true;
};

const close = () => {
    form.errors = {};
    dialog.value = false;
};

const submit = () => {
    if (isEdit.value) updateGroup();
    else createGroup();
};

const createGroup = () => {
    form.post(page.props.route, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("Group created successfully"));
            dialog.value = false;
            emit("created");
        },
        onError: () => {},
    });
};

const updateGroup = () => {
    form.put(props.item.links.update, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("Group updated successfully"));
            dialog.value = false;
            emit("updated");
        },
        onError: () => {},
    });
};
</script>
