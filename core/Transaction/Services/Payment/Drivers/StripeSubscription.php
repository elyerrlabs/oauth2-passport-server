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
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use Core\Transaction\Model\User;
use Stripe\Refund as StripeRefund;
use Illuminate\Support\Facades\Log;
use Core\Transaction\Model\Transaction;
use Core\Transaction\Model\PaymentProvider;
use Stripe\Exception\InvalidRequestException;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Transaction\Services\Payment\Contracts\PaymentMethod;

class StripeSubscription implements PaymentMethod
{
    /**
     * Provider name
     * @var string
     */
    protected $method = "stripe_checkout";


    /**
     * Service
     * @var TransactionService
     */
    public $transactionService;

    /**
     * Billing period supported by stripe
     * @var array
     */
    protected $stripe_billing_period = [
        'daily' => ['interval' => 'day', 'interval_count' => 1],
        'weekly' => ['interval' => 'week', 'interval_count' => 1],
        'biweekly' => ['interval' => 'week', 'interval_count' => 2],
        'monthly' => ['interval' => 'month', 'interval_count' => 1],
        'quarterly' => ['interval' => 'month', 'interval_count' => 3],
        'semiannual' => ['interval' => 'month', 'interval_count' => 6],
        'annual' => ['interval' => 'year', 'interval_count' => 1],
        'biannual' => ['interval' => 'year', 'interval_count' => 2]
    ];

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $this->transactionService = app(TransactionService::class);
    }

    /**
     * Process data
     * @param array $data
     * @return mixed
     */
    public function buy(array $data)
    {
        $provider = $this->createCustomerId($data);

        //Create metadata
        $meta = [
            'mode' => 'payment',
            'customer' => $provider->customer_id,
            'expires_at' => now()->addMinutes(30)->timestamp,
            'line_items' => $data['items'],
            'payment_intent_data' => [
                'setup_future_usage' => 'off_session',
                'metadata' => [
                    'user_id' => $provider->user->id,
                    'transaction_code' => $data['transaction_code'],
                ],
            ],
            'success_url' => route('transaction.checkout.success') . "?code={$data['transaction_code']}",
            'metadata' => [
                'user_id' => $provider->user->id,
                'transaction_code' => $data['transaction_code'],
            ],
        ];

        if (isset($data['checkout_code'])) {
            $meta['payment_intent_data']['metadata']['checkout_code'] = $data['checkout_code'];
            $meta['metadata']['checkout_code'] = $data['checkout_code'];
        }

        try {
            $session = Session::create($meta);
            // set provider and user data to session response
            $session['provider'] = $provider->toArray();

            return $session;

        } catch (InvalidRequestException $th) {
            throw new ReportError($th->getMessage(), 403);
        }
    }


    /**
     * Charge recurring payment
     * @param array $package
     * @return void
     */
    public function chargeRecurringPayment(array $package): void
    {
        // Retrieve provider by user id and payment method
        $provider = PaymentProvider::where('user_id', $package['user']['id'])
            ->where('name', $package['transaction']['payment_method'])
            ->first();

        //Generate a new payment intent to renew package
        $intent = PaymentIntent::create([
            'amount' => $package['meta']['price']['amount'],
            'currency' => strtolower($package['meta']['price']['currency']),
            'customer' => $provider->customer_id,
            'payment_method' => $package['transaction']['payment_method_id'],
            'off_session' => true,
            'confirm' => true,
            'metadata' => [
                'transaction_code' => $this->transactionService->generateTransactionCode(),
                'user_id' => $package['user']['id'],
                'method' => config('billing.methods.stripe.key'),
                'renew' => true
            ],
        ]);

        //Create new transaction for this payment intent
        $this->transactionService->createRecurringPayment(
            $intent->toArray(),
            $package
        );
    }

    /**
     * Abort operation
     * @param Transaction $transaction
     * @return void
     */
    public function cancel(Transaction $transaction)
    {

    }

    /**
     * force activation
     * @param array $response
     * @return void
     */
    public function forceActivation(array $response)
    {
        switch ($response['object']) {
            case 'payment_intent':
                $session = (object) [
                    'payment_intent' => $response['id'],
                    'status' => $response['status'],
                ];
                break;

            case 'checkout.session':
                $session = Session::retrieve($response['id']);

                if ($session->payment_status != "paid") { //check the session has been paid
                    throw new ReportError("Payment not successful. Status: " . $session->payment_status, 402);
                }
                break;
            default:
                throw new ReportError("Invalid response object type: " . $response['object'], 400);
        }

        // Retrieve the payment intent of the session
        $payment_intent = PaymentIntent::retrieve($session->payment_intent);

        if ($payment_intent->status != "succeeded") {//check the status has been succeeded
            throw new ReportError("Payment not successful. Status: " . $session->status, 402);
        }

        // Retrieve the last charge succeeded
        $last_charge = Charge::retrieve($payment_intent->latest_charge);

        $this->transactionService->HandledSuccessfullyPayment($last_charge->toArray(), 'succeed');
    }

    /**
     *  Add customer id to the current user
     * @param array $data
     */
    public function createCustomerId(array $data): PaymentProvider
    {
        //Get the current user
        $user = User::find(auth()->user()->id);

        //Check if the user already has a payment provider
        $provider = PaymentProvider::with('user')
            ->where('name', config('billing.methods.stripe.key'))
            ->where('user_id', $user->id)->first();

        //If the user does not have a payment provider, create one
        //and create a new customer in Stripe
        if (empty($provider)) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);

            $provider = $user->paymentProviders()->create([
                'name' => config('billing.methods.stripe.key'),
                'customer_id' => $customer->id,
            ]);
        }

        return $provider;
    }

    /**
     * Transaction refund
     * @param array $transaction
     * @return array
     */
    public function refund(array $transaction)
    {
        //Generate a new payment intent to renew package
        $refund = StripeRefund::create([
            'payment_intent' => $transaction['payment_intent_id'],
            'amount' => $transaction['refund']['amount'],
            'metadata' => [
                'transaction_code' => $this->transactionService->generateTransactionCode(),
                'user_id' => $transaction['user']['id'],
                'method' => config('billing.methods.stripe.key'),
                'type' => 'refund',
                'refund_id' => $transaction['refund']['id'],
            ],
        ]);
        
        return $refund->toArray();
    }
}
