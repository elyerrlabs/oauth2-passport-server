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
    <!-- Create Button -->
    <button
        @click="dialog = true"
        class="relative group h-12 w-12 rounded-full bg-blue-600 text-white p-3 shadow-lg hover:shadow-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300"
    >
        <i class="mdi mdi-plus-circle text-xl"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
        >
            {{ __("Add new service") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"
            ></div>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Create New Service')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <v-input
                    v-model="form.name"
                    :error="errors.name"
                    :required="true"
                    :label="__('Name')"
                />

                <v-switch
                    v-model="form.system"
                    :error="errors.system"
                    :label="__('System Service')"
                    :required="true"
                />
            </div>

            <div class="grid grid-cols-1 mb-4">
                <v-textarea
                    v-model="form.description"
                    :error="errors.description"
                    :label="__('Description')"
                    :required="true"
                />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 mb-4 gap-4">
                <v-select
                    v-model="form.group_id"
                    :options="groups"
                    :placeholder="
                        __('Select the group this service belongs to')
                    "
                    :required="true"
                    :label="__('Choose group')"
                    :error="errors.group_id"
                />

                <v-select
                    v-model="form.visibility"
                    :label="__('Select visibility')"
                    :placeholder="
                        __('Set the visibility level for this service')
                    "
                    :options="visibilityOptions"
                    :required="true"
                    :error="errors.visibility"
                />
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50"
            >
                <button
                    @click="close"
                    :disabled="loading"
                    class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="create"
                    :disabled="loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors',
                        loading
                            ? 'bg-blue-400 cursor-not-allowed'
                            : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-200',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i v-else class="mdi mdi-check-circle"></i>
                    {{ __("Create Service") }}
                </button>
            </div>
        </template>
    </v-modal>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VSwitch from "@/components/VSwitch.vue";
import VSelect from "@/components/VSelect.vue";
export default {
    components: {
        VModal,
        VInput,
        VTextarea,
        VSwitch,
        VSelect,
    },

    emits: ["created"],

    data() {
        return {
            dialog: false,
            loading: false,
            loadingGroups: false,
            visibilityOptions: [
                { name: __("private"), id: "private" },
                { name: __("public"), id: "public" },
            ],
            form: {
                name: null,
                description: null,
                group_id: null,
                system: false,
                visibility: null,
            },
            errors: {},
            groups: [],
        };
    },

    created() {
        this.getGroups();
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close() {
            this.form = {
                name: null,
                description: null,
                group_id: null,
                system: false,
                visibility: null,
            };
            this.errors = {};
            this.dialog = false;
            this.loading = false;
        },

        open() {
            this.form = {
                name: null,
                description: null,
                group_id: null,
                system: false,
                visibility: null,
            };
            this.errors = {};
            this.dialog = true;
        },

        /**
         * Create a new service
         */
        async create() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route.services,
                    this.form
                );

                if (res.status == 201) {
                    $notify.success(__("Service created successfully"));

                    this.form = {
                        name: null,
                        description: null,
                        group_id: null,
                        system: false,
                        visibility: null,
                    };
                    this.errors = {};
                    this.$emit("created", true);
                    this.dialog = false;
                }
            } catch (e) {
                if (
                    e.response &&
                    e.response.status == 422 &&
                    e.response.data.errors
                ) {
                    this.errors = e.response.data.errors;
                }

                if (e?.response?.data?.message) {
                    if (e?.response?.data?.message) {
                        $notify.error(__("Failed to load groups"));
                    }
                }
            } finally {
                this.loading = false;
            }
        },

        async getGroups() {
            this.loadingGroups = true;
            try {
                const res = await this.$server.get(
                    this.$page.props.route.groups,
                    {
                        params: {
                            page: 1,
                            per_page: 1000,
                        },
                    }
                );

                if (res.status == 200) {
                    this.groups = res.data.data;
                }
            } catch (e) {
                if (e?.response?.data?.message) {
                    $notify.error(__("Failed to load groups"));
                }
            } finally {
                this.loadingGroups = false;
            }
        },
    },
};
</script>

<style scoped>
/* Animations */
@keyframes pulse {
    0%,
    100% {
        transform: scale(1);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1),
            0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1),
            0 10px 10px -5px rgb(0 0 0 / 0.04);
    }
}

button:first-child {
    animation: pulse 2s infinite;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Ensure modal is above other elements */
.fixed {
    z-index: 50;
}

/* Backdrop transition */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Smooth transitions for inputs */
input,
textarea,
select {
    transition: all 0.3s ease;
}

input:focus,
textarea:focus,
select:focus {
    transform: translateY(-1px);
}
</style>
