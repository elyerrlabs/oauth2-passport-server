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
    <div class="w-full max-w-7xl mx-auto p-6">
        <!-- Header Section -->
        <div
            class="mb-8 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 shadow-sm"
        >
            <div class="flex items-start gap-2 mb-4">
                <div
                    class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg"
                >
                    <i class="fas fa-pencil-alt text-white text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">
                        {{ __("Product Attributes") }}
                    </h2>
                    <p class="text-md text-gray-700 font-medium mb-4">
                        {{ __("Manage product attributes and variations") }}
                    </p>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        {{
                            __(
                                "Product attributes represent the main characteristics of your products (e.g., color, size, material). These attributes help customers filter products in the store and make more accurate purchase decisions."
                            )
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-8"
        >
            <!-- Card Header -->
            <div
                class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200"
            >
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
                >
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ __("Attributes List") }}
                        </h3>
                        <p class="text-gray-600">
                            {{
                                __("Manage all product attributes in one place")
                            }}
                        </p>
                    </div>
                    <span
                        class="px-4 py-2 bg-blue-500 text-white rounded-full text-sm font-semibold shadow-sm"
                    >
                        {{ modelValue.length }} {{ __("attribute") }}(s)
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Empty State -->
                <div v-if="modelValue.length === 0" class="text-center py-16">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner"
                    >
                        <i class="fas fa-tags text-gray-400 text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-3">
                        {{ __("No attributes added yet") }}
                    </h4>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        {{
                            __(
                                "Get started by adding your first product attribute to enhance your product catalog."
                            )
                        }}
                    </p>
                    <button
                        @click="addAttribute"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                    >
                        <i class="fas fa-plus-circle text-xl"></i>
                        <span class="text-lg">{{
                            __("Add First Attribute")
                        }}</span>
                    </button>
                </div>

                <!-- Attributes Grid -->
                <div v-else class="space-y-6">
                    <div
                        v-for="(attr, index) in modelValue"
                        :key="index"
                        class="group bg-gradient-to-br from-gray-50 to-white rounded-2xl border-2 border-gray-100 p-6 shadow-sm hover:shadow-2xl transition-all duration-300 hover:border-blue-200"
                    >
                        <!-- Attribute Header -->
                        <div
                            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6 pb-6 border-b border-gray-200"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md"
                                >
                                    <span
                                        class="text-white font-bold text-lg"
                                        >{{ index + 1 }}</span
                                    >
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900">
                                        {{ __("Attribute") }} #{{ index + 1 }}
                                    </h4>
                                    <p
                                        v-if="attr.name"
                                        class="text-blue-600 font-medium"
                                    >
                                        {{ attr.name }}
                                    </p>
                                    <p v-else class="text-gray-500 text-sm">
                                        {{ __("Unnamed attribute") }}
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="deleteAttribute(index)"
                                class="flex items-center gap-2 px-4 py-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200 border border-transparent hover:border-red-200 font-medium"
                                :title="__('Delete attribute')"
                            >
                                <i class="fas fa-trash-alt"></i>
                                <span>{{ __("Delete") }}</span>
                            </button>
                        </div>

                        <!-- Form Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <v-input
                                :label="__('Attribute Name')"
                                v-model="attr.name"
                                :required="true"
                                :placeholder="__('e.g., Color, Size, Material')"
                            />
                            <v-select
                                :label="__('Data Type')"
                                :options="typeOptions"
                                v-model="attr.type"
                                :required="true"
                            />
                            <v-select
                                :label="__('Display Widget')"
                                :options="widgetOptions"
                                v-model="attr.widget"
                                :required="true"
                            />
                            <v-input
                                :label="__('Attribute Value')"
                                v-model="attr.value"
                                :required="true"
                                :placeholder="__('e.g., Red, Large, Cotton')"
                            />
                            <!--
                                <v-input
                                :label="__('Available Stock')"
                                v-model="attr.stock"
                                :required="true"
                                type="number"
                                :placeholder="__('Enter stock quantity')"
                                />
                                -->
                            <v-switch
                                :label="__('Multiple Values')"
                                v-model="attr.multiple"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Button -->
            <div
                v-if="modelValue.length > 0"
                class="border-t border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6"
            >
                <button
                    @click="addAttribute"
                    class="w-full max-w-md mx-auto px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-3"
                >
                    <i class="fas fa-plus-circle text-xl"></i>
                    <span class="text-lg">{{ __("Add New Attribute") }}</span>
                </button>
            </div>
        </div>

        <v-error :error="error" />
    </div>
</template>

<script>
import VError from "./VError.vue";
import VInput from "./VInput.vue";
import VSelect from "./VSelect.vue";
import VSwitch from "./VSwitch.vue";

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
                title: this.__("Are you sure?"),
                text: this.__(
                    "You are about to delete this attribute. This action cannot be undone."
                ),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: this.__("Yes, delete it!"),
                cancelButtonText: this.__("Cancel"),
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

                // Show success notification with SweetAlert2
                await this.$swal({
                    title: this.__("Deleted!"),
                    text: this.__("Attribute has been deleted successfully."),
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: "top-end",
                });

                this.$emit("update:modelValue", this.modelValue);
                return;
            }

            // Server deletion required
            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status === 200) {
                    this.modelValue.splice(index, 1);

                    await this.$swal({
                        title: this.__("Deleted!"),
                        text: this.__(
                            "Attribute has been deleted successfully."
                        ),
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: "top-end",
                    });
                }
            } catch (e) {
                await this.$swal({
                    title: this.__("Error!"),
                    text: this.__(
                        "There was an error deleting the attribute. Please try again."
                    ),
                    icon: "error",
                    confirmButtonText: this.__("OK"),
                });
            } finally {
                this.$emit("update:modelValue", this.modelValue);
            }
        },
    },
};
</script>
