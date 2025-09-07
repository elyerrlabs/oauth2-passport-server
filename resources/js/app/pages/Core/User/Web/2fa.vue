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
    <v-user-layout>
        <q-page class="two-factor-auth-page">
            <div class="page-container">
                <!-- Header Section -->
                <div class="page-header">
                    <div class="header-content">
                        <q-icon
                            name="mdi-shield-lock"
                            size="36px"
                            color="primary"
                            class="header-icon"
                        />
                        <q-toolbar-title
                            class="text-h4 text-weight-bold text-grey-8"
                        >
                            {{ __("Two-Factor Authentication") }}
                        </q-toolbar-title>
                    </div>
                    <div class="text-subtitle1 text-grey-7 q-mt-sm">
                        {{
                            __("Add an extra layer of security to your account")
                        }}
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row q-col-gutter-lg justify-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <!-- Status Card -->
                        <q-card class="status-card" flat bordered>
                            <q-card-section class="status-section">
                                <div class="row items-center justify-between">
                                    <div class="col">
                                        <div
                                            class="text-h6 text-weight-medium text-grey-8"
                                        >
                                            {{ __("Current Status") }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <q-badge
                                            :color="
                                                user.m2fa
                                                    ? 'positive'
                                                    : 'negative'
                                            "
                                            class="status-badge"
                                            rounded
                                        >
                                            <q-icon
                                                :name="
                                                    user.m2fa
                                                        ? 'mdi-check-decagram'
                                                        : 'mdi-shield-off'
                                                "
                                                size="16px"
                                                class="q-mr-xs"
                                            />
                                            {{
                                                user.m2fa
                                                    ? __("ACTIVE")
                                                    : __("INACTIVE")
                                            }}
                                        </q-badge>
                                    </div>
                                </div>

                                <div class="status-message q-mt-md">
                                    <q-icon
                                        :name="
                                            user.m2fa
                                                ? 'mdi-check-circle'
                                                : 'mdi-alert-circle'
                                        "
                                        :color="
                                            user.m2fa ? 'positive' : 'warning'
                                        "
                                        size="20px"
                                        class="q-mr-sm"
                                    />
                                    <span
                                        :class="
                                            user.m2fa
                                                ? 'text-positive'
                                                : 'text-warning'
                                        "
                                    >
                                        {{
                                            user.m2fa
                                                ? __(
                                                      "Your account is protected with 2FA"
                                                  )
                                                : __(
                                                      "Enable 2FA for enhanced security"
                                                  )
                                        }}
                                    </span>
                                </div>
                            </q-card-section>
                        </q-card>

                        <!-- Authentication Card -->
                        <q-card class="auth-card q-mt-lg" flat bordered>
                            <q-card-section>
                                <div
                                    class="text-h6 text-weight-medium text-grey-8 q-mb-md"
                                >
                                    <q-icon
                                        name="mdi-email-fast"
                                        class="q-mr-sm"
                                    />
                                    {{ __("Email Verification") }}
                                </div>

                                <div class="text-caption text-grey-7 q-mb-lg">
                                    {{
                                        __(
                                            "We'll send a verification code to your email address to enable two-factor authentication."
                                        )
                                    }}
                                </div>

                                <div class="token-section">
                                    <q-input
                                        v-model="token"
                                        :label="
                                            __(
                                                'Enter 6-digit verification code'
                                            )
                                        "
                                        outlined
                                        dense
                                        class="token-input"
                                        :error="!!errors.token"
                                        maxlength="6"
                                        counter
                                        @keyup.enter="activateFactor"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon
                                                name="mdi-lock"
                                                color="grey-6"
                                            />
                                        </template>
                                        <template v-slot:append>
                                            <q-icon
                                                name="mdi-refresh"
                                                color="primary"
                                                class="cursor-pointer"
                                                @click="requestCode"
                                            >
                                                <q-tooltip>{{
                                                    __("Request new code")
                                                }}</q-tooltip>
                                            </q-icon>
                                        </template>
                                    </q-input>
                                    <v-error :error="errors.token" />

                                    <div class="input-hint q-mt-xs">
                                        {{
                                            __(
                                                "Enter the 6-digit code sent to your email"
                                            )
                                        }}
                                    </div>
                                </div>
                            </q-card-section>

                            <q-card-actions
                                class="action-buttons q-px-md q-pb-md"
                            >
                                <q-btn
                                    @click="requestCode"
                                    :label="__('Send Verification Code')"
                                    color="primary"
                                    outline
                                    icon="mdi-email-send"
                                    class="request-button"
                                    :loading="sendingCode"
                                >
                                    <template v-slot:loading>
                                        <q-spinner-hourglass class="on-left" />
                                        {{ __("Sending...") }}
                                    </template>
                                </q-btn>

                                <q-btn
                                    :label="
                                        user.m2fa
                                            ? __('Disable 2FA')
                                            : __('Enable 2FA')
                                    "
                                    :color="user.m2fa ? 'negative' : 'positive'"
                                    @click="activateFactor"
                                    :icon="
                                        user.m2fa
                                            ? 'mdi-lock-open'
                                            : 'mdi-lock-check'
                                    "
                                    unelevated
                                    class="action-button"
                                    :loading="loading"
                                    :disable="!token && !user.m2fa"
                                >
                                    <template v-slot:loading>
                                        <q-spinner-hourglass class="on-left" />
                                        {{
                                            user.m2fa
                                                ? __("Disabling...")
                                                : __("Enabling...")
                                        }}
                                    </template>
                                </q-btn>
                            </q-card-actions>
                        </q-card>

                        <!-- Coming Soon Features -->
                        <q-card
                            class="upcoming-features-card q-mt-lg"
                            flat
                            bordered
                        >
                            <q-card-section>
                                <div
                                    class="text-h6 text-weight-medium text-grey-8 q-mb-md"
                                >
                                    <q-icon
                                        name="mdi-rocket-launch"
                                        class="q-mr-sm"
                                    />
                                    {{ __("Coming Soon") }}
                                </div>

                                <div class="features-list">
                                    <div
                                        class="feature-item row items-center q-mb-md"
                                    >
                                        <q-icon
                                            name="mdi-cellphone-key"
                                            color="blue"
                                            size="24px"
                                            class="q-mr-md"
                                        />
                                        <div>
                                            <div class="text-weight-medium">
                                                {{
                                                    __(
                                                        "Authenticator App (TOTP)"
                                                    )
                                                }}
                                            </div>
                                            <div
                                                class="text-caption text-grey-7"
                                            >
                                                {{
                                                    __(
                                                        "Use apps like Google Authenticator or Authy for verification codes"
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="feature-item row items-center">
                                        <q-icon
                                            name="mdi-qrcode-scan"
                                            color="green"
                                            size="24px"
                                            class="q-mr-md"
                                        />
                                        <div>
                                            <div class="text-weight-medium">
                                                {{ __("QR Code Setup") }}
                                            </div>
                                            <div
                                                class="text-caption text-grey-7"
                                            >
                                                {{
                                                    __(
                                                        "Quick setup with QR code scanning for authenticator apps"
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
    data() {
        return {
            token: "",
            user: {},
            errors: {},
            loading: false,
            sendingCode: false,
        };
    },
    mounted() {
        this.user = this.$page.props.user;
        console.log(this.$page.props.user.links);
    },
    methods: {
        async requestCode() {
            this.sendingCode = true;
            try {
                const res = await this.$server.post(
                    this.$page.props.user.links.request_2fa_code
                );
                if (res.status === 201) {
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                        timeout: 3000,
                    });

                    this.errors = {};
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            } finally {
                this.sendingCode = false;
            }
        },

        async activateFactor() {
            if (!this.token && !this.user.m2fa) {
                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "warning",
                        message: this.__("Please enter a verification code"),
                        timeout: 3000,
                    });
                }

                return;
            }

            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.user.links.f2a_activate,
                    {
                        token: this.token,
                    }
                );

                if (res.status === 201) {
                    this.token = "";
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                        timeout: 3000,
                    });
                    // Reload after a short delay to show the success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
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

<style lang="scss" scoped>
.two-factor-auth-page {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 24px;
}

.page-container {
    max-width: 1000px;
    width: 100%;
}

.page-header {
    text-align: center;
    margin-bottom: 32px;

    .header-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-bottom: 8px;
    }

    .header-icon {
        background: rgba(0, 0, 0, 0.05);
        padding: 16px;
        border-radius: 50%;
    }
}

.status-card,
.auth-card,
.upcoming-features-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);

    .q-card__section {
        padding: 24px;
    }
}

.status-card {
    .status-badge {
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-message {
        display: flex;
        align-items: center;
        font-weight: 500;
    }
}

.auth-card {
    .token-section {
        .token-input {
            :deep(.q-field__control) {
                border-radius: 10px;
                height: 52px;
            }

            :deep(.q-field__native) {
                padding-top: 8px;
                padding-bottom: 8px;
                font-size: 1.1rem;
                letter-spacing: 2px;
                font-weight: 500;
                text-align: center;
            }
        }

        .input-hint {
            font-size: 0.75rem;
            color: #718096;
            text-align: center;
        }
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;

        .request-button,
        .action-button {
            flex: 1;
            min-width: 180px;
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.5px;
        }
    }
}

.upcoming-features-card {
    .feature-item {
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);

        &:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .two-factor-auth-page {
        padding: 16px;
    }

    .page-header {
        .text-h4 {
            font-size: 1.75rem;
        }

        .header-icon {
            padding: 12px;
            font-size: 28px;
        }
    }
}

@media (max-width: 767px) {
    .two-factor-auth-page {
        padding: 12px;
    }

    .auth-card .action-buttons {
        flex-direction: column;

        .request-button,
        .action-button {
            width: 100%;
        }
    }

    .page-header {
        .text-h4 {
            font-size: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            gap: 12px;
        }
    }
}

// Animation for status changes
.status-badge {
    transition: all 0.3s ease;
}

// Enhanced focus states
.token-input :deep(.q-field__control:focus-within) {
    box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
}
</style>
