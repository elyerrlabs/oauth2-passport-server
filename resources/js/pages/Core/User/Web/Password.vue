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
            class="min-h-screen bg-white dark:bg-gray-900 py-4 px-4 sm:px-6 lg:px-8 transition-colors duration-200"
        >
            <div class="max-w-4xl mx-auto">
                <!-- Compact Header -->
                <div class="mb-6">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center transition-colors duration-200"
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
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Update Password") }}
                            </h1>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-400 mt-1"
                            >
                                {{ __("Secure your account") }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                    <!-- Password Form Card -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-md"
                    >
                        <div class="space-y-5">
                            <!-- Current Password -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
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
                                        :placeholder="
                                            __('Enter current password')
                                        "
                                        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        :class="{
                                            'border-red-500 dark:border-red-400':
                                                form.errors.current_password,
                                            'border-green-500 dark:border-green-400':
                                                form.current_password &&
                                                !form.errors.current_password,
                                        }"
                                    />
                                    <button
                                        @click="
                                            showCurrentPassword =
                                                !showCurrentPassword
                                        "
                                        type="button"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                        :aria-label="
                                            showCurrentPassword
                                                ? 'Hide password'
                                                : 'Show password'
                                        "
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
                                <v-error
                                    :error="form.errors.current_password"
                                />
                            </div>

                            <!-- New Password -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
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
                                        :placeholder="__('Create new password')"
                                        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        :class="{
                                            'border-red-500 dark:border-red-400':
                                                form.errors.password,
                                            'border-green-500 dark:border-green-400':
                                                form.password &&
                                                !form.errors.password &&
                                                passwordStrength >= 0.7,
                                        }"
                                    />
                                    <button
                                        @click="
                                            showNewPassword = !showNewPassword
                                        "
                                        type="button"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                        :aria-label="
                                            showNewPassword
                                                ? 'Hide password'
                                                : 'Show password'
                                        "
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
                                <div v-if="form.password" class="mt-3">
                                    <div
                                        class="flex items-center justify-between text-xs mb-2"
                                        :class="passwordStrengthTextColor"
                                    >
                                        <span
                                            >{{
                                                __("Password strength")
                                            }}:</span
                                        >
                                        <span
                                            class="font-medium flex items-center"
                                        >
                                            {{ passwordStrengthText }}
                                            <svg
                                                v-if="passwordStrength >= 0.7"
                                                class="w-3 h-3 ml-1 text-green-500"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </span>
                                    </div>
                                    <div
                                        class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2"
                                    >
                                        <div
                                            class="h-2 rounded-full transition-all duration-500 ease-out"
                                            :class="passwordStrengthColor"
                                            :style="{
                                                width: `${
                                                    passwordStrength * 100
                                                }%`,
                                            }"
                                        ></div>
                                    </div>
                                    <div class="mt-2 grid grid-cols-4 gap-1">
                                        <div
                                            v-for="n in 4"
                                            :key="n"
                                            class="h-1 rounded-full transition-colors duration-300"
                                            :class="
                                                n <=
                                                Math.ceil(passwordStrength * 4)
                                                    ? passwordStrengthSegmentColor
                                                    : 'bg-gray-200 dark:bg-gray-600'
                                            "
                                        ></div>
                                    </div>
                                </div>
                                <v-error :error="form.errors.password" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
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
                                        :placeholder="
                                            __('Confirm new password')
                                        "
                                        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                        :class="{
                                            'border-red-500 dark:border-red-400':
                                                form.errors
                                                    .password_confirmation,
                                            'border-green-500 dark:border-green-400':
                                                form.password_confirmation &&
                                                !form.errors
                                                    .password_confirmation &&
                                                passwordsMatch,
                                        }"
                                    />
                                    <button
                                        @click="
                                            showConfirmPassword =
                                                !showConfirmPassword
                                        "
                                        type="button"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                        :aria-label="
                                            showConfirmPassword
                                                ? 'Hide password'
                                                : 'Show password'
                                        "
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
                                        class="w-3.5 h-3.5 mr-2 shrink-0"
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
                                        class="text-xs font-medium"
                                        :class="
                                            passwordsMatch
                                                ? 'text-green-600 dark:text-green-400'
                                                : 'text-red-600 dark:text-red-400'
                                        "
                                    >
                                        {{
                                            passwordsMatch
                                                ? __("Passwords match")
                                                : __("Passwords do not match")
                                        }}
                                    </span>
                                </div>
                                <v-error
                                    :error="form.errors.password_confirmation"
                                />
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button
                                    @click="update"
                                    :disabled="loading || !isFormValid"
                                    class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white py-2.5 px-4 rounded-lg font-medium focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-sm shadow-sm hover:shadow-md"
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
                                            : __("Update Password")
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tips -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 h-full transition-all duration-200 hover:shadow-md"
                    >
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mr-3 transition-colors duration-200"
                            >
                                <svg
                                    class="w-4 h-4 text-green-600 dark:text-green-400"
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
                            <h2
                                class="text-base font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Password Security Tips") }}
                            </h2>
                        </div>
                        <ul class="space-y-3 text-sm">
                            <li
                                v-for="(tip, index) in securityTips"
                                :key="index"
                                class="flex items-start group"
                            >
                                <svg
                                    class="w-4 h-4 text-green-500 dark:text-green-400 mr-3 mt-0.5 shrink-0 transition-transform duration-200 group-hover:scale-110"
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
                                <span
                                    class="text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors duration-200"
                                >
                                    {{ tip }}
                                </span>
                            </li>
                        </ul>

                        <!-- Additional Security Info -->
                        <div
                            class="mt-6 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800"
                        >
                            <div class="flex items-start">
                                <svg
                                    class="w-4 h-4 text-blue-500 dark:text-blue-400 mt-0.5 mr-2 shrink-0"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <p
                                    class="text-xs text-blue-700 dark:text-blue-300"
                                >
                                    {{
                                        __(
                                            "Your password is encrypted and stored securely. We recommend updating it every 3-6 months."
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import { ref, computed } from "vue";
import VAccountLayout from "@/components/VAccountLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VError from "@/components/VError.vue";

const page = usePage();

// Reactive state
const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const loading = ref(false);
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

// Computed: consejos de seguridad
const securityTips = computed(() => [
    __("Use at least 12 characters"),
    __("Include uppercase and lowercase letters"),
    __("Add numbers and special characters"),
    __("Avoid common words or personal information"),
    __("Don't reuse passwords from other sites"),
    __("Consider using a passphrase"),
]);

// Fuerza de contraseña
const passwordStrength = computed(() => {
    if (!form.password) return 0;

    let strength = 0;
    const password = form.password;

    // Longitud
    if (password.length >= 8) strength += 0.15;
    if (password.length >= 12) strength += 0.15;
    if (password.length >= 16) strength += 0.1;

    // Variedad de caracteres
    if (/[A-Z]/.test(password)) strength += 0.15;
    if (/[a-z]/.test(password)) strength += 0.15;
    if (/[0-9]/.test(password)) strength += 0.15;
    if (/[^A-Za-z0-9]/.test(password)) strength += 0.15;

    // Bonus
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 0.1;
    if (/[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password))
        strength += 0.05;

    return Math.min(strength, 1);
});

const passwordStrengthColor = computed(() => {
    if (passwordStrength.value < 0.4) return "bg-red-500";
    if (passwordStrength.value < 0.7) return "bg-yellow-500";
    return "bg-green-500";
});

const passwordStrengthSegmentColor = computed(() => {
    if (passwordStrength.value < 0.4) return "bg-red-500";
    if (passwordStrength.value < 0.7) return "bg-yellow-500";
    return "bg-green-500";
});

const passwordStrengthTextColor = computed(() => {
    if (passwordStrength.value < 0.4) return "text-red-600 dark:text-red-400";
    if (passwordStrength.value < 0.7)
        return "text-yellow-600 dark:text-yellow-400";
    return "text-green-600 dark:text-green-400";
});

const passwordStrengthText = computed(() => {
    if (passwordStrength.value < 0.4) return __("Weak");
    if (passwordStrength.value < 0.7) return __("Medium");
    return __("Strong");
});

const passwordsMatch = computed(
    () => form.password === form.password_confirmation && form.password !== ""
);

const isFormValid = computed(
    () =>
        form.current_password &&
        form.password &&
        form.password_confirmation &&
        passwordsMatch.value &&
        passwordStrength.value >= 0.4
);

async function update() {
    loading.value = true;

    form.put(page.props.user.links.change_password, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.resetAndClearErrors();
            $notify.success(__("Password has been updated successfully"));
        },
        onFinish: () => {
            loading.value = false;
        },
    });
}
</script>
