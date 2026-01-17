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
        class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700"
    >
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg px-4 py-3 flex items-center justify-between mb-4"
        >
            <div class="flex items-center">
                <button
                    @click="show = !show"
                    class="p-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 mr-3"
                    :aria-label="__('Toggle Filters')"
                >
                    <i class="mdi mdi-tune text-xl"></i>
                </button>
                <span class="text-lg font-semibold">{{
                    __("Advanced Filters")
                }}</span>
            </div>

            <button
                @click="clean"
                class="p-2 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                :aria-label="__('Clear Filters')"
            >
                <i class="mdi mdi-broom text-xl"></i>
            </button>
        </div>

        <!-- Filters Section -->
        <transition
            enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-200 ease-in"
            enter-from-class="opacity-0 max-h-0"
            enter-to-class="opacity-100 max-h-96"
            leave-from-class="opacity-100 max-h-96"
            leave-to-class="opacity-0 max-h-0"
        >
            <div
                v-show="show"
                class="bg-white dark:bg-gray-900 rounded-lg p-4 overflow-hidden"
            >
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pb-3"
                >
                    <!-- Search -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 block"
                        >
                            {{ __("Search") }}
                        </label>
                        <div class="relative">
                            <input
                                v-model="inputValue"
                                type="text"
                                :placeholder="__('Type to search...')"
                                @input="emitFilterChange"
                                @keyup="emitFilterChange"
                                class="w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 dark:bg-gray-800 dark:text-white"
                                :class="selected_parameter ? 'pl-12' : 'pl-3'"
                            />
                            <div
                                v-if="selected_parameter"
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <span
                                    class="text-gray-500 dark:text-gray-400 text-sm"
                                    >{{ selected_parameter }}:</span
                                >
                            </div>
                            <div
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                            >
                                <i
                                    class="mdi mdi-magnify text-gray-400 dark:text-gray-500"
                                ></i>
                            </div>
                        </div>
                    </div>

                    <!-- Search By -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 block"
                        >
                            {{ __("Search By") }}
                        </label>
                        <div class="relative">
                            <select
                                v-model="selected_parameter"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none bg-white dark:bg-gray-800 dark:text-white"
                            >
                                <option value="">
                                    {{ __("Select field...") }}
                                </option>
                                <option
                                    v-for="param in params"
                                    :key="param.value"
                                    :value="param.value"
                                >
                                    {{ param.key }}
                                </option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-400"
                            >
                                <i class="mdi mdi-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Order By -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 block"
                        >
                            {{ __("Order By") }}
                        </label>
                        <div class="relative">
                            <select
                                v-model="order_by"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none bg-white dark:bg-gray-800 dark:text-white"
                            >
                                <option value="">
                                    {{ __("Select field...") }}
                                </option>
                                <option
                                    v-for="param in params"
                                    :key="param.value"
                                    :value="param.value"
                                >
                                    {{ param.key }}
                                </option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-400"
                            >
                                <i class="mdi mdi-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Order Type -->
                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 block"
                        >
                            {{ __("Order Type") }}
                        </label>
                        <div class="relative">
                            <select
                                v-model="order_type"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 appearance-none bg-white dark:bg-gray-800 dark:text-white"
                            >
                                <option value="">
                                    {{ __("Select order...") }}
                                </option>
                                <option
                                    v-for="type in orderTypes"
                                    :key="type.value"
                                    :value="type.value"
                                >
                                    {{ type.label }}
                                </option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-400"
                            >
                                <i class="mdi mdi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    emits: ["change"],
    props: {
        params: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            selected_parameter:
                this.params.length > 0 ? this.params[0].value : "",
            inputValue: "",
            show: false,
            order_by: "",
            order_type: "",
            orderTypes: [
                { label: "Ascending", value: "asc" },
                { label: "Descending", value: "desc" },
            ],
        };
    },
    watch: {
        selected_parameter: "emitFilterChange",
        order_by: "emitFilterChange",
        order_type: "emitFilterChange",
    },
    methods: {
        clean() {
            this.inputValue = "";
            this.order_by = "";
            this.order_type = "";
            this.show = false;
            this.emitFilterChange();
        },
        emitFilterChange() {
            const filterObject = {};

            if (this.inputValue) {
                filterObject[this.selected_parameter] = this.inputValue;
            }

            if (this.order_by) {
                filterObject.order_by = this.order_by;
            }

            if (this.order_type) {
                filterObject.order_type = this.order_type;
            }

            this.$emit("change", filterObject);
        },
    },
};
</script>
