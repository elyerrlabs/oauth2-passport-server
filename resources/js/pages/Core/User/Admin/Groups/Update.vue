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
        class="w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-full flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
    >
        <i class="mdi mdi-pencil text-lg"></i>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Update Group')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <!-- Content -->
            <div class="p-6 space-y-4">
                <v-input
                    :label="__('Group Name')"
                    :placeholder="__('name')"
                    v-model="form.name"
                    :error="errors.name"
                    :required="true"
                    :disabled="true"
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
                    :disabled="true"
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
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-lg flex items-center space-x-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-close-circle"></i>
                    <span>{{ __("Cancel") }}</span>
                </button>
                <button
                    @click="updateGroup"
                    :disabled="loading"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg flex items-center space-x-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="mdi mdi-content-save" v-if="!loading"></i>
                    <div
                        v-if="loading"
                        class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
                    ></div>
                    <span>{{ __("Update Group") }}</span>
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

export default {
    components: {
        VModal,
        VTextarea,
        VInput,
        VSwitch,
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
                    this.$q?.notify?.({
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
                }
                if (e?.response?.data?.message) {
                    this.$q?.notify?.({
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
