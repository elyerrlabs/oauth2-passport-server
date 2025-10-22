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
        class="w-full md:w-80 bg-white rounded-2xl shadow-lg p-3 md:p-6 space-y-2"
    >
        <div class="flex justify-between md:justify-start">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __("Filters") }}
            </h2>
            <button
                class="py-1 px-2 cursor-pointer bg-blue-500 text-white hover:bg-blue-700 md:hidden rounded-full"
            >
                <span
                    @click="toggleFilter"
                    class="mdi mdi-filter-outline"
                ></span>
            </button>
        </div>

        <!-- Price Range Filter -->
        <div class="space-y-4 md:block" :class="{ hidden: !toggle }">
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 shadow-sm border border-gray-100"
            >
                <!-- Range Display -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-600">{{
                            __("Price Range")
                        }}</span>
                        <span class="text-lg font-bold text-blue-600">
                            {{ priceMin }} - {{ priceMax }}
                        </span>
                    </div>
                </div>

                <!-- Dual Range Slider -->
                <div class="relative mb-8 h-6">
                    <!-- Track -->
                    <div
                        class="absolute h-1.5 bg-gray-200 rounded-full w-full top-1/2 transform -translate-y-1/2"
                    ></div>

                    <!-- Active Range -->
                    <div
                        class="absolute h-1.5 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full top-1/2 transform -translate-y-1/2"
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
                            class="w-5 h-5 bg-white border-2 border-blue-500 rounded-full shadow-lg hover:scale-110 transition-transform"
                        >
                            <div
                                class="w-2 h-2 bg-blue-500 rounded-full mx-auto mt-1.5"
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
                            class="w-5 h-5 bg-white border-2 border-indigo-500 rounded-full shadow-lg hover:scale-110 transition-transform"
                        >
                            <div
                                class="w-2 h-2 bg-indigo-500 rounded-full mx-auto mt-1.5"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Price Inputs -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide"
                            >{{ __("Min Price") }}</label
                        >
                        <div class="relative">
                            <input
                                type="number"
                                v-model.number="priceMin"
                                :min="minPrice"
                                :max="priceMax"
                                class="w-full pl-7 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide"
                        >
                            {{ __("Max Price") }}
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                v-model.number="priceMax"
                                :min="priceMin"
                                :max="maxPrice"
                                class="w-full pl-7 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent bg-white"
                            />
                        </div>
                    </div>
                </div>

                <!-- Quick Select Buttons -->
                <div class="mt-4">
                    <label
                        class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide"
                        >{{ __("Quick Select") }}</label
                    >
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="range in quickRanges"
                            :key="range.label"
                            @click="setPriceRange(range.min, range.max)"
                            class="px-3 py-1.5 text-xs rounded-full border transition-colors"
                            :class="
                                isActiveRange(range.min, range.max)
                                    ? 'bg-blue-100 border-blue-300 text-blue-700 font-medium'
                                    : 'border-gray-200 text-gray-600 hover:border-blue-300 hover:text-blue-600'
                            "
                        >
                            {{ range.label }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static Filters Section -->
        <div class="space-y-4 md:block" :class="{ hidden: !toggle }">
            <div
                class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-5 shadow-sm border border-gray-100"
            >
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-700 text-lg">
                        {{ __("Product Status") }}
                    </h3>
                    <i class="fas fa-bolt text-purple-500 text-xl"></i>
                </div>

                <!-- Latest Products Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __("Latest Products") }}
                    </label>
                    <div class="flex items-center space-x-3">
                        <select
                            v-model="staticFilters.latest"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:border-transparent bg-white text-sm"
                        >
                            <option value="">
                                {{ __("Show all products") }}
                            </option>
                            <option value="1">{{ __("Last 1 day") }}</option>
                            <option value="3">{{ __("Last 3 days") }}</option>
                            <option value="7">{{ __("Last 7 days") }}</option>
                            <option value="15">{{ __("Last 15 days") }}</option>
                            <option value="30">{{ __("Last 30 days") }}</option>
                        </select>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __("Filter by product creation date") }}
                    </p>
                </div>

                <!-- Latest Sellers Filter -->
                <div class="mb-4">
                    <label
                        class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:border-purple-400 transition-colors cursor-pointer bg-white"
                    >
                        <input
                            type="checkbox"
                            v-model="staticFilters.latest_seller"
                            true-value="true"
                            false-value=""
                            class="text-purple-600 focus:ring-purple-500 rounded"
                        />
                        <div class="flex-1">
                            <span class="text-gray-700 font-medium text-sm">
                                {{ __("Best Sellers") }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __("Show only top selling products") }}
                            </p>
                        </div>
                        <i class="fas fa-crown text-yellow-500"></i>
                    </label>
                </div>

                <!-- Featured Products Filter -->
                <div class="mb-2">
                    <label
                        class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:border-purple-400 transition-colors cursor-pointer bg-white"
                    >
                        <input
                            type="checkbox"
                            v-model="staticFilters.featured"
                            true-value="true"
                            false-value=""
                            class="text-purple-600 focus:ring-purple-500 rounded"
                        />
                        <div class="flex-1">
                            <span class="text-gray-700 font-medium text-sm">
                                {{ __("Featured Products") }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ __("Show only featured products") }}
                            </p>
                        </div>
                        <i class="fas fa-star text-purple-500"></i>
                    </label>
                </div>
            </div>
        </div>

        <!-- Dynamic Filters from API -->
        <div
            v-for="filter in filters"
            :key="filter.slug"
            class="space-y-4 md:block"
            :class="{ hidden: !toggle }"
        >
            <div
                class="bg-gray-50 rounded-xl p-2 shadow-sm border border-gray-100"
            >
                <div class="flex justify-between">
                    <h3 class="font-medium text-gray-700 mb-2">
                        {{ filter.name }}
                    </h3>
                    <i class="mdi mdi-filter text-2xl text-blue-600" />
                </div>

                <!-- Checkbox Widget -->
                <div v-if="filter.widget === 'checkbox'" class="space-y-2">
                    <label
                        v-for="value in filter.values"
                        :key="value"
                        class="flex items-center space-x-3 p-2 rounded-lg border border-gray-200 hover:border-blue-400 transition-colors cursor-pointer"
                        :class="
                            selectedFilters[filter.slug]?.includes(value)
                                ? 'bg-blue-50 border-blue-300'
                                : 'bg-white'
                        "
                    >
                        <input
                            type="checkbox"
                            :value="value"
                            v-model="selectedFilters[filter.slug]"
                            class="text-blue-600 focus:ring-blue-500 rounded"
                            :true-value="filter.multiple ? [value] : value"
                            :false-value="filter.multiple ? [] : null"
                        />
                        <span class="text-gray-700 text-sm capitalize">
                            {{ value }}
                        </span>
                    </label>
                </div>

                <!-- Radio Widget -->
                <div v-else-if="filter.widget === 'radio'" class="space-y-2">
                    <label
                        v-for="value in filter.values"
                        :key="value"
                        class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:border-blue-400 transition-colors cursor-pointer"
                        :class="
                            selectedFilters[filter.slug] === value
                                ? 'bg-blue-50 border-blue-300'
                                : 'bg-white'
                        "
                    >
                        <input
                            type="radio"
                            :value="value"
                            v-model="selectedFilters[filter.slug]"
                            class="text-blue-600 focus:ring-blue-500"
                        />
                        <span class="text-gray-700 text-sm capitalize">
                            {{ value }}
                        </span>
                    </label>
                </div>

                <!-- Select Widget -->
                <div v-else-if="filter.widget === 'select'" class="space-y-2">
                    <select
                        v-model="selectedFilters[filter.slug]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white"
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
        <div class="gap-3 md:flex" :class="{ hidden: !toggle }">
            <button
                @click="applyAllFilters"
                class="flex-1 bg-gradient-to-r cursor-pointer from-blue-500 to-indigo-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all shadow-md font-medium transform hover:scale-105"
            >
                {{ __("Apply Filters") }}
            </button>
            <button
                @click="clearAllFilters"
                class="px-4 py-3 border cursor-pointer border-gray-300 text-gray-600 rounded-lg hover:border-gray-400 hover:text-gray-700 transition-colors font-medium"
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
                const res = await this.$server.get(
                    this.$page.props.api.ecommerce.filters
                );
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
