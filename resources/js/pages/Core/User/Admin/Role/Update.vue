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
            {{ __("Edit role") }}
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
                        <div class="text-h6">{{ __("Update Role") }}</div>
                        <div class="text-caption">
                            {{ __("Modify role details") }}
                        </div>
                    </q-card-section>
                </div>

                <q-card-section class="q-pt-lg">
                    <div
                        class="text-subtitle1 text-weight-medium q-mb-md text-grey-8"
                    >
                        {{ __("Editing") }}:
                        <span class="text-blue-8">"{{ form.name }}"</span>
                    </div>

                    <div class="q-gutter-y-md">
                        <q-input
                            v-model="form.name"
                            :label="__('Role Name')"
                            outlined
                            color="primary"
                            :error="!!errors.name"
                            class="input-field"
                            :loading="loading"
                            :readonly="system"
                            :hint="
                                system
                                    ? __('System role name cannot be modified')
                                    : ''
                            "
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
                            :label="__('Description')"
                            outlined
                            color="primary"
                            :error="!!errors.description"
                            type="textarea"
                            rows="3"
                            class="input-field"
                            :loading="loading"
                            :hint="
                                __(
                                    'Describe the purpose and permissions of this role'
                                )
                            "
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-text-box-outline" />
                            </template>
                            <template v-slot:error>
                                <v-error :error="errors.description" />
                            </template>
                        </q-input>

                        <div
                            v-if="system"
                            class="bg-orange-1 text-orange-8 rounded-borders q-pa-md"
                        >
                            <div class="row items-center">
                                <q-icon
                                    name="mdi-shield-check"
                                    size="sm"
                                    class="q-mr-sm"
                                />
                                <span class="text-caption">
                                    {{
                                        __(
                                            "This is a system role. Some properties cannot be modified."
                                        )
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="right" class="q-pa-md">
                    <q-btn
                        flat
                        color="grey-7"
                        :label="__('Cancel')"
                        @click="close"
                        class="q-mr-sm"
                        icon="mdi-close-circle"
                        :disable="loading"
                    />
                    <q-btn
                        color="primary"
                        :label="__('Update Role')"
                        @click="updateRole"
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
            system: false,
        };
    },

    methods: {
        close() {
            this.form = {};
            this.errors = {};
            this.dialog = false;
            this.loading = false;
            this.system = false;
        },

        open(item) {
            const { system, ...form } = item;
            this.form = { ...form };
            this.system = system;
            this.errors = {};
            this.dialog = true;
        },

        async updateRole() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    this.$q.notify({
                        type: "positive",
                        message: this.__("Role updated successfully"),
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.$emit("updated", true);
                    this.errors = {};
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
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
