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
    <div>
        <!-- Update Button -->
        <button
            @click="loadData(item)"
            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
            :title="__('Update Commission Rate')"
        >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                />
            </svg>
        </button>

        <!-- Commission Update Dialog -->
        <div v-if="dialog" class="fixed inset-0 z-50 overflow-y-auto">
            <div
                class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
            >
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                    @click="dialog = false"
                ></div>

                <!-- Dialog panel -->
                <div
                    class="relative inline-block w-full max-w-md px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg
                                    class="w-6 h-6 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ __("Update Commission Rate") }}
                            </h3>
                        </div>
                        <button
                            @click="dialog = false"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="space-y-6">
                        <!-- Current Commission Display -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">
                                {{ __("Current Commission Rate") }}
                            </p>
                            <div class="text-3xl font-bold text-blue-600">
                                {{ item.commission_rate }}%
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                {{ __("for") }} {{ item.name }}
                                {{ item.last_name }}
                            </p>
                        </div>

                        <!-- Commission Input -->
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    {{ __("New Commission Rate") }}
                                </label>

                                <div class="relative">
                                    <input
                                        v-model="form.commission_rate"
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="0.01"
                                        @keyup.enter="update"
                                        :class="[
                                            'w-full pl-10 pr-12 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors',
                                            errors.commission_rate
                                                ? 'border-red-300 bg-red-50'
                                                : 'border-gray-300',
                                        ]"
                                        placeholder="0.00"
                                    />
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="w-5 h-5 text-gray-400"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                            />
                                        </svg>
                                    </div>
                                    <div
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                                    >
                                        <span class="text-gray-500">%</span>
                                    </div>
                                </div>

                                <p class="text-xs text-gray-500 mt-2">
                                    {{ __("Enter a value between 0 and 100") }}
                                </p>

                                <div
                                    v-if="errors.commission_rate"
                                    class="text-red-600 text-xs mt-2"
                                >
                                    {{ errors.commission_rate[0] }}
                                </div>
                            </div>

                            <!-- Visual Slider -->
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-700">{{
                                        __("Adjust with slider")
                                    }}</span>
                                    <span
                                        class="text-sm font-medium text-blue-600"
                                        >{{ sliderValue }}%</span
                                    >
                                </div>
                                <input
                                    type="range"
                                    v-model="sliderValue"
                                    min="0"
                                    max="100"
                                    step="0.5"
                                    @input="updateFromSlider"
                                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                                />
                                <div
                                    class="flex justify-between text-xs text-gray-500"
                                >
                                    <span>0%</span>
                                    <span>100%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200"
                    >
                        <button
                            @click="dialog = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            {{ __("Cancel") }}
                        </button>
                        <button
                            @click="update"
                            :disabled="updating"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center space-x-2"
                        >
                            <svg
                                v-if="updating"
                                class="w-4 h-4 animate-spin"
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
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"
                                />
                            </svg>
                            <span>{{
                                updating
                                    ? __("Updating...")
                                    : __("Update Commission")
                            }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
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
            // Ensure the value is within 0-100 range and has max 2 decimal places
            if (newValue !== null && newValue !== undefined) {
                let numValue = parseFloat(newValue);
                if (isNaN(numValue)) {
                    this.form.commission_rate = 0;
                } else if (numValue > 100) {
                    this.form.commission_rate = 100;
                } else if (numValue < 0) {
                    this.form.commission_rate = 0;
                } else {
                    // Limit to 2 decimal places
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

                    // Replace Quasar notification
                    console.log("Commission rate updated successfully");
                    // You can use your preferred notification system here
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    console.error("Error:", e.response.data.message);
                }
            } finally {
                this.updating = false;
            }
        },
    },
};
</script>

<style scoped>
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #2563eb;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #2563eb;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.slider::-webkit-slider-track {
    width: 100%;
    height: 8px;
    cursor: pointer;
    background: #e5e7eb;
    border-radius: 4px;
}

.slider::-moz-range-track {
    width: 100%;
    height: 8px;
    cursor: pointer;
    background: #e5e7eb;
    border-radius: 4px;
}
</style>
