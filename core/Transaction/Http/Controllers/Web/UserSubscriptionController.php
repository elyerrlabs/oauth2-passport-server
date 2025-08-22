<?php

namespace Core\Transaction\Http\Controllers\Web;

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

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Transaction\Http\Requests\UserRenewRequest;
use Core\Transaction\Http\Requests\UserStoreRequest;
use Core\Transaction\Repositories\PackageRepository;
use Core\Transaction\Repositories\TransactionRepository;

class UserSubscriptionController extends WebController
{

    /**
     * Transaction repository
     * @var 
     */
    public $transactionRepository;

    /**
     * Package repository
     * @var 
     */
    public $packageRepository;

    /**
     * 
     * @param \Core\Transaction\Repositories\TransactionRepository $transactionRepository
     * @param \Core\Transaction\Repositories\PackageRepository $packageRepository
     */
    public function __construct(TransactionRepository $transactionRepository, PackageRepository $packageRepository)
    {
        parent::__construct();
        $this->transactionRepository = $transactionRepository;
        $this->packageRepository = $packageRepository;
    }

    /**
     * Show the package for the user
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->packageRepository->searchForUser($request);
        }

        return Inertia::render("Core/Transaction/Web/Subscription/Index", [
            'route' => route('transaction.subscriptions.index')
        ]);
    }

    /**
     * Show package detail
     * @param \Illuminate\Http\Request $request
     * @param string $transaction_code
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Package>>|\Core\Transaction\Model\Package|\Core\Transaction\Repositories\TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Inertia\Response|null
     */
    public function show(Request $request, string $transaction_code)
    {
        if ($request->wantsJson()) {

            return $this->packageRepository->findByTransactionCode($transaction_code);
        }

        return Inertia::render('Core/Transaction/Web/Subscription/Detail', [
            'routes' => [
                'plans' => route('transaction.plans.index'),
                'billing_period' => route('api.transaction.payments.billing-period'),
                'currencies' => route('api.transaction.payments.currencies'),
                'methods' => route('api.transaction.payments.methods'),
                'services' => route('api.transaction.services.services'),
                'subscription' => route('transaction.subscriptions.pay'),
                'renew_package' => route('transaction.subscriptions.renew')
            ],
        ]);
    }

    /**
     * Create new subscription for the user
     * @param UserStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function subscription(UserStoreRequest $request)
    {
        return $this->transactionRepository->subscription($request->toArray());
    }

    /**
     * Cancel transaction
     * @param string $transaction_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function cancel(string $transaction_id)
    {
        return $this->transactionRepository->cancel($transaction_id);
    }

    /**
     * Renew the package
     * @param  UserRenewRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renew(UserRenewRequest $request)
    {
        return $this->transactionRepository->renewByUser($request);
    }

    /**
     * Show the transaction view
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function success(Request $request)
    {
        $data = $this->transactionRepository->retrieveTransactionForUser($request->code);

        return view('payment.success', ['transaction' => $data]);
    }

    /**
     * Enable or disable recurring payment
     * @param string $package_id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function recurringPayment(string $package_id)
    {
        return $this->packageRepository->recurringPaymentEnableOrDisable($package_id);
    }
}
