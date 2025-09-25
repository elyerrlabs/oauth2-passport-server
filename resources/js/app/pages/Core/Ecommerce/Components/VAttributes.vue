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
    <div class="w-full max-w-7xl mx-auto p-3">
        <!-- Header Section -->
        <div class="mb-4">
            <div class="flex items-center space-x-4 mb-2">
                <div
                    class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm"
                >
                    <i class="fas fa-pencil-alt text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __("Product Attributes") }}
                    </h2>
                    <p class="text-gray-600">
                        {{ __("Manage product attributes and variations") }}
                    </p>
                </div>
            </div>
            <p class="text-gray-500 text-sm leading-relaxed mt-2">
                {{
                    __(
                        "Product attributes represent the main characteristics of your products (e.g., color, size, material). These attributes help customers filter products in the store and make more accurate purchase decisions."
                    )
                }}
            </p>
        </div>

        <!-- Main Content Card -->
        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >
            <!-- Attributes List -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __("Attributes List") }}
                    </h3>
                    <span
                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium"
                    >
                        {{ internalAttributes.length }} {{ __("attribute") }}(s)
                    </span>
                </div>

                <!-- Empty State -->
                <div
                    v-if="internalAttributes.length === 0"
                    class="text-center py-6"
                >
                    <div
                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                    >
                        <i class="fas fa-tags text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">
                        {{ __("No attributes added yet") }}
                    </h4>
                    <p class="text-gray-500 mb-6">
                        {{
                            __(
                                "Get started by adding your first product attribute"
                            )
                        }}
                    </p>
                </div>

                <!-- Attributes Grid -->
                <div v-else class="space-y-4">
                    <div
                        v-for="(attr, index) in internalAttributes"
                        :key="index"
                        class="bg-gray-50 rounded-lg p-6 border border-gray-200 hover:border-blue-300 transition-colors duration-200"
                    >
                        <div
                            class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start"
                        >
                            <!-- Name - Full width on mobile, 3 cols on desktop -->
                            <div class="lg:col-span-2">
                                <label
                                    class="text-sm font-medium text-gray-700 mb-2 flex items-center space-x-2"
                                >
                                    <i
                                        class="fas fa-tag text-gray-400 text-sm"
                                    ></i>
                                    <span>{{ __("Name") }}</span>
                                </label>
                                <input
                                    v-model="attr.name"
                                    type="text"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="e.g., Color, Size"
                                    @input="emitUpdate"
                                />
                            </div>

                            <!-- Type and Widget - 2 cols each on desktop -->
                            <div class="lg:col-span-2">
                                <label
                                    class="text-sm font-medium text-gray-700 mb-2 flex items-center space-x-2"
                                >
                                    <i
                                        class="fas fa-list text-gray-400 text-sm"
                                    ></i>
                                    <span>{{ __("Type") }}</span>
                                </label>
                                <div class="relative">
                                    <select
                                        v-model="attr.type"
                                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white pr-10"
                                        @change="emitUpdate"
                                    >
                                        <option
                                            v-for="option in typeOptions"
                                            :key="option"
                                            :value="option"
                                        >
                                            {{ option }}
                                        </option>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"
                                    ></i>
                                </div>
                            </div>

                            <div class="lg:col-span-2">
                                <label
                                    class="text-sm font-medium text-gray-700 mb-2 flex items-center space-x-2"
                                >
                                    <i
                                        class="fas fa-puzzle-piece text-gray-400 text-sm"
                                    ></i>
                                    <span>{{ __("Widget") }}</span>
                                </label>
                                <div class="relative">
                                    <select
                                        v-model="attr.widget"
                                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white pr-10"
                                        @change="emitUpdate"
                                    >
                                        <option
                                            v-for="option in widgetOptions"
                                            :key="option"
                                            :value="option"
                                        >
                                            {{ option }}
                                        </option>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"
                                    ></i>
                                </div>
                            </div>

                            <!-- Value and Stock - 2 cols each on desktop -->
                            <div class="lg:col-span-2">
                                <label
                                    class="text-sm font-medium text-gray-700 mb-2 flex items-center space-x-2"
                                >
                                    <i
                                        class="fas fa-code text-gray-400 text-sm"
                                    ></i>
                                    <span>{{ __("Value") }}</span>
                                </label>
                                <input
                                    v-model="attr.value"
                                    type="text"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    placeholder="e.g., Red, Large"
                                    @input="emitUpdate"
                                />
                            </div>

                            <div class="lg:col-span-1">
                                <label
                                    class="text-sm font-medium text-gray-700 mb-2 flex items-center space-x-2"
                                >
                                    <i
                                        class="fas fa-cubes text-gray-400 text-sm"
                                    ></i>
                                    <span>{{ __("Stock") }}</span>
                                </label>
                                <input
                                    v-model="attr.stock"
                                    type="number"
                                    min="0"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    @input="emitUpdate"
                                />
                            </div>

                            <!-- Multiple Checkbox and Delete Button -->
                            <div
                                class="lg:col-span-2 flex items-center justify-between space-x-4 pt-2"
                            >
                                <label
                                    class="flex items-center space-x-3 cursor-pointer group"
                                >
                                    <div class="relative">
                                        <input
                                            v-model="attr.multiple"
                                            type="checkbox"
                                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-all duration-200"
                                            @change="emitUpdate"
                                        />
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                            >{{ __("Multiple Values") }}</span
                                        >
                                        <span class="text-xs text-gray-500">{{
                                            __("Allow multiple selections")
                                        }}</span>
                                    </div>
                                </label>

                                <button
                                    @click="deleteAttribute(index)"
                                    class="flex-shrink-0 w-12 h-12 flex items-center justify-center border border-red-200 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200 group"
                                    :title="__('Delete attribute')"
                                >
                                    <i
                                        class="fas fa-trash-alt group-hover:scale-110 transition-transform duration-200"
                                    ></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <v-error :error="error" />
            </div>

            <!-- Add Button Section -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                <button
                    @click="addAttribute"
                    class="w-full max-w-md mx-auto px-6 py-4 border-2 border-dashed border-blue-300 text-blue-600 rounded-xl hover:bg-blue-50 hover:border-blue-400 transition-all duration-200 flex items-center justify-center space-x-3 font-medium group"
                >
                    <i
                        class="fas fa-plus-circle text-lg group-hover:scale-110 transition-transform duration-200"
                    ></i>
                    <span class="text-lg">{{ __("Add New Attribute") }}</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import VError from "./VError.vue";

export default {
    components: {
        VError,
    },
    emits: ["update:modelValue"],
    props: {
        modelValue: {
            type: Array,
            required: true,
            default: () => [],
            validator: (value) => {
                return value.every(
                    (attr) =>
                        typeof attr === "object" &&
                        "name" in attr &&
                        "type" in attr &&
                        "value" in attr &&
                        "widget" in attr &&
                        "multiple" in attr &&
                        "stock" in attr
                );
            },
        },
        error: {
            type: Array,
            default: [],
        },
    },

    data() {
        return {
            internalAttributes: [],
            typeOptions: ["string", "integer", "boolean"],
            widgetOptions: ["checkbox", "select", "radio", "slide"],
            lastValidState: [],
        };
    },

    computed: {
        multipleCount() {
            return this.internalAttributes.filter((attr) => attr.multiple)
                .length;
        },
        totalStock() {
            return this.internalAttributes.reduce(
                (sum, attr) => sum + (parseInt(attr.stock) || 0),
                0
            );
        },
        linkedCount() {
            return this.internalAttributes.filter((attr) => attr.links).length;
        },
    },

    watch: {
        modelValue: {
            immediate: true,
            deep: true,
            handler(newVal) {
                this.lastValidState = JSON.parse(JSON.stringify(newVal));
                this.internalAttributes = this.normalizeAttributes(newVal);
            },
        },
    },

    methods: {
        normalizeAttributes(attrs) {
            return attrs.map((attr) => ({
                name: attr.name || "",
                type: attr.type || "string",
                value: attr.value || "",
                widget: attr.widget || "checkbox",
                multiple: attr.multiple || false,
                stock: attr.stock || 0,
                ...(attr.links ? { links: attr.links } : {}),
            }));
        },

        addAttribute() {
            this.internalAttributes.push({
                name: "",
                type: "string",
                value: "",
                widget: "checkbox",
                multiple: false,
                stock: 0,
            });
            this.emitUpdate();
        },

        emitUpdate() {
            const attributes = JSON.parse(
                JSON.stringify(this.internalAttributes)
            );
            this.$emit("update:modelValue", attributes);
        },

        async deleteAttribute(index) {
            const item = this.internalAttributes[index];

            // Replace confirm with SweetAlert2
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
                this.internalAttributes.splice(index, 1);
                this.emitUpdate();

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
                return;
            }

            // Server deletion required
            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status === 200) {
                    this.internalAttributes.splice(index, 1);
                    this.emitUpdate();

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
            }
        },
    },
};
</script>
