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

    "admin_dashboard" => [
        "name" => "Admin",
        "route" => "user.admin.dashboard",
        "icon" => "mdi-security",
        'show' => "administrator",
    ],

    "merge" => [

        "user_routes" => [
            1 => [
                'id' => 'me',
                'name' => 'Me',
                'route' => 'user.dashboard',
                'icon' => 'mdi-information',
                'show' => true,
            ],
            2 => [
                'id' => 'profile',
                'name' => 'Profile',
                'route' => 'user.profile',
                'icon' => 'mdi-account-details-outline',
                'show' => true,
            ],
            3 => [
                'name' => 'Password',
                'route' => 'user.password',
                'icon' => 'mdi-lock-reset',
                'show' => true,
            ],
            4 => [
                'id' => '2fa',
                'name' => '2FA',
                'route' => 'user.2fa.request',
                'icon' => 'mdi-two-factor-authentication',
                'show' => true,
            ],
            5 => [
                'id' => 'notifications',
                'name' => 'Notifications',
                'route' => 'user.notification.index',
                'icon' => 'mdi-bell-badge-outline',
                'show' => true,
                'count' => request()->user() ? request()->user()->unreadNotifications()->count() : 0
            ]
        ],
    ],
    /*[
        'name' => 'Developers',
        'icon' => 'mdi-tools',
        'show' => intval(config('routes.users.developers')) ? true : false,
        'menu' => [
            [
                'name' => 'Applications',
                'route' => intval(config('routes.users.api')) ? route('passport.clients.index') : null,
                'icon' => 'mdi-wan',
                'show' => intval(config('routes.users.clients')) ? true : false
            ],
            [
                'name' => 'API Key',
                'route' => intval(config('routes.users.api')) ? route('passport.personal.tokens.index') : null,
                'icon' => 'mdi-shield-key-outline',
                'show' => intval(config('routes.users.api')) ? true : false,
            ],
        ]
    ],*/

    "admin_routes" => [
        [
            "name" => "Dashboard",
            "route" => "user.admin.dashboard",
            "icon" => "mdi-view-dashboard",
            'show' => 'administrator',
        ],
        [
            "name" => "Groups",
            "route" => "user.admin.groups.index",
            "icon" => "mdi-account-group",
            'show' => 'administrator',
        ],
        [
            "name" => "Roles",
            "route" => "user.admin.roles.index",
            "icon" => "mdi-format-list-group",
            'show' => 'administrator',
        ],
        [
            "name" => "Services",
            "route" => "user.admin.services.index",
            "icon" => "mdi-text-box-check",
            'show' => 'administrator',
        ],
        [
            "name" => "Users",
            "route" => "user.admin.users.index",
            "icon" => "mdi-account-multiple",
            'show' => 'administrator',
        ],
        /*[
            "name" => "Clients",
            "route" => "user.admin.clients.index",
            "icon" => "mdi-apps",
            'show' => 'administrator',
        ],
        [
            "name" => "Broadcasts",
            "route" => "user.admin.broadcasts.index",
            "icon" => "mdi-broadcast",
            'show' => 'administrator',
        ],
        [
            "name" => "Plans",
            "route" => "user.admin.plans.index",
            "icon" => "mdi-cash-clock",
            'show' => 'administrator',
        ],
        [
            "name" => "Transactions",
            "route" => "user.admin.transactions.index",
            "icon" => "mdi-account-cash-outline",
            'show' => 'administrator',
        ],
        
        [
            "name" => "Terminal",
            "route" => "admin.terminals.index",
            "icon" => "mdi-console",
            'show' => 'administrator',
        ],
        ,*/
    ]
];
