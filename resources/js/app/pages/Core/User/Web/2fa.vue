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
        <q-page padding>
            <div class="row q-col-gutter-md">
                <div class="col q-ma-sm">
                    <q-card bordered>
                        <q-card-section>
                            <q-toolbar class="q-ma-sm">
                                <q-toolbar-title class="text-grey-7">
                                    Two Factor Authentication
                                </q-toolbar-title>
                                <q-btn
                                    icon="mdi-check-decagram-outline"
                                    :color="user.m2fa ? 'positive' : 'negative'"
                                >
                                    <q-tooltip
                                        transition-show="rotate"
                                        transition-hide="rotate"
                                    >
                                        {{
                                            user.m2fa
                                                ? "2FA Activated"
                                                : "2FA inactive"
                                        }}
                                    </q-tooltip>
                                </q-btn>
                            </q-toolbar>
                        </q-card-section>
                        <q-card-section>
                            <q-input
                                v-model="token"
                                label="Insert Code ..."
                                outlined
                                dense
                            />
                            <v-error :error="errors.token" />
                        </q-card-section>

                        <q-card-actions align="between">
                            <q-btn
                                @click="requestCode"
                                label="Request token"
                                color="positive"
                                outline
                            />
                            <q-btn
                                :label="user.m2fa ? 'Deactivate' : 'Activate'"
                                :color="user.m2fa ? 'negative' : 'positive'"
                                @click="activateFactor"
                                outline
                                color="negative"
                            >
                                <q-tooltip
                                    transition-show="rotate"
                                    transition-hide="rotate"
                                >
                                    {{
                                        user.m2fa
                                            ? "2FA Activated"
                                            : "2FA inactive"
                                    }}
                                </q-tooltip>
                            </q-btn>
                        </q-card-actions>
                    </q-card>
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
        };
    },
    mounted() {
        this.user = this.$page.props.user;
    },
    methods: {
        popup(message, type = "positive") {
            if (message) {
                this.$q.notify({
                    message,
                    type,
                });
            }
        },
        async requestCode() {
            try {
                const res = await this.$server.post(
                    this.$page.props.routes['f2a_authorize']
                );
                if (res.status === 201) {
                    this.popup(res.data.message);
                    this.errors = {};
                }
            } catch (err) {
                if (err.response) {
                    this.popup(err.response.data.message, "warning");
                }
            }
        },

        async activateFactor() {
            try {
                const res = await this.$server.post(
                    this.$page.props.routes['f2a_activate'],
                    {
                        token: this.token,
                    }
                );
                if (res.status === 201) {
                    this.token = "";
                    this.errors = {};
                    this.popup("2FA has been activated successfully");
                    window.location.reload();
                }
            } catch (err) {
                if (err.response && err.response.status == 422) {
                    this.errors = err.response.data.errors;
                }
            }
        },
    },
};
</script>
