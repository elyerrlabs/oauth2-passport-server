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
    <div class="q-pa-md q-gutter-sm">
        <!-- Create/Update Button -->
        <q-btn
            color="primary"
            outline
            @click="open"
            :icon="item ? 'mdi-pencil' : 'mdi-plus'"
            :label="title"
            class="action-btn shadow-3"
            :class="{ 'text-white': !item?.id }"
            size="sm"
        >
        </q-btn>

        <!-- Category Form Dialog -->
        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
            full-width
        >
            <q-card class="category-form-dialog rounded-borders">
                <!-- Dialog Header -->
                <q-card-section class="dialog-header bg-primary text-white">
                    <div class="row items-center">
                        <q-icon
                            :name="
                                item ? 'mdi-pencil-circle' : 'mdi-plus-circle'
                            "
                            size="28px"
                            class="q-mr-sm"
                        />
                        <div class="text-h5 text-weight-bold">
                            {{ title }} {{ __("Category") }}
                        </div>
                        <q-space />
                        <q-btn
                            icon="close"
                            flat
                            round
                            dense
                            v-close-popup
                            class="text-white"
                            @click="close"
                        />
                    </div>
                </q-card-section>

                <!-- Form Content -->
                <q-card-section class="dialog-content scroll">
                    <div class="q-gutter-y-lg">
                        <!-- Basic Information Section -->
                        <div class="section-container">
                            <div class="section-title">
                                <q-icon
                                    name="mdi-information"
                                    class="q-mr-sm"
                                />
                                {{ __("Basic Information") }}
                            </div>

                            <div class="row q-col-gutter-md">
                                <!-- Name -->
                                <div class="col-12 col-md-6">
                                    <q-input
                                        outlined
                                        v-model="form.name"
                                        :label="__('Category Name *')"
                                        :error="!!errors.name"
                                        color="primary"
                                        class="custom-input"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon name="mdi-tag" />
                                        </template>
                                        <template v-slot:error>
                                            <v-error
                                                :error="errors.name"
                                            ></v-error>
                                        </template>
                                    </q-input>
                                </div>

                                <!-- Icon -->
                                <div class="col-12 col-md-6">
                                    <q-input
                                        outlined
                                        v-model="form.icon"
                                        :label="__('Icon *')"
                                        :error="!!errors.icon"
                                        placeholder="mdi-image"
                                        color="primary"
                                        class="custom-input"
                                        :hint="__('Material Design icon name')"
                                    >
                                        <template v-slot:prepend>
                                            <q-icon name="mdi-emoticon" />
                                        </template>
                                        <template v-slot:append>
                                            <q-btn
                                                flat
                                                round
                                                icon="mdi-launch"
                                                color="primary"
                                                @click="openIconLibrary"
                                                class="icon-library-btn"
                                            >
                                                <q-tooltip>{{
                                                    __("View Icon Library")
                                                }}</q-tooltip>
                                            </q-btn>
                                        </template>
                                        <template v-slot:error>
                                            <v-error :error="errors.icon" />
                                        </template>
                                    </q-input>
                                </div>

                                <!-- Status Toggles -->
                                <div class="col-12 col-md-6">
                                    <div class="toggle-group">
                                        <q-toggle
                                            v-model="form.published"
                                            :label="__('Published')"
                                            color="positive"
                                            :error="!!errors.published"
                                            icon="mdi-eye"
                                            class="custom-toggle"
                                        >
                                            <q-tooltip>{{
                                                __(
                                                    "Make category visible to users"
                                                )
                                            }}</q-tooltip>
                                        </q-toggle>
                                        <v-error :error="errors.published" />
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="toggle-group">
                                        <q-toggle
                                            v-model="form.featured"
                                            :label="__('Featured')"
                                            color="accent"
                                            :error="!!errors.featured"
                                            icon="mdi-star"
                                            class="custom-toggle"
                                        >
                                            <q-tooltip>{{
                                                __(
                                                    "Highlight this category as featured"
                                                )
                                            }}</q-tooltip>
                                        </q-toggle>
                                        <v-error :error="errors.featured" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="section-container">
                            <div class="section-title">
                                <q-icon name="mdi-text" class="q-mr-sm" />
                                {{ __("Description") }}
                            </div>
                            <div
                                class="text-caption text-weight-medium q-mb-xs"
                            >
                                {{
                                    __(
                                        "Category description (supports rich text formatting)"
                                    )
                                }}
                            </div>
                            <v-editor
                                class="required"
                                v-model="form.description"
                                :label="__('Category Description')"
                            />
                            <v-error :error="errors.description" />
                        </div>

                        <!-- Images Section -->
                        <div class="section-container">
                            <div class="section-title">
                                <q-icon name="mdi-image" class="q-mr-sm" />
                                {{ __("Category Images") }}
                            </div>

                            <div class="q-mb-md">
                                <v-file-uploader
                                    class="required"
                                    type="images"
                                    @uploaded="loadImages"
                                ></v-file-uploader>
                                <v-error :error="errors.images"></v-error>
                            </div>

                            <!-- Image Gallery -->
                            <div
                                v-if="current_images && current_images.length"
                                class="gallery-section"
                            >
                                <div
                                    class="text-caption text-weight-medium q-mb-sm"
                                >
                                    {{ __("Current Images") }}
                                </div>
                                <v-gallery
                                    :images="current_images"
                                    class="category-gallery"
                                ></v-gallery>
                            </div>
                        </div>
                    </div>
                </q-card-section>

                <!-- Dialog Actions -->
                <q-card-actions align="right" class="dialog-actions q-pa-md">
                    <q-btn
                        :label="__('Cancel')"
                        color="grey"
                        @click="close"
                        outline
                        class="action-btn"
                        icon="mdi-close"
                    />
                    <q-btn
                        :label="item ? __('Update') : __('Create')"
                        color="primary"
                        @click="create"
                        class="action-btn"
                        :icon="item ? 'mdi-check' : 'mdi-plus'"
                        unelevated
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    emits: ["created"],

    props: {
        title: {
            type: String,
            default: "Create",
        },
        item: {
            type: Object,
            required: false,
        },
    },

    data() {
        return {
            dialog: false,
            form: {
                id: "",
                name: "",
                icon: "",
                description: "",
                published: false,
                featured: false,
                images: [],
            },
            errors: {},
            current_images: [],
        };
    },

    methods: {
        close() {
            this.form = {
                id: "",
                name: "",
                icon: "",
                description: "",
                published: false,
                featured: false,
                images: [],
            };
            this.errors = {};
            this.current_images = [];
            this.dialog = false;
        },

        open() {
            this.form = {
                id: "",
                name: "",
                icon: "",
                description: "",
                published: false,
                featured: false,
                images: [],
            };
            this.errors = {};

            if (this.item) {
                this.form.id = this.item.id;
                this.form.name = this.item.name;
                this.form.description = this.item.description;
                this.form.icon = this.item?.icon?.icon || "";
                this.form.featured = this.item.featured;
                this.form.published = this.item.published;
                this.current_images = this.item?.images || [];
            }

            this.dialog = true;
        },

        loadImages(files) {
            this.form.images = files;
        },

        openIconLibrary() {
            window.open("https://pictogrammers.com/library/mdi/", "_blank");
        },

        async create() {
            const payload = new FormData();

            payload.append("id", this.form.id);
            payload.append("name", this.form.name);
            payload.append("description", this.form.description);
            payload.append("icon", this.form.icon);
            payload.append("featured", this.form.featured ? 1 : 0);
            payload.append("published", this.form.published ? 1 : 0);

            if (this.form?.images?.length > 0) {
                this.form.images.forEach((file) => {
                    payload.append("images[]", file);
                });
            }

            try {
                const res = await this.$server.post(
                    this.$page.props.route,
                    payload,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                if (res.status === 200 || res.status === 201) {
                    this.close();
                    this.$emit("created", true);

                    this.$q.notify({
                        type: "positive",
                        message: this.form.id
                            ? this.__("Category updated successfully")
                            : this.__("Category created successfully"),
                        timeout: 3000,
                        icon: "mdi-check-circle",
                        position: "top-right",
                    });
                }
            } catch (e) {
                if (e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                    this.$q.notify({
                        type: "warning",
                        message: this.__("Please check the input fields"),
                        timeout: 3000,
                        icon: "update",
                    });
                }

                if (e?.response?.data?.message) {
                    this.$q.notify({
                        type: "negative",
                        message: e.response.data.message,
                        timeout: 3000,
                    });
                }
            }
        },
    },
};
</script>

<style scoped>
/* CSS Variables for Theme Consistency */
:root {
    --color-primary: #1976d2;
    --color-secondary: #26a69a;
    --color-accent: #ff6b35;
    --color-positive: #21ba45;
    --color-negative: #c10015;
    --color-warning: #f2c037;
    --border-radius: 12px;
    --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition-speed: 0.3s;
}

.action-btn {
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 500;
    transition: transform var(--transition-speed) ease,
        box-shadow var(--transition-speed) ease;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
}

.category-form-dialog {
    border-radius: var(--border-radius);
    overflow: hidden;
    width: 100%;
    max-height: 90vh;
}

.dialog-header {
    padding: 20px 24px;
}

.dialog-content {
    padding: 24px;
    max-height: 60vh;
}

.dialog-actions {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    background: var(--color-light);
}

.section-container {
    padding: 20px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: var(--border-radius);
    background: white;
    margin-bottom: 20px;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-primary);
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid rgba(0, 0, 0, 0.06);
}

.custom-input :deep(.q-field__control) {
    border-radius: 8px;
}

.custom-toggle :deep(.q-toggle__label) {
    font-weight: 500;
}

.toggle-group {
    padding: 8px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    background: #fafafa;
}

.icon-library-btn {
    transition: transform var(--transition-speed) ease;
}

.icon-library-btn:hover {
    transform: scale(1.1);
}

.gallery-section {
    margin-top: 16px;
}

.category-gallery {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: var(--border-radius);
    padding: 12px;
}

/* Responsive adjustments */
@media (max-width: 1023px) {
    .category-form-dialog {
        max-width: 95vw;
    }

    .dialog-content {
        padding: 16px;
        max-height: 70vh;
    }

    .section-container {
        padding: 16px;
    }
}

@media (max-width: 599px) {
    .dialog-header .text-h5 {
        font-size: 1.25rem;
    }

    .action-btn {
        min-width: 100px;
        padding: 6px 12px;
    }

    .toggle-group {
        margin-bottom: 12px;
    }
}
</style>
