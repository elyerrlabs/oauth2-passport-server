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
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">
                Two-Factor Authentication
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Add an extra layer of security to your account
            </p>
        </div>

        <div
            class="rounded-2xl border m-auto p-6 bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700"
        >
            <div class="w-4xl">
                <!-- ========================= -->
                <!-- 2FA NOT ENABLED -->
                <!-- ========================= -->
                <div v-if="!isTwoFactorEnabled && !isSetupInProgress">
                    <h3
                        class="text-lg font-semibold mb-2 text-gray-900 dark:text-white"
                    >
                        Enable Two-Factor Authentication
                    </h3>

                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        Protect your account by requiring a one-time code at
                        login.
                    </p>

                    <button
                        @click="startTwoFactorSetup"
                        :disabled="loading"
                        class="w-full py-3 rounded-xl font-medium bg-blue-600 hover:bg-blue-700 text-white"
                    >
                        Start 2FA Setup
                    </button>
                </div>

                <!-- ========================= -->
                <!-- SETUP IN PROGRESS -->
                <!-- ========================= -->
                <div v-else-if="isSetupInProgress">
                    <h3
                        class="text-lg font-semibold mb-4 text-gray-900 dark:text-white"
                    >
                        Scan QR Code
                    </h3>

                    <div
                        v-if="qrCodeSvg"
                        class="bg-white p-4 rounded-xl inline-block mb-4"
                        v-html="qrCodeSvg"
                    />

                    <div class="mb-6">
                        <p
                            class="text-sm mb-1 text-gray-600 dark:text-gray-400"
                        >
                            Manual setup key
                        </p>
                        <code
                            class="font-mono text-lg text-blue-600 dark:text-blue-400"
                        >
                            {{ secretKey }}
                        </code>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-sm mb-2 text-gray-700 dark:text-gray-300"
                        >
                            Verification code
                        </label>
                        <input
                            v-model="verificationCode"
                            @input="formatVerificationCode"
                            maxlength="6"
                            placeholder="000000"
                            class="w-full text-center text-2xl tracking-widest py-3 rounded-xl border bg-white text-gray-900 border-gray-300 dark:bg-gray-900 dark:text-white dark:border-gray-700"
                        />
                    </div>

                    <button
                        @click="confirmTwoFactorSetup"
                        :disabled="verificationCode.length !== 6 || loading"
                        class="w-full py-3 rounded-xl font-medium bg-green-600 hover:bg-green-700 text-white"
                    >
                        Confirm & Enable
                    </button>
                </div>

                <!-- ========================= -->
                <!-- 2FA ENABLED -->
                <!-- ========================= -->
                <div v-else>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center bg-green-500/20"
                        >
                            <svg
                                class="w-6 h-6 text-green-500 dark:text-green-400"
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
                        </div>

                        <div>
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                Two-Factor Authentication Enabled
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Your account is protected.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 flex-wrap">
                        <button
                            @click="loadRecoveryCodes"
                            class="flex-1 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            View Recovery Codes
                        </button>

                        <button
                            @click="isDisableChallenge = true"
                            class="flex-1 py-3 rounded-xl border border-red-600 text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20"
                        >
                            Disable 2FA
                        </button>

                        <button
                            v-if="recoveryCodes.length"
                            @click="regenerateRecoveryCodes"
                            class="flex-1 py-3 rounded-xl border border-yellow-500 text-yellow-600 hover:bg-yellow-50 dark:border-yellow-600 dark:text-yellow-400 dark:hover:bg-yellow-900/20"
                        >
                            Regenerate Recovery Codes
                        </button>
                    </div>

                    <div
                        v-if="isDisableChallenge"
                        class="mt-6 p-4 rounded-xl border border-red-300 bg-red-50 dark:border-red-800 dark:bg-red-900/20"
                    >
                        <h4
                            class="font-semibold mb-2 text-red-700 dark:text-red-400"
                        >
                            Confirm Disable Two-Factor Authentication
                        </h4>

                        <p class="text-sm mb-4 text-red-600 dark:text-red-300">
                            Enter the 6-digit code from your authenticator app
                            to continue.
                        </p>

                        <input
                            v-model="disableCode"
                            @input="formatDisableCode"
                            maxlength="6"
                            placeholder="000000"
                            class="w-full mb-4 text-center text-2xl tracking-widest py-3 rounded-xl border bg-white text-gray-900 border-red-300 dark:bg-gray-900 dark:text-white dark:border-red-700"
                        />

                        <div class="flex gap-3">
                            <button
                                @click="confirmDisableTwoFactor"
                                :disabled="disableCode.length !== 6 || loading"
                                class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium"
                            >
                                Confirm Disable
                            </button>

                            <button
                                @click="isDisableChallenge = false"
                                class="flex-1 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Recovery Codes -->
                    <div v-if="recoveryCodes.length" class="mt-6">
                        <h4
                            class="font-semibold mb-2 text-gray-900 dark:text-white"
                        >
                            Recovery Codes
                        </h4>

                        <div class="grid grid-cols-2 gap-2">
                            <code
                                v-for="(code, i) in recoveryCodes"
                                :key="i"
                                class="p-2 rounded text-center font-mono bg-gray-100 text-blue-600 dark:bg-gray-900 dark:text-blue-400"
                            >
                                {{ code }}
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-account-layout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import VAccountLayout from "@/components/VAccountLayout.vue";

const page = usePage();
const user = ref({});

// UI state
const activeTab = ref("setup");
const loading = ref(false);

const isDisableChallenge = ref(false);
const disableCode = ref("");

// Derived state (SOURCE OF TRUTH)
const isTwoFactorEnabled = computed(() =>
    Boolean(user.value?.two_factor_enabled),
);

// Setup flow
const isSetupInProgress = ref(false);
const qrCodeSvg = ref("");
const secretKey = ref("");
const verificationCode = ref("");

// Recovery
const recoveryCodes = ref([]);

// Messages
const message = ref("");
const messageType = ref("info");

// --------------------------------
// Lifecycle
// --------------------------------
onMounted(() => {
    user.value = page.props.user;

    if (user.value.two_factor_enabled) {
        activeTab.value = "setup";

        loadRecoveryCodes();
    }
});

// --------------------------------
// Helpers
// --------------------------------
const formatVerificationCode = (e) => {
    verificationCode.value = e.target.value.replace(/\D/g, "").slice(0, 6);
};

const formatDisableCode = (e) => {
    disableCode.value = e.target.value.replace(/\D/g, "").slice(0, 6);
};

// --------------------------------
// 2FA Actions
// --------------------------------
const startTwoFactorSetup = async () => {
    loading.value = true;
    try {
        await $server.post("/auth/user/two-factor-authentication");

        const qr = await $server.get("/auth/user/two-factor-qr-code");
        const secret = await $server.get("/auth/user/two-factor-secret-key");

        qrCodeSvg.value = qr.data.svg ?? qr.data;
        secretKey.value = secret.data.secretKey ?? secret.data;

        isSetupInProgress.value = true;
        message.value = "Scan the QR code with your authenticator app";
        messageType.value = "info";
    } catch {
        message.value = "Failed to start 2FA setup";
        messageType.value = "error";
    } finally {
        loading.value = false;
    }
};

const confirmTwoFactorSetup = async () => {
    if (verificationCode.value.length !== 6) return;

    loading.value = true;
    try {
        await $server.post("/auth/user/confirmed-two-factor-authentication", {
            code: verificationCode.value,
        });

        user.value.two_factor_enabled = true;
        isSetupInProgress.value = false;

        await loadRecoveryCodes();

        message.value = "Two-factor authentication enabled";
        messageType.value = "success";
    } catch {
        message.value = "Invalid verification code";
        messageType.value = "error";
    } finally {
        loading.value = false;
    }
};

const confirmDisableTwoFactor = async () => {
    if (disableCode.value.length !== 6) return;

    loading.value = true;
    try {
        await $server.delete("/auth/user/two-factor-authentication", {
            data: {
                code: disableCode.value,
            },
        });

        user.value.two_factor_enabled = false;
        isDisableChallenge.value = false;
        disableCode.value = "";
        recoveryCodes.value = [];

        message.value = "Two-factor authentication disabled";
        messageType.value = "success";
    } catch {
        message.value = "Invalid authentication code";
        messageType.value = "error";
    } finally {
        loading.value = false;
    }
};

// --------------------------------
// Recovery
// --------------------------------
const loadRecoveryCodes = async () => {
    const res = await $server.get("/auth/user/two-factor-recovery-codes");
    recoveryCodes.value = Array.isArray(res.data)
        ? res.data
        : (res.data.recovery_codes ?? []);
};

const regenerateRecoveryCodes = async () => {
    if (
        !confirm("Regenerate recovery codes? Your old codes will stop working.")
    ) {
        return;
    }

    loading.value = true;
    try {
        await $server.post("/auth/user/two-factor-recovery-codes");

        await loadRecoveryCodes();

        message.value = "Recovery codes regenerated successfully";
        messageType.value = "success";
    } catch {
        message.value = "Failed to regenerate recovery codes";
        messageType.value = "error";
    } finally {
        loading.value = false;
    }
};
</script>
