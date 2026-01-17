<?php

namespace Core\Transaction\Services\Payment\Webhook;

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

use Core\Transaction\Jobs\ExpiresPaymentJob;
use Core\Transaction\Jobs\FailedPaymentJob;
use Core\Transaction\Jobs\SuccessfullyPaymentJob;
use Core\Transaction\Jobs\SuccessfullyRefundJob;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Stripe\Exception\SignatureVerificationException;
use Core\Transaction\Repositories\TransactionRepository;

class StripeWebhookController extends Controller
{
    /**
     * Repository
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->repository = $transactionRepository;
    }

    /**
     * Listen events form stripe provider
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        Stripe::setApiKey(config('services.stripe.secret'));

        try {

            $event = Webhook::constructEvent($payload, $sigHeader, $secret);

        } catch (\UnexpectedValueException $e) {
            Log::error($e);
            http_response_code(400);
            exit();
        } catch (SignatureVerificationException $e) {
            Log::error($e);
            http_response_code(400);
            exit();
        }

        // Recovery metadata
        $metadata = $event->data->object->toArray();

        // Handle the event
        switch ($event->type) {

            case 'checkout.session.completed':
                SuccessfullyPaymentJob::dispatch($metadata);
                break;

            case 'payment_intent.payment_failed':
                $sessions = Session::all([
                    'limit' => 1,
                    'payment_intent' => $metadata['id'],
                ]);
                $metadata['session'] = $sessions->data[0]->toArray();
                Log::info("payment_intent.payment_failed : ", $metadata);
                FailedPaymentJob::dispatch($metadata);
                break;

            case "checkout.session.expired":
                Log::info("checkout.session.expired : ", $metadata);
                ExpiresPaymentJob::dispatch($metadata);
                break;

            case "charge.succeeded":
                SuccessfullyPaymentJob::dispatch($metadata, 'succeed');
                break;

            case "charge.refund.updated": 
                SuccessfullyRefundJob::dispatch($metadata);
                break;

            default:
                Log::info("Listen unknown event : ", $event->toArray());
        }

        return response()->json(['status' => 'success']);
    }
}
