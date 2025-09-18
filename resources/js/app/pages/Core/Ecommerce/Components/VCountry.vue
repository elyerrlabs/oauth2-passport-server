<template>
    <div class="w-full relative">
        <label class="block text-sm font-medium text-gray-700">
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
                @input="filterCountries"
                @blur="handleBlur"
                @focus="open = true"
                @click="open = true"
                @keydown.esc="open = false"
                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500"
                :class="{ 'border-red-500': errors?.country }"
                :placeholder="__('Select a country')"
            />
            <v-error :error="error" />
        </div>

        <!-- Dropdown -->
        <ul
            v-if="open && countries.length"
            class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto"
        >
            <li
                v-for="country in countries"
                :key="country.id"
                @click="selectCountry(country)"
                class="cursor-pointer px-3 py-2 hover:bg-gray-100 flex items-center space-x-2"
            >
                <span>{{ country.name_en.split(" ")[0] }}</span>
                <span>{{ country.name_en.replace(/^.*? /, "") }}</span>
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
            type: [Array, Object],
            default: () => ({}),
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
        };
    },

    mounted() {
        this.getCountries();
        window.addEventListener("keydown", this.handleEscape);
    },

    beforeUnmount() {
        window.removeEventListener("keydown", this.handleEscape);
    },

    methods: {
        handleBlur() {
            setTimeout(() => {
                this.open = false;
            }, 150);
        },

        handleEscape(e) {
            if (e.key === "Escape") {
                this.open = false;
            }
        },

        async getCountries() {
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
                }
            } catch (e) {
                this.$notify.error("Failed to load countries");
            }
        },

        selectCountry(country) {
            this.search = country.name_en;
            this.$emit("update:modelValue", country.name_en);
            this.open = false;
        },
    },
};
</script>
