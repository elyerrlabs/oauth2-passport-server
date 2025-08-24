<template>
    <div>
        <!-- Thumbnail Grid -->
        <div v-if="images.length" class="gallery-grid q-mb-md">
            <div
                v-for="(img, index) in images"
                :key="index"
                class="gallery-thumbnail"
                @click="openLightbox(index)"
            >
                <q-img
                    :src="img.url"
                    :ratio="1"
                    class="thumbnail-image"
                    spinner-color="primary"
                >
                    <template v-slot:error>
                        <div
                            class="absolute-full flex flex-center bg-negative text-white"
                        >
                            Failed to load
                        </div>
                    </template>

                    <!-- Delete Button -->
                    <q-btn
                        v-if="img.links?.delete"
                        flat
                        round
                        dense
                        icon="delete"
                        color="negative"
                        size="sm"
                        class="absolute-top-right q-ma-xs"
                        @click.stop="confirmDelete(index, img)"
                    />
                </q-img>
            </div>
        </div>

        <!-- Empty State Message -->
        <div v-else class="text-center text-grey q-pa-md">
            <q-icon name="image_not_supported" size="xl" />
            <div class="q-mt-sm">No images available</div>
        </div>

        <!-- Lightbox Viewer -->
        <q-dialog
            v-model="lightboxOpen"
            maximized
            transition-show="slide-up"
            transition-hide="slide-down"
        >
            <q-card class="lightbox-card">
                <!-- Close Button (Top Right) -->
                <q-btn
                    flat
                    round
                    dense
                    icon="close"
                    color="white"
                    class="absolute-top-right q-ma-md lightbox-close-btn"
                    @click="lightboxOpen = false"
                />

                <q-carousel
                    v-model="currentImageIndex"
                    animated
                    swipeable
                    infinite
                    arrows
                    navigation
                    control-color="primary"
                    height="100%"
                >
                    <q-carousel-slide
                        v-for="(img, index) in images"
                        :key="index"
                        :name="index"
                        class="column items-center justify-center"
                    >
                        <div class="full-width full-height flex flex-center">
                            <q-img
                                :src="img.url"
                                spinner-color="primary"
                                class="lightbox-image"
                                fit="contain"
                            />
                        </div>
                    </q-carousel-slide>
                </q-carousel>

                <!-- Close Button (Bottom Center) -->
                <q-btn
                    flat
                    label="Close"
                    icon="close"
                    color="white"
                    class="absolute-bottom-center q-mb-md lightbox-close-btn-bottom"
                    @click="lightboxOpen = false"
                />
            </q-card>
        </q-dialog>

        <!-- Confirmation Dialog -->
        <q-dialog v-model="confirmDialog" persistent>
            <q-card>
                <q-card-section class="row items-center">
                    <q-avatar
                        icon="delete"
                        color="negative"
                        text-color="white"
                    />
                    <span class="q-ml-sm"
                        >Are you sure you want to delete this image?</span
                    >
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Cancel" color="primary" v-close-popup />
                    <q-btn
                        flat
                        label="Delete"
                        color="negative"
                        @click="deleteImage"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
    props: {
        images: {
            type: Array,
            default: () => [],
        },
    },

    data() {
        return {
            currentImageIndex: 0,
            lightboxOpen: false,
            confirmDialog: false,
            imageToDelete: null,
        };
    },

    methods: {
        openLightbox(index) {
            this.currentImageIndex = index;
            this.lightboxOpen = true;
        },

        confirmDelete(index, img) {
            this.imageToDelete = { index, img };
            this.confirmDialog = true;
        },

        async deleteImage() {
            if (!this.imageToDelete) return;

            const { index, img } = this.imageToDelete;

            try {
                const res = await this.$server.delete(img.links.delete);

                if (res.status === 200 || res.status === 204) {
                    this.$emit("image-deleted", index);
                    this.$q.notify({
                        message: "Image deleted successfully",
                        color: "positive",
                        icon: "check",
                        position: "top",
                    });
                }
            } catch (error) {
                this.$q.notify({
                    message: "Error deleting image",
                    color: "negative",
                    icon: "error",
                    position: "top",
                });
                console.error("Error deleting image:", error);
            } finally {
                this.confirmDialog = false;
                this.imageToDelete = null;
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
}

.gallery-thumbnail {
    cursor: pointer;
    transition: transform 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2), 0 2px 2px rgba(0, 0, 0, 0.14),
        0 3px 1px -2px rgba(0, 0, 0, 0.12);

    &:hover {
        transform: scale(1.03);
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2), 0 5px 8px rgba(0, 0, 0, 0.14),
            0 1px 14px rgba(0, 0, 0, 0.12);
    }
}

.thumbnail-image {
    height: 100%;
    width: 100%;
}

.lightbox-card {
    background-color: rgba(0, 0, 0, 0.9);
}

.lightbox-image {
    max-width: 90vw;
    max-height: 90vh;
}

.lightbox-close-btn {
    z-index: 3000;
    background: rgba(0, 0, 0, 0.5);
}

.lightbox-close-btn-bottom {
    z-index: 3000;
    background: rgba(0, 0, 0, 0.5);
    padding: 8px 24px;
    border-radius: 20px;
}

@media (max-width: 599px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 8px;
    }
}
</style>
