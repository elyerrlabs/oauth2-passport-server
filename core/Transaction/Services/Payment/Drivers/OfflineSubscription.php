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

use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Str;
use Core\Transaction\Model\PaymentProvider;
use Core\Transaction\Model\User;
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
    public function buy(array $data)
    {
        $provider = $this->createCustomerId($data);

        // Calculate total items
        $result = collect($data['items'])->reduce(function ($carry, $item) {
            $currency = $item['price_data']['currency'];
            $carry['currency'] = $currency;
            $carry['total'] += $item['price_data']['unit_amount'] * $item['quantity'];
            return $carry;
        }, ['currency' => null, 'total' => 0]);

        $meta = [
            'id' => $this->repository->generateSessionId(),
            'customer' => $provider->customer_id,
            'currency' => $result['currency'],
            'amount_total' => $result['total'],
            'payment_intent' => $this->repository->generateIntent(),
            'metadata' => [
                'user_id' => $provider->user->id,
                'transaction_code' => $data['transaction_code'],
            ],
            'payment_intent_data' => [
                "metadata" => [
                    'user_id' => $provider->user->id,
                    'transaction_code' => $data['transaction_code'],
                ],
            ],
            'url' => route('transaction.checkout.success') . "?code={$data['transaction_code']}",
        ];

        if (isset($data['checkout_code'])) {
            $meta['payment_intent_data']['metadata']['checkout_code'] = $data['checkout_code'];
            $meta['metadata']['checkout_code'] = $data['checkout_code'];
        }

        $session = new Fluent($meta);
        $session['provider'] = $provider;

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

    /**
     * Force activation
     * @param array $response
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return void
     */
    public function forceActivation(array $response)
    {
        if (
            !auth()->user()->hasAccess([
                'administrator:transactions:full',
                'administrator:transactions:update'
            ])
        ) {
            throw new ReportError(__("You don’t have permission to perform this action"), 403);
        }

        $response['payment_method'] = null;
        $response["status"] = config('billing.status.successful.name');
        $response["receipt_url"] = route('transaction.checkout.success') . "?code={$response['metadata']['transaction_code']}";

        $this->repository->paymentSuccessfully($response, 'succeed');
    }

    public function createCustomerId(array $data): PaymentProvider
    {
        $user = User::findOrFail(auth()->id());

        // Check OFFLINE provider
        $provider = PaymentProvider::where('name', config('billing.methods.offline.key'))
            ->where('user_id', $user->id)
            ->first();

        if (!$provider) {
            $customerId = $this->generateUniqueOfflineId();

            $provider = $user->paymentProviders()->create([
                'name' => config('billing.methods.offline.key'),
                'customer_id' => $customerId,
            ]);
        }

        return $provider;
    }

    /**
     * Generate a unique provider offline code
     * @return string
     */
    protected function generateUniqueOfflineId(): string
    {
        do {

            $hash = substr(
                hash('crc32b', uniqid(Str::random(10) . microtime(true), true)),
                0,
                16
            );

            $uniqueCode = 'offline_cus_' . strtolower($hash);
        } while (
            PaymentProvider::where('customer_id', $uniqueCode)->exists()
        );

        return $uniqueCode;
    }

}
