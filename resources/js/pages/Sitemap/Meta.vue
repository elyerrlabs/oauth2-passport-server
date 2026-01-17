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
    <v-seo-layout>
        <!-- Container -->
        <div
            class="p-2 rounded-xl bg-white shadow-sm transition dark:bg-gray-900 dark:border-gray-700 dark:shadow-none"
        >
            <!-- Header -->
            <div class="mb-6">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1"
                >
                    {{ __("SEO Metadata Manager") }}
                </h1>

                <p class="text-gray-600 dark:text-gray-400">
                    {{
                        __(
                            "Here you can update the host metadata, including meta tags and SEO configurations associated with this domain."
                        )
                    }}
                </p>
            </div>

            <!-- Editor -->
            <v-editor
                v-model="form.meta"
                :error="form.errors.meta"
                :jodit="false"
                :preview="false"                
            />

            <!-- Actions -->
            <div class="mt-6 flex justify-end">
                <button
                    @click="updateMeta"
                    class="px-4 py-2 cursor-pointer rounded-md font-medium transition text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
                >
                    {{ __("Update Metadata") }}
                </button>
            </div>
        </div>
    </v-seo-layout>
</template>

<script setup>
import VSeoLayout from "@/components/VGeneralLayout.vue";
import VEditor from "@/components/VEditor.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { onMounted } from "vue";

const page = usePage();

const form = useForm({
    meta: "",
});

onMounted(() => {
    form.meta = page.props.data;
});

const updateMeta = () => {
    form.post(page.props.routes.store, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(__("Meta data has been updated successfully"));
        },
    });
};
</script>
