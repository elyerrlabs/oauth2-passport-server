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
    <v-guest-layout>
        <div class="max-w-7xl mx-auto p-6 documentation-container">
            <!-- Header Section -->
            <div class="flex items-center mb-8">
                <div
                    class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mr-4 transition-colors duration-200"
                >
                    <span
                        class="mdi mdi-book-open-page-variant text-blue-600 dark:text-blue-400 text-xl"
                    ></span>
                </div>
                <div>
                    <h1
                        class="text-3xl font-bold text-blue-600 dark:text-blue-400"
                    >
                        {{ __("Documentation & Resources") }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        {{
                            __(
                                "Complete guides, API references, and development resources"
                            )
                        }}
                    </p>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Source Code Section -->
                <div class="col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card group"
                    >
                        <div
                            class="bg-blue-600 dark:bg-blue-700 text-white rounded-t-xl p-5 card-header transition-colors duration-200"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center mr-3"
                                >
                                    <span
                                        class="mdi mdi-code-braces text-white text-lg"
                                    ></span>
                                </div>
                                <h2 class="text-xl font-bold">
                                    {{ __("Source Code") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://github.com/elyerr/oauth2-passport-server'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30"
                                    >
                                        <span
                                            class="mdi mdi-github text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("GitHub Repository") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Main code repository on GitHub"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-orange-50 dark:bg-orange-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/30"
                                    >
                                        <span
                                            class="mdi mdi-gitlab text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("GitLab Repository") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Main code repository on GitLab"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/LICENSE.txt'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-green-100 dark:group-hover:bg-green-900/30"
                                    >
                                        <span
                                            class="mdi mdi-scale-balance text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("License") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Project license information"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/CHANGELOG.md'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-purple-50 dark:bg-purple-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/30"
                                    >
                                        <span
                                            class="mdi mdi-format-list-bulleted text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("Changelog") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Project version history and changes"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Documentation Section -->
                <div class="col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card group"
                    >
                        <div
                            class="bg-purple-600 dark:bg-purple-700 text-white rounded-t-xl p-5 card-header transition-colors duration-200"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-purple-500 dark:bg-purple-600 rounded-full flex items-center justify-center mr-3"
                                >
                                    <span
                                        class="mdi mdi-api text-white text-lg"
                                    ></span>
                                </div>
                                <h2 class="text-xl font-bold">
                                    {{ __("API Documentation") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://documenter.getpostman.com/view/5625104/2sB2xBDq6o'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30"
                                    >
                                        <span
                                            class="mdi mdi-post text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("Postman Documentation") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Complete API reference with examples"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wiki & Guides Section -->
                <div class="col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card group"
                    >
                        <div
                            class="bg-teal-600 dark:bg-teal-700 text-white rounded-t-xl p-5 card-header transition-colors duration-200"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-teal-500 dark:bg-teal-600 rounded-full flex items-center justify-center mr-3"
                                >
                                    <span
                                        class="mdi mdi-book-open-variant text-white text-lg"
                                    ></span>
                                </div>
                                <h2 class="text-xl font-bold">
                                    {{ __("Wiki & Guides") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-teal-50 dark:bg-teal-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-teal-100 dark:group-hover:bg-teal-900/30"
                                    >
                                        <span
                                            class="mdi mdi-home text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("Main Wiki") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Project overview and main documentation"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/prompts-supported'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-amber-50 dark:bg-amber-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/30"
                                    >
                                        <span
                                            class="mdi mdi-comment-question text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("Supported Prompts") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Available authentication prompts"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/connect-applications'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30"
                                    >
                                        <span
                                            class="mdi mdi-connection text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("Connect Applications") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "How to integrate applications"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/autorizacion'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-red-100 dark:group-hover:bg-red-900/30"
                                    >
                                        <span
                                            class="mdi mdi-shield-account text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{ __("Authorization Guide") }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Authorization processes and flows"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Development & Deployment Section -->
                <div class="col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card group"
                    >
                        <div
                            class="bg-cyan-600 dark:bg-cyan-700 text-white rounded-t-xl p-5 card-header transition-colors duration-200"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-8 h-8 bg-cyan-500 dark:bg-cyan-600 rounded-full flex items-center justify-center mr-3"
                                >
                                    <span
                                        class="mdi mdi-rocket-launch text-white text-lg"
                                    ></span>
                                </div>
                                <h2 class="text-xl font-bold">
                                    {{ __("Development & Deployment") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <!-- English Documentation -->
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/dev/developers_en.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-green-100 dark:group-hover:bg-green-900/30"
                                    >
                                        <span
                                            class="mdi mdi-developer-board text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                __("Developer Guide (English)")
                                            }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Development documentation in English"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/deploy/deploy_en.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30"
                                    >
                                        <span
                                            class="mdi mdi-cloud-upload text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                __("Deployment Guide (English)")
                                            }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Deployment instructions in English"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <!-- Spanish Documentation -->
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/dev/developers_es.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-orange-50 dark:bg-orange-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/30"
                                    >
                                        <span
                                            class="mdi mdi-developer-board text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                __("Developer Guide (Spanish)")
                                            }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Development documentation in Spanish"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 cursor-pointer transition-all duration-200 doc-item group"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/deploy/deploy_es.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-purple-50 dark:bg-purple-900/20 rounded-full flex items-center justify-center text-gray-800 dark:text-gray-200 mr-4 transition-colors duration-200 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/30"
                                    >
                                        <span
                                            class="mdi mdi-cloud-upload text-xl"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div
                                            class="font-medium text-gray-900 dark:text-white"
                                        >
                                            {{
                                                __("Deployment Guide (Spanish)")
                                            }}
                                        </div>
                                        <div
                                            class="text-sm text-gray-500 dark:text-gray-400"
                                        >
                                            {{
                                                __(
                                                    "Deployment instructions in Spanish"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        class="text-gray-400 dark:text-gray-500 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
                                    >
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </v-guest-layout>
</template>

<script>
import VGuestLayout from "@/layouts/VGuestLayout.vue";

export default {
    name: "DocumentationLinks",
    components: {
        VGuestLayout,
    },
    methods: {
        openLink(url) {
            window.open(url, "_blank", "noopener,noreferrer");
        },
    },
};
</script>

<style scoped>
.documentation-container {
    max-width: 1200px;
    margin: 0 auto;
}

.doc-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.doc-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.doc-list {
    padding: 0;
}

.doc-item {
    border-radius: 8px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.doc-item:hover {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

/* Dark mode styles */
@media (prefers-color-scheme: dark) {
    .doc-item:hover {
        background-color: rgba(55, 65, 81, 0.5);
        border-color: #4b5563;
    }
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .documentation-container {
        padding: 16px;
    }

    .text-3xl {
        font-size: 1.75rem;
    }
}

@media (max-width: 600px) {
    .text-3xl {
        font-size: 1.5rem;
    }

    .doc-item {
        padding: 12px;
    }
}
</style>
