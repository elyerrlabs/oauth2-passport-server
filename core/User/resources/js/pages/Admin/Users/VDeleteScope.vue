<template>
    <div>
        <button
            @click="dialog = true"
            class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors duration-200"
        >
            <i class="mdi mdi-delete mr-1.5"></i>
            {{ __("Delete") }}
        </button>

        <v-modal
            v-model="dialog"
            :title="__('Delete Scope Permission')"
            panel-class="w-full lg:w-5xl"
        >
            <template #body>
                <div class="space-y-6">
                    <!-- Danger Icon -->
                    <div class="flex justify-center">
                        <div
                            class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-alert-circle text-red-600 dark:text-red-400 text-3xl"
                            ></i>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="text-center space-y-3">
                        <h3
                            class="text-lg font-semibold text-gray-900 dark:text-white"
                        >
                            {{ __("Revoke this permission?") }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            {{
                                __(
                                    "You are about to revoke the following scope from this user:"
                                )
                            }}
                        </p>
                    </div>

                    <!-- Scope Details Card -->
                    <div
                        class="border border-red-200 dark:border-red-800 rounded-lg p-4 bg-red-50 dark:bg-red-900/20"
                    >
                        <!-- Service and Role Info -->
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <i
                                        class="mdi mdi-shield-key text-red-600 dark:text-red-400"
                                    ></i>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ item.scope?.role?.name }}
                                    </span>
                                    <span
                                        class="text-xs px-2 py-1 rounded-full bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-300"
                                    >
                                        {{ item.scope?.role?.slug }}
                                    </span>
                                </div>
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ item.scope?.role?.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Service Info -->
                        <div
                            class="border-t pt-3 border-red-200 dark:border-red-800"
                        >
                            <div class="flex items-center gap-2 mb-2">
                                <i
                                    class="mdi mdi-cog text-blue-600 dark:text-blue-400"
                                ></i>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white"
                                >
                                    {{ item.scope?.service?.name }}
                                </span>
                                <span
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    ({{ item.scope?.service?.group?.name }})
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ item.scope?.service?.description }}
                            </p>
                        </div>

                        <!-- GSR ID -->
                        <div
                            class="mt-3 pt-3 border-t border-red-200 dark:border-red-800"
                        >
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                            >
                                {{ __("GSR Identifier") }}
                            </div>
                            <div
                                class="font-mono text-sm text-gray-800 dark:text-gray-200 break-all bg-white dark:bg-gray-800 px-3 py-2 rounded border"
                            >
                                {{ item.scope?.gsr_id }}
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Message -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                        <p
                            class="text-sm text-gray-600 dark:text-gray-300 text-center"
                        >
                            {{
                                __(
                                    "This action will immediately revoke this permission. The user will lose access to this functionality."
                                )
                            }}
                        </p>
                    </div>

                    <!-- Verification -->
                    <div class="space-y-3">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ __("To confirm, type the GSR ID:") }}
                        </p>
                        <div
                            class="mb-2 p-3 bg-gray-100 dark:bg-gray-800 rounded border"
                        >
                            <div
                                class="font-mono text-sm text-center text-gray-800 dark:text-gray-200"
                            >
                                {{ item.scope?.gsr_id }}
                            </div>
                        </div>
                        <input
                            v-model="confirmationText"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            :placeholder="__('Type the GSR ID to confirm')"
                        />
                        <p
                            v-if="!isConfirmed && confirmationText"
                            class="text-xs text-red-600 dark:text-red-400"
                        >
                            {{ __("The GSR ID does not match") }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex items-center justify-end gap-3 pt-4 border-t dark:border-gray-700"
                    >
                        <button
                            @click="dialog = false"
                            class="px-4 py-2 cursor-pointer rounded-lg border hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 transition-colors"
                        >
                            {{ __("Cancel") }}
                        </button>
                        <button
                            @click="revokeScope"
                            :disabled="!isConfirmed || form.processing"
                            class="px-4 py-2 cursor-pointer rounded-lg bg-red-600 hover:bg-red-700 text-white flex items-center gap-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <i class="mdi mdi-delete"></i>
                            {{
                                form.processing
                                    ? __("Revoking...")
                                    : __("Revoke Permission")
                            }}
                        </button>
                    </div>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

const emits = defineEmits(["deleted"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const dialog = ref(false);
const confirmationText = ref("");
const form = useForm({});

const isConfirmed = computed(() => {
    return confirmationText.value === props.item.scope?.gsr_id;
});

const revokeScope = () => {
    if (!isConfirmed.value) return;

    form.put(props.item.links.revoke, {
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(__("Permission revoked successfully"));
            emits("deleted");
            dialog.value = false;
            confirmationText.value = "";
            form.reset();
        },
        onError: () => {
            $notify.error(__("Failed to revoke permission"));
        },
    });
};
</script>
