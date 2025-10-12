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
    <div>
        <button
            @click="openDeleteDialog"
            class="bg-red-500 text-white hover:bg-red-700 p-2 rounded transition-colors"
            data-test="delete-button"
        >
            {{ __("Delete") }}
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</template>

<script>
export default {
    emits: ["deleted"],

    props: {
        item: {
            type: Object,
            required: true,
        },
    },

    methods: {
        openDeleteDialog() {
            this.$swal({
                title: __("Delete product?"),
                text: `${__("Delete")} "${this.item.name}"? ${__(
                    "This cannot be undone."
                )}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#6b7280",
                confirmButtonText: __("Delete"),
                cancelButtonText: __("Cancel"),
            }).then((result) => {
                if (result.isConfirmed) {
                    this.destroy();
                }
            });
        },

        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.$swal({
                        icon: "success",
                        title: __("Deleted"),
                        timer: 1500,
                        showConfirmButton: false,
                    });
                }
            } catch (e) {
                this.$swal({
                    icon: "error",
                    title: __("Error"),
                    text:
                        e?.response?.data?.message || __("Delete failed"),
                });
            }
        },
    },
};
</script>
