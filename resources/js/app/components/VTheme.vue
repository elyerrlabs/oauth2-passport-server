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
    <div class="q-pa-md">
        <q-select
            filled
            dense
            v-model="selectedTheme"
            :options="themes"
            label="Choose theme"
            option-value="value"
            option-label="label"
            emit-value
            map-options
            @update:model-value="applyTheme"
            :option-slot="true"
            popup-content-class="theme-selector-popup"
        >
            <template v-slot:option="scope">
                <q-item v-bind="scope.itemProps">
                    <q-item-section avatar>
                        <q-icon
                            :name="scope.opt.icon"
                            :color="scope.opt.color"
                        />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>
                            {{ scope.opt.label }}
                        </q-item-label>
                    </q-item-section>
                </q-item>
            </template>

            <template v-slot:selected>
                <q-item-section avatar>
                    <q-icon :name="selectedIcon" :color="selectedColor" />
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{ selectedLabel }}</q-item-label>
                </q-item-section>
            </template>
        </q-select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedTheme: "",
            themes: [
                {
                    label: "Light (Light)",
                    value: "light",
                    icon: "light_mode",
                    color: "primary",
                },
                {
                    label: "Dark (Dark)",
                    value: "dark",
                    icon: "dark_mode",
                    color: "grey-8",
                },
                {
                    label: "Red (Red)",
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
        applyTheme(theme) {
            document.body.setAttribute("data-theme", theme);
            localStorage.setItem("theme", theme);
            this.$q.dark.set(theme === "dark");
        },
    },
};
</script>

<style scoped>
.theme-selector-popup {
    max-height: 200px;
}
</style>
