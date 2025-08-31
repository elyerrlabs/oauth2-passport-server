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
    <!-- Update Button -->
    <q-btn
        icon="mdi-percent"
        outline
        round
        color="primary"
        size="sm"
        @click="loadData(item)"
        class="update-commission-btn"
    >
        <q-tooltip transition-show="scale" transition-hide="scale">
            Update Commission Rate
        </q-tooltip>
    </q-btn>

    <!-- Commission Update Dialog -->
    <q-dialog
        v-model="dialog"
        persistent
        maximized
        transition-show="scale"
        transition-hide="scale"
    >
        <q-card class="commission-dialog-card">
            <!-- Header -->
            <q-card-section class="dialog-header bg-primary text-white">
                <div class="row items-center">
                    <q-icon
                        name="mdi-account-cash"
                        size="28px"
                        class="q-mr-md"
                    />
                    <div class="text-h6">Update Commission Rate</div>
                </div>
                <q-space />
                <q-btn
                    icon="mdi-close"
                    flat
                    round
                    dense
                    v-close-popup
                    class="close-btn"
                />
            </q-card-section>

            <!-- Content -->
            <q-card-section class="dialog-content">
                <div class="row justify-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <!-- Current Commission Display -->
                        <div
                            class="current-commission-section text-center q-mb-lg"
                        >
                            <div class="text-caption text-grey-7">
                                Current Commission Rate
                            </div>
                            <div class="text-h4 text-primary text-weight-bold">
                                {{ item.commission_rate }}%
                            </div>
                            <div class="text-caption text-grey-7 q-mt-xs">
                                for {{ item.name }} {{ item.last_name }}
                            </div>
                        </div>

                        <!-- Commission Input -->
                        <div class="commission-input-section">
                            <div class="text-subtitle2 q-mb-sm">
                                New Commission Rate
                            </div>

                            <q-input
                                v-model="form.commission_rate"
                                label="Commission Percentage"
                                outlined
                                dense
                                type="number"
                                min="0"
                                max="100"
                                step="0.01"
                                :error="!!errors.commission_rate"
                                :error-message="errors.commission_rate"
                                class="commission-input"
                                @keyup.enter="update"
                            >
                                <template v-slot:prepend>
                                    <q-icon
                                        name="mdi-percent"
                                        color="primary"
                                    />
                                </template>
                                <template v-slot:append>
                                    <span class="text-caption text-grey-6"
                                        >%</span
                                    >
                                </template>
                                <template v-slot:hint>
                                    Enter a value between 0 and 100
                                </template>
                            </q-input>

                            <!-- Visual Slider -->
                            <div class="q-mt-md">
                                <q-slider
                                    v-model="sliderValue"
                                    :min="0"
                                    :max="100"
                                    :step="0.5"
                                    label
                                    :label-value="sliderValue + '%'"
                                    color="primary"
                                    @update:model-value="updateFromSlider"
                                    class="commission-slider"
                                />
                                <div class="row justify-between q-mt-xs">
                                    <span class="text-caption text-grey-6"
                                        >0%</span
                                    >
                                    <span class="text-caption text-grey-6"
                                        >100%</span
                                    >
                                </div>
                            </div>
                        </div>

                        <v-error
                            :error="errors.commission_rate"
                            class="q-mt-sm"
                        />
                    </div>
                </div>
            </q-card-section>

            <!-- Actions -->
            <q-card-actions class="dialog-actions" align="right">
                <q-btn
                    flat
                    label="Cancel"
                    color="grey-7"
                    @click="dialog = false"
                    class="action-btn"
                    icon="mdi-close-circle"
                />
                <q-btn
                    flat
                    label="Update Commission"
                    color="primary"
                    @click="update"
                    class="action-btn"
                    :loading="updating"
                    icon="mdi-check-circle"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<script>
export default {
    emits: ["updated"],

    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            errors: {},
            form: {
                commission_rate: 0,
            },
            dialog: false,
            updating: false,
            sliderValue: 0,
        };
    },

    watch: {
        "form.commission_rate"(newValue) {
            // Ensure the value is within 0-100 range and has max 2 decimal places
            if (newValue !== null && newValue !== undefined) {
                let numValue = parseFloat(newValue);
                if (isNaN(numValue)) {
                    this.form.commission_rate = 0;
                } else if (numValue > 100) {
                    this.form.commission_rate = 100;
                } else if (numValue < 0) {
                    this.form.commission_rate = 0;
                } else {
                    // Limit to 2 decimal places
                    this.form.commission_rate =
                        Math.round(numValue * 100) / 100;
                }
                this.sliderValue = this.form.commission_rate;
            }
        },
    },

    methods: {
        /**
         * Load necessary data to update commission rate
         */
        async loadData(item) {
            this.form = {
                commission_rate: parseFloat(item.commission_rate) || 0,
            };
            this.sliderValue = this.form.commission_rate;
            this.errors = {};
            this.dialog = true;
        },

        /**
         * Update commission rate from slider
         */
        updateFromSlider(value) {
            this.form.commission_rate = value;
        },

        /**
         * Update the commission rate
         */
        async update() {
            // Validate input
            if (
                this.form.commission_rate === null ||
                this.form.commission_rate === undefined
            ) {
                this.errors = {
                    commission_rate: ["Commission rate is required"],
                };
                return;
            }

            const commissionRate = parseFloat(this.form.commission_rate);
            if (isNaN(commissionRate)) {
                this.errors = {
                    commission_rate: ["Please enter a valid number"],
                };
                return;
            }

            if (commissionRate < 0 || commissionRate > 100) {
                this.errors = {
                    commission_rate: [
                        "Commission rate must be between 0 and 100",
                    ],
                };
                return;
            }

            this.updating = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.item.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.errors = {};
                    this.$emit("updated", true);
                    this.dialog = false;

                    this.$q.notify({
                        type: "positive",
                        message: "Commission rate updated successfully",
                        timeout: 3000,
                        position: "top-right",
                        icon: "mdi-check-circle",
                        progress: true,
                    });
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                } else if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                        position: "top-right",
                        icon: "mdi-alert-circle",
                        progress: true,
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message:
                            "An error occurred while updating the commission rate",
                        timeout: 3000,
                        position: "top-right",
                        icon: "mdi-alert-circle",
                        progress: true,
                    });
                }
            } finally {
                this.updating = false;
            }
        },
    },
};
</script>

<style scoped>
.update-commission-btn {
    transition: transform 0.2s ease;
}

.update-commission-btn:hover {
    transform: scale(1.1);
}

.commission-dialog-card {
    width: 100%;
    max-width: 600px;
    border-radius: 16px;
    overflow: hidden;
}

.dialog-header {
    padding: 20px;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
}

.close-btn {
    color: white !important;
}

.dialog-content {
    padding: 30px;
}

.dialog-actions {
    padding: 20px;
    border-top: 1px solid #e0e0e0;
}

.current-commission-section {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
    border-radius: 12px;
    border-left: 4px solid var(--q-primary);
}

.commission-input-section {
    padding: 20px;
    background-color: #fafafa;
    border-radius: 12px;
}

.commission-input {
    border-radius: 8px;
}

.commission-slider {
    margin-top: 20px;
}

.action-btn {
    border-radius: 8px;
    padding: 8px 16px;
    min-width: 120px;
    transition: all 0.2s ease;
}

.action-btn:hover {
    transform: translateY(-1px);
}

:deep(.q-dialog__inner > div) {
    border-radius: 16px;
}

:deep(.q-slider__track-container--h) {
    height: 8px;
    border-radius: 4px;
}

:deep(.q-slider__thumb-container) {
    width: 24px;
    height: 24px;
}

:deep(.q-slider__thumb) {
    width: 24px;
    height: 24px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

@media (max-width: 600px) {
    .commission-dialog-card {
        margin: 16px;
        max-width: calc(100% - 32px);
    }

    .dialog-content {
        padding: 20px;
    }

    .current-commission-section .text-h4 {
        font-size: 1.8rem;
    }

    .action-btn {
        min-width: 100px;
        padding: 6px 12px;
    }
}
</style>
