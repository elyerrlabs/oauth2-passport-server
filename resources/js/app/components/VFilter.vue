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
    <q-card class="q-pa-md shadow-2 rounded-borders">
        <!-- Header -->
        <q-card-section
            class="bg-primary text-white row items-center justify-between q-pa-sm q-gutter-sm rounded-borders"
        >
            <div class="row items-center">
                <q-btn
                    flat
                    dense
                    round
                    icon="mdi-tune"
                    @click="show = !show"
                    class="q-mr-sm"
                    :aria-label="__('Toggle Filters')"
                />
                <span class="text-h6">{{ __("Advanced Filters") }}</span>
            </div>

            <q-btn
                flat
                dense
                round
                icon="mdi-broom"
                @click="clean"
                :aria-label="__('Clear Filters')"
                color="white"
            />
        </q-card-section>

        <!-- Filters Section -->
        <q-slide-transition>
            <div v-show="show" class="q-pa-md bg-grey-1">
                <div class="row q-col-gutter-md q-pb-sm">
                    <!-- Search -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-input
                            v-model="inputValue"
                            outlined
                            dense
                            debounce="300"
                            :label="__('Search')"
                            :placeholder="__('Type to search...')"
                            clearable
                            @keyup="emitFilterChange"
                            @input="emitFilterChange"
                            :prefix="
                                selected_parameter
                                    ? selected_parameter + ':'
                                    : ''
                            "
                        >
                            <template v-slot:append>
                                <q-icon name="mdi-magnify" />
                            </template>
                        </q-input>
                    </div>

                    <!-- Search By -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-select
                            v-model="selected_parameter"
                            :options="params"
                            option-value="value"
                            option-label="key"
                            outlined
                            dense
                            :label="__('Search By')"
                            emit-value
                            map-options
                            clearable
                        />
                    </div>

                    <!-- Order By -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-select
                            v-model="order_by"
                            :options="params"
                            option-value="value"
                            option-label="key"
                            outlined
                            dense
                            :label="__('Order By')"
                            emit-value
                            map-options
                            clearable
                        />
                    </div>

                    <!-- Order Type -->
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <q-select
                            v-model="order_type"
                            :options="orderTypes"
                            outlined
                            dense
                            :label="__('Order Type')"
                            emit-value
                            map-options
                            clearable
                        />
                    </div>
                </div>
            </div>
        </q-slide-transition>
    </q-card>
</template>

<script>
export default {
    emits: ["change"],
    props: {
        params: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            selected_parameter:
                this.params.length > 0 ? this.params[0].value : "",
            inputValue: "",
            show: false,
            order_by: "",
            order_type: "",
            orderTypes: [
                { label: "Ascending", value: "asc" },
                { label: "Descending", value: "desc" },
            ],
        };
    },
    watch: {
        selected_parameter: "emitFilterChange",
        order_by: "emitFilterChange",
        order_type: "emitFilterChange",
    },
    methods: {
        clean() {
            this.inputValue = "";
            this.order_by = "";
            this.order_type = "";
            this.show = false;
            this.emitFilterChange();
        },
        emitFilterChange() {
            const filterObject = {};

            if (this.inputValue) {
                filterObject[this.selected_parameter] = this.inputValue;
            }

            if (this.order_by) {
                filterObject.order_by = this.order_by;
            }

            if (this.order_type) {
                filterObject.order_type = this.order_type;
            }

            this.$emit("change", filterObject);
        },
    },
};
</script>

<style scoped>
.rounded-borders {
    border-radius: 12px;
}
</style>
