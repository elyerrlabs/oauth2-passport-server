<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <textarea
            v-model="localValue"
            :placeholder="placeholder"
            class="mt-1 py-2 px-4 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
            :class="{ 'bg-gray-200': disabled }"
            :disabled="disabled"
            :rows="rows"
        ></textarea>

        <v-error :error="error" />
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import VError from "./VError.vue";

const props = defineProps({
    modelValue: [String, Number],
    label: { type: String, required: true },
    placeholder: { type: String, default: "" },
    required: { type: Boolean, default: false },
    error: { type: Array, default: [] },
    digits: { type: Number, default: 2 },
    disabled: { type: Boolean, default: false },
    rows: { type: Number, default: 2 },
});

const emit = defineEmits(["update:modelValue"]);

const localValue = ref(props.modelValue ?? "");

// Watch for changes from parent → child
watch(
    () => props.modelValue,
    (newVal) => {
        localValue.value = newVal;
    }
);

// Watch for changes from child → parent
watch(localValue, (val) => {
    emit("update:modelValue", val);
});
</script>
