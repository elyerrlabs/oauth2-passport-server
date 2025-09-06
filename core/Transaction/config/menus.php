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
 *
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
 */

return [
    "transaction_dashboard" => [
        "id" => "transaction",
        "name" => __("Transaction"),
        "route" => "transaction.admin.dashboard",
        "icon" => "mdi-swap-vertical",
        'show' => "administrator"
    ],

    'delivery_address' => [
        'id' => 'index',
        'name' => __('Add address'),
        'route' => 'transaction.delivery.addresses.index',
        'icon' => 'mdi-map-marker-outline',
        'show' => true,
    ],

    /**
     * Merge route in another menu
     */
    "merge" => [

        "user_routes" => [

            'subscription' => [
                'id' => 'subscriptions',
                'name' => __('Subscriptions'),
                'route' => 'transaction.subscriptions.index',
                'icon' => 'mdi-gift-outline',
                'show' => true,
            ],
            'store' => [
                'id' => 'store',
                'name' => __('Buy subscription'),
                'route' => 'transaction.plans.index',
                'icon' => 'mdi-currency-usd',
                'show' => true,
            ],
        ],

        "transactions" => [
            'plans' => [
                "id" => "plans",
                "name" => __("Subscriptions"),
                "route" => "transaction.plans.index",
                "icon" => "mdi-cash-clock",
                'show' => true,
            ],
        ]
    ],

    "transaction_routes" => [
        [
            "id" => "dashboard",
            "name" => __("Dashboard"),
            "route" => "transaction.admin.dashboard",
            "icon" => "mdi-view-dashboard",
            'show' => 'administrator',
        ],
        [
            'id' => 'transaction',
            'name' => __('Transactions'),
            'route' => 'transaction.admin.transactions.index',
            'icon' => 'mdi-cash',
            'show' => true,
        ],
        [
            "id" => "plans",
            "name" => __("Plans"),
            "route" => "transaction.admin.plans.index",
            "icon" => "mdi-cash-clock",
            'show' => 'administrator',
        ],
    ]
];
