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
    <div
        class="attributes-container bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 w-full"
    >
        <!-- Header -->
        <div class="flex items-center space-x-2 mb-4">
            <div
                class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center"
            >
                <i class="mdi mdi-pencil-plus text-white text-sm"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __("Attributes") }}
            </h3>
        </div>

        <!-- Dynamic attributes list -->
        <div class="space-y-4">
            <div
                v-for="(attr, index) in internalAttributes"
                :key="index"
                class="attribute-row bg-white dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700"
            >
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end"
                >
                    <!-- Name -->
                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Name") }}
                        </label>
                        <input
                            v-model="attr.name"
                            @input="emitUpdate"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            :placeholder="__('Name')"
                        />
                    </div>

                    <!-- Type -->
                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Type") }}
                        </label>
                        <select
                            v-model="attr.type"
                            @change="emitUpdate"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                        >
                            <option
                                v-for="option in typeOptions"
                                :key="option"
                                :value="option"
                            >
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <!-- Value -->
                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Value") }}
                        </label>
                        <input
                            v-model="attr.value"
                            @input="emitUpdate"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            :placeholder="__('Value')"
                        />
                    </div>

                    <!-- Stock -->
                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Stock") }}
                        </label>
                        <input
                            v-model="attr.stock"
                            @input="emitUpdate"
                            type="number"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                            :placeholder="__('Stock')"
                        />
                    </div>

                    <!-- Widget -->
                    <div class="input-group">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            {{ __("Widget") }}
                        </label>
                        <select
                            v-model="attr.widget"
                            @change="emitUpdate"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                        >
                            <option
                                v-for="option in widgetOptions"
                                :key="option"
                                :value="option"
                            >
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <!-- Multiple & Delete -->
                    <div class="flex items-center space-x-3 justify-end">
                        <!-- Multiple -->
                        <div class="checkbox-container flex items-center">
                            <input
                                v-model="attr.multiple"
                                @change="emitUpdate"
                                type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                            />
                            <label
                                class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Multiple") }}
                            </label>
                        </div>

                        <!-- Delete button -->
                        <button
                            @click="deleteAttribute(index)"
                            class="w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-md flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        >
                            <i class="mdi mdi-delete text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add new attribute -->
        <button
            @click="addAttribute"
            class="mt-4 px-4 py-2 border border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
        >
            <i class="mdi mdi-plus text-sm"></i>
            <span>{{ __("Add attribute") }}</span>
        </button>
    </div>
</template>

<script>
export default {
    name: "AttributesInput",
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
    },

    data() {
        return {
            internalAttributes: [],
            typeOptions: ["string", "integer", "boolean"],
            widgetOptions: ["checkbox", "select", "radio", "slide"],
            lastValidState: [],
        };
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
                name: attr.name || null,
                type: attr.type || "string",
                value: attr.value || null,
                widget: attr.widget || "checkbox",
                multiple: attr.multiple || false,
                stock: attr.stock || 0,
                ...(attr.links ? { links: attr.links } : {}),
            }));
        },

        addAttribute() {
            this.internalAttributes.push({
                name: null,
                type: "string",
                value: null,
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

            if (!item.links?.destroy) {
                this.internalAttributes.splice(index, 1);
                this.emitUpdate();
                return;
            }

            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status === 200) {
                    this.internalAttributes.splice(index, 1);
                    this.emitUpdate();
                    this.$notify?.({
                        message: "Attribute deleted",
                        type: "positive",
                    });
                }
            } catch (e) {
                this.$notify?.({
                    message: "Error deleting attribute",
                    type: "negative",
                });
            }
        },
    },
};
</script>
