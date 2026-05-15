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
    <v-main-layout>
        <div class="mx-auto max-w-5xl space-y-6 px-4 py-4 sm:px-6">
            <div
                class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800"
            >
                <div
                    class="bg-gradient-to-r from-blue-600 via-sky-600 to-cyan-600 px-6 py-8 text-white"
                >
                    <div
                        class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <div class="max-w-2xl">
                            <div
                                class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-sm"
                            >
                                <i class="mdi mdi-shield-key-outline text-2xl"></i>
                            </div>
                            <h1 class="text-2xl font-bold sm:text-3xl">
                                {{ __("Two-Factor Authentication") }}
                            </h1>
                            <p class="mt-2 text-sm text-blue-50 sm:text-base">
                                {{
                                    __(
                                        "Protect your account with an extra verification step when signing in.",
                                    )
                                }}
                            </p>
                        </div>

                        <div
                            class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:min-w-[320px]"
                        >
                            <div
                                class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"
                            >
                                <div class="text-xs uppercase tracking-[0.2em] text-blue-100">
                                    {{ __("Status") }}
                                </div>
                                <div class="mt-2 flex items-center gap-2 text-sm font-medium">
                                    <span
                                        class="h-2.5 w-2.5 rounded-full"
                                        :class="
                                            isTwoFactorEnabled
                                                ? 'bg-emerald-300'
                                                : 'bg-amber-300'
                                        "
                                    ></span>
                                    {{
                                        isTwoFactorEnabled
                                            ? __("Protected")
                                            : __("Not enabled")
                                    }}
                                </div>
                            </div>

                            <div
                                class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur-sm"
                            >
                                <div class="text-xs uppercase tracking-[0.2em] text-blue-100">
                                    {{ __("Recovery Codes") }}
                                </div>
                                <div class="mt-2 text-sm font-medium">
                                    {{ recoveryCodes.length || 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div
                        v-if="message"
                        class="mb-6 flex items-start gap-3 rounded-2xl border p-4 text-sm"
                        :class="messageClasses"
                    >
                        <i :class="messageIcon" class="mt-0.5 text-lg"></i>
                        <p>{{ message }}</p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)]">
                        <div
                            class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 dark:border-slate-700 dark:bg-slate-900/30"
                        >
                <!-- 2FA NOT ENABLED -->
                <div v-if="!isTwoFactorEnabled && !isSetupInProgress">
                    <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __("Enable Two-Factor Authentication") }}
                    </h3>

                    <p class="mb-6 text-gray-600 dark:text-gray-400">
                        {{
                            __(
                                "Protect your account by requiring a one-time code at login.",
                            )
                        }}
                    </p>

                    <v-button
                        @click="startTwoFactorSetup"
                        :disabled="loading"
                        :loading="loading"
                        :label="__('Start 2FA Setup')"
                        left-icon="mdi mdi-shield-plus-outline"
                        variant="primary"
                        full-width
                    >
                    </v-button>
                </div>

                <!-- SETUP IN PROGRESS -->
                <div v-else-if="isSetupInProgress">
                    <div class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                        {{ __("Scan QR Code") }}
                    </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{
                                __(
                                    "Use Google Authenticator, 1Password, Authy or another compatible app.",
                                )
                            }}
                        </p>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-[280px_minmax(0,1fr)]">
                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 text-center dark:border-slate-700 dark:bg-slate-800"
                        >
                            <div
                                v-if="qrCodeSvg"
                                class="inline-flex rounded-2xl bg-white p-4 shadow-sm"
                                v-html="qrCodeSvg"
                            />
                        </div>

                        <div class="space-y-5">
                            <div
                                class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800"
                            >
                                <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Manual setup key") }}
                                </p>
                                <code
                                    class="block break-all rounded-xl bg-slate-100 px-4 py-3 font-mono text-base text-blue-600 dark:bg-slate-900 dark:text-blue-400"
                                >
                                    {{ secretKey }}
                                </code>
                            </div>

                            <v-input
                                v-model="verificationCode"
                                :label="__('Verification code')"
                                :placeholder="__('000000')"
                                maxlength="6"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                :input-class="[
                                    'text-center text-2xl tracking-[0.35em]',
                                    verificationCode.length === 6
                                        ? 'border-green-500 dark:border-green-400'
                                        : '',
                                ]"
                                @input="formatVerificationCode"
                            />

                            <div class="flex flex-col gap-3 sm:flex-row">
                    <v-button
                        @click="confirmTwoFactorSetup"
                        :disabled="verificationCode.length !== 6 || loading"
                        :loading="loading"
                        :label="__('Confirm & Enable')"
                        left-icon="mdi mdi-check-decagram-outline"
                        variant="success"
                        full-width
                    >
                    </v-button>
                                <v-button
                                    @click="cancelSetup"
                                    :disabled="loading"
                                    :label="__('Cancel')"
                                    variant="light"
                                    full-width
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2FA ENABLED -->
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
                                {{ __("Two-Factor Authentication Enabled") }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Your account is protected.") }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 flex-wrap">
                        <v-button
                            @click="loadRecoveryCodes"
                            :label="__('View Recovery Codes')"
                            left-icon="mdi mdi-key-chain-variant"
                            variant="light"
                            class="flex-1"
                        >
                        </v-button>

                        <v-button
                            @click="isDisableChallenge = true"
                            :label="__('Disable 2FA')"
                            left-icon="mdi mdi-shield-off-outline"
                            variant="danger"
                            class="flex-1"
                        >
                        </v-button>

                        <v-button
                            v-if="recoveryCodes.length"
                            @click="regenerateRecoveryCodes"
                            :loading="loading"
                            :label="__('Regenerate Recovery Codes')"
                            left-icon="mdi mdi-refresh"
                            variant="warning"
                            class="flex-1"
                        >
                        </v-button>
                    </div>

                    <div
                        v-if="isDisableChallenge"
                        class="mt-6 p-4 rounded-xl border border-red-300 bg-red-50 dark:border-red-800 dark:bg-red-900/20"
                    >
                        <h4
                            class="font-semibold mb-2 text-red-700 dark:text-red-400"
                        >
                            {{
                                __("Confirm Disable Two-Factor Authentication")
                            }}
                        </h4>

                        <p class="text-sm mb-4 text-red-600 dark:text-red-300">
                            {{
                                __(
                                    "Enter the 6-digit code from your authenticator app to continue.",
                                )
                            }}
                        </p>

                        <v-input
                            v-model="disableCode"
                            :label="__('Authentication code')"
                            :placeholder="__('000000')"
                            maxlength="6"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            :input-class="[
                                'mb-4 text-center text-2xl tracking-[0.35em] border-red-300 dark:border-red-700',
                                disableCode.length === 6
                                    ? 'border-green-500 dark:border-green-400'
                                    : '',
                            ]"
                            @input="formatDisableCode"
                        />

                        <div class="flex gap-3">
                            <v-button
                                @click="confirmDisableTwoFactor"
                                :disabled="disableCode.length !== 6 || loading"
                                :loading="loading"
                                :label="__('Confirm Disable')"
                                left-icon="mdi mdi-alert-octagon-outline"
                                variant="danger"
                                full-width
                            >
                            </v-button>

                            <v-button
                                @click="isDisableChallenge = false"
                                :disabled="loading"
                                :label="__('Cancel')"
                                variant="light"
                                full-width
                            >
                            </v-button>
                        </div>
                    </div>

                    <!-- Recovery Codes -->
                    <div v-if="recoveryCodes.length" class="mt-6">
                        <h4
                            class="font-semibold mb-2 text-gray-900 dark:text-white"
                        >
                            {{ __("Recovery Codes") }}
                        </h4>

                        <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                            {{
                                __(
                                    "Store these codes somewhere safe. Each code can be used once if you lose access to your authenticator app.",
                                )
                            }}
                        </p>

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

                        <aside class="space-y-4">
                            <div
                                class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-800"
                            >
                                <h2 class="mb-3 text-base font-semibold text-gray-900 dark:text-white">
                                    {{ __("How it works") }}
                                </h2>
                                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                    <li class="flex gap-3">
                                        <span class="mt-0.5 h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span>{{ __("Scan the QR code with an authenticator app.") }}</span>
                                    </li>
                                    <li class="flex gap-3">
                                        <span class="mt-0.5 h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span>{{ __("Enter the 6-digit code generated by the app.") }}</span>
                                    </li>
                                    <li class="flex gap-3">
                                        <span class="mt-0.5 h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span>{{ __("Save your recovery codes in a safe place.") }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div
                                class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800 dark:bg-emerald-900/20"
                            >
                                <div class="mb-3 flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-500/15"
                                    >
                                        <i class="mdi mdi-lock-check-outline text-xl text-emerald-600 dark:text-emerald-400"></i>
                                    </div>
                                    <h2 class="text-base font-semibold text-emerald-800 dark:text-emerald-300">
                                        {{ __("Security Tips") }}
                                    </h2>
                                </div>

                                <ul class="space-y-3 text-sm text-emerald-700 dark:text-emerald-300">
                                    <li>{{ __("Do not share your recovery codes.") }}</li>
                                    <li>{{ __("Use a trusted authenticator app on a device you control.") }}</li>
                                    <li>{{ __("Regenerate recovery codes if you think they were exposed.") }}</li>
                                </ul>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import VMainLayout from "@/components/VMainLayout.vue";
import VButton from "@/components/VButton.vue";
import VInput from "@/components/VInput.vue";

const page = usePage();
const user = ref({});

// UI state
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
    console.log(page.props.user);
    
    if (user.value.two_factor_enabled) {
        loadRecoveryCodes();
    }
});

// --------------------------------
// Helpers
// --------------------------------
const formatVerificationCode = (value) => {
    verificationCode.value = String(value).replace(/\D/g, "").slice(0, 6);
};

const formatDisableCode = (value) => {
    disableCode.value = String(value).replace(/\D/g, "").slice(0, 6);
};

const cancelSetup = () => {
    isSetupInProgress.value = false;
    qrCodeSvg.value = "";
    secretKey.value = "";
    verificationCode.value = "";
    message.value = "";
};

const messageClasses = computed(() => {
    if (messageType.value === "success") {
        return "border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300";
    }

    if (messageType.value === "error") {
        return "border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300";
    }

    return "border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-300";
});

const messageIcon = computed(() => {
    if (messageType.value === "success") {
        return "mdi mdi-check-circle-outline";
    }

    if (messageType.value === "error") {
        return "mdi mdi-alert-circle-outline";
    }

    return "mdi mdi-information-outline";
});

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
        message.value = __("Scan the QR code with your authenticator app");
        messageType.value = __("info");
    } catch {
        message.value = __("Failed to start 2FA setup");
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

        message.value = __("Two-factor authentication enabled");
        messageType.value = "success";
    } catch {
        message.value = __("Invalid verification code");
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

        message.value = __("Two-factor authentication disabled");
        messageType.value = "success";
    } catch {
        message.value = __("Invalid authentication code");
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
        !confirm(
            __("Regenerate recovery codes? Your old codes will stop working."),
        )
    ) {
        return;
    }

    loading.value = true;
    try {
        await $server.post("/auth/user/two-factor-recovery-codes");

        await loadRecoveryCodes();

        message.value = __("Recovery codes regenerated successfully");
        messageType.value = "success";
    } catch {
        message.value = __("Failed to regenerate recovery codes");
        messageType.value = "error";
    } finally {
        loading.value = false;
    }
};
</script>
