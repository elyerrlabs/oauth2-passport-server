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
    <div class="p-4 space-y-3">
        <button
            @click="open"
            class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300"
            :class="{ 'animate-pulse': !dialog }"
        >
            <i class="mdi mdi-plus-circle text-xl"></i>
        </button>

        <v-modal
            v-model="dialog"
            :title="__('Create New Group')"
            panel-class="w-full lg:w-4xl"
        >
            <template #body>
                <!-- Form -->
                <div class="p-6 space-y-4">
                    <v-input
                        :label="__('Group Name')"
                        :placeholder="__('name')"
                        v-model="form.name"
                        :error="errors.name"
                        :required="true"
                    />

                    <v-textarea
                        :label="__('Description')"
                        v-model="form.description"
                        :error="errors.description"
                        :placeholder="__('Write short description ...')"
                        :required="true"
                    />

                    <!-- System Group Checkbox -->
                    <v-switch
                        :label="__('System Group')"
                        v-model="form.system"
                        :placeholder="
                            __(
                                'System groups have special permissions and cannot be deleted.'
                            )
                        "
                    />
                </div>

                <!-- Actions -->
                <div
                    class="flex justify-end space-x-3 p-6 border-t border-gray-200"
                >
                    <button
                        @click="close"
                        :disabled="loading"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ __("Cancel") }}
                    </button>
                    <button
                        @click="create"
                        :disabled="loading"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center space-x-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="mdi mdi-check-circle" v-if="!loading"></i>
                        <div
                            v-if="loading"
                            class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
                        ></div>
                        <span>{{ __("Create Group") }}</span>
                    </button>
                </div>
            </template>
        </v-modal>
    </div>
</template>

<script>
import VModal from "@/components/VModal.vue";
import VInput from "@/components/VInput.vue";
import VTextarea from "@/components/VTextarea.vue";
import VSwitch from "@/components/VSwitch.vue";

export default {
    components: {
        VModal,
        VTextarea,
        VInput,
        VSwitch,
    },
    emits: ["created"],

    data() {
        return {
            dialog: false,
            loading: false,
            form: {
                name: null,
                description: null,
                system: false,
            },
            errors: {},
        };
    },

    methods: {
        close() {
            this.form = {
                name: null,
                description: null,
                system: false,
            };
            this.errors = {};
            this.dialog = false;
            this.loading = false;
        },

        open() {
            this.form.name = null;
            this.form.description = null;
            this.form.system = false;
            this.errors = {};
            this.dialog = true;
        },

        async create() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );

                if (res.status == 201) {
                    this.$notify?.(__("Group created successfully"));

                    this.form = {
                        name: null,
                        description: null,
                        system: false,
                    };
                    this.errors = {};
                    this.$emit("created", true);
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response && e.response.data.errors) {
                    this.errors = e.response.data.errors;
                }
                if (e?.response?.data?.message) {
                    $notify.error(e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
