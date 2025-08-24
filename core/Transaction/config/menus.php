<?php

return [
    "transaction_dashboard" => [
        "id" => "transaction",
        "name" => "Transaction",
        "route" => "transaction.admin.dashboard",
        "icon" => "mdi-swap-vertical",
        'show' => "administrator"
    ],

    /**
     * Merge route in another menu
     */
    "merge" => [

        "user_routes" => [

            12 => [
                'id' => 'subscriptions',
                'name' => 'Subscriptions',
                'route' => 'transaction.subscriptions.index',
                'icon' => 'mdi-gift-outline',
                'show' => true,
            ],
            13 => [
                'id' => 'store',
                'name' => 'Buy subscription',
                'route' => 'transaction.plans.index',
                'icon' => 'mdi-currency-usd',
                'show' => true,
            ],
        ],

        "transactions" => [
            1 => [
                "id" => "plans",
                "name" => "Subscriptions",
                "route" => "transaction.plans.index",
                "icon" => "mdi-cash-clock",
                'show' => true,
            ],
        ]
    ],

    "transaction_routes" => [
        [
            "name" => "Dashboard",
            "route" => "transaction.admin.dashboard",
            "icon" => "mdi-view-dashboard",
            'show' => 'administrator',
        ],
        [
            'id' => 'transaction',
            'name' => 'Transactions',
            'route' => 'transaction.admin.transactions.index',
            'icon' => 'mdi-cash',
            'show' => true,
        ],
        [
            "name" => "Plans",
            "route" => "transaction.admin.plans.index",
            "icon" => "mdi-cash-clock",
            'show' => 'administrator',
        ],
    ]
];
