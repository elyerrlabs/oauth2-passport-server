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

    "ecommerce_orders" => [
        "id" => "orders",
        "name" => __("My cart"),
        "route" => "ecommerce.orders.index",
        "icon" => "mdi-store-cog",
        'service' => true
    ],

    "ecommerce_checkout" => [
        "id" => "orders",
        "name" => __("My checkouts"),
        "route" => "ecommerce.checkouts.index",
        "icon" => "mdi-store-cog",
        'service' => true
    ],

    "ecommerce_orders_api" => [
        "id" => "orders",
        "name" => __("My cart"),
        "route" => "api.ecommerce.orders.index",
        "icon" => "mdi-store-cog",
        'service' => true
    ],

    "merge" => [

        "admin_dashboard" => [
            "ecommerce" => [
                "id" => "ecommerce",
                "name" => __("Ecommerce"),
                "route" => "ecommerce.admin.dashboard",
                "icon" => "mdi-store-cog",
                'service' => "administrator:ecommerce"
            ],
        ],

        "user_routes" => [

            "ecommerce" => [
                "id" => "ecommerce",
                "name" => __("Ecommerce"),
                "route" => "ecommerce.dashboard",
                "icon" => "mdi-store-cog",
                'service' => true
            ],

            "ecommerce_orders" => [
                "id" => "orders",
                "name" => __("My cart"),
                "route" => "ecommerce.orders.index",
                "icon" => "mdi-store-cog",
                'service' => true
            ],

            "ecommerce_checkout" => [
                "id" => "orders",
                "name" => __("My orders"),
                "route" => "ecommerce.checkouts.index",
                "icon" => "mdi-store-cog",
                'service' => true
            ],
        ]
    ],

    "ecommerce_menus" => [
        [
            "id" => 'dashboard',
            "name" => __("Dashboard"),
            "route" => "ecommerce.admin.dashboard",
            "icon" => "mdi-view-dashboard",
            'service' => "administrator:ecommerce"
        ],
        [
            "id" => "categories",
            "name" => __("Categories"),
            "route" => "ecommerce.admin.categories.index",
            "icon" => "mdi-circle-outline",
            'service' => "administrator:ecommerce"
        ],
        [
            "id" => "products",
            "name" => __("Products"),
            "route" => "ecommerce.admin.products.index",
            "icon" => "mdi-circle-outline",
            'service' => "administrator:ecommerce"
        ],
        [
            "id" => "orders",
            "name" => __("Orders"),
            "route" => "ecommerce.admin.orders.complete",
            "icon" => "mdi-format-list-checks",
            'service' => "administrator:ecommerce"
        ],
        [
            "id" => "orders_pending",
            "name" => __("Pending"),
            "route" => "ecommerce.admin.orders.pending",
            "icon" => "mdi-clock-outline",
            'service' => "administrator:ecommerce"
        ],
    ],
];



