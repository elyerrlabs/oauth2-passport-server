<template>
    <div>
        <v-button
            left-icon="mdi mdi-trash-can-outline"
            :label="round ? '' : __('Delete session')"
            :title="__('Delete session')"
            @click="dialog = true"
            variant="warning"
            size="xs"
            :round="round"
        />

        <v-modal v-model="dialog" :title="__('Delete Session')">
            <template #body>
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{
                            __("Are you sure you want to delete this session?")
                        }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">
                        <i class="mdi mdi-information-outline"></i>
                        {{
                            __(
                                "This action cannot be undone. You will be logged out from this device.",
                            )
                        }}
                    </p>
                    <div
                        class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
                    >
                        <div class="flex items-center gap-2 text-xs">
                            <i class="mdi mdi-ip-network text-gray-400"></i>
                            <span class="text-gray-600 dark:text-gray-400"
                                >{{ __("IP") }}:</span
                            >
                            <span
                                class="font-mono text-gray-700 dark:text-gray-300"
                                >{{ item.ip }}</span
                            >
                        </div>
                        <div class="flex items-center gap-2 text-xs mt-1">
                            <i class="mdi mdi-clock-outline text-gray-400"></i>
                            <span class="text-gray-600 dark:text-gray-400"
                                >{{ __("Last activity") }}:</span
                            >
                            <span class="text-gray-700 dark:text-gray-300">{{
                                item.last_activity
                            }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs mt-1">
                            <i class="mdi mdi-browser text-gray-400"></i>
                            <span class="text-gray-600 dark:text-gray-400"
                                >{{ __("Browser") }}:</span
                            >
                            <span class="text-gray-700 dark:text-gray-300"
                                >{{ item.agent?.substring(0, 60) }}...</span
                            >
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <v-button
                            @click="dialog = false"
                            :label="__('Cancel')"
                            variant="secondary"
                        />
                        <v-button
                            @click="deleteSession"
                            :label="__('Delete')"
                            variant="danger"
                            :loading="form.processing"
                        />
                    </div>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import { ref } from "vue";
import VModal from "@/components/VModal.vue";
import VButton from "@/components/VButton.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({});
const props = defineProps({
    item: {
        type: Object,
        required: true,
        default: () => ({
            id: "",
            ip: "",
            agent: "",
            last_activity: "",
            links: {
                destroy: "",
            },
        }),
    },
    round: {
        type: Boolean,
        default: () => false,
    },
});
const emits = defineEmits(["deleted"]);
const dialog = ref(false);

const deleteSession = () => {
    if (!props.item.links?.destroy) {
        console.error("Destroy link not provided");
        return;
    }

    form.delete(props.item.links.destroy, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
            emits("deleted", props.item);
            dialog.value = false;
        },
        onError: (e) => {
            console.log(e);
        },
    });
};
</script>
