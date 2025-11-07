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
    <!-- Edit Button -->
    <button
        @click="open(item)"
        class="relative w-12 h-12 gap-2 border border-blue-600 px-4 py-2 text-blue-600 rounded-full hover:bg-blue-600 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200"
    >
        <i class="mdi mdi-pencil text-lg"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-blue-600 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
        >
            {{ __("Edit service") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-blue-600"
            ></div>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Update Service')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <div class="grid grid-cols-1 lg:grid-cols-2 mb-4 gap-4">
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

            <div class="grid grid-cols-1 md:grid-cols-2 mb-4">
                <v-select
                    v-model="form.visibility"
                    :label="__('Select visibility')"
                    :options="visibilityOptions"
                    :placeholder="
                        __('Set the visibility level for this service')
                    "
                    :required="true"
                    :error="errors.visibility"
                />
            </div>

            <div
                class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl"
            >
                <button
                    @click="close"
                    :disabled="loading"
                    class="flex items-center gap-2 px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-close-circle"></i>
                    {{ __("Cancel") }}
                </button>
                <button
                    @click="updateService"
                    :disabled="loading"
                    :class="[
                        'flex items-center gap-2 px-6 py-2 text-white rounded-lg focus:outline-none focus:ring-2 transition-colors',
                        loading
                            ? 'bg-blue-400 cursor-not-allowed'
                            : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-200',
                    ]"
                >
                    <i v-if="loading" class="mdi mdi-loading animate-spin"></i>
                    <i v-else class="mdi mdi-content-save"></i>
                    {{ __("Update Service") }}
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
    emits: ["updated"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            visibilityOptions: [
                { name: __("private"), id: "private" },
                { name: __("public"), id: "public" },
            ],
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
            this.form = item;
            this.errors = {};
            this.dialog = true;
        },

        async updateService() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.put(
                    this.form.links.update,
                    this.form
                );

                if (res.status == 200) {
                    $notify.error(__("Service updated successfully"));
                    this.$emit("updated", true);
                    this.errors = {};
                    this.dialog = false;
                }
            } catch (e) {
                if (e.response && e.response.status == 422) {
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
