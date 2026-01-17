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
    <div class="w-full">
        <!-- Label Section -->
        <div class="flex items-center justify-between mb-2">
            <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-200"
            >
                {{ label }}
                <span v-if="required" class="text-red-500">*</span>
            </label>

            <!-- Character Counter -->
            <div
                v-if="maxLength"
                class="text-xs text-gray-500 dark:text-gray-400"
            >
                <span
                    :class="{
                        'text-green-600 dark:text-green-400':
                            localValue.length <= maxLength,
                        'text-red-600 dark:text-red-400':
                            localValue.length > maxLength,
                    }"
                >
                    {{ localValue.length }} / {{ maxLength }}
                </span>
            </div>
        </div>

        <!-- Textarea Container -->
        <div class="relative">
            <textarea
                v-model="localValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :rows="rows"
                :maxlength="maxLength"
                class="w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-opacity-50 transition-all duration-200 resize-y min-h-[80px]"
                :class="{
                    'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed':
                        disabled,
                    'bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400':
                        !disabled,
                    'border-red-500 dark:border-red-400 ring-2 ring-red-500 dark:ring-red-400 ring-opacity-20':
                        error.length,
                    'pr-10': showClearButton,
                }"
                @input="handleInput"
                @focus="isFocused = true"
                @blur="isFocused = false"
            ></textarea>

            <!-- Clear Button -->
            <button
                v-if="showClearButton && !disabled"
                @click="clearText"
                class="absolute right-3 top-3 p-1 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors duration-200"
            >
                <i class="mdi mdi-close-circle text-lg"></i>
            </button>

            <!-- Loading Indicator -->
            <div v-if="loading" class="absolute right-3 top-3">
                <div
                    class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"
                ></div>
            </div>
        </div>

        <!-- Helper Text -->
        <div
            v-if="helperText"
            class="mt-1 text-xs text-gray-500 dark:text-gray-400"
        >
            {{ helperText }}
        </div>

        <!-- Error Messages -->
        <v-error :error="error" class="mt-2" />

        <!-- Max Length Warning -->
        <div
            v-if="maxLength && localValue.length > maxLength"
            class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
        >
            <div
                class="flex items-center space-x-2 text-red-800 dark:text-red-300"
            >
                <i class="mdi mdi-alert-circle-outline text-sm"></i>
                <span class="text-xs font-medium">
                    {{ __("Character limit exceeded by") }}
                    {{ localValue.length - maxLength }}
                </span>
            </div>
        </div>

        <!-- Auto-resize Indicator -->
        <div v-if="autoResize" class="flex items-center justify-end mt-1">
            <div
                class="flex items-center space-x-1 text-xs text-gray-400 dark:text-gray-500"
            >
                <i class="mdi mdi-arrow-expand-vertical text-xs"></i>
                <span>{{ __("Auto-resize enabled") }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, nextTick } from "vue";
import VError from "@/components/VError.vue";

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },
    label: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
    error: { type: [Array, String], default: [] },
    disabled: {
        type: Boolean,
        default: false,
    },
    rows: {
        type: Number,
        default: 4,
    },
    maxLength: {
        type: Number,
        default: null,
    },
    helperText: {
        type: String,
        default: "",
    },
    loading: {
        type: Boolean,
        default: false,
    },
    autoResize: {
        type: Boolean,
        default: false,
    },
    clearable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["update:modelValue", "input", "focus", "blur"]);

const localValue = ref(props.modelValue ?? "");
const isFocused = ref(false);
const textareaRef = ref(null);

// Computed properties
const showClearButton = computed(() => {
    return props.clearable && localValue.value.length > 0 && !props.disabled;
});

const characterCountColor = computed(() => {
    if (!props.maxLength) return "text-gray-500";

    const ratio = localValue.value.length / props.maxLength;
    if (ratio > 1) return "text-red-600 dark:text-red-400";
    if (ratio > 0.9) return "text-yellow-600 dark:text-yellow-400";
    return "text-green-600 dark:text-green-400";
});

// Watch for changes from parent → child
watch(
    () => props.modelValue,
    (newVal) => {
        localValue.value = newVal ?? "";
        if (props.autoResize) {
            nextTick(autoResizeTextarea);
        }
    }
);

// Watch for changes from child → parent
watch(localValue, (val) => {
    emit("update:modelValue", val);
    if (props.autoResize) {
        nextTick(autoResizeTextarea);
    }
});

// Methods
const handleInput = (event) => {
    emit("input", event.target.value);
};

const clearText = () => {
    localValue.value = "";
    emit("update:modelValue", "");
    emit("input", "");

    // Focus the textarea after clearing
    if (textareaRef.value) {
        textareaRef.value.focus();
    }
};

const autoResizeTextarea = () => {
    const textarea = textareaRef.value;
    if (!textarea || !props.autoResize) return;

    // Reset height to auto to get the correct scrollHeight
    textarea.style.height = "auto";

    // Set new height based on scrollHeight
    const newHeight = Math.max(
        textarea.scrollHeight,
        parseInt(getComputedStyle(textarea).minHeight) || 80
    );
    textarea.style.height = `${newHeight}px`;
};

// Expose methods for parent component
defineExpose({
    focus: () => textareaRef.value?.focus(),
    clear: clearText,
    getValue: () => localValue.value,
});
</script>

<style scoped>
/* Custom scrollbar for textarea */
textarea::-webkit-scrollbar {
    width: 6px;
}

textarea::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

textarea::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

textarea::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Dark mode scrollbar */
.dark textarea::-webkit-scrollbar-track {
    background: #374151;
}

.dark textarea::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.dark textarea::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Smooth transitions */
textarea {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Focus styles */
textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.dark textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}
</style>
