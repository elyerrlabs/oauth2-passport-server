<template>
    <v-guest-layout>
        <div class="max-w-7xl mx-auto p-6 documentation-container">
            <!-- Header Section -->
            <div class="flex items-center mb-8">
                <span
                    class="mdi mdi-book-open-page-variant w-8 h-8 text-blue-600 mr-4"
                ></span>
                <h1 class="text-3xl font-bold text-blue-600">
                    {{ __("Documentation & Resources") }}
                </h1>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Source Code Section -->
                <div class="col-span-1">
                    <div
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card"
                    >
                        <div
                            class="bg-blue-600 text-white rounded-t-xl p-5 card-header"
                        >
                            <div class="flex items-center">
                                <span
                                    class="mdi mdi-code-braces w-6 h-6 text-white mr-2"
                                ></span>
                                <h2 class="text-xl font-bold">
                                    {{ __("Source Code") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://github.com/elyerr/oauth2-passport-server'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-github w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("GitHub Repository") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Main code repository on GitHub"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-gitlab w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("GitLab Repository") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Main code repository on GitLab"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/LICENSE.txt'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-scale-balance w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("License") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Project license information"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/CHANGELOG.md'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-format-list-bulleted w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("Changelog") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Project version history and changes"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
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
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card"
                    >
                        <div
                            class="bg-purple-600 text-white rounded-t-xl p-5 card-header"
                        >
                            <div class="flex items-center">
                                <span
                                    class="mdi mdi-api w-6 h-6 text-white mr-2"
                                ></span>
                                <h2 class="text-xl font-bold">
                                    {{ __("API Documentation") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://documenter.getpostman.com/view/5625104/2sB2xBDq6o'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-post w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("Postman Documentation") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Complete API reference with examples"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
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
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card"
                    >
                        <div
                            class="bg-teal-600 text-white rounded-t-xl p-5 card-header"
                        >
                            <div class="flex items-center">
                                <span
                                    class="mdi mdi-book-open-variant w-6 h-6 text-white mr-2"
                                ></span>
                                <h2 class="text-xl font-bold">
                                    {{ __("Wiki & Guides") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-home w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("Main Wiki") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Project overview and main documentation"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/prompts-supported'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-comment-question w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("Supported Prompts") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Available authentication prompts"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/connect-applications'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-connection w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("Connect Applications") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "How to integrate applications"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/autorizacion'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-shield-account w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{ __("Authorization Guide") }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Authorization processes and flows"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
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
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 doc-card"
                    >
                        <div
                            class="bg-cyan-600 text-white rounded-t-xl p-5 card-header"
                        >
                            <div class="flex items-center">
                                <span
                                    class="mdi mdi-rocket-launch w-6 h-6 text-white mr-2"
                                ></span>
                                <h2 class="text-xl font-bold">
                                    {{ __("Development & Deployment") }}
                                </h2>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="space-y-3 doc-list">
                                <!-- English Documentation -->
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/dev/developers_en.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-developer-board w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{
                                                __("Developer Guide (English)")
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Development documentation in English"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/deploy/deploy_en.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-cloud-upload w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{
                                                __("Deployment Guide (English)")
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Deployment instructions in English"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <!-- Spanish Documentation -->
                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/dev/developers_es.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-developer-board w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{
                                                __("Developer Guide (Spanish)")
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Development documentation in Spanish"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
                                        <span
                                            class="mdi mdi-open-in-new w-5 h-5"
                                        ></span>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center p-4 rounded-lg border border-transparent hover:bg-gray-50 hover:border-gray-200 cursor-pointer transition-all duration-200 doc-item"
                                    @click="
                                        openLink(
                                            'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/deploy/deploy_es.md?ref_type=heads'
                                        )
                                    "
                                >
                                    <div
                                        class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-gray-800 mr-4"
                                    >
                                        <span
                                            class="mdi mdi-cloud-upload w-6 h-6"
                                        ></span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            {{
                                                __("Deployment Guide (Spanish)")
                                            }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{
                                                __(
                                                    "Deployment instructions in Spanish"
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">
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
    transition: background-color 0.2s ease;
    border: 1px solid transparent;
}

.doc-item:hover {
    background-color: #f8f9fa;
    border-color: #e9ecef;
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
