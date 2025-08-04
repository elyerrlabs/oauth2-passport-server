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
    <q-layout view="hHh lpR fFf">
        <!-- Header -->
        <q-header elevated>
            <q-toolbar>
                <q-toolbar-title>
                    <q-btn
                        dense
                        flat
                        round
                        icon="mdi-view-dashboard-variant"
                        @click="homePage"
                    />
                    {{ $page.props.app_name }}
                </q-toolbar-title>
                <v-theme />
                <q-btn
                    flat
                    label="Plans"
                    @click="open($page.props.guest_routes['plans'])"
                />

                <q-btn
                    v-if="!$page.props.user?.id"
                    flat
                    label="Register"
                    @click="open($page.props.auth_routes['register'])"
                />
                <q-btn
                    v-if="!$page.props.user?.id"
                    flat
                    label="Login"
                    @click="open($page.props.auth_routes['login'])"
                />
                <v-profile />
            </q-toolbar>
        </q-header>

        <!-- Main Content -->
        <q-page-container>
            <slot name="body" />
        </q-page-container>

        <!-- Footer 
        <q-footer class="text-center" elevated>
            <q-toolbar>
                <q-toolbar-title class="text-center">
                    © 2025 Mi Aplicación
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>-->
        <!-- Guest Login Modal 
        <v-login :guest="guest" @close="guest = false" />
        -->
    </q-layout>
</template>

<script>
export default {
    data() {
        return {
            guest: false,
        };
    },
    methods: {
        open(url) {
            const currentUrl = window.location.href;
            const redirectUrl = new URL(url);

            redirectUrl.searchParams.set("redirect_to", currentUrl); 

            window.location.href = redirectUrl.toString();
        },

        isActive(item) {
            return item.route == window.location.href;
        },
        homePage() {
            window.location.href =
                this.$page.props.user_routes[0].menu[0]["route"];
        },
    },
};
</script>
