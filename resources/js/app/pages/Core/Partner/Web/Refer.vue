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
        <div class="referral-container q-pa-lg">
            <!-- Header Section -->
            <div class="row items-center q-mb-lg">
                <q-avatar
                    color="primary"
                    text-color="white"
                    icon="mdi-link-variant"
                    size="48px"
                    class="q-mr-md"
                />
                <div>
                    <div class="text-h4 text-weight-bold text-primary">
                        {{ __("Referral System") }}
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        {{ __("Generate and share your referral links") }}
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <q-card flat class="referral-card shadow-5">
                <q-card-section class="card-header bg-primary text-white">
                    <div class="text-h5 text-weight-bold">
                        {{ __("Generate Referral Link") }}
                    </div>
                    <div class="text-subtitle2">
                        {{ __("Share your link and earn commissions") }}
                    </div>
                </q-card-section>

                <!-- Commission Info -->
                <q-card-section class="commission-section">
                    <div class="row items-center justify-between">
                        <div class="row items-center">
                            <q-icon
                                name="mdi-percent"
                                color="primary"
                                size="24px"
                                class="q-mr-sm"
                            />
                            <div class="text-subtitle1">
                                {{ __("Your Commission Rate") }}
                            </div>
                        </div>
                        <q-badge color="primary" class="commission-badge">
                            {{ partner?.commission_rate || 0 }}%
                        </q-badge>
                    </div>
                    <q-linear-progress
                        size="12px"
                        :value="(partner?.commission_rate || 0) / 100"
                        color="primary"
                        class="q-mt-md"
                        rounded
                    >
                        <div class="absolute-full flex flex-center">
                            <q-badge
                                color="white"
                                text-color="primary"
                                :label="`${partner?.commission_rate || 0}%`"
                            />
                        </div>
                    </q-linear-progress>
                </q-card-section>

                <q-separator />

                <!-- Action Section -->
                <q-card-section class="action-section">
                    <div class="row items-center justify-between">
                        <div class="text-caption text-grey-7">
                            {{
                                __(
                                    "Generate a unique referral link to share with your network"
                                )
                            }}
                        </div>
                        <q-btn
                            :label="__('Generate New Link')"
                            color="primary"
                            icon="mdi-link-variant-plus"
                            @click="generateReferralLink"
                            unelevated
                            class="generate-btn"
                            :loading="generating"
                        />
                    </div>
                </q-card-section>

                <q-separator />

                <!-- Generated Link Section -->
                <q-card-section class="link-section">
                    <div class="text-subtitle2 text-weight-medium q-mb-sm">
                        {{ __("Your Referral Link") }}
                    </div>

                    <div v-if="partner?.referral_links" class="link-container">
                        <q-input
                            readonly
                            v-model="partner.referral_links"
                            dense
                            outlined
                            class="link-input"
                            input-class="text-copyable"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-link" color="primary" />
                            </template>
                            <template v-slot:append>
                                <q-btn
                                    flat
                                    icon="mdi-content-copy"
                                    color="primary"
                                    @click="
                                        copyToClipboard(partner.referral_links)
                                    "
                                    class="copy-btn"
                                >
                                    <q-tooltip>{{
                                        __("Copy to clipboard")
                                    }}</q-tooltip>
                                </q-btn>
                                <q-btn
                                    flat
                                    icon="mdi-share-variant"
                                    color="primary"
                                    @click="shareLink(partner.referral_links)"
                                    class="share-btn"
                                >
                                    <q-tooltip>{{
                                        __("Share link")
                                    }}</q-tooltip>
                                </q-btn>
                            </template>
                        </q-input>
                    </div>

                    <div v-else class="placeholder-section text-center q-pa-lg">
                        <q-icon
                            name="mdi-link-off"
                            size="48px"
                            color="grey-4"
                            class="q-mb-sm"
                        />
                        <div class="text-grey-6">
                            {{ __("No referral link generated yet") }}
                        </div>
                        <div class="text-caption text-grey-5">
                            {{
                                __(
                                    'Click "Generate New Link" to create your first referral link'
                                )
                            }}
                        </div>
                    </div>
                </q-card-section>

                <!-- Success Notification -->
                <transition
                    enter-active-class="animated fadeIn"
                    leave-active-class="animated fadeOut"
                >
                    <q-banner
                        dense
                        class="notification-banner bg-positive text-white"
                        v-if="copied"
                    >
                        <template v-slot:avatar>
                            <q-icon name="mdi-check-circle" />
                        </template>
                        {{ __("Link successfully copied to clipboard!") }}
                        <template v-slot:action>
                            <q-btn
                                flat
                                :label="__('Dismiss')"
                                @click="copied = false"
                            />
                        </template>
                    </q-banner>
                </transition>
            </q-card>

            <!-- Additional Information -->
            <q-card flat class="info-card q-mt-md">
                <q-card-section>
                    <div class="text-h6 text-weight-medium q-mb-md">
                        {{ __("How It Works") }}
                    </div>
                    <div class="row q-col-gutter-md">
                        <div class="col-12 col-md-4">
                            <div class="row items-center q-mb-sm">
                                <q-avatar
                                    color="blue-1"
                                    text-color="primary"
                                    icon="mdi-link"
                                    size="sm"
                                    class="q-mr-sm"
                                />
                                <div class="text-subtitle2">
                                    {{ __("Generate Link") }}
                                </div>
                            </div>
                            <div class="text-caption text-grey-7">
                                {{
                                    __(
                                        "Create your unique referral link that tracks sign-ups from your network."
                                    )
                                }}
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="row items-center q-mb-sm">
                                <q-avatar
                                    color="green-1"
                                    text-color="positive"
                                    icon="mdi-share"
                                    size="sm"
                                    class="q-mr-sm"
                                />
                                <div class="text-subtitle2">
                                    {{ __("Share Widely") }}
                                </div>
                            </div>
                            <div class="text-caption text-grey-7">
                                {{
                                    __(
                                        "Share your link on social media, emails, or directly with contacts."
                                    )
                                }}
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="row items-center q-mb-sm">
                                <q-avatar
                                    color="orange-1"
                                    text-color="warning"
                                    icon="mdi-cash"
                                    size="sm"
                                    class="q-mr-sm"
                                />
                                <div class="text-subtitle2">
                                    {{ __("Earn Commission") }}
                                </div>
                            </div>
                            <div class="text-caption text-grey-7">
                                {{
                                    __(
                                        "Receive commissions for every successful referral through your link."
                                    )
                                }}
                            </div>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </div>
    </v-partner-layout>
</template>

<script>
export default {
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
                    this.$q.notify({
                        type: "positive",
                        message: "New referral link generated successfully!",
                        position: "top-right",
                        icon: "mdi-check-circle",
                    });
                }
            } catch (error) {
                this.$q.notify({
                    type: "negative",
                    message: "Failed to generate referral link",
                    position: "top-right",
                    icon: "mdi-alert-circle",
                });
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

                    // Visual feedback
                    this.$q.notify({
                        message: "Copied to clipboard!",
                        color: "positive",
                        icon: "mdi-content-copy",
                        position: "top-right",
                        timeout: 1500,
                    });
                })
                .catch((err) => {
                    this.$q.notify({
                        message: "Failed to copy to clipboard",
                        color: "negative",
                        icon: "mdi-alert-circle",
                        position: "top-right",
                    });
                });
        },

        shareLink(link) {
            if (navigator.share) {
                navigator
                    .share({
                        title: "Join me on this platform",
                        text: "Check out this amazing platform I use!",
                        url: link,
                    })
                    .catch((err) => {
                        console.log("Error sharing:", err);
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
    max-width: 800px;
    margin: 0 auto;
}

.referral-card {
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 24px;
}

.commission-section {
    padding: 20px;
}

.commission-badge {
    font-size: 1.1rem;
    padding: 8px 16px;
    border-radius: 16px;
}

.action-section {
    padding: 20px;
}

.generate-btn {
    border-radius: 8px;
    padding: 8px 16px;
}

.link-section {
    padding: 20px;
}

.link-container {
    position: relative;
}

.link-input {
    border-radius: 8px;
}

.text-copyable {
    font-family: "Monaco", "Menlo", "Ubuntu Mono", monospace;
    font-size: 0.9rem;
    cursor: pointer;
}

.copy-btn,
.share-btn {
    margin-left: 4px;
}

.placeholder-section {
    border: 2px dashed #e0e0e0;
    border-radius: 12px;
    background-color: #fafafa;
}

.notification-banner {
    border-radius: 0 0 12px 12px;
}

.info-card {
    border-radius: 12px;
    border-left: 4px solid #1976d2;
}

/* Animation for the generate button */
.generate-btn {
    transition: transform 0.2s ease;
}

.generate-btn:active {
    transform: scale(0.98);
}

/* Hover effects */
.referral-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.referral-card:hover {
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .referral-container {
        padding: 12px;
    }

    .card-header {
        padding: 16px;
    }

    .text-h4 {
        font-size: 1.5rem;
    }

    .action-section .row {
        flex-direction: column;
        gap: 12px;
    }

    .generate-btn {
        width: 100%;
    }
}
</style>
