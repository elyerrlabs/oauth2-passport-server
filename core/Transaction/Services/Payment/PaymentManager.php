<?php

namespace Core\Transaction\Services\Payment;

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
     * @return mixed|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application
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
}

