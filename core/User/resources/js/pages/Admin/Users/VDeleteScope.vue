<template>
    <div>
        <v-button
            @click="dialog = true"
            size="xs"
            variant="danger"
            icon="mdi mdi-delete"
            :label="__('Delete')"
        />

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
                                    "You are about to revoke the following scope from this user:",
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

                        <!-- GSR ID with Copy/Paste -->
                        <div
                            class="mt-3 pt-3 border-t border-red-200 dark:border-red-800"
                        >
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                            >
                                {{ __("GSR Identifier") }}
                            </div>
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex-1 font-mono text-sm text-gray-800 dark:text-gray-200 break-all bg-white dark:bg-gray-800 px-3 py-2 rounded border"
                                >
                                    {{ item.scope?.gsr_id }}
                                </div>
                                <v-button
                                    @click="copyToClipboard(item.scope?.gsr_id)"
                                    variant="secondary"
                                    size="sm"
                                    icon="mdi mdi-content-copy"
                                    :title="__('Copy GSR ID')"
                                >
                                </v-button>
                                <v-button
                                    @click="pasteFromClipboard"
                                    variant="secondary"
                                    size="sm"
                                    icon="mdi mdi-content-paste"
                                    :title="__('Paste GSR ID')"
                                >
                                </v-button>
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
                                    "This action will immediately revoke this permission. The user will lose access to this functionality.",
                                )
                            }}
                        </p>
                    </div>

                    <!-- Verification -->
                    <div class="space-y-3">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ __("To confirm, type the GSR ID:") }}
                        </p>
                        <div class="relative">
                            <div
                                class="mb-2 p-3 bg-gray-100 dark:bg-gray-800 rounded border font-mono text-sm text-center text-gray-800 dark:text-gray-200"
                            >
                                {{ item.scope?.gsr_id }}
                            </div>
                            <div class="absolute right-2 top-2">
                                <v-button
                                    @click="pasteToInput"
                                    variant="ghost"
                                    size="xs"
                                    icon="mdi mdi-content-paste"
                                    :title="__('Paste to input')"
                                >
                                </v-button>
                            </div>
                        </div>
                        <div class="relative">
                            <input
                                v-model="confirmationText"
                                type="text"
                                class="w-full px-3 py-2 border rounded-lg dark:bg-gray-800 dark:border-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                :placeholder="
                                    __('Type or paste the GSR ID to confirm')
                                "
                                @keyup.enter="revokeScope"
                            />
                            <div
                                v-if="confirmationText"
                                class="absolute right-2 top-2"
                            >
                                <v-button
                                    v-if="!isConfirmed && confirmationText"
                                    @click="clearConfirmation"
                                    variant="ghost"
                                    size="xs"
                                    icon="mdi mdi-close"
                                    :title="__('Clear')"
                                >
                                </v-button>
                                <i
                                    v-else-if="isConfirmed"
                                    class="mdi mdi-check-circle text-green-500 text-lg"
                                ></i>
                            </div>
                        </div>
                        <p
                            v-if="!isConfirmed && confirmationText"
                            class="text-xs text-red-600 dark:text-red-400"
                        >
                            {{ __("The GSR ID does not match") }}
                        </p>
                        <p
                            v-else-if="isConfirmed && confirmationText"
                            class="text-xs text-green-600 dark:text-green-400"
                        >
                            <i class="mdi mdi-check-circle mr-1"></i>
                            {{ __("GSR ID verified successfully") }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex items-center justify-end gap-3 pt-4 border-t dark:border-gray-700"
                    >
                        <v-button @click="dialog = false" variant="light">
                            {{ __("Cancel") }}
                        </v-button>
                        <v-button
                            @click="revokeScope"
                            :disabled="!isConfirmed || processing"
                            :loading="processing"
                            variant="danger"
                            icon="mdi mdi-delete"
                        >
                            {{
                                processing
                                    ? __("Revoking...")
                                    : __("Revoke Permission")
                            }}
                        </v-button>
                    </div>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VButton from "@/components/VButton.vue";
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

const form = useForm({});
const processing = ref(false);
const dialog = ref(false);
const confirmationText = ref("");
const isConfirmed = computed(() => {
    return confirmationText.value === props.item.scope?.gsr_id;
});

const revokeScope = async () => {
    if (!isConfirmed.value) return;
    processing.value = true;

    form.delete(props.item.links.revoke, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (res) => {
            $notify.success(__("Permission revoked successfully"));
            emits("deleted");
            confirmationText.value = "";
        },
        onError: (e) => {
            console.log(e);
        },
        onFinish: () => {
            processing.value = false;
            dialog.value = false;
        },
    });
};

const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        $notify.success(__("GSR ID copied to clipboard"));
    } catch (err) {
        // Fallback method
        const textarea = document.createElement("textarea");
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand("copy");
        document.body.removeChild(textarea);
        $notify.success(__("GSR ID copied to clipboard"));
    }
};

const pasteFromClipboard = async () => {
    try {
        const text = await navigator.clipboard.readText();
        if (text) {
            confirmationText.value = text;
            $notify.success(__("Pasted from clipboard"));
        }
    } catch (err) {
        $notify.error(
            __("Unable to paste. Please check clipboard permissions."),
        );
    }
};

const pasteToInput = async () => {
    try {
        const text = await navigator.clipboard.readText();
        if (text) {
            confirmationText.value = text;
            $notify.success(__("Pasted to confirmation field"));
        }
    } catch (err) {
        $notify.error(
            __("Unable to paste. Please check clipboard permissions."),
        );
    }
};

const clearConfirmation = () => {
    confirmationText.value = "";
};
</script>
