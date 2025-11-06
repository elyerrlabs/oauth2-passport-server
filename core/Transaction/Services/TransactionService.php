<?php

namespace Core\Transaction\Services;

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
use Core\Transaction\Notifications\ProcessRefundNotification;
use App\Notifications\Subscription\PaymentFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Core\Ecommerce\Model\Order;
use Core\Ecommerce\Model\Variant;
use Core\Transaction\Model\Refund;
use Illuminate\Support\Facades\DB;
use Core\Transaction\Model\Checkout;
use Core\User\Repositories\UserRepository;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Partner\Repositories\PartnerRepository;
use Core\Transaction\Repositories\PlanRepository;
use App\Notifications\Checkout\CheckoutNotification;
use Core\Transaction\Repositories\PackageRepository;
use App\Notifications\Subscription\RenewSuccessfully;
use Core\Transaction\Services\Payment\PaymentManager;
use App\Notifications\Subscription\PaymentSuccessfully;
use Core\Transaction\Repositories\TransactionRepository;
use Core\Transaction\Notifications\SuccessfullyRefundNotification;

class TransactionService
{
    use JsonResponser;

    /**
     * Transaction code
     * @var string
     */
    public $transaction_code;

    /**
     * Repository
     * @var TransactionRepository
     */
    private $repository;

    /**
     * Payment manager
     * @var PaymentManager
     */
    private $paymentManager;

    /**
     * User repository
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Package repository
     * @var PackageRepository
     */
    private $packageService;

    /**
     * Plan repository
     * @var PlanRepository
     */
    private $planRepository;

    /**
     * Partner repository
     * @var
     */
    private $partnerRepository;

    /**
     * Construct
     * @param string $transaction_code
     */
    public function __construct(string $transaction_code = null)
    {
        $this->transaction_code = $transaction_code;
        $this->repository = app(TransactionRepository::class);
        $this->paymentManager = app(PaymentManager::class);
        $this->userRepository = app(UserRepository::class);
        $this->packageService = app(PackageService::class);
        $this->planRepository = app(PlanRepository::class);
        $this->partnerRepository = app(PartnerRepository::class);
    }

    /**
     * Handled transaction refund
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return void
     */
    public function handledRefund()
    {
        // find the transaction
        $transaction = $this->repository->findByCode($this->transaction_code);

        if (empty($transaction)) {
            throw new ReportError(__('The original transaction could not be found, so the refund cannot be generated.'), 404);
        }

        // Generate payment refund
        $payment_refund = $this->paymentManager->refund(
            $transaction->payment_method,
            $transaction->toArray()
        );

        // Generate new transaction
        $this->generateTransactionRefund($payment_refund);
    }

    /**
     * Handled payment
     * @return void
     */
    public function HandledSuccessfullyPayment(array $meta, string $mode)
    {
        // Search transaction
        $transaction = $this->repository->findByCode($meta['metadata']['transaction_code']);

        //Search customer
        $customer = $this->userRepository->find($meta['metadata']['user_id']);

        //Search auth user
        $auth_user = auth()->user();

        //Page to redirect after payment

        switch ($mode) {
            case 'session':
                $transaction->payment_intent_id = $meta['payment_intent'];
                $transaction->session_id = $meta['id'];
                $transaction->user_id = $auth_user ? $auth_user->id : null;
                $transaction->push();

                break;

            case "succeed":
                $transaction->payment_method_id = $meta['payment_method'];
                $transaction->payment_intent_id = $meta['payment_intent'];
                $transaction->response = $meta;
                $transaction->status = config('billing.status.successful.id');
                $transaction->payment_url = $meta["receipt_url"];
                $transaction->user_id = $auth_user ? $auth_user->id : null;

                if (isset($meta['metadata']['checkout_code']) && !empty($meta['metadata']['checkout_code'])) {

                    $checkout = Checkout::with('orders')->where('code', $meta['metadata']['checkout_code'])->first();

                    foreach ($checkout->orders as $item) {
                        // Retrieve the order
                        $order = Order::find($item->id);

                        // Updated stock for products variant and attributes
                        $product = Variant::find($order->meta['variant']['id']);
                        $product->setStock($product->stock - $order->quantity);
                        $product->push();

                    }
                    $redirect_to = route('transaction.checkout.success') . "?code={$meta['metadata']['transaction_code']}";
                    $customer->notify(new CheckoutNotification($redirect_to));

                } else {// Only for subscription
                    //Dispatch only renew packages
                    $redirect_to = route(
                        'transaction.subscriptions.show',
                        ['transaction_code' => $meta['metadata']['transaction_code']]
                    );

                    if ($transaction->renew) {

                        $this->packageService->RenewSuccessfully(
                            $transaction->transactionable,
                            $transaction->code
                        );

                        //dispatch notification
                        $customer->notify(new RenewSuccessfully($redirect_to));

                    } else {// Dispatch only buy packages
                        $this->packageService->paymentSuccessfully($transaction->transactionable);

                        //Dispatch notification
                        $customer->notify(new PaymentSuccessfully($redirect_to));
                    }
                }

                //Set the package metadata
                $package_meta = $transaction->transactionable->meta();
                unset($package_meta['transactions']);
                unset($package_meta['transaction']);
                unset($package_meta['user']);

                $transaction->meta = $package_meta;
                $transaction->push();
                break;
            default:
                break;
        }
    }


    /**
     * Handled successfully refund
     * @param array $metadata
     * @return void
     */
    public function handledSuccessfullyRefund(array $meta)
    {
        $transaction = $this->repository->findByCode($meta['metadata']['transaction_code']);
        $transaction->payment_method_id = $meta['payment_method'];
        $transaction->payment_intent_id = $meta['payment_intent'];
        $transaction->response = $meta;
        $transaction->payment_url = $meta["receipt_url"];
        $transaction->status = config('billing.status.successful.id');

        $transaction->push();

        $this->userRepository->find($transaction->owner_id)
            ->notify(new SuccessfullyRefundNotification($transaction->toArray()));
    }

    /**
     * Handled failed payment
     * @param array $metadata
     * @return void
     */
    public function handledFailedPayment(array $meta)
    {
        $transaction = $this->repository->findByCode($meta['metadata']['transaction_code']);

        //Search  customer
        $customer = $this->userRepository->find($meta['metadata']['user_id']);

        //Page to redirect after payment
        $redirect_to = route('transaction.subscriptions.index');

        $transaction->status = config('billing.status.failed.id');
        $transaction->session_id = $meta['session']['id'];
        $transaction->payment_intent_id = $meta['id'];
        $transaction->payment_method_id = $meta['payment_method'];
        $transaction->response = $meta;
        $transaction->payment_url = $meta['session']['url'];

        //Set the package metadata
        $package_meta = $transaction->transactionable->meta();
        unset($package_meta['transactions']);
        unset($package_meta['transaction']);
        unset($package_meta['user']);

        $transaction->meta = $package_meta;
        $transaction->push();

        $customer->notify(new PaymentFailed($redirect_to));
    }

    /**
     * Handled expires payment
     * @param array $metadata
     * @return void
     */
    public function handledExpiresPayment(array $meta)
    {
        $transaction = $this->repository->findByCode($meta['metadata']['transaction_code']);

        $transaction->status = config('billing.status.expired.id');
        $transaction->session_id = $meta['id'];
        $transaction->payment_intent_id = $meta['payment_intent'];
        $transaction->payment_url = $meta['url'];
        $transaction->response = $meta;

        $transaction->meta = $transaction->transactionable->meta();
        $transaction->push();
    }

    /**
     * Cancel payment
     * @param string $id
     * @return \Core\Transaction\Model\Transaction|null
     */
    public function cancelPayment(string $id)
    {
        $transaction = $this->repository->find($id);

        $transaction->status = config('billing.status.cancelled.id');
        //Set the package metadata
        $package_meta = $transaction->transactionable->meta();
        unset($package_meta['transactions']);
        unset($package_meta['transaction']);
        unset($package_meta['user']);

        $transaction->meta = $package_meta;
        $transaction->push();

        return $transaction;
    }


    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Transaction>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->repository->query();

        // Eager loading
        $query->with([
            'user',
            'owner',
            'transactionable',
            'partner',
            'refund'
        ]);

        $query->orderByDesc("created_at");

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);

            $query->orWhereHas('refund');
        }

        // Search by email
        if ($request->filled('email')) {
            $query->whereHas(
                'owner',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(email) like ?", ["%" . strtolower($request->email) . "%"]);
                }
            );

            $query->whereHas(
                'user',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(email) like ?", ["%" . strtolower($request->email) . "%"]);
                }
            );
        }

        // Search by name
        if ($request->filled('name')) {
            $query->whereHas(
                'owner',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(name) like ?", ["%" . strtolower($request->name) . "%"]);
                }
            );

            $query->whereHas(
                'user',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(name) like ?", ["%" . strtolower($request->email) . "%"]);
                }
            );
        }

        if ($request->filled('code')) {
            $query->whereRaw("LOWER(code) like ?", ["%" . strtolower($request->code) . "%"]);
        }

        return $query;
    }


    /**
     * List transaction for user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Transaction>
     */
    public function searchForUser(Request $request)
    {
        $query = $this->repository->query();

        $query->where('', auth()->user()->id);

        if ($request->filled('code')) {
            $query->where('code', $request->code);
        }

        return $query;
    }



    /**
     * Renew package by user
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function renewByUser(Request $request)
    {
        // Search for a package  to renew
        $current_package = $this->packageService->find($request->package);
        $this->packageService->lastGracePeriodCheck($current_package);
        $package = $current_package->toArray();

        //Generate unique transaction code
        $code = $this->generateTransactionCode();
        $package['meta']['transaction_code'] = $code;

        // New instance of Payment Manager class to use the correct driver
        $paymentManager = $this->paymentManager->buy(
            $request->payment_method,
            $package['meta'] // plan saved
        );

        // Add payment manager inside the package
        $package['payment_manager'] = $paymentManager->toArray();

        //Generate new transaction
        $current_package->transactions()->create([
            'total' => $package['payment_manager']['amount_total'],
            'currency' => $package['meta']['price']['currency'],
            'status' => config("billing.status.pending.id"),
            'payment_method' => $request->payment_method,
            'billing_period' => $package['meta']['price']['billing_period'],
            'session_id' => $package['payment_manager']['id'],
            'payment_intent_id' => $package['payment_manager']['payment_intent'],
            'payment_url' => $package['payment_manager']['url'],
            'owner_id' => $request->owner_id,
            'renew' => true,
            'code' => $code,
            'response' => $package['payment_manager'],
        ]);

        return $this->data([
            'data' => [
                "message" => "Redirecting to Stripe Checkout...",
                "redirect_to" => $paymentManager->url,
            ]
        ], 201);
    }


    /**
     * Try to activate the transaction
     * @param string $id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return void
     */
    public function activate(string $id)
    {

        $model = $this->repository->find($id);

        if ($model->status == config('billing.status.successful.id')) {
            throw new ReportError("This action is not allowed for the current transaction.", 400);
        }

        if (
            !in_array($model->status, [
                config('billing.status.pending.id'),
                config('billing.status.failed.id')
            ])
        ) {
            throw new ReportError("This action is not allowed for the current transaction.", 403);
        }

        // Retrieve the response data of the transaction
        $meta = $model->response;

        //Use Payment manager to resolve driver
        $this->paymentManager->forceActivation($model->payment_method, $meta);
    }

    /**
     *  Create a new subscription
     * @param array $data
     * @return \Elyerr\ApiResponse\Assets\Json
     */
    public function subscription(array $data)
    {
        // Generate transaction code
        $code = $this->generateTransactionCode();

        //Generate metadata
        $plan = $this->planRepository->processPlan(
            $data['plan'],
            $data['billing_period']
        );

        $plan['transaction_code'] = $code;

        // Set the items to pay
        $plan['items'] = [
            [
                'price_data' => [
                    'currency' => $plan['price']['currency'],
                    'unit_amount' => $plan['price']['amount'],
                    'product_data' => [
                        'name' => $plan['name'],
                    ],
                ],
                'quantity' => 1,
            ]
        ];

        // Use payment manager to resolve driver and generate checkout session to pay
        $paymentManager = $this->paymentManager->buy(
            $data['payment_method'],
            $plan
        );

        //
        DB::transaction(function () use ($plan, $data, $paymentManager) {

            $provider = $paymentManager->provider;

            //Register package
            $package = $this->packageService->create([
                'status' => config("billing.status.pending.id"),
                'is_recurring' => config('billing.period.one_time.id') != $plan['price']['billing_period'],
                'transaction_code' => $plan['transaction_code'],
                'user_id' => $provider->user_id,
                'meta' => $plan, // add plan to the metadata
            ]);

            //Generate transaction
            $transaction = [
                'total' => $paymentManager->amount_total,
                'currency' => $plan['price']['currency'],
                'status' => config("billing.status.pending.id"),
                'payment_method' => $data['payment_method'],
                'billing_period' => $plan['price']['billing_period'],
                'renew' => false,
                'code' => $plan['transaction_code'],
                'owner_id' => $data['owner_id'],
                'response' => $paymentManager->toArray(),// save payment manager response
            ];

            /**
             * Associate a partner to the user's transaction if applicable
             */

            // Check if the authenticated user already has an assigned partner
            if (!empty($partner_id = $provider->user->partner_id)) {

                // Find the partner by ID
                $partner = $this->partnerRepository->find($partner_id);
                // If the partner exists, associate it with the transaction
                if (!empty($partner)) {
                    $transaction['partner_id'] = $partner->id;
                    $transaction['partner_commission_rate'] = $partner->commission_rate;
                }

                // If the user has no assigned partner, check for a referral code
            } elseif (!empty($data['referral_code']) && empty($provider->user->partner_id)) {

                // Find the partner by referral code
                $partner = $this->partnerRepository->findByCode($data['referral_code']);

                // If a valid partner is found, associate it with the transaction
                if (!empty($partner)) {
                    $transaction['partner_id'] = $partner->id;
                    $transaction['partner_commission_rate'] = $partner->commission_rate;
                }
            }

            $package->transactions()->create($transaction);
        });

        return $this->data(
            collect([
                'data' => [
                    "message" => "Redirecting to Stripe Checkout...",
                    "redirect_to" => $paymentManager->url,
                ]
            ]),
            201
        );
    }


    /**
     * Buy
     * @param array $data
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function buy(array $data)
    {
        // Generate transaction code
        $data['transaction_code'] = $this->generateTransactionCode();

        //Use payment manager to resolve driver
        $paymentManager = $this->paymentManager->buy(
            $data['payment_method'],
            $data
        );

        DB::transaction(function () use ($data, $paymentManager) {

            //Retrieve the checkout object and update data
            $checkout = Checkout::find($data['id']);
            $checkout->transaction_code = $data['transaction_code'];
            $checkout->push();

            //Generate transaction
            $transaction = [
                'total' => $paymentManager->amount_total,
                'currency' => $paymentManager->currency,
                'status' => config("billing.status.pending.id"),
                'payment_method' => $data['payment_method'],
                'billing_period' => $data['billing_period'],
                'renew' => false,
                'code' => $data['transaction_code'],
                'owner_id' => $data['owner_id'],
                'response' => $paymentManager->toArray(),// save payment manager response
            ];

            $checkout->transactions()->create($transaction);
        });

        return $this->data(
            collect([
                'data' => [
                    "message" => __("Redirecting to Stripe Checkout..."),
                    "redirect_to" => $paymentManager->url,
                ]
            ]),
            201
        );
    }

    /**
     * Create new transaction for stripe provider
     * @param array $paymentIntent
     * @param array $data
     * @return \Core\Transaction\Repositories\TModel
     */
    public function createRecurringPayment(array $paymentIntent, array $data)
    {
        return $this->packageService->find($data['id'])
            ->transactions()
            ->create([
                'total' => $paymentIntent['amount'],
                'currency' => $paymentIntent['currency'],
                'type' => config("billing.types.payment.id"),
                'status' => config("billing.status.pending.id"),
                'billing_period' => $data['meta']['price']['billing_period'],
                'session_id' => null,
                'payment_url' => null,
                'renew' => true,
                'response' => $paymentIntent,
                'payment_intent_id' => $paymentIntent['id'],
                'payment_method' => $paymentIntent['metadata']['method'],
                'payment_method_id' => $paymentIntent['payment_method'],
                'code' => $paymentIntent['metadata']['transaction_code'],
                'owner_id' => $paymentIntent['metadata']['user_id']
            ]);
    }


    /**
     * Generate a new transaction for refund
     * @param mixed $refund
     */
    public function generateTransactionRefund($refund)
    {
        // Find refund and create transaction
        Refund::find($refund->metadata->refund_id)
            ->transactions()->create([
                    'total' => $refund->amount,
                    'currency' => $refund->currency,
                    'type' => config("billing.types.refund.id"),
                    'status' => config("billing.status.processing.id"),
                    'billing_period' => config('billing.period.one_time.id'),
                    'session_id' => null,
                    'payment_url' => null,
                    'response' => $refund->toArray(),
                    'payment_method' => $refund->metadata->method,
                    'payment_intent_id' => $refund->payment_intent,
                    'code' => $refund->metadata->transaction_code,
                    'owner_id' => $refund->metadata->owner_id
                ]);

        // Notification to the user about the process
        $this->userRepository->find($refund->metadata->owner_id)
            ->notify(new ProcessRefundNotification($refund->metadata->transaction_code));
    }

    /**
     * Search transaction by code for the user
     * @param string $code
     * @throws \Exception
     * @return \Core\Transaction\Model\Transaction|null
     */
    public function retrieveTransactionForUser(string $code)
    {
        $transaction = $this->repository->findByCode($code);

        if (empty($transaction)) {
            throw new Exception("Page not found", 404);
        }

        return $transaction;
    }

    /**
     * Generate a new session id
     * @return string
     */
    public function generateSessionId()
    {
        return 'cs_offline_' . Str::random(45);
    }

    /**
     * Generate a intent code
     * @return string
     */
    public function generateIntent()
    {
        return 'pi_' . Str::random(45);
    }

    /**
     * Generate a transaction code
     * @return string
     */
    public function generateTransactionCode()
    {
        $micro = explode(' ', microtime());
        $timestamp = date('YmdHis', (int) $micro[1]) . substr($micro[0], 2, 3);
        return 'TXN-' . $timestamp . '-' . strtoupper(Str::random(4));
    }
}
