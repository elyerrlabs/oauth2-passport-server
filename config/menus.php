<?php


/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

return [

    "merge" => [

        //"admin_menu" => [
        //],

        'developers' => [
            'aouth2' => [
                "name" => "OAuth2 Clients",
                "route" => "passport.clients.index",
                "icon" => "mdi mdi-connection",
                "service" => "developer:oauth",
                "position" => 1
            ],
            'api' => [
                "name" => "Api Keys",
                "route" => "passport.personal.tokens.index",
                "icon" => "mdi mdi-xml",
                "service" => "developer:api",
            ],
            "horizon" => [
                "name" => "Horizon",
                "route" => "horizon.index",
                "icon" => "mdi mdi-poll",
                "service" => "developer:horizon"
            ],
            "sitemap" => [
                "name" => "Sitemap",
                "route" => "admin.sitemaps.index",
                "icon" => "mdi mdi-sitemap-outline",
                "service" => "developer:seo"
            ],
            "page_creator" => [
                "name" => "Pages",
                "route" => "admin.pages.index",
                "icon" => "mdi mdi-hammer-wrench",
                "service" => "developer:pages"
            ],
            "legal" => [
                'name' => 'Policies',
                'route' => 'admin.policies.terms-and-conditions',
                'icon' => "mdi mdi-file-sign",
                'service' => "administrator:settings",
            ],
            "langs" => [
                "name" => "Lang",
                "route" => "admin.langs.index",
                "icon" => "mdi mdi-translate",
                "service" => "developer:lang"
            ],
            "logs" => [
                "name" => "Logs",
                "route" => "admin.logs",
                "icon" => "mdi mdi-text-box-outline",
                "service" => "developer:logs"
            ]
        ],

        //"user_routes" => [
        // "ecommerce" => [ // group
        //     "id" => "my-route", // id
        //     "name" => "My Route",
        //     "route" => "example.route",
        //     "icon" => "mdi-store-cog",
        //     'service' => true // true or scope, example "administrator:role:view"
        // ],
        // ],

        // Only parent api routes
        // "api" => [
        // example
        // 'ecommerce' => [ // paren group
        //    'search' => 'api.ecommerce.search', // route
        // ]
        // ]

    ],

    "sitemap_menus" => [
        [
            "id" => "list_of_pages",
            "name" => "List of pages",
            "route" => "admin.sitemaps.index",
            "icon" => "mdi mdi-sitemap-outline",
            'service' => "developer:seo",
            "position" => 1
        ],
        [
            "id" => "robot",
            "name" => "Robot",
            "route" => "admin.sitemaps.robot.form",
            "icon" => "mdi mdi-robot-angry-outline",
            'service' => "developer:seo",
            "position" => 2
        ]
    ],

    "pages" => [
        [
            'id' => 'list_of_pages',
            'name' => 'List of pages',
            'route' => 'admin.pages.index',
            'icon' => 'mdi mdi-file-document-outline',
            'service' => "developer:pages:view",
            "position" => 1
        ],
        [
            'id' => 'layouts',
            'name' => 'Layouts',
            'route' => 'admin.layouts.schema',
            'icon' => 'mdi mdi-file-document-outline',
            'service' => "developer:pages:view",
            "position" => 2
        ],
        [
            'id' => 'static_pages',
            'name' => 'Static pages',
            'route' => 'admin.seo.schema',
            'icon' => 'mdi mdi-file-document-outline',
            'service' => "developer:pages:view",
            "position" => 3
        ],
        [
            'id' => 'policies',
            'name' => 'Policies',
            'route' => 'admin.policies.schema',
            'icon' => 'mdi mdi-file-document-outline',
            'service' => "developer:pages:view",
            "position" => 4
        ],
        [
            'id' => 'sitemap_urls',
            'name' => 'Sitemap URLs',
            'route' => 'admin.sitemaps.index',
            'icon' => 'mdi mdi-sitemap',
            'service' => 'developer:seo:view',
            "position" => 5
        ],
        [
            'id' => 'robots_txt',
            'name' => 'Robots.txt',
            'route' => 'admin.sitemaps.robot.form',
            'icon' => 'mdi mdi-robot',
            'service' => 'developer:seo:view',
            "position" => 6
        ],
        [
            'id' => 'favicon',
            'name' => 'Favicon',
            'route' => 'admin.sitemaps.favicon.form',
            'icon' => 'mdi mdi-upload-circle-outline',
            'service' => 'developer:seo:view',
            "position" => 7
        ],
    ]
];
