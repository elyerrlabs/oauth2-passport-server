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
                <div class="p-3 bg-blue-600 text-white rounded-lg mr-4">
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
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ __("Referral System") }}
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">
                        {{ __("Generate and share your referral links") }}
                    </p>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <!-- Card Header -->
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                    <h2 class="text-lg font-bold">
                        {{ __("Generate Referral Link") }}
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">
                        {{ __("Share your link and earn commissions") }}
                    </p>
                </div>

                <!-- Commission Info -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <svg
                                class="w-5 h-5 text-blue-600 mr-2"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"
                                />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">
                                {{ __("Your Commission Rate") }}
                            </span>
                        </div>
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                        >
                            {{ partner?.commission_rate || 0 }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                            :style="{
                                width: `${partner?.commission_rate || 0}%`,
                            }"
                        ></div>
                    </div>
                </div>

                <!-- Action Section -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                    >
                        <p class="text-sm text-gray-600 flex-1">
                            {{
                                __(
                                    "Generate a unique referral link to share with your network"
                                )
                            }}
                        </p>
                        <button
                            @click="generateReferralLink"
                            :disabled="generating"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center justify-center space-x-2 min-w-[140px]"
                        >
                            <svg
                                v-if="generating"
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
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M17 7h-4v2h4c1.65 0 3 1.35 3 3s-1.35 3-3 3h-4v2h4c2.76 0 5-2.24 5-5s-2.24-5-5-5zm-6 8H7c-1.65 0-3-1.35-3-3s1.35-3 3-3h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-2zm-3-4h8v2H8z"
                                />
                            </svg>
                            <span>{{
                                generating
                                    ? __("Generating...")
                                    : __("Generate New Link")
                            }}</span>
                        </button>
                    </div>
                </div>

                <!-- Generated Link Section -->
                <div class="px-6 py-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">
                        {{ __("Your Referral Link") }}
                    </h3>

                    <div v-if="partner?.referral_links" class="space-y-3">
                        <div
                            class="flex border border-gray-300 rounded-md overflow-hidden"
                        >
                            <div
                                class="flex items-center px-3 bg-gray-50 border-r border-gray-300"
                            >
                                <svg
                                    class="w-4 h-4 text-blue-600"
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
                                class="flex-1 px-3 py-2 text-sm bg-white outline-none truncate"
                            />
                            <div class="flex border-l border-gray-300">
                                <button
                                    @click="
                                        copyToClipboard(partner.referral_links)
                                    "
                                    class="p-2 text-gray-600 hover:text-blue-600 hover:bg-gray-50 transition-colors"
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
                                    class="p-2 text-gray-600 hover:text-blue-600 hover:bg-gray-50 transition-colors"
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
                        class="text-center py-6 border-2 border-dashed border-gray-300 rounded-lg"
                    >
                        <svg
                            class="w-12 h-12 text-gray-300 mx-auto mb-3"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"
                            />
                        </svg>
                        <p class="text-gray-500 text-sm mb-1">
                            {{ __("No referral link generated yet") }}
                        </p>
                        <p class="text-gray-400 text-xs">
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
                        class="mx-6 mb-4 p-3 bg-green-50 border border-green-200 rounded-md flex items-center"
                    >
                        <svg
                            class="w-5 h-5 text-green-500 mr-2"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                            />
                        </svg>
                        <span class="text-green-800 text-sm flex-1">
                            {{ __("Link successfully copied to clipboard!") }}
                        </span>
                        <button
                            @click="copied = false"
                            class="text-green-600 hover:text-green-800"
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
                class="bg-white rounded-lg border border-gray-200 shadow-sm mt-4 p-6"
            >
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ __("How It Works") }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="p-3 bg-blue-50 rounded-lg inline-flex mb-3">
                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M17 7h-4v2h4c1.65 0 3 1.35 3 3s-1.35 3-3 3h-4v2h4c2.76 0 5-2.24 5-5s-2.24-5-5-5zm-6 8H7c-1.65 0-3-1.35-3-3s1.35-3 3-3h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-2zm-3-4h8v2H8z"
                                />
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">
                            {{ __("Generate Link") }}
                        </h4>
                        <p class="text-xs text-gray-600">
                            {{
                                __(
                                    "Create your unique referral link that tracks sign-ups from your network."
                                )
                            }}
                        </p>
                    </div>
                    <div class="text-center">
                        <div
                            class="p-3 bg-green-50 rounded-lg inline-flex mb-3"
                        >
                            <svg
                                class="w-6 h-6 text-green-600"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"
                                />
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">
                            {{ __("Share Widely") }}
                        </h4>
                        <p class="text-xs text-gray-600">
                            {{
                                __(
                                    "Share your link on social media, emails, or directly with contacts."
                                )
                            }}
                        </p>
                    </div>
                    <div class="text-center">
                        <div
                            class="p-3 bg-orange-50 rounded-lg inline-flex mb-3"
                        >
                            <svg
                                class="w-6 h-6 text-orange-600"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91 2.56.62 4.18 1.63 4.18 3.71 0 1.76-1.39 2.83-3.13 3.16z"
                                />
                            </svg>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">
                            {{ __("Earn Commission") }}
                        </h4>
                        <p class="text-xs text-gray-600">
                            {{
                                __(
                                    "Receive commissions for every successful referral through your link."
                                )
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-partner-layout>
</template>

<script>
import VPartnerLayout from "@/layouts/VPartnerLayout.vue";

export default {
    components: {
        VPartnerLayout,
    },
    data() {
        return {
            partner: {
                referral_links: null,
                commission_rate: 0,
            },
            copied: false,
            generating: false,
        };
    },

    mounted() {
        this.generateReferralLink();
    },

    methods: {
        async generateReferralLink() {
            this.generating = true;
            try {
                const res = await this.$server.post(this.$page.props.route);
                if (res.status == 201) {
                    this.partner = res.data.data;

                    $notify.success(
                        __("New referral link generated successfully!")
                    );
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.generating = false;
            }
        },

        copyToClipboard(text) {
            navigator.clipboard
                .writeText(text)
                .then(() => {
                    this.copied = true;
                    setTimeout(() => (this.copied = false), 3000);
                })
                .catch((err) => {
                    $notify.error(__("Failed to copy to clipboard"));
                });
        },

        shareLink(link) {
            if (navigator.share) {
                navigator
                    .share({
                        title: __("Join me on this platform"),
                        text: __("Check out this amazing platform I use!"),
                        url: link,
                    })
                    .catch((err) => {
                        $notify.error(__("Error sharing"));
                    });
            } else {
                this.copyToClipboard(link);
            }
        },
    },
};
</script>

<style scoped>
.referral-container {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
