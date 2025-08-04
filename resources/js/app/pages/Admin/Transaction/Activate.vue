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
        <q-btn outline icon="mdi-power" color="positive" @click="dialog = true">
            <q-tooltip transition-show="rotate" transition-hide="rotate">
                Activate transaction
            </q-tooltip>
        </q-btn>
        <q-dialog v-model="dialog">
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">Activate</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section>
                    Are you sure you want to activate this transaction?
                </q-card-section>
                <q-card-actions class="flex">
                    <q-btn
                        outline
                        label="Accept"
                        color="positive"
                        @click="activate"
                    />
                    <q-space></q-space>
                    <q-btn
                        outline
                        label="Cancel"
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
                        message: "Transaction has been activated",
                    });
                    this.$emit("updated");
                }
            } catch (error) {
                if (error?.response?.status == 402) {
                    this.$q.notify({
                        type: "negative",
                        message: `This transaction can be activated : ${error.response.data.message}`,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
