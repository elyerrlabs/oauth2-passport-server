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
        <!-- Label Section -->
        <div class="flex items-center justify-start mb-2">
            <label
                class="block text-sm font-medium text-gray-700 transition-colors duration-200"
            >
                {{ label }}
                <span class="text-red-500" v-if="required">*</span>
            </label>

            <!-- Value Indicator -->
            <span
                class="text-xs font-medium px-2 py-1 rounded-md transition-colors duration-200"
                :class="
                    localValue
                        ? 'bg-green-100 text-green-800'
                        : 'bg-gray-100 text-gray-800'
                "
            >
                {{ localValue ? __(activeText) : __(inactiveText) }}
            </span>
        </div>

        <!-- Optional placeholder -->
        <p v-if="placeholder" class="text-xs text-gray-500 mb-3">
            {{ __(placeholder) }}
        </p>

        <!-- Toggle Switch -->
        <div class="flex items-center">
            <label class="flex items-center cursor-pointer group">
                <!-- Toggle Track -->
                <div class="relative">
                    <input
                        type="checkbox"
                        class="sr-only"
                        v-model="localValue"
                    />
                    <!-- Track -->
                    <div
                        class="w-14 h-8 rounded-full transition-all duration-300 ease-in-out"
                        :class="
                            localValue
                                ? 'bg-green-500 group-hover:bg-green-600'
                                : 'bg-gray-300 group-hover:bg-gray-400'
                        "
                    ></div>
                    <!-- Thumb -->
                    <div
                        class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full shadow-lg transition-transform duration-300 ease-in-out flex items-center justify-center"
                        :class="localValue ? 'translate-x-6' : ''"
                    >
                        <!-- Inner icon indicator -->
                        <svg
                            v-if="localValue"
                            class="w-3 h-3 text-green-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <svg
                            v-else
                            class="w-3 h-3 text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Toggle Label -->
                <span
                    class="mx-4 text-sm font-medium text-gray-700 transition-colors duration-200"
                    :class="localValue ? 'text-green-700' : 'text-gray-700'"
                >
                    {{ localValue ? __(activeLabel) : __(inactiveLabel) }}
                </span>
            </label>
        </div>

        <!-- Validation Message -->
        <v-error :error="error" />
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import VError from "./VError.vue";
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
    error: { Array, default: [] },
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
label,
div,
span {
    transition: all 0.3s ease;
}

input:focus + div {
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
}
</style>
