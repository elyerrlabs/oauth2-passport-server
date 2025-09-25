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
    <div class="w-full max-w-4xl p-2">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                >
                    <i class="fas fa-tags text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __("Tags") }}
                    </h3>
                    <p class="text-sm text-gray-600">
                        {{ __("Manage product tags and categories") }}
                    </p>
                </div>
            </div>
            <p class="text-gray-500 text-sm leading-relaxed mt-2">
                {{
                    __(
                        "Tags allow you to add keywords or labels to your products (e.g., 'eco-friendly', 'new arrival', 'summer collection'). Unlike categories, tags are flexible and can be combined freely. They help customers quickly find related products and improve search and filtering in your store."
                    )
                }}
            </p>
        </div>

        <!-- Main Content Card -->
        <div
            class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 overflow-hidden mx-auto"
        >
            <div class="p-2">
                <!-- Tags Counter -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-700">{{
                        __("Tags List")
                    }}</span>
                    <span
                        class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium"
                    >
                        {{ internalTags.length }} {{ __("tag") }}(s)
                    </span>
                </div>

                <!-- Tags Grid -->
                <div class="flex flex-wrap gap-3 mb-6">
                    <div
                        v-for="(tag, index) in internalTags"
                        :key="index"
                        :class="[
                            'tag-item flex items-center space-x-2 px-4 py-2 rounded-full border transition-all duration-200',
                            tag.editing
                                ? 'bg-white border-blue-300 shadow-sm'
                                : 'bg-blue-50 border-blue-200 hover:bg-blue-100',
                        ]"
                    >
                        <!-- Tag Display -->
                        <div
                            v-if="!tag.editing"
                            class="flex items-center space-x-2"
                        >
                            <span
                                class="text-sm font-medium text-blue-800 max-w-[120px] truncate"
                            >
                                {{ tag.name || __("New Tag") }}
                            </span>
                        </div>

                        <!-- Edit Input -->
                        <div v-else class="flex items-center space-x-2">
                            <input
                                v-model="tag.name"
                                type="text"
                                class="w-32 px-2 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tag name"
                                @blur="finishEditing(tag, index)"
                                @keyup.enter="finishEditing(tag, index)"
                                @keyup.esc="cancelEditing(index)"
                                ref="tagInputs"
                            />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-1">
                            <!-- Edit/Save Button -->
                            <button
                                @click="
                                    tag.editing
                                        ? finishEditing(tag, index)
                                        : startEditing(tag, index)
                                "
                                :class="[
                                    'w-6 h-6 rounded-full flex items-center justify-center transition-colors duration-200',
                                    tag.editing
                                        ? 'bg-green-100 text-green-600 hover:bg-green-200'
                                        : 'bg-blue-100 text-blue-600 hover:bg-blue-200',
                                ]"
                                :title="tag.editing ? __('Save') : __('Edit')"
                            >
                                <i
                                    class="fas text-xs"
                                    :class="
                                        tag.editing ? 'fa-check' : 'fa-edit'
                                    "
                                ></i>
                            </button>

                            <!-- Delete Button -->
                            <button
                                @click="deleteTag(index)"
                                class="w-6 h-6 rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition-colors duration-200"
                                title="Delete tag"
                            >
                                <i class="fas fa-trash text-xs"></i>
                            </button>

                            <!-- Cancel Button (only when editing) -->
                            <button
                                v-if="tag.editing"
                                @click="cancelEditing(index)"
                                class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors duration-200"
                                title="Cancel"
                            >
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Add Tag Button -->
                <button
                    @click="addTag"
                    :disabled="maxTags && internalTags.length >= maxTags"
                    :class="[
                        'add-btn px-4 py-2 rounded-lg border-2 border-dashed transition-all duration-200 flex items-center space-x-2',
                        maxTags && internalTags.length >= maxTags
                            ? 'border-gray-300 text-gray-400 cursor-not-allowed'
                            : 'border-blue-300 text-blue-600 hover:border-blue-400 hover:bg-blue-50',
                    ]"
                >
                    <i class="fas fa-plus"></i>
                    <span class="text-sm font-medium">{{ __("Add Tag") }}</span>
                </button>

                <!-- Max Tags Message -->
                <div
                    v-if="maxTags && internalTags.length >= maxTags"
                    class="mt-2 text-sm text-amber-600 flex items-center space-x-1"
                >
                    <i class="fas fa-exclamation-triangle"></i>
                    <span
                        >{{ __("Maximum") }} {{ maxTags }} {{ __("tags") }}
                        {{ __("allowed") }}</span
                    >
                </div>

                <!-- Empty State -->
                <div v-if="internalTags.length === 0" class="text-center py-8">
                    <div
                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3"
                    >
                        <i class="fas fa-tag text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">
                        {{ __("No tags added yet") }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ __("Click the button above to add your first tag") }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["update:modelValue", "tags-updated"],
    props: {
        modelValue: {
            type: Array,
            default: () => [],
            validator: (value) => {
                return value.every((item) =>
                    typeof item === "object"
                        ? "name" in item
                        : typeof item === "string"
                );
            },
        },
        allowDuplicates: {
            type: Boolean,
            default: false,
        },
        maxTags: {
            type: Number,
            default: null,
        },
    },

    data() {
        return {
            internalTags: [],
            lastValidState: [],
        };
    },

    computed: {
        editingCount() {
            return this.internalTags.filter((tag) => tag.editing).length;
        },
    },

    watch: {
        modelValue: {
            immediate: true,
            deep: true,
            handler(newVal) {
                this.lastValidState = [...newVal];
                this.internalTags = this.normalizeTags(newVal);
            },
        },
    },

    methods: {
        normalizeTags(tags) {
            return tags.map((tag) => ({
                name: typeof tag === "string" ? tag : tag.name || "",
                editing: false,
                ...(typeof tag === "object" ? tag : {}),
            }));
        },

        addTag() {
            if (this.maxTags && this.internalTags.length >= this.maxTags) {
                this.$swal({
                    icon: "warning",
                    title: "Maximum Tags Reached",
                    text: `You can only add up to ${this.maxTags} tags.`,
                    timer: 3000,
                    showConfirmButton: false,
                });
                return;
            }

            this.internalTags.push({
                name: "",
                editing: true,
            });

            // Focus the new input
            this.$nextTick(() => {
                const inputs = this.$refs.tagInputs;
                if (inputs && inputs.length) {
                    inputs[inputs.length - 1].focus();
                }
            });
        },

        startEditing(tag, index) {
            this.lastValidState = [...this.internalTags];
            this.internalTags[index].editing = true;

            // Focus the input
            this.$nextTick(() => {
                const inputs = this.$refs.tagInputs;
                if (inputs && inputs[index]) {
                    inputs[index].focus();
                }
            });
        },

        finishEditing(tag, index) {
            const name = tag.name.trim();

            if (!name) {
                this.deleteTag(index);
                return;
            }

            if (!this.allowDuplicates && this.isDuplicate(name, index)) {
                this.$swal({
                    icon: "error",
                    title: "Duplicate Tag",
                    text: "This tag already exists.",
                    timer: 2000,
                    showConfirmButton: false,
                });
                this.cancelEditing(index);
                return;
            }

            this.internalTags[index] = {
                ...this.internalTags[index],
                name,
                editing: false,
            };
            this.emitUpdate();
        },

        cancelEditing(index) {
            // Restore last valid state if it's a new empty tag
            if (
                this.internalTags[index].name === "" &&
                !this.lastValidState[index]
            ) {
                this.internalTags.splice(index, 1);
            } else {
                this.internalTags[index].editing = false;
                if (this.lastValidState[index]) {
                    this.internalTags[index].name =
                        typeof this.lastValidState[index] === "string"
                            ? this.lastValidState[index]
                            : this.lastValidState[index].name;
                }
            }
        },

        isDuplicate(name, currentIndex) {
            return this.internalTags.some(
                (tag, index) => tag.name === name && index !== currentIndex
            );
        },

        async deleteTag(index) {
            const item = this.internalTags[index];

            // SweetAlert2 confirmation
            const result = await this.$swal({
                title: this.__("Delete Tag?"),
                text: this.__("Are you sure you want to delete this tag?"),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: this.__("Yes, delete it!"),
                cancelButtonText: this.__("Cancel"),
                reverseButtons: true,
            });

            if (!result.isConfirmed) {
                return;
            }

            if (item.links?.destroy) {
                try {
                    const res = await this.$server.delete(item.links.destroy);
                    if (res.status === 200) {
                        this.internalTags.splice(index, 1);
                        this.emitUpdate();
                        this.$swal({
                            icon: "success",
                            title: this.__("Deleted!"),
                            text: this.__("Tag has been deleted successfully."),
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: "top-end",
                        });
                    }
                } catch (e) {
                    this.$swal({
                        icon: "error",
                        title: this.__("Error"),
                        text:
                            e?.response?.data?.message ||
                            this.__("Failed to delete tag."),
                        confirmButtonText: this.__("OK"),
                    });
                }
            } else {
                this.internalTags.splice(index, 1);
                this.emitUpdate();
                this.$swal({
                    icon: "success",
                    title: this.__("Deleted!"),
                    text: this.__("Tag has been deleted successfully."),
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                });
            }
        },

        emitUpdate() {
            // Always emit as array of objects with name property
            const tags = this.internalTags.map((tag) => ({
                name: tag.name,
                ...(tag.links ? { links: tag.links } : {}),
            }));

            this.$emit("update:modelValue", tags);
            this.$emit("tags-updated", tags);
        },
    },
};
</script>
