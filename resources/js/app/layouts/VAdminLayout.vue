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
    <q-layout view="hHh Lpr lff" v-if="user.id">
        <q-header>
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title>
                    <q-avatar>
                        <img src="../../../img/favicon.png" />
                    </q-avatar>
                    {{ app_name }}
                </q-toolbar-title>

                <v-theme />
                <v-profile></v-profile>
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left">
            <q-list bordered>
                <div v-for="(item, index) in menus" :key="index">
                    <q-separator />
                    <q-item
                        clickable
                        v-ripple
                        @click="open(item)"
                        :active="isActive(item)"
                        active-class="secondary"
                    >
                        <q-item-section avatar>
                            <q-avatar class="text-primary" :icon="item.icon" />
                        </q-item-section>

                        <q-item-section>{{ item.name }}</q-item-section>
                    </q-item>
                </div>
            </q-list>
        </q-drawer>
        <q-page-container>
            <q-page :class="{ 'no-radius': !leftDrawerOpen }">
                <slot />
            </q-page>
        </q-page-container>

        <q-page v-if="!user.id" class="fixed-full flex flex-center bg-white">
            <div class="text-center">
                <q-spinner size="3rem" color="indigo" class="q-mb-md" />
                <p class="text-h6 text-grey-7 q-animate--pulse">Loading ...</p>
            </div>
        </q-page>
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            drawer: true,
            errors: {},
            leftDrawerOpen: true,
            menus: [],
            user: [],
            app_name: "",
        };
    },

    created() {
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.admin_routes;
    },

    methods: {
        toggleLeftDrawer() {
            this.leftDrawerOpen = !this.leftDrawerOpen;
        },

        open(item) {
            window.location.href = item.route;
        },

        isActive(item) {
            return item.route == window.location.href;
        },
    },
};
</script>
<style scoped>
.item-admin.active {
    background-color: rgba(0, 150, 136, 0.1);
    border-left: 3px solid #26a69a;
}
</style>
