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
    <q-dialog v-model="dialog">
        <q-card>
            <q-card-section class="row items-center">
                <q-icon name="mdi-alert-circle" color="red" size="md" />
                <span class="q-ml-sm"
                    >Are you sure you want to remove this token with name</span
                >
                <q-chip color="blue-6" text-color="white" class="q-ml-sm">
                    {{ item.name }}
                </q-chip>
                ?
            </q-card-section>

            <q-card-actions align="right">
                <q-btn
                    label="Close"
                    color="negative"
                    flat
                    @click="dialog = false"
                />
                <q-btn label="Agree" color="primary" @click="destroy" />
            </q-card-actions>
        </q-card>
    </q-dialog>

    <q-btn
        color="negative"
        icon="mdi-delete-outline"
        round
        flat
        @click="dialog = true"
    />
</template>

<script>
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
        };
    },

    methods: {
        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.destroy);
                if (res.status === 200) {
                    this.$emit("deleted", true);
                    this.dialog = false;
                }
            } catch (e) {}
        },
    },
};
</script>
