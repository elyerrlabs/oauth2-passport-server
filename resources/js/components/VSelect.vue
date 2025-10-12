<template>
    <div class="w-full">
        <!-- Label -->
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }} <span class="text-red-500" v-if="required">*</span>
        </label>

        <!-- Main Select Container -->
        <div class="relative">
            <!-- Trigger Button -->
            <div
                @click="toggleDropdown"
                class="flex items-center justify-between w-full px-4 py-1 text-left border border-gray-300 rounded-lg shadow-sm cursor-pointer bg-white hover:border-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'ring-2 ring-blue-500 border-blue-500': isOpen }"
            >
                <span class="truncate py-1">
                    {{
                        selectedOption
                            ? selectedOption[labelKey]
                            : __(placeholder)
                    }}
                </span>
                <div class="flex items-center space-x-2">
                    <!-- Clear Button -->
                    <button
                        v-if="internalValue && clearable"
                        @click.stop="clearSelection"
                        class="p-1 text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <i class="fas fa-times text-sm"></i>
                    </button>
                    <!-- Dropdown Icon -->
                    <i
                        class="fas fa-chevron-down text-gray-400 transition-transform duration-200"
                        :class="{ 'transform rotate-180': isOpen }"
                    ></i>
                </div>
            </div>

            <!-- Dropdown Menu -->
            <div
                v-show="isOpen"
                class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-hidden"
            >
                <!-- Search Input -->
                <div class="p-2 border-b border-gray-100">
                    <div class="relative">
                        <i
                            class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                        ></i>
                        <input
                            type="text"
                            v-model="searchQuery"
                            @input="onSearch"
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :placeholder="searchPlaceholder"
                            ref="searchInput"
                        />
                    </div>
                </div>

                <!-- Options List -->
                <div class="overflow-y-auto max-h-48">
                    <!-- No Results -->
                    <div
                        v-if="filteredOptions.length === 0"
                        class="px-4 py-3 text-sm text-gray-500 text-center"
                    >
                        <i class="fas fa-search mr-2"></i>
                        {{ __("No results found") }}
                    </div>

                    <!-- Options -->
                    <div
                        v-for="(option, index) in filteredOptions"
                        :key="index"
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
                            class="fas fa-check text-blue-600 ml-2"
                        ></i>
                    </div>
                </div>

                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="px-4 py-3 text-sm text-gray-500 text-center border-t border-gray-100"
                >
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    {{ __("Loading...") }}
                </div>
            </div>
        </div>
        <v-error :error="error" />

        <!-- Hidden Native Select for Form Submission -->
        <select v-model="internalValue" class="hidden">
            <option value="" disabled>{{ __(placeholder) }}</option>
            <option
                v-for="(option, index) in options"
                :key="index"
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
    components: { VError },

    props: {
        modelValue: {
            type: [String, Number, Object, null],
            default: null,
        },
        options: {
            type: Array,
            required: true,
        },
        label: {
            type: String,
            default: "",
        },
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
        clearable: {
            type: Boolean,
            default: true,
        },
        loading: {
            type: Boolean,
            default: false,
        },
        required: {
            type: Boolean,
            default: false,
        },
        error: {
            type: Array,
            default: [],
        },

        returnObject: {
            type: Boolean,
            default: false,
        },
    },

    emits: ["update:modelValue", "change", "search"],

    data() {
        return {
            internalValue: this.modelValue,
            searchQuery: "",
            isOpen: false,
        };
    },

    computed: {
        selectedOption() {
            if (
                this.returnObject &&
                typeof this.modelValue === "object" &&
                this.modelValue !== null
            ) {
                return this.modelValue;
            }

            return this.options.find(
                (opt) => opt[this.valueKey] === this.internalValue
            );
        },

        filteredOptions() {
            if (!this.searchQuery) return this.options;
            const query = this.searchQuery.toLowerCase();
            return this.options.filter((option) =>
                option[this.labelKey].toLowerCase().includes(query)
            );
        },
    },

    watch: {
        modelValue(newVal) {
            this.internalValue = this.returnObject
                ? newVal?.[this.valueKey] ?? null
                : newVal;
        },

        options: {
            handler() {
                const exists = this.options.some(
                    (opt) => opt[this.valueKey] === this.internalValue
                );
                if (!exists) {
                    this.internalValue = null;
                    this.$emit("update:modelValue", null);
                }
            },
            deep: true,
        },
    },

    methods: {
        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => this.$refs.searchInput?.focus());
            }
        },

        selectOption(option) {
            const emittedValue = this.returnObject
                ? option
                : option[this.valueKey];

            this.internalValue = option[this.valueKey];
            this.$emit("update:modelValue", emittedValue);
            this.$emit("change", emittedValue);
            this.isOpen = false;
            this.searchQuery = "";
        },

        clearSelection() {
            this.internalValue = null;
            this.$emit("update:modelValue", null);
            this.$emit("change", null);
        },

        isSelected(option) {
            return option[this.valueKey] === this.internalValue;
        },

        onSearch() {
            this.$emit("search", this.searchQuery);
        },

        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.isOpen = false;
            }
        },
    },

    mounted() {
        document.addEventListener("click", this.handleClickOutside);
    },

    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
    },
};
</script>
