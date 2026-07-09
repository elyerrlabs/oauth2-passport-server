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
            "modules" => [
                "name" => "Modules",
                "route" => "admin.modules.index",
                "icon" => "mdi mdi-view-module",
                "service" => "developer:modules:view",
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
];
