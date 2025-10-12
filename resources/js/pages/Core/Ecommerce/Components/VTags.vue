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
    <div class="w-full max-w-6xl mx-auto p-3">
        <!-- Header Section - Mejorado -->
        <div
            class="mb-8 p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl border border-purple-100 shadow-sm"
        >
            <div class="flex items-start gap-6 mb-4">
                <div
                    class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg"
                >
                    <i class="fas fa-tags text-white text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">
                        {{ __("Product Tags") }}
                    </h2>
                    <p class="text-xl text-gray-700 font-medium mb-4">
                        {{ __("Manage product tags and categories") }}
                    </p>
                    <p class="text-gray-600 text-md leading-relaxed">
                        {{
                            __(
                                "Tags allow you to add keywords or labels to your products (e.g., 'eco-friendly', 'new arrival', 'summer collection'). Unlike categories, tags are flexible and can be combined freely. They help customers quickly find related products and improve search and filtering in your store."
                            )
                        }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Card  -->
        <div
            class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden"
        >
            <!-- Card Header -->
            <div
                class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200"
            >
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4"
                >
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ __("Tags Manager") }}
                        </h3>
                        <p class="text-gray-600">
                            {{
                                __("Add, edit, and organize your product tags")
                            }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="px-4 py-2 bg-purple-500 text-white rounded-full text-sm font-semibold shadow-sm"
                        >
                            {{ internalTags.length }} {{ __("tag") }}(s)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tags Content -->
            <div class="p-8">
                <!-- Empty State - Mejorado -->
                <div v-if="internalTags.length === 0" class="text-center py-16">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner"
                    >
                        <i class="fas fa-tags text-gray-400 text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-3">
                        {{ __("No tags added yet") }}
                    </h4>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        {{
                            __(
                                "Start by adding tags to help customers discover your products through search and filtering."
                            )
                        }}
                    </p>
                    <button
                        @click="addTag"
                        :disabled="maxTags && internalTags.length >= maxTags"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    >
                        <i class="fas fa-plus-circle text-xl"></i>
                        <span class="text-lg">{{ __("Add First Tag") }}</span>
                    </button>
                </div>

                <!-- Tags Grid - Mejorado -->
                <div v-else class="space-y-4 mb-8">
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
                    >
                        <div
                            v-for="(tag, index) in internalTags"
                            :key="index"
                            :class="[
                                'group relative bg-gradient-to-br from-white to-gray-50 rounded-2xl border-2 p-4 shadow-sm hover:shadow-xl transition-all duration-300',
                                tag.editing
                                    ? 'border-purple-300 ring-4 ring-purple-100'
                                    : 'border-gray-200 hover:border-purple-200',
                            ]"
                        >
                            <!-- Tag Content -->
                            <div class="flex items-center justify-between">
                                <!-- Tag Display -->
                                <div v-if="!tag.editing" class="flex-1 min-w-0">
                                    <span
                                        class="text-sm font-semibold text-gray-900 truncate block"
                                    >
                                        {{ tag.name || __("New Tag") }}
                                    </span>
                                </div>

                                <!-- Edit Input -->
                                <div v-else class="flex-1">
                                    <input
                                        v-model="tag.name"
                                        type="text"
                                        class="w-full px-3 py-2 text-sm border-2 border-purple-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                        :placeholder="__('Tag name')"
                                        @blur="finishEditing(tag, index)"
                                        @keyup.enter="finishEditing(tag, index)"
                                        @keyup.esc="cancelEditing(index)"
                                        ref="tagInputs"
                                    />
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 ml-3">
                                    <!-- Edit/Save Button -->
                                    <button
                                        @click="
                                            tag.editing
                                                ? finishEditing(tag, index)
                                                : startEditing(tag, index)
                                        "
                                        :class="[
                                            'w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200 shadow-sm',
                                            tag.editing
                                                ? 'bg-green-500 text-white hover:bg-green-600 hover:scale-110'
                                                : 'bg-purple-100 text-purple-600 hover:bg-purple-200 hover:scale-110',
                                        ]"
                                        :title="
                                            tag.editing
                                                ? __('Save')
                                                : __('Edit')
                                        "
                                    >
                                        <i
                                            class="fas text-xs"
                                            :class="
                                                tag.editing
                                                    ? 'fa-check'
                                                    : 'fa-edit'
                                            "
                                        ></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button
                                        @click="deleteTag(index)"
                                        class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all duration-200 shadow-sm"
                                        :title="__('Delete tag')"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>

                                    <!-- Cancel Button (only when editing) -->
                                    <button
                                        v-if="tag.editing"
                                        @click="cancelEditing(index)"
                                        class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:scale-110 transition-all duration-200 shadow-sm"
                                        :title="__('Cancel')"
                                    >
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Editing Indicator -->
                            <div
                                v-if="tag.editing"
                                class="absolute -top-2 -right-2"
                            >
                                <span
                                    class="px-2 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full shadow-lg"
                                >
                                    {{ __("Editing") }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Tag Section - Mejorado -->
                <div
                    v-if="internalTags.length > 0"
                    class="border-t border-gray-200 pt-8"
                >
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between gap-4"
                    >
                        <div class="flex-1">
                            <p class="text-gray-600 text-sm">
                                {{
                                    __(
                                        "Add more tags to improve product discoverability"
                                    )
                                }}
                            </p>
                        </div>
                        <button
                            @click="addTag"
                            :disabled="
                                maxTags && internalTags.length >= maxTags
                            "
                            :class="[
                                'px-6 py-3 rounded-xl border-2 font-semibold transition-all duration-300 flex items-center gap-2',
                                maxTags && internalTags.length >= maxTags
                                    ? 'border-gray-300 text-gray-400 cursor-not-allowed bg-gray-100'
                                    : 'border-purple-300 text-purple-600 hover:border-purple-400 hover:bg-purple-50 hover:scale-105 bg-white shadow-sm',
                            ]"
                        >
                            <i class="fas fa-plus"></i>
                            <span>{{ __("Add New Tag") }}</span>
                        </button>
                    </div>

                    <!-- Max Tags Message -->
                    <div
                        v-if="maxTags && internalTags.length >= maxTags"
                        class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl"
                    >
                        <div class="flex items-center gap-3">
                            <i
                                class="fas fa-exclamation-triangle text-amber-500 text-lg"
                            ></i>
                            <div>
                                <p class="text-amber-800 font-semibold">
                                    {{ __("Maximum Tags Reached") }}
                                </p>
                                <p class="text-amber-700 text-sm">
                                    {{ __("You can only add up to") }}
                                    {{ maxTags }} {{ __("tags") }}
                                </p>
                            </div>
                        </div>
                    </div>
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
                    title: __("Maximum Tags Reached"),
                    text: __(
                        "You can only add up to {maxTags} tags."
                    ).replace("{maxTags}", this.maxTags),
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
                    title: __("Duplicate Tag"),
                    text: __("This tag already exists."),
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

            const result = await this.$swal({
                title: __("Delete Tag?"),
                text: __("Are you sure you want to delete this tag?"),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: __("Yes, delete it!"),
                cancelButtonText: __("Cancel"),
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
                            title: __("Deleted!"),
                            text: __("Tag has been deleted successfully."),
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: "top-end",
                        });
                    }
                } catch (e) {
                    this.$swal({
                        icon: "error",
                        title: __("Error"),
                        text:
                            e?.response?.data?.message ||
                            __("Failed to delete tag."),
                        confirmButtonText: __("OK"),
                    });
                }
            } else {
                this.internalTags.splice(index, 1);
                this.emitUpdate();
                this.$swal({
                    icon: "success",
                    title: __("Deleted!"),
                    text: __("Tag has been deleted successfully."),
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                });
            }
        },

        emitUpdate() {
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
