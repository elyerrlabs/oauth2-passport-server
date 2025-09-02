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
        <q-page class="q-pa-lg">
            <!-- Header Section -->
            <div class="row items-center q-mb-xl">
                <div>
                    <div class="text-h4 text-primary text-weight-bold">
                        {{ __("Dashboard Analytics") }}
                    </div>
                    <div class="text-subtitle1 text-grey-7">
                        {{
                            __(
                                "Monitor your transaction metrics and performance"
                            )
                        }}
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row q-col-gutter-lg q-mb-xl">
                <div
                    v-for="card in cards"
                    :key="card.label"
                    class="col-xs-12 col-sm-6 col-md-4"
                >
                    <q-card class="stat-card q-pa-lg shadow-3" flat bordered>
                        <div class="column full-height justify-between">
                            <div
                                class="text-caption text-weight-medium text-uppercase text-grey-6 q-mb-sm"
                            >
                                {{ card.label }}
                            </div>
                            <div class="row items-center justify-between">
                                <div
                                    class="text-h3 text-weight-bold text-primary"
                                >
                                    {{ card.value }}
                                </div>
                                <q-icon
                                    :name="card.icon"
                                    size="42px"
                                    color="primary"
                                    class="card-icon"
                                />
                            </div>
                        </div>
                    </q-card>
                </div>
            </div>

            <!-- Filters Section -->
            <q-card flat bordered class="q-mb-xl filter-section">
                <q-card-section>
                    <div class="text-h6 text-weight-medium q-mb-md">
                        {{ __("Filter Analytics") }}
                    </div>
                    <div class="row q-col-gutter-md items-end">
                        <q-input
                            v-model="params.start"
                            type="date"
                            :label="__('Start date')"
                            outlined
                            dense
                            class="col-12 col-sm-6 col-md-2"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-calendar-start" size="sm" />
                            </template>
                        </q-input>

                        <q-input
                            v-model="params.end"
                            type="date"
                            :label="__('End date')"
                            outlined
                            dense
                            class="col-12 col-sm-6 col-md-2"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-calendar-end" size="sm" />
                            </template>
                        </q-input>

                        <q-select
                            v-model="params.status"
                            :options="status"
                            :label="__('Status')"
                            outlined
                            dense
                            class="col-12 col-sm-6 col-md-2"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-status" size="sm" />
                            </template>
                        </q-select>

                        <q-select
                            v-model="chartType"
                            :options="chartTypes"
                            :label="__('Chart type')"
                            outlined
                            dense
                            class="col-12 col-sm-6 col-md-2"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-chart-bar" size="sm" />
                            </template>
                        </q-select>

                        <q-select
                            v-model="params.type"
                            :options="types"
                            :label="__('Date grouping')"
                            outlined
                            dense
                            class="col-12 col-sm-6 col-md-2"
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-calendar-group" size="sm" />
                            </template>
                        </q-select>

                        <div class="col-12 col-sm-6 col-md-2">
                            <q-btn
                                :label="__('Apply Filters')"
                                @click="getData"
                                color="primary"
                                icon="mdi-filter"
                                unelevated
                                class="full-width filter-btn"
                            />
                        </div>
                    </div>
                </q-card-section>
            </q-card>

            <!-- Chart Section -->
            <q-card flat bordered class="chart-card">
                <q-card-section>
                    <div class="row items-center justify-between q-mb-md">
                        <div class="text-h6 text-weight-medium">
                            {{ __("Transaction Analytics") }}
                        </div>
                        <q-badge color="blue" class="q-pa-sm">
                            <q-icon name="mdi-sync" size="sm" class="q-mr-xs" />
                            {{ __("Auto-refreshing every 10 seconds") }}
                        </q-badge>
                    </div>

                    <apex-charts
                        width="100%"
                        height="400"
                        :type="chartType"
                        :options="chartOptions"
                        :series="chartSeries"
                        class="chart-container"
                    />
                </q-card-section>
            </q-card>
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
                    label: this.__("Packages"),
                    value: data["packages"],
                    icon: "mdi-package-variant",
                },
                {
                    label: this.__("Plans"),
                    value: data["plans"],
                    icon: "inventory_2",
                },
                {
                    label: this.__("Total Transactions"),
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
                    name: this.__("Transactions"),
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
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false,
                        },
                    },
                },
                colors: ["#1976d2"],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                },
                title: {
                    text: this.__("Transactions by :type", {
                        ":type": this.__(this.params.type),
                    }),
                    align: "left",
                    style: {
                        fontSize: "16px",
                        fontWeight: "bold",
                    },
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
.stat-card {
    border-radius: 12px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.card-icon {
    opacity: 0.8;
    transition: transform 0.3s ease;
}

.stat-card:hover .card-icon {
    transform: scale(1.1);
    opacity: 1;
}

.filter-section {
    border-radius: 12px;
}

.filter-btn {
    border-radius: 8px;
    height: 40px;
}

.chart-card {
    border-radius: 12px;
}

.chart-container {
    border-radius: 8px;
}

.text-h3 {
    font-size: 2.5rem;
    line-height: 1.2;
}

.text-h4 {
    font-size: 2rem;
    line-height: 1.2;
}

.text-h6 {
    font-weight: 600;
}

.shadow-3 {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.08);
}

.q-card {
    border: 1px solid #e0e0e0;
}

.q-input,
.q-select {
    border-radius: 8px;
}

.q-btn {
    text-transform: none;
    font-weight: 500;
}
</style>
