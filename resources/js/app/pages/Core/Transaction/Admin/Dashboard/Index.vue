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
    <v-admin-transaction-layout>
        <q-page class="q-pa-md">
            <!-- Cards -->
            <div class="row q-col-gutter-md q-mb-md">
                <div
                    v-for="card in cards"
                    :key="card.label"
                    class="col-xs-12 col-sm-6 col-md-3"
                >
                    <q-card
                        class="q-pa-md flex column justify-between"
                        flat
                        bordered
                    >
                        <div
                            class="text-caption text-weight-medium text-uppercase text-grey-7"
                        >
                            {{ card.label }}
                        </div>
                        <div class="row items-center justify-between q-mt-sm">
                            <div class="text-h4 text-weight-bold">
                                {{ card.value }}
                            </div>
                            <q-icon
                                :name="card.icon"
                                size="36px"
                                color="primary"
                            />
                        </div>
                    </q-card>
                </div>
            </div>

            <!-- Filters -->
            <div class="q-gutter-sm row items-end q-mb-lg">
                <q-input
                    v-model="params.start"
                    type="date"
                    label="Start date"
                    dense
                    outlined
                    class="col"
                />
                <q-input
                    v-model="params.end"
                    type="date"
                    label="End date"
                    dense
                    outlined
                    class="col"
                />
                <q-select
                    v-model="params.status"
                    :options="status"
                    label="Status"
                    dense
                    outlined
                    class="col"
                />

                <q-select
                    v-model="chartType"
                    :options="chartTypes"
                    label="Chart type"
                    dense
                    outlined
                    class="col"
                />

                <q-select
                    v-model="params.type"
                    :options="types"
                    label="Date"
                    dense
                    outlined
                    class="col"
                />
                <div class="col-auto">
                    <q-btn
                        label="Filter"
                        @click="getData"
                        color="primary"
                        class="q-mt-sm"
                    />
                </div>
            </div>

            <!-- Chart and Last Users -->
            <div class="row q-col-gutter-md">
                <div class="col-12">
                    <q-card flat bordered>
                        <q-card-section>
                            <apex-charts
                                width="100%"
                                height="400"
                                type="line"
                                :options="chartOptions"
                                :series="chartSeries"
                                class="q-mt-md"
                            />
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </q-page>
    </v-admin-transaction-layout>
</template>

<script>
import ApexCharts from "vue3-apexcharts";

export default {
    components: {
        ApexCharts,
    },

    data() {
        return {
            params: {
                start: null,
                end: null,
                type: "month",
                status: "successful",
            },
            chartType: "line",
            chartTypes: ["bar", "line", "area"],
            types: ["day", "month", "year"],
            transactions_by_month: [],
            plans_count: 0,
            package_count: 0,
            cards: [],
            chartSeries: [],
            chartOptions: {},
            status: [],
        };
    },

    watch: {
        "params.type"(value) {
            this.getData();
        },

        chartType(value) {
            this.renderChart();
        },
    },

    created() {
        if (!this.params.start || !this.params.end) {
            const { start, end } = this.getDefaultDates();
            this.params.start = start;
            this.params.end = end;
        }

        this.getData();
    },

    mounted() {
        this.getStatus();
        setInterval(() => {
            this.getData();
        }, 10000);
    },

    methods: {
        getDefaultDates() {
            const today = new Date();
            const end = today.toISOString().split("T")[0];

            const pastDate = new Date(today);
            pastDate.setMonth(today.getMonth() - 6);
            const start = pastDate.toISOString().split("T")[0];

            return { start, end };
        },

        loadData(data) {
            this.transactions_by_month = data["transactions_by_month"];

            this.plans_count = data["plans"];
            this.package_count = data["packages"];

            this.cards = [
                {
                    label: "Packages",
                    value: data["packages"],
                    icon: "mdi-package-variant",
                },
                {
                    label: "Plans",
                    value: data["plans"],
                    icon: "inventory_2",
                },
                {
                    label: "Total Transactions",
                    value: data["transactions"],
                    icon: "bar_chart",
                },
            ];

            this.renderChart();
        },

        async getData() {
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.loadData(res.data.data);
                }
            } catch (error) {}
        },

        async getStatus() {
            try {
                const res = await this.$server.get(this.$page.props.status);

                if (res.status == 200) {
                    this.status = res.data.data.map((item) => ({
                        label: item.name,
                        value: item.name,
                        description: item.description,
                    }));
                }
            } catch (error) {}
        },

        renderChart() {
            this.chartSeries = [
                {
                    name: "Transactions",
                    data: this.transactions_by_month.map((item) => item.total),
                },
            ];

            this.chartOptions = {
                chart: {
                    height: 350,
                    type: this.chartType,
                    zoom: {
                        enabled: false,
                    },
                },
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: "smooth",
                },
                title: {
                    text: `Transactions by ${this.params.type}`,
                    align: "left",
                },
                grid: {
                    row: {
                        colors: ["#f3f3f3", "transparent"],
                        opacity: 0.5,
                    },
                },
                xaxis: {
                    categories: this.transactions_by_month.map(
                        (item) => item.month
                    ),
                },
            };
        },

        formatDate(dateStr) {
            const options = { year: "numeric", month: "short", day: "numeric" };
            return new Date(dateStr).toLocaleDateString("es-ES", options);
        },
    },
};
</script>

<style scoped>
.q-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: box-shadow 0.2s ease;
}
.text-h6 {
    font-weight: 600;
}
</style>
