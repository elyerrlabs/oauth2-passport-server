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
            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="text-center">
                    <svg
                        class="animate-spin h-12 w-12 text-blue-600 mx-auto"
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
                    <div class="text-blue-600 text-xl font-semibold mt-4">
                        {{ __("Loading permissions...") }}
                    </div>
                    <div class="text-gray-500 text-sm mt-2">
                        {{ __("This may take a few moments") }}
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
                        class="border border-gray-200 rounded-lg p-6 space-y-4 bg-white shadow-sm"
                    >
                        <!-- Skeleton Group Header -->
                        <div class="space-y-2">
                            <div
                                class="h-6 bg-gray-200 rounded w-1/3 animate-pulse"
                            ></div>
                            <div
                                class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"
                            ></div>
                        </div>

                        <!-- Skeleton Services -->
                        <div class="space-y-4 ml-2 mt-6">
                            <div
                                v-for="m in 2"
                                :key="m"
                                class="border border-gray-200 rounded-lg p-4 bg-gray-50"
                            >
                                <div class="flex items-center mb-3">
                                    <div
                                        class="h-5 bg-gray-200 rounded w-1/4 animate-pulse flex-1"
                                    ></div>
                                    <div
                                        class="h-4 bg-gray-200 rounded w-1/6 animate-pulse ml-4"
                                    ></div>
                                </div>

                                <!-- Skeleton Checkboxes -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
                                >
                                    <div
                                        v-for="k in 3"
                                        :key="k"
                                        class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200"
                                    >
                                        <div
                                            class="w-4 h-4 bg-gray-200 rounded animate-pulse"
                                        ></div>
                                        <div class="flex-1 space-y-2">
                                            <div
                                                class="h-4 bg-gray-200 rounded animate-pulse"
                                            ></div>
                                            <div
                                                class="h-3 bg-gray-200 rounded w-3/4 animate-pulse"
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
                        class="border border-gray-200 rounded-lg p-6 space-y-4 bg-white shadow-sm transition-all duration-300 hover:shadow-md"
                    >
                        <!-- Group Header -->
                        <div>
                            <div
                                class="text-xl font-bold capitalize text-gray-900"
                            >
                                {{ group.name }}
                            </div>
                            <div class="text-green-600 text-sm mt-1">
                                {{ group.description }}
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="space-y-4 ml-2 mt-6">
                            <transition-group
                                name="staggered-fade"
                                tag="div"
                                class="space-y-4"
                            >
                                <div
                                    v-for="(roles, service) in group.services"
                                    :key="service"
                                    class="border border-gray-200 rounded-lg p-4 bg-gray-50 transition-all duration-300 hover:border-gray-300"
                                >
                                    <!-- Service Header -->
                                    <div class="flex items-center mb-3">
                                        <span
                                            class="text-lg font-medium capitalize text-gray-800 flex-1"
                                        >
                                            {{ service }}
                                        </span>
                                        <span class="text-gray-500 text-sm">
                                            {{
                                                roles[0]?.service_description ||
                                                ""
                                            }}
                                        </span>
                                    </div>

                                    <!-- Roles Checkboxes -->
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
                                    >
                                        <transition-group
                                            name="staggered-fade"
                                            tag="div"
                                            class="contents"
                                        >
                                            <label
                                                v-for="role in roles"
                                                :key="role.id"
                                                class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-white transition-all duration-200 cursor-pointer transform hover:scale-[1.02]"
                                                :class="{
                                                    'bg-blue-50 border-blue-200 shadow-sm':
                                                        selected_scopes.includes(
                                                            role.id
                                                        ),
                                                }"
                                            >
                                                <input
                                                    type="checkbox"
                                                    v-model="selected_scopes"
                                                    :value="role.id"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition-colors duration-200"
                                                />
                                                <div class="flex-1 min-w-0">
                                                    <div
                                                        class="text-sm font-medium text-gray-900 truncate"
                                                    >
                                                        {{ role.role_name }}
                                                    </div>
                                                    <div
                                                        class="text-xs text-gray-500 mt-1 truncate"
                                                        :title="
                                                            role.role_description
                                                        "
                                                    >
                                                        {{
                                                            role.role_description
                                                        }}
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
                        class="text-center py-12"
                    >
                        <svg
                            class="w-16 h-16 text-gray-300 mx-auto mb-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                            />
                        </svg>
                        <div class="text-gray-500 text-lg">
                            {{ __("No permissions available") }}
                        </div>
                        <div class="text-gray-400 text-sm mt-2">
                            {{
                                __(
                                    "Contact your administrator to configure permissions"
                                )
                            }}
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

                $notify.error(__("Error loading scopes"));
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
</style>
