<?php

namespace Core\Transaction\Services\Payment\Drivers;

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

use Core\Transaction\Services\TransactionService;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Support\Str;
use Core\Transaction\Model\PaymentProvider;
use Core\Transaction\Model\User;
use Illuminate\Support\Fluent;
use Core\Transaction\Model\Transaction;
use Core\Transaction\Services\Payment\Contracts\PaymentMethod;

class OfflineSubscription implements PaymentMethod
{
    /**
     * Provider name
     * @var string
     */
    protected $method = "offline";

    /**
     * Transaction service
     * @var TransactionService
     */
    private $transactionService;


    public function __construct()
    {
        $this->transactionService = app(TransactionService::class);
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
            'id' => $this->transactionService->generateSessionId(),
            'customer' => $provider->customer_id,
            'currency' => $result['currency'],
            'amount_total' => $result['total'],
            'payment_intent' => $this->transactionService->generateIntent(),
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

        $this->transactionService->HandledSuccessfullyPayment($response, 'succeed');
    }

    /**
     * Create customer id
     * @param array $data
     * @return PaymentProvider|TModel|TValue|\Illuminate\Database\Eloquent\Model
     */
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

    /**
     * Refund transaction
     * @param array $transaction
     * @return array
     */
    public function refund(array $transaction)
    {
        $refund = [
            'payment_method' => $transaction['payment_method_id'],
            'payment_intent' => $transaction['payment_intent_id'],
            'amount' => $transaction['refund']['amount'],
            'currency' => $transaction['refund']['currency'],
            'receipt_url' => '',
            'metadata' => [
                'transaction_code' => $this->transactionService->generateTransactionCode(),
                'user_id' => $transaction['user']['id'],
                'method' => config('billing.methods.offline.key'),
                'type' => 'refund',
                'refund_id' => $transaction['refund']['id'],
            ],
        ];

        return $refund;
    }
}