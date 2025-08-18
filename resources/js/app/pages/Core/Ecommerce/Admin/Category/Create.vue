<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn
            outline
            color="positive"
            @click="open"
            icon="mdi-plus-circle"
            size="sm"
        >
            {{ title }}
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            :maximized="true"
            transition-show="slide-up"
            transition-hide="slide-down"
        >
            <q-card class="q-pa-md">
                <q-card-section>
                    <h6 class="text-gray-500">{{ title }}</h6>
                </q-card-section>
                <q-card-section>
                    <div class="row q-col-gutter-md">
                        <!-- Name -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-input
                                class="required"
                                v-model="form.name"
                                label="Name"
                                outline
                                :error="!!errors.name"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.name"></v-error>
                                </template>
                            </q-input>
                        </div>

                        <!-- Icon -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-input
                                outline
                                class="required"
                                v-model="form.icon"
                                label="Icon"
                                :error="!!errors.icon"
                                hint="Choose a Material Design icon name"
                                bottom-slots
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.icon" />
                                </template>
                                <template v-slot:append>
                                    <q-btn
                                        outline
                                        flat
                                        round
                                        icon="mdi-link-variant"
                                        color="primary"
                                        @click="openIconLibrary"
                                        :title="'View icon library'"
                                    />
                                </template>
                            </q-input>
                        </div>

                        <!-- Featured -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-checkbox
                                v-model="form.featured"
                                label="Featured"
                                :error="!!errors.featured"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.featured" />
                                </template>
                            </q-checkbox>
                        </div>

                        <!-- Published -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <q-checkbox
                                v-model="form.published"
                                label="Published"
                                :error="!!errors.featured"
                            >
                                <template v-slot:error>
                                    <v-error :error="errors.featured" />
                                </template>
                            </q-checkbox>
                        </div>

                        <div class="col-12">
                            <v-editor
                                class="required"
                                v-model="form.description"
                                label="Description"
                            />
                            <v-error :error="errors.description" />
                        </div>

                        <div class="col-12">
                            <v-file-uploader
                                class="required"
                                type="images"
                                @uploaded="loadImages"
                            ></v-file-uploader>
                            <v-error :error="errors.images"></v-error>
                        </div>

                        <div class="col-12">
                            <v-gallery :images="current_images"></v-gallery>
                        </div>
                    </div>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn
                        outline
                        color="positive"
                        label="Accept"
                        @click="create"
                    />

                    <q-btn
                        outline
                        color="secondary"
                        label="Close"
                        @click="close"
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
            form: {},
            errors: {},
            current_images: [],
        };
    },

    methods: {
        /**
         * Clean the form when it is closed
         */
        close() {
            this.category = {};
            this.errors = {};
            this.dialog = false;
        },

        open() {
            this.form.id = "";
            this.form.name = "";
            this.form.icon = "";
            this.form.icon = "";
            this.form.description = "";
            this.form.published = false;
            this.form.featured = false;
            this.form.images = [];
            this.errors = {};
            if (this.item) {
                this.form.id = this.item.id;
                this.form.name = this.item.name;
                this.form.description = this.item.description;
                this.form.icon = this.item?.icon?.icon;
                this.form.featured = this.item.featured;
                this.form.published = this.item.published;
                this.current_images = this.item?.images;
            }

            this.dialog = true;
        },

        loadImages(files) {
            this.form.images = files;
        },

        openIconLibrary() {
            window.open("https://pictogrammers.com/library/mdi/", "_blank");
        },

        /**
         * Create a new client
         */
        async create() {
            const payload = new FormData();

            payload.append("id", this.form.id);
            payload.append("name", this.form.name);
            payload.append("description", this.form.description);
            payload.append("icon", this.form.icon);
            payload.append("featured", this.form.featured);
            payload.append("published", this.form.published);

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
                    this.form = {};
                    this.errors = {};
                    this.$emit("created", true);
                    this.dialog = false;

                    this.$q.notify({
                        type: "positive",
                        message:
                            res.status === 201
                                ? "Category created successfully"
                                : "Category updated successfully",
                        position: "top",
                        timeout: 3000,
                    });
                }
            } catch (e) {
                if (e?.response?.data?.errors && e?.response?.status == 422) {
                    this.errors = e.response.data.errors;
                }
            }
        },
    },
};
</script>
