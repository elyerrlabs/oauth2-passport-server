<template>
    <div>
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700"
            :for="id"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>
        <input
            :id="id"
            :type="type"
            v-model="model"
            :placeholder="placeholder"
            class="mt-1 py-2 px-3 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
        />

        <v-error :error="error" />
    </div>
</template>

<script setup>
import { computed } from "vue";
import VError from "./VError.vue";

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    type: {
        type: String,
        default: "text",
    },
    placeholder: String,
    id: String,
    required: {
        type: Boolean,
        default: false,
    },
    error: Array,
});

const emit = defineEmits(["update:modelValue"]);

const model = computed({
    get: () => props.modelValue,
    set: (val) => emit("update:modelValue", val),
});
</script>
