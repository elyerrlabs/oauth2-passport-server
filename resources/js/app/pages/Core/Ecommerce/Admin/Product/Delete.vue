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
    <div>
        <q-btn
            outline
            rounded
            :color="COLORS.danger"
            @click="dialog = true"
            icon="mdi-delete-outline"
            size="sm"
            class="q-px-sm delete-btn"
            data-test="delete-button"
        >
            {{ __("Delete") }}
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
            class="delete-dialog"
        >
            <q-card class="delete-card">
                <q-card-section class="dialog-header text-center">
                    <q-icon
                        :name="ICONS.warning"
                        :color="COLORS.danger"
                        size="lg"
                        class="warning-icon"
                    />
                    <div class="text-h6 dialog-title">
                        {{ __("Delete Product") }}
                    </div>
                </q-card-section>

                <q-card-section class="dialog-content text-center">
                    <p class="confirmation-text">
                        {{ __("Are you sure you want to delete") }}
                        <span class="product-name">{{ item.name }}</span
                        >? {{ __("This action cannot be undone.") }}
                    </p>
                </q-card-section>

                <q-card-actions align="center" class="dialog-actions">
                    <q-btn
                        flat
                        :color="COLORS.secondary"
                        :label="__('Cancel')"
                        @click="dialog = false"
                        class="action-btn cancel-btn"
                        data-test="cancel-button"
                    />
                    <q-btn
                        unelevated
                        :color="COLORS.danger"
                        :label="__('Delete Product')"
                        @click="destroy"
                        class="action-btn delete-confirm-btn"
                        data-test="confirm-delete-button"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
// Color variables for theming
const COLORS = {
    danger: "negative",
    secondary: "grey-7",
    lightBg: "grey-2",
};

// Icon constants
const ICONS = {
    warning: "mdi-alert-circle-outline",
};

export default {
    emits: ["deleted"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
            COLORS,
            ICONS,
        };
    },

    methods: {
        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.showNotification(
                        "Product deleted successfully",
                        "positive"
                    );
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
                this.dialog = false;
            }
        },

        showNotification(message, color) {
            this.$q.notify({
                message,
                color,
                icon: "mdi-information-outline",
                position: "top",
                timeout: 2500,
            });
        },
    },
};
</script>

<style lang="scss" scoped>
// CSS variables for theming
:root {
    --danger-color: #f44336;
    --danger-light: #ffebee;
    --secondary-color: #9e9e9e;
    --text-color: #333;
    --light-bg: #f5f5f5;
    --border-radius: 8px;
    --box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.delete-btn {
    transition: all 0.3s ease;

    &:hover {
        background-color: var(--danger-light);
        transform: translateY(-1px);
    }

    &:active {
        transform: translateY(0);
    }
}

.delete-dialog {
    .delete-card {
        max-width: 450px;
        width: 90vw;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);

        .dialog-header {
            padding: 24px 16px 16px;

            .warning-icon {
                margin-bottom: 12px;
            }

            .dialog-title {
                font-weight: 600;
                color: var(--text-color);
                margin: 0;
            }
        }

        .dialog-content {
            padding: 0 24px 16px;

            .confirmation-text {
                color: var(--text-color);
                line-height: 1.5;
                margin: 0;

                .product-name {
                    font-weight: 600;
                    color: var(--danger-color);
                }
            }
        }

        .dialog-actions {
            padding: 16px 24px 24px;

            .action-btn {
                min-width: 120px;
                border-radius: 4px;
                font-weight: 500;
                padding: 8px 16px;
                transition: all 0.2s ease;

                &.cancel-btn {
                    margin-right: 12px;

                    &:hover {
                        background-color: var(--light-bg);
                    }
                }

                &.delete-confirm-btn {
                    color: white;

                    &:hover {
                        opacity: 0.9;
                        transform: translateY(-1px);
                    }

                    &:active {
                        transform: translateY(0);
                    }
                }
            }
        }
    }
}

// Responsive adjustments
@media (max-width: 600px) {
    .delete-dialog .delete-card {
        margin: 16px;

        .dialog-actions {
            flex-direction: column;
            gap: 12px;

            .action-btn {
                width: 100%;

                &.cancel-btn {
                    margin-right: 0;
                    order: 2;
                }
            }
        }
    }
}
</style>
