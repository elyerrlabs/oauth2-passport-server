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
    <q-layout view="hHh Lpr lff" >
        <!-- Header -->
        <q-header elevated class="header bg-primary">
            <q-toolbar class="header-content">
                <q-btn
                    flat
                    dense
                    round
                    icon="menu"
                    class="toggle-sidebar"
                    @click="toggleMenu"
                />
                <q-toolbar-title>
                    {{ app_name }}
                </q-toolbar-title>
                <q-space />

                <v-theme />
                <v-profile />
            </q-toolbar>
        </q-header>

        <!-- Sidebar -->
        <q-drawer
            v-model="isSidebarOpen"
            show-if-above
            bordered
            class="sidebar"
        >
            <q-list class="sidebar-menu q-ma-sm">
                <!-- Account -->
                <q-item-label header class="menu-section-title">
                    Account
                </q-item-label>

                <q-item
                    clickable
                    v-ripple
                    @click="open($page.props.user_dashboard)"
                    v-if="$page.props.user_dashboard.show"
                    class="menu-item"
                >
                    <q-item-section avatar>
                        <q-avatar
                            class="text-primary"
                            :icon="$page.props.user_dashboard.icon"
                        />
                    </q-item-section>
                    <q-item-section>
                        {{ $page.props.user_dashboard.name }}
                    </q-item-section>
                </q-item>

                <!-- Developers -->
                <q-item-label
                    header
                    class="menu-section-title"
                    v-if="developers.show"
                >
                    {{ developers.name }}
                </q-item-label>

                <q-item
                    clickable
                    class="menu-item"
                    v-for="(item, index) in developers.menu"
                    :key="index"
                    @click="open(item)"
                >
                    <q-item-section avatar>
                        <q-avatar
                            size="xl"
                            class="text-primary"
                            :icon="item.icon"
                        />
                    </q-item-section>
                    <q-item-section>{{ item.name }}</q-item-section>
                </q-item>

                <!-- Dashboards -->
                <q-item-label header class="menu-section-title">
                    Dashboards
                </q-item-label>

                <q-item
                    clickable
                    v-ripple
                    @click="open(admin_dashboard)"
                    v-if="admin_dashboard.show"
                >
                    <q-item-section avatar>
                        <q-avatar
                            class="text-primary"
                            :icon="admin_dashboard.icon"
                        />
                    </q-item-section>
                    <q-item-section>
                        {{ admin_dashboard.name }}
                    </q-item-section>
                </q-item>

                <q-item
                    clickable
                    v-ripple
                    @click="open(transaction_dashboard)"
                    v-if="transaction_dashboard.show"
                >
                    <q-item-section avatar>
                        <q-avatar
                            class="text-primary"
                            :icon="transaction_dashboard.icon"
                        />
                    </q-item-section>
                    <q-item-section>
                        {{ transaction_dashboard.name }}
                    </q-item-section>
                </q-item>

                <q-item
                    clickable
                    v-ripple
                    @click="open(partner_dashboard)"
                    v-if="partner_dashboard.show"
                >
                    <q-item-section avatar>
                        <q-avatar
                            class="text-primary"
                            :icon="partner_dashboard.icon"
                        />
                    </q-item-section>
                    <q-item-section>
                        {{ partner_dashboard.name }}
                    </q-item-section>
                </q-item>
                <q-item
                    clickable
                    v-ripple
                    @click="open(settings)"
                    v-if="settings.show"
                >
                    <q-item-section avatar>
                        <q-avatar  class="text-primary" :icon="settings.icon" />
                    </q-item-section>
                    <q-item-section>
                        {{ settings.name }}
                    </q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <!-- Main Content -->
        <q-page-container>
            <q-page>
                <slot />
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script>
export default {
    name: "AdminLayout",
    data() {
        return {
            isSidebarOpen: false,
            user: {},
            app_name: "",
            menus: [],
            admin_dashboard: [],
            transaction_dashboard: [],
            partner_dashboard: [],
            settings: {},
            developers: [],
        };
    },

    created() {
        this.user = this.$page.props.user;
        this.app_name = this.$page.props.app_name;
        this.menus = this.$page.props.user_routes;
        this.admin_dashboard = this.$page.props.admin_dashboard;
        this.transaction_dashboard = this.$page.props.transaction_dashboard;
        this.partner_dashboard = this.$page.props.partner_dashboard;
        this.settings = this.$page.props.settings;
        this.developers = this.$page.props.developers;
    },

    mounted() {
        this.setupEventListeners();

        // Simular estado de carga inicial
        setTimeout(() => {
            this.isLoading = false;
        }, 1500);
    },

    methods: {
        open(item) {
            window.location.href = item.route;
        },

        toggleMenu() {
            this.isSidebarOpen = !this.isSidebarOpen;

            // Bloquear el scroll del body cuando el menú está abierto
            if (this.isSidebarOpen) {
                document.body.style.overflow = "hidden";
            } else {
                document.body.style.overflow = "auto";
            }
        },
        handleResize() {
            if (window.innerWidth >= 992) {
                this.isSidebarOpen = false;
                document.body.style.overflow = "auto";
            }
        },
        setupEventListeners() {
            // Cerrar sidebar al hacer clic en un elemento del menú (solo en móviles)
            const menuItems = document.querySelectorAll(".menu-item");
            menuItems.forEach((item) => {
                item.addEventListener("click", () => {
                    if (window.innerWidth < 992) {
                        this.toggleMenu();
                    }
                });
            });

            // Cerrar menú al redimensionar la ventana si pasa al modo escritorio
            window.addEventListener("resize", this.handleResize);
        },
    },
    beforeUnmount() {
        // Limpiar event listeners
        window.removeEventListener("resize", this.handleResize);
    },
};
</script>

<style lang="css" scoped>
/*:root {
    --primary: #4361ee;
    --secondary: #3a0ca3;
    --success: #4cc9f0;
    --light: #f8f9fa;
    --dark: #212529;
    --gray: #6c757d;
    --light-gray: #e9ecef;
    --sidebar-width: 280px;
    --header-height: 70px;
    --border-radius: 12px;
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    background-color: #f5f7fb;
    color: var(--dark);
    overflow-x: hidden;
}*/

/* Layout principal */
.layout-container {
    display: flex;
    min-height: 100vh;
}

/* Header */
.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: var(--header-height);
    background: linear-gradient(120deg, var(--primary), var(--secondary));
    color: white;
    display: flex;
    align-items: center;
    padding: 0 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    transition: var(--transition);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.header-left {
    display: flex;
    align-items: center;
}

.toggle-sidebar {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: var(--transition);
}

.toggle-sidebar:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.logo {
    display: flex;
    align-items: center;
}

.logo img {
    width: 40px;
    height: 40px;
    margin-right: 12px;
    border-radius: 10px;
    object-fit: cover;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.logo span {
    font-weight: 600;
    font-size: 1.4rem;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.3);
    cursor: pointer;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: var(--header-height);
    left: 0;
    height: calc(100vh - var(--header-height));
    width: var(--sidebar-width);
    background: white;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    overflow-y: auto;
    transition: var(--transition);
    z-index: 900;
    padding: 20px 0;
}

.sidebar.collapsed {
    transform: translateX(-100%);
}

.sidebar-menu {
    list-style: none;
    padding: 0 15px;
}

.menu-section {
    margin-bottom: 25px;
}

.menu-section-title {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--gray);
    padding: 0 20px;
    margin-bottom: 15px;
    font-weight: 600;
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-radius: var(--border-radius);
    margin-bottom: 8px;
    cursor: pointer;
    transition: var(--transition);
    color: var(--dark);
    text-decoration: none;
    position: relative;
}

.menu-item:hover {
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.menu-item.active {
    background-color: rgba(67, 97, 238, 0.15);
    color: var(--primary);
    font-weight: 500;
}

.menu-item.active::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background-color: var(--primary);
    border-radius: 0 var(--border-radius) var(--border-radius) 0;
}

.menu-icon {
    width: 24px;
    height: 24px;
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.menu-text {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.badge {
    background-color: #ff4757;
    color: white;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Contenido principal */
.main-content {
    flex: 1;
    margin-left: var(--sidebar-width);
    padding: 30px;
    margin-top: var(--header-height);
    transition: var(--transition);
}

.main-content.expanded {
    margin-left: 0;
}

.page-container {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    padding: 25px;
    min-height: calc(100vh - var(--header-height) - 60px);
}

/* Estado de carga */
.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: linear-gradient(135deg, #f5f7fb 0%, #e4e8f0 100%);
}

.spinner {
    width: 60px;
    height: 60px;
    border: 5px solid rgba(67, 97, 238, 0.2);
    border-radius: 50%;
    border-top-color: var(--primary);
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.loading-text {
    font-size: 1.2rem;
    color: var(--gray);
    font-weight: 500;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        opacity: 0.6;
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0.6;
    }
}

/* Overlay para móviles */
.overlay {
    position: fixed;
    top: var(--header-height);
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 899;
    display: none;
    transition: var(--transition);
}

.overlay.active {
    display: block;
}

/* Responsive */
@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
        width: 280px;
    }

    .sidebar.mobile-open {
        transform: translateX(0);
        box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
    }

    .main-content {
        margin-left: 0;
        padding: 20px;
    }

    .logo span {
        font-size: 1.2rem;
    }
}

@media (max-width: 576px) {
    .header {
        padding: 0 15px;
    }

    .logo span {
        font-size: 1.1rem;
    }

    .page-container {
        padding: 20px 15px;
    }

    .main-content {
        padding: 15px;
    }
}

/* Tarjetas de ejemplo para el contenido */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    padding: 20px;
    transition: var(--transition);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--primary);
}

.card-content {
    color: var(--gray);
    font-size: 0.9rem;
}
</style>
