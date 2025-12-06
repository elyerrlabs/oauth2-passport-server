<?php

namespace Core\Transaction\Services\Payment\Webhook;

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
