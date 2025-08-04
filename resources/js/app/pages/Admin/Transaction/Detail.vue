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
            color="positive"
            outline
            icon="mdi-eye-arrow-right"
        />

        <q-dialog v-model="dialog" persistent full-width>
            <q-card class="q-pa-md" style="min-width: 600px; max-width: 90vw">
                <q-card-section class="bg-primary text-white">
                    <div class="text-h6">
                        <q-icon
                            name="mdi-information-outline"
                            class="q-mr-sm"
                        />
                        Transaction details
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-section>
                    <q-markup-table dense flat class="q-mb-md">
                        <tbody>
                            <tr v-for="(value, key) in filteredItem" :key="key">
                                <td>
                                    <strong>{{ formatKey(key) }}</strong>
                                </td>
                                <td>{{ formatValue(value) }}</td>
                            </tr>
                        </tbody>
                    </q-markup-table>

                    <q-expansion-item
                        v-if="item.response"
                        icon="mdi-code-json"
                        label="JSON Response"
                        dense
                        expand-separator
                        header-class="text-primary"
                    >
                        <q-card>
                            <q-card-section class="bg-grey-2 q-pa-sm">
                                <pre
                                    class="scroll q-ma-none"
                                    style="
                                        max-height: 300px;
                                        white-space: pre-wrap;
                                    "
                                    >{{ prettyJSON(item.response) }}
                                </pre>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>

                    <q-expansion-item
                        v-if="item.meta"
                        icon="mdi-database-eye"
                        label="Meta Information"
                        dense
                        expand-separator
                        header-class="text-primary"
                    >
                        <q-card>
                            <q-card-section class="bg-grey-2 q-pa-sm">
                                <pre
                                    class="scroll q-ma-none"
                                    style="
                                        max-height: 300px;
                                        white-space: pre-wrap;
                                    "
                                    >{{ prettyJSON(item.meta) }}
                                </pre>
                            </q-card-section>
                        </q-card>
                    </q-expansion-item>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right">
                    <q-btn
                        outline
                        label="Close"
                        color="primary"
                        v-close-popup
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
            const exclude = ["response", "meta"];
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
            if (typeof value === "boolean") return value ? "Yes" : "No";
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
