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
    <!-- Delete Button -->
    <button
        @click="dialog = true"
        class="relative group rounded-full p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-200 dark:focus:ring-red-800"
    >
        <i class="mdi mdi-delete-outline text-lg"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-red-600 dark:bg-red-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50"
        >
            {{ __("Delete role") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-red-600 dark:border-t-red-700"
            ></div>
        </div>
    </button>

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
                                          "Warning: This is a system role. Deleting it may affect application functionality."
                                      )
                                    : __(
                                          "Warning: This action cannot be undone. All associated permissions will be removed."
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
                    <input
                        v-model="confirmationText"
                        type="text"
                        class="w-full max-w-md mx-auto px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-red-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
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
                                        "System roles are protected and cannot be deleted."
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
                <button
                    @click="dialog = false"
                    :disabled="loading"
                    class="flex items-center gap-2 px-6 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-600 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-close-circle"></i>
                    {{ __("Cancel") }}
                </button>
                <button
                    v-if="!item.system"
                    @click="destroy"
                    :disabled="!canDelete || loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200',
                        !canDelete || loading
                            ? 'bg-red-400 dark:bg-red-600 cursor-not-allowed'
                            : 'bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600 focus:ring-red-200 dark:focus:ring-red-800',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i v-else class="mdi mdi-delete-forever"></i>
                    <span>{{
                        loading ? __("Deleting...") : __("Delete Role")
                    }}</span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const emits = defineEmits(["deleted"]);
const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const loading = ref(false);
const dialog = ref(false);
const confirmationText = ref("");
const form = useForm();

const canDelete = computed(() => {
    return confirmationText.value === props.item.slug;
});

const handleConfirm = () => {
    if (canDelete.value && !loading.value) {
        destroy();
    }
};

const destroy = () => {
    if (!canDelete.value || loading.value) {
        return;
    }

    loading.value = true;

    form.delete(props.item.links.destroy, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("Role deleted successfully"));
            emits("deleted");
            dialog.value = false;
            confirmationText.value = "";
        },
        onFinish: () => {
            loading.value = false;
        },
        onError: (e) => {
            if (e.message) {
                $notify.error(e.message);
            } else {
                $notify.error(__("An error occurred while deleting the role"));
            }
        },
    });
};
</script>
