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
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <input
            :type="type === 'money' ? 'text' : type"
            v-model="localValue"
            :placeholder="placeholder ?? label"
            @blur="handleUp"
            @input="change"
            class="mt-1 py-2 px-3 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
            :class="{ 'bg-gray-200': disabled, 'border-red-600': error.length }"
            :disabled="disabled"
        />

        <v-error :error="error" />
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import VError from "./VError.vue";

const props = defineProps({
    modelValue: [String, Number],
    label: { type: String, required: true },
    type: { type: String, default: "text" },
    placeholder: { type: String, default: null },
    required: { type: Boolean, default: false },
    error: { type: Array, default: [] },
    digits: { type: Number, default: 2 },
    disabled: { type: Boolean, default: false },
});

const emit = defineEmits(["update:modelValue"]);

const localValue = ref("");

watch(
    () => props.modelValue,
    (val) => {
        if (props.type === "money") {
            const num = Number(val);
            localValue.value = !isNaN(num)
                ? (num / Math.pow(10, props.digits)).toFixed(props.digits)
                : "";
        } else {
            localValue.value = val ?? "";
        }
    },
    { immediate: true }
);

const handleUp = () => {
    if (props.type === "money") {
        const num = Number(localValue.value);
        if (!isNaN(num)) {
            // guarda en centavos
            emit(
                "update:modelValue",
                Math.round(num * Math.pow(10, props.digits))
            );
            // re-formatea la vista (ej. 300.9 → 300.90)
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
    if (props.type != "money") {
        emit("update:modelValue", localValue.value);
    }
};
</script>
