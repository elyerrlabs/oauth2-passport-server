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
    <div class="row q-gutter-xs">    
        <q-btn
            :color="item.disabled ? 'positive' : 'negative'"
            outline
            round
            :icon="item.disabled ? 'mdi-account-check' : 'mdi-account-cancel'"
            @click="open"
            class="status-toggle-btn"
        >
            <q-tooltip
                transition-show="scale"
                transition-hide="scale"
                :class="item.disabled ? 'bg-positive' : 'bg-negative'"
            >
                {{ item.disabled ? "Enable this user" : "Disable this user" }}
            </q-tooltip>
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="jump-up"
            transition-hide="jump-down"
        >
            <div class="dialog-backdrop flex flex-center">
                <q-card class="status-dialog-card shadow-15">
                    <div
                        :class="
                            [
                                'dialog-header',
                                item.disabled ? 'bg-positive' : 'bg-negative',
                            ] + ' text-white'
                        "
                    >
                        <q-card-section class="text-center">
                            <q-icon
                                :name="
                                    item.disabled
                                        ? 'mdi-account-check-outline'
                                        : 'mdi-account-cancel-outline'
                                "
                                size="lg"
                                class="q-mb-sm"
                            />
                            <div class="text-h5">
                                {{
                                    item.disabled
                                        ? "Enable User"
                                        : "Disable User"
                                }}
                            </div>
                            <div class="text-caption">
                                {{
                                    item.disabled
                                        ? "Activate user account"
                                        : "Temporarily disable user account"
                                }}
                            </div>
                        </q-card-section>
                    </div>

                    <q-card-section class="q-pt-lg text-center">
                        <div class="user-info q-mb-md">
                            <q-avatar
                                size="lg"
                                color="blue"
                                text-color="white"
                                class="q-mb-sm"
                            >
                                {{
                                    item.name
                                        ? item.name.charAt(0).toUpperCase()
                                        : "U"
                                }}
                            </q-avatar>
                            <div class="text-h6 text-grey-8">
                                {{ item.name }} {{ item.last_name }}
                            </div>
                            <div class="text-caption text-grey-6">
                                {{ item.email }}
                            </div>
                        </div>

                        <div
                            class="confirmation-message bg-grey-2 rounded-borders q-pa-md"
                        >
                            <div class="row items-center justify-center">
                                <q-icon
                                    :name="
                                        item.disabled
                                            ? 'mdi-information-outline'
                                            : 'mdi-alert-outline'
                                    "
                                    :color="
                                        item.disabled ? 'positive' : 'negative'
                                    "
                                    size="md"
                                    class="q-mr-sm"
                                />
                                <span class="text-body1">
                                    {{
                                        item.disabled
                                            ? "This user account will be activated and granted access to the system."
                                            : "This user account will be disabled and access will be temporarily restricted."
                                    }}
                                </span>
                            </div>
                        </div>

                        <div
                            v-if="!item.disabled"
                            class="warning-message bg-orange-1 text-orange-8 rounded-borders q-pa-md q-mt-md"
                        >
                            <div class="row items-center">
                                <q-icon
                                    name="mdi-alert"
                                    size="sm"
                                    class="q-mr-sm"
                                />
                                <span class="text-caption">
                                    The user will not be able to log in until
                                    the account is re-enabled.
                                </span>
                            </div>
                        </div>
                    </q-card-section>

                    <q-card-actions align="center" class="q-pa-lg">
                        <q-btn
                            label="Cancel"
                            color="grey-7"
                            @click="dialog = false"
                            flat
                            class="q-mr-md"
                            icon="mdi-close-circle"
                        />
                        <q-btn
                            :label="
                                item.disabled ? 'Enable User' : 'Disable User'
                            "
                            :color="item.disabled ? 'positive' : 'negative'"
                            @click="action(item)"
                            :icon="
                                item.disabled
                                    ? 'mdi-check-circle'
                                    : 'mdi-cancel'
                            "
                            unelevated
                            :loading="loading"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </q-dialog>
    </div>
</template>

<script>
export default {
    props: ["item"],

    emits: ["updated"],

    data() {
        return {
            dialog: false,
            loading: false,
        };
    },

    methods: {
        open() {
            this.dialog = true;
        },

        action(item) {
            if (item.disabled) {
                this.enable();
            } else {
                this.disable();
            }
        },

        async disable() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.disable);
                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "User disabled successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.$emit("updated", true);
                }
            } catch (e) {
                if (e.response && e.response.data && e.response.data.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: "Error disabling user",
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
                this.dialog = false;
            }
        },

        async enable() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.item.links.enable);
                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "User enabled successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.$emit("updated", true);
                }
            } catch (e) {
                if (e.response && e.response.data && e.response.data.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                } else {
                    this.$q.notify({
                        type: "negative",
                        message: "Error enabling user",
                        position: "top",
                        icon: "mdi-alert-circle",
                        timeout: 3000,
                    });
                }
            } finally {
                this.loading = false;
                this.dialog = false;
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

.status-dialog-card {
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.user-info {
    padding: 1rem;
}

.confirmation-message {
    border: 1px solid #e0e0e0;
}

.warning-message {
    border: 1px solid #ff9800;
}

.status-toggle-btn {
    transition: transform 0.2s ease;
}

.status-toggle-btn:hover {
    transform: scale(1.1);
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
