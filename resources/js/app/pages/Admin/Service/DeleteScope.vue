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
    <q-btn
        outline
        round
        color="negative"
        @click="dialog = true"
        icon="mdi-trash-can-outline"
    >
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Delete scope
        </q-tooltip>
    </q-btn>

    <q-dialog v-model="dialog">
        <q-card>
            <q-card-section>
                <div class="text-h6">Revoke scope</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
                Are you sure you want to delete this role?
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    outline
                    label="Confirm"
                    color="primary"
                    @click="destroy"
                />

                <q-btn
                    outline
                    label="Cancel"
                    color="negative"
                    @click="dialog = false"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
<script>
export default {
    emits: ["deleted"],

    props: {
        scope: {
            required: false,
            type: Object,
        },
    },

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        async destroy() {
            try {
                const res = await this.$server.delete(this.scope.links.revoke);

                if (res.status == 200) {
                    this.$emit("deleted");
                    this.dialog = false;
                    this.$q.notify({
                        type: "positive",
                        message: "Scope has been deleted successfully",
                        timeout: 3000,
                    });
                }
            } catch (error) {}
        },
    },
};
</script>
