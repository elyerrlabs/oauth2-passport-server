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

use Illuminate\Support\Carbon;


if (!function_exists('billing_get_period')) {
    /**
     * Get the period.
     *
     * @param string $currency
     * @return array|null
     */
    function billing_get_period(string $period)
    {
        $periods = config('billing.period');

        return $periods[strtolower($period)] ?? null;
    }
}

if (!function_exists('billing_period_end')) {
    /**
     * Get the end date of a billing period based on the config.
     *
     * @param string $billing_period
     * @return \Illuminate\Support\Carbon|null
     */
    function billing_period_end(string $billing_period)
    {
        $period = config('billing.period.' . $billing_period);
        if (!$period) {
            return null;
        }
        return Carbon::now()->{"add" . ucfirst($period['unit'])}($period['interval']);
    }
}


if (!function_exists('billing_get_status')) {
    /**
     * Get the full status information.
     * @param string $status
     * @return Illuminate\Support\Collection 
     */
    function billing_get_status(string $status)
    {
        return collect(config('billing.status'))->map(function ($item) {
            return collect($item)->only(['name', 'message']);
        });
    }
}

if (!function_exists('billing_get_status_message')) {
    /**
     * Get the message for a given status.
     *
     * @param string $status
     * @return string|null
     */
    function billing_get_status_message(string $status)
    {
        return config('billing.status.' . $status . '.message');
    }
}

if (!function_exists('billing_get_status_name')) {
    /**
     * Get the name for a given status.
     *
     * @param string $status
     * @return string|null
     */
    function billing_get_status_name(string $period)
    {
        return config('billing.period.' . $period . '.name', $period);
    }
}

if (!function_exists('billing_get_currency')) {
    /**
     * Get the currency information by code.
     *
     * @param string $currency
     * @return array|null
     */
    function billing_get_currency(string $currency)
    {
        $currencies = config('billing.currency');

        foreach ($currencies as $key) {
            if (strtolower($key['code']) === strtolower($currency)) {
                return $key;
            }
        }

        return null;
    }
}


if (!function_exists('billing_get_expiration_date')) {
    /**
     * Get the expiration date by adding the billing period to the current date and time.
     *
     * @param string $periodKey
     * @return mixed
     */
    function billing_get_expiration_date(string $periodKey)
    {
        $period = billing_get_period($periodKey);

        if (!$period) {
            return null; // Period not found
        }

        $interval = $period['interval'];
        $unit = $period['unit'];

        return now()->add($unit, $interval);
    }
}


if (!function_exists('billing_get_currency_name')) {
    /**
     * Get the currency name by code.
     *
     * @param string $currency
     * @return string|null
     */
    function billing_get_currency_name(string $currency)
    {
        $currency = billing_get_currency($currency);
        return $currency['name'] ?? null;
    }
}

if (!function_exists('billing_get_method')) {
    /**
     * Get the payment method name.
     *
     * @param string $method
     * @return string|null
     */
    function billing_get_method(string $method)
    {
        return config("billing.methods." . $method) ?? null;
    }
}

if (!function_exists('billing_get_tax_percentage')) {
    /**
     * Get the tax percentage value.
     *
     * @return int
     */
    function billing_get_tax_percentage()
    {
        return config('billing.tax_percentage', 0);
    }
}

if (!function_exists('billing_get_grace_period_days')) {
    /**
     * Get the grace period in days.
     *
     * @return int
     */
    function billing_get_grace_period_days()
    {
        return config('billing.grace_period_days', 0);
    }
}

if (!function_exists('billing_is_valid_period')) {
    /**
     * Validate if the given period name exists.
     *
     * @param string $period
     * @return bool
     */
    function billing_is_valid_period(string $period): bool
    {
        return array_key_exists($period, config('billing.period', []));
    }
}

if (!function_exists('billing_is_valid_status')) {
    /**
     * Validate if the given status name exists.
     *
     * @param string $status
     * @return bool
     */
    function billing_is_valid_status(string $status): bool
    {
        return array_key_exists($status, config('billing.status', []));
    }
}

if (!function_exists('billing_is_valid_method')) {
    /**
     * Validate if the given payment method exists.
     *
     * @param string $method
     * @return bool
     */
    function billing_is_valid_method(string $method): bool
    {
        return array_key_exists($method, config('billing.methods', []));
    }
}

if (!function_exists('billing_is_valid_currency')) {
    /**
     * Validate if the given currency code exists.
     *
     * @param string $currency
     * @return bool
     */
    function billing_is_valid_currency(string $currency): bool
    {
        return collect(config('billing.currency'))
            ->contains(fn($item) => strtolower($item['usd'] ?? $item['eur'] ?? '') === strtolower($currency));
    }
}

if (!function_exists('billing_currencies')) {
    /**
     * billing currencies
     * @return array
     */
    function billing_currencies()
    {
        return array_values(config('billing.currency'));
    }
}

if (!function_exists('billing_periods')) {
    /**
     * billing periods
     * @return array
     */
    function billing_periods()
    {
        return array_values(config("billing.period"));
    }
}

if (!function_exists('billing_methods')) {
    /**
     * Billing methods
     * @return array
     */
    function billing_methods()
    {
        return array_values(config('billing.methods'));
    }
}

if (!function_exists('billing_statuses')) {
    /**
     * billing status
     * @return array
     */
    function billing_statuses()
    {
        return array_values(config('billing.status'));
    }
}

if (!function_exists('billings_types')) {
    /**
     * billing status
     * @return array
     */
    function billings_types()
    {
        return array_values(config('billing.types'));
    }
}


if (!function_exists('searchByDate')) {
    /**
     * Search type of date to search
     * @param mixed $index
     */
    function searchByDate($index)
    {
        $attribute = [
            'day' => 'YYYY-MM-DD',
            'month' => 'YYYY-MM',
            'year' => 'YYYY'
        ];

        return $attribute[$index] ?? 'YYYY-MM-DD';
    }
}

