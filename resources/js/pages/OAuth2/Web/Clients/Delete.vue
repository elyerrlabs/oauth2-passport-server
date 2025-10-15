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
        <!-- Delete Button  -->
        <button
            @click="openDialog"
            class="delete-btn group relative inline-flex items-center justify-center bg-transparent text-gray-600 hover:text-red-600 font-medium py-2.5 px-4 rounded-lg border border-gray-300 hover:border-red-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
        >
            <div class="flex items-center space-x-2">
                <svg
                    class="w-4 h-4 transform group-hover:scale-110 transition-transform duration-200"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                </svg>
                <span class="text-sm font-medium"> {{ __("Remove") }} </span>
            </div>
            <!-- Subtle background on hover -->
            <div
                class="absolute inset-0 rounded-lg bg-red-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 -z-10"
            ></div>
        </button>
    </div>
</template>

<script>
export default {
    emits: ["deleted"],
    props: { item: { type: Object, required: true } },

    methods: {
        openDialog() {
            $swal.fire
                .fire({
                    title: __("Delete Client"),
                    text: __(
                        "This action cannot be undone. Type DELETE to confirm."
                    ),
                    icon: "warning",
                    input: "text",
                    inputPlaceholder: "DELETE",
                    inputValidator: (value) => {
                        if (value !== "DELETE") {
                            return __('Please type "DELETE" to confirm');
                        }
                    },
                    showCancelButton: true,
                    confirmButtonText: __("Delete"),
                    cancelButtonText: __("Cancel"),
                    confirmButtonColor: "#dc2626",
                    cancelButtonColor: "#6b7280",
                    reverseButtons: true,
                    customClass: {
                        popup: "rounded-xl max-w-sm",
                        confirmButton:
                            "px-4 py-2 text-sm font-medium rounded-md",
                        cancelButton:
                            "px-4 py-2 text-sm font-medium rounded-md",
                    },
                    preConfirm: () => this.destroy(),
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.showSuccessNotification();
                    }
                });
        },

        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.destroy);
                if (res.status == 200) {
                    this.$emit("deleted", true);
                    return true;
                }
            } catch (e) {
                $swal.fire.fire({
                    title: __("Error"),
                    text: e?.response?.data?.message || "Unexpected error",
                    icon: "error",
                    confirmButtonColor: "#dc2626",
                });
                return false;
            }
        },

        showSuccessNotification() {
            $swal.fire.fire({
                title: __("Deleted!"),
                text: __("OAuth client deleted successfully."),
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
            });
        },
    },
};
</script>
