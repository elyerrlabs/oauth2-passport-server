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

        "admin_dashboard" => [
            "horizon" => [
                "name" => "Horizon",
                "route" => "horizon.index",
                "icon" => "mdi mdi-poll",
                "service" => "administrator:admin"
            ],
            "seo" => [
                "name" => "Seo",
                "route" => "admin.sitemaps.index",
                "icon" => "mdi mdi-sitemap-outline",
                "service" => "administrator:seo"
            ],
        ],

        //"user_routes" => [
        // "ecommerce" => [ // group
        //     "id" => "my-route", // id
        //     "name" => __("My Route"),
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

    "seo_menus" => [
        [
            "id" => "list_of_pages",
            "name" => "List of pages",
            "route" => "admin.sitemaps.index",
            "icon" => "mdi-sitemap-outline",
            'service' => "administrator:seo"

        ],
        [
            "id" => "meta_tags",
            "name" => "Meta tags",
            "route" => "admin.sitemaps.meta.form",
            "icon" => "mdi-code-block-tags",
            'service' => "administrator:seo"
        ],
        [
            "id" => "robot",
            "name" => "Robot",
            "route" => "admin.sitemaps.robot.form",
            "icon" => "mdi-robot-angry-outline",
            'service' => "administrator:seo"
        ]
    ],
];
