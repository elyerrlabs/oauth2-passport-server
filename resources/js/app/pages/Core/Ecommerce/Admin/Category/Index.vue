<template>
    <v-admin-ecommerce-layout>
        <div class="q-ma-sm">
            <div class="row justify-between items-center q-mb-md">
                <div class="text-h6 q-ma-sm">Categories</div>
                <v-create @created="getCategories" />
            </div>

            <q-table
                :rows="groups"
                :columns="columns"
                row-key="id"
                flat
                bordered
                :pagination="{
                    page: search.page,
                    rowsPerPage: search.per_page,
                }"
                hide-pagination
                class="q-mb-md"
            >
                <template v-slot:body-cell-actions="props">
                    <q-td>
                        <div class="row q-gutter-sm justify-end">
                            <v-create
                                :item="props.row"
                                @created="getCategories"
                                title="Update"
                            />
                            <v-delete
                                :item="props.row"
                                @deleted="getCategories"
                            />
                        </div>
                    </q-td>
                </template>
            </q-table>

            <div class="row justify-center">
                <q-pagination
                    v-model="search.page"
                    :max="pages.total_pages"
                    color="primary"
                    size="sm"
                    boundary-numbers
                    direction-links
                    class="q-pa-xs q-gutter-sm rounded-borders"
                />
            </div>
        </div>
    </v-admin-ecommerce-layout>
</template>

<script>
import VCreate from "./Create.vue";
import VDelete from "./Delete.vue";

export default {
    components: {
        VCreate,
        VDelete,
    },

    data() {
        return {
            groups: [],
            params: [{ key: "Name", value: "name" }],
            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
            columns: [
                {
                    name: "name",
                    label: "Name",
                    field: "name",
                    align: "center",
                },
                {
                    name: "slug",
                    label: "Slug",
                    field: "slug",
                    align: "center",
                },
                {
                    name: "icon",
                    label: "Icon",
                    field: (row) => row.icon?.icon,
                    align: "center",
                },
                {
                    name: "published",
                    label: "Published",
                    field: (row) => (row.published ? "Yes" : "No"),
                    align: "center",
                },
                {
                    name: "featured",
                    label: "Featured",
                    field: (row) => (row.featured ? "Yes" : "No"),
                    align: "center",
                },
                {
                    name: "actions",
                    label: "Actions",
                    align: "center",
                },
            ],
        };
    },

    created() {
        this.getCategories();
    },

    watch: {
        "search.page"(value) {
            this.getCategories();
        },
        "search.per_page"(value) {
            this.getCategories();
        },
    },

    methods: {
        searching(param) {
            this.getCategories(param);
        },

        getCategories(param = null) {
            const params = { ...this.search, ...param };

            this.$server
                .get(this.$page.props.route, {
                    params: params,
                })
                .then((res) => {
                    this.groups = res.data.data;
                    let meta = res.data.meta;
                    this.pages = meta.pagination;
                    this.search.page = meta.pagination.current_page;
                })
                .catch((err) => {
                    console.error("Error loading categories:", err);
                });
        },
    },
};
</script>
