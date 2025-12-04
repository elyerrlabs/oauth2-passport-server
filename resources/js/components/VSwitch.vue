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
    <div class="w-full">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center space-x-3">
                <!-- Label -->
                <label
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-200"
                >
                    {{ label }}
                    <span class="text-red-500" v-if="required">*</span>
                </label>

                <!-- Value Indicator -->
                <span
                    class="text-xs font-medium px-2.5 rounded-full border transition-all duration-200 shadow-sm"
                    :class="
                        localValue
                            ? 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 border-green-200 dark:border-green-800'
                            : 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-700'
                    "
                >
                    <i
                        class="mdi mr-1 text-xs"
                        :class="
                            localValue ? 'mdi-check-circle' : 'mdi-close-circle'
                        "
                    ></i>
                    {{ localValue ? __(activeText) : __(inactiveText) }}
                </span>
            </div>

            <!-- Optional Help Icon -->
            <div v-if="placeholder" class="relative group">
                <i
                    class="mdi mdi-information-outline text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 cursor-help text-lg"
                ></i>
                <div
                    class="absolute bottom-full right-0 mb-2 w-64 px-3 py-2 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10 shadow-xl"
                >
                    {{ __(placeholder) }}
                    <div
                        class="absolute top-full right-2 -mt-1 w-2 h-2 bg-gray-900 dark:bg-gray-700 transform rotate-45"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Toggle Switch Container -->
        <div
            class="flex items-center justify-between px-2 py-1.5 bg-white dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800/70 group"
        >
            <!-- Toggle Switch -->
            <div class="flex items-center space-x-2">
                <label class="flex items-center cursor-pointer group/switch">
                    <!-- Toggle Track -->
                    <div class="relative">
                        <input
                            type="checkbox"
                            class="sr-only"
                            v-model="localValue"
                            :disabled="disabled"
                        />
                        <!-- Track -->
                        <div
                            class="w-16 h-7 rounded-full transition-all duration-300 ease-in-out shadow-inner"
                            :class="[
                                localValue
                                    ? 'bg-green-500 dark:bg-green-600 group-hover/switch:bg-green-600 dark:group-hover/switch:bg-green-500'
                                    : 'bg-gray-300 dark:bg-gray-600 group-hover/switch:bg-gray-400 dark:group-hover/switch:bg-gray-500',
                                disabled ? 'opacity-50 cursor-not-allowed' : '',
                            ]"
                        ></div>
                        <!-- Thumb -->
                        <div
                            class="absolute left-1 top-1 bg-white dark:bg-gray-100 w-5 h-5 rounded-full shadow-lg transition-all duration-300 ease-in-out flex items-center justify-center group-hover/switch:shadow-xl"
                            :class="[
                                localValue ? 'translate-x-8' : '',
                                disabled ? 'opacity-70' : '',
                            ]"
                        >
                            <!-- Inner icon indicator -->
                            <i
                                v-if="localValue"
                                class="mdi mdi-check text-green-500 dark:text-green-600 text-sm"
                            ></i>
                            <i
                                v-else
                                class="mdi mdi-close text-gray-400 dark:text-gray-500 text-sm"
                            ></i>
                        </div>

                        <!-- Active/Inactive Background Glow -->
                        <div
                            class="absolute inset-0 rounded-full opacity-0 transition-opacity duration-300"
                            :class="[
                                localValue
                                    ? 'bg-green-200 dark:bg-green-900/30 group-hover/switch:opacity-40'
                                    : 'bg-gray-200 dark:bg-gray-700 group-hover/switch:opacity-40',
                            ]"
                        ></div>
                    </div>

                    <!-- Toggle Label -->
                    <span
                        class="ml-4 text-sm font-semibold transition-colors duration-200"
                        :class="[
                            localValue
                                ? 'text-green-700 dark:text-green-400'
                                : 'text-gray-700 dark:text-gray-400',
                            disabled ? 'opacity-50' : '',
                        ]"
                    >
                        {{ localValue ? __(activeLabel) : __(inactiveLabel) }}
                    </span>
                </label>
            </div>

            <!-- Status Icon 
            <div class="shrink-0">
                <div
                    class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-300"
                    :class="[
                        localValue
                            ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400',
                        disabled ? 'opacity-50' : '',
                    ]"
                >
                    <i
                        v-if="localValue"
                        class="mdi mdi-toggle-switch text-xl"
                    ></i>
                    <i v-else class="mdi mdi-toggle-switch-off text-xl"></i>
                </div>
            </div>-->
        </div>

        <!-- Description (alternative to tooltip) -->
        <p
            v-if="placeholder && !$slots.description"
            class="text-xs text-gray-500 dark:text-gray-400 mt-2 px-1"
        >
            {{ __(placeholder) }}
        </p>

        <!-- Custom Description Slot -->
        <div v-if="$slots.description" class="mt-2">
            <slot name="description" :value="localValue" />
        </div>

        <!-- Validation Message -->
        <v-error :error="error" class="mt-2" />

        <!-- Usage Statistics (Optional) -->
        <div
            v-if="showStats && localValue"
            class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800"
        >
            <div
                class="flex items-center space-x-2 text-sm text-blue-700 dark:text-blue-300"
            >
                <i class="mdi mdi-chart-line"></i>
                <span>{{
                    __("This feature is currently active and being used")
                }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import VError from "@/components/VError.vue";

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        default: "",
    },
    activeLabel: {
        type: String,
        default: "Enabled",
    },
    inactiveLabel: {
        type: String,
        default: "Disabled",
    },
    activeText: {
        type: String,
        default: "Active",
    },
    inactiveText: {
        type: String,
        default: "Inactive",
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    error: { type: [Array, String], default: [] },
    showStats: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue", "change"]);

const localValue = ref(props.modelValue);

watch(
    () => props.modelValue,
    (newVal) => {
        localValue.value = newVal;
    }
);

watch(localValue, (newVal) => {
    emit("update:modelValue", newVal);
    emit("change", newVal);
});
</script>

<style scoped>
.group\/switch:focus-within .relative > div:first-child {
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
}

.dark .group\/switch:focus-within .relative > div:first-child {
    box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.3);
}

/* Smooth transitions for all interactive elements */
label,
div,
span,
button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Disabled state styling */
input:disabled + div {
    cursor: not-allowed;
}

input:disabled ~ span {
    cursor: not-allowed;
}
</style>
