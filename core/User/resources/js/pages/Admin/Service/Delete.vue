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
        <v-button
            @click="open"
            :title="__('Delete service')"
            icon="mdi mdi-delete-outline tex-md"
            round
            variant="danger"
        />

        <!-- Delete Confirmation Modal -->
        <v-modal
            v-model="dialog"
            :title="__('Delete Service')"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <div class="p-6 space-y-4 text-center">
                    <!-- Confirmation Message -->
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ __("Are you sure you want to delete the service") }}
                        <span class="font-bold text-blue-600 dark:text-blue-400"
                            >"{{ form.name }}"</span
                        >?
                    </p>

                    <!-- Service Info Chips -->
                    <div class="flex justify-center gap-2 flex-wrap">
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 rounded-full text-sm transition-colors duration-200"
                        >
                            <i class="mdi mdi-identifier"></i>
                            {{ __("ID") }}: {{ form.id }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 rounded-full text-sm transition-colors duration-200"
                        >
                            <i class="mdi mdi-account-group"></i>
                            {{ __("Group") }}:
                            {{ form.group?.name || __("N/A") }}
                        </span>
                    </div>

                    <div class="flex justify-center gap-2 flex-wrap">
                        <span
                            v-if="form.system"
                            class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300 rounded-full text-sm transition-colors duration-200"
                        >
                            <i class="mdi mdi-shield-check"></i>
                            {{ __("System Service") }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300 rounded-full text-sm transition-colors duration-200"
                        >
                            <i class="mdi mdi-eye"></i>
                            {{ form.visibility || __("N/A") }}
                        </span>
                    </div>

                    <!-- Warning Message -->
                    <div
                        :class="[
                            'p-4 rounded-lg border transition-colors duration-200',
                            form.system
                                ? 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800 text-orange-800 dark:text-orange-300'
                                : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300',
                        ]"
                    >
                        <div class="flex items-start gap-2">
                            <i class="mdi mdi-alert mt-0.5 shrink-0"></i>
                            <span class="text-sm text-left">
                                {{
                                    form.system
                                        ? __(
                                              "Warning: This is a system service. Deleting it may affect application functionality.",
                                          )
                                        : __(
                                              "Warning: This action cannot be undone. The service will be permanently removed.",
                                          )
                                }}
                            </span>
                        </div>
                    </div>

                    <!-- Confirmation Input (solo para servicios no system) -->
                    <div v-if="!form.system" class="space-y-3 pt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("To confirm, type the service name below:") }}
                        </p>
                        <div class="flex justify-center">
                            <code
                                class="bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-lg font-mono text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 text-sm"
                            >
                                {{ form.slug }}
                            </code>
                        </div>
                        <v-input
                            v-model="confirmationText"
                            @keyup.enter="handleConfirm"
                            :placeholder="__('Enter service name')"
                        />
                    </div>

                    <!-- System Service Warning -->
                    <div v-if="form.system" class="pt-2">
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
                                            "System services are protected and cannot be deleted.",
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
                        :title="__('Cancel')"
                        :label="__('Cancel')"
                        icon="mdi mdi-close-circle"
                    />

                    <v-button
                        @click="destroy"
                        :disabled="!canDelete || loading"
                        :icon="
                            loading
                                ? 'mdi mdi-loading animate-spin'
                                : 'mdi mdi-delete-forever'
                        "
                        :label="
                            loading ? __('Deleting...') : __('Delete Service')
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
import VButton from "@/components/VButton.vue";
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

const form = useForm({
    id: null,
    name: "",
    slug: "",
    group: null,
    visibility: "",
    system: false,
});

const canDelete = computed(() => {
    return confirmationText.value === form.slug;
});

const handleConfirm = async () => {
    if (canDelete.value && !loading.value) {
        await destroy();
    }
};

const open = () => {
    confirmationText.value = "";

    if (props.item?.id) {
    }
    form.id = props.item.id;
    form.name = props.item.name;
    form.slug = props.item.slug;
    form.group = props.item.group;
    form.visibility = props.item.visibility;
    form.system = props.item.system;

    dialog.value = true;
};

const destroy = async () => {
    try {
        const res = await $server.delete(props.item.links.destroy);
        if (res.status == 200) {
            $notify.success(__("Service deleted successfully"));
            emits("deleted");
            dialog.value = false;
            confirmationText.value = "";
        }
    } catch (error) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    } finally {
        loading.value = false;
    }
};
</script>
