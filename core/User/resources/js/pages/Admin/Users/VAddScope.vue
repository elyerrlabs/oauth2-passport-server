<template>
    <div>
        <button
            @click="open"
            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition-colors flex items-center gap-2"
        >
            <i class="mdi mdi-plus"></i>
            {{ __("Add") }}
        </button>

        <v-modal
            v-model="dialog"
            :title="__('Add Scope Permission')"
            panel-class="w-full lg:w-5xl"
        >
            <template #body>
                <div class="space-y-6">
                    <!-- Warning Message -->
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4"
                    >
                        <div class="flex items-start gap-3">
                            <i
                                class="mdi mdi-information text-yellow-600 dark:text-yellow-400 text-xl mt-0.5"
                            ></i>
                            <div>
                                <h4
                                    class="font-medium text-yellow-800 dark:text-yellow-300"
                                >
                                    {{ __("Scope Assignment") }}
                                </h4>
                                <p
                                    class="text-sm text-yellow-700 dark:text-yellow-400 mt-1"
                                >
                                    {{
                                        __(
                                            "The following scope will be assigned to the user"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Information -->
                    <div
                        class="border rounded-lg p-5 bg-white dark:bg-gray-900"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-shield-key text-blue-600 dark:text-blue-400"
                                    ></i>
                                </div>
                                <div>
                                    <h3
                                        class="font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ item.name }}
                                    </h3>
                                    <p
                                        class="text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        {{ item.slug }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                >
                                    Scope ID
                                </div>
                                <div
                                    class="font-mono text-xs text-gray-700 dark:text-gray-300 truncate max-w-[150px]"
                                >
                                    {{ item.scope?.id }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="text-sm text-gray-600 dark:text-gray-300 italic border-t pt-3"
                        >
                            {{ item.description }}
                        </div>
                    </div>

                    <!-- Scope Details -->
                    <div
                        class="border rounded-lg p-4 space-y-4 bg-gray-50 dark:bg-gray-800/50"
                    >
                        <h4
                            class="text-sm font-semibold text-gray-800 dark:text-gray-200"
                        >
                            {{ __("Scope Configuration") }}
                        </h4>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    GSR ID
                                </div>
                                <div
                                    class="font-mono text-sm text-gray-900 dark:text-white"
                                >
                                    {{ item.scope?.gsr_id }}
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    Status
                                </div>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-2 h-2 rounded-full"
                                        :class="
                                            item.scope?.active
                                                ? 'bg-green-500'
                                                : 'bg-red-500'
                                        "
                                    ></div>
                                    <span
                                        class="text-sm"
                                        :class="
                                            item.scope?.active
                                                ? 'text-green-600 dark:text-green-400'
                                                : 'text-red-600 dark:text-red-400'
                                        "
                                    >
                                        {{
                                            item.scope?.active
                                                ? __("Active")
                                                : __("Inactive")
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Access Badges -->
                        <div class="flex flex-wrap gap-2 pt-2">
                            <span
                                v-if="item.scope?.web"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400"
                            >
                                <i class="mdi mdi-web"></i>
                                Web
                            </span>

                            <span
                                v-if="item.scope?.api_key"
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400"
                            >
                                <i class="mdi mdi-key"></i>
                                API Key
                            </span>

                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs"
                                :class="
                                    item.scope?.public
                                        ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                                        : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                                "
                            >
                                <i
                                    class="mdi"
                                    :class="
                                        item.scope?.public
                                            ? 'mdi-eye'
                                            : 'mdi-eye-off'
                                    "
                                ></i>
                                {{
                                    item.scope?.public
                                        ? __("Public")
                                        : __("Private")
                                }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex items-center justify-between pt-4 border-t dark:border-gray-700"
                    >
                        <button
                            @click="dialog = false"
                            class="px-4 py-2 rounded-lg border hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 transition-colors"
                        >
                            {{ __("Cancel") }}
                        </button>

                        <button
                            @click="add"
                            class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 transition-colors"
                        >
                            <i class="mdi mdi-shield-plus"></i>
                            {{ __("Assign Scope") }}
                        </button>
                    </div>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script setup>
import VModal from "@/components/VModal.vue";
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

const emits = defineEmits(["created"]);

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const dialog = ref(false);

const open = () => {
    dialog.value = true;
    form.scopes.push(props.item.scope.id);
};

const form = useForm({
    scopes: [],
});

const add = () => {
    form.post(props.item.scope.links.scopes, {
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(__("Scope assigned successfully"));
            emits("created");
            dialog.value = false;
        },
        onFinish: () => {
            form.reset();
        },
    });
};
</script>
