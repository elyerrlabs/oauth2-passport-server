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
        <div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Compact Header -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">
                                {{ __("Update Password") }}
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ __("Secure your account") }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    <!-- Password Form Card -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="space-y-5">
                            <!-- Current Password -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ __("Current Password") }}
                                </label>
                                <div class="relative">
                                    <input
                                        :type="
                                            showCurrentPassword
                                                ? 'text'
                                                : 'password'
                                        "
                                        v-model="form.current_password"
                                        placeholder="Enter current password"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm"
                                        :class="{
                                            'border-red-500':
                                                errors.current_password,
                                        }"
                                    />
                                    <button
                                        @click="
                                            showCurrentPassword =
                                                !showCurrentPassword
                                        "
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg
                                            v-if="showCurrentPassword"
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m9.02 9.02l3.411 3.411"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </button>
                                </div>
                                <v-error :error="errors.current_password" />
                            </div>

                            <!-- New Password -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ __("New Password") }}
                                </label>
                                <div class="relative">
                                    <input
                                        :type="
                                            showNewPassword
                                                ? 'text'
                                                : 'password'
                                        "
                                        v-model="form.password"
                                        placeholder="Create new password"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm"
                                        :class="{
                                            'border-red-500': errors.password,
                                        }"
                                    />
                                    <button
                                        @click="
                                            showNewPassword = !showNewPassword
                                        "
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg
                                            v-if="showNewPassword"
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m9.02 9.02l3.411 3.411"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div v-if="form.password" class="mt-2">
                                    <div
                                        class="flex items-center justify-between text-xs text-gray-600 mb-1"
                                    >
                                        <span
                                            >{{
                                                __("Password strength")
                                            }}:</span
                                        >
                                        <span class="font-medium">{{
                                            passwordStrengthText
                                        }}</span>
                                    </div>
                                    <div
                                        class="w-full bg-gray-200 rounded-full h-1.5"
                                    >
                                        <div
                                            class="h-1.5 rounded-full transition-all duration-300"
                                            :class="passwordStrengthColor"
                                            :style="{
                                                width: `${
                                                    passwordStrength * 100
                                                }%`,
                                            }"
                                        ></div>
                                    </div>
                                </div>
                                <v-error :error="errors.password" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    {{ __("Confirm Password") }}
                                </label>
                                <div class="relative">
                                    <input
                                        :type="
                                            showConfirmPassword
                                                ? 'text'
                                                : 'password'
                                        "
                                        v-model="form.password_confirmation"
                                        placeholder="Confirm new password"
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm"
                                        :class="{
                                            'border-red-500':
                                                errors.password_confirmation,
                                        }"
                                    />
                                    <button
                                        @click="
                                            showConfirmPassword =
                                                !showConfirmPassword
                                        "
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                    >
                                        <svg
                                            v-if="showConfirmPassword"
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m9.02 9.02l3.411 3.411"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Password Match Indicator -->
                                <div
                                    v-if="
                                        form.password &&
                                        form.password_confirmation
                                    "
                                    class="mt-2 flex items-center"
                                >
                                    <svg
                                        class="w-3.5 h-3.5 mr-2"
                                        :class="
                                            passwordsMatch
                                                ? 'text-green-500'
                                                : 'text-red-500'
                                        "
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            v-if="passwordsMatch"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                        <path
                                            v-else
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                    <span
                                        class="text-xs"
                                        :class="
                                            passwordsMatch
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        {{
                                            passwordsMatch
                                                ? "Passwords match"
                                                : "Passwords do not match"
                                        }}
                                    </span>
                                </div>
                                <v-error
                                    :error="errors.password_confirmation"
                                />
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button
                                    @click="update"
                                    :disabled="loading"
                                    class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-blue-700 focus:ring-1 focus:ring-blue-500 focus:ring-offset-1 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-sm"
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
                                            ? "Updating..."
                                            : "Update Password"
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tips -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 h-full"
                    >
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3"
                            >
                                <svg
                                    class="w-4 h-4 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                            </div>
                            <h2 class="text-base font-semibold text-gray-900">
                                {{ __("Password Security Tips") }}
                            </h2>
                        </div>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-start">
                                <svg
                                    class="w-4 h-4 text-green-500 mr-3 mt-0.5 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span class="text-gray-600"
                                    >{{ __("Use at least 8 character") }}s</span
                                >
                            </li>
                            <li class="flex items-start">
                                <svg
                                    class="w-4 h-4 text-green-500 mr-3 mt-0.5 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span class="text-gray-600">{{
                                    __(
                                        "Include uppercase and lowercase letters"
                                    )
                                }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg
                                    class="w-4 h-4 text-green-500 mr-3 mt-0.5 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span class="text-gray-600">{{
                                    __("Add numbers and special characters")
                                }}</span>
                            </li>
                            <li class="flex items-start">
                                <svg
                                    class="w-4 h-4 text-green-500 mr-3 mt-0.5 flex-shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span class="text-gray-600"
                                    >{{
                                        __(
                                            "Avoid common words or personal information"
                                        )
                                    }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script>
import VAccountLayout from "../../../Components/VAccountLayout.vue";

export default {
    components: {
        VAccountLayout,
    },
    data() {
        return {
            form: {
                current_password: "",
                password: "",
                password_confirmation: "",
            },
            errors: {},
            loading: false,
            showCurrentPassword: false,
            showNewPassword: false,
            showConfirmPassword: false,
        };
    },

    computed: {
        passwordStrength() {
            if (!this.form.password) return 0;

            let strength = 0;
            const password = this.form.password;

            // Length check
            if (password.length >= 8) strength += 0.2;
            if (password.length >= 12) strength += 0.1;

            // Character variety checks
            if (/[A-Z]/.test(password)) strength += 0.2;
            if (/[a-z]/.test(password)) strength += 0.2;
            if (/[0-9]/.test(password)) strength += 0.2;
            if (/[^A-Za-z0-9]/.test(password)) strength += 0.2;

            return Math.min(strength, 1);
        },

        passwordStrengthColor() {
            if (this.passwordStrength < 0.4) return "bg-red-500";
            if (this.passwordStrength < 0.7) return "bg-yellow-500";
            return "bg-green-500";
        },

        passwordStrengthText() {
            if (this.passwordStrength < 0.4) return "Weak";
            if (this.passwordStrength < 0.7) return "Medium";
            return "Strong";
        },

        passwordsMatch() {
            return (
                this.form.password === this.form.password_confirmation &&
                this.form.password !== ""
            );
        },
    },

    methods: {
        async update() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.$page.props.user.links.change_password,
                    this.form
                );

                if (res.status === 200) {
                    this.form = {
                        current_password: "",
                        password: "",
                        password_confirmation: "",
                    };

                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                        timeout: 3000,
                        position: "top-right",
                        icon: "mdi-check-circle",
                        progress: true,
                    });
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
