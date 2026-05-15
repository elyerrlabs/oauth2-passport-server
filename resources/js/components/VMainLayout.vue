<!--
OAuth2 Passport Server — a centralized, modular authorization server
implementing OAuth 2.0 and OpenID Connect specifications.

Copyright (c) 2026 Elvis Yerel Roman Concha

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

Author: Elvis Yerel Roman Concha
Contact: yerel9212@yahoo.es

SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
    <v-layout>
        <template #aside>
            <!-- Dashboards Section -->
            <v-item-menu
                :items="page.props.admin_dashboard"
                :title="__('Administrator')"
                icon="mdi mdi-shield-crown-outline text-2xl me-2"
            />

            <v-item-menu
                :items="page.props.developers"
                :title="__('Developers')"
                icon="mdi mdi-application-brackets-outline text-2xl me-2"
            />

            <v-item-menu
                :items="page.props?.user_routes"
                :title="__('My apps')"
                icon="mdi mdi-apps text-2xl me-2"
            />

            <v-item-menu
                :items="page.props.user_settings"
                :title="__('Settings')"
                icon="mdi mdi-account-lock-outline text-2xl me-2"
            />
        </template>
        <template #main>
            <slot />
        </template>
    </v-layout>
</template>

<script setup>
import VLayout from "@/components/VLayout.vue";
import VItemMenu from "@/components/VItemMenu.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
</script>
