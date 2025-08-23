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
        round
        flat
        color="primary"
        @click="open(item)"
        icon="mdi-pencil"
        size="sm"
        class="q-mr-xs"
    >
        <q-tooltip
            transition-show="scale"
            transition-hide="scale"
            class="bg-primary"
        >
            Edit group
        </q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        persistent
        transition-show="jump-up"
        transition-hide="jump-down"
    >
        <div class="dialog-backdrop flex flex-center">
            <q-card class="edit-dialog-card shadow-15">
                <div class="dialog-header bg-primary text-white">
                    <q-card-section class="text-center">
                        <q-icon
                            name="mdi-pencil-box-outline"
                            size="lg"
                            class="q-mb-sm"
                        />
                        <div class="text-h6">Update Group</div>
                        <div class="text-caption">Modify group details</div>
                    </q-card-section>
                </div>

                <q-card-section class="q-pt-lg">
                    <div
                        class="text-subtitle1 text-weight-medium q-mb-md text-grey-8"
                    >
                        Editing:
                        <span class="text-blue-8">"{{ form.name }}"</span>
                    </div>

                    <div class="q-gutter-y-md">
                        <q-input
                            v-model="form.name"
                            label="Group Name"
                            dense
                            outlined
                            color="primary"
                            :error="!!errors.name"
                            class="input-field"
                            :loading="loading"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-tag-outline" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.name"></v-error>
                            </template>
                        </q-input>

                        <q-input
                            v-model="form.description"
                            label="Description"
                            dense
                            outlined
                            color="primary"
                            :error="!!errors.description"
                            type="textarea"
                            rows="3"
                            class="input-field"
                            :loading="loading"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-text-box-outline" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.description" />
                            </template>
                        </q-input>
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="q-pa-md">
                    <q-btn
                        flat
                        color="grey-7"
                        label="Cancel"
                        @click="close"
                        class="q-mr-sm"
                        icon="mdi-close-circle"
                        :disable="loading"
                    />
                    <q-btn
                        color="primary"
                        label="Update Group"
                        @click="updateGroup"
                        :loading="loading"
                        icon="mdi-content-save"
                    />
                </q-card-actions>
            </q-card>
        </div>
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
            errors: {},
            form: {},
            dialog: false,
            loading: false,
        };
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
            this.loading = false;
        },

        open(item) {
            this.form = { ...item };
            this.errors = {};
            this.dialog = true;
        },

        async updateGroup() {
            this.loading = true;
            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "Group updated successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                    });
                    this.$emit("updated", true);
                    this.errors = {};
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        type: "negative",
                        message: "Please check the form for errors",
                        position: "top",
                        icon: "mdi-alert-circle",
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: "Error updating group",
                        position: "top",
                        icon: "mdi-alert-circle",
                    });
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.dialog-backdrop {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.edit-dialog-card {
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.input-field {
    transition: all 0.3s ease;
}

.input-field:focus-within {
    transform: translateY(-2px);
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
