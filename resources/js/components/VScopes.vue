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
    <div class="space-y-8">
        <!-- Loading State with Smooth Transition -->
        <transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="loading" class="flex justify-center items-center py-16">
                <div class="text-center space-y-4">
                    <div class="relative">
                        <svg
                            class="animate-spin h-14 w-14 text-blue-600 dark:text-blue-400 mx-auto"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        <div
                            class="absolute inset-0 flex items-center justify-center"
                        >
                            <i
                                class="mdi mdi-shield-account text-blue-500 text-lg"
                            ></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div
                            class="text-blue-600 dark:text-blue-400 text-xl font-semibold"
                        >
                            {{ __("Loading Permissions") }}
                        </div>
                        <div class="text-gray-500 dark:text-gray-400 text-sm">
                            {{ __("Setting up your access controls...") }}
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Scopes Content with Smooth Transition -->
        <transition
            enter-active-class="transition-all duration-500 ease-out"
            enter-from-class="opacity-0 transform translate-y-4"
            enter-to-class="opacity-100 transform translate-y-0"
            leave-active-class="transition-all duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="!loading" class="space-y-6">
                <!-- Skeleton Loading for Initial Render -->
                <transition-group
                    name="staggered-fade"
                    tag="div"
                    class="space-y-6"
                    v-if="showSkeleton && scopes.length === 0"
                >
                    <div
                        v-for="n in 3"
                        :key="n"
                        class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 space-y-4 bg-white dark:bg-gray-800 shadow-sm"
                    >
                        <!-- Skeleton Group Header -->
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="h-8 bg-gray-200 dark:bg-gray-700 rounded-lg w-1/4 animate-pulse"
                                ></div>
                                <div
                                    class="h-6 bg-gray-200 dark:bg-gray-700 rounded-full w-20 animate-pulse"
                                ></div>
                            </div>
                            <div
                                class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"
                            ></div>
                        </div>

                        <!-- Skeleton Services -->
                        <div class="space-y-4 ml-2 mt-6">
                            <div
                                v-for="m in 2"
                                :key="m"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-900/50"
                            >
                                <div
                                    class="flex items-center justify-between mb-4"
                                >
                                    <div
                                        class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-1/4 animate-pulse"
                                    ></div>
                                    <div
                                        class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/6 animate-pulse"
                                    ></div>
                                </div>

                                <!-- Skeleton Checkboxes -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
                                >
                                    <div
                                        v-for="k in 3"
                                        :key="k"
                                        class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700"
                                    >
                                        <div
                                            class="w-5 h-5 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"
                                        ></div>
                                        <div class="flex-1 space-y-2">
                                            <div
                                                class="h-4 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"
                                            ></div>
                                            <div
                                                class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition-group>

                <!-- Actual Content -->
                <transition-group
                    name="staggered-fade"
                    tag="div"
                    class="space-y-6"
                    v-else
                >
                    <div
                        v-for="group in groupedScopes"
                        :key="group.name"
                        class="border border-gray-200 dark:border-gray-700 rounded-xl p-6 space-y-4 bg-white dark:bg-gray-800 shadow-sm transition-all duration-300 hover:shadow-md dark:hover:shadow-gray-900/50"
                    >
                        <!-- Group Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center"
                                    >
                                        <i
                                            class="mdi mdi-folder-account text-white text-lg"
                                        ></i>
                                    </div>
                                    <div>
                                        <div
                                            class="text-xl font-bold text-gray-900 dark:text-white capitalize"
                                        >
                                            {{ group.name }}
                                        </div>
                                        <div
                                            class="text-green-600 dark:text-green-400 text-sm mt-1 flex items-center"
                                        >
                                            <i
                                                class="mdi mdi-information-outline mr-1"
                                            ></i>
                                            {{ group.description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full"
                            >
                                {{ Object.keys(group.services).length }}
                                {{ __("services") }}
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="space-y-4 mt-6">
                            <transition-group
                                name="staggered-fade"
                                tag="div"
                                class="space-y-4"
                            >
                                <div
                                    v-for="(roles, service) in group.services"
                                    :key="service"
                                    class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-white dark:bg-gray-900/30 transition-all duration-300 hover:border-gray-300 dark:hover:border-gray-600"
                                >
                                    <!-- Service Header -->
                                    <div
                                        class="flex items-center justify-between mb-4"
                                    >
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <div
                                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"
                                            >
                                                <i
                                                    class="mdi mdi-cog text-blue-600 dark:text-blue-400 text-sm"
                                                ></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-lg font-semibold text-gray-800 dark:text-white capitalize"
                                                >
                                                    {{ service }}
                                                </span>
                                                <div
                                                    v-if="
                                                        roles[0]
                                                            ?.service_description
                                                    "
                                                    class="text-gray-600 dark:text-gray-400 text-sm mt-1"
                                                >
                                                    {{
                                                        roles[0]
                                                            ?.service_description
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-gray-500 dark:text-gray-400 text-sm bg-white dark:bg-gray-800 px-2 py-1 rounded-full border"
                                        >
                                            {{ roles.length }} {{ __("roles") }}
                                        </span>
                                    </div>

                                    <!-- Roles Checkboxes -->
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3"
                                    >
                                        <transition-group
                                            name="staggered-fade"
                                            tag="div"
                                            class="contents"
                                        >
                                            <label
                                                v-for="role in roles"
                                                :key="role.id"
                                                class="flex items-start space-x-3 p-4 rounded-xl border transition-all duration-200 cursor-pointer group"
                                                :class="{
                                                    'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 shadow-sm scale-[1.02]':
                                                        selected_scopes.includes(
                                                            role.id
                                                        ),
                                                    'border-gray-200 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-sm':
                                                        !selected_scopes.includes(
                                                            role.id
                                                        ),
                                                }"
                                            >
                                                <input
                                                    type="checkbox"
                                                    v-model="selected_scopes"
                                                    :value="role.id"
                                                    class="w-4 h-4 text-blue-600 dark:text-blue-400 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors duration-200 mt-1"
                                                />
                                                <div
                                                    class="flex-1 min-w-0 space-y-2"
                                                >
                                                    <div
                                                        class="flex items-center justify-between"
                                                    >
                                                        <div
                                                            class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                                                        >
                                                            {{ __(role.role_name) }}
                                                        </div>
                                                        <i
                                                            v-if="
                                                                selected_scopes.includes(
                                                                    role.id
                                                                )
                                                            "
                                                            class="mdi mdi-check-circle text-blue-600 dark:text-blue-400 text-lg"
                                                        ></i>
                                                    </div>
                                                    <div
                                                        class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2"
                                                        :title="
                                                            __(role.role_description)
                                                        "
                                                    >
                                                        {{
                                                            __(role.role_description) ||
                                                            __(
                                                                "No description available"
                                                            )
                                                        }}
                                                    </div>
                                                    <div
                                                        class="flex items-center text-xs text-gray-400 dark:text-gray-500"
                                                    >
                                                        <i
                                                            class="mdi mdi-key-chain mr-1"
                                                        ></i>
                                                        {{ role.role_slug }}
                                                    </div>
                                                </div>
                                            </label>
                                        </transition-group>
                                    </div>
                                </div>
                            </transition-group>
                        </div>
                    </div>
                </transition-group>

                <!-- Empty State -->
                <transition
                    enter-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                >
                    <div
                        v-if="!loading && scopes.length === 0"
                        class="text-center py-16"
                    >
                        <div
                            class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6"
                        >
                            <i
                                class="mdi mdi-shield-off text-gray-400 text-3xl"
                            ></i>
                        </div>
                        <div class="space-y-3">
                            <div
                                class="text-gray-600 dark:text-gray-300 text-xl font-semibold"
                            >
                                {{ __("No Permissions Available") }}
                            </div>
                            <div
                                class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto"
                            >
                                {{
                                    __(
                                        "Permissions haven't been configured yet. Contact your system administrator to set up access controls for your organization."
                                    )
                                }}
                            </div>
                        </div>
                        <button
                            class="mt-6 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                        >
                            {{ __("Contact Administrator") }}
                        </button>
                    </div>
                </transition>

                <!-- Summary Footer -->
                <transition
                    enter-active-class="transition-all duration-300"
                    enter-from-class="opacity-0 transform translate-y-4"
                    enter-to-class="opacity-100 transform translate-y-0"
                >
                    <div
                        v-if="!loading && scopes.length > 0"
                        class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6"
                    >
                        <div
                            class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0"
                        >
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center"
                                >
                                    <i
                                        class="mdi mdi-shield-check text-white text-xl"
                                    ></i>
                                </div>
                                <div>
                                    <div
                                        class="text-lg font-semibold text-gray-900 dark:text-white"
                                    >
                                        {{ selected_scopes.length }}
                                        {{ __("Permissions Selected") }}
                                    </div>
                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        {{ __("across") }}
                                        {{ groupedScopes.length }}
                                        {{ __("categories") }}
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400"
                            >
                                <i class="mdi mdi-information-outline"></i>
                                <span>{{
                                    __("Changes are saved automatically")
                                }}</span>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    emits: ["checked"],
    props: ["default_roles"],

    data() {
        return {
            scopes: [],
            selected_scopes: [],
            user_scopes: [],
            loading: true,
            showSkeleton: true,
        };
    },

    watch: {
        default_roles: {
            handler(values) {
                if (values && values.length > 0) {
                    const scopes = values.map(
                        (userScope) => userScope.scope.id
                    );

                    this.selected_scopes = [
                        ...new Set([...this.selected_scopes, ...scopes]),
                    ];
                }
            },
            immediate: true,
            deep: true,
        },

        selected_scopes: {
            handler(newScopes) {
                this.$emit("checked", newScopes);
            },
            deep: true,
        },
    },

    mounted() {
        this.getScopes();
    },

    computed: {
        groupedScopes() {
            const grouped = {};

            this.scopes.forEach((scope) => {
                const groupName = scope.service.group.name;
                const groupDescription = scope.service.group.description;
                const serviceName = scope.service.name;

                scope.role_name = scope.role.name;
                scope.role_slug = scope.role.slug;
                scope.role_description = scope.role.description;
                scope.service_description = scope.service.description;

                if (!grouped[groupName]) {
                    grouped[groupName] = {
                        name: groupName,
                        description: groupDescription,
                        services: {},
                    };
                }

                if (!grouped[groupName].services[serviceName]) {
                    grouped[groupName].services[serviceName] = [];
                }

                grouped[groupName].services[serviceName].push(scope);
            });

            return Object.values(grouped);
        },
    },

    methods: {
        async getScopes() {
            try {
                await new Promise((resolve) => setTimeout(resolve, 500));

                const res = await this.$server.get(this.$page.props.scopes, {
                    params: { per_page: 150 },
                });

                if (res.status === 200) {
                    await new Promise((resolve) => setTimeout(resolve, 300));

                    this.scopes = res.data.data;
                    this.syncSelectedScopes();

                    setTimeout(() => {
                        this.showSkeleton = false;
                        this.loading = false;
                    }, 200);
                }
            } catch (e) {
                this.showSkeleton = false;
                this.loading = false;

                this.$notify?.error(__("Error loading scopes"));
            }
        },

        syncSelectedScopes() {
            if (!this.default_roles || !this.default_roles.length) return;

            const defaultGsrIds = this.default_roles.map(
                (role) => role.scope?.id || role.scope
            );
            this.user_scopes = this.scopes
                .filter((scope) =>
                    defaultGsrIds.includes(scope.gsr_id || scope.id)
                )
                .map((scope) => scope.id);

            if (this.user_scopes.length > 0) {
                this.selected_scopes = [
                    ...new Set([...this.selected_scopes, ...this.user_scopes]),
                ];
            }
        },
    },
};
</script>

<style scoped>
.staggered-fade-enter-active {
    transition: all 0.5s ease;
}

.staggered-fade-leave-active {
    transition: all 0.3s ease;
}

.staggered-fade-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.staggered-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.staggered-fade-enter-active:nth-child(1) {
    transition-delay: 0.1s;
}
.staggered-fade-enter-active:nth-child(2) {
    transition-delay: 0.2s;
}
.staggered-fade-enter-active:nth-child(3) {
    transition-delay: 0.3s;
}
.staggered-fade-enter-active:nth-child(4) {
    transition-delay: 0.4s;
}
.staggered-fade-enter-active:nth-child(5) {
    transition-delay: 0.5s;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
