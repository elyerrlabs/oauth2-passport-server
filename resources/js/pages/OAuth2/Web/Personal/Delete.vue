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
    <!-- Delete Button -->
    <button
        @click="confirmDelete"
        :disabled="loading"
        :aria-label="__('Delete API key') + ' ' + item.name"
        class="inline-flex items-center gap-2 px-3 py-2 text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 group"
    >
        <svg
            class="w-4 h-4 transition-transform group-hover:scale-110"
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
        <span class="text-sm font-medium">
            <span v-if="!loading">{{ __("Delete") }}</span>
            <span v-else class="flex items-center gap-2">
                <svg
                    class="animate-spin h-3 w-3"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                {{ __("Deleting...") }}
            </span>
        </span>
    </button>
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
    data() {
        return {
            loading: false,
        };
    },
    methods: {
        async confirmDelete() {
            if (this.loading) return;

            const result = await $swal.fire.fire({
                title: __("Delete API Key?"),
                html: this.getConfirmationHtml(),
                icon: "warning",
                iconColor: "#DC2626",
                showCancelButton: true,
                confirmButtonText: __("Yes, Delete It"),
                cancelButtonText: __("Cancel"),
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: "rounded-xl",
                    title: "text-lg font-semibold text-gray-900",
                    htmlContainer: "text-left",
                    confirmButton:
                        "px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200",
                    cancelButton:
                        "px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 mr-3",
                },
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return this.destroy();
                },
                allowOutsideClick: () => !this.loading,
            });

            if (result.isConfirmed && result.value) {
                this.showSuccessMessage();
            }
        },

        getConfirmationHtml() {
            return `
                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div class="text-red-800">
                            <p class="font-medium text-sm">${__(
                                "This action cannot be undone"
                            )}</p>
                            <p class="text-sm mt-1">${__(
                                "Applications using this key will immediately lose access"
                            )}</p>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-gray-50 rounded-lg border">
                        <p class="text-gray-600 text-sm mb-2">${__(
                            "You are about to delete:"
                        )}</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            <span class="font-medium text-gray-900">${this.escapeHtml(
                                this.item.name
                            )}</span>
                        </div>
                        ${
                            this.item.id
                                ? `
                            <div class="flex items-center gap-2 mt-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                                <span class="text-xs text-gray-500">ID: ${this.item.id}</span>
                            </div>
                        `
                                : ""
                        }
                    </div>
                </div>
            `;
        },

        async destroy() {
            this.loading = true;
            try {
                const res = await this.$server.delete(this.item.links.destroy);
                if (res.status === 200) {
                    this.$emit("deleted", true);
                    return true;
                }
                return false;
            } catch (e) {
                const message = this.getErrorMessage(e);
                await $swal.fire.fire({
                    icon: "error",
                    title: __("Delete Failed"),
                    text: message,
                    confirmButtonText: __("OK"),
                    customClass: {
                        confirmButton:
                            "px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2",
                    },
                });
                return false;
            } finally {
                this.loading = false;
            }
        },

        showSuccessMessage() {
            $swal.fire.fire({
                icon: "success",
                title: __("Deleted!"),
                html: `
                    <div class="text-center space-y-3">
                        <div class="flex justify-center">
                            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">${__(
                                "API Key Deleted"
                            )}</h3>
                            <p class="text-gray-600 mt-1">${__(
                                "The API key has been permanently deleted"
                            )}</p>
                        </div>
                    </div>
                `,
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
                willClose: () => {
                    // Additional cleanup if needed
                },
            });
        },

        getErrorMessage(error) {
            if (error?.response?.data?.message) {
                return error.response.data.message;
            }
            if (error?.response?.status === 404) {
                return __(
                    "API key not found. It may have been already deleted."
                );
            }
            if (error?.response?.status === 403) {
                return __(
                    "You don't have permission to delete this API key."
                );
            }
            return __("Failed to delete API key. Please try again.");
        },

        escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        },
    },
};
</script>
