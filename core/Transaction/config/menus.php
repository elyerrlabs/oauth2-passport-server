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

    'delivery_address' => [
        'id' => 'index',
        'name' => __('Add address'),
        'route' => 'transaction.delivery.addresses.index',
        'icon' => 'mdi-map-marker-outline',
        'service' => true,
    ],

    /**
     * Merge route in another menu
     */
    "merge" => [

        "api" => [
            "transactions" => [
                'billing_period' => 'api.transaction.payments.billing-period',
                'currencies' => 'api.transaction.payments.currencies',
                'methods' => 'api.transaction.payments.methods',
                'statuses' => 'api.transaction.payments.status',
                'types' => 'api.transaction.payments.types',
                'refund_statuses' => 'api.transaction.refund.status',
                'services_list' => 'api.transaction.services.list',
                //  'users_refund_index' => 'api.transaction.users.refund.index',
                //  'users_refund_store' => 'api.transaction.users.refund.store',
                //  'admin_refund_index' => 'api.transaction.admin.refund.index',
                //  'admin_refund_update' => 'api.transaction.admin.refund.update',
            ],
        ],

        "admin_dashboard" => [

            "transactions" => [
                "id" => "transaction",
                "name" => __("Transactions"),
                "route" => "transaction.admin.dashboard",
                "icon" => "mdi-swap-vertical",
                'service' => "administrator:transactions"
            ],
        ],

        "user_routes" => [
            'transaction' => [
                'id' => 'transaction',
                'name' => __('My transactions'),
                'route' => 'transaction.transactions.index',
                'icon' => 'mdi-cash-edit',
                'service' => true,
            ],
            'my_subscription' => [
                'id' => 'subscriptions',
                'name' => __('My subscriptions'),
                'route' => 'transaction.subscriptions.index',
                'icon' => 'mdi-gift-outline',
                'service' => true,
            ],
            'buy_subscription' => [
                'id' => 'store',
                'name' => __('Buy subscription'),
                'route' => 'transaction.plans.index',
                'icon' => 'mdi-currency-usd',
                'service' => true,
            ],
        ],
    ],

    "transaction_routes" => [
        [
            "id" => "dashboard",
            "name" => __("Dashboard"),
            "route" => "transaction.admin.dashboard",
            "icon" => "mdi-view-dashboard",
            'service' => 'administrator:transactions',
        ],
        [
            'id' => 'transaction',
            'name' => __('Transactions'),
            'route' => 'transaction.admin.transactions.index',
            'icon' => 'mdi-cash',
            'service' => "administrator:transactions",
        ],
        [
            'id' => 'refund',
            'name' => 'Refunds',
            'route' => "transaction.admin.refunds.index",
            'icon' => "mdi-cash-refund",
            'service' => 'administrator:refund',
        ],
        [
            "id" => "plans",
            "name" => __("Plans"),
            "route" => "transaction.admin.plans.index",
            "icon" => "mdi-cash-clock",
            'service' => 'administrator:plan',
        ],
    ]
];
