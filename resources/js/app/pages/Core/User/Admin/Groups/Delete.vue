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
        color="negative"
        @click="dialog = true"
        icon="mdi-delete-outline"
        size="sm"
        class="q-mr-xs"
    >
        <q-tooltip
            transition-show="scale"
            transition-hide="scale"
            class="bg-negative"
        >
            {{ __("Delete group") }}
        </q-tooltip>
    </q-btn>

    <q-dialog
        v-model="dialog"
        persistent
        transition-show="jump-up"
        transition-hide="jump-down"
    >
        <div class="dialog-backdrop flex flex-center">
            <q-card class="delete-dialog-card shadow-15">
                <div class="dialog-header bg-negative text-white">
                    <q-card-section class="text-center">
                        <q-icon
                            name="mdi-alert-circle-outline"
                            size="lg"
                            class="q-mb-sm"
                        />
                        <div class="text-h6">{{ __("Confirm Deletion") }}</div>
                        <div class="text-caption">
                            {{ __("This action cannot be undone") }}
                        </div>
                    </q-card-section>
                </div>

                <q-card-section class="q-pt-lg text-center">
                    <div class="text-body1 q-mb-md">
                        {{ __("Are you sure you want to delete the group") }}
                        <span class="text-weight-bold text-blue-8"
                            >"{{ item.name }}"</span
                        >?
                    </div>

                    <div class="flex justify-center q-gutter-sm q-mb-md">
                        <q-chip
                            color="blue-1"
                            text-color="blue-8"
                            icon="mdi-identifier"
                            class="q-pa-sm"
                        >
                            {{ __("ID") }}: {{ item.id }}
                        </q-chip>
                    </div>

                    <div class="bg-red-1 text-red-8 rounded-borders q-pa-md">
                        <div class="row items-center">
                            <q-icon
                                name="mdi-alert"
                                size="sm"
                                class="q-mr-sm"
                            />
                            <span class="text-caption">
                                {{
                                    __(
                                        "Warning: This will permanently remove the group and all associated data."
                                    )
                                }}
                            </span>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="center" class="q-pa-md">
                    <q-btn
                        flat
                        color="grey-7"
                        :label="__('Cancel')"
                        @click="dialog = false"
                        class="q-mr-md"
                        icon="mdi-close-circle"
                    />
                    <q-btn
                        color="negative"
                        :label="__('Delete Group')"
                        @click="destroy"
                        icon="mdi-delete-forever"
                        class="q-px-md"
                    />
                </q-card-actions>
            </q-card>
        </div>
    </q-dialog>
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
            loading: false,
        };
    },

    methods: {
        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "Group deleted successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                    });
                    this.$emit("deleted", true);
                    this.dialog = false;
                }
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: "Error deleting group",
                    position: "top",
                    icon: "mdi-alert-circle",
                });
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

.delete-dialog-card {
    width: 100%;
    max-width: 450px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
