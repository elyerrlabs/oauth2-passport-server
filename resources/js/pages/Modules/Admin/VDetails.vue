<template>
    <div>
        <v-button
            variant="success"
            round
            icon="mdi mdi-eye-settings-outline"
            @click="dialog = true"
            :title="__('Show details')"
        />
        <v-modal
            v-model="dialog"
            :title="__('Module Details')"
            panel-class="w-full lg:w-5xl"
        >
            <template #body>
                <div class="space-y-6">
                    <!-- Header -->
                    <div
                        class="flex items-center gap-4 pb-4 border-b border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="w-14 h-14 rounded-xl flex items-center justify-center text-white font-bold text-xl"
                            :style="{ backgroundColor: getColor(module.name) }"
                        >
                            {{ module.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <h3
                                class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                            >
                                {{ module.name }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="
                                        module.enabled
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    "
                                >
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="
                                            module.enabled
                                                ? 'bg-green-500'
                                                : 'bg-red-500'
                                        "
                                    ></span>
                                    {{
                                        module.enabled
                                            ? __("Active")
                                            : __("Inactive")
                                    }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300':
                                            module.provider === 'git',
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200':
                                            module.provider === 'local',
                                    }"
                                >
                                    {{ module.provider }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("Source") }}
                            </label>
                            <p
                                class="text-sm text-gray-900 dark:text-gray-100 font-mono break-all"
                            >
                                {{ module.source }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("Path") }}
                            </label>
                            <p
                                class="text-sm text-gray-900 dark:text-gray-100 font-mono break-all"
                            >
                                {{ module.path }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("Current Version") }}
                            </label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"
                                >
                                    {{ module.current_version || "—" }}
                                </span>
                            </p>
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("Last Version") }}
                            </label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300"
                                >
                                    {{ module.last_version || "—" }}
                                </span>
                            </p>
                        </div>

                        <div class="space-y-1" v-if="module.new_version">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("New Version Available") }}
                            </label>
                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                                >
                                    ↑ {{ module.new_version }}
                                </span>
                            </p>
                        </div>

                        <div class="space-y-1" v-if="module.username">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("Username") }}
                            </label>
                            <p
                                class="text-sm text-gray-900 dark:text-gray-100 font-mono"
                            >
                                {{ module.username }}
                            </p>
                        </div>

                        <div class="space-y-1" v-if="module.token">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("Token") }}
                            </label>
                            <p
                                class="text-sm text-gray-900 dark:text-gray-100 font-mono"
                            >
                                ••••••••••••••••
                            </p>
                        </div>

                        <div class="space-y-1">
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                            >
                                {{ __("ID") }}
                            </label>
                            <p
                                class="text-sm text-gray-900 dark:text-gray-100 font-mono"
                            >
                                {{ module.id }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between gap-3 mt-8">
                    <v-button v-if="!module.enabled" variant="warning" @click="enabled">
                        {{ __("Enabled") }}
                    </v-button>

                    <v-button v-else variant="danger" @click="disabled">
                        {{ __("Disabled") }}
                    </v-button>
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

const emits = defineEmits(["enabled"]);

const props = defineProps({
    module: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({});

const dialog = ref(false);

const enabled = () => {
    form.put(props.module.links.enabled, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            dialog.value = false;
            emits("enabled");
        },
    });
};

const disabled = () => {
    form.put(props.module.links.disabled, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            dialog.value = false;
            emits("enabled");
        },
    });
};

function getColor(name) {
    const colors = [
        "#6366f1",
        "#8b5cf6",
        "#3b82f6",
        "#06b6d4",
        "#10b981",
        "#f59e0b",
        "#ef4444",
        "#ec4899",
    ];
    const index = name
        .split("")
        .reduce((acc, char) => acc + char.charCodeAt(0), 0);
    return colors[index % colors.length];
}
</script>
