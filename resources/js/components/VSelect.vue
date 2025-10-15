<template>
    <div class="w-full">
        <!-- Label -->
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700 mb-1"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
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
                            :option="selectedOption"
                            :placeholder="__(placeholder)"
                        />
                    </template>
                    <template v-else>
                        {{
                            selectedOption
                                ? selectedOption[labelKey]
                                : __(placeholder)
                        }}
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
import VError from "./VError.vue";

export default {
    name: "VSelect",
    components: { VError },

    props: {
        modelValue: [String, Number, Object, null],
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
    },

    emits: ["update:modelValue", "change", "search"],

    data() {
        return {
            internalValue: null,
            isOpen: false,
            searchQuery: "",
        };
    },

    computed: {
        selectedOption() {
            if (!this.internalValue || !this.options?.length) return null;
            return (
                this.options.find(
                    (opt) => opt[this.valueKey] === this.internalValue
                ) || null
            );
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
    },

    watch: {
        modelValue: {
            immediate: true,
            handler(newVal) {
                if (this.returnObject && newVal && typeof newVal === "object") {
                    this.internalValue = newVal[this.valueKey];
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
            this.internalValue = value;

            const emitted = this.returnObject ? option : value;
            this.$emit("update:modelValue", emitted);
            this.$emit("change", emitted);

            this.isOpen = false;
            this.searchQuery = "";
        },

        clearSelection() {
            this.internalValue = null;
            this.$emit("update:modelValue", null);
            this.$emit("change", null);
            this.isOpen = false;
        },

        getOptionKey(option, index) {
            return option[this.valueKey] ?? `option-${index}`;
        },

        isSelected(option) {
            return option[this.valueKey] === this.internalValue;
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
