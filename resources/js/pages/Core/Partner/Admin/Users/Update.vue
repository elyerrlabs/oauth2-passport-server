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
    <!-- Update Button -->
    <button
        @click="loadData(item)"
        class="group p-2.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-xl border border-blue-200 hover:border-blue-300 transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        :title="__('Update Commission Rate')"
    >
        <div class="flex items-center space-x-2">
            <div class="relative">
                <svg
                    class="w-4 h-4 transition-transform group-hover:scale-110"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                    />
                </svg>
            </div>
            <span class="text-xs font-medium hidden sm:inline">
                {{ __("Adjust") }}
            </span>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Update Commission Rate')"
        panel-class="w-full lg:max-w-lg"
    >
        <template #body>
            <!-- Content -->
            <div class="space-y-8">
                <!-- Current Commission Display -->
                <div
                    class="text-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100"
                >
                    <div
                        class="flex items-center justify-center space-x-2 mb-3"
                    >
                        <div
                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-4 h-4 text-blue-600"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-blue-700">
                            {{ __("Current Commission Rate") }}
                        </p>
                    </div>
                    <div
                        class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent"
                    >
                        {{ item.commission_rate }}%
                    </div>
                    <p class="text-sm text-blue-600/80 mt-2 font-medium">
                        {{ __("for") }}
                        <span class="font-semibold"
                            >{{ item.name }} {{ item.last_name }}</span
                        >
                    </p>
                </div>

                <!-- Commission Input Section -->
                <div class="space-y-6">
                    <!-- Input Field -->
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-900 mb-4"
                        >
                            {{ __("New Commission Rate") }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>

                        <div class="relative group">
                            <input
                                v-model="form.commission_rate"
                                type="number"
                                min="0"
                                max="100"
                                step="0.01"
                                @keyup.enter="update"
                                :class="[
                                    'w-full pl-12 pr-16 py-4 border-2 rounded-xl text-lg font-semibold transition-all duration-300 focus:ring-4 focus:ring-blue-500/20',
                                    errors.commission_rate
                                        ? 'border-red-300 bg-red-50 text-red-900 focus:border-red-500'
                                        : 'border-gray-200 bg-white text-gray-900 focus:border-blue-500 group-hover:border-blue-300',
                                ]"
                                placeholder="0.00"
                            />
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <svg
                                    class="w-6 h-6 transition-colors duration-300"
                                    :class="
                                        errors.commission_rate
                                            ? 'text-red-500'
                                            : 'text-blue-500 group-hover:text-blue-600'
                                    "
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                    />
                                </svg>
                            </div>
                            <div
                                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none"
                            >
                                <span
                                    class="text-lg font-semibold"
                                    :class="
                                        errors.commission_rate
                                            ? 'text-red-500'
                                            : 'text-gray-500'
                                    "
                                    >%</span
                                >
                            </div>
                        </div>

                        <p
                            class="text-xs text-gray-500 mt-3 flex items-center space-x-1"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <span>{{
                                __("Enter a value between 0 and 100")
                            }}</span>
                        </p>

                        <div
                            v-if="errors.commission_rate"
                            class="flex items-center space-x-2 mt-3 p-3 bg-red-50 border border-red-200 rounded-lg"
                        >
                            <svg
                                class="w-4 h-4 text-red-500 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <span class="text-red-700 text-sm font-medium">{{
                                errors.commission_rate[0]
                            }}</span>
                        </div>
                    </div>

                    <!-- Visual Slider -->
                    <div
                        class="space-y-4 bg-gray-50 rounded-xl p-6 border border-gray-200"
                    >
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-900">{{
                                __("Adjust with slider")
                            }}</span>
                            <span
                                class="text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent"
                            >
                                {{ sliderValue }}%
                            </span>
                        </div>
                        <input
                            type="range"
                            v-model="sliderValue"
                            min="0"
                            max="100"
                            step="0.5"
                            @input="updateFromSlider"
                            class="w-full h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full appearance-none cursor-pointer slider-thumb"
                        />
                        <div
                            class="flex justify-between text-sm font-medium text-gray-600"
                        >
                            <span>0%</span>
                            <span class="text-gray-400">|</span>
                            <span>25%</span>
                            <span class="text-gray-400">|</span>
                            <span>50%</span>
                            <span class="text-gray-400">|</span>
                            <span>75%</span>
                            <span class="text-gray-400">|</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-4 mt-8 pt-8 border-t border-gray-200"
            >
                <button
                    @click="dialog = false"
                    class="px-6 py-3 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-300"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="update"
                    :disabled="updating"
                    class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 focus:scale-105 flex items-center space-x-3 shadow-lg shadow-blue-500/25"
                >
                    <svg
                        v-if="updating"
                        class="w-5 h-5 animate-spin"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        class="w-5 h-5"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"
                        />
                    </svg>
                    <span class="font-bold">
                        {{
                            updating
                                ? __("Updating...")
                                : __("Update Commission")
                        }}
                    </span>
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
export default {
    components: {
        VModal,
    },
    emits: ["updated"],

    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            errors: {},
            form: {
                commission_rate: 0,
            },
            dialog: false,
            updating: false,
            sliderValue: 0,
        };
    },

    watch: {
        "form.commission_rate"(newValue) {
            if (newValue !== null && newValue !== undefined) {
                let numValue = parseFloat(newValue);
                if (isNaN(numValue)) {
                    this.form.commission_rate = 0;
                } else if (numValue > 100) {
                    this.form.commission_rate = 100;
                } else if (numValue < 0) {
                    this.form.commission_rate = 0;
                } else {
                    this.form.commission_rate =
                        Math.round(numValue * 100) / 100;
                }
                this.sliderValue = this.form.commission_rate;
            }
        },
    },

    methods: {
        /**
         * Load necessary data to update commission rate
         */
        async loadData(item) {
            this.form = {
                commission_rate: parseFloat(item.commission_rate) || 0,
            };
            this.sliderValue = this.form.commission_rate;
            this.errors = {};
            this.dialog = true;
        },

        /**
         * Update commission rate from slider
         */
        updateFromSlider(value) {
            this.form.commission_rate = parseFloat(value);
        },

        /**
         * Update the commission rate
         */
        async update() {
            // Validate input
            if (
                this.form.commission_rate === null ||
                this.form.commission_rate === undefined
            ) {
                this.errors = {
                    commission_rate: ["Commission rate is required"],
                };
                return;
            }

            const commissionRate = parseFloat(this.form.commission_rate);
            if (isNaN(commissionRate)) {
                this.errors = {
                    commission_rate: ["Please enter a valid number"],
                };
                return;
            }

            if (commissionRate < 0 || commissionRate > 100) {
                this.errors = {
                    commission_rate: [
                        "Commission rate must be between 0 and 100",
                    ],
                };
                return;
            }

            this.updating = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.item.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.errors = {};
                    this.$emit("updated", true);
                    this.dialog = false;
                    $notify.error(__("Commission rate updated successfully"));
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.updating = false;
            }
        },
    },
};
</script>

<style scoped>
/* Custom slider styles */
.slider-thumb {
    background: linear-gradient(to right, #3b82f6, #8b5cf6);
}

.slider-thumb::-webkit-slider-thumb {
    appearance: none;
    height: 24px;
    width: 24px;
    border-radius: 50%;
    background: white;
    border: 3px solid #3b82f6;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
    cursor: pointer;
    transition: all 0.2s ease;
}

.slider-thumb::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.6);
}

.slider-thumb::-moz-range-thumb {
    height: 24px;
    width: 24px;
    border-radius: 50%;
    background: white;
    border: 3px solid #3b82f6;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
    cursor: pointer;
    transition: all 0.2s ease;
}

.slider-thumb::-moz-range-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.6);
}
</style>
