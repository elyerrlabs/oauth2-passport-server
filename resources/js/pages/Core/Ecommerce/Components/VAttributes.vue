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
        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mb-6"
    >
        <!-- Header -->
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-600">
            <div class="flex items-start gap-3">
                <div
                    class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center"
                >
                    <i class="fas fa-pencil-alt text-white"></i>
                </div>
                <div>
                    <h2
                        class="text-base sm:text-lg font-bold text-gray-900 dark:text-white"
                    >
                        {{ __("Product Attributes") }}
                    </h2>
                    <p
                        class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm mt-1 leading-relaxed"
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
        <div class="p-3 sm:p-4">
            <!-- Empty State -->
            <div v-if="modelValue.length === 0" class="text-center py-4">
                <div
                    class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mx-auto"
                >
                    <i class="fas fa-tags text-gray-400 dark:text-gray-500"></i>
                </div>
                <h4
                    class="text-base font-bold text-gray-900 dark:text-white mt-2"
                >
                    {{ __("No attributes added yet") }}
                </h4>
                <p
                    class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm max-w-md mx-auto mt-1"
                >
                    {{
                        __(
                            "Get started by adding your first product attribute to enhance your product catalog."
                        )
                    }}
                </p>
                <button
                    @click="addAttribute"
                    class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium flex items-center mx-auto transition-colors"
                >
                    <i class="fas fa-plus-circle mr-2"></i>
                    {{ __("Add First Attribute") }}
                </button>
                <v-error :error="error" />
            </div>

            <!-- Attributes Grid -->
            <div v-else class="space-y-3">
                <div
                    v-for="(attr, index) in modelValue"
                    :key="index"
                    class="bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-3 sm:p-4"
                >
                    <!-- Attribute Header -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 pb-3 border-b border-gray-200 dark:border-gray-600"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-600 rounded-lg flex items-center justify-center"
                            >
                                <span
                                    class="text-white font-bold text-sm sm:text-base"
                                >
                                    {{ index + 1 }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="text-sm sm:text-base font-bold text-gray-900 dark:text-white truncate"
                                >
                                    {{ __("Attribute") }} #{{ index + 1 }}
                                </h4>
                                <p
                                    v-if="attr.name"
                                    class="text-blue-600 dark:text-blue-400 text-xs sm:text-sm truncate"
                                >
                                    {{ attr.name }}
                                </p>
                                <p
                                    v-else
                                    class="text-gray-500 dark:text-gray-400 text-xs"
                                >
                                    {{ __("Unnamed attribute") }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="deleteAttribute(index)"
                            class="mt-2 sm:mt-0 px-3 py-1.5 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded text-sm transition-colors flex items-center justify-center w-full sm:w-auto"
                        >
                            <i class="fas fa-trash-alt mr-1.5"></i>
                            <span>{{ __("Delete attribute") }}</span>
                        </button>
                    </div>

                    <!-- Form Fields -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 items-start"
                    >
                        <!-- Name -->
                        <div>
                            <v-input
                                :label="__('Attribute Name')"
                                v-model="attr.name"
                                :required="true"
                                :placeholder="__('e.g., Color, Size, Material')"
                                size="small"
                            />
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            >
                                {{
                                    __("A descriptive name for this attribute")
                                }}
                            </div>
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['name']"
                            />
                        </div>

                        <!-- Type -->
                        <div>
                            <v-select
                                :label="__('Data Type')"
                                :options="typeOptions"
                                v-model="attr.type"
                                :required="true"
                                size="small"
                            />
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            >
                                {{
                                    __("The type of data this attribute holds")
                                }}
                            </div>
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['type']"
                            />
                        </div>

                        <!-- Widget -->
                        <div>
                            <v-select
                                :label="__('Display Widget')"
                                :options="widgetOptions"
                                v-model="attr.widget"
                                :required="true"
                                size="small"
                            />
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            >
                                {{
                                    __(
                                        "How this attribute will be displayed to customers"
                                    )
                                }}
                            </div>
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['widget']"
                            />
                        </div>

                        <!-- Value -->
                        <div class="sm:col-span-2 lg:col-span-1">
                            <v-input
                                :label="__('Attribute Value')"
                                v-model="attr.value"
                                :required="true"
                                :placeholder="__('e.g., Red, Large, Cotton')"
                                size="small"
                            />
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            >
                                {{ __("The actual value for this attribute") }}
                            </div>
                            <v-error
                                v-if="error[index]"
                                :error="error[index]['value']"
                            />
                        </div>

                        <!-- Multiple Values -->
                        <div class="sm:col-span-2 lg:col-span-1">
                            <div class="mb-1">
                                <v-switch
                                    :label="__('Multiple Values')"
                                    v-model="attr.multiple"
                                    size="small"
                                />
                            </div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            >
                                {{
                                    __(
                                        "Allow multiple values for this attribute"
                                    )
                                }}
                            </div>
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
            class="border-t border-gray-200 dark:border-gray-600 p-3 sm:p-4"
        >
            <button
                @click="addAttribute"
                class="w-full sm:w-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium flex items-center justify-center transition-colors"
            >
                <i class="fas fa-plus-circle mr-2"></i>
                {{ __("Add New Attribute") }}
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
            });

            if (!result.isConfirmed) {
                return;
            }

            // Local attribute
            if (!item.links?.destroy) {
                this.modelValue.splice(index, 1);
                $notify.success(__("Attribute has been deleted successfully."));
                this.$emit("update:modelValue", this.modelValue);
                return;
            }

            // Server deletion
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
