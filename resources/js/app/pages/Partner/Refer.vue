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
    <v-partner-layout>
        <q-page padding class="q-pa-md">
            <q-card class="q-pa-md shadow-2 rounded-borders">
                <q-card-section>
                    <div class="text-h6">Generate Referral Link</div>
                </q-card-section>

                <q-card-actions class="flex q-pa-md">
                    <span class="text-grey-5">
                        <strong>Commission rate : </strong>
                        {{ partner?.commission_rate }} %
                    </span>
                    <q-space />
                    <q-btn
                        label="Generate"
                        color="primary"
                        @click="generateReferralLink"
                    />
                </q-card-actions>
                <q-separator />
                <q-card-section>
                    <div class="q-mb-md" v-if="partner?.referral_links">
                        <q-input
                            readonly
                            v-model="partner.referral_links"
                            dense
                        >
                            <template v-slot:append>
                                <q-btn
                                    flat
                                    icon="content_copy"
                                    @click="
                                        copyToClipboard(partner.referral_links)
                                    "
                                />
                            </template>
                        </q-input>
                    </div>

                    <q-banner
                        dense
                        class="bg-green-2 text-green-9"
                        v-if="copied"
                    >
                        Link copied to clipboard!
                    </q-banner>
                </q-card-section>
            </q-card>
        </q-page>
    </v-partner-layout>
</template>

<script>
export default {
    data() {
        return {
            partner: {
                referral_links: null,
            },
            copied: false,
        };
    },

    mounted() {
        this.generateReferralLink();
    },

    methods: {
        async generateReferralLink() {
            try {
                const res = await this.$server.post(this.$page.props.route);
                if (res.status == 201) {
                    this.partner = res.data.data;
                }
            } catch (error) {}
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.copied = true;
                setTimeout(() => (this.copied = false), 2000);
            });
        },
    },
};
</script>
