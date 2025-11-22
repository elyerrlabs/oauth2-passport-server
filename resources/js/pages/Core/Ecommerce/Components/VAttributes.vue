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
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8"
    >
        <div
            class="mb-2 p-4 bg-linear-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-blue-100 dark:border-gray-700 shadow-sm"
        >
            <div class="flex items-start gap-2 mb-4">
                <div
                    class="shrink-0 w-8 h-8 bg-linear-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg"
                >
                    <i class="fas fa-pencil-alt text-white text-lg"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ __("Product Attributes") }}
                    </h2>
                    <p
                        class="text-md text-gray-700 dark:text-gray-300 font-medium"
                    >
                        {{ __("Manage product attributes and variations") }}
                    </p>
                    <p
                        class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed"
                    >
                        {{
                            __(
                                "Product attributes represent the main characteristics of your products (e.g., color, size, material). These attributes help customers filter products in the store and make more accurate purchase decisions."
                            )
                        }}
                    </p>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div class="p-2">
            <!-- Empty State -->
            <div v-if="modelValue.length === 0" class="text-center py-4">
                <div
                    class="w-10 h-10 bg-linear-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-3xl flex items-center justify-center mx-auto shadow-inner"
                >
                    <i
                        class="fas fa-tags text-gray-400 dark:text-gray-500 text-lg"
                    ></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ __("No attributes added yet") }}
                </h4>
                <p
                    class="text-gray-600 dark:text-gray-400 text-md mb-2 max-w-md mx-auto"
                >
                    {{
                        __(
                            "Get started by adding your first product attribute to enhance your product catalog."
                        )
                    }}
                </p>
                <button
                    @click="addAttribute"
                    class="inline-flex items-center cursor-pointer gap-3 px-4 py-2 bg-linear-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 hover:from-blue-600 hover:to-blue-700 dark:hover:from-blue-700 dark:hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                >
                    <i class="fas fa-plus-circle text-lg"></i>
                    <span class="text-md">{{ __("Add First Attribute") }}</span>
                </button>

                <v-error :error="error" />
            </div>

            <!-- Attributes Grid -->
            <div v-else class="space-y-1">
                <div
                    v-for="(attr, index) in modelValue"
                    :key="index"
                    class="group bg-linear-to-br from-gray-50 to-white dark:from-gray-700 dark:to-gray-800 rounded-2xl border-2 border-gray-100 dark:border-gray-600 p-2 shadow-sm hover:shadow-2xl transition-all duration-300 hover:border-blue-200 dark:hover:border-blue-600"
                >
                    <!-- Attribute Header -->
                    <div
                        class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-2 pb-2 border-b border-gray-200 dark:border-gray-600"
                    >
                        <div class="flex items-end gap-4">
                            <div
                                class="w-12 h-12 bg-linear-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md"
                            >
                                <span class="text-white font-bold text-lg">
                                    {{ index + 1 }}
                                </span>
                            </div>
                            <div>
                                <h4
                                    class="text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    {{ __("Attribute") }} #{{ index + 1 }}
                                </h4>
                                <p
                                    v-if="attr.name"
                                    class="text-blue-600 dark:text-blue-400 font-medium"
                                >
                                    {{ attr.name }}
                                </p>
                                <p
                                    v-else
                                    class="text-gray-500 dark:text-gray-400 text-sm"
                                >
                                    {{ __("Unnamed attribute") }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="deleteAttribute(index)"
                            class="flex items-center gap-2 px-4 py-2 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200 border border-transparent hover:border-red-200 dark:hover:border-red-800 font-medium"
                        >
                            <i class="fas fa-trash-alt"></i>
                            <span>{{ __("Delete attribute") }}</span>
                        </button>
                    </div>

                    <!-- Form Grid -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-end"
                    >
                        <div>
                            <v-input
                                :label="__('Attribute Name')"
                                v-model="attr.name"
                                :required="true"
                                :placeholder="__('e.g., Color, Size, Material')"
                            />
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['name']"
                            />
                        </div>
                        <div>
                            <v-select
                                :label="__('Data Type')"
                                :options="typeOptions"
                                v-model="attr.type"
                                :required="true"
                            />
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['type']"
                            />
                        </div>
                        <div>
                            <v-select
                                :label="__('Display Widget')"
                                :options="widgetOptions"
                                v-model="attr.widget"
                                :required="true"
                            />
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['widget']"
                            />
                        </div>
                        <div>
                            <v-input
                                :label="__('Attribute Value')"
                                v-model="attr.value"
                                :required="true"
                                :placeholder="__('e.g., Red, Large, Cotton')"
                            />
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['value']"
                            />
                        </div>
                        <div>
                            <v-switch
                                :label="__('Multiple Values')"
                                v-model="attr.multiple"
                            />
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['multiple']"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div
            v-if="modelValue.length > 0"
            class="border-t border-gray-200 dark:border-gray-600 bg-linear-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-8 py-6"
        >
            <button
                @click="addAttribute"
                class="mx-auto cursor-pointer px-3 py-2 bg-linear-to-r from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 hover:from-green-600 hover:to-green-700 dark:hover:from-green-700 dark:hover:to-green-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-3"
            >
                <i class="fas fa-plus-circle text-xl"></i>
                <span class="text-md">{{ __("Add New Attribute") }}</span>
            </button>
        </div>
    </div>
</template>

<script>
import VError from "@/components/VError.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VSwitch from "@/components/VSwitch.vue";

export default {
    components: {
        VError,
        VInput,
        VSelect,
        VSwitch,
    },
    emits: ["update:modelValue"],
    props: {
        modelValue: {
            type: Array,
            default: [],
        },
        error: {
            type: Array,
            default: [],
        },
    },

    data() {
        return {
            typeOptions: [
                { name: "String", id: "string" },
                { name: "Number", id: "number" },
                { name: "Boolean", id: "boolean" },
            ],
            widgetOptions: [
                { name: "Checkbox", id: "checkbox" },
                { name: "Select", id: "select" },
            ],
            lastValidState: [],
        };
    },

    methods: {
        addAttribute() {
            this.modelValue.push({
                name: "",
                type: "string",
                value: "",
                widget: "checkbox",
                multiple: false,
                stock: 0,
            });

            this.$emit("update:modelValue", this.modelValue);
        },

        async deleteAttribute(index) {
            const item = this.modelValue[index];

            const result = await this.$swal({
                title: __("Are you sure?"),
                text: __(
                    "You are about to delete this attribute. This action cannot be undone."
                ),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: __("Yes, delete it!"),
                cancelButtonText: __("Cancel"),
                reverseButtons: true,
                focusCancel: true,
            });

            // If user cancels, return early
            if (!result.isConfirmed) {
                return;
            }

            // Local attribute (no server link)
            if (!item.links?.destroy) {
                this.modelValue.splice(index, 1);

                $notify.success(__("Attribute has been deleted successfully."));

                this.$emit("update:modelValue", this.modelValue);
                return;
            }

            // Server deletion required
            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status === 200) {
                    this.modelValue.splice(index, 1);

                    $notify.success(
                        __("Attribute has been deleted successfully.")
                    );
                }
            } catch (e) {
                $notify.error(
                    __(
                        "There was an error deleting the attribute. Please try again."
                    )
                );
            } finally {
                this.$emit("update:modelValue", this.modelValue);
            }
        },
    },
};
</script>
