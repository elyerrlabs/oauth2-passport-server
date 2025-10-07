<template>
    <div class="max-w-6xl mx-auto p-6">
        <!-- Component Description -->
        <div
            class="mb-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl shadow-sm"
        >
            <div class="flex items-start gap-4">
                <div
                    class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                >
                    <i class="fas fa-cubes text-blue-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        {{ __("What is a Variant?") }}
                    </h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        {{
                            __(
                                "A variant represents a specific version of your product (e.g. 128GB, Red, Limited Edition). Each variant has its own stock, price, currency, and description. This allows you to manage availability and pricing more flexibly."
                            )
                        }}
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div
                            class="flex items-center gap-3 p-3 bg-white rounded-lg border border-blue-100"
                        >
                            <div
                                class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center"
                            >
                                <i class="fas fa-tag text-white text-xs"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">
                                    {{ __("Name") }}
                                </h3>
                                <p class="text-gray-600 text-xs">
                                    {{
                                        __(
                                            "A short label for the variant (e.g. '128GB')"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 bg-white rounded-lg border border-green-100"
                        >
                            <div
                                class="w-8 h-8 bg-green-500 rounded flex items-center justify-center"
                            >
                                <i class="fas fa-cubes text-white text-xs"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">
                                    {{ __("Stock") }}
                                </h3>
                                <p class="text-gray-600 text-xs">
                                    {{
                                        __(
                                            "The available quantity for this variant"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 bg-white rounded-lg border border-yellow-100"
                        >
                            <div
                                class="w-8 h-8 bg-yellow-500 rounded flex items-center justify-center"
                            >
                                <i
                                    class="fas fa-dollar-sign text-white text-xs"
                                ></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">
                                    {{ __("Price") }}
                                </h3>
                                <p class="text-gray-600 text-xs">
                                    {{
                                        __(
                                            "The cost of this variant, independent from the base product"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 bg-white rounded-lg border border-purple-100"
                        >
                            <div
                                class="w-8 h-8 bg-purple-500 rounded flex items-center justify-center"
                            >
                                <i class="fas fa-globe text-white text-xs"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">
                                    {{ __("Currency") }}
                                </h3>
                                <p class="text-gray-600 text-xs">
                                    {{
                                        __(
                                            "The currency in which the price is defined"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg border border-indigo-100"
                    >
                        <div
                            class="w-8 h-8 bg-indigo-500 rounded flex items-center justify-center"
                        >
                            <i class="fas fa-align-left text-white text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">
                                {{ __("Description") }}
                            </h3>
                            <p class="text-gray-600 text-xs">
                                {{
                                    __(
                                        "Optional short description for this variant to clarify details and specifications"
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div
            class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6 p-6 bg-white rounded-2xl shadow-sm border border-gray-200"
        >
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900 mb-1">
                    {{ __("Manage Variants") }}
                </h1>
                <p class="text-gray-600 text-sm">
                    {{ __("Add and configure different product variations") }}
                </p>
            </div>
            <button
                @click="addVariant"
                class="flex items-center gap-2 px-5 py-2.5 cursor-pointer bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-md transition-all duration-300"
            >
                <i class="fas fa-plus-circle"></i>
                <span>{{ __("Add Variant") }}</span>
            </button>
        </div>

        <!-- Variants List -->
        <div class="space-y-4">
            <div
                v-for="(variant, index) in modelValue"
                :key="index"
                class="group bg-white rounded-xl border-2 border-gray-100 p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:border-blue-200"
            >
                <!-- Variant Header -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 pb-4 border-b border-gray-100"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center"
                        >
                            <span class="text-white font-bold">{{
                                index + 1
                            }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ __("Variant") }} #{{ index + 1 }}
                            </h3>
                            <p
                                v-if="variant.name"
                                class="text-blue-600 font-medium text-sm"
                            >
                                {{ variant.name }}
                            </p>
                            <p v-else class="text-gray-500 text-xs">
                                {{ __("Unnamed variant") }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="removeVariant(index)"
                        class="flex items-center gap-1.5 cursor-pointer px-3 py-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-all duration-200 border border-transparent hover:border-red-200 text-sm"
                    >
                        <i class="fas fa-trash-alt"></i>
                        <span class="font-medium">{{ __("Remove") }}</span>
                    </button>
                </div>

                <!-- Variant Form -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <v-input
                            :label="__('Variant Name')"
                            v-model="variant.name"
                            :required="true"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['name']"
                        />
                    </div>

                    <div>
                        <v-input
                            :label="__('Stock Quantity')"
                            v-model="variant.stock"
                            :required="true"
                            type="number"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['stock']"
                        />
                    </div>
                    <div>
                        <v-input
                            :label="__('Price')"
                            v-model="variant.price"
                            :required="true"
                            type="money"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['price']"
                        />
                    </div>
                    <div>
                        <v-select
                            v-model="variant.currency"
                            :options="currencies"
                            :label="__('Currency')"
                            value-key="code"
                            label-key="name"
                            :required="true"
                        />
                        <v-error
                            v-if="error[index]"
                            :error="error[index]['currency']"
                        />
                    </div>

                    <div class="lg:col-span-2">
                        <v-textarea
                            :label="__('Description')"
                            v-model="variant.description"
                            :required="true"
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
            class="text-center py-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300"
        >
            <div
                class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-4"
            >
                <i class="fas fa-cubes text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">
                {{ __("No variants yet") }}
            </h3>
            <p class="text-gray-600 mb-4 max-w-md mx-auto text-sm">
                {{
                    __(
                        "Get started by adding your first product variant to manage different versions of your product."
                    )
                }}
            </p>
            <button
                @click="addVariant"
                class="inline-flex items-center cursor-pointer gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow hover:shadow-md transition-all duration-300"
            >
                <i class="fas fa-plus-circle"></i>
                <span>{{ __("Create First Variant") }}</span>
            </button>
            <v-error v-for="(value, index) in error" :error="value" />
        </div>
    </div>
</template>

<script>
import VInput from "./VInput.vue";
import VSelect from "./VSelect.vue";
import VTextarea from "./VTextarea.vue";
import VError from "./VError.vue";

export default {
    components: {
        VInput,
        VSelect,
        VTextarea,
        VError,
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
            errors: {},
            currencies: [],
        };
    },

    watch: {
        calculateStock() {
            let stock = Number(this.form.stock);
            const adjustment = Number(this.update_stock) || 0;

            if (this.decrease) {
                stock -= adjustment;
            } else {
                stock += adjustment;
            }

            this.current_stock = Math.max(0, stock);
        },
    },

    created() {
        this.getCurrencies();
    },
    methods: {
        addVariant() {
            this.modelValue.push({
                name: "",
                stock: 0,
                price: 0,
                currency: "",
                description: "",
            });
            this.$emit("update:modelValue", this.modelValue);
            this.$notify.success(
                this.__(
                    "Nuw variant section has been added, please complete the information"
                )
            );
        },

        async removeVariant(index) {
            const item = this.modelValue[index];

            const result = await this.$swal({
                title: this.__("Are you sure?"),
                text: this.__(
                    "You are about to delete this variant. This action cannot be undone."
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
                    text: this.__("Variant has been deleted successfully."),
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
                        text: this.__("Variant has been deleted successfully."),
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
                        "There was an error deleting the variant. Please try again."
                    ),
                    icon: "error",
                    confirmButtonText: this.__("OK"),
                });
            } finally {
                this.$emit("update:modelValue", this.modelValue);
            }
        },

        async getCurrencies() {
            try {
                const res = await this.$server.get(
                    this.$page.props.routes.currencies
                );
                if (res.status == 200) {
                    this.currencies = res.data.data.map((item) => ({
                        code: item.code,
                        name: `${item.code} - ${item.name}`,
                    }));
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$notify.error(e.response.data.message);
                }
            }
        },
    },
};
</script>
