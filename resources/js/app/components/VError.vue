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
-->
<template>
    <div v-if="hasErrors">
        <span
            v-for="(item, index) in filteredErrors"
            :key="index"
            class="text-red-500 block"
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
