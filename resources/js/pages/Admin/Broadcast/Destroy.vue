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
    <q-dialog v-model="dialog" persistent>
        <q-card class="w-100">
            <q-card-section>
                <div class="text-h6">{{ __("Delete channel") }}</div>
            </q-card-section>

            <q-card-section>
                {{
                    __(
                        "This channel will be removed. This action cannot be undone. Are you sure you want to proceed?"
                    )
                }}
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    outline
                    :label="__('Save')"
                    icon="mdi-content-save-alert"
                    color="positive"
                    @click="destroy"
                />
                <q-btn
                    outline
                    :label="__('Close')"
                    icon="mdi-close-circle"
                    color="secondary"
                    @click="dialog = false"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-btn
        outline
        round
        color="red"
        icon="mdi-delete-circle-outline"
        @click="dialog = true"
    >
        <q-tooltip>{{ __("Delete channel") }}</q-tooltip>
    </q-btn>
</template>
<script>
export default {
    emits: ["deleted"],

    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            errors: {},
            dialog: false,
        };
    },

    methods: {
        /**
         * Create a new user in the system
         *
         */
        async destroy(isActive) {
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.errors = {};
                    this.$emit("deleted", true);
                    this.$q.notify({
                        type: "positive",
                        message: this.__(
                            "The channel has been deleted successfully"
                        ),
                        timeout: 3000,
                    });
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
                isActive.value = false;
            }
        },
    },
};
</script>
