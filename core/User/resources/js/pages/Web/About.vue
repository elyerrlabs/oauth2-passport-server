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
    <v-main-layout>
        <div
            class="min-h-screen bg-linear-to-br from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900"
        >
            <div class="px-6 py-8 lg:px-10">
                <div class="max-w-6xl mx-auto">
                    <!-- Header Section with User Profile -->
                    <div
                        class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
                    >
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-linear-to-br from-slate-700 to-slate-900 dark:from-slate-200 dark:to-white rounded-xl flex items-center justify-center shadow-lg"
                                >
                                    <span
                                        class="text-white dark:text-slate-900 font-semibold text-base"
                                        >{{ userInitials }}</span
                                    >
                                </div>
                                <div
                                    class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-900"
                                ></div>
                            </div>
                            <div>
                                <h1
                                    class="text-xl font-bold text-slate-900 dark:text-white"
                                >
                                    {{ user.name }}
                                </h1>
                                <p
                                    class="text-sm text-slate-500 dark:text-slate-400"
                                >
                                    {{ user.email }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 text-sm">
                            <div
                                class="flex items-center gap-2 text-slate-500 dark:text-slate-400"
                            >
                                <i class="mdi mdi-clock-outline text-lg"></i>
                                <span>{{ user.last_connected }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"
                                    ></span>
                                    <span
                                        class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"
                                    ></span>
                                </span>
                                <span
                                    class="text-emerald-600 dark:text-emerald-400 font-medium"
                                    >{{ __("Online") }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Command Bar / Search -->
                    <div class="mb-10">
                        <div class="relative max-w-2xl mx-auto">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none"
                            >
                                <i
                                    class="mdi mdi-magnify text-slate-400 text-xl"
                                ></i>
                            </div>
                            <input
                                v-model="searchTerm"
                                type="text"
                                @input="filterApplications"
                                :placeholder="
                                    __(
                                        'Search applications, settings, or type a command...',
                                    )
                                "
                                class="w-full pl-12 pr-32 py-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-slate-900 dark:focus:ring-slate-300 focus:border-transparent transition-all duration-200 shadow-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 outline-none"
                            />
                            <div
                                class="absolute inset-y-0 right-0 pr-4 flex items-center gap-2"
                            >
                                <kbd
                                    class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md"
                                    >⌘</kbd
                                >
                                <kbd
                                    class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md"
                                    >K</kbd
                                >
                            </div>
                        </div>
                        <p
                            v-if="searchTerm && filtered_apps.length"
                            class="text-center text-xs text-slate-500 dark:text-slate-400 mt-2"
                        >
                            {{ filtered_apps.length }} {{ __("result")
                            }}{{ filtered_apps.length !== 1 ? "s" : "" }}
                            {{ __("for") }} "{{ searchTerm }}"
                        </p>
                    </div>

                    <!-- Tab Navigation -->
                    <div
                        class="border-b border-slate-200 dark:border-slate-800 mb-8"
                    >
                        <nav class="flex gap-1">
                            <button
                                @click="activeTab = 'apps'"
                                class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200"
                                :class="
                                    activeTab === 'apps'
                                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border-x border-t border-slate-200 dark:border-slate-800'
                                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'
                                "
                            >
                                {{ __("Applications") }}
                                <span
                                    class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400"
                                    >{{ user_routes.length }}</span
                                >
                            </button>
                            <button
                                v-if="admin_routes.length"
                                @click="activeTab = 'admin'"
                                class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200"
                                :class="
                                    activeTab === 'admin'
                                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border-x border-t border-slate-200 dark:border-slate-800'
                                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'
                                "
                            >
                                {{ __("Administration") }}
                                <span
                                    class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400"
                                    >{{ admin_routes.length }}</span
                                >
                            </button>
                            <button
                                @click="activeTab = 'settings'"
                                class="px-5 py-2.5 text-sm font-medium rounded-t-lg transition-all duration-200"
                                :class="
                                    activeTab === 'settings'
                                        ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border-x border-t border-slate-200 dark:border-slate-800'
                                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'
                                "
                            >
                                {{ __("Settings") }}
                                <span
                                    class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400"
                                    >{{ totalSettings }}</span
                                >
                            </button>
                        </nav>
                    </div>

                    <!-- Applications Tab -->
                    <div v-if="activeTab === 'apps'" class="space-y-2">
                        <div v-if="filtered_apps.length" class="grid gap-2">
                            <a
                                v-for="app in filtered_apps"
                                :key="app.id"
                                :href="app.route"
                                class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
                            >
                                <div
                                    class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center group-hover:bg-slate-900 dark:group-hover:bg-white transition-colors"
                                >
                                    <i
                                        :class="[
                                            app.icon || 'mdi mdi-application',
                                            'text-slate-500 dark:text-slate-400 group-hover:text-white dark:group-hover:text-slate-900 text-lg transition-colors',
                                        ]"
                                    ></i>
                                </div>
                                <div class="flex-1">
                                    <h3
                                        class="font-semibold text-slate-900 dark:text-white"
                                        v-html="
                                            searchTerm
                                                ? highlightMatch(app.name)
                                                : app.name
                                        "
                                    ></h3>
                                    <p
                                        class="text-sm text-slate-500 dark:text-slate-400"
                                    >
                                        {{
                                            app.description || __("Application")
                                        }}
                                    </p>
                                </div>
                                <div
                                    class="flex items-center gap-2 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300"
                                >
                                    <span class="text-sm">{{
                                        __("Open")
                                    }}</span>
                                    <i class="mdi mdi-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                        <div
                            v-else
                            class="text-center py-16 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800"
                        >
                            <div
                                class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <i
                                    class="mdi mdi-magnify text-slate-400 text-2xl"
                                ></i>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400">
                                {{ __("No applications found matching") }} "{{
                                    searchTerm
                                }}"
                            </p>
                        </div>
                    </div>

                    <!-- Administration Tab -->
                    <div
                        v-if="activeTab === 'admin' && admin_routes.length"
                        class="grid gap-2"
                    >
                        <a
                            v-for="admin in admin_routes"
                            :key="admin.id"
                            :href="admin.route"
                            class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
                        >
                            <div
                                class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center group-hover:bg-slate-900 dark:group-hover:bg-white transition-colors"
                            >
                                <i
                                    :class="[
                                        admin.icon || 'mdi mdi-shield',
                                        'text-slate-500 dark:text-slate-400 group-hover:text-white dark:group-hover:text-slate-900 text-lg transition-colors',
                                    ]"
                                ></i>
                            </div>
                            <div class="flex-1">
                                <h3
                                    class="font-semibold text-slate-900 dark:text-white"
                                >
                                    {{ admin.name }}
                                </h3>
                                <p
                                    class="text-sm text-slate-500 dark:text-slate-400"
                                >
                                    {{
                                        admin.description ||
                                        __("Administration section")
                                    }}
                                </p>
                            </div>
                            <div
                                class="flex items-center gap-2 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300"
                            >
                                <span class="text-sm">{{ __("Access") }}</span>
                                <i class="mdi mdi-arrow-right"></i>
                            </div>
                        </a>
                    </div>

                    <!-- Settings Tab -->
                    <div v-if="activeTab === 'settings'" class="space-y-8">
                        <!-- User Preferences -->
                        <div v-if="user_settings.length">
                            <div class="flex items-center gap-3 mb-4">
                                <i
                                    class="mdi mdi-account-outline text-slate-400"
                                ></i>
                                <h2
                                    class="text-sm font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500"
                                >
                                    {{ __("User Preferences") }}
                                </h2>
                                <div
                                    class="flex-1 h-px bg-slate-200 dark:bg-slate-800"
                                ></div>
                            </div>
                            <div class="grid gap-2">
                                <a
                                    v-for="setting in user_settings"
                                    :key="setting.id"
                                    :href="setting.route"
                                    class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
                                >
                                    <div
                                        class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            :class="[
                                                setting.icon,
                                                'text-slate-500 dark:text-slate-400 text-lg',
                                            ]"
                                        ></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3
                                            class="font-semibold text-slate-900 dark:text-white"
                                        >
                                            {{ setting.name }}
                                        </h3>
                                        <p
                                            class="text-sm text-slate-500 dark:text-slate-400"
                                        >
                                            {{
                                                setting.description ||
                                                __("Setting")
                                            }}
                                        </p>
                                    </div>
                                    <i
                                        class="mdi mdi-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-slate-900 dark:group-hover:text-white"
                                    ></i>
                                </a>
                            </div>
                        </div>

                        <!-- System Administration -->
                        <div v-if="admin_settings.length">
                            <div class="flex items-center gap-3 mb-4">
                                <i
                                    class="mdi mdi-cog-outline text-slate-400"
                                ></i>
                                <h2
                                    class="text-sm font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500"
                                >
                                    {{ __("System Administration") }}
                                </h2>
                                <div
                                    class="flex-1 h-px bg-slate-200 dark:bg-slate-800"
                                ></div>
                            </div>
                            <div class="grid gap-2">
                                <a
                                    v-for="setting in admin_settings"
                                    :key="setting.id"
                                    :href="setting.route"
                                    class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow-md transition-all duration-200"
                                >
                                    <div
                                        class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center"
                                    >
                                        <i
                                            :class="[
                                                setting.icon,
                                                'text-slate-500 dark:text-slate-400 text-lg',
                                            ]"
                                        ></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3
                                            class="font-semibold text-slate-900 dark:text-white"
                                        >
                                            {{ setting.name }}
                                        </h3>
                                        <p
                                            class="text-sm text-slate-500 dark:text-slate-400"
                                        >
                                            {{
                                                setting.description ||
                                                __("System setting")
                                            }}
                                        </p>
                                    </div>
                                    <i
                                        class="mdi mdi-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-slate-900 dark:group-hover:text-white"
                                    ></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="mt-12 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-slate-400 dark:text-slate-500"
                    >
                        <div class="flex items-center gap-6">
                            <span class="flex items-center gap-1.5"
                                ><i class="mdi mdi-apps"></i>
                                {{ user_routes.length }}
                                {{ __("Applications") }}</span
                            >
                            <span
                                class="hidden sm:inline text-slate-300 dark:text-slate-700"
                                >•</span
                            >
                            <span class="flex items-center gap-1.5"
                                ><i class="mdi mdi-tune"></i>
                                {{ totalSettings }} {{ __("Settings") }}</span
                            >
                            <span
                                v-if="admin_routes.length"
                                class="hidden sm:inline text-slate-300 dark:text-slate-700"
                                >•</span
                            >
                            <span
                                v-if="admin_routes.length"
                                class="flex items-center gap-1.5"
                                ><i class="mdi mdi-shield-account"></i>
                                {{ admin_routes.length }}
                                {{ __("Admin") }}</span
                            >
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-mono text-xs">{{
                                app_name
                            }}</span>
                            <span class="text-slate-300 dark:text-slate-700"
                                >|</span
                            >
                            <span class="text-xs">{{
                                __("OAuth2 Passport Server")
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Sessions Section -->
        <div class="mt-8">
            <div class="flex items-center gap-3 mb-4">
                <i class="mdi mdi-laptop text-slate-400"></i>
                <h2
                    class="text-sm font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500"
                >
                    {{ __("Active Sessions") }}
                </h2>
                <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800"></div>
                <span class="text-xs text-slate-400 dark:text-slate-500">
                    <i class="mdi mdi-shield-check"></i>
                    {{ __("Secure & Encrypted") }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Current Session Card -->
                <div
                    v-for="(session, index) in sessions"
                    :key="session.id"
                    :class="[
                        'relative overflow-hidden rounded-xl border p-4',
                        session.current
                            ? 'bg-linear-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/30 dark:to-teal-950/30 border-emerald-200 dark:border-emerald-800'
                            : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800',
                    ]"
                >
                    <div v-if="session.current" class="absolute top-0 right-0">
                        <div class="m-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"
                                ></span>
                                <span
                                    class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"
                                ></span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div
                            :class="[
                                'w-10 h-10 rounded-lg flex items-center justify-center',
                                session.current
                                    ? 'bg-emerald-100 dark:bg-emerald-900/50'
                                    : 'bg-slate-100 dark:bg-slate-800',
                            ]"
                        >
                            <i
                                :class="[
                                    'text-xl',
                                    session.current
                                        ? 'mdi mdi-check-circle text-emerald-600 dark:text-emerald-400'
                                        : 'mdi mdi-laptop text-slate-500 dark:text-slate-400',
                                ]"
                            ></i>
                        </div>
                        <div class="flex-1">
                            <h3
                                :class="[
                                    'font-semibold text-sm',
                                    session.current
                                        ? 'text-emerald-900 dark:text-emerald-100'
                                        : 'text-slate-700 dark:text-slate-300',
                                ]"
                            >
                                {{
                                    session.current
                                        ? __("Current Session")
                                        : __("Other Session")
                                }}
                            </h3>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"
                            >
                                <i class="mdi mdi-ip-network"></i>
                                {{ session.ip || "127.0.0.1" }}
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-mono"
                            >
                                <i class="mdi mdi-identifier"></i>
                                {{ session.id?.substring(0, 16) }}...
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400 mt-1"
                            >
                                <i class="mdi mdi-clock-outline"></i>
                                {{ __("Last activity") }}:
                                {{ session.last_activity }}
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400 my-1"
                            >
                                <span
                                    v-if="session.current"
                                    class="px-2 py-0.5 bg-emerald-200 dark:bg-emerald-800 text-emerald-800 dark:text-emerald-200 rounded-full text-xs font-medium"
                                >
                                    {{ __("Active Now") }}
                                </span>
                            </p>
                            <v-delete-session
                                :item="session"
                                @deleted="deleteSession"
                            />
                        </div>
                    </div>

                    <div
                        class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700"
                    >
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">
                                <i class="mdi mdi-browser"></i>
                                {{ session.agent?.substring(0, 50) }}...
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Session Details Accordion -->
            <div class="mt-4">
                <details
                    class="group bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden"
                >
                    <summary
                        class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                    >
                        <div class="flex items-center gap-2">
                            <i
                                class="mdi mdi-information-outline text-slate-400 group-open:rotate-180 transition-transform"
                            ></i>
                            <span
                                class="text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                {{ __("All Sessions Details") }}
                            </span>
                        </div>
                        <i
                            class="mdi mdi-chevron-down text-slate-400 group-open:rotate-180 transition-transform"
                        ></i>
                    </summary>
                    <div
                        class="p-4 pt-0 border-t border-slate-200 dark:border-slate-800"
                    >
                        <div class="space-y-4">
                            <div
                                v-for="session in sessions"
                                :key="session.id"
                                class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50"
                            >
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm"
                                >
                                    <div>
                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                            >{{ __("Session ID") }}:</span
                                        >
                                        <code
                                            class="ml-2 text-xs bg-white dark:bg-slate-900 px-2 py-1 rounded font-mono"
                                        >
                                            {{ session.id }}
                                        </code>
                                    </div>
                                    <div>
                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                            >{{ __("IP Address") }}:</span
                                        >
                                        <span class="ml-2 font-mono text-xs">{{
                                            session.ip
                                        }}</span>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                            >{{ __("User Agent") }}:</span
                                        >
                                        <span class="ml-2 text-xs break-all">{{
                                            session.agent
                                        }}</span>
                                    </div>
                                    <div>
                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                            >{{ __("Last Activity") }}:</span
                                        >
                                        <span class="ml-2 text-xs">{{
                                            session.last_activity
                                        }}</span>
                                    </div>
                                    <div>
                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                            >{{ __("Status") }}:</span
                                        >
                                        <span
                                            class="ml-2 inline-flex items-center gap-1"
                                        >
                                            <span
                                                v-if="session.current"
                                                class="relative flex h-2 w-2"
                                            >
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"
                                                ></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"
                                                ></span>
                                            </span>
                                            <span
                                                :class="
                                                    session.current
                                                        ? 'text-emerald-600 dark:text-emerald-400'
                                                        : 'text-slate-500 dark:text-slate-400'
                                                "
                                                class="text-xs font-medium"
                                            >
                                                {{
                                                    session.current
                                                        ? __("Active")
                                                        : __("Inactive")
                                                }}
                                            </span>
                                        </span>
                                    </div>
                                    <v-delete-session
                                        :item="session"
                                        @deleted="deleteSession"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </details>
            </div>

            <!-- Security Tip Card -->
            <div
                class="mt-4 bg-linear-to-br from-amber-50 to-orange-50 dark:from-amber-950/20 dark:to-orange-950/20 rounded-xl border border-amber-200 dark:border-amber-800 p-4"
            >
                <div class="flex items-start gap-3">
                    <div
                        class="w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center"
                    >
                        <i
                            class="mdi mdi-shield-lock text-amber-600 dark:text-amber-400 text-xl"
                        ></i>
                    </div>
                    <div class="flex-1">
                        <h3
                            class="font-semibold text-amber-900 dark:text-amber-100 text-sm"
                        >
                            {{ __("Security Tip") }}
                        </h3>
                        <p
                            class="text-xs text-amber-700 dark:text-amber-300 mt-1"
                        >
                            {{
                                __(
                                    "Regularly review your active sessions and revoke any you don't recognize.",
                                )
                            }}
                        </p>
                        <p
                            class="text-xs text-amber-600 dark:text-amber-400 mt-2"
                        >
                            <i class="mdi mdi-information-outline"></i>
                            {{ __("Session IDs are hashed for security") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-main-layout>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import VMainLayout from "@/components/VMainLayout.vue";
import { usePage } from "@inertiajs/vue3";
import VDeleteSession from "./DeleteSession.vue";

const page = usePage();
const searchTerm = ref("");
const activeTab = ref("apps");

const user = ref({});
const user_routes = ref([]);
const admin_routes = ref([]);
const user_settings = ref([]);
const admin_settings = ref([]);
const userInitials = ref("");
const filtered_apps = ref([]);
const app_name = ref("");
const sessions = ref([]);

const totalSettings = computed(
    () => user_settings.value.length + admin_settings.value.length,
);

onMounted(() => {
    sessions.value = page.props.sessions.data;
    user.value = page.props.user || {};
    user_routes.value = page.props.user_routes || [];
    filtered_apps.value = [...user_routes.value];
    admin_routes.value = page.props.admin_routes || [];
    user_settings.value = page.props.user_settings || [];
    admin_settings.value = page.props.admin_settings || [];
    app_name.value = page.props.app_name || "";
    userInitials.value =
        `${user.value.name?.[0] || ""}${user.value.last_name?.[0] || ""}`.toUpperCase();
});

const deleteSession = (item) => {
    const index = sessions.value.findIndex((session) => session.id === item.id);

    if (index !== -1) {
        sessions.value.splice(index, 1);
    }
};

const filterApplications = () => {
    if (!searchTerm.value.trim()) {
        filtered_apps.value = [...user_routes.value];
        return;
    }
    const term = searchTerm.value.toLowerCase();
    filtered_apps.value = user_routes.value.filter(
        (app) =>
            app.name.toLowerCase().includes(term) ||
            (app.description && app.description.toLowerCase().includes(term)),
    );
};

const highlightMatch = (text) => {
    if (!searchTerm.value || !text) return text;
    const regex = new RegExp(`(${searchTerm.value})`, "gi");
    return text.replace(
        regex,
        '<mark class="bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-200 rounded px-0.5">$1</mark>',
    );
};
</script>
