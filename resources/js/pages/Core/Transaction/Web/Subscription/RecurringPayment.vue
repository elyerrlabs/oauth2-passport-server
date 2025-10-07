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
            :label="buttonLabel"
            :icon="buttonIcon"
            :color="buttonColor"
            size="sm"
            class="q-mt-sm full-width"
            @click="dialog = true"
        />
        <q-dialog v-model="dialog">
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">{{ __(dialogTitle) }}</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section>
                    {{ __(dialogMessage) }}
                </q-card-section>

                <q-card-actions class="flex">
                    <q-btn
                        outline
                        :label="__(Accept)"
                        color="positive"
                        @click="recurringPayment"
                    />
                    <q-space />
                    <q-btn
                        outline
                        :label="__(Cancel)"
                        color="negative"
                        @click="dialog = false"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["success"],

    props: {
        item: {
            type: Object,
            required: true,
            default: () => ({}),
        },
    },

    data() {
        return {
            dialog: false,
        };
    },

    computed: {
        buttonLabel() {
            return this.item?.is_recurring
                ? "Cancel recurring payment"
                : "Enable recurring payment";
        },
        buttonIcon() {
            return this.item?.is_recurring ? "mdi-cart-off" : "mdi-autorenew";
        },
        buttonColor() {
            return this.item?.is_recurring ? "negative" : "positive";
        },
        dialogTitle() {
            return this.item?.is_recurring
                ? "Cancel recurring payment"
                : "Enable recurring payment";
        },
        dialogMessage() {
            return this.item?.is_recurring
                ? "Are you sure you want to cancel the recurring payment?"
                : "Do you want to enable recurring payment for this item?";
        },
    },

    methods: {
        async recurringPayment() {
            try {
                const res = await this.$server.put(this.item.links.recurring);

                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: res.data.message,
                        timeout: 5000,
                    });
                    this.$emit("success");
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
    },
};
</script>
