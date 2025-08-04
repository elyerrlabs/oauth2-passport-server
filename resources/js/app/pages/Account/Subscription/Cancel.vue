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
-->
<template>
    <div>
        <q-btn
            outline
            label="Cancel payment"
            icon="mdi-cart-remove"
            color="negative"
            size="sm"
            class="q-mt-sm full-width"
            @click="dialog = true"
        />
        <q-dialog v-model="dialog">
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">Cancel operation</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
                </q-card-section>

                <q-card-section>
                    Are you sure you want to cancel the process to payment?
                </q-card-section>
                <q-card-actions class="flex">
                    <q-btn
                        outline
                        label="Accept"
                        color="positive"
                        @click="cancel"
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
    emits: ["success"],

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
        async cancel() {
            try {
                const res = await this.$server.put(this.item.links.cancel);

                if (res.status == 200) {
                    this.$emit("success");
                }
            } catch (error) {
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
