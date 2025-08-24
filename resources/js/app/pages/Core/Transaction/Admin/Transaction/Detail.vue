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
    <div>
        <q-btn
            @click="dialog = true"
            color="primary"
            icon="mdi-eye-outline"
            round
            outline
            size="sm"
        >
            <q-tooltip class="bg-primary">View Transaction Details</q-tooltip>
        </q-btn>

        <q-dialog v-model="dialog" persistent>
            <q-card
                class="detail-card"
                style="min-width: 700px; max-width: 90vw"
            >
                <q-card-section
                    class="detail-header bg-primary text-white q-pa-md"
                >
                    <div class="row items-center">
                        <q-icon
                            name="mdi-file-document-outline"
                            size="sm"
                            class="q-mr-sm"
                        />
                        <div class="text-h6">Transaction Details</div>
                        <q-space />
                        <q-btn
                            icon="close"
                            flat
                            round
                            dense
                            v-close-popup
                            class="text-white"
                        />
                    </div>
                </q-card-section>

                <q-card-section class="q-pt-lg scroll" style="max-height: 70vh">
                    <!-- Main transaction information -->
                    <div class="info-section q-mb-md">
                        <div
                            class="text-subtitle1 text-weight-medium q-mb-sm text-primary"
                        >
                            <q-icon name="mdi-information" class="q-mr-xs" />
                            Transaction Information
                        </div>

                        <div class="info-grid">
                            <div
                                v-for="(value, key) in filteredItem"
                                :key="key"
                                class="info-item row q-py-xs"
                            >
                                <div class="info-label col-4 text-grey-8">
                                    {{ formatKey(key) }}
                                </div>
                                <div
                                    class="info-value col-8 text-weight-medium"
                                >
                                    {{ formatValue(value) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <q-separator class="q-my-md" />

                    <!-- JSON Response Section -->
                    <q-expansion-item
                        v-if="item.response"
                        icon="mdi-code-json"
                        label="JSON Response"
                        dense
                        expand-separator
                        header-class="text-primary expansion-header"
                    >
                        <q-card class="bg-grey-1">
                            <q-card-section class="q-pa-sm">
                                <pre class="json-pre">{{
                                    prettyJSON(item.response)
                                }}</pre>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <!-- Meta Information Section -->
                    <q-expansion-item
                        v-if="item.meta"
                        icon="mdi-database-search"
                        label="Meta Information"
                        dense
                        expand-separator
                        header-class="text-primary expansion-header"
                        class="q-mt-md"
                    >
                        <q-card class="bg-grey-1">
                            <q-card-section class="q-pa-sm">
                                <pre class="json-pre">{{
                                    prettyJSON(item.meta)
                                }}</pre>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right" class="q-pa-md">
                    <q-btn
                        flat
                        label="Close"
                        color="primary"
                        v-close-popup
                        icon="mdi-close"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
export default {
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
    computed: {
        filteredItem() {
            const exclude = ["response", "meta", "links", "id"];
            return Object.keys(this.item)
                .filter((key) => !exclude.includes(key))
                .reduce((acc, key) => {
                    acc[key] = this.item[key];
                    return acc;
                }, {});
        },
    },
    methods: {
        formatKey(key) {
            return key
                .replace(/_/g, " ")
                .replace(/\b\w/g, (c) => c.toUpperCase());
        },
        formatValue(value) {
            if (value === null || value === undefined) return "N/A";
            if (typeof value === "boolean") return value ? "Yes" : "No";
            if (typeof value === "object") return JSON.stringify(value);
            return value;
        },
        prettyJSON(jsonData) {
            try {
                return JSON.stringify(
                    typeof jsonData === "string"
                        ? JSON.parse(jsonData)
                        : jsonData,
                    null,
                    2
                );
            } catch (e) {
                return jsonData;
            }
        },
    },
};
</script>

<style scoped>
.detail-card {
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.detail-header {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.info-section {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 16px;
    background-color: #fafafa;
}

.info-grid {
    display: grid;
    grid-gap: 4px;
}

.info-item {
    border-bottom: 1px solid #eeeeee;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
}

.json-pre {
    font-size: 12px;
    line-height: 1.4;
    overflow-x: auto;
    padding: 12px;
    background-color: #f5f5f5;
    border-radius: 4px;
    border-left: 4px solid #027be3;
    max-height: 300px;
}

.expansion-header {
    font-weight: 500;
    padding: 8px 0;
}

.expansion-header :deep(.q-item__section--side) {
    color: inherit;
}
</style>
