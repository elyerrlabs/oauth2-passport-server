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
        "name" => __("Dashboard"),
        "route" => "user.dashboard",
        "icon" => "mdi-home",
        'service' => true,
    ],

    "notification" => [
        "id" => "notification",
        "name" => __("Notification"),
        "route" => "user.notification.unread",
        "icon" => "mdi-bell",
        'service' => true,
    ],

    "notification_mark_as_read" => [
        "id" => "notification_mark_as_read",
        "name" => __("Unread Notification"),
        "route" => "user.notification.mark-all-as-read",
        "icon" => "mdi-bell",
        'service' => true,
    ],


    "merge" => [

        "admin_dashboard" => [

            "administrator" => [
                "id" => "admin",
                "name" => __("Admin"),
                "route" => "user.admin.dashboard",
                "icon" => "mdi-security",
                'service' => "administrator:admin",
            ],

            "settings" => [
                "name" => __("Settings"),
                "route" => "admin.settings.general",
                "icon" => "mdi-cogs",
                'service' => 'administrator:settings',
            ],

        ],

        "user_settings" => [

            "profile" => [
                'id' => 'profile',
                'name' => __('Update Information'),
                'route' => 'user.profile',
                'icon' => 'mdi-account-details-outline',
                'service' => true,
            ],
            "password" => [
                'id' => 'password',
                'name' => __('Change password'),
                'route' => 'user.password',
                'icon' => 'mdi-lock-reset',
                'service' => true,
            ],
            '2fa' => [
                'id' => '2fa',
                'name' => __('Two Factor Authorization'),
                'route' => 'user.2fa.request',
                'icon' => 'mdi-two-factor-authentication',
                'service' => true,
            ],

        ],
    ],

    "admin_routes" => [
        [
            "id" => "dashboard",
            "name" => __("Dashboard"),
            "route" => "user.admin.dashboard",
            "icon" => "mdi-view-dashboard",
            'service' => 'administrator:admin',
            'position' => 1,
        ],
        [
            "id" => "groups",
            "name" => __("Groups"),
            "route" => "user.admin.groups.index",
            "icon" => "mdi-account-group",
            'service' => 'administrator:group',
            'position' => 2,
        ],
        [
            "id" => "roles",
            "name" => __("Roles"),
            "route" => "user.admin.roles.index",
            "icon" => "mdi-format-list-group",
            'service' => 'administrator:role',
            'position' => 3,
        ],
        [
            "id" => "services",
            "name" => __("Services"),
            "route" => "user.admin.services.index",
            "icon" => "mdi-text-box-check",
            'service' => 'administrator:service',
            'position' => 4,
        ],
        [
            "id" => "users",
            "name" => __("Users"),
            "route" => "user.admin.users.index",
            "icon" => "mdi-account-multiple",
            'service' => 'administrator:user',
            'position' => 5,
        ],
        [
            "id" => "clients",
            "name" => __("Clients OAuth"),
            "route" => "admin.clients.index",
            "icon" => "mdi-apps",
            'service' => 'administrator:application',
            'position' => 6,
        ],
    ]
];
