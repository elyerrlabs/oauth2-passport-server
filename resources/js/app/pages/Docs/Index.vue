<template>
    <v-guest-layout>
        <template #body>
            <div class="q-pa-lg documentation-container">
                <!-- Header Section -->
                <div class="row items-center q-mb-lg">
                    <q-icon
                        name="mdi-book-open-page-variant"
                        size="32px"
                        color="primary"
                        class="q-mr-md"
                    />
                    <div class="text-h4 text-weight-bold text-primary">
                        Documentation & Resources
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="row q-col-gutter-lg">
                    <!-- Source Code Section -->
                    <div class="col-12 col-md-6">
                        <q-card class="doc-card shadow-3 rounded-borders">
                            <q-card-section
                                class="card-header bg-primary text-white"
                            >
                                <div class="row items-center">
                                    <q-icon
                                        name="mdi-code-braces"
                                        size="24px"
                                        class="q-mr-sm"
                                    />
                                    <div class="text-h6 text-weight-bold">
                                        Source Code
                                    </div>
                                </div>
                            </q-card-section>

                            <q-card-section>
                                <q-list class="doc-list">
                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://github.com/elyerr/oauth2-passport-server'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="blue-grey-1"
                                                text-color="dark"
                                                icon="mdi-github"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >GitHub Repository</q-item-label
                                            >
                                            <q-item-label caption
                                                >Main code repository on
                                                GitHub</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="orange-1"
                                                text-color="dark"
                                                icon="mdi-gitlab"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >GitLab Repository</q-item-label
                                            >
                                            <q-item-label caption
                                                >Main code repository on
                                                GitLab</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/LICENSE.txt'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="green-1"
                                                text-color="dark"
                                                icon="mdi-scale-balance"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >License</q-item-label
                                            >
                                            <q-item-label caption
                                                >Project license
                                                information</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/CHANGELOG.md'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="purple-1"
                                                text-color="dark"
                                                icon="mdi-format-list-bulleted"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Changelog</q-item-label
                                            >
                                            <q-item-label caption
                                                >Project version history and
                                                changes</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>

                    <!-- API Documentation Section -->
                    <div class="col-12 col-md-6">
                        <q-card class="doc-card shadow-3 rounded-borders">
                            <q-card-section
                                class="card-header bg-secondary text-white"
                            >
                                <div class="row items-center">
                                    <q-icon
                                        name="mdi-api"
                                        size="24px"
                                        class="q-mr-sm"
                                    />
                                    <div class="text-h6 text-weight-bold">
                                        API Documentation
                                    </div>
                                </div>
                            </q-card-section>

                            <q-card-section>
                                <q-list class="doc-list">
                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://documenter.getpostman.com/view/5625104/2sB2xBDq6o'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="blue-1"
                                                text-color="dark"
                                                icon="mdi-post"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Postman
                                                Documentation</q-item-label
                                            >
                                            <q-item-label caption
                                                >Complete API reference with
                                                examples</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>

                    <!-- Wiki & Guides Section -->
                    <div class="col-12 col-md-6">
                        <q-card class="doc-card shadow-3 rounded-borders">
                            <q-card-section
                                class="card-header bg-accent text-white"
                            >
                                <div class="row items-center">
                                    <q-icon
                                        name="mdi-book-open-variant"
                                        size="24px"
                                        class="q-mr-sm"
                                    />
                                    <div class="text-h6 text-weight-bold">
                                        Wiki & Guides
                                    </div>
                                </div>
                            </q-card-section>

                            <q-card-section>
                                <q-list class="doc-list">
                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="teal-1"
                                                text-color="dark"
                                                icon="mdi-home"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Main Wiki</q-item-label
                                            >
                                            <q-item-label caption
                                                >Project overview and main
                                                documentation</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/prompts-supported'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="amber-1"
                                                text-color="dark"
                                                icon="mdi-comment-question"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Supported Prompts</q-item-label
                                            >
                                            <q-item-label caption
                                                >Available authentication
                                                prompts</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/connect-applications'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="indigo-1"
                                                text-color="dark"
                                                icon="mdi-connection"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Connect
                                                Applications</q-item-label
                                            >
                                            <q-item-label caption
                                                >How to integrate
                                                applications</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/wikis/home/autorizacion'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="red-1"
                                                text-color="dark"
                                                icon="mdi-shield-account"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Authorization
                                                Guide</q-item-label
                                            >
                                            <q-item-label caption
                                                >Authorization processes and
                                                flows</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>

                    <!-- Development & Deployment Section -->
                    <div class="col-12 col-md-6">
                        <q-card class="doc-card shadow-3 rounded-borders">
                            <q-card-section
                                class="card-header bg-info text-white"
                            >
                                <div class="row items-center">
                                    <q-icon
                                        name="mdi-rocket-launch"
                                        size="24px"
                                        class="q-mr-sm"
                                    />
                                    <div class="text-h6 text-weight-bold">
                                        Development & Deployment
                                    </div>
                                </div>
                            </q-card-section>

                            <q-card-section>
                                <q-list class="doc-list">
                                    <!-- English Documentation -->
                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/dev/developers_en.md?ref_type=heads'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="green-1"
                                                text-color="dark"
                                            >
                                                <q-icon
                                                    name="mdi-developer-board"
                                                />
                                            </q-avatar>
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Developer Guide
                                                (English)</q-item-label
                                            >
                                            <q-item-label caption
                                                >Development documentation in
                                                English</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/deploy/deploy_en.md?ref_type=heads'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="blue-1"
                                                text-color="dark"
                                            >
                                                <q-icon
                                                    name="mdi-cloud-upload"
                                                />
                                            </q-avatar>
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Deployment Guide
                                                (English)</q-item-label
                                            >
                                            <q-item-label caption
                                                >Deployment instructions in
                                                English</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <!-- Spanish Documentation -->
                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/dev/developers_es.md?ref_type=heads'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="orange-1"
                                                text-color="dark"
                                            >
                                                <q-icon
                                                    name="mdi-developer-board"
                                                />
                                            </q-avatar>
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Developer Guide
                                                (Spanish)</q-item-label
                                            >
                                            <q-item-label caption
                                                >Development documentation in
                                                Spanish</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>

                                    <q-item
                                        class="doc-item"
                                        clickable
                                        v-ripple
                                        @click="
                                            openLink(
                                                'https://gitlab.com/elyerr/oauth2-passport-server/-/blob/main/docs/deploy/deploy_es.md?ref_type=heads'
                                            )
                                        "
                                    >
                                        <q-item-section avatar>
                                            <q-avatar
                                                color="purple-1"
                                                text-color="dark"
                                            >
                                                <q-icon
                                                    name="mdi-cloud-upload"
                                                />
                                            </q-avatar>
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label
                                                class="text-weight-medium"
                                                >Deployment Guide
                                                (Spanish)</q-item-label
                                            >
                                            <q-item-label caption
                                                >Deployment instructions in
                                                Spanish</q-item-label
                                            >
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-icon
                                                name="mdi-open-in-new"
                                                color="grey-6"
                                            />
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>
            </div>
        </template>
    </v-guest-layout>
</template>

<script>
export default {
    name: "DocumentationLinks",
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
    padding: 16px 20px;
}

.doc-list {
    padding: 0;
}

.doc-item {
    border-radius: 8px;
    margin-bottom: 8px;
    transition: background-color 0.2s ease;
    border: 1px solid transparent;
}

.doc-item:hover {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.doc-item:last-child {
    margin-bottom: 0;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .documentation-container {
        padding: 16px;
    }

    .text-h4 {
        font-size: 1.75rem;
    }
}

@media (max-width: 600px) {
    .text-h4 {
        font-size: 1.5rem;
    }

    .doc-item {
        padding: 12px;
    }
}
</style>
