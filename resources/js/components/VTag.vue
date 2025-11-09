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
    <div
        class="tags-container bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 w-full"
    >
        <!-- Header -->
        <div class="flex items-center space-x-2 mb-4">
            <div
                class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center"
            >
                <i class="mdi mdi-tag-multiple text-white text-sm"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __("Tags") }}
            </h3>
        </div>

        <!-- Tags List -->
        <div class="flex flex-wrap gap-2 mb-4">
            <div
                v-for="(tag, index) in internalTags"
                :key="index"
                class="tag-item flex items-center px-3 py-2 rounded-full transition-all duration-200 group"
                :class="[
                    tag.editing
                        ? 'bg-white dark:bg-gray-700 border-2 border-blue-500 dark:border-blue-400 shadow-md'
                        : 'bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/40',
                ]"
            >
                <!-- Tag name (visible cuando no se está editando) -->
                <div
                    v-if="!tag.editing"
                    class="tag-label text-sm font-medium text-blue-700 dark:text-blue-300 mr-2 max-w-32 truncate"
                >
                    {{ tag.name || __("New Tag") }}
                </div>

                <!-- Input (visible solo al editar) -->
                <input
                    v-else
                    v-model="tag.name"
                    @blur="finishEditing(tag, index)"
                    @keyup.enter="finishEditing(tag, index)"
                    @keyup.esc="cancelEditing(index)"
                    class="tag-edit-input bg-transparent border-none outline-none text-sm text-gray-900 dark:text-white w-32 px-1"
                    :placeholder="__('Enter tag name')"
                    ref="tagInputs"
                />

                <!-- Controls -->
                <div class="tag-actions flex items-center space-x-1">
                    <button
                        @click="
                            tag.editing
                                ? finishEditing(tag, index)
                                : startEditing(tag, index)
                        "
                        class="w-6 h-6 rounded-full flex items-center justify-center transition-colors duration-200"
                        :class="[
                            tag.editing
                                ? 'bg-green-500 hover:bg-green-600 text-white'
                                : 'bg-blue-200 dark:bg-blue-800 hover:bg-blue-300 dark:hover:bg-blue-700 text-blue-600 dark:text-blue-400',
                        ]"
                    >
                        <i
                            :class="
                                tag.editing
                                    ? 'mdi mdi-check text-xs'
                                    : 'mdi mdi-pencil text-xs'
                            "
                        ></i>
                    </button>

                    <button
                        @click="deleteTag(index)"
                        class="w-6 h-6 rounded-full bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-800 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors duration-200"
                    >
                        <i class="mdi mdi-delete text-xs"></i>
                    </button>

                    <button
                        v-if="tag.editing"
                        @click="cancelEditing(index)"
                        class="w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-400 flex items-center justify-center transition-colors duration-200"
                    >
                        <i class="mdi mdi-close text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Add Tag Button & Counter -->
        <div class="flex items-center justify-between">
            <button
                @click="addTag"
                class="flex items-center space-x-2 px-4 py-2 border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                :disabled="maxTags && internalTags.length >= maxTags"
            >
                <i class="mdi mdi-plus text-sm"></i>
                <span class="text-sm font-medium">{{ __("Add tag") }}</span>
            </button>

            <!-- Tags Counter -->
            <div class="text-xs text-gray-500 dark:text-gray-400">
                <span v-if="maxTags">
                    {{ internalTags.length }} / {{ maxTags }} {{ __("tags") }}
                </span>
                <span v-else> {{ internalTags.length }} {{ __("tags") }} </span>
            </div>
        </div>

        <!-- Max Tags Warning -->
        <div
            v-if="maxTags && internalTags.length >= maxTags"
            class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg"
        >
            <div
                class="flex items-center space-x-2 text-yellow-800 dark:text-yellow-300"
            >
                <i class="mdi mdi-alert-circle-outline"></i>
                <span class="text-sm font-medium">
                    {{ __("Maximum tags reached") }} ({{ maxTags }})
                </span>
            </div>
        </div>

        <!-- Duplicate Warning -->
        <div
            v-if="duplicateWarning"
            class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
        >
            <div class="flex items-center justify-between">
                <div
                    class="flex items-center space-x-2 text-red-800 dark:text-red-300"
                >
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <span class="text-sm font-medium">
                        {{ __("Tag already exists") }}
                    </span>
                </div>
                <button
                    @click="duplicateWarning = false"
                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                >
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TagsInput",
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
            duplicateWarning: false,
            tagInputs: [],
        };
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
                return;
            }

            this.internalTags.push({
                name: "",
                editing: true,
            });

            // Focus the new input
            this.$nextTick(() => {
                const inputs = this.$refs.tagInputs;
                if (inputs && inputs.length > 0) {
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
                this.duplicateWarning = true;
                this.cancelEditing(index);
                return;
            }

            this.internalTags[index] = {
                ...this.internalTags[index],
                name,
                editing: false,
            };
            this.emitUpdate();
            this.duplicateWarning = false;
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
            this.duplicateWarning = false;
        },

        isDuplicate(name, currentIndex) {
            return this.internalTags.some(
                (tag, index) => tag.name === name && index !== currentIndex
            );
        },

        async deleteTag(index) {
            const item = this.internalTags[index];

            if (item.links?.destroy) {
                try {
                    const res = await this.$server.delete(item.links.destroy);
                    if (res.status === 200) {
                        this.internalTags.splice(index, 1);
                        this.emitUpdate();
                        this.$notify?.({
                            message: "Tag deleted successfully",
                            type: "success",
                        });
                    }
                } catch (e) {
                    if (e?.response?.data?.message) {
                        this.$notify?.({
                            message: e.response.data.message,
                            type: "error",
                        });
                    }
                }
            } else {
                this.internalTags.splice(index, 1);
                this.emitUpdate();
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

<style scoped>
.tag-item {
    animation: slideIn 0.2s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tag-edit-input::placeholder {
    color: #9ca3af;
}

.dark .tag-edit-input::placeholder {
    color: #6b7280;
}
</style>
