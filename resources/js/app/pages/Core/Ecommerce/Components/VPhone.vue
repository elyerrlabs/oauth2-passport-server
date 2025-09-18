<template>
    <div class="grid grid-cols-12 gap-4">
        <label class="block col-span-12 text-sm font-medium text-gray-700">
            {{ label }}
            <span class="text-red-500" v-if="required"> * </span>
        </label>
        <div class="col-span-4 relative">
            <div class="relative">
                <span
                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"
                >
                    📞
                </span>
                <input
                    type="text"
                    v-model="search"
                    @input="getCountries"
                    @focus="open = true"
                    @click="open = true"
                    @keydown.esc="open = false"
                    @blur="handleBlur"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500"
                    :class="{ 'border-red-500': errors?.phone }"
                    placeholder="+51"
                />
            </div>

            <!-- Dropdown -->
            <ul
                v-if="open && countries.length"
                class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto"
            >
                <li
                    v-for="code in countries"
                    :key="code.id"
                    @click="selectDialCode(code)"
                    class="cursor-pointer px-3 py-2 hover:bg-gray-100 flex justify-between"
                >
                    <span>{{ code.emoji }} {{ code.name_en }}</span>
                </li>
            </ul>
        </div>

        <div class="col-span-8">
            <div class="relative">
                <input
                    type="text"
                    :value="internalPhone"
                    @input="updatePhone($event.target.value)"
                    maxlength="25"
                    class="w-full pr-10 pl-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:border-blue-500"
                    :class="{ 'border-red-500': errors?.phone }"
                    :placeholder="__('Enter your phone number')"
                />
                <span
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400"
                >
                    📱
                </span>
                <v-error :error="error" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: {
            type: String,
            default: "",
        },
        error: {
            type: [Array, Object],
            default: () => ({}),
        },
        label: {
            type: String,
            default: "Phone",
        },
        required: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            countries: [],
            internalDialCode: "",
            internalPhone: "",
            search: "",
            open: false,
        };
    },

    watch: {
        modelValue: {
            immediate: true,
            handler(newValue) {
                this.parsePhoneValue(newValue);
            },
        },
    },

    mounted() {
        this.getCountries();
    },

    methods: {
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
                this.$swal.error("Failed to load countries");
            }
        },

        selectDialCode(code) {
            this.internalDialCode = code.dial_code;
            this.search = code.name_en;
            this.open = false;
            this.emitPhoneValue();
        },

        parsePhoneValue(phoneValue) {
            if (phoneValue) {
                const parts = phoneValue.split(" ");
                if (parts.length >= 2 && parts[0].startsWith("+")) {
                    this.internalDialCode = parts[0];
                    this.search = parts[0];
                    this.internalPhone = parts.slice(1).join(" ");
                } else if (phoneValue.startsWith("+")) {
                    this.internalDialCode = phoneValue;
                    this.search = phoneValue;
                    this.internalPhone = "";
                } else {
                    this.internalDialCode = "";
                    this.search = "";
                    this.internalPhone = phoneValue;
                }
            } else {
                this.internalDialCode = "";
                this.search = "";
                this.internalPhone = "";
            }
        },

        updatePhone(value) {
            this.internalPhone = value;
            this.emitPhoneValue();
        },

        emitPhoneValue() {
            let fullPhone = "";

            if (this.internalDialCode && this.internalPhone) {
                fullPhone = `${this.internalDialCode} ${this.internalPhone}`;
            } else if (this.internalDialCode) {
                fullPhone = `${this.internalDialCode}`;
            } else if (this.internalPhone) {
                fullPhone = this.internalPhone;
            }

            this.$emit("update:modelValue", fullPhone);
        },

        handleBlur() {
            setTimeout(() => {
                this.open = false;
            }, 150);
        },
    },
};
</script>
