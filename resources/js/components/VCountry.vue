<template>
    <div>
        <q-select
            :model-value="modelValue"
            dense
            outlined
            use-input
            fill-input
            hide-selected
            emit-value
            map-options
            input-debounce="300"
            :options="filteredCountries"
            :label="__('Country')"
            :error="!!errors.country"
            @filter="filterCountries"
            @update:model-value="updateValue"
            color="primary"
            class="input-field"
        >
            <template v-slot:prepend>
                <q-icon name="mdi-earth" />
            </template>
            <template v-slot:error>
                <v-error :error="errors.country"></v-error>
            </template>
            <template v-slot:option="scope">
                <q-item v-bind="scope.itemProps">
                    <q-item-section avatar>
                        <span>{{ scope.opt.label.split(" ")[0] }}</span>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>{{
                            scope.opt.label.replace(/^.*? /, "")
                        }}</q-item-label>
                    </q-item-section>
                </q-item>
            </template>
        </q-select>
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
            countries: [],
            filteredCountries: [],
        };
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

            this.filteredCountries = this.countries.map((c) => ({
                label: `${c.emoji} ${c.name_en}`,
                value: c.name_en,
            }));
        },

        filterCountries(val, update) {
            if (!val) {
                update(() => {
                    this.filteredCountries = this.countries.map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
                });
                return;
            }

            const needle = val.toLowerCase();
            update(() => {
                this.filteredCountries = this.countries
                    .filter((c) => c.name_en.toLowerCase().includes(needle))
                    .map((c) => ({
                        label: `${c.emoji} ${c.name_en}`,
                        value: c.name_en,
                    }));
            });
        },

        updateValue(value) {
            this.$emit("update:modelValue", value);
        },
    },
};
</script>
