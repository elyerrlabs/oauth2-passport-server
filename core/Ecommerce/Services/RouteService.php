<?php

namespace Core\Ecommerce\Services;

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

final class RouteService
{

    /**
     * Public API Web routes
     * @param array $routes
     * @return array 
     */
    public static function api(array $routes = [])
    {
        $defaults = [
            'search' => route('api.ecommerce.web.search'),
            'categories' => route('api.ecommerce.web.categories.index'),
            'filters' => route('api.ecommerce.web.filters.index'),
            'checkouts' => route('api.ecommerce.web.checkouts.index'),
            'orders' => route('api.ecommerce.web.orders.index'),
            'payments' => route('api.ecommerce.web.payments.store'),
        ];

        return array_merge($defaults, $routes);
    }

    /**
     * Admin
     * @return array
     */
    public static function admin()
    {
        return [
            'categories' => route('api.ecommerce.admin.categories.store'),
            'orders' => route('api.ecommerce.admin.orders.complete'),
            'customers' => route('api.ecommerce.admin.orders.customers'),
            'pending' => route('api.ecommerce.admin.orders.pending'),
            'products' => route('api.ecommerce.admin.products.index')
        ];
    }
}
