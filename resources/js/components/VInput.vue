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
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div class="relative">
            <input
                v-bind="attrs"
                :type="inputType"
                v-model="localValue"
                :placeholder="placeholder ?? label"
                @blur="handleUp"
                @input="change"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                :class="[
                    inputClass,
                    {
                        'bg-gray-200 dark:bg-gray-600': disabled,
                        'border-red-600 dark:border-red-500': hasError,
                        'pr-10': canToggleVisibility,
                    },
                    inputType == 'date' ? 'date' : '',
                ]"
                :disabled="disabled"
            />

            <button
                v-if="canToggleVisibility"
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition-colors duration-200 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                :aria-label="revealed ? __('Hide value') : __('Show value')"
                :title="revealed ? __('Hide value') : __('Show value')"
                @click="toggleVisibility"
            >
                <i
                    :class="
                        revealed
                            ? 'mdi mdi-eye-off-outline'
                            : 'mdi mdi-eye-outline'
                    "
                    class="text-base"
                ></i>
            </button>
        </div>

        <div
            v-if="helper"
            class="mt-1 flex items-center rounded bg-blue-100 p-2 text-justify"
        >
            <i class="fas fa-info-circle mr-3 mt-1 text-blue-500"></i>
            <small class="text-sm text-blue-900">
                {{ helper }}
            </small>
        </div>

        <v-error :error="error" />
    </div>
</template>

<script setup>
defineOptions({
    inheritAttrs: false,
});

import { computed, onMounted, ref, useAttrs, watch } from "vue";
import VError from "@/components/VError.vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

const attrs = useAttrs();

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    type: { type: String, default: "text" },
    placeholder: { type: String, default: null },
    required: { type: Boolean, default: false },
    error: { type: [Array, Object, String], default: () => [] },
    digits: { type: Number, default: 2 },
    disabled: { type: Boolean, default: false },
    helper: { type: String, default: null },
    hide: { type: Boolean, default: false },
    inputClass: { type: [String, Array, Object], default: "" },
    dateFormat: { type: String, default: () => "Y-m-d" },
    dateLocale: { type: String, default: () => "en" },
    maxDate: { type: String, default: () => "today" },
});

const emit = defineEmits(["update:modelValue", "input"]);

const localValue = ref("");
const revealed = ref(false);

const hasError = computed(() => {
    if (typeof props.error === "string") {
        return !!props.error.length;
    }

    if (Array.isArray(props.error)) {
        return props.error.length > 0;
    }

    if (props.error && typeof props.error === "object") {
        return Object.keys(props.error).length > 0;
    }

    return false;
});

const canToggleVisibility = computed(() => props.hide);

const inputType = computed(() => {
    if (props.type === "money") {
        return "text";
    }

    if (canToggleVisibility.value) {
        return revealed.value ? "text" : "password";
    }

    return props.type;
});

watch(
    () => props.modelValue,
    (val) => {
        if (props.type === "money") {
            const num = Number(val);
            localValue.value = !Number.isNaN(num)
                ? (num / Math.pow(10, props.digits)).toFixed(props.digits)
                : "";
        } else {
            localValue.value = val ?? "";
        }
    },
    { immediate: true },
);

onMounted(() => {
    flatpickr(".date", {
        dateFormat: props.dateFormat,
        locale: props.dateLocale,
        maxDate: props.maxDate,
    });
});

const handleUp = () => {
    if (props.type === "money") {
        const num = Number(localValue.value);

        if (!Number.isNaN(num)) {
            emit(
                "update:modelValue",
                Math.round(num * Math.pow(10, props.digits)),
            );
            localValue.value = num.toFixed(props.digits);
        } else {
            emit("update:modelValue", null);
            localValue.value = "";
        }
    } else {
        emit("update:modelValue", localValue.value);
    }
};

const change = () => {
    if (props.type !== "money") {
        emit("update:modelValue", localValue.value);
        emit("input", localValue.value);
    }
};

const toggleVisibility = () => {
    revealed.value = !revealed.value;
};
</script>
