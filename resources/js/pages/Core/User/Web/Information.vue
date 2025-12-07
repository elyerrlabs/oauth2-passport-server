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
    <v-account-layout>
        <div
            class="min-h-screen bg-white dark:bg-gray-900 py-4 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-5xl mx-auto">
                <!-- Compact Header -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-blue-600 dark:text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-xl font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Account Information") }}
                            </h1>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-400 mt-1"
                            >
                                {{ __("Manage your personal details") }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Account Form Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700"
                >
                    <!-- Card Header -->
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-gray-700"
                    >
                        <h2
                            class="text-lg font-medium text-gray-900 dark:text-white"
                        >
                            {{ __("Personal Details") }}
                        </h2>
                    </div>

                    <!-- Form Content -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div class="space-y-2">
                                <v-input
                                    :label="__('First Name')"
                                    v-model="form.name"
                                    :placeholder="__('Enter your first name')"
                                    :error="form.errors.name"
                                    :required="true"
                                />
                            </div>

                            <!-- Last Name -->
                            <div class="space-y-2">
                                <v-input
                                    :label="__('Last Name')"
                                    v-model="form.last_name"
                                    :placeholder="__('Enter your last name')"
                                    :error="form.errors.last_name"
                                    :required="true"
                                />
                            </div>

                            <!-- Email    -->
                            <div class="space-y-2">
                                <v-input
                                    :label="__('Email')"
                                    v-model="form.email"
                                    placeholder="something@email.com"
                                    :error="form.errors.email"
                                    :required="true"
                                    disabled
                                />
                            </div>

                            <div class="space-y-2">
                                <v-select
                                    :label="__('Country')"
                                    v-model="form.country"
                                    :options="countries"
                                    :error="form.errors.country"
                                    :required="true"
                                    label-key="name_en"
                                    value-key="name_en"
                                    searchable
                                >
                                    <template #selected="{ option }">
                                        <span
                                            class="text-gray-700 dark:text-gray-200"
                                        >
                                            {{
                                                option
                                                    ? `${option.emoji} - ${option.name_en}`
                                                    : __("Choose your country")
                                            }}
                                        </span>
                                    </template>
                                    <template #option="{ option }">
                                        <span
                                            class="text-gray-700 p-2 dark:text-gray-200 block"
                                        >
                                            {{ option.emoji }}
                                            {{ option.name_en }}
                                        </span>
                                    </template>
                                </v-select>
                            </div>

                            <!-- City -->
                            <div class="space-y-2">
                                <v-input
                                    :label="__('City')"
                                    v-model="form.city"
                                    :placeholder="__('Enter your city')"
                                    :error="form.errors.city"
                                    :required="true"
                                />
                            </div>

                            <!-- Address -->
                            <div class="space-y-2">
                                <v-input
                                    :label="__('Address')"
                                    v-model="form.address"
                                    :placeholder="__('Enter your full address')"
                                    :error="form.errors.address"
                                    :required="true"
                                />
                            </div>

                            <!-- Dial Code -->
                            <div class="space-y-2">
                                <v-select
                                    :label="__('Dial code')"
                                    v-model="form.dial_code"
                                    :options="countries"
                                    :error="form.errors.dial_code"
                                    :required="true"
                                    label-key="name_en"
                                    value-key="dial_code"
                                    searchable
                                >
                                    <template #selected="{ option }">
                                        <span
                                            class="text-gray-700 dark:text-gray-200"
                                        >
                                            {{
                                                option
                                                    ? `${option.emoji} - ${option.name_en} ${option.dial_code}`
                                                    : __("Select dial code")
                                            }}
                                        </span>
                                    </template>
                                    <template #option="{ option }">
                                        <span
                                            class="text-gray-700 p-2 dark:text-gray-200 block"
                                        >
                                            {{ option.emoji }} -
                                            {{ option.name_en }} -
                                            {{ option.dial_code }}
                                        </span>
                                    </template>
                                </v-select>
                            </div>

                            <!-- Phone -->
                            <div class="space-y-2">
                                <v-input
                                    :label="__('Phone')"
                                    v-model="form.phone"
                                    :placeholder="__('Enter your phone number')"
                                    :error="form.errors.phone"
                                    :required="true"
                                />
                            </div>

                            <!-- Birthday -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                >
                                    {{ __("Birthday") }}
                                </label>
                                <div class="relative">
                                    <VueDatePicker
                                        v-model="form.birthday"
                                        :enable-time-picker="false"
                                        :max-date="new Date()"
                                        format="yyyy-MM-dd"
                                        model-type="format"
                                        :placeholder="
                                            __('Select your birthday')
                                        "
                                        auto-apply
                                        :dark="is_dark"
                                    />
                                    <div
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <div
                                    v-if="form.errors.birthday"
                                    class="text-sm text-red-600 dark:text-red-400 mt-1"
                                >
                                    {{ form.errors.birthday[0] }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <v-select
                                    :label="__('Language')"
                                    v-model="form.lang"
                                    :options="langs"
                                    :error="form.errors.lang"
                                />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div
                            class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700"
                        >
                            <button
                                @click="update"
                                :disabled="loading"
                                class="w-full sm:w-auto bg-blue-600 dark:bg-blue-500 text-white py-3 px-8 rounded-lg font-medium hover:bg-blue-700 dark:hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[140px]"
                            >
                                <svg
                                    v-if="loading"
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
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
                                {{
                                    loading
                                        ? __("Updating...")
                                        : __("Update Information")
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VAccountLayout from "@/components/VAccountLayout.vue";
import VInput from "@/components/VInput.vue";
import VSelect from "@/components/VSelect.vue";

const page = usePage();

const form = useForm({
    name: "",
    last_name: "",
    country: "",
    city: "",
    address: "",
    dial_code: "",
    phone: "",
    birthday: "",
    lang: "",
});

const is_dark = ref(false);

const countries = ref([]);
const loading = ref(false);

const langs = [
    {
        name: __("English"),
        id: "en",
    },
    {
        name: __("Spanish"),
        id: "es",
    },
];

onMounted(() => {
    Object.assign(form, page.props.user);

    getDialCode();
    isDark();
    window.addEventListener("theme-change", isDark);
});

const isDark = () => {
    is_dark.value = localStorage.getItem("theme") == "light" ? false : true;
};

//
const getDialCode = async () => {
    try {
        const res = await $server.get("/api/public/countries", {
            params: {
                order_by: "name_en",
                order_type: "asc",
            },
        });
        if (res.status === 200) {
            countries.value = res.data;
        }
    } catch (e) {
        if (e?.response?.data?.message) {
            $notify.error(e.response.data.message);
        }
    }
};

const update = async () => {
    loading.value = true;

    form.put(page.props.route, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("Information has been updated successfully"));
            window.location.reload();
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>
