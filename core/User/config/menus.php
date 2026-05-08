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

    "user_dashboard" => [
        "id" => "dashboard",
        "name" => "Dashboard",
        "route" => "user.dashboard",
        "icon" => "mdi mdi-home",
        'service' => true,
    ],

    "merge" => [

        "admin_dashboard" => [
            'groups' => [
                "id" => "groups",
                "name" => "Groups",
                "route" => "user.admin.groups.index",
                "icon" => "mdi mdi-account-group",
                'service' => 'administrator:group',
                'position' => 1
            ],
            'roles' => [
                "id" => "roles",
                "name" => "Roles",
                "route" => "user.admin.roles.index",
                "icon" => "mdi mdi-format-list-group",
                'service' => 'administrator:role',
                'position' => 2
            ],
            'services' => [
                "id" => "services",
                "name" => "Services",
                "route" => "user.admin.services.index",
                "icon" => "mdi mdi-text-box-check",
                'service' => 'administrator:service',
                'position' => 3
            ],
            'users' => [
                "id" => "users",
                "name" => "Users",
                "route" => "user.admin.users.index",
                "icon" => "mdi mdi-account-multiple",
                'service' => 'administrator:user',
                'position' => 4
            ],
            'oauth2_and_openid_connect' => [
                "id" => "Oauth2 and OpenId Connect",
                "name" => "Clients OAuth",
                "route" => "admin.clients.index",
                "icon" => "mdi mdi-apps",
                'service' => 'administrator:application',
                'position' => 5
            ],
            "settings" => [
                "name" => "Settings",
                "route" => "admin.settings.general",
                "icon" => "mdi mdi-cogs",
                'service' => 'administrator:settings',
            ],
        ],

        "user_routes" => [
            'notifications' => [
                'id' => 'notifications',
                'name' => 'My Notifications',
                'route' => 'user.notifications',
                'icon' => 'mdi mdi-bell-circle-outline',
                'service' => true,
            ],
        ],

        "user_settings" => [

            "profile" => [
                'id' => 'profile',
                'name' => 'Update Information',
                'route' => 'user.profile',
                'icon' => 'mdi mdi-account-details-outline',
                'service' => true,
            ],
            "password" => [
                'id' => 'password',
                'name' => 'Change password',
                'route' => 'user.password',
                'icon' => 'mdi mdi-lock-reset',
                'service' => true,
            ],

            '2fa' => [
                'id' => '2fa',
                'name' => 'Two Factor Authorization',
                'route' => 'user.twoFactor',
                'icon' => 'mdi mdi-two-factor-authentication',
                'service' => true,
            ],

        ],
    ],

    "admin_routes" => [
        [
            "id" => "groups",
            "name" => "Groups",
            "route" => "user.admin.groups.index",
            "icon" => "mdi mdi-account-group",
            'service' => 'administrator:group',
            'position' => 2,
        ],
        [
            "id" => "roles",
            "name" => "Roles",
            "route" => "user.admin.roles.index",
            "icon" => "mdi mdi-format-list-group",
            'service' => 'administrator:role',
            'position' => 3,
        ],
        [
            "id" => "services",
            "name" => "Services",
            "route" => "user.admin.services.index",
            "icon" => "mdi mdi-text-box-check",
            'service' => 'administrator:service',
            'position' => 4,
        ],
        [
            "id" => "users",
            "name" => "Users",
            "route" => "user.admin.users.index",
            "icon" => "mdi mdi-account-multiple",
            'service' => 'administrator:user',
            'position' => 5,
        ],
        [
            "id" => "clients",
            "name" => "Clients OAuth",
            "route" => "admin.clients.index",
            "icon" => "mdi mdi-apps",
            'service' => 'administrator:application',
            'position' => 6,
        ],
    ]
];
