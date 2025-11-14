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
    <v-partner-layout>
        <div class="referral-container p-4">
            <!-- Header Section -->
            <div class="flex items-center mb-6">
                <div
                    class="p-3 bg-blue-600 dark:bg-blue-500 text-white rounded-lg mr-4 shadow-lg"
                >
                    <svg
                        class="w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M17 7h-4v2h4c1.65 0 3 1.35 3 3s-1.35 3-3 3h-4v2h4c2.76 0 5-2.24 5-5s-2.24-5-5-5zm-6 8H7c-1.65 0-3-1.35-3-3s1.35-3 3-3h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-2zm-3-4h8v2H8z"
                        />
                    </svg>
                </div>
                <div>
                    <h1
                        class="text-2xl font-bold text-gray-900 dark:text-white"
                    >
                        {{ __("Referral System") }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                        {{ __("Generate and share your referral links") }}
                    </p>
                </div>
            </div>

            <!-- Main Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300"
            >
                <!-- Card Header -->
                <div
                    class="bg-blue-600 dark:bg-blue-700 text-white px-6 py-4 rounded-t-lg"
                >
                    <h2 class="text-lg font-bold">
                        {{ __("Generate Referral Link") }}
                    </h2>
                    <p class="text-blue-100 dark:text-blue-200 text-sm mt-1">
                        {{ __("Share your link and earn commissions") }}
                    </p>
                </div>

                <!-- Commission Info -->
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-600"
                >
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <svg
                                class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"
                                />
                            </svg>
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                {{ __("Your Commission Rate") }}
                            </span>
                        </div>
                        <span
                            class="bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded-full border border-blue-200 dark:border-blue-700"
                        >
                            {{ partner?.commission_rate || 0 }}%
                        </span>
                    </div>
                    <div
                        class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2"
                    >
                        <div
                            class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-300"
                            :style="{
                                width: `${partner?.commission_rate || 0}%`,
                            }"
                        ></div>
                    </div>
                </div>

                <!-- Action Section -->
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-600"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                    >
                        <p
                            class="text-sm text-gray-600 dark:text-gray-400 flex-1"
                        >
                            {{
                                __(
                                    "Generate a unique referral link to share with your network"
                                )
                            }}
                        </p>
                    </div>
                </div>

                <!-- Generated Link Section -->
                <div class="px-6 py-4">
                    <h3
                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3"
                    >
                        {{ __("Your Referral Link") }}
                    </h3>

                    <div v-if="partner?.referral_links" class="space-y-3">
                        <div
                            class="flex border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white dark:bg-gray-700 transition-colors duration-300"
                        >
                            <div
                                class="flex items-center px-3 bg-gray-50 dark:bg-gray-600 border-r border-gray-300 dark:border-gray-500"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600 dark:text-blue-400"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"
                                    />
                                </svg>
                            </div>
                            <input
                                readonly
                                :value="partner.referral_links"
                                class="flex-1 px-3 py-3 text-sm bg-transparent outline-none truncate text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                            />
                            <div
                                class="flex border-l border-gray-300 dark:border-gray-500"
                            >
                                <button
                                    @click="
                                        copyToClipboard(partner.referral_links)
                                    "
                                    class="p-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200"
                                    :title="__('Copy to clipboard')"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    @click="shareLink(partner.referral_links)"
                                    class="p-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200"
                                    :title="__('Share link')"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50 transition-colors duration-300"
                    >
                        <svg
                            class="w-12 h-12 text-gray-300 dark:text-gray-500 mx-auto mb-3"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"
                            />
                        </svg>
                        <p
                            class="text-gray-500 dark:text-gray-400 text-sm mb-1"
                        >
                            {{ __("No referral link generated yet") }}
                        </p>
                        <p class="text-gray-400 dark:text-gray-500 text-xs">
                            {{
                                __(
                                    'Click "Generate New Link" to create your first referral link'
                                )
                            }}
                        </p>
                    </div>
                </div>

                <!-- Success Notification -->
                <transition
                    enter-active-class="transform ease-out duration-300 transition"
                    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                    leave-active-class="transition ease-in duration-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="copied"
                        class="mx-6 mb-4 p-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg flex items-center"
                    >
                        <svg
                            class="w-5 h-5 text-green-500 dark:text-green-400 mr-2"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                            />
                        </svg>
                        <span
                            class="text-green-800 dark:text-green-200 text-sm flex-1"
                        >
                            {{ __("Link successfully copied to clipboard!") }}
                        </span>
                        <button
                            @click="copied = false"
                            class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 transition-colors"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
                                />
                            </svg>
                        </button>
                    </div>
                </transition>
            </div>

            <!-- Additional Information -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mt-6 p-6 transition-colors duration-300"
            >
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white mb-6"
                >
                    {{ __("How It Works") }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center group">
                        <div
                            class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-2xl inline-flex mb-4 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600 dark:text-blue-400"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M17 7h-4v2h4c1.65 0 3 1.35 3 3s-1.35 3-3 3h-4v2h4c2.76 0 5-2.24 5-5s-2.24-5-5-5zm-6 8H7c-1.65 0-3-1.35-3-3s1.35-3 3-3h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-2zm-3-4h8v2H8z"
                                />
                            </svg>
                        </div>
                        <h4
                            class="text-sm font-medium text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Generate Link") }}
                        </h4>
                        <p
                            class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed"
                        >
                            {{
                                __(
                                    "Create your unique referral link that tracks sign-ups from your network."
                                )
                            }}
                        </p>
                    </div>
                    <div class="text-center group">
                        <div
                            class="p-4 bg-green-50 dark:bg-green-900/30 rounded-2xl inline-flex mb-4 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-green-600 dark:text-green-400"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"
                                />
                            </svg>
                        </div>
                        <h4
                            class="text-sm font-medium text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Share Widely") }}
                        </h4>
                        <p
                            class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed"
                        >
                            {{
                                __(
                                    "Share your link on social media, emails, or directly with contacts."
                                )
                            }}
                        </p>
                    </div>
                    <div class="text-center group">
                        <div
                            class="p-4 bg-orange-50 dark:bg-orange-900/30 rounded-2xl inline-flex mb-4 group-hover:scale-110 transition-transform duration-300"
                        >
                            <svg
                                class="w-6 h-6 text-orange-600 dark:text-orange-400"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                />
                            </svg>
                        </div>
                        <h4
                            class="text-sm font-medium text-gray-900 dark:text-white mb-2"
                        >
                            {{ __("Earn Commission") }}
                        </h4>
                        <p
                            class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed"
                        >
                            {{
                                __(
                                    "Receive commissions for every successful referral through your link."
                                )
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stats Section (Nueva sección agregada) -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mt-6 p-6 transition-colors duration-300"
            >
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
                >
                    {{ __("Referral Statistics") }}
                </h3>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center"
                    >
                        <div
                            class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1"
                        >
                            0
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Total Referrals") }}
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center"
                    >
                        <div
                            class="text-2xl font-bold text-green-600 dark:text-green-400 mb-1"
                        >
                            $0.00
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Total Earnings") }}
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center"
                    >
                        <div
                            class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-1"
                        >
                            0%
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Conversion Rate") }}
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center"
                    >
                        <div
                            class="text-2xl font-bold text-orange-600 dark:text-orange-400 mb-1"
                        >
                            0
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Active Referrals") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-partner-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import VPartnerLayout from "@/layouts/VPartnerLayout.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const partner = ref({
    referral_links: null,
    commission_rate: 0,
});

const copied = ref(false);
const generating = ref(false);

onMounted(() => {
    partner.value = page.props.data;
});

const copyToClipboard = (text) => {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            copied.value = true;
            setTimeout(() => (copied.value = false), 3000);
        })
        .catch(() => {
            $notify.error(__("Failed to copy to clipboard"));
        });
};

const shareLink = (link) => {
    if (navigator.share) {
        navigator
            .share({
                title: __("Join me on this platform"),
                text: __("Check out this amazing platform I use!"),
                url: link,
            })
            .catch(() => {
                $notify.error(__("Error sharing"));
            });
    } else {
        copyToClipboard(link);
    }
};
</script>

<style scoped>
.referral-container {
    max-width: 1200px;
    margin: 0 auto;
}

* {
    transition: background-color 0.3s ease, border-color 0.3s ease,
        color 0.3s ease;
}

button:not(:disabled):hover {
    transform: translateY(-1px);
}

.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.dark ::-webkit-scrollbar {
    width: 6px;
}

.dark ::-webkit-scrollbar-track {
    background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 3px;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

button:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.shadow-sm {
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}

.dark .shadow-sm {
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.2);
}
</style>
