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
            outline
            round
            icon="mdi-shield-account"
            color="primary"
            @click="openDialog"
            class="q-mr-xs"
        >
            <q-tooltip
                transition-show="scale"
                transition-hide="scale"
                class="bg-primary text-white"
            >
                {{ __("View assigned scopes") }}
            </q-tooltip>
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            full-width
            transition-show="jump-up"
            transition-hide="jump-down"
        >
            <div class="dialog-backdrop">
                <q-card class="scopes-view-dialog-card shadow-15">
                    <!-- Header -->
                    <div class="dialog-header bg-primary text-white">
                        <q-card-section>
                            <div class="row items-center justify-between">
                                <div>
                                    <div class="text-h4 text-weight-bold">
                                        {{ __("Assigned Access Scopes") }}
                                    </div>
                                    <div class="text-subtitle1">
                                        {{ __("For user:") }}
                                        <span class="text-weight-bold"
                                            >{{ item.name }}
                                            {{ item.last_name }}</span
                                        >
                                    </div>
                                    <div class="text-caption opacity-80">
                                        {{ item.email }}
                                    </div>
                                </div>
                                <q-btn
                                    flat
                                    round
                                    icon="mdi-close"
                                    @click="dialog = false"
                                    size="md"
                                    class="close-btn"
                                />
                            </div>
                        </q-card-section>
                    </div>

                    <q-separator />

                    <!-- Body -->
                    <q-card-section class="q-pt-xl">
                        <!-- Loading State -->
                        <div class="text-center q-pa-xl" v-if="loading">
                            <q-spinner-gears size="xl" color="primary" />
                            <div class="text-h6 q-mt-md text-primary">
                                {{ __("Loading assigned scopes...") }}
                            </div>
                            <div class="text-caption text-grey-6">
                                {{
                                    __(
                                        "Please wait while we fetch the user's permissions"
                                    )
                                }}
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-else-if="!loading && user_roles.length === 0"
                            class="text-center q-pa-xl"
                        >
                            <q-icon
                                name="mdi-shield-off"
                                size="xl"
                                color="grey-4"
                            />
                            <div class="text-h6 text-grey-6 q-mt-md">
                                {{ __("No scopes assigned") }}
                            </div>
                            <div class="text-grey-5">
                                {{
                                    __(
                                        "This user doesn't have any access permissions yet"
                                    )
                                }}
                            </div>
                        </div>

                        <!-- Scopes Content -->
                        <div v-else class="scopes-content">
                            <div
                                v-for="[groupName, services] in groupedRoles"
                                :key="groupName"
                                class="group-section q-mb-xl"
                            >
                                <div
                                    class="group-header bg-blue-1 text-blue-8 q-pa-md rounded-borders"
                                >
                                    <q-icon
                                        name="mdi-account-group"
                                        size="md"
                                        class="q-mr-sm"
                                    />
                                    <span class="text-h6 text-weight-bold">{{
                                        groupName
                                    }}</span>
                                </div>

                                <div
                                    v-for="[
                                        serviceName,
                                        roles,
                                    ] in Object.entries(services)"
                                    :key="serviceName"
                                    class="service-section q-mt-md"
                                >
                                    <div
                                        class="service-header bg-grey-2 q-pa-sm rounded-borders"
                                    >
                                        <q-icon
                                            name="mdi-cog"
                                            size="sm"
                                            class="q-mr-sm"
                                        />
                                        <span
                                            class="text-subtitle1 text-weight-medium"
                                            >{{ serviceName }}</span
                                        >
                                    </div>

                                    <div
                                        class="roles-grid row q-col-gutter-md q-mt-sm"
                                    >
                                        <div
                                            v-for="(item, index) in roles"
                                            :key="index"
                                            class="col-12 col-md-6"
                                        >
                                            <q-card
                                                class="role-card shadow-2"
                                                bordered
                                            >
                                                <q-card-section class="q-pa-md">
                                                    <div
                                                        class="row items-center justify-between"
                                                    >
                                                        <div class="col-grow">
                                                            <div
                                                                class="text-weight-bold text-primary"
                                                            >
                                                                <q-icon
                                                                    name="mdi-key"
                                                                    class="q-mr-xs"
                                                                />
                                                                {{
                                                                    item.scope
                                                                        .role
                                                                        .name
                                                                }}
                                                            </div>
                                                            <div
                                                                class="text-caption text-grey-7 q-mt-xs"
                                                            >
                                                                {{
                                                                    item.scope
                                                                        .role
                                                                        .description ||
                                                                    __(
                                                                        "No description available"
                                                                    )
                                                                }}
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <q-btn
                                                                round
                                                                flat
                                                                icon="mdi-delete-outline"
                                                                color="negative"
                                                                @click="
                                                                    confirmAction(
                                                                        item
                                                                    )
                                                                "
                                                                size="sm"
                                                                class="delete-btn"
                                                            >
                                                                <q-tooltip>{{
                                                                    __(
                                                                        "Revoke this permission"
                                                                    )
                                                                }}</q-tooltip>
                                                            </q-btn>
                                                        </div>
                                                    </div>

                                                    <!-- Permission Badges -->
                                                    <div
                                                        class="row q-gutter-xs q-mt-md"
                                                    >
                                                        <q-badge
                                                            :color="
                                                                item.scope
                                                                    .api_key
                                                                    ? 'green'
                                                                    : 'grey'
                                                            "
                                                            :icon="
                                                                item.scope
                                                                    .api_key
                                                                    ? 'mdi-key-check'
                                                                    : 'mdi-key-remove'
                                                            "
                                                            class="q-pa-xs"
                                                        >
                                                            {{ __("API Key:") }}
                                                            {{
                                                                item.scope
                                                                    .api_key
                                                                    ? __("Yes")
                                                                    : __("No")
                                                            }}
                                                        </q-badge>
                                                        <q-badge
                                                            :color="
                                                                item.scope
                                                                    .active
                                                                    ? 'blue'
                                                                    : 'grey'
                                                            "
                                                            :icon="
                                                                item.scope
                                                                    .active
                                                                    ? 'mdi-check-circle'
                                                                    : 'mdi-close-circle'
                                                            "
                                                            class="q-pa-xs"
                                                        >
                                                            {{ __("Active:") }}
                                                            {{
                                                                item.scope
                                                                    .active
                                                                    ? __("Yes")
                                                                    : __("No")
                                                            }}
                                                        </q-badge>
                                                        <q-badge
                                                            :color="
                                                                item.scope
                                                                    .public
                                                                    ? 'orange'
                                                                    : 'grey'
                                                            "
                                                            :icon="
                                                                item.scope
                                                                    .public
                                                                    ? 'mdi-earth'
                                                                    : 'mdi-earth-off'
                                                            "
                                                            class="q-pa-xs"
                                                        >
                                                            {{ __("Public:") }}
                                                            {{
                                                                item.scope
                                                                    .public
                                                                    ? __("Yes")
                                                                    : __("No")
                                                            }}
                                                        </q-badge>
                                                    </div>
                                                </q-card-section>
                                            </q-card>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </q-card-section>

                    <!-- Footer -->
                    <q-card-actions align="right" class="q-pa-lg">
                        <q-btn
                            :label="__('Close')"
                            color="primary"
                            @click="dialog = false"
                            unelevated
                            icon="mdi-close"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </q-dialog>

        <!-- Confirmation Dialog -->
        <q-dialog v-model="confirm" persistent>
            <div class="dialog-backdrop flex flex-center">
                <q-card class="confirm-dialog-card shadow-15">
                    <div class="dialog-header bg-negative text-white">
                        <q-card-section class="text-center">
                            <q-icon
                                name="mdi-alert-circle-outline"
                                size="lg"
                                class="q-mb-sm"
                            />
                            <div class="text-h6">
                                {{ __("Revoke Permission") }}
                            </div>
                            <div class="text-caption">
                                {{ __("This action cannot be undone") }}
                            </div>
                        </q-card-section>
                    </div>

                    <q-card-section class="text-center q-pt-lg">
                        <div class="text-body1 q-mb-md">
                            {{ __("Are you sure you want to revoke the") }}
                            <span class="text-weight-bold text-primary"
                                >"{{ selected_scope.scope?.role?.name }}"</span
                            >
                            {{ __("permission from this user?") }}
                        </div>
                        <div
                            class="bg-red-1 text-red-8 rounded-borders q-pa-md"
                        >
                            <div class="row items-center">
                                <q-icon
                                    name="mdi-alert"
                                    size="sm"
                                    class="q-mr-sm"
                                />
                                <span class="text-caption">{{
                                    __(
                                        "The user will lose access to associated resources"
                                    )
                                }}</span>
                            </div>
                        </div>
                    </q-card-section>

                    <q-card-actions align="center" class="q-pa-lg">
                        <q-btn
                            :label="__('Cancel')"
                            color="grey-7"
                            v-close-popup
                            flat
                            class="q-mr-md"
                            icon="mdi-close-circle"
                        />
                        <q-btn
                            :label="__('Revoke Permission')"
                            color="negative"
                            @click="revoke"
                            unelevated
                            icon="mdi-delete-forever"
                            :loading="revoking"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </q-dialog>
    </div>
</template>

<script>
export default {
    props: {
        item: {
            required: true,
            type: Object,
        },
    },

    data() {
        return {
            user_roles: [],
            dialog: false,
            confirm: false,
            selected_scope: {},
            loading: true,
            revoking: false,
        };
    },

    computed: {
        groupedRoles() {
            const grouped = {};

            for (const item of this.user_roles) {
                const group =
                    item.scope?.service?.group?.name || "Unknown Group";
                const service = item.scope?.service?.name || "Unknown Service";

                if (!grouped[group]) {
                    grouped[group] = {};
                }

                if (!grouped[group][service]) {
                    grouped[group][service] = [];
                }

                grouped[group][service].push(item);
            }

            return Object.entries(grouped);
        },
    },

    methods: {
        openDialog() {
            this.dialog = true;
            this.userRoles();
        },

        confirmAction(item) {
            this.selected_scope = item;
            this.confirm = true;
        },

        async userRoles() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.item.links.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status === 200) {
                    this.user_roles = res.data.data;
                }
            } catch (error) {
                this.$q.notify({
                    type: "negative",
                    message: "Failed to load assigned scopes",
                    position: "top",
                    icon: "mdi-alert-circle",
                    timeout: 3000,
                });
            } finally {
                this.loading = false;
            }
        },

        async revoke() {
            this.revoking = true;
            try {
                const res = await this.$server.put(
                    this.selected_scope.links.revoke
                );

                if (res.status === 200) {
                    this.$q.notify({
                        type: "positive",
                        message: "Permission revoked successfully",
                        position: "top",
                        icon: "mdi-check-circle",
                        timeout: 3000,
                    });
                    this.userRoles();
                    this.confirm = false;
                }
            } catch (error) {
                this.$q.notify({
                    type: "negative",
                    message:
                        error.response?.data?.message ||
                        "Error revoking permission",
                    position: "top",
                    icon: "mdi-alert-circle",
                    timeout: 3000,
                });
            } finally {
                this.revoking = false;
            }
        },
    },
};
</script>

<style scoped>
.dialog-backdrop {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 2rem;
    min-height: 100vh;
}

.scopes-view-dialog-card {
    width: 100%;
    max-width: 1200px;
    border-radius: 12px;
    overflow: hidden;
}

.confirm-dialog-card {
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.group-header {
    border-left: 4px solid #1976d2;
}

.service-header {
    border-left: 3px solid #78909c;
}

.role-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 8px;
}

.role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.delete-btn {
    transition: transform 0.2s ease;
}

.delete-btn:hover {
    transform: scale(1.1);
}

.close-btn {
    transition: transform 0.2s ease;
}

.close-btn:hover {
    transform: rotate(90deg);
}

.opacity-80 {
    opacity: 0.8;
}

.rounded-borders {
    border-radius: 8px;
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
