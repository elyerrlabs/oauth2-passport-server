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
        <!-- Delete Button -->
        <button
            @click="confirmDelete"
            class="delete-btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md transition-colors duration-200 flex items-center space-x-2"
        >
            <i class="fas fa-trash"></i>
            <span>{{ __("Delete") }}</span>
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
        async confirmDelete() {
            try {
                const result = await this.$swal({
                    title: this.__("Delete Category?"),
                    html: `
                        <div class="text-left">
                            <p class="mb-4">${this.__(
                                "You are about to delete the category"
                            )} <strong class="text-red-600">"${
                        this.item.name
                    }"</strong>.</p>
                            <p class="mb-4">${this.__(
                                "This action cannot be undone and will remove all associated data."
                            )}</p>

                            <div class="bg-gray-100 p-4 rounded-lg mb-4">
                                <h4 class="font-semibold text-gray-800 mb-2">${this.__(
                                    "Category Details"
                                )}</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center">
                                        <i class="fas fa-tag text-gray-500 mr-2 w-5"></i>
                                        <span class="font-medium">${this.__(
                                            "Name:"
                                        )}</span>
                                        <span class="ml-2">${
                                            this.item.name
                                        }</span>
                                    </div>
                                    ${
                                        this.item.icon?.icon
                                            ? `
                                    <div class="flex items-center">
                                        <i class="fas fa-icons text-gray-500 mr-2 w-5"></i>
                                        <span class="font-medium">${this.__(
                                            "Icon:"
                                        )}</span>
                                        <span class="ml-2">
                                            <i class="mdi ${
                                                this.item.icon.icon
                                            } text-blue-500 mr-1"></i>
                                            ${this.item.icon.icon}
                                        </span>
                                    </div>
                                    `
                                            : ""
                                    }
                                    <div class="flex items-center">
                                        <i class="fas fa-eye ${
                                            this.item.published
                                                ? "text-green-500"
                                                : "text-gray-400"
                                        } mr-2 w-5"></i>
                                        <span class="font-medium">${this.__(
                                            "Status:"
                                        )}</span>
                                        <span class="ml-2">${
                                            this.item.published
                                                ? this.__("Published")
                                                : this.__("Hidden")
                                        }</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-star ${
                                            this.item.featured
                                                ? "text-yellow-500"
                                                : "text-gray-400"
                                        } mr-2 w-5"></i>
                                        <span class="font-medium">${this.__(
                                            "Featured:"
                                        )}</span>
                                        <span class="ml-2">${
                                            this.item.featured
                                                ? this.__("Yes")
                                                : this.__("No")
                                        }</span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-red-600 text-sm flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                ${this.__(
                                    "Warning: This will permanently delete the category and cannot be recovered."
                                )}
                            </p>
                        </div>
                    `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6b7280",
                    confirmButtonText: this.__("Delete Category"),
                    cancelButtonText: this.__("Cancel"),
                    focusCancel: true,
                    customClass: {
                        popup: "rounded-lg",
                        confirmButton: "px-4 py-2 rounded-lg",
                        cancelButton: "px-4 py-2 rounded-lg mr-2",
                    },
                    buttonsStyling: false,
                });

                if (result.isConfirmed) {
                    await this.destroy();
                }
            } catch (error) {
                this.$swal({
                    title: this.__("Error!"),
                    text: this.__("Delete cancelled"),
                    icon: "error",
                    confirmButtonText: this.__("OK"),
                });
            }
        },

        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);

                    // Success notification
                    this.$swal({
                        title: this.__("Deleted!"),
                        text: this.__("Category has been deleted successfully"),
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            } catch (e) {
                let errorMessage = this.__(
                    "An error occurred while deleting the category"
                );

                if (e?.response?.data?.message) {
                    errorMessage = this.__(e.response.data.message);
                }

                this.$swal({
                    title: this.__("Error!"),
                    text: errorMessage,
                    icon: "error",
                    confirmButtonText: this.__("OK"),
                });
            }
        },
    },
};
</script>

<style>
.delete-btn {
    min-width: 100px;
}

.swal2-popup {
    border-radius: 12px !important;
    padding: 2rem !important;
}

.swal2-confirm,
.swal2-cancel {
    border-radius: 8px !important;
    padding: 0.5rem 1.5rem !important;
    font-weight: 500 !important;
}
</style>
