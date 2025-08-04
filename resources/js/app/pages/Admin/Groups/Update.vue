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
    <q-btn round outline color="positive" @click="open(item)" icon="mdi-pencil">
        <q-tooltip transition-show="rotate" transition-hide="rotate">
            Update group
        </q-tooltip>
    </q-btn>

    <q-dialog v-model="dialog" persistent transition-show="scale" transition-hide="scale">
        <q-card class="q-pa-md full-width">
            <q-card-section class="text-center">
                <h6 class="text-gray-500">Update group</h6>
            </q-card-section>
            <q-card-section>
                <q-input v-model="form.description" label="Description" dense="dense" :error="!!errors.redirect"
                    type="textarea">
                    <template v-slot:error>
                        <v-error :error="errors.redirect"></v-error>
                    </template>
                </q-input>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn outline color="positive" label="Accept" @click="updateClient" />

                <q-btn outline color="secondary" label="Close" @click="close" />
            </q-card-actions>
        </q-card>
    </q-dialog>
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
            errors: {
                description: "",
            },
            form: {},
            dialog: false,
        };
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
        },

        open(item) {
            this.form = { ...item };
            this.errors = {};
            this.dialog = true;
        },

        async updateClient() {
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 201) {
                    this.$emit("updated", true);
                    this.errors = {};
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
