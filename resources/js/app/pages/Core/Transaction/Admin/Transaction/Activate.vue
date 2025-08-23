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
            icon="mdi-power"
            color="positive"
            @click="dialog = true"
            size="sm"
            round
        >
            <q-tooltip
                transition-show="scale"
                transition-hide="scale"
                class="bg-primary text-body2"
            >
                Activate transaction
            </q-tooltip>
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="dialog-card">
                <q-card-section class="dialog-header bg-primary text-white">
                    <div class="text-h6">Activate Transaction</div>
                    <q-space />
                    <q-btn
                        icon="close"
                        flat
                        round
                        dense
                        v-close-popup
                        class="text-white"
                    />
                </q-card-section>

                <q-card-section class="q-pt-lg dialog-content">
                    <div class="row items-center q-mb-md">
                        <q-icon
                            name="mdi-alert-circle-outline"
                            color="warning"
                            size="24px"
                            class="q-mr-sm"
                        />
                        <span class="text-subtitle1">
                            Are you sure you want to activate this transaction?
                        </span>
                    </div>

                    <q-separator class="q-my-md" />

                    <div v-if="item.description" class="transaction-details">
                        <div class="text-caption text-grey-7">
                            Transaction details:
                        </div>
                        <div class="text-body2">{{ item.description }}</div>
                    </div>
                </q-card-section>

                <q-card-actions class="dialog-actions">
                    <q-btn
                        flat
                        label="Cancel"
                        color="grey"
                        @click="dialog = false"
                        class="q-px-md"
                    />
                    <q-space />
                    <q-btn
                        label="Activate"
                        color="positive"
                        @click="activate"
                        icon="mdi-power"
                        class="q-px-lg activate-action-btn"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["updated"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        async activate() {
            try {
                const res = await this.$server.put(this.item.links.activate);

                if (res.status == 200) {
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Transaction has been activated successfully",
                        icon: "mdi-check-circle",
                        position: "top-right",
                        timeout: 2500,
                    });
                    this.$emit("updated");
                }
            } catch (error) {
                if (error?.response?.status == 402) {
                    this.$q.notify({
                        type: "negative",
                        message: `Cannot activate transaction: ${error.response.data.message}`,
                        icon: "mdi-alert-circle",
                        position: "top-right",
                        timeout: 3000,
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message:
                            "An error occurred while activating the transaction",
                        icon: "mdi-alert-circle",
                        position: "top-right",
                        timeout: 3000,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>

<style scoped>
.dialog-card {
    min-width: 400px;
    border-radius: 8px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.dialog-header {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    padding: 16px;
}

.dialog-content {
    padding: 20px 24px;
}

.dialog-actions {
    padding: 16px 24px;
    border-top: 1px solid rgba(0, 0, 0, 0.12);
}

.activate-btn {
    transition: all 0.3s ease;
}

.activate-btn:hover {
    transform: scale(1.05);
    background-color: rgba(0, 150, 0, 0.1);
}

.activate-action-btn {
    font-weight: 600;
}

.transaction-details {
    background-color: #f9f9f9;
    padding: 12px;
    border-radius: 6px;
    margin-top: 12px;
}
</style>
