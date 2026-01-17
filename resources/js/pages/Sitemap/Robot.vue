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
    <v-seo-layout>
        <!-- Container -->
        <div
            class="p-6 rounded-xl bg-white shadow-sm transition dark:bg-gray-900 dark:border-gray-700 dark:shadow-none"
        >
            <!-- Header -->
            <div class="mb-8">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2"
                >
                    {{ __("Robots.txt Manager") }}
                </h1>

                <p class="text-gray-600 dark:text-gray-400">
                    {{
                        __(
                            "Configure and customize the robots.txt file to control search engine access to your website."
                        )
                    }}
                </p>
            </div>

            <!-- URL Information Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div
                    class="bg-linear-to-r from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-blue-600 dark:text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                />
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-sm font-medium text-blue-800 dark:text-blue-300"
                            >
                                {{ __("Current Domain") }}
                            </div>
                            <div
                                class="text-sm text-blue-600 dark:text-blue-400 font-mono break-all"
                            >
                                {{ currentDomain }}
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-linear-to-r from-green-50 to-green-100/50 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-700 rounded-lg p-4"
                >
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-green-600 dark:text-green-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div>
                            <div
                                class="text-sm font-medium text-green-800 dark:text-green-300"
                            >
                                {{ __("Sitemap Location") }}
                            </div>
                            <div
                                class="text-sm text-green-600 dark:text-green-400 font-mono break-all"
                            >
                                {{ sitemapUrl }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editor Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        {{ __("Robots.txt File Content") }}
                    </label>
                    <button
                        @click="applyTemplate('comprehensive')"
                        class="px-4 py-2 cursor-pointer rounded-md font-medium transition text-white bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-800 flex items-center space-x-2"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <span>{{ __("Use Comprehensive Template") }}</span>
                    </button>
                </div>
                <v-editor
                    v-model="form.meta"
                    :error="form.errors.meta"
                    :jodit="false"
                    :preview="false"
                    lang="text"
                />
            </div>

            <!-- Help Information -->
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6"
            >
                <div class="flex items-start space-x-3">
                    <svg
                        class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <div class="text-blue-800 dark:text-blue-300 text-sm">
                        <p class="font-medium mb-1">
                            {{ __("What is robots.txt?") }}
                        </p>
                        <p>
                            {{
                                __(
                                    "The robots.txt file tells search engines which pages on your site they can crawl and which they cannot. Use 'User-agent' to specify the robot and 'Disallow' to block sections."
                                )
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Templates -->
            <div class="mb-8">
                <h3
                    class="text-lg font-semibold text-gray-900 dark:text-white mb-4"
                >
                    {{ __("Quick Templates") }}
                </h3>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <button
                        @click="applyTemplate('allowAll')"
                        class="p-4 text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 hover:shadow-md"
                    >
                        <div
                            class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-2"
                        >
                            <svg
                                class="w-4 h-4 text-green-600 dark:text-green-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                        </div>
                        <div
                            class="font-medium text-gray-900 dark:text-white mb-1"
                        >
                            {{ __("Allow All") }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{
                                __(
                                    "Allow all search engines to crawl entire site"
                                )
                            }}
                        </div>
                    </button>

                    <button
                        @click="applyTemplate('blockAll')"
                        class="p-4 text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 hover:shadow-md"
                    >
                        <div
                            class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mb-2"
                        >
                            <svg
                                class="w-4 h-4 text-red-600 dark:text-red-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </div>
                        <div
                            class="font-medium text-gray-900 dark:text-white mb-1"
                        >
                            {{ __("Block All") }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{
                                __(
                                    "Block all search engines from crawling the site"
                                )
                            }}
                        </div>
                    </button>

                    <button
                        @click="applyTemplate('blockAdmin')"
                        class="p-4 text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 hover:shadow-md"
                    >
                        <div
                            class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mb-2"
                        >
                            <svg
                                class="w-4 h-4 text-orange-600 dark:text-orange-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                        </div>
                        <div
                            class="font-medium text-gray-900 dark:text-white mb-1"
                        >
                            {{ __("Block Admin Areas") }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{
                                __("Block access to admin and private sections")
                            }}
                        </div>
                    </button>

                    <button
                        @click="applyTemplate('custom')"
                        class="p-4 text-left border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 hover:shadow-md"
                    >
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-2"
                        >
                            <svg
                                class="w-4 h-4 text-blue-600 dark:text-blue-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>
                        <div
                            class="font-medium text-gray-900 dark:text-white mb-1"
                        >
                            {{ __("Custom Configuration") }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Start with a basic custom configuration") }}
                        </div>
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <div
                class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700"
            >
                <button
                    @click="resetToDefault"
                    class="px-6 py-3 cursor-pointer rounded-lg font-medium transition text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 flex items-center space-x-2"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                        />
                    </svg>
                    <span>{{ __("Reset to Default") }}</span>
                </button>
                <button
                    @click="updateMeta"
                    class="px-6 py-3 cursor-pointer rounded-lg font-medium transition text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 flex items-center space-x-2"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                    <span>{{ __("Update Robots.txt") }}</span>
                </button>
            </div>
        </div>
    </v-seo-layout>
</template>

<script setup>
import VSeoLayout from "@/components/VGeneralLayout.vue";
import VEditor from "@/components/VEditor.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { onMounted, ref, computed } from "vue";

const page = usePage();

const form = useForm({
    meta: "",
});

// Computed properties for real-time URL detection
const currentDomain = computed(() => window.location.origin);
const sitemapUrl = computed(() => `${currentDomain.value}/sitemaps/index.xml`);

onMounted(() => {
    form.meta = page.props.data || generateDefaultTemplate();
});

const generateDefaultTemplate = () => {
    return `# Default robots.txt configuration
# Generated for: ${currentDomain.value}

User-agent: *
Allow: /

# Sitemap location
Sitemap: ${sitemapUrl.value}`;
};

const updateMeta = () => {
    form.post(page.props.routes.store, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            $notify.success(
                __("Robots.txt file has been updated successfully")
            );
        },
    });
};

const applyTemplate = (templateType) => {
    const templates = {
        allowAll: `# Allow all search engines
# Domain: ${currentDomain.value}

User-agent: *
Allow: /

# Sitemap location
Sitemap: ${sitemapUrl.value}`,

        blockAll: `# Block all search engines
# Domain: ${currentDomain.value}

User-agent: *
Disallow: /

# Sitemap location
Sitemap: ${sitemapUrl.value}`,

        blockAdmin: `# Block admin and private areas
# Domain: ${currentDomain.value}

User-agent: *
Allow: /
Disallow: /admin/
Disallow: /private/
Disallow: /dashboard/
Disallow: /api/
Disallow: /storage/

# Sitemap location
Sitemap: ${sitemapUrl.value}`,

        custom: `# Custom robots.txt configuration
# Domain: ${currentDomain.value}

User-agent: *
Allow: /
Disallow: /private/

# Crawl delay (optional)
# Crawl-delay: 10

# Sitemap location
Sitemap: ${sitemapUrl.value}`,

        comprehensive: `# Comprehensive robots.txt configuration
# Generated for: ${currentDomain.value}
# Last updated: ${new Date().toISOString().split("T")[0]}

# Allow all major search engines
User-agent: *
Allow: /

# Block sensitive directories
Disallow: /admin/
Disallow: /private/
Disallow: /dashboard/
Disallow: /api/
Disallow: /storage/
Disallow: /config/
Disallow: /vendor/
Disallow: /node_modules/
Disallow: /.env
Disallow: /backup/
Disallow: /logs/

# Block common files
Disallow: /package.json
Disallow: /composer.json
Disallow: /yarn.lock
Disallow: /package-lock.json

# Allow specific file types
Allow: /*.css$
Allow: /*.js$
Allow: /*.png$
Allow: /*.jpg$
Allow: /*.jpeg$
Allow: /*.gif$
Allow: /*.svg$
Allow: /*.webp$

# Block query parameters that don't affect content
Disallow: /*?utm_
Disallow: /*?fbclid=
Disallow: /*?gclid=
Disallow: /*?msclkid=

# Specific rules for major search engines
User-agent: Googlebot
Allow: /
Crawl-delay: 1

User-agent: Bingbot
Allow: /
Crawl-delay: 2

User-agent: Slurp
Allow: /
Crawl-delay: 3

User-agent: DuckDuckBot
Allow: /
Crawl-delay: 1

User-agent: Baiduspider
Allow: /
Crawl-delay: 5

User-agent: YandexBot
Allow: /
Crawl-delay: 3

# Block AI crawlers (optional)
User-agent: ChatGPT-User
Disallow: /

User-agent: CCBot
Disallow: /

User-agent: GPTBot
Disallow: /

# Sitemap locations
Sitemap: ${sitemapUrl.value}
Sitemap: ${currentDomain.value}/sitemaps/sitemap-index.xml
Sitemap: ${currentDomain.value}/sitemaps/pages.xml
Sitemap: ${currentDomain.value}/sitemaps/posts.xml

# Host directive (for multi-domain setups)
# Host: ${currentDomain.value.replace(/^https?:\/\//, "")}

# Thank you for respecting our robots.txt file!`,
    };

    form.meta = templates[templateType] || templates.allowAll;

    if (templateType === "comprehensive") {
        $notify.success(__("Comprehensive template applied successfully"));
    }
};

const resetToDefault = () => {
    form.meta = generateDefaultTemplate();
    $notify.info(__("Reset to default configuration"));
};
</script>
