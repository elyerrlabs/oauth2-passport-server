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
    <q-dialog v-model="dialog" persistent>
        <q-card>
            <q-card-section class="row items-center">
                <span class="q-ml-sm">
                    {{
                        item.disabled
                            ? "This user will be activated"
                            : "This user will be disabled"
                    }}
                </span>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn outline label="Cancel" color="negative" v-close-popup />
                <q-btn
                    outline
                    label="Accept"
                    @click="action(item)"
                    color="primary"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        :color="item.disabled ? 'negative' : 'positive'"
        outline
        round
        icon="mdi-power"
        @click="open"
    >
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            {{ item.disabled ? "Enable this user" : "Disable this user" }}
        </q-tooltip>
    </q-btn>
</template>
<script>
export default {
    props: ["item"],

    emits: ["updated"],

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        open() {
            this.dialog = true;
        },

        action(item) {
            if (item.disabled) {
                this.enable();
            } else {
                this.disable();
            }
        },

        async disable() {
            try {
                const res = await this.$server.delete(this.item.links.disable);
                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "User disabled successfully",
                        timeout: 3000,
                    });

                    this.$emit("updated", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },

        async enable() {
            try {
                const res = await this.$server.get(this.item.links.enable);
                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "User enabled successfully",
                        timeout: 3000,
                    });

                    this.$emit("updated", true);
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status != 422 &&
                    e.response.data &&
                    e.response.data.message
                ) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                    });
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
