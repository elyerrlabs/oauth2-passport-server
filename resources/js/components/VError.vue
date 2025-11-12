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
            class="px-3 p-2 my-2 rounded text-sm block mb-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300"
            v-for="(item, index) in filteredErrors"
            :key="index"
        >
            {{ item }}
        </span>
    </div>
</template>

<script>
export default {
    props: {
        error: {
            type: [Object, String],
            required: true,
            default: () => ({ example: "example Error" }),
        },
    },
    computed: {
        filteredErrors() {
            if (typeof this.error === "string") {
                return [this.error];
            }
            return Object.values(this.error).filter(
                (msg) => msg !== "example Error"
            );
        },
        hasErrors() {
            return this.filteredErrors.length > 0;
        },
    },
};
</script>
