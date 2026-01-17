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

        "user_routes" => [

            "partner" => [
                "id" => "partner",
                "name" => __("Partner"),
                "route" => "partner.dashboard",
                "icon" => "mdi-account-cash",
                'service' => 'reseller:partner',
            ],
        ],

        "admin_dashboard" => [

            /*  "partner" => [
                  "id" => "list",
                  "name" => __("Partners"),
                  "route" => "partner.admin.partner.index",
                  "icon" => "mdi-handshake-outline",
                  'service' => 'administrator:partner',
              ]*/
        ],
    ],

    "admin_routes" => [
        [
            "id" => "list",
            "name" => __("Partners"),
            "route" => "partner.admin.partner.index",
            "icon" => "mdi-handshake-outline",
            'service' => 'administrator:partner',
            'position' => 7
        ]
    ],

    "partner_routes" => [
        [
            "id" => "dashboard",
            "name" => __("Dashboard"),
            "route" => "partner.dashboard",
            "icon" => "mdi-account-cash",
            'service' => 'reseller:partner',
        ],
        [
            "id" => "referral_link",
            "name" => __("Referral Link"),
            "route" => "partner.show",
            "icon" => "mdi-reload",
            'service' => 'reseller:partner',
        ],
        [
            "id" => "sales",
            "name" => __("My Sales"),
            "route" => "partner.sales",
            "icon" => "mdi-cash-multiple",
            'service' => 'reseller:partner',
        ],

    ]
];
