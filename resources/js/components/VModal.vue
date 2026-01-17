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
                <div class="fixed inset-0 bg-black/25 dark:bg-black/40" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                    class="flex items-center justify-center p-1 lg:p-4 text-center"
                >
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
                                'transform rounded-2xl bg-white dark:bg-gray-800 p-2 lg:p-6 text-left align-middle shadow-xl transition-all',
                                panelClass,
                            ]"
                        >
                            <DialogTitle
                                as="div"
                                class="flex justify-between"
                                v-if="title"
                            >
                                <h1
                                    class="font-semibold text-md md:text-lg lg:text-3xl lg:mx-2 text-gray-900 dark:text-white"
                                >
                                    {{ title }}
                                </h1>

                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 dark:bg-blue-900/30 px-4 py-2 text-sm font-medium text-blue-900 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-blue-400"
                                    @click="closeModal"
                                >
                                    X
                                </button>
                            </DialogTitle>
                            <div
                                class="md:p-4 text-gray-700 dark:text-gray-300 grow"
                            >
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
