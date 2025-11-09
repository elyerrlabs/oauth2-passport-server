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
    <div class="w-full">
        <!-- Label -->
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
            <small
                v-if="description"
                class="block text-blue-600 dark:text-blue-400 text-xs mt-1 font-normal"
            >
                {{ description }}
            </small>
        </label>

        <!-- Select container -->
        <div class="relative" ref="selectContainer">
            <!-- Trigger -->
            <button
                type="button"
                @click="toggleDropdown"
                class="flex items-center justify-between w-full px-4 py-3 text-left border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm bg-white dark:bg-gray-800 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                :class="{
                    'ring-2 ring-blue-500 border-blue-500 dark:border-blue-400':
                        isOpen,
                    'border-red-500 dark:border-red-400': error?.length,
                }"
                :disabled="loading"
            >
                <!-- Selected slot -->
                <span class="truncate flex-1 text-left">
                    <template v-if="$slots.selected">
                        <slot
                            name="selected"
                            :option="selectedOptions"
                            :placeholder="placeholder"
                        />
                    </template>
                    <template v-else>
                        <template v-if="multiple">
                            <span
                                v-if="selectedOptions.length"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="(opt, i) in selectedOptions"
                                    :key="i"
                                    class="bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-lg text-sm flex items-center space-x-2 group transition-all duration-200"
                                >
                                    <span class="font-medium">{{
                                        opt[labelKey]
                                    }}</span>
                                    <button
                                        type="button"
                                        @click.stop="selectOption(opt)"
                                        class="w-4 h-4 bg-blue-200 dark:bg-blue-800 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 hover:bg-blue-300 dark:hover:bg-blue-700 transition-colors group-hover:scale-110"
                                    >
                                        <i class="mdi mdi-close text-xs"></i>
                                    </button>
                                </span>
                            </span>
                            <span
                                v-else
                                class="text-gray-400 dark:text-gray-100 font-medium"
                            >
                                {{ __(placeholder) }}
                            </span>
                        </template>

                        <template v-else>
                            <span
                                :class="
                                    selectedOptions
                                        ? 'text-gray-900 dark:text-white font-medium'
                                        : 'text-gray-400 dark:text-gray-500'
                                "
                            >
                                {{
                                    selectedOptions
                                        ? selectedOptions[labelKey]
                                        : __(placeholder)
                                }}
                            </span>
                        </template>
                    </template>
                </span>

                <div class="flex items-center space-x-3 ml-3">
                    <!-- Loading indicator -->
                    <div v-if="loading" class="flex items-center">
                        <div
                            class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"
                        ></div>
                    </div>

                    <!-- Clear button -->
                    <button
                        v-if="hasSelection && clearable && !loading"
                        @click.stop="clearSelection"
                        type="button"
                        class="p-1.5 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200"
                    >
                        <i class="mdi mdi-close text-base"></i>
                    </button>

                    <!-- Chevron -->
                    <i
                        class="mdi mdi-chevron-down text-gray-400 dark:text-gray-500 transition-transform duration-300"
                        :class="{
                            'rotate-180 transform': isOpen,
                            'animate-bounce': !isOpen && !hasSelection,
                        }"
                    ></i>
                </div>
            </button>

            <!-- Dropdown -->
            <transition
                enter-active-class="transition-all duration-200 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-2"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition-all duration-150 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-2"
            >
                <div
                    v-show="isOpen"
                    class="absolute w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl max-h-80 overflow-hidden z-50 backdrop-blur-sm"
                    :class="panelClass"
                >
                    <!-- Search -->
                    <div
                        v-if="searchable"
                        class="p-3 border-b border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/50"
                    >
                        <div class="relative">
                            <i
                                class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm"
                            ></i>
                            <input
                                type="text"
                                v-model="searchQuery"
                                @input="onSearch"
                                ref="searchInput"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                                :placeholder="searchPlaceholder"
                            />
                            <button
                                v-if="searchQuery"
                                @click="
                                    searchQuery = '';
                                    onSearch();
                                "
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 rounded transition-colors"
                            >
                                <i class="mdi mdi-close text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="overflow-y-auto max-h-64 custom-scrollbar">
                        <!-- Clear option -->
                        <div
                            v-if="clearable && !multiple"
                            @click="clearSelection"
                            class="flex items-center justify-between px-4 py-3 cursor-pointer text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition-colors duration-150 group"
                        >
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-5 h-5 rounded border border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500 transition-colors"
                                ></div>
                                <span class="text-sm font-medium italic">
                                    {{ __("Not selected") }}
                                </span>
                            </div>
                            <i
                                class="mdi mdi-close text-sm opacity-0 group-hover:opacity-100 transition-opacity"
                            ></i>
                        </div>

                        <!-- No results -->
                        <div
                            v-if="!filteredOptions.length && !loading"
                            class="px-4 py-8 text-center"
                        >
                            <div
                                class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3"
                            >
                                <i
                                    class="mdi mdi-magnify-remove text-gray-400 dark:text-gray-500 text-xl"
                                ></i>
                            </div>
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1"
                            >
                                {{ __("No results found") }}
                            </div>
                            <div
                                class="text-xs text-gray-400 dark:text-gray-500"
                            >
                                {{ __("Try adjusting your search") }}
                            </div>
                        </div>

                        <!-- Custom options slot -->
                        <template v-if="$slots.option">
                            <div
                                v-for="(option, index) in filteredOptions"
                                :key="getOptionKey(option, index)"
                                @click="selectOption(option)"
                                class="cursor-pointer transition-all duration-150 group"
                                :class="[
                                    isSelected(option)
                                        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'
                                        : 'hover:bg-white dark:hover:bg-gray-700/50 text-gray-900 dark:text-white',
                                    index < filteredOptions.length - 1
                                        ? 'border-b border-gray-100 dark:border-gray-700'
                                        : '',
                                ]"
                            >
                                <slot
                                    name="option"
                                    :option="option"
                                    :selected="isSelected(option)"
                                    :index="index"
                                />
                            </div>
                        </template>

                        <!-- Default options -->
                        <template v-else>
                            <div
                                v-for="(option, index) in filteredOptions"
                                :key="getOptionKey(option, index)"
                                @click="selectOption(option)"
                                class="flex items-center justify-between px-4 py-3 cursor-pointer transition-all duration-150 group"
                                :class="[
                                    isSelected(option)
                                        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'
                                        : 'hover:bg-white dark:hover:bg-gray-700/50 text-gray-900 dark:text-white',
                                    index < filteredOptions.length - 1
                                        ? 'border-b border-gray-100 dark:border-gray-700'
                                        : '',
                                ]"
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-5 h-5 rounded border flex items-center justify-center transition-all duration-200"
                                        :class="[
                                            isSelected(option)
                                                ? 'bg-blue-500 dark:bg-blue-600 border-blue-500 dark:border-blue-600 text-white'
                                                : 'border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500',
                                        ]"
                                    >
                                        <i
                                            v-if="isSelected(option)"
                                            class="mdi mdi-check text-xs"
                                        ></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium block">
                                            {{ option[labelKey] }}
                                        </span>
                                        <span
                                            v-if="option.description"
                                            class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 block"
                                        >
                                            {{ option.description }}
                                        </span>
                                    </div>
                                </div>
                                <i
                                    v-if="isSelected(option) && !multiple"
                                    class="mdi mdi-check-circle text-blue-500 dark:text-blue-400 text-lg ml-2"
                                ></i>
                            </div>
                        </template>
                    </div>

                    <!-- Loading state -->
                    <div
                        v-if="loading"
                        class="px-4 py-4 text-center border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/30"
                    >
                        <div
                            class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400"
                        >
                            <div
                                class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"
                            ></div>
                            <span class="text-sm font-medium">{{
                                __("Loading options...")
                            }}</span>
                        </div>
                    </div>

                    <!-- Results count -->
                    <div
                        v-if="filteredOptions.length > 0 && searchQuery"
                        class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900/30"
                    >
                        {{ filteredOptions.length }} {{ __("results found") }}
                    </div>
                </div>
            </transition>
        </div>

        <!-- Error -->
        <v-error :error="error" class="mt-2" />

        <!-- Selection summary -->
        <div
            v-if="multiple && selectedOptions.length > 0"
            class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-1"
        >
            <i class="mdi mdi-check-circle text-green-500"></i>
            <span>{{ selectedOptions.length }} {{ __("items selected") }}</span>
        </div>

        <!-- Hidden select for form submission -->
        <select v-model="internalValue" class="hidden" :multiple="multiple">
            <option value="" disabled>{{ __(placeholder) }}</option>
            <option
                v-for="(option, index) in options"
                :key="getOptionKey(option, index)"
                :value="option[valueKey]"
            >
                {{ option[labelKey] }}
            </option>
        </select>
    </div>
</template>

<script>
import VError from "@/components/VError.vue";

export default {
    name: "VSelect",
    components: { VError },

    props: {
        modelValue: {
            type: [String, Number, Object, Array, null],
            default: null,
        },
        options: {
            type: Array,
            default: () => [],
        },
        label: String,
        labelKey: {
            type: String,
            default: "name",
        },
        valueKey: {
            type: String,
            default: "id",
        },
        placeholder: {
            type: String,
            default: "Select an option",
        },
        searchPlaceholder: {
            type: String,
            default: "Search...",
        },
        clearable: Boolean,
        searchable: Boolean,
        loading: Boolean,
        required: Boolean,
        error: { type: [Array, String], default: [] },
        returnObject: Boolean,
        panelClass: String,
        description: String,
        multiple: {
            type: Boolean,
            default: false,
        },
    },

    emits: ["update:modelValue", "change", "search"],

    data() {
        return {
            internalValue: this.multiple ? [] : null,
            isOpen: false,
            searchQuery: "",
        };
    },

    computed: {
        selectedOptions() {
            if (!this.options?.length) return this.multiple ? [] : null;

            if (this.multiple == true) {
                return this.options.filter(
                    (opt) =>
                        Array.isArray(this.internalValue) &&
                        this.internalValue.includes(opt[this.valueKey])
                );
            }

            const selected = this.options.find(
                (opt) => opt[this.valueKey] === this.internalValue
            );
            return selected ? selected : null;
        },

        filteredOptions() {
            if (!this.searchQuery) return this.options;
            const query = this.searchQuery.toLowerCase();
            return this.options.filter((option) =>
                String(option[this.labelKey] || "")
                    .toLowerCase()
                    .includes(query)
            );
        },

        hasSelection() {
            return this.multiple
                ? this.internalValue.length > 0
                : !!this.internalValue;
        },
    },

    watch: {
        modelValue: {
            immediate: true,
            handler(newVal) {
                if (this.multiple) {
                    this.internalValue = Array.isArray(newVal) ? newVal : [];
                } else {
                    this.internalValue = newVal ?? null;
                }
            },
        },

        isOpen(val) {
            if (val) {
                document.addEventListener("click", this.handleClickOutside);
                document.addEventListener("keydown", this.handleEscapeKey);
                this.$nextTick(() => this.$refs.searchInput?.focus());
            } else {
                document.removeEventListener("click", this.handleClickOutside);
                document.removeEventListener("keydown", this.handleEscapeKey);
                this.searchQuery = "";
            }
        },
    },

    methods: {
        toggleDropdown() {
            if (this.loading) return;
            this.isOpen = !this.isOpen;
        },

        selectOption(option) {
            const value = option[this.valueKey];

            if (this.multiple) {
                const exists = this.internalValue.includes(value);

                if (exists) {
                    this.internalValue = this.internalValue.filter(
                        (id) => id !== value
                    );
                } else {
                    this.internalValue = [...this.internalValue, value];
                }

                this.$emit("update:modelValue", [...this.internalValue]);
                this.$emit("change", [...this.internalValue]);
            } else {
                this.internalValue = value;
                this.$emit("update:modelValue", value);
                this.$emit("change", value);
                this.isOpen = false;
            }

            this.searchQuery = "";
        },

        clearSelection() {
            this.internalValue = this.multiple ? [] : null;
            this.$emit("update:modelValue", this.multiple ? [] : null);
            this.$emit("change", this.multiple ? [] : null);
            this.isOpen = false;
        },

        getOptionKey(option, index) {
            return option[this.valueKey] ?? `option-${index}`;
        },

        isSelected(option) {
            const value = option[this.valueKey];
            return this.multiple
                ? this.internalValue.includes(value)
                : value === this.internalValue;
        },

        onSearch() {
            this.$emit("search", this.searchQuery);
        },

        handleClickOutside(event) {
            if (
                this.$refs.selectContainer &&
                !this.$refs.selectContainer.contains(event.target)
            ) {
                this.isOpen = false;
            }
        },

        handleEscapeKey(event) {
            if (event.key === "Escape" && this.isOpen) {
                this.isOpen = false;
            }
        },
    },

    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
        document.removeEventListener("keydown", this.handleEscapeKey);
    },
};
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.dark .custom-scrollbar::-webkit-scrollbar-track {
    background: #374151;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #6b7280;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>
