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
        <!-- Delete Button -->
        <v-button
            @click="dialog = true"
            :title="__('Delete role')"
            icon="mdi mdi-delete-outline text-md"
            round
            variant="danger"
        />

        <!-- Delete Confirmation Modal -->
        <v-modal
            v-model="dialog"
            :title="__('Delete Role')"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <div class="p-6 space-y-4 text-center">
                    <!-- Confirmation Message -->
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __("Are you sure you want to delete the role") }}
                        <span class="font-bold text-blue-600 dark:text-blue-400"
                            >"{{ item.name }}"</span
                        >?
                    </p>

                    <!-- Role Info Chips -->
                    <div class="flex justify-center gap-2 flex-wrap">
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-sm transition-colors duration-200"
                        >
                            <i class="mdi mdi-identifier"></i>
                            {{ __("ID") }}: {{ item.id }}
                        </span>
                        <span
                            v-if="item.system"
                            class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300 rounded-full text-sm transition-colors duration-200"
                        >
                            <i class="mdi mdi-shield-check"></i>
                            {{ __("System Role") }}
                        </span>
                    </div>

                    <!-- Warning Message -->
                    <div
                        :class="[
                            'p-4 rounded-lg border transition-colors duration-200',
                            item.system
                                ? 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800 text-orange-800 dark:text-orange-300'
                                : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300',
                        ]"
                    >
                        <div class="flex items-start gap-2">
                            <i class="mdi mdi-alert mt-0.5 shrink-0"></i>
                            <span class="text-sm text-left">
                                {{
                                    item.system
                                        ? __(
                                              "Warning: This is a system role. Deleting it may affect application functionality.",
                                          )
                                        : __(
                                              "Warning: This action cannot be undone. All associated permissions will be removed.",
                                          )
                                }}
                            </span>
                        </div>
                    </div>

                    <!-- Confirmation Input (solo para roles no system) -->
                    <div v-if="!item.system" class="space-y-3 pt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("To confirm, type the role name below:") }}
                        </p>
                        <div class="flex justify-center">
                            <code
                                class="bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-lg font-mono text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 text-sm"
                            >
                                {{ item.slug }}
                            </code>
                        </div>

                        <v-input
                            v-model="confirmationText"
                            :placeholder="__('Enter role name')"
                            @keyup.enter="handleConfirm"
                        />
                    </div>

                    <!-- System Role Warning -->
                    <div v-if="item.system" class="pt-2">
                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4"
                        >
                            <div
                                class="flex items-center gap-2 justify-center text-purple-800 dark:text-purple-300"
                            >
                                <i class="mdi mdi-shield-lock"></i>
                                <span class="text-sm font-medium">
                                    {{
                                        __(
                                            "System roles are protected and cannot be deleted.",
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-center gap-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-2xl transition-colors duration-200"
                >
                    <v-button
                        @click="dialog = false"
                        :disabled="loading"
                        :label="__('Cancel')"
                        :title="__('Cancel')"
                        icon="mdi mdi-close-circle"
                        variant="primary"
                    />

                    <v-button
                        @click="destroy"
                        :label="loading ? __('Deleting...') : __('Delete Role')"
                        :title="loading ? __('Deleting...') : __('Delete Role')"
                        :icon="
                            loading
                                ? 'mdi mdi-loading animate-spin'
                                : 'mdi mdi-delete-forever'
                        "
                        :disabled="!canDelete || loading"
                        variant="success"
                    />
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VButton from "@/components/VButton.vue";

import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const emits = defineEmits(["deleted"]);
const props = defineProps({
    item: {
        type: Object,
        required: true,
        default: () => {},
    },
});

const form = useForm({});
const loading = ref(false);
const dialog = ref(false);
const confirmationText = ref("");

const canDelete = computed(() => {
    return confirmationText.value === props.item?.slug;
});

const handleConfirm = () => {
    if (canDelete.value && !loading.value) {
        destroy();
    }
};

const destroy = async () => {
    if (!canDelete.value || loading.value) {
        return;
    }

    loading.value = true;

    form.delete(props.item.links.destroy, {
        preserveScroll: true,
        preserveScroll: true,
        onSuccess: (res) => {
            $notify.success(__("Role deleted successfully"));
            emits("deleted");
            dialog.value = false;
            confirmationText.value = "";
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
