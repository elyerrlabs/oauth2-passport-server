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
    <v-admin-layout>
        <q-page class="admin-dashboard-page">
            <!-- Header Section -->
            <div class="page-header">
                <div class="header-content">
                    <q-icon
                        name="mdi-view-dashboard"
                        size="36px"
                        color="primary"
                        class="header-icon"
                    />
                    <q-toolbar-title
                        class="text-h4 text-weight-bold text-grey-8"
                    >
                        Admin Dashboard
                    </q-toolbar-title>
                </div>
                <div class="text-subtitle1 text-grey-7 q-mt-sm">
                    Monitor your application's performance and user activity
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-cards row q-col-gutter-md q-mb-xl">
                <div
                    v-for="card in cards"
                    :key="card.label"
                    class="col-xs-12 col-sm-6 col-md-3"
                >
                    <q-card class="stats-card q-pa-lg" flat bordered>
                        <div class="card-content">
                            <div
                                class="text-caption text-weight-medium text-uppercase text-grey-7 q-mb-xs"
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
                            <q-linear-progress
                                size="6px"
                                :value="0.7"
                                color="primary"
                                class="q-mt-md"
                                rounded
                            />
                        </div>
                    </q-card>
                </div>
            </div>

            <!-- Filters Section -->
            <q-card flat bordered class="filters-card q-mb-lg">
                <q-card-section>
                    <div class="text-h6 text-weight-medium text-grey-8 q-mb-md">
                        <q-icon name="mdi-filter" class="q-mr-sm" />
                        Filter Analytics
                    </div>
                    <div class="row q-col-gutter-md items-end">
                        <q-input
                            v-model="params.start"
                            type="date"
                            label="Start Date"
                            dense
                            outlined
                            class="col-12 col-sm-6 col-md-3"
                            stack-label
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-calendar-start" />
                            </template>
                        </q-input>
                        <q-input
                            v-model="params.end"
                            type="date"
                            label="End Date"
                            dense
                            outlined
                            class="col-12 col-sm-6 col-md-3"
                            stack-label
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-calendar-end" />
                            </template>
                        </q-input>
                        <q-select
                            v-model="params.type"
                            :options="types"
                            label="Date Range"
                            dense
                            outlined
                            class="col-12 col-sm-6 col-md-2"
                            stack-label
                            emit-value
                            map-options
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-calendar-range" />
                            </template>
                        </q-select>
                        <q-select
                            v-model="chartType"
                            :options="chartTypes"
                            label="Chart Type"
                            dense
                            outlined
                            class="col-12 col-sm-6 col-md-2"
                            stack-label
                            emit-value
                            map-options
                        >
                            <template v-slot:prepend>
                                <q-icon name="mdi-chart-bar" />
                            </template>
                        </q-select>
                        <div class="col-12 col-sm-6 col-md-2">
                            <q-btn
                                label="Apply Filters"
                                @click="getData"
                                color="primary"
                                icon="mdi-check"
                                unelevated
                                class="full-width"
                                :loading="loading"
                            >
                                <template v-slot:loading>
                                    <q-spinner-hourglass class="on-left" />
                                    Loading...
                                </template>
                            </q-btn>
                        </div>
                    </div>
                </q-card-section>
            </q-card>

            <!-- Chart and Users Section -->
            <div class="row q-col-gutter-xl">
                <!-- Chart Column -->
                <div class="col-12">
                    <q-card flat bordered class="chart-card">
                        <q-card-section>
                            <div
                                class="text-h6 text-weight-medium text-grey-8 q-mb-md"
                            >
                                <q-icon name="mdi-chart-line" class="q-mr-sm" />
                                User Growth Analytics
                            </div>
                            <apex-charts
                                width="100%"
                                height="400"
                                :type="chartType"
                                :options="chartOptions"
                                :series="chartSeries"
                            />
                        </q-card-section>
                    </q-card>
                </div>

                <!-- Recent Users Column -->
                <div class="col-12">
                    <q-card flat bordered class="users-card">
                        <q-card-section>
                            <div
                                class="text-h6 text-weight-medium text-grey-8 q-mb-md"
                            >
                                <q-icon
                                    name="mdi-account-group"
                                    class="q-mr-sm"
                                />
                                Recent Users
                            </div>
                            <q-list class="users-list">
                                <q-item
                                    v-for="user in last_users"
                                    :key="user.id"
                                    clickable
                                    class="user-item q-py-md"
                                    v-ripple
                                >
                                    <q-item-section avatar>
                                        <q-avatar
                                            color="primary"
                                            text-color="white"
                                        >
                                            {{ getUserInitials(user.name) }}
                                        </q-avatar>
                                    </q-item-section>

                                    <q-item-section>
                                        <q-item-label
                                            class="text-weight-medium"
                                        >
                                            {{ user.name }}
                                        </q-item-label>
                                        <q-item-label
                                            caption
                                            class="text-grey-7"
                                        >
                                            {{ user.email }}
                                        </q-item-label>
                                    </q-item-section>

                                    <q-item-section side>
                                        <q-badge
                                            outline
                                            color="primary"
                                            class="date-badge"
                                        >
                                            <q-icon
                                                name="mdi-calendar"
                                                size="14px"
                                                class="q-mr-xs"
                                            />
                                            {{ formatDate(user.created_at) }}
                                        </q-badge>
                                    </q-item-section>
                                </q-item>
                            </q-list>

                            <div
                                v-if="last_users.length === 0"
                                class="text-center q-pa-lg"
                            >
                                <q-icon
                                    name="mdi-account-off"
                                    size="48px"
                                    color="grey-4"
                                />
                                <div class="text-grey-6 q-mt-md">
                                    No recent users
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
            </div>

            <!-- Auto-refresh Indicator -->
            <div class="auto-refresh-indicator fixed-bottom-right q-ma-md">
                <q-badge color="primary" rounded transparent>
                    <q-icon name="mdi-autorenew" size="16px" class="q-mr-xs" />
                    Auto-refresh every 10 seconds
                </q-badge>
            </div>
        </q-page>
    </v-admin-layout>
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
            },
            chartType: "line",
            chartTypes: [
                { label: "Line Chart", value: "line" },
                { label: "Bar Chart", value: "bar" },
                { label: "Area Chart", value: "area" },
            ],
            types: [
                { label: "Daily", value: "day" },
                { label: "Monthly", value: "month" },
                { label: "Yearly", value: "year" },
            ],
            users_by_month: [],
            last_users: [],
            groups: 0,
            roles: 0,
            services: 0,
            users: 0,
            cards: [],
            chartSeries: [],
            chartOptions: {},
            loading: false,
            refreshInterval: null,
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
        this.refreshInterval = setInterval(() => {
            this.getData();
        }, 10000);
    },

    beforeUnmount() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
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
            this.users_by_month = data["users_by_month"];
            this.last_users = data["last_users"];

            this.groups = data["groups"];
            this.roles = data["roles"];
            this.services = data["services"];
            this.users = data["users"];

            this.cards = [
                {
                    label: "Groups",
                    value: this.groups,
                    icon: "mdi-account-group",
                },
                {
                    label: "Roles",
                    value: this.roles,
                    icon: "mdi-shield-account",
                },
                { label: "Users", value: this.users, icon: "mdi-account" },
                { label: "Services", value: this.services, icon: "mdi-cog" },
            ];

            this.renderChart();
        },

        async getData() {
            this.loading = true;
            try {
                const res = await this.$server.get(this.$page.props.route, {
                    params: this.params,
                });

                if (res.status == 200) {
                    this.loadData(res.data.data);
                }
            } catch (error) {
                console.error("Failed to load dashboard data:", error);
                this.$q.notify({
                    message: "Failed to load dashboard data",
                    color: "negative",
                    icon: "mdi-alert",
                    position: "top",
                });
            } finally {
                this.loading = false;
            }
        },

        renderChart() {
            this.chartSeries = [
                {
                    name: "Users",
                    data: this.users_by_month.map((item) => item.total),
                },
            ];

            this.chartOptions = {
                chart: {
                    height: 350,
                    type: this.chartType,
                    zoom: {
                        enabled: true,
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true,
                        },
                    },
                    animations: {
                        enabled: true,
                        easing: "easeinout",
                        speed: 800,
                    },
                },
                colors: ["#3b82f6"],
                dataLabels: {
                    enabled: this.chartType === "bar",
                },
                stroke: {
                    curve: "smooth",
                    width: 3,
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                    },
                },
                title: {
                    text: `User Growth - ${
                        this.params.type.charAt(0).toUpperCase() +
                        this.params.type.slice(1)
                    }ly`,
                    align: "left",
                    style: {
                        fontSize: "16px",
                        fontWeight: "600",
                        color: "#374151",
                    },
                },
                grid: {
                    borderColor: "#e5e7eb",
                    strokeDashArray: 4,
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0,
                    },
                },
                xaxis: {
                    categories: this.users_by_month.map((item) => item.month),
                    labels: {
                        style: {
                            colors: "#6b7280",
                            fontSize: "12px",
                        },
                    },
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: "#6b7280",
                            fontSize: "12px",
                        },
                    },
                },
                tooltip: {
                    theme: "light",
                    x: {
                        format: "MMM yyyy",
                    },
                },
            };
        },

        formatDate(dateStr) {
            const options = { year: "numeric", month: "short", day: "numeric" };
            return new Date(dateStr).toLocaleDateString("en-US", options);
        },

        getUserInitials(name) {
            if (!name) return "U";
            return name
                .split(" ")
                .map((n) => n[0])
                .join("")
                .toUpperCase()
                .substring(0, 2);
        },
    },
};
</script>

<style lang="scss" scoped>
.admin-dashboard-page {
    background: linear-gradient(135deg, #f8fafc 0%, #e4e8f0 100%);
    min-height: 100vh;
    padding: 24px;
}

.page-header {
    margin-bottom: 32px;

    .header-content {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 8px;
    }

    .header-icon {
        background: rgba(0, 0, 0, 0.05);
        padding: 16px;
        border-radius: 50%;
    }
}

.stats-cards {
    .stats-card {
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background: white;

        &:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-content {
            .text-h3 {
                font-size: 2.5rem;
            }

            .card-icon {
                opacity: 0.8;
            }

            .q-linear-progress {
                margin-top: 16px;
            }
        }
    }
}

.filters-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);

    .q-card__section {
        padding: 24px;
    }
}

.chart-card,
.users-card {
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);

    .q-card__section {
        padding: 24px;
    }
}

.users-list {
    .user-item {
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;

        &:hover {
            background: rgba(0, 123, 255, 0.05);
            transform: translateX(4px);
        }

        .date-badge {
            padding: 6px 10px;
            border-radius: 16px;
            font-size: 0.75rem;
        }
    }
}

.auto-refresh-indicator {
    z-index: 1000;
}

// Responsive adjustments
@media (max-width: 1023px) {
    .admin-dashboard-page {
        padding: 16px;
    }

    .stats-cards {
        .stats-card {
            .text-h3 {
                font-size: 2rem;
            }
        }
    }
}

@media (max-width: 599px) {
    .admin-dashboard-page {
        padding: 12px;
    }

    .page-header {
        .text-h4 {
            font-size: 1.75rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }

    .filters-card {
        .row {
            flex-direction: column;
        }
    }
}
</style>
