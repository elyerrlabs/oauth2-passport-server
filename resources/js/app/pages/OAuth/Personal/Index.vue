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
-->
<template>
    <v-user-layout>
        <q-toolbar>
            <q-toolbar-title>List of API KEY</q-toolbar-title>
            <v-create @created="getPersonalAccessToken()" />
        </q-toolbar>

        <q-table
            flat
            bordered
            :rows="tokens"
            :columns="columns"
            row-key="name"
            hide-bottom
            :rows-per-page-options="[search.per_page]"
        >
            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <v-delete
                        @deleted="getPersonalAccessToken"
                        :item="props.row"
                    />
                </q-td>
            </template>
        </q-table>

        <div class="row justify-center q-mt-md">
            <q-pagination
                v-model="search.page"
                color="grey-8"
                :max="pages.total_pages"
                size="sm"
            />
        </div>
    </v-user-layout>
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
            tokens: [],
            columns: [
                {
                    name: "name",
                    label: "Name",
                    field: "name",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "created",
                    label: "Created",
                    field: "created",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "expires",
                    label: "Expires",
                    field: "expires",
                    align: "left",
                    sortable: true,
                },
                {
                    name: "actions",
                    label: "Actions",
                    field: "actions",
                    align: "right",
                },
            ],

            pages: {
                total_pages: 0,
            },
            search: {
                page: 1,
                per_page: 15,
            },
        };
    },

    created() {
        this.getPersonalAccessToken();
    },

    methods: {
        getPersonalAccessToken() {
            this.$server
                .get(this.$page.props.route)
                .then((res) => {
                    this.tokens = res.data.data;
                    this.pages = res.data.meta.pagination;
                    this.search.total_pages =
                        res.data.meta.pagination.total_pages;
                })
                .catch((e) => {});
        },
    },
};
</script>
