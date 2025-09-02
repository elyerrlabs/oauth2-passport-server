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
    <div>
        <q-btn
            outline
            icon="mdi-shield-lock-open-outline"
            color="primary"
            @click="open"
            class="q-mr-xs scope-manager-btn"
        >
            <q-tooltip
                transition-show="scale"
                transition-hide="scale"
                class="bg-primary text-white"
            >
                {{ __("Manage access scopes") }}
            </q-tooltip>
            <q-badge
                v-if="scopesCount > 0"
                color="orange"
                floating
                rounded
                class="scope-badge"
            >
                {{ scopesCount }}
            </q-badge>
        </q-btn>

        <q-dialog v-model="dialog" full-width persistent>
            <div class="dialog-backdrop">
                <q-card class="scopes-dialog-card shadow-15">
                    <!-- Header Section -->
                    <div class="dialog-header bg-primary text-white">
                        <q-card-section>
                            <div class="row items-center justify-between">
                                <div class="col-grow">
                                    <div class="text-h4 text-weight-bold">
                                        {{ __("Scope Management") }}
                                    </div>
                                    <div class="text-subtitle1 q-mt-xs">
                                        {{ __("Managing permissions for:") }}
                                        <span class="text-weight-bold">{{
                                            __(service.name)
                                        }}</span>
                                    </div>
                                    <div class="text-caption opacity-80">
                                        <q-icon
                                            name="mdi-account-group"
                                            class="q-mr-xs"
                                        />
                                        {{ __("Group:") }}
                                        {{
                                            __(service.group?.name) ||
                                            __("Not assigned")
                                        }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <v-add-scope
                                        :link="service.links.scopes"
                                        @created="getScopes"
                                        class="add-scope-btn"
                                    />
                                </div>
                            </div>
                        </q-card-section>
                    </div>

                    <q-card-section class="q-pt-xl">
                        <!-- Statistics Overview -->
                        <div
                            class="row q-col-gutter-md q-mb-xl"
                            v-if="scopesCount > 0"
                        >
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <q-card
                                    flat
                                    class="stat-card bg-blue-1 text-blue-8"
                                >
                                    <q-card-section class="text-center">
                                        <div class="text-h5 text-weight-bold">
                                            {{ scopesCount }}
                                        </div>
                                        <div class="text-caption">
                                            {{ __("Total Scopes") }}
                                        </div>
                                        <q-icon
                                            name="mdi-lock"
                                            size="md"
                                            class="q-mt-sm"
                                        />
                                    </q-card-section>
                                </q-card>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <q-card
                                    flat
                                    class="stat-card bg-green-1 text-green-8"
                                >
                                    <q-card-section class="text-center">
                                        <div class="text-h5 text-weight-bold">
                                            {{ activeScopesCount }}
                                        </div>
                                        <div class="text-caption">
                                            {{ __("Active") }}
                                        </div>
                                        <q-icon
                                            name="mdi-check-circle"
                                            size="md"
                                            class="q-mt-sm"
                                        />
                                    </q-card-section>
                                </q-card>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <q-card
                                    flat
                                    class="stat-card bg-orange-1 text-orange-8"
                                >
                                    <q-card-section class="text-center">
                                        <div class="text-h5 text-weight-bold">
                                            {{ apiKeyScopesCount }}
                                        </div>
                                        <div class="text-caption">
                                            {{ __("API Key Access") }}
                                        </div>
                                        <q-icon
                                            name="mdi-key"
                                            size="md"
                                            class="q-mt-sm"
                                        />
                                    </q-card-section>
                                </q-card>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <q-card
                                    flat
                                    class="stat-card bg-purple-1 text-purple-8"
                                >
                                    <q-card-section class="text-center">
                                        <div class="text-h5 text-weight-bold">
                                            {{ publicScopesCount }}
                                        </div>
                                        <div class="text-caption">
                                            {{ __("Public Access") }}
                                        </div>
                                        <q-icon
                                            name="mdi-earth"
                                            size="md"
                                            class="q-mt-sm"
                                        />
                                    </q-card-section>
                                </q-card>
                            </div>
                        </div>

                        <!-- Scopes Grid -->
                        <transition-group
                            name="scope-transition"
                            tag="div"
                            class="row q-col-gutter-lg"
                        >
                            <div
                                v-for="(item, index) in scopes"
                                :key="item.gsr_id"
                                class="col-12 col-md-6 col-lg-4"
                            >
                                <q-card class="scope-card shadow-5" bordered>
                                    <!-- Card Header -->
                                    <q-card-section
                                        class="scope-header bg-grey-2"
                                    >
                                        <div
                                            class="row items-center justify-between"
                                        >
                                            <div class="col-grow">
                                                <div
                                                    class="text-h6 text-primary text-weight-bold"
                                                >
                                                    <q-icon
                                                        name="mdi-account-key"
                                                        class="q-mr-sm"
                                                    />
                                                    {{ __(item.role.name) }}
                                                </div>
                                                <div
                                                    class="text-caption text-grey-7 q-mt-xs"
                                                >
                                                    <q-icon
                                                        name="mdi-account-group"
                                                        class="q-mr-xs"
                                                    />
                                                    {{
                                                        __(
                                                            item.service.group
                                                                .name
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <q-btn
                                                    round
                                                    flat
                                                    color="blue"
                                                    icon="mdi-content-copy"
                                                    @click="
                                                        copyToClipboard(
                                                            item.gsr_id
                                                        )
                                                    "
                                                    size="sm"
                                                    class="copy-btn"
                                                >
                                                    <q-tooltip>{{
                                                        __(
                                                            "Copy GSR_ID to clipboard"
                                                        )
                                                    }}</q-tooltip>
                                                </q-btn>
                                            </div>
                                        </div>
                                    </q-card-section>

                                    <q-separator />

                                    <!-- Scope Details -->
                                    <q-card-section class="q-pt-md">
                                        <div class="scope-details">
                                            <div class="detail-item q-mb-md">
                                                <div
                                                    class="text-caption text-grey-7"
                                                >
                                                    {{ __("GSR_ID") }}
                                                </div>
                                                <div
                                                    class="text-code text-weight-medium"
                                                >
                                                    {{ item.gsr_id }}
                                                </div>
                                            </div>

                                            <div class="permissions-grid">
                                                <div class="permission-item">
                                                    <q-icon
                                                        :name="
                                                            item.api_key
                                                                ? 'mdi-key-check'
                                                                : 'mdi-key-remove'
                                                        "
                                                        :color="
                                                            item.api_key
                                                                ? 'green'
                                                                : 'red'
                                                        "
                                                        size="sm"
                                                    />
                                                    <span class="q-ml-sm">{{
                                                        __("API Key Access")
                                                    }}</span>
                                                    <q-badge
                                                        :color="
                                                            item.api_key
                                                                ? 'green'
                                                                : 'grey'
                                                        "
                                                        class="q-ml-sm"
                                                    >
                                                        {{
                                                            item.api_key
                                                                ? __("Enabled")
                                                                : __("Disabled")
                                                        }}
                                                    </q-badge>
                                                </div>

                                                <div class="permission-item">
                                                    <q-icon
                                                        :name="
                                                            item.active
                                                                ? 'mdi-check-circle'
                                                                : 'mdi-close-circle'
                                                        "
                                                        :color="
                                                            item.active
                                                                ? 'green'
                                                                : 'red'
                                                        "
                                                        size="sm"
                                                    />
                                                    <span class="q-ml-sm">{{
                                                        __("Active Status")
                                                    }}</span>
                                                    <q-badge
                                                        :color="
                                                            item.active
                                                                ? 'green'
                                                                : 'grey'
                                                        "
                                                        class="q-ml-sm"
                                                    >
                                                        {{
                                                            item.active
                                                                ? __("Active")
                                                                : __("Inactive")
                                                        }}
                                                    </q-badge>
                                                </div>

                                                <div class="permission-item">
                                                    <q-icon
                                                        :name="
                                                            item.public
                                                                ? 'mdi-earth'
                                                                : 'mdi-earth-off'
                                                        "
                                                        :color="
                                                            item.public
                                                                ? 'blue'
                                                                : 'grey'
                                                        "
                                                        size="sm"
                                                    />
                                                    <span class="q-ml-sm">{{
                                                        __("Public Access")
                                                    }}</span>
                                                    <q-badge
                                                        :color="
                                                            item.public
                                                                ? 'blue'
                                                                : 'grey'
                                                        "
                                                        class="q-ml-sm"
                                                    >
                                                        {{
                                                            item.public
                                                                ? __("Public")
                                                                : __("Private")
                                                        }}
                                                    </q-badge>
                                                </div>
                                            </div>
                                        </div>
                                    </q-card-section>

                                    <!-- Action Buttons -->
                                    <q-card-actions
                                        align="right"
                                        class="q-pa-md scope-actions"
                                    >
                                        <v-delete-scope
                                            :scope="item"
                                            @deleted="getScopes"
                                        />
                                        <v-add-scope
                                            icon="mdi-pencil-lock-outline"
                                            :scope="item"
                                            :link="service.links.scopes"
                                            @created="getScopes"
                                        />
                                    </q-card-actions>
                                </q-card>
                            </div>
                        </transition-group>

                        <!-- Empty State -->
                        <div
                            v-if="scopesCount === 0"
                            class="empty-state text-center q-pa-xl"
                        >
                            <q-icon
                                name="mdi-lock-off-outline"
                                size="xl"
                                color="grey-4"
                            />
                            <div class="text-h6 text-grey-7 q-mt-md">
                                {{ __("No scopes configured") }}
                            </div>
                            <div class="text-caption text-grey-5 q-mb-lg">
                                {{
                                    __(
                                        "Add access permissions to control who can use this service"
                                    )
                                }}
                            </div>
                            <v-add-scope
                                :link="service.links.scopes"
                                @created="getScopes"
                                class="q-mt-md"
                            />
                        </div>
                    </q-card-section>

                    <!-- Dialog Footer -->
                    <q-card-actions align="right" class="q-pa-lg dialog-footer">
                        <q-btn
                            outline
                            :label="__('Close')"
                            color="primary"
                            v-close-popup
                            icon="mdi-close"
                            class="close-btn"
                        />
                    </q-card-actions>
                </q-card>
            </div>
        </q-dialog>
    </div>
</template>
<script>
import VAddScope from "./AddScope.vue";
import VDeleteScope from "./DeleteScope.vue";

export default {
    props: ["service"],

    components: {
        VAddScope,
        VDeleteScope,
    },

    data() {
        return {
            scopes: [],
            dialog: false,
            loading: false,
        };
    },

    computed: {
        scopesCount() {
            return this.scopes.length;
        },
        activeScopesCount() {
            return this.scopes.filter((scope) => scope.active).length;
        },
        apiKeyScopesCount() {
            return this.scopes.filter((scope) => scope.api_key).length;
        },
        publicScopesCount() {
            return this.scopes.filter((scope) => scope.public).length;
        },
    },

    methods: {
        open() {
            this.getScopes();
            this.dialog = true;
        },

        async getScopes() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.service.links.scopes);
                if (res.status == 200) {
                    this.scopes = res.data.data;
                }
            } catch (e) {
                this.$q.notify({
                    type: "negative",
                    message: this.__("Failed to load scopes"),
                    icon: "mdi-alert-circle",
                    timeout: 3000,
                });
            } finally {
                this.loading = false;
            }
        },

        async copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(text);
                this.$q.notify({
                    type: "positive",
                    message: this.__("GSR_ID copied to clipboard"),
                    icon: "mdi-check-circle",
                    timeout: 2000,
                });
            } catch (err) {
                this.$q.notify({
                    type: "negative",
                    message: this.__("Failed to copy to clipboard"),
                    icon: "mdi-alert-circle",
                    timeout: 2000,
                });
            }
        },
    },
};
</script>

<style scoped>
.dialog-backdrop {
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 2rem;
    min-height: 100vh;
}

.scopes-dialog-card {
    width: 100%;
    max-width: 1400px;
    border-radius: 16px;
    overflow: hidden;
}

.dialog-header {
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
    padding: 2rem;
}

.scope-manager-btn {
    position: relative;
    transition: transform 0.2s ease;
}

.scope-manager-btn:hover {
    transform: translateY(-2px);
}

.scope-badge {
    font-size: 0.7em;
    padding: 4px 6px;
}

.stat-card {
    border-radius: 12px;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
}

.scope-card {
    border-radius: 12px;
    transition: all 0.3s ease;
    height: 100%;
}

.scope-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.2) !important;
}

.scope-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 1.5rem;
}

.copy-btn {
    transition: transform 0.2s ease;
}

.copy-btn:hover {
    transform: scale(1.1);
}

.scope-details {
    padding: 0.5rem;
}

.detail-item {
    padding: 0.5rem 0;
}

.text-code {
    font-family: "Monaco", "Menlo", "Ubuntu Mono", monospace;
    font-size: 0.9em;
    background: #f5f5f5;
    padding: 0.5rem;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
}

.permissions-grid {
    display: grid;
    gap: 1rem;
    margin-top: 1rem;
}

.permission-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #fafafa;
    border-radius: 8px;
    border: 1px solid #eeeeee;
}

.scope-actions {
    border-top: 1px solid #f0f0f0;
}

.empty-state {
    border: 2px dashed #e0e0e0;
    border-radius: 12px;
    background: #fafafa;
}

.dialog-footer {
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
}

.close-btn {
    border-radius: 8px;
    padding: 0.5rem 1.5rem;
}

.add-scope-btn {
    transition: transform 0.2s ease;
}

.add-scope-btn:hover {
    transform: scale(1.05);
}

/* Animations */
.scope-transition-move {
    transition: transform 0.5s ease;
}

.scope-transition-enter-active,
.scope-transition-leave-active {
    transition: all 0.4s ease;
}

.scope-transition-enter-from,
.scope-transition-leave-to {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
}

.shadow-15 {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 15px 25px rgba(0, 0, 0, 0.15);
}
</style>
