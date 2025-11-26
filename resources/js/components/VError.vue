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
    <div v-if="hasErrors">
        <span
            class="px-2 p-2 my-2 rounded text-sm block mb-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300"
            v-for="(error, index) in normalizedErrors"
            :key="index"
        >
            <div v-for="(item, i) in error" :key="i">
                {{ item }}
            </div>
        </span>
    </div>
</template>

<script>
export default {
    props: {
        error: {
            type: [Array, Object, String],
            default: () => ({ example: "example Error" }),
        },
    },

    computed: {
        /**
         * Devuelve un array limpio de errores (sin el ejemplo)
         */
        filteredErrors() {
            // Si es string → lo metemos en array
            if (typeof this.error === "string") {
                if (this.error === "example Error") return [];
                return [this.error];
            }

            // Si es array u objeto → limpiamos
            return Object.values(this.error).filter(
                (msg) => msg !== "example Error"
            );
        },

        /**
         * Normaliza todos los tipos de error a un array para que el template
         * siempre pueda hacer v-for sin romper
         */
        normalizedErrors() {
            return this.filteredErrors.map((err) => {
                if (Array.isArray(err)) {
                    return err;
                }

                if (typeof err === "object" && err !== null) {
                    return Object.values(err);
                }

                return [err]; // string o valor simple
            });
        },

        hasErrors() {
            return this.normalizedErrors.length > 0;
        },
    },
};
</script>
