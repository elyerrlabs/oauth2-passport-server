<?php

namespace Core\Transaction\Services\Payment\Drivers;

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

use Illuminate\Support\Fluent;
use Core\Transaction\Model\Transaction;
use Core\Transaction\Repositories\TransactionRepository;
use Core\Transaction\Services\Payment\Contracts\PaymentMethod; 

class OfflineSubscription implements PaymentMethod
{
    /**
     * Provider name
     * @var string
     */
    protected $method = "offline";


    /**
     * Repository of the transaction
     */
    public $repository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->repository = $transactionRepository;
    }

    /**
     * Process data
     * @param array $data
     * @return  
     */
    public function subscription(array $data)
    {
        $user = auth()->user();

        $session = new Fluent([
            'id' => $this->repository->generateSessionId(),
            'currency' => $data['price']['currency'],
            'amount_subtotal' => $data['price']['amount'],
            'amount_total' => $data['price']['amount'],
            'payment_intent' => $this->repository->generateIntent(),
            'metadata' => [
                'user_id' => $user->id,
                'transaction_code' => $data['transaction_code'],
            ],
            'url' => route('transaction.checkout.success') . "?code={$data['transaction_code']}",
        ]);

        return $session;
    }

    public function chargeRecurringPayment(array $package)
    {

    }


    /**
     * Abort operation
     * @param  Transaction $transaction
     * @return void
     */
    public function cancel(Transaction $transaction)
    {

    }


    public function forceActivation(array $response)
    {
        $response['payment_method'] = null;
        $response["status"] = config('billing.status.successful.name');
        $response["receipt_url"] = route('transaction.checkout.success') . "?code={$response['metadata']['transaction_code']}";

        $this->repository->paymentSuccessfully($response, 'succeed');
    }
}
