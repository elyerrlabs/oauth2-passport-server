<template>
    <div class="w-full">
        <!-- Label -->
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700 mb-1"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
            <small v-if="description" class="block text-blue-700">
                {{ description }}
            </small>
        </label>

        <!-- Select container -->
        <div class="relative" ref="selectContainer">
            <!-- Trigger -->
            <button
                type="button"
                @click="toggleDropdown"
                class="flex items-center justify-between w-full px-3 py-2 text-left border border-gray-300 rounded-lg shadow-sm bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                :class="{ 'ring-2 ring-blue-500 border-blue-500': isOpen }"
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
                                class="flex flex-wrap gap-1"
                            >
                                <span
                                    v-for="(opt, i) in selectedOptions"
                                    :key="i"
                                    class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs flex items-center space-x-1"
                                >
                                    <span>{{ opt[labelKey] }}</span>
                                    <i
                                        class="mdi mdi-close cursor-pointer text-[10px]"
                                        @click.stop="selectOptions(opt)"
                                    ></i>
                                </span>
                            </span>
                            <span v-else class="text-gray-400">{{
                                __(placeholder)
                            }}</span>
                        </template>

                        <template v-else>
                            {{
                                selectedOptions
                                    ? selectedOptions[labelKey]
                                    : __(placeholder)
                            }}
                        </template>
                    </template>
                </span>

                <div class="flex items-center space-x-2 ml-2">
                    <button
                        v-if="internalValue && clearable"
                        @click.stop="clearSelection"
                        type="button"
                        class="p-1 text-gray-400 hover:text-gray-600 rounded transition"
                    >
                        <i class="mdi mdi-close text-sm"></i>
                    </button>
                    <i
                        class="mdi mdi-chevron-down text-gray-400 transition-transform duration-200"
                        :class="{ 'rotate-180': isOpen }"
                    ></i>
                </div>
            </button>

            <!-- Dropdown -->
            <div
                v-show="isOpen"
                class="absolute w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-hidden z-50"
                :class="panelClass"
            >
                <!-- Search -->
                <div v-if="searchable" class="p-2 border-b border-gray-100">
                    <div class="relative">
                        <i
                            class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"
                        ></i>
                        <input
                            type="text"
                            v-model="searchQuery"
                            @input="onSearch"
                            ref="searchInput"
                            class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :placeholder="searchPlaceholder"
                        />
                    </div>
                </div>

                <!-- Options -->
                <div class="overflow-y-auto max-h-48">
                    <div
                        v-if="clearable"
                        @click="clearSelection"
                        class="flex items-center justify-between px-4 py-3 cursor-pointer text-gray-500 hover:bg-gray-50 border-b border-gray-100"
                    >
                        <span class="truncate italic">
                            {{ __("Not selected") }}
                        </span>
                        <i class="mdi mdi-close text-sm"></i>
                    </div>

                    <div
                        v-if="!filteredOptions.length && !loading"
                        class="px-4 py-3 text-sm text-gray-500 text-center"
                    >
                        <i class="mdi mdi-magnify text-gray-400 mr-1"></i>
                        {{ __("No results found") }}
                    </div>

                    <template v-if="$slots.option">
                        <div
                            v-for="(option, index) in filteredOptions"
                            :key="getOptionKey(option, index)"
                            @click="selectOption(option)"
                            class="cursor-pointer transition-colors duration-150"
                            :class="[
                                isSelected(option)
                                    ? 'bg-blue-50 text-blue-700'
                                    : 'hover:bg-gray-50 text-gray-900',
                                index < filteredOptions.length - 1
                                    ? 'border-b border-gray-100'
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

                    <template v-else>
                        <div
                            v-for="(option, index) in filteredOptions"
                            :key="getOptionKey(option, index)"
                            @click="selectOption(option)"
                            class="flex items-center justify-between px-4 py-3 cursor-pointer transition-colors duration-150"
                            :class="[
                                isSelected(option)
                                    ? 'bg-blue-50 text-blue-700'
                                    : 'hover:bg-gray-50 text-gray-900',
                                index < filteredOptions.length - 1
                                    ? 'border-b border-gray-100'
                                    : '',
                            ]"
                        >
                            <span class="truncate">{{ option[labelKey] }}</span>
                            <i
                                v-if="isSelected(option)"
                                class="mdi mdi-check text-blue-600 text-sm ml-2"
                            ></i>
                        </div>
                    </template>
                </div>

                <!-- Loading -->
                <div
                    v-if="loading"
                    class="px-4 py-3 text-sm text-gray-500 text-center border-t border-gray-100"
                >
                    <i class="mdi mdi-loading mdi-spin mr-1"></i>
                    {{ __("Loading...") }}
                </div>
            </div>
        </div>

        <!-- Error -->
        <v-error :error="error" class="mt-1" />

        <!-- Hidden select -->
        <select v-model="internalValue" class="hidden">
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
        error: Array,
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
            if (!this.options?.length) return [];

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
            } else {
                document.removeEventListener("click", this.handleClickOutside);
                document.removeEventListener("keydown", this.handleEscapeKey);
            }
        },
    },

    methods: {
        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen)
                this.$nextTick(() => this.$refs.searchInput?.focus());
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
