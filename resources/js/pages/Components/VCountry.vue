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
    <div class="w-full relative">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ __("Country") }}
            <span v-if="required" class="text-red-500"> * </span>
        </label>
        <div class="relative">
            <span
                class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"
            >
                🌍
            </span>
            <input
                type="text"
                v-model="search"
                @input="getCountries"
                @blur="handleBlur"
                @focus="open = true"
                @click="open = true"
                @keydown.esc="open = false"
                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                :class="{ 'border-red-500': error.length }"
                :placeholder="__('Select a country')"
            />
            <v-error :error="error" />
        </div>

        <!-- Dropdown -->
        <ul
            v-if="open && countries.length"
            class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto"
        >
            <li
                v-for="country in countries"
                :key="country.id"
                @click="selectCountry(country)"
                class="cursor-pointer px-4 py-3 hover:bg-blue-50 border-b border-gray-100 last:border-b-0 transition-colors flex items-center space-x-3"
            >
                <span class="text-lg">{{ country.emoji }}</span>
                <span class="text-gray-700">{{ country.name_en }}</span>
            </li>
        </ul>
    </div>
</template>

<script>
import VError from "./VError.vue";
export default {
    components: { VError },
    props: {
        modelValue: {
            type: String,
            default: "",
        },
        error: {
            type: [Array],
            default: [],
        },
        required: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            countries: [],
            search: "",
            open: false,
            selectedCountry: null,
        };
    },

    watch: {
        modelValue: {
            immediate: true,
            handler(newVal) {
                // Cuando el valor del prop cambia, actualizar el search
                if (newVal && newVal !== this.search) {
                    this.search = newVal;
                    // Opcional: cargar el país seleccionado si es necesario
                    this.loadSelectedCountry(newVal);
                }
            },
        },
    },

    mounted() {
        window.addEventListener("keydown", this.handleEscape);
        // Si hay un valor inicial, cargarlo en el search
        if (this.modelValue) {
            this.search = this.modelValue;
        }
    },

    beforeUnmount() {
        window.removeEventListener("keydown", this.handleEscape);
    },

    methods: {
        handleBlur() {
            setTimeout(() => {
                this.open = false;
            }, 200);
        },

        handleEscape(e) {
            if (e.key === "Escape") {
                this.open = false;
            }
        },

        async getCountries() {
            if (this.search.length < 2) {
                this.countries = [];
                return;
            }

            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: {
                        order_by: "name_en",
                        order_type: "asc",
                        name: this.search,
                    },
                });
                if (res.status === 200) {
                    this.countries = res.data;
                    this.open = true;
                }
            } catch (e) {
                console.error("Failed to load countries:", e);
                this.$notify.error("Failed to load countries");
            }
        },

        async loadSelectedCountry(countryName) {
            // Opcional: cargar información del país seleccionado
            if (countryName && !this.selectedCountry) {
                try {
                    const res = await this.$server.get(
                        "/api/public/countries",
                        {
                            params: {
                                name: countryName,
                                exact_match: true,
                            },
                        }
                    );
                    if (res.status === 200 && res.data.length > 0) {
                        this.selectedCountry = res.data[0];
                    }
                } catch (e) {
                    console.error("Failed to load selected country:", e);
                }
            }
        },

        selectCountry(country) {
            this.search = country.name_en;
            this.selectedCountry = country;
            this.$emit("update:modelValue", country.name_en);
            this.open = false;
            this.countries = [];
        },
    },
};
</script>
