<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" :class="['relative', zIndex]">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            :class="[
                                'transform  rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all',
                                panelClass,
                            ]"
                        >
                            <DialogTitle as="div" class="flex justify-between" v-if="title">
                                <h1 class="font-semibold text-3xl mx-2">
                                    {{ title }}
                                </h1>

                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    @click="closeModal"
                                >
                                    X
                                </button>
                            </DialogTitle>
                            <div class="p-6 text-gray-700 flex-grow">
                                <slot name="body" />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, watch } from "vue";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";

const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    modelValue: {
        type: Boolean,
        default: false,
    },
    panelClass: {
        type: String,
        default: "w-full max-w-md",
    },
    zIndex: {
        type: String,
        default: "z-80",
    },
});

const emits = defineEmits(["update:modelValue"]);

const isOpen = ref(false);

watch(
    () => props.modelValue,
    (value) => {
        isOpen.value = value;
        emits("update:modelValue", value);
    }
);

function closeModal() {
    isOpen.value = false;
    emits("update:modelValue", false);
}
</script>
