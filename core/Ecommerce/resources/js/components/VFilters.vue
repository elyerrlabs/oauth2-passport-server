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
    <aside
        class="w-full lg:w-64 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 space-y-3"
    >
        <div class="flex justify-between items-center">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white">
                {{ __("Filters") }}
            </h2>
            <button
                class="p-1.5 cursor-pointer bg-blue-500 dark:bg-blue-600 text-white hover:bg-blue-600 dark:hover:bg-blue-700 lg:hidden rounded transition-colors"
                @click="toggleFilter"
            >
                <i class="fas fa-filter text-xs"></i>
            </button>
        </div>

        <!-- Price Range Filter -->
        <div class="space-y-3 lg:block" :class="{ hidden: !toggle }">
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600"
            >
                <div class="flex justify-between items-center mb-3">
                    <span
                        class="text-sm font-medium text-gray-600 dark:text-gray-300"
                    >
                        {{ __("Price Range") }}
                    </span>
                    <span
                        class="text-sm font-bold text-blue-600 dark:text-blue-400"
                    >
                        {{ priceMin }} - {{ priceMax }}
                    </span>
                </div>

                <!-- Dual Range Slider -->
                <div class="relative mb-4 h-5">
                    <!-- Track -->
                    <div
                        class="absolute h-1 bg-gray-300 dark:bg-gray-600 rounded-full w-full top-1/2 transform -translate-y-1/2"
                    ></div>

                    <!-- Active Range -->
                    <div
                        class="absolute h-1 bg-blue-500 dark:bg-blue-400 rounded-full top-1/2 transform -translate-y-1/2"
                        :style="{
                            left:
                                ((priceMin - minPrice) /
                                    (maxPrice - minPrice)) *
                                    100 +
                                '%',
                            width:
                                ((priceMax - priceMin) /
                                    (maxPrice - minPrice)) *
                                    100 +
                                '%',
                        }"
                    ></div>

                    <!-- Min Thumb -->
                    <div
                        class="absolute top-1/2 transform -translate-y-1/2 cursor-pointer"
                        :style="{
                            left:
                                ((priceMin - minPrice) /
                                    (maxPrice - minPrice)) *
                                    100 +
                                '%',
                        }"
                        @mousedown="startDragging('min')"
                    >
                        <div
                            class="w-4 h-4 bg-white dark:bg-gray-800 border-2 border-blue-500 dark:border-blue-400 rounded-full shadow-md hover:scale-110 transition-transform"
                        >
                            <div
                                class="w-1.5 h-1.5 bg-blue-500 dark:bg-blue-400 rounded-full mx-auto mt-1"
                            ></div>
                        </div>
                    </div>

                    <!-- Max Thumb -->
                    <div
                        class="absolute top-1/2 transform -translate-y-1/2 cursor-pointer"
                        :style="{
                            left:
                                ((priceMax - minPrice) /
                                    (maxPrice - minPrice)) *
                                    100 +
                                '%',
                        }"
                        @mousedown="startDragging('max')"
                    >
                        <div
                            class="w-4 h-4 bg-white dark:bg-gray-800 border-2 border-blue-500 dark:border-blue-400 rounded-full shadow-md hover:scale-110 transition-transform"
                        >
                            <div
                                class="w-1.5 h-1.5 bg-blue-500 dark:bg-blue-400 rounded-full mx-auto mt-1"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Price Inputs -->
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label
                            class="block text-xs text-gray-500 dark:text-gray-400 mb-1"
                        >
                            {{ __("Min") }}
                        </label>
                        <input
                            type="number"
                            v-model.number="priceMin"
                            :min="minPrice"
                            :max="priceMax"
                            class="w-full px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-600 text-gray-900 dark:text-white"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-xs text-gray-500 dark:text-gray-400 mb-1"
                        >
                            {{ __("Max") }}
                        </label>
                        <input
                            type="number"
                            v-model.number="priceMax"
                            :min="priceMin"
                            :max="maxPrice"
                            class="w-full px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-600 text-gray-900 dark:text-white"
                        />
                    </div>
                </div>

                <!-- Quick Select Buttons -->
                <div class="mt-3">
                    <div class="flex flex-wrap gap-1">
                        <button
                            v-for="range in quickRanges"
                            :key="range.label"
                            @click="setPriceRange(range.min, range.max)"
                            class="px-2 py-1 text-xs rounded border transition-colors"
                            :class="
                                isActiveRange(range.min, range.max)
                                    ? 'bg-blue-100 dark:bg-blue-900 border-blue-300 dark:border-blue-600 text-blue-700 dark:text-blue-300 font-medium'
                                    : 'border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:border-blue-300 dark:hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 bg-white dark:bg-gray-600'
                            "
                        >
                            {{ range.label }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static Filters Section -->
        <div class="space-y-3 lg:block" :class="{ hidden: !toggle }">
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600"
            >
                <div class="flex items-center justify-between mb-3">
                    <h3
                        class="font-medium text-gray-700 dark:text-gray-200 text-sm"
                    >
                        {{ __("Product Status") }}
                    </h3>
                    <i
                        class="fas fa-bolt text-blue-500 dark:text-blue-400 text-sm"
                    ></i>
                </div>

                <!-- Latest Products Filter -->
                <div class="mb-3">
                    <label
                        class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"
                    >
                        {{ __("Latest Products") }}
                    </label>
                    <select
                        v-model="staticFilters.latest"
                        class="w-full px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-600 text-gray-900 dark:text-white"
                    >
                        <option value="">{{ __("Show all") }}</option>
                        <option value="1">{{ __("Last 1 day") }}</option>
                        <option value="3">{{ __("Last 3 days") }}</option>
                        <option value="7">{{ __("Last 7 days") }}</option>
                        <option value="15">{{ __("Last 15 days") }}</option>
                        <option value="30">{{ __("Last 30 days") }}</option>
                    </select>
                </div>

                <!-- Latest Sellers Filter -->
                <div class="mb-3">
                    <label
                        class="flex items-center space-x-2 p-2 rounded border border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer bg-white dark:bg-gray-600"
                    >
                        <input
                            type="checkbox"
                            v-model="staticFilters.latest_seller"
                            true-value="true"
                            false-value=""
                            class="text-blue-600 dark:text-blue-500 focus:ring-blue-500 rounded text-xs"
                        />
                        <div class="flex-1">
                            <span
                                class="text-gray-700 dark:text-gray-300 text-xs font-medium"
                            >
                                {{ __("Best Sellers") }}
                            </span>
                        </div>
                        <i
                            class="fas fa-crown text-yellow-500 dark:text-yellow-400 text-xs"
                        ></i>
                    </label>
                </div>

                <!-- Featured Products Filter -->
                <div>
                    <label
                        class="flex items-center space-x-2 p-2 rounded border border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer bg-white dark:bg-gray-600"
                    >
                        <input
                            type="checkbox"
                            v-model="staticFilters.featured"
                            true-value="true"
                            false-value=""
                            class="text-blue-600 dark:text-blue-500 focus:ring-blue-500 rounded text-xs"
                        />
                        <div class="flex-1">
                            <span
                                class="text-gray-700 dark:text-gray-300 text-xs font-medium"
                            >
                                {{ __("Featured") }}
                            </span>
                        </div>
                        <i
                            class="fas fa-star text-blue-500 dark:text-blue-400 text-xs"
                        ></i>
                    </label>
                </div>
            </div>
        </div>

        <!-- Dynamic Filters from API -->
        <div
            v-for="filter in filters"
            :key="filter.slug"
            class="space-y-3 lg:block"
            :class="{ hidden: !toggle }"
        >
            <div
                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600"
            >
                <div class="flex justify-between items-center mb-2">
                    <h3
                        class="font-medium text-gray-700 dark:text-gray-200 text-sm"
                    >
                        {{ filter.name }}
                    </h3>
                    <i
                        class="fas fa-filter text-blue-500 dark:text-blue-400 text-xs"
                    ></i>
                </div>

                <!-- Checkbox Widget -->
                <div v-if="filter.widget === 'checkbox'" class="space-y-1">
                    <label
                        v-for="value in filter.values"
                        :key="value"
                        class="flex items-center space-x-2 p-1.5 rounded border border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer text-xs"
                        :class="
                            selectedFilters[filter.slug]?.includes(value)
                                ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-300 dark:border-blue-500'
                                : 'bg-white dark:bg-gray-600'
                        "
                    >
                        <input
                            type="checkbox"
                            :value="value"
                            v-model="selectedFilters[filter.slug]"
                            class="text-blue-600 dark:text-blue-500 focus:ring-blue-500 rounded text-xs"
                            :true-value="filter.multiple ? [value] : value"
                            :false-value="filter.multiple ? [] : null"
                        />
                        <span
                            class="text-gray-700 dark:text-gray-300 capitalize"
                        >
                            {{ value }}
                        </span>
                    </label>
                </div>

                <!-- Radio Widget -->
                <div v-else-if="filter.widget === 'radio'" class="space-y-1">
                    <label
                        v-for="value in filter.values"
                        :key="value"
                        class="flex items-center space-x-2 p-1.5 rounded border border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer text-xs"
                        :class="
                            selectedFilters[filter.slug] === value
                                ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-300 dark:border-blue-500'
                                : 'bg-white dark:bg-gray-600'
                        "
                    >
                        <input
                            type="radio"
                            :value="value"
                            v-model="selectedFilters[filter.slug]"
                            class="text-blue-600 dark:text-blue-500 focus:ring-blue-500 text-xs"
                        />
                        <span
                            class="text-gray-700 dark:text-gray-300 capitalize"
                        >
                            {{ value }}
                        </span>
                    </label>
                </div>

                <!-- Select Widget -->
                <div v-else-if="filter.widget === 'select'" class="space-y-1">
                    <select
                        v-model="selectedFilters[filter.slug]"
                        class="w-full px-2 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-600 text-gray-900 dark:text-white"
                    >
                        <option value="">
                            {{ __("Select") }} {{ filter.name }}
                        </option>
                        <option
                            v-for="value in filter.values"
                            :key="value"
                            :value="value"
                            class="capitalize"
                        >
                            {{ value }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="gap-2 lg:flex" :class="{ hidden: !toggle }">
            <button
                @click="applyAllFilters"
                class="flex-1 bg-blue-500 dark:bg-blue-600 text-white py-2 rounded hover:bg-blue-600 dark:hover:bg-blue-700 transition-colors shadow-sm font-medium text-xs"
            >
                {{ __("Apply Filters") }}
            </button>
            <button
                @click="clearAllFilters"
                class="px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded hover:border-gray-400 dark:hover:border-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors font-medium bg-white dark:bg-gray-700 text-xs"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
    </aside>
</template>

<script>
export default {
    data() {
        return {
            minPrice: 0,
            maxPrice: 10000,
            priceMin: 0,
            priceMax: 5000,
            dragging: null,
            quickRanges: [
                { label: `${__("Under")} 100`, min: 0, max: 100 },
                { label: "100-500", min: 100, max: 500 },
                { label: "500-1K", min: 500, max: 1000 },
                { label: "1K-5K", min: 1000, max: 5000 },
                { label: "5K+", min: 5000, max: 10000 },
            ],
            filters: [],
            selectedFilters: {},
            staticFilters: {
                latest: "",
                latest_seller: "",
                featured: "",
            },
            toggle: false,
        };
    },

    created() {
        this.getFilters();
    },

    methods: {
        toggleFilter() {
            this.toggle = !this.toggle;
        },

        startDragging(type) {
            this.dragging = type;
            document.addEventListener("mousemove", this.handleDrag);
            document.addEventListener("mouseup", this.stopDragging);
            document.addEventListener("touchmove", this.handleDrag);
            document.addEventListener("touchend", this.stopDragging);
        },

        handleDrag(e) {
            if (!this.dragging) return;

            const slider = this.$el.querySelector(".relative");
            if (!slider) return;

            const rect = slider.getBoundingClientRect();
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const position = Math.max(
                0,
                Math.min(1, (clientX - rect.left) / rect.width)
            );
            const value = Math.round(
                this.minPrice + position * (this.maxPrice - this.minPrice)
            );

            if (this.dragging === "min") {
                this.priceMin = Math.min(value, this.priceMax);
            } else {
                this.priceMax = Math.max(value, this.priceMin);
            }
        },

        stopDragging() {
            this.dragging = null;
            document.removeEventListener("mousemove", this.handleDrag);
            document.removeEventListener("mouseup", this.stopDragging);
            document.removeEventListener("touchmove", this.handleDrag);
            document.removeEventListener("touchend", this.stopDragging);
        },

        setPriceRange(min, max) {
            this.priceMin = min;
            this.priceMax = max;
        },

        isActiveRange(min, max) {
            return this.priceMin === min && this.priceMax === max;
        },

        async getFilters() {
            try {
                const res = await this.$server.get("/api/ecommerce/filters");
                if (res.status == 200) {
                    this.filters = res.data.data;
                }
            } catch (e) {}
        },

        applyAllFilters() {
            const attrsEntries = Object.entries(this.selectedFilters).filter(
                ([, v]) =>
                    v !== null &&
                    v !== undefined &&
                    v !== "" &&
                    (!Array.isArray(v) || v.length > 0)
            );

            const attrsStr = attrsEntries
                .map(([k, v]) => {
                    if (Array.isArray(v)) {
                        return `${k}=${v.join(",")}`;
                    }
                    return `${k}=${v}`;
                })
                .join(",");

            const filters = {
                price: `${this.priceMin},${this.priceMax}`,
            };

            // Add static filters
            Object.entries(this.staticFilters).forEach(([key, value]) => {
                if (value !== "" && value !== null && value !== undefined) {
                    filters[key] = value;
                }
            });

            if (attrsStr) {
                filters.attrs = attrsStr;
            }

            this.$emit("changed", filters);
        },

        clearAllFilters() {
            this.priceMin = this.minPrice;
            this.priceMax = this.maxPrice;

            // Clear all dynamic filters
            Object.keys(this.selectedFilters).forEach((key) => {
                if (Array.isArray(this.selectedFilters[key])) {
                    this.selectedFilters[key] = [];
                } else {
                    this.selectedFilters[key] = null;
                }
            });

            // Clear static filters
            this.staticFilters = {
                latest: "",
                latest_seller: "",
                featured: "",
            };

            this.applyAllFilters();
        },
    },

    beforeUnmount() {
        this.stopDragging();
    },

    watch: {
        priceMin(newVal) {
            if (newVal > this.priceMax) {
                this.priceMin = this.priceMax;
            }
            if (newVal < this.minPrice) {
                this.priceMin = this.minPrice;
            }
        },
        priceMax(newVal) {
            if (newVal < this.priceMin) {
                this.priceMax = this.priceMin;
            }
            if (newVal > this.maxPrice) {
                this.priceMax = this.maxPrice;
            }
        },
    },
};
</script>
