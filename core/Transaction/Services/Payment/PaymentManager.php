<?php

namespace Core\Transaction\Services\Payment;

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

use Exception;
use Core\Transaction\Model\Transaction;
use Core\Transaction\Services\Payment\Drivers\StripeSubscription;
use Core\Transaction\Services\Payment\Drivers\OfflineSubscription;

class PaymentManager
{
    protected array $drivers = [
        'stripe' => StripeSubscription::class,
        'offline' => OfflineSubscription::class,
    ];

    /**
     * Resolve instance
     * @param string $method
     * @throws \Exception
     * @return StripeSubscription|OfflineSubscription
     */
    public function resolve(string $method)
    {
        if (!isset($this->drivers[$method])) {
            throw new Exception("Unsupported method", 404);
        }

        return app($this->drivers[$method]);
    }

    /**
     * Process payment
     * @param string $method
     * @param array $data
     */
    public function buy(string $method, array $data)
    {
        return $this->resolve($method)->buy($data);
    }

    /**
     * Charge recurring payment
     * @param string $method
     * @param array $package
     */
    public function chargeRecurringPayment(string $method, $package)
    {
        return $this->resolve($method)->chargeRecurringPayment($package);
    }

    /**
     * Force activation of failed transactions
     * @param string $method
     * @param array $response
     */
    public function forceActivation(string $method, array $response)
    {
        return $this->resolve($method)->forceActivation($response);
    }

    /**
     * Cancel 
     * @param string $method
     * @param  Transaction $transaction
     */
    public function cancel(string $method, Transaction $transaction)
    {
        return $this->resolve($method)->cancel($transaction);
    }

    /**
     * Refund
     * @param string $method
     * @param array $transaction
     */
    public function refund(string $method, array $transaction)
    {
        return $this->resolve($method)->refund($transaction);
    }
}

