<template>
    <div
        class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 sm:p-4"
    >
        <!-- Header -->
        <div class="mb-4 sm:mb-6">
            <div class="flex items-start gap-3 mb-4">
                <div
                    class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center"
                >
                    <i class="fas fa-cubes text-white"></i>
                </div>
                <div>
                    <h2
                        class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-1"
                    >
                        {{ __("What is a Variant?") }}
                    </h2>
                    <p
                        class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm leading-relaxed"
                    >
                        {{
                            __(
                                "A variant represents a specific version of your product (e.g. 128GB, Red, Limited Edition). Each variant has its own stock, price, currency, and description. This allows you to manage availability and pricing more flexibly."
                            )
                        }}
                    </p>
                </div>
            </div>

            <!-- Quick Info Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                <div
                    class="flex items-start gap-2 p-2 bg-blue-50 dark:bg-blue-900/20 rounded"
                >
                    <div
                        class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center flex-shrink-0 mt-0.5"
                    >
                        <i class="fas fa-tag text-white text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-xs font-medium text-gray-900 dark:text-white"
                        >
                            {{ __("Name") }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{
                                __(
                                    "A short label for the variant (e.g. '128GB')"
                                )
                            }}
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-start gap-2 p-2 bg-green-50 dark:bg-green-900/20 rounded"
                >
                    <div
                        class="w-6 h-6 bg-green-600 rounded flex items-center justify-center flex-shrink-0 mt-0.5"
                    >
                        <i class="fas fa-cubes text-white text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-xs font-medium text-gray-900 dark:text-white"
                        >
                            {{ __("Stock") }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{ __("The available quantity for this variant") }}
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-start gap-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded"
                >
                    <div
                        class="w-6 h-6 bg-yellow-600 rounded flex items-center justify-center flex-shrink-0 mt-0.5"
                    >
                        <i class="fas fa-dollar-sign text-white text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-xs font-medium text-gray-900 dark:text-white"
                        >
                            {{ __("Price") }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{
                                __(
                                    "The cost of this variant, independent from the base product"
                                )
                            }}
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-start gap-2 p-2 bg-purple-50 dark:bg-purple-900/20 rounded"
                >
                    <div
                        class="w-6 h-6 bg-purple-600 rounded flex items-center justify-center flex-shrink-0 mt-0.5"
                    >
                        <i class="fas fa-globe text-white text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p
                            class="text-xs font-medium text-gray-900 dark:text-white"
                        >
                            {{ __("Currency") }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{
                                __("The currency in which the price is defined")
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4"
        >
            <div class="flex-1">
                <h3
                    class="text-sm sm:text-base font-bold text-gray-900 dark:text-white"
                >
                    {{ __("Manage Variants") }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">
                    {{ __("Add and configure different product variations") }}
                </p>
            </div>
            <button
                @click="addVariant"
                class="px-3 sm:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm flex items-center justify-center transition-colors w-full sm:w-auto"
            >
                <i class="fas fa-plus-circle mr-2"></i>
                <span>{{ __("Add Variant") }}</span>
            </button>
        </div>

        <!-- Variants List -->
        <div v-if="modelValue.length > 0" class="space-y-3">
            <div
                v-for="(variant, index) in modelValue"
                :key="index"
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 p-3 sm:p-4"
            >
                <!-- Variant Header -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 pb-3 border-b border-gray-200 dark:border-gray-600"
                >
                    <div class="flex items-center gap-2">
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
                                {{ __("Variant") }} #{{ index + 1 }}
                            </h4>
                            <p
                                v-if="variant.name"
                                class="text-blue-600 dark:text-blue-400 text-xs sm:text-sm truncate"
                            >
                                {{ variant.name }}
                            </p>
                            <p
                                v-else
                                class="text-gray-500 dark:text-gray-400 text-xs"
                            >
                                {{ __("Unnamed variant") }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="removeVariant(index)"
                        class="mt-2 sm:mt-0 px-3 py-1.5 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded text-sm transition-colors flex items-center justify-center w-full sm:w-auto"
                    >
                        <i class="fas fa-trash-alt mr-1.5"></i>
                        <span>{{ __("Remove") }}</span>
                    </button>
                </div>

                <!-- Variant Form -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Name -->
                    <div>
                        <v-input
                            :label="__('Variant Name')"
                            v-model="variant.name"
                            :required="true"
                            :placeholder="
                                __('e.g., 128GB, Red, Limited Edition')
                            "
                            size="small"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['name']"
                        />
                    </div>

                    <!-- Stock -->
                    <div>
                        <v-input
                            :label="__('Stock Quantity')"
                            v-model="variant.stock"
                            :required="true"
                            type="number"
                            :disabled="variant?.id ? true : false"
                            size="small"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['stock']"
                        />
                    </div>

                    <!-- Stock Adjustment (if editing) -->
                    <div v-if="variant?.id" class="sm:col-span-2">
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded p-3 mb-2"
                        >
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end"
                            >
                                <div>
                                    <v-input
                                        :label="__('Update Stock')"
                                        v-model="variant.adjustment"
                                        type="number"
                                        @input="calculateStock(variant)"
                                        size="small"
                                    />
                                </div>
                                <div>
                                    <v-switch
                                        v-model="variant.decrease"
                                        :label="__('Decrease')"
                                        @change="calculateStock(variant)"
                                        size="small"
                                    />
                                </div>
                            </div>
                            <div
                                class="text-xs text-gray-600 dark:text-gray-400 mt-2"
                            >
                                {{ __("Current stock") }}:
                                <span class="font-semibold">{{
                                    variant.stock_temp
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <v-input
                            :label="__('Price')"
                            v-model="variant.price"
                            :required="true"
                            type="money"
                            size="small"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['price']"
                        />
                    </div>

                    <!-- Currency -->
                    <div>
                        <v-select
                            v-model="variant.currency"
                            :options="currencies"
                            :label="__('Currency')"
                            value-key="code"
                            label-key="name"
                            :required="true"
                            size="small"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['currency']"
                        />
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <div
                            class="mb-2 text-xs text-gray-600 dark:text-gray-400"
                        >
                            {{
                                __(
                                    "Optional short description for this variant to clarify details and specifications"
                                )
                            }}
                        </div>
                        <v-textarea
                            :label="__('Description')"
                            v-model="variant.description"
                            :required="true"
                            :rows="3"
                            size="small"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['description']"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="modelValue.length === 0"
            class="text-center py-6 bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-600"
        >
            <div
                class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mx-auto mb-3"
            >
                <i class="fas fa-cubes text-blue-600 dark:text-blue-400"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                {{ __("No variants yet") }}
            </h3>
            <p
                class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mb-4 max-w-md mx-auto"
            >
                {{
                    __(
                        "Get started by adding your first product variant to manage different versions of your product."
                    )
                }}
            </p>
            <button
                @click="addVariant"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm flex items-center mx-auto transition-colors"
            >
                <i class="fas fa-plus-circle mr-2"></i>
                {{ __("Create First Variant") }}
            </button>
            <v-error
                v-for="(value, index) in error"
                :key="index"
                :error="value"
            />
        </div>
    </div>
</template>

<script>
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";
import VTextarea from "@/components/VTextarea.vue";
import VError from "@/components/VError.vue";
import VSwitch from "@/components/VSwitch.vue";

export default {
    components: {
        VInput,
        VSelect,
        VTextarea,
        VError,
        VSwitch,
    },
    props: {
        modelValue: {
            type: Array,
            default: () => [],
        },
        error: {
            type: Array,
            default: [],
        },
    },
    emits: ["update:modelValue"],
    data() {
        return {
            currencies: [],
        };
    },

    created() {
        this.getCurrencies();
    },

    methods: {
        calculateStock(variant) {
            if (!variant?.id) return;

            let stock = Number(variant.stock_temp);
            const adjustment = Number(variant.adjustment) || 0;

            if (variant.decrease) {
                stock -= adjustment;
            } else {
                stock += adjustment;
            }

            variant.stock = Math.max(0, stock);
        },

        addVariant() {
            this.modelValue.push({
                name: "",
                stock: 0,
                price: 0,
                currency: "",
                description: "",
            });
            this.$emit("update:modelValue", this.modelValue);
        },

        async removeVariant(index) {
            const item = this.modelValue[index];

            const result = await this.$swal({
                title: __("Are you sure?"),
                text: __(
                    "You are about to delete this variant. This action cannot be undone."
                ),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: __("Yes, delete it!"),
                cancelButtonText: __("Cancel"),
            });

            if (!result.isConfirmed) return;

            if (!item.links?.destroy) {
                this.modelValue.splice(index, 1);
                this.$emit("update:modelValue", this.modelValue);
                return;
            }

            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status === 200) {
                    this.modelValue.splice(index, 1);
                }
            } catch (e) {
                console.error("Error deleting variant:", e);
            } finally {
                this.$emit("update:modelValue", this.modelValue);
            }
        },

        async getCurrencies() {
            try {
                const res = await this.$server.get(
                    "/api/transaction/payments/currencies"
                );
                if (res.status == 200) {
                    this.currencies = res.data.data.map((item) => ({
                        code: item.code,
                        name: `${item.code} - ${item.name}`,
                    }));
                }
            } catch (e) {
                console.error("Error loading currencies:", e);
            }
        },
    },
};
</script>
