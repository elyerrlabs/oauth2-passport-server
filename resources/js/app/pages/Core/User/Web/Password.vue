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
        <q-page class="password-update-page">
            <div class="page-container">
                <!-- Header Section -->
                <div class="page-header">
                    <div class="header-content">
                        <q-icon
                            name="mdi-lock-reset"
                            size="32px"
                            color="primary"
                            class="header-icon"
                        />
                        <q-toolbar-title
                            class="text-h5 text-weight-bold text-grey-8"
                        >
                            {{ __("Update Password") }}
                        </q-toolbar-title>
                    </div>
                    <div class="text-subtitle1 text-grey-7 q-mt-xs">
                        {{ __("Secure your account with a new password") }}
                    </div>
                </div>

                <!-- Password Form Card -->
                <q-card class="password-form-card" flat bordered>
                    <q-card-section>
                        <div class="row q-col-gutter-lg">
                            <!-- Current Password -->
                            <div class="col-12">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Current Password") }}
                                    </div>
                                    <q-input
                                        filled
                                        v-model="form.current_password"
                                        :placeholder="
                                            __('Enter your current password')
                                        "
                                        :type="
                                            showCurrentPassword
                                                ? 'text'
                                                : 'password'
                                        "
                                        :error="!!errors.current_password"
                                        class="password-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                :name="
                                                    showCurrentPassword
                                                        ? 'mdi-eye-off'
                                                        : 'mdi-eye'
                                                "
                                                class="cursor-pointer"
                                                @click="
                                                    showCurrentPassword =
                                                        !showCurrentPassword
                                                "
                                            />
                                        </template>
                                    </q-input>
                                    <v-error :error="errors.current_password" />
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="col-12">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("New Password") }}
                                    </div>
                                    <q-input
                                        filled
                                        type="password"
                                        v-model="form.password"
                                        :placeholder="
                                            __('Create a strong new password')
                                        "
                                        :error="!!errors.password"
                                        class="password-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                :name="
                                                    showNewPassword
                                                        ? 'mdi-eye-off'
                                                        : 'mdi-eye'
                                                "
                                                class="cursor-pointer"
                                                @click="
                                                    showNewPassword =
                                                        !showNewPassword
                                                "
                                            />
                                        </template>
                                    </q-input>
                                    <div
                                        class="password-strength q-mt-xs"
                                        v-if="form.password"
                                    >
                                        <div class="text-caption text-grey-7">
                                            {{ __("Password strength:") }}
                                        </div>
                                        <q-linear-progress
                                            :value="passwordStrength"
                                            :color="passwordStrengthColor"
                                            class="q-mt-xs"
                                            rounded
                                            size="6px"
                                        />
                                        <div
                                            class="text-caption text-grey-7 q-mt-xs"
                                        >
                                            {{ passwordStrengthText }}
                                        </div>
                                    </div>
                                    <v-error :error="errors.password" />
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-12">
                                <div class="input-section">
                                    <div class="input-label">
                                        {{ __("Confirm Password") }}
                                    </div>
                                    <q-input
                                        filled
                                        type="password"
                                        v-model="form.password_confirmation"
                                        :placeholder="
                                            __('Confirm your new password')
                                        "
                                        :error="!!errors.password_confirmation"
                                        class="password-input"
                                    >
                                        <template v-slot:append>
                                            <q-icon
                                                :name="
                                                    showConfirmPassword
                                                        ? 'mdi-eye-off'
                                                        : 'mdi-eye'
                                                "
                                                class="cursor-pointer"
                                                @click="
                                                    showConfirmPassword =
                                                        !showConfirmPassword
                                                "
                                            />
                                        </template>
                                    </q-input>
                                    <div
                                        class="password-match q-mt-xs"
                                        v-if="
                                            form.password &&
                                            form.password_confirmation
                                        "
                                    >
                                        <q-icon
                                            :name="
                                                passwordsMatch
                                                    ? 'mdi-check-circle'
                                                    : 'mdi-alert-circle'
                                            "
                                            :color="
                                                passwordsMatch
                                                    ? 'positive'
                                                    : 'negative'
                                            "
                                            size="16px"
                                        />
                                        <span
                                            class="text-caption q-ml-xs"
                                            :class="
                                                passwordsMatch
                                                    ? 'text-positive'
                                                    : 'text-negative'
                                            "
                                        >
                                            {{
                                                passwordsMatch
                                                    ? __("Passwords match")
                                                    : __(
                                                          "Passwords do not match"
                                                      )
                                            }}
                                        </span>
                                    </div>
                                    <v-error
                                        :error="errors.password_confirmation"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="action-buttons q-mt-xl">
                            <q-btn
                                :label="__('Update Password')"
                                color="primary"
                                unelevated
                                no-caps
                                @click="update"
                                class="submit-button"
                                :loading="loading"
                            >
                                <template v-slot:loading>
                                    <q-spinner-hourglass class="on-left" />
                                    {{ __("Updating...") }}
                                </template>
                            </q-btn>
                        </div>
                    </q-card-section>
                </q-card>

                <!-- Security Tips -->
                <q-card class="security-tips-card q-mt-lg" flat bordered>
                    <q-card-section>
                        <div class="text-h6 text-weight-medium text-grey-8">
                            <q-icon name="mdi-shield-check" class="q-mr-sm" />
                            {{ __("Password Security Tips") }}
                        </div>
                        <q-list dense class="q-mt-md">
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-check-circle"
                                        color="positive"
                                    />
                                </q-item-section>
                                <q-item-section>{{
                                    __("Use at least 8 characters")
                                }}</q-item-section>
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-check-circle"
                                        color="positive"
                                    />
                                </q-item-section>
                                <q-item-section>{{
                                    __(
                                        "Include uppercase and lowercase letters"
                                    )
                                }}</q-item-section>
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-check-circle"
                                        color="positive"
                                    />
                                </q-item-section>
                                <q-item-section>{{
                                    __("Add numbers and special characters")
                                }}</q-item-section>
                            </q-item>
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon
                                        name="mdi-check-circle"
                                        color="positive"
                                    />
                                </q-item-section>
                                <q-item-section>{{
                                    __(
                                        "Avoid common words or personal information"
                                    )
                                }}</q-item-section>
                            </q-item>
                        </q-list>
                    </q-card-section>
                </q-card>
            </div>
        </q-page>
    </v-user-layout>
</template>

<script>
export default {
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
            if (this.passwordStrength < 0.4) return "negative";
            if (this.passwordStrength < 0.7) return "warning";
            return "positive";
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

                    this.$q.notify({
                        type: "negative",
                        message: "Please fix the errors in the form",
                        timeout: 3000,
                        position: "top-right",
                        icon: "mdi-alert-circle",
                        progress: true,
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: "An error occurred. Please try again.",
                        timeout: 3000,
                        position: "top-right",
                        icon: "mdi-alert-circle",
                        progress: true,
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
.password-update-page {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 24px;
}

.page-container {
    max-width: 600px;
    width: 100%;
}

.page-header {
    text-align: center;
    margin-bottom: 32px;

    .header-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .header-icon {
        background: rgba(0, 0, 0, 0.05);
        padding: 12px;
        border-radius: 50%;
    }
}

.password-form-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;

    .q-card__section {
        padding: 32px;
    }
}

.input-section {
    margin-bottom: 24px;

    .input-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #2d3748;
        font-size: 0.95rem;
    }

    .password-input {
        :deep(.q-field__control) {
            border-radius: 10px;
            height: 52px;
        }

        :deep(.q-field__native) {
            padding-top: 8px;
            padding-bottom: 8px;
        }
    }

    .password-strength,
    .password-match {
        display: flex;
        align-items: center;
    }
}

.action-buttons {
    display: flex;
    justify-content: center;

    .submit-button {
        min-width: 200px;
        height: 48px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        text-transform: none;
        letter-spacing: 0.5px;
    }
}

.security-tips-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);

    .q-card__section {
        padding: 24px;
    }

    .q-item {
        padding: 8px 0;

        .q-item__section--avatar {
            min-width: 32px;
        }
    }
}

// Responsive adjustments
@media (max-width: 1023px) {
    .password-update-page {
        padding: 16px;
    }

    .password-form-card .q-card__section {
        padding: 24px;
    }
}

@media (max-width: 599px) {
    .page-header {
        .text-h5 {
            font-size: 1.5rem;
        }

        .header-icon {
            padding: 8px;
            font-size: 24px;
        }
    }

    .password-form-card .q-card__section {
        padding: 20px;
    }

    .submit-button {
        width: 100%;
    }
}
</style>
