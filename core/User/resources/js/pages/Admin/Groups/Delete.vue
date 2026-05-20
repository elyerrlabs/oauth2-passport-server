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
    <v-button
        @click="dialog = true"
        icon="mdi mdi-delete text-lg"
        round
        :title="__('Delete Group')"
        variant="danger"
    />

    <!-- Confirmation Modal -->
    <v-modal
        v-model="dialog"
        :title="__('Delete Group')"
        panel-class="w-full lg:w-3xl"
    >
        <template #body>
            <div class="p-6 space-y-4">
                <!-- Warning Icon -->
                <div
                    class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full"
                >
                    <i
                        class="mdi mdi-alert-circle-outline text-red-600 dark:text-red-400 text-xl"
                    ></i>
                </div>

                <!-- Warning Text -->
                <div class="text-center">
                    <h3
                        class="text-lg font-semibold text-gray-900 dark:text-white mb-2"
                    >
                        {{ __("This action is irreversible") }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        {{
                            __(
                                "Please type the group name to confirm deletion.",
                            )
                        }}
                    </p>
                </div>

                <!-- Group Name Display   -->
                <div class="text-center">
                    <code
                        class="bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg text-sm font-mono text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700"
                    >
                        {{ item.slug }}
                    </code>
                </div>

                <!-- Confirmation Input -->
                <v-input
                    :label="__('Type the group name to confirm')"
                    :placeholder="__('Enter group name')"
                    v-model="confirmationText"
                    @keyup.enter="handleConfirm"
                />

                <!-- Additional Warning -->
                <div
                    class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4"
                >
                    <div class="flex items-start space-x-3">
                        <i
                            class="mdi mdi-information-outline text-amber-600 dark:text-amber-400 mt-0.5"
                        ></i>
                        <div class="text-sm text-amber-800 dark:text-amber-300">
                            <strong class="font-medium">{{
                                __("Warning:")
                            }}</strong>
                            {{
                                __(
                                    "All data associated with this group will be permanently deleted and cannot be recovered.",
                                )
                            }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50"
            >
                <v-button
                    :label="__('Cancel')"
                    :title="__('Cancel')"
                    :aria-label="__('Cancel')"
                    icon="mdi mdi-close-circle"
                    :disabled="form.processing"
                    @click="dialog = false"
                    variant="danger"
                />

                <v-button
                    @click="destroy"
                    :disabled="!canDelete"
                    :label="
                        form.processing ? __('Deleting...') : __('Delete Group')
                    "
                    :title="
                        form.processing ? __('Deleting...') : __('Delete Group')
                    "
                    icon="mdi mdi-trash-can"
                    variant="success"
                />
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import VModal from "@/components/VModal.vue";
import VButton from "@/components/VButton.vue";
import VInput from "@/components/VInput.vue";

// Props
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

// Emits
const emit = defineEmits(["deleted"]);

// Reactive state
const dialog = ref(false);
const confirmationText = ref("");

// Computed
const canDelete = computed(() => {
    return !form.processing && confirmationText.value === props.item.slug;
});

// Reset confirmation when dialog closes
watch(dialog, (newVal) => {
    if (!newVal) {
        confirmationText.value = "";
    }
});

// useForm
const form = useForm({});

// Methods
const handleConfirm = () => {
    if (canDelete.value) {
        destroy();
    }
};

const destroy = () => {
    if (!canDelete.value) {
        return;
    }

    form.delete(props.item.links.destroy, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            $notify.success(__("Group deleted successfully"));
            emit("deleted");
            dialog.value = false;
            confirmationText.value = "";
        },
        onError: (e) => {
            console.log(e);
        },
    });
};
</script>
