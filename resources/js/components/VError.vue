<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
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
