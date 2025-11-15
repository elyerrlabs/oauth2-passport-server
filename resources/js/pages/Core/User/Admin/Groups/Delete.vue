<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->
<!--
Copyright (c) 2025 Elvis Yerel Roman Concha

This file is part of an open source project licensed under the
"NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).

You may use, study, modify, and redistribute this file for personal,
educational, or non-commercial research purposes only.

Commercial use is strictly prohibited without prior written consent
from the author.

Combining this software with any project licensed for commercial use
(such as AGPL) is not permitted without explicit authorization.

This software supports OAuth 2.0 and OpenID Connect.

Author Contact: yerel9212@yahoo.es

SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
-->

<template>
    <!-- Delete Button -->
    <button
        @click="dialog = true"
        class="w-8 h-8 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:ring-opacity-50"
    >
        <i class="mdi mdi-delete text-lg"></i>
    </button>

    <!-- Confirmation Modal -->
    <v-modal
        v-model="dialog"
        :title="__('Confirm Deletion')"
        panel-class="w-full lg:w-3xl"
    >
        <template #body>
            <div class="p-6 space-y-4">
                <p class="text-gray-700 dark:text-gray-300">
                    {{
                        __(
                            "Are you sure you want to delete this group? This action cannot be undone."
                        )
                    }}
                </p>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50"
            >
                <button
                    @click="dialog = false"
                    :disabled="form.processing"
                    class="px-6 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 font-medium rounded-lg flex items-center space-x-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-close-circle"></i>
                    <span>{{ __("Cancel") }}</span>
                </button>

                <button
                    @click="destroy"
                    :disabled="form.processing"
                    class="px-6 py-2 bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600 text-white font-medium rounded-lg flex items-center space-x-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-trash-can" v-if="!form.processing"></i>
                    <div
                        v-else
                        class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
                    ></div>
                    <span>
                        {{
                            form.processing
                                ? __("Deleting...")
                                : __("Delete Group")
                        }}
                    </span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import VModal from "@/components/VModal.vue";

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

// useForm
const form = useForm({});

// Methods
const destroy = () => {
    form.delete(props.item.links.destroy, {
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(__("Group deleted successfully"));
            emit("deleted");
            dialog.value = false;
        },
        onError: (e) => {
            if (e.message) {
                $notify.error(e.message);
            } else {
                $notify.error(__("An error occurred while deleting the group"));
            }
        },
    });
};
</script>
