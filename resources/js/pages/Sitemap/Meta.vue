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
            <v-editor v-model="form.meta" :error="form.errors.meta" />

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
