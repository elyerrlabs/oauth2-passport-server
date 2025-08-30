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
    <div class="text-center q-ma-sm">
        <q-btn
            dense
            class="text-white"
            outline
            round
            no-caps
            :icon="selectedIcon"
        >
            <q-menu fit>
                <q-list style="min-width: 150px">
                    <q-item
                        v-for="theme in themes"
                        :key="theme.value"
                        clickable
                        v-close-popup
                        @click="selectTheme(theme.value)"
                    >
                        <q-item-section avatar>
                            <q-icon :name="theme.icon" :color="theme.color" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>
                                {{ theme.label }}
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-menu>
        </q-btn>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedTheme: "",
            themes: [
                {
                    label: "Light",
                    value: "light",
                    icon: "light_mode",
                    color: "primary",
                },
                {
                    label: "Dark",
                    value: "dark",
                    icon: "dark_mode",
                    color: "grey-8",
                },
                {
                    label: "Red",
                    value: "red",
                    icon: "palette",
                    color: "red",
                },
            ],
        };
    },
    computed: {
        selectedLabel() {
            return (
                this.themes.find((t) => t.value === this.selectedTheme)
                    ?.label || "Choose theme"
            );
        },
        selectedIcon() {
            return (
                this.themes.find((t) => t.value === this.selectedTheme)?.icon ||
                "palette"
            );
        },
        selectedColor() {
            return (
                this.themes.find((t) => t.value === this.selectedTheme)
                    ?.color || "primary"
            );
        },
    },
    mounted() {
        const theme = localStorage.getItem("theme") || "light";
        this.selectedTheme = theme;
        this.applyTheme(theme);
    },
    methods: {
        selectTheme(theme) {
            this.selectedTheme = theme;
            this.applyTheme(theme);
        },
        applyTheme(theme) {
            document.body.setAttribute("data-theme", theme);
            localStorage.setItem("theme", theme);
            this.$q.dark.set(theme === "dark");
        },
    },
};
</script>

<style scoped></style>
