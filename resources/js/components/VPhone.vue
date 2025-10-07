<template>
    <div class="row">
        <div class="col-4">
            <q-select
                :model-value="internalDialCode"
                dense
                outlined
                use-input
                fill-input
                hide-selected
                emit-value
                map-options
                input-debounce="300"
                :options="filteredDialCodes"
                :label="__('Dial Code')"
                :error="!!errors.dial_code"
                @filter="filterDialCodes"
                @update:model-value="updateDialCode"
                color="primary"
                class="input-field"
            >
                <template v-slot:prepend>
                    <q-icon name="mdi-phone" />
                </template>
                <template v-slot:error>
                    <v-error :error="errors.dial_code"></v-error>
                </template>
            </q-select>
        </div>
        <div class="col-8">
            <q-input
                filled
                dense
                :model-value="internalPhone"
                :placeholder="__('Enter your phone number')"
                maxlength="25"
                :error="!!errors.phone"
                @update:model-value="updatePhone"
            >
                <template v-slot:append>
                    <q-icon name="mdi-phone" color="grey-5" />
                </template>
            </q-input>
        </div>
        <div class="col-12">
            <v-error :error="errors.dial_code" />
            <v-error :error="errors.phone" />
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
        errors: {
            type: [Array, Object],
            default: () => ({}),
        },
    },

    data() {
        return {
            filteredDialCodes: [],
            countries: [],
            internalDialCode: "",
            internalPhone: "",
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

    created() {
        this.getCountries();
    },

    methods: {
        async getCountries() {
            try {
                const res = await this.$server.get("/api/public/countries", {
                    params: { order_by: "name_en", order_type: "asc" },
                });
                if (res.status === 200) this.countries = res.data;
            } catch (e) {
                this.$q.notify({
                    type: "negative",
                    message: "Failed to load countries",
                    position: "top",
                    icon: "mdi-alert-circle",
                    timeout: 3000,
                });
            }

            this.filteredDialCodes = this.countries.map((c) => ({
                label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                value: c.dial_code,
            }));
        },

        filterDialCodes(val, update) {
            if (!val) {
                update(() => {
                    this.filteredDialCodes = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
                });
                return;
            }

            const needle = val.toLowerCase();
            update(() => {
                this.filteredDialCodes = this.countries
                    .filter((c) =>
                        `${c.name_en} ${c.dial_code}`
                            .toLowerCase()
                            .includes(needle)
                    )
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en} (${c.dial_code})`,
                        value: c.dial_code,
                    }));
            });
        },

        parsePhoneValue(phoneValue) {
            if (phoneValue) {
                const parts = phoneValue.split(" ");
                if (parts.length >= 2 && parts[0].startsWith("+")) {
                    this.internalDialCode = parts[0];
                    this.internalPhone = parts.slice(1).join(" ");
                } else if (phoneValue.startsWith("+")) {
                    this.internalDialCode = phoneValue;
                    this.internalPhone = "";
                } else {
                    this.internalDialCode = "";
                    this.internalPhone = phoneValue;
                }
            } else {
                this.internalDialCode = "";
                this.internalPhone = "";
            }
        },

        updateDialCode(value) {
            this.internalDialCode = value;
            this.emitPhoneValue();
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
    },
};
</script>
