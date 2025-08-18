<template>
    <div class="q-pa-md q-gutter-sm">
        <q-btn
            outline
            color="negative"
            @click="dialog = true"
            icon="mdi-delete-outline"
            size="sm"
        >
            Delete
        </q-btn>

        <q-dialog
            v-model="dialog"
            persistent
            transition-show="scale"
            transition-hide="scale"
        >
            <q-card class="w-100 py-4">
                <q-card-section class="text-center">
                    <h6 class="text-gray-500">Delete category</h6>
                </q-card-section>
                <q-card-section>
                    Are you share you want to remove this category
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn
                        outline
                        color="positive"
                        label="Accept"
                        @click="destroy"
                    />

                    <q-btn
                        outline
                        color="secondary"
                        label="Close"
                        @click="dialog = false"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
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

    data() {
        return {
            dialog: false,
        };
    },

    methods: {
        async destroy() {
            try {
                const res = await this.$server.delete(this.item.links.destroy);

                if (res.status == 200) {
                    this.$emit("deleted", true);
                    this.$q.notify({
                        message: "Category has been deleted successful",
                        color: "positive",
                        icon: "mdi-check-circle-outline",
                    });
                }
            } catch (err) {
                if (err?.response?.data?.message) {
                    this.$q.notify({
                        message: err.response?.data?.message,
                        color: "negative",
                        icon: "mdi-alert-circle-outline",
                    });
                }
            } finally {
                this.dialog = false;
            }
        },
    },
};
</script>
