<template>
    <div>
        <q-card flat bordered class="file-uploader q-pa-md">
            <q-card-section class="q-pa-none q-mb-md">
                <div class="row items-center q-gutter-sm">
                    <q-icon name="cloud_upload" color="primary" size="md" />
                    <div class="text-h6 text-primary">Upload files</div>
                </div>
            </q-card-section>

            <q-card-section class="q-pa-none">
                <!-- Hidden file input -->
                <input
                    ref="fileInput"
                    type="file"
                    :accept="acceptedTypes"
                    :multiple="multiple"
                    hidden
                    @change="handleFiles"
                />

                <!-- Button to select files -->
                <q-btn
                    color="primary"
                    icon="attach_file"
                    label="Select files"
                    @click="triggerInput"
                    class="q-mb-md"
                />

                <!-- Image preview -->
                <div
                    v-if="type === 'images' && files.length > 0"
                    class="row q-col-gutter-sm q-mb-md"
                >
                    <div
                        v-for="(fileObj, index) in files"
                        :key="index"
                        class="col-6 col-sm-4 col-md-3"
                    >
                        <q-card flat bordered>
                            <q-img
                                :src="fileObj.preview"
                                :ratio="1"
                                class="preview-image"
                                spinner-color="primary"
                            >
                                <template v-slot:error>
                                    <div
                                        class="absolute-full flex flex-center bg-negative text-white"
                                    >
                                        Error loading image
                                    </div>
                                </template>
                            </q-img>

                            <q-card-actions align="around" class="q-pa-sm">
                                <q-btn
                                    flat
                                    dense
                                    round
                                    icon="delete"
                                    color="negative"
                                    @click="removeFile(index)"
                                    size="sm"
                                />
                                <q-tooltip>Delete image</q-tooltip>
                            </q-card-actions>
                        </q-card>
                    </div>
                </div>

                <!-- File list -->
                <q-list
                    v-if="type === 'files' && files.length > 0"
                    bordered
                    separator
                    class="rounded-borders"
                >
                    <q-item v-for="(fileObj, index) in files" :key="index">
                        <q-item-section avatar>
                            <q-icon
                                :name="getFileIcon(fileObj.file)"
                                color="primary"
                            />
                        </q-item-section>

                        <q-item-section>
                            <q-item-label>{{ fileObj.file.name }}</q-item-label>
                            <q-item-label caption>
                                {{ formatFileSize(fileObj.file.size) }}
                            </q-item-label>
                        </q-item-section>

                        <q-item-section side>
                            <q-btn
                                flat
                                round
                                dense
                                icon="delete"
                                color="negative"
                                @click="removeFile(index)"
                            />
                        </q-item-section>
                    </q-item>
                </q-list>

                <!-- Message when there are no files -->
                <div
                    v-if="files.length === 0"
                    class="text-center q-pa-md text-grey-6"
                >
                    <q-icon name="folder_open" size="lg" />
                    <div class="q-mt-sm">No files selected</div>
                </div>
            </q-card-section>

            <!-- Progress bar -->
            <q-linear-progress
                v-if="uploading"
                indeterminate
                color="primary"
                class="q-mt-md"
            />
        </q-card>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: "files",
            validator: (value) => ["images", "files"].includes(value),
        },
        multiple: {
            type: Boolean,
            default: true,
        },
        maxFiles: {
            type: Number,
            default: null,
        },
        maxFileSize: {
            type: Number,
            default: 10, // MB
        },
    },

    emits: ["uploaded", "error"],

    data() {
        return {
            files: [],
            uploading: false,
        };
    },

    computed: {
        acceptedTypes() {
            return this.type === "images" ? "image/*" : "*/*";
        },
    },

    methods: {
        triggerInput() {
            this.$refs.fileInput.click();
        },

        async handleFiles(event) {
            const selectedFiles = Array.from(event.target.files);

            // Validar número máximo de archivos
            if (
                this.maxFiles &&
                this.files.length + selectedFiles.length > this.maxFiles
            ) {
                this.$emit(
                    "error",
                    `Solo puedes subir un máximo de ${this.maxFiles} archivos`
                );
                return;
            }

            // Procesar cada archivo
            for (const file of selectedFiles) {
                // Validar tamaño máximo
                if (file.size > this.maxFileSize * 1024 * 1024) {
                    this.$emit(
                        "error",
                        `El archivo ${file.name} excede el tamaño máximo de ${this.maxFileSize}MB`
                    );
                    continue;
                }

                const fileObj = { file };

                if (this.type === "images" && file.type.startsWith("image/")) {
                    fileObj.preview = await this.createPreview(file);
                }

                this.files.push(fileObj);
            }

            this.emitUploaded();
            event.target.value = null;
        },

        createPreview(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => resolve(e.target.result);
                reader.readAsDataURL(file);
            });
        },

        removeFile(index) {
            const removed = this.files.splice(index, 1)[0];
            if (removed.preview) {
                URL.revokeObjectURL(removed.preview);
            }
            this.emitUploaded();
        },

        emitUploaded() {
            this.$emit(
                "uploaded",
                this.files.map((f) => f.file)
            );
        },

        getFileIcon(file) {
            const extension = file.name.split(".").pop().toLowerCase();
            const icons = {
                pdf: "picture_as_pdf",
                doc: "description",
                docx: "description",
                xls: "table_chart",
                xlsx: "table_chart",
                ppt: "slideshow",
                pptx: "slideshow",
                jpg: "image",
                jpeg: "image",
                png: "image",
                gif: "image",
                zip: "folder_zip",
                rar: "folder_zip",
                mp3: "audiotrack",
                mp4: "movie",
                txt: "text_snippet",
            };
            return icons[extension] || "insert_drive_file";
        },

        formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return (
                parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + " " + sizes[i]
            );
        },
    },

    beforeUnmount() {
        this.files.forEach((f) => {
            if (f.preview) {
                URL.revokeObjectURL(f.preview);
            }
        });
    },
};
</script>

<style lang="scss" scoped>
.file-uploader {
    .preview-image {
        max-height: 200px;
        cursor: pointer;
        transition: transform 0.3s ease;

        &:hover {
            transform: scale(1.02);
        }
    }
}
</style>
