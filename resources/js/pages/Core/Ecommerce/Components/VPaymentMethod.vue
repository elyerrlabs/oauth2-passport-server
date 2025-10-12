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
    <div class="w-full my-4">
        <div class="text-xl font-semibold text-gray-900 mb-6">
            {{ __("Choose your payment method") }}
        </div>

        <div
            class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
        >
            <div
                v-for="(method, key) in filteredMethods"
                :key="key"
                @click="selectMethod(key)"
                v-show="method.enable"
                :class="[
                    'relative flex flex-col items-center justify-center p-4 rounded-xl shadow transition-all cursor-pointer border bg-white',
                    selected_method === method.key
                        ? 'border-2 border-blue-600 bg-blue-50'
                        : 'border border-gray-200 hover:-translate-y-1 hover:shadow-lg',
                    !method.enable ? 'opacity-60 cursor-not-allowed' : '',
                ]"
            >
                <div class="flex items-center justify-center h-16">
                    <i
                        :class="[
                            'mdi',
                            method.icon,
                            'text-3xl',
                            method.enable ? 'text-blue-600' : 'text-gray-400',
                        ]"
                    ></i>
                </div>

                <div
                    class="mt-2 text-sm font-medium"
                    :class="
                        selected_method === method.key
                            ? 'text-blue-600'
                            : 'text-gray-900'
                    "
                >
                    {{ method.name }}
                </div>

                <div
                    v-if="!method.enable"
                    class="mt-1 text-xs italic text-gray-500"
                >
                    {{ __("Currently unavailable") }}
                </div>
                <div
                    v-if="selected_method === method.key"
                    class="absolute top-2 right-2 text-green-600 bg-white rounded-full"
                >
                    <i class="mdi mdi-check-circle text-lg"></i>
                </div>
            </div>
        </div>

        <div
            v-if="selected_method !== null && selected_method !== -1"
            class="mt-6 animate-fade-in"
        >
            <div
                class="flex items-center p-4 border rounded-xl bg-white shadow border-l-4 border-blue-600"
            >
                <i
                    :class="[
                        'mdi',
                        methods[selected_method]?.icon,
                        'text-xl text-blue-600 mr-3',
                    ]"
                ></i>
                <div class="text-base">
                    {{ __("Selected method") }}:
                    <span class="font-semibold">
                        {{ methods[selected_method]?.name }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: {
            type: [String, Number],
            default: null,
        },
    },
    emits: ["update:modelValue"],
    data() {
        return {
            selected_method: this.modelValue,
            methods: {},
        };
    },
    computed: {
        filteredMethods() {
            return Object.entries(this.methods)
                .filter(([key, method]) => method)
                .reduce((obj, [key, method]) => {
                    obj[key] = method;
                    return obj;
                }, {});
        },
    },
    watch: {
        modelValue(newVal) {
            this.selected_method = newVal;
        },
        selected_method(newVal) {
            this.$emit("update:modelValue", newVal);
        },
    },
    created() {
        this.getPaymentMethods();
    },
    methods: {
        selectMethod(key) {
            if (this.methods[key] && this.methods[key].enable) {
                this.selected_method = this.methods[key].key;
            }
        },
        async getPaymentMethods() {
            try {
                const res = await this.$server.get(
                    "/api/transaction/payments/methods"
                );
                if (res.status === 200) {
                    this.methods = res.data.data;

                    if (
                        this.selected_method &&
                        !this.methods[this.selected_method]
                    ) {
                        this.selected_method = null;
                    }
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                     $notify.error(__(e.response.data.message));
                }
            }
        },
    },
};
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in {
    animation: fade-in 0.4s ease;
}
</style>
