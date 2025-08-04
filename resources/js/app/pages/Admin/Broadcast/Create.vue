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
        <q-card class="q-pa-md full-width">
            <q-card-section>
                <div class="text-h6">Add new channel</div>
            </q-card-section>

            <q-card-section>
                <div class="q-gutter-md">
                    <q-input outlined v-model="form.name" dense="dense" label="Name" :error="!!errors.name">
                        <template v-slot:error>
                            <v-error :error="errors.name"></v-error>
                        </template>
                    </q-input>
                    <q-input outlined type="textarea" v-model="form.description" dense="dense" label="description"
                        :error="!!errors.description">
                        <template v-slot:error>
                            <v-error :error="errors.description"></v-error>
                        </template>
                    </q-input>

                    <q-item tag="label" v-ripple>
                        <q-item-section avatar>
                            <q-checkbox v-model="form.system" val="orange" color="orange" :error="!!errors.system">
                                <template v-slot:error>
                                    <v-error :error="errors.system" />
                                </template>
                            </q-checkbox>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>System</q-item-label>
                            <q-item-label caption>
                                This action cannot be undone.
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn label="Save" outline icon="mdi-content-save-alert" color="positive" @click="create" />
                <q-btn label="Close" outline icon="mdi-close-circle" color="secondary" @click="close" />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-btn outline round color="positive" icon="mdi-plus" @click="open">
        <q-tooltip> Add new channel</q-tooltip>
    </q-btn>
</template>
<script>
export default {
    emits: ["created"],

    data() {
        return {
            form: {},
            errors: {},
            dialog: false,
        };
    },

    methods: {
        /**
         *  reset keys when the windows is closed
         */
        close() {
            this.dialog = false;
            this.clean()

        },

        open() {
            this.dialog = true;
            this.clean();
        },

        clean() {
            this.form.name = null;
            this.form.description = null;
            this.form.system = false;
            this.errors = {}
        },

        /**
         * Create a new user in the system
         *
         */
        async create() {
            try {
                const res = await this.$server.post(
                    "/admin/broadcasts",
                    this.form
                );

                if (res.status == 201) {
                    this.clean()
                    this.$emit("created", true);
                    this.$q.notify({
                        type: "positive",
                        message: "A new channel has been created",
                        timeout: 3000,
                    });
                    this.dialog = false;
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.data.errors &&
                    e.response.status == 422
                ) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
