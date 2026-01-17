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

        "admin_routes" => [
            'refunds' => [
                'id' => 'review-refund',
                'name' => __('Review refunds'),
                'route' => "transaction.admin.refunds.review.index",
                'icon' => "mdi-credit-card-refund-outline",
                'service' => 'administrator:refunds',
            ]
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
            'service' => 'administrator:refunds',
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
