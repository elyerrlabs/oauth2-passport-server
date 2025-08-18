<template>
    <div class="attributes-container">
        <q-card-section class="q-pa-none q-mb-md">
            <div class="row items-center q-gutter-sm">
                <q-icon name="mdi-pencil-plus" color="primary" size="md" />
                <div class="text-h6 text-primary">Attributes</div>
            </div>
        </q-card-section>

        <!-- Dynamic attributes list -->
        <div
            v-for="(attr, index) in internalAttributes"
            :key="index"
            class="attribute-row"
        >
            <!-- Name -->
            <q-input
                class="attribute-item"
                filled
                v-model="attr.name"
                label="Name"
                dense
                @update:model-value="emitUpdate"
            />

            <!-- Type -->
            <q-select
                class="attribute-item"
                filled
                v-model="attr.type"
                :options="typeOptions"
                label="Type"
                dense
                @update:model-value="emitUpdate"
            />

            <!-- Value -->
            <q-input
                class="attribute-item"
                filled
                v-model="attr.value"
                label="Value"
                dense
                @update:model-value="emitUpdate"
            />

            <!-- Stock -->
            <q-input
                class="attribute-item"
                filled
                type="number"
                v-model="attr.stock"
                label="Stock"
                dense
                @update:model-value="emitUpdate"
            />

            <!-- Widget -->
            <q-select
                class="attribute-item"
                filled
                v-model="attr.widget"
                :options="widgetOptions"
                label="Widget"
                dense
                @update:model-value="emitUpdate"
            />

            <!-- Multiple -->
            <div class="attribute-item checkbox-container">
                <q-checkbox
                    v-model="attr.multiple"
                    label="Multiple"
                    dense
                    @update:model-value="emitUpdate"
                />
            </div>

            <!-- Delete button -->
            <div class="attribute-item btn-container">
                <q-btn
                    outline
                    color="negative"
                    icon="delete"
                    @click="deleteAttribute(index)"
                    class="delete-btn"
                />
            </div>
        </div>

        <!-- Add new attribute -->
        <q-btn
            color="primary"
            size="sm"
            outline
            icon="add"
            label="Add attribute"
            @click="addAttribute"
        />
    </div>
</template>

<script>
export default {
    name: "AttributesInput",
    emits: ["update:modelValue"],
    props: {
        modelValue: {
            type: Array,
            required: true,
            default: () => [],
            validator: (value) => {
                return value.every(
                    (attr) =>
                        typeof attr === "object" &&
                        "name" in attr &&
                        "type" in attr &&
                        "value" in attr &&
                        "widget" in attr &&
                        "multiple" in attr &&
                        "stock" in attr
                );
            },
        },
    },

    data() {
        return {
            internalAttributes: [],
            typeOptions: ["string", "integer", "boolean"],
            widgetOptions: ["checkbox", "select", "radio", "slide"],
            lastValidState: [],
        };
    },

    watch: {
        modelValue: {
            immediate: true,
            deep: true,
            handler(newVal) {
                this.lastValidState = JSON.parse(JSON.stringify(newVal));
                this.internalAttributes = this.normalizeAttributes(newVal);
            },
        },
    },

    methods: {
        normalizeAttributes(attrs) {
            return attrs.map((attr) => ({
                name: attr.name || null,
                type: attr.type || "string",
                value: attr.value || null,
                widget: attr.widget || "checkbox",
                multiple: attr.multiple || false,
                stock: attr.stock || 0,
                ...(attr.links ? { links: attr.links } : {}),
            }));
        },

        addAttribute() {
            this.internalAttributes.push({
                name: null,
                type: "string",
                value: null,
                widget: "checkbox",
                multiple: false,
                stock: 0,
            });
            this.emitUpdate();
        },

        emitUpdate() {
            const attributes = JSON.parse(
                JSON.stringify(this.internalAttributes)
            );
            this.$emit("update:modelValue", attributes);
        },

        async deleteAttribute(index) {
            const item = this.internalAttributes[index];

            if (!item.links?.destroy) {
                this.internalAttributes.splice(index, 1);
                this.emitUpdate();
                return;
            }

            try {
                const res = await this.$server.delete(item.links.destroy);
                if (res.status === 200) {
                    this.internalAttributes.splice(index, 1);
                    this.emitUpdate();
                    this.$q.notify({
                        message: "Attribute deleted",
                        color: "positive",
                        icon: "check",
                    });
                }
            } catch (error) {
                this.$q.notify({
                    message: "Error deleting attribute",
                    color: "negative",
                    icon: "error",
                });
                console.error("Error deleting attribute:", error);
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.attributes-container {
    width: 100%;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;

    .attribute-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        margin-bottom: 12px;

        .attribute-item {
            flex: 1 1 100%;

            &.checkbox-container {
                display: flex;
                align-items: center;
                min-height: 40px; /* Match input height */
            }

            &.btn-container {
                display: flex;
                justify-content: flex-end;
            }
        }

        .delete-btn {
            min-width: 40px;
        }
    }

    .add-btn {
        margin-top: 16px;
    }

    /* Medium screens (750px - 945px) */
    @media (min-width: 750px) and (max-width: 945px) {
        .attribute-row {
            .attribute-item {
                flex: 1 1 calc(50% - 6px); /* 2 items per row with gap */
            }
        }
    }

    /* Large screens (>945px) */
    @media (min-width: 945px) {
        .attribute-row {
            flex-wrap: nowrap;

            .attribute-item {
                flex: 1 1 auto;
                min-width: 0;

                &.checkbox-container {
                    flex: 0 1 auto;
                    padding: 0 8px;
                }

                &.btn-container {
                    flex: 0 0 auto;
                }
            }
        }
    }
}

.title-icon {
    color: var(--q-primary);
}
</style>
