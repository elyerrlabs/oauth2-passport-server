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
        @click="open"
        class="relative group h-12 w-12 rounded-full bg-blue-600 text-white p-3 shadow-lg hover:shadow-xl hover:bg-blue-700 transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300"
    >
        <i class="mdi mdi-plus-circle text-xl"></i>

        <!-- Tooltip -->
        <div
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap"
        >
            {{ __("Add new role") }}
            <div
                class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"
            ></div>
        </div>
    </button>

    <v-modal
        v-model="dialog"
        :title="__('Create New Role')"
        panel-class="w-full lg:w-4xl"
    >
        <template #body>
            <!-- Form Content -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2">
                <v-input
                    :label="__('Name')"
                    v-model="form.name"
                    :placeholder="__('name')"
                    :required="true"
                    :error="errors.name"
                />
            </div>

            <div class="p-6 grid grid-cols-1">
                <v-textarea
                    :label="__('Description')"
                    v-model="form.description"
                    :placeholder="__('description')"
                    :required="true"
                    :error="errors.description"
                />
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2">
                <v-switch
                    :label="__('System Group')"
                    v-model="form.system"
                    :error="errors.system"
                    :placeholder="
                        __(
                            'System groups have special permissions and cannot be deleted.'
                        )
                    "
                />
            </div>

            <div
                class="grid grid-cols-2 justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl"
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
                    {{ __("Create Role") }}
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
        VInput,
        VTextarea,
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
        /**
         * Clean the form when it is closed
         */
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
            this.form = {
                name: null,
                description: null,
                system: false,
            };
            this.errors = {};
            this.dialog = true;
        },

        /**
         * Create a new role
         */
        async create() {
            this.loading = true;
            this.errors = {};

            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    this.form
                );

                if (res.status == 201) {
                    // Reemplazar notificación de Quasar
                    this.showNotification(
                        "success",
                        "Role created successfully"
                    );

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
                    this.showNotification("error", e.response.data.message);
                }
            } finally {
                this.loading = false;
            }
        },

        /**
         * Mostrar notificación (reemplazo para $q.notify)
         */
        showNotification(type, message) {
            // Aquí puedes integrar tu sistema de notificaciones preferido
            // Por ejemplo: toast, alerta personalizada, etc.
            console.log(`${type}: ${message}`);

            // Ejemplo básico con alerta nativa (reemplaza con tu solución preferida)
            if (type === "success") {
                alert(`✅ ${message}`);
            } else {
                alert(`❌ ${message}`);
            }
        },
    },
};
</script>

<style scoped>
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

/* Asegurar que el modal esté por encima de otros elementos */
.fixed {
    z-index: 50;
}

/* Transición de backdrop */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}
</style>
