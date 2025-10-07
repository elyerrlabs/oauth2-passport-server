<template>
    <div class="tags-container">
        <q-card-section class="q-pa-none q-mb-md">
            <div class="row items-center q-gutter-sm">
                <q-icon name="mdi-tag-multiple" color="primary" size="md" />
                <div class="text-h6 text-primary">{{ __("Tags") }}</div>
            </div>
        </q-card-section>

        <div class="tags-list q-gutter-sm q-mb-md">
            <div
                v-for="(tag, index) in internalTags"
                :key="index"
                class="tag-item row items-center"
                :class="{ 'editing-tag': tag.editing }"
            >
                <!-- Tag name (visible cuando no se está editando) -->
                <div v-if="!tag.editing" class="tag-label">
                    {{ tag.name || __("New Tag") }}
                </div>

                <!-- Input (visible solo al editar) -->
                <q-input
                    v-else
                    v-model="tag.name"
                    dense
                    autofocus
                    @blur="finishEditing(tag, index)"
                    @keyup.enter="finishEditing(tag, index)"
                    @keyup.esc="cancelEditing(index)"
                    class="tag-edit-input"
                    :rules="[
                        (val) => !!val.trim() || __('Tag cannot be empty'),
                    ]"
                />

                <!-- Controls -->
                <div class="tag-actions">
                    <q-btn
                        round
                        dense
                        flat
                        :icon="tag.editing ? 'check' : 'edit'"
                        size="sm"
                        @click="
                            tag.editing
                                ? finishEditing(tag, index)
                                : startEditing(tag, index)
                        "
                        class="edit-btn"
                        :color="tag.editing ? 'positive' : 'primary'"
                    />
                    <q-btn
                        round
                        dense
                        flat
                        color="negative"
                        icon="delete"
                        size="sm"
                        @click="deleteTag(index)"
                        class="delete-btn"
                    />
                    <q-btn
                        v-if="tag.editing"
                        round
                        dense
                        flat
                        icon="close"
                        size="sm"
                        @click="cancelEditing(index)"
                        class="cancel-btn"
                        color="grey"
                    />
                </div>
            </div>
        </div>

        <q-btn
            color="primary"
            size="sm"
            outline
            icon="add"
            :label="__('Add tag')"
            @click="addTag"
            class="add-btn"
        />
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
                // Validar que sea un array de objetos con propiedad name o strings
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
                this.$q.notify({
                    message: `Maximum ${this.maxTags} tags allowed`,
                    color: "warning",
                });
                return;
            }

            this.internalTags.push({
                name: "",
                editing: true,
            });
        },

        startEditing(tag, index) {
            this.lastValidState = [...this.internalTags];
            this.internalTags[index].editing = true;
        },

        finishEditing(tag, index) {
            const name = tag.name.trim();

            if (!name) {
                this.deleteTag(index);
                return;
            }

            if (!this.allowDuplicates && this.isDuplicate(name, index)) {
                this.$q.notify({
                    message: "Tag already exists",
                    color: "negative",
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
            // Restaurar el último estado válido si es un nuevo tag vacío
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

            if (item.links?.destroy) {
                try {
                    const res = await this.$server.delete(item.links.destroy);
                    if (res.status === 200) {
                        this.internalTags.splice(index, 1);
                        this.emitUpdate();
                        this.$q.notify({
                            message: "Tag deleted",
                            color: "positive",
                        });
                    }
                } catch (e) {
                    if (e?.response?.data?.message) {
                        this.$q.notify({
                            type: "negative",
                            message: e.response.data.message,
                            timeout: 3000,
                        });
                    }
                }
            } else {
                this.internalTags.splice(index, 1);
                this.emitUpdate();
            }
        },

        emitUpdate() {
            // Siempre emitir como array de objetos con propiedad name
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

<style lang="scss" scoped>
.tags-container {
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;

    .tags-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;

        .tag-item {
            background: #e0f2fe;
            border-radius: 16px;
            padding: 4px 8px 4px 12px;
            transition: all 0.3s ease;
            border: 1px solid #bae6fd;

            &:hover {
                background: #bae6fd;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            &.editing-tag {
                background: white;
                border: 1px solid #0ea5e9;
            }

            .tag-label {
                font-size: 0.875rem;
                color: #0369a1;
                margin-right: 4px;
                max-width: 150px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .tag-edit-input {
                width: 120px;

                .q-field__control {
                    height: 24px;
                    min-height: unset;
                }

                .q-field__native {
                    padding: 0;
                }
            }

            .tag-actions {
                display: flex;
                align-items: center;
                gap: 2px;

                .q-btn {
                    width: 24px;
                    height: 24px;
                }
            }
        }
    }

    .add-btn {
        margin-top: 8px;
    }

    .title-icon {
        color: var(--q-primary);
    }
}
</style>
