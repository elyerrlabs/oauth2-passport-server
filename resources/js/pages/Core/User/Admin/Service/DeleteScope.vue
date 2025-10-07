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
        size="sm"
        class="q-mr-xs"
    >
        <q-tooltip
            transition-show="scale"
            transition-hide="scale"
            class="bg-negative"
        >
            Delete scope
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
                            name="mdi-lock-remove"
                            size="lg"
                            class="q-mb-sm"
                        />
                        <div class="text-h6">Revoke Scope</div>
                        <div class="text-caption">This action is permanent</div>
                    </q-card-section>
                </div>

                <q-card-section class="q-pt-lg text-center">
                    <div class="text-body1 q-mb-md">
                        Are you sure you want to revoke the scope for role
                        <span class="text-weight-bold text-blue-8"
                            >"{{ __(scope.role?.name) }}"</span
                        >?
                    </div>

                    <div class="flex justify-center q-gutter-sm q-mb-md">
                        <q-chip
                            color="blue-1"
                            text-color="blue-8"
                            icon="mdi-identifier"
                            class="q-pa-sm"
                        >
                            Role ID: {{ scope.role?.id }}
                        </q-chip>
                    </div>

                    <div
                        class="permissions-summary bg-grey-2 rounded-borders q-pa-md"
                    >
                        <div class="text-caption text-weight-medium q-mb-xs">
                            Current Permissions:
                        </div>
                        <div class="row justify-center q-gutter-sm">
                            <q-badge
                                v-if="scope.api_key"
                                color="blue"
                                icon="mdi-key"
                                class="q-px-sm"
                            >
                                API Key Access
                            </q-badge>
                            <q-badge
                                v-if="scope.active"
                                color="green"
                                icon="mdi-check-circle"
                                class="q-px-sm"
                            >
                                Active
                            </q-badge>
                            <q-badge
                                v-if="scope.public"
                                color="orange"
                                icon="mdi-earth"
                                class="q-px-sm"
                            >
                                Public
                            </q-badge>
                            <q-badge
                                v-if="
                                    !scope.api_key &&
                                    !scope.active &&
                                    !scope.public
                                "
                                color="grey"
                                class="q-px-sm"
                            >
                                No permissions set
                            </q-badge>
                        </div>
                    </div>

                    <div
                        class="bg-red-1 text-red-8 rounded-borders q-pa-md q-mt-md"
                    >
                        <div class="row items-center">
                            <q-icon
                                name="mdi-alert"
                                size="sm"
                                class="q-mr-sm"
                            />
                            <span class="text-caption">
                                Warning: This will permanently remove access
                                permissions. Users with this role will lose
                                access to the associated service.
                            </span>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="center" class="q-pa-md">
                    <q-btn
                        flat
                        color="grey-7"
                        label="Cancel"
                        @click="dialog = false"
                        class="q-mr-md"
                        icon="mdi-close-circle"
                        :disable="loading"
                    />
                    <q-btn
                        color="negative"
                        label="Revoke Scope"
                        @click="destroy"
                        icon="mdi-trash-can-outline"
                        class="q-px-md"
                        :loading="loading"
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
        scope: {
            required: true,
            type: Object,
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
                const res = await this.$server.delete(this.scope.links.revoke);

                if (res.status == 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "Scope revoked successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.$emit("deleted");
                    this.dialog = false;
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
    max-width: 500px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.permissions-summary {
    border: 1px solid #e0e0e0;
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
