<?php

/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 */

return [

    "user_dashboard" => [
        "id" => "dashboard",
        "name" => __("Dashboard"),
        "route" => "user.dashboard",
        "icon" => "mdi-home",
        'show' => true,
    ],

    "notification" => [
        "id" => "notification",
        "name" => __("Notification"),
        "route" => "user.notification.unread",
        "icon" => "mdi-bell",
        'show' => true,
    ],

    "notification_mark_as_read" => [
        "id" => "notification_mark_as_read",
        "name" => __("Unread Notification"),
        "route" => "user.notification.mark-all-as-read",
        "icon" => "mdi-bell",
        'show' => true,
    ],

    "admin_dashboard" => [
        "id" => "admin",
        "name" => __("Admin"),
        "route" => "user.admin.dashboard",
        "icon" => "mdi-security",
        'show' => "administrator",
    ],

    "merge" => [

        "user_routes" => [

            "profile" => [
                'id' => 'profile',
                'name' => __('Information'),
                'route' => 'user.profile',
                'icon' => 'mdi-account-details-outline',
                'show' => true,
            ],
            "password" => [
                'id' => 'password',
                'name' => __('Change password'),
                'route' => 'user.password',
                'icon' => 'mdi-lock-reset',
                'show' => true,
            ],
            '2fa' => [
                'id' => '2fa',
                'name' => __('Two Factor Authorization'),
                'route' => 'user.2fa.request',
                'icon' => 'mdi-two-factor-authentication',
                'show' => true,
            ],
            "notifications" => [
                'id' => 'notifications',
                'name' => __('Notifications'),
                'route' => 'user.notification.index',
                'icon' => 'mdi-bell-badge-outline',
                'show' => true,
                'count' => request()->user() ? request()->user()->unreadNotifications()->count() : 0
            ]
        ],
    ],

    "admin_routes" => [
        [
            "id" => "dashboard",
            "name" => __("Dashboard"),
            "route" => "user.admin.dashboard",
            "icon" => "mdi-view-dashboard",
            'show' => 'administrator',
        ],
        [
            "id" => "groups",
            "name" => __("Groups"),
            "route" => "user.admin.groups.index",
            "icon" => "mdi-account-group",
            'show' => 'administrator',
        ],
        [
            "id" => "roles",
            "name" => __("Roles"),
            "route" => "user.admin.roles.index",
            "icon" => "mdi-format-list-group",
            'show' => 'administrator',
        ],
        [
            "id" => "services",
            "name" => __("Services"),
            "route" => "user.admin.services.index",
            "icon" => "mdi-text-box-check",
            'show' => 'administrator',
        ],
        [
            "id" => "users",
            "name" => __("Users"),
            "route" => "user.admin.users.index",
            "icon" => "mdi-account-multiple",
            'show' => 'administrator',
        ],
        [
            "id" => "clients",
            "name" => __("Clients"),
            "route" => "admin.clients.index",
            "icon" => "mdi-apps",
            'show' => 'administrator',
        ],
    ]
];
