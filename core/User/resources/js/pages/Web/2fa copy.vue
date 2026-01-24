<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <v-account-layout>
        <div class="mb-8">
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
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        />
                    </svg>
                </div>
                <div>
                    <h1
                        class="text-xl font-semibold text-gray-900 dark:text-white"
                    >
                        {{ "Two-Factor Authentication" }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{
                            __(
                                "Secure your account with an extra layer of protection",
                            )
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Status & Actions -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Security Status Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
                >
                    <div class="flex items-center justify-between mb-6">
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
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h2
                                    class="text-lg font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ __("Security Status") }}
                                </h2>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        __(
                                            "Current two-factor authentication status",
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div
                            :class="[
                                'px-4 py-2 rounded-lg font-medium text-sm border',
                                user.m2fa
                                    ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800'
                                    : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800',
                            ]"
                        >
                            <span class="flex items-center space-x-1">
                                <svg
                                    v-if="user.m2fa"
                                    class="w-4 h-4"
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
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                                <span>{{
                                    user.m2fa ? __("Protected") : __("At Risk")
                                }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Status Message -->
                    <div
                        :class="[
                            'p-4 rounded-lg border',
                            user.m2fa
                                ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300'
                                : 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300',
                        ]"
                    >
                        <div class="flex items-center space-x-3">
                            <svg
                                v-if="user.m2fa"
                                class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0"
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
                            <svg
                                v-else
                                class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"
                                />
                            </svg>
                            <div>
                                <p class="font-medium text-sm">
                                    {{
                                        user.m2fa
                                            ? __(
                                                  "Your account is secured with 2FA",
                                              )
                                            : __(
                                                  "Enable 2FA to protect your account",
                                              )
                                    }}
                                </p>
                                <p class="text-xs opacity-80 mt-1">
                                    {{
                                        user.m2fa
                                            ? __(
                                                  "Unauthorized access attempts will be blocked",
                                              )
                                            : __(
                                                  "Your account is vulnerable to unauthorized access",
                                              )
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6"
                >
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-cyan-600 dark:text-cyan-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h2
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Email Verification") }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{
                                    __(
                                        "Verify your identity with a secure code",
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <!-- Code Input Section -->
                    <div class="space-y-4">
                        <div class="space-y-3">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Verification Code") }}
                            </label>
                            <div class="relative">
                                <input
                                    v-model="form.token"
                                    type="text"
                                    maxlength="6"
                                    placeholder="Enter 6-digit code"
                                    class="w-full pl-4 pr-32 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-400 transition-colors text-sm font-mono placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    :class="
                                        form.errors.token
                                            ? 'border-red-500 dark:border-red-400 focus:ring-red-500'
                                            : ''
                                    "
                                    @keyup.enter="activateFactor"
                                />
                                <button
                                    @click="requestCode"
                                    :disabled="sendingCode"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 flex items-center space-x-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-xs"
                                >
                                    <svg
                                        v-if="sendingCode"
                                        class="w-3 h-3 animate-spin"
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
                                    <svg
                                        v-else
                                        class="w-3 h-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 5l7 7-7 7M5 5l7 7-7 7"
                                        />
                                    </svg>
                                    <span>{{
                                        sendingCode ? "Sending..." : "Get Code"
                                    }}</span>
                                </button>
                            </div>
                            <v-error :error="form.errors.token" />
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{
                                    __(
                                        "Check your email for the 6-digit verification code",
                                    )
                                }}
                            </p>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-4">
                            <button
                                @click="activateFactor"
                                :class="[
                                    'w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm',
                                    user.m2fa
                                        ? 'bg-red-600 hover:bg-red-700 text-white'
                                        : 'bg-green-600 hover:bg-green-700 text-white',
                                ]"
                            >
                                <svg
                                    v-if="loading"
                                    class="w-4 h-4 animate-spin"
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
                                <svg
                                    v-else
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        v-if="user.m2fa"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"
                                    />
                                    <path
                                        v-else
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </svg>
                                <span>
                                    {{
                                        loading
                                            ? user.m2fa
                                                ? "Disabling..."
                                                : "Activating..."
                                            : user.m2fa
                                              ? "Disable 2FA"
                                              : "Activate 2FA"
                                    }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Features -->
            <div class="lg:col-span-1">
                <div class="space-y-4">
                    <!-- Security Features -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5"
                    >
                        <div class="flex items-center space-x-3 mb-4">
                            <div
                                class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-4 h-4 text-purple-600 dark:text-purple-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                            </div>
                            <h3
                                class="text-base font-semibold text-gray-900 dark:text-white"
                            >
                                {{ __("Coming Features") }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VAccountLayout from "@/components/VAccountLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import VError from "@/components/VError.vue";

const page = usePage();

const form = useForm({
    token: "",
});

const user = ref({});
const loading = ref(false);
const sendingCode = ref(false);

onMounted(() => {
    user.value = page.props.user;
});

const requestCode = () => {
    sendingCode.value = true;

    form.post(user.value.links.request_2fa_code, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            $notify.success(__("We have sent the token to your email"));
        },
        onFinish: () => {
            sendingCode.value = false;
            loading.value = false;
        },
    });
};

function activateFactor() {
    loading.value = true;

    form.post(user.value.links.f2a_activate, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            form.resetAndClearErrors();
            user.value.m2fa = !user.value.m2fa;
            if (user.value.m2fa) {
                $notify.success(
                    __("Two-factor authentication has been activated."),
                );
            } else {
                $notify.error(
                    __("Two-factor authentication has been deactivated."),
                );
            }
        },
        onFinish: () => {
            loading.value = false;
        },
    });
}
</script>
