<?php

namespace Core\Transaction\Http\Controllers\Web;

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

use Core\Transaction\Services\PackageService;
use Core\Transaction\Services\TransactionService;
use Core\Transaction\Transformer\User\UserPackageTransformer;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\Transaction\Http\Requests\UserRenewRequest;
use Core\Transaction\Http\Requests\UserStoreRequest;

class UserSubscriptionController extends WebController
{
    /**
     * Transaction repository
     * @var TransactionService
     */
    private $transactionService;

    /**
     * Package repository
     * @var PackageService
     */
    private $packageService;


    public function __construct()
    {
        parent::__construct();
        $this->transactionService = app(TransactionService::class);
        $this->packageService = app(PackageService::class);
    }

    /**
     * Show the package for the user
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('per_page') ? $request->per_page : 15;

        $data = $this->packageService->searchForUser($request)->paginate($page);

        return Inertia::render(
            "Web/Subscription/Index",
            [
                'data' => fractal($data, UserPackageTransformer::class)->toArray() ?? [],
                'route' => route('transaction.subscriptions.index')
            ]
        );
    }

    /**
     * Show package detail
     * @param \Illuminate\Http\Request $request
     * @param string $transaction_code
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Package>>|\Core\Transaction\Model\Package|\Core\Transaction\Repositories\TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Inertia\Response|null
     */
    public function show(Request $request, string $transaction_code)
    {
        $data = $this->packageService->findByTransactionCode($transaction_code);

        return Inertia::render('Web/Subscription/Detail', [
            'data' => fractal($data, UserPackageTransformer::class)->toArray()['data'] ?? [],
            'routes' => [
                'plans' => route('transaction.plans.index'),
                'billing_period' => route('api.transaction.payments.billing-period'),
                'currencies' => route('api.transaction.payments.currencies'),
                'methods' => route('api.transaction.payments.methods'),
                'services' => route('api.transaction.services.list'),
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
        $request->merge([
            'user_id' => auth()->user()->id,
        ]);

        return $this->transactionService->subscription($request->toArray());
    }

    /**
     * Cancel transaction
     * @param string $transaction_id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function cancel(string $transaction_id)
    {
        // $this->transactionService->cancelPayment($transaction_id);

        //return $this->message(__("Transaction has been cancelled"));
        return $this->message(__("This function is currently disabled for maintenance."));
    }

    /**
     * Renew the package
     * @param  UserRenewRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function renew(UserRenewRequest $request)
    {
        $request->merge([
            'user_id' => auth()->user()->id,
        ]);

        return $this->transactionService->renewByUser($request);
    }

    /**
     * Enable or disable recurring payment
     * @param string $package_id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function recurringPayment(string $package_id)
    {
        $this->packageService->recurringPaymentEnableOrDisable($package_id);

        return $this->message(__('Recurring payment for this package has been updated successfully'), );
    }

    /**
     * Activate the transaction
     * @param \ Core\Transaction\Model\Transaction $transaction
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function activate(string $id)
    {
        $this->transactionService->activate($id);

        return $this->message("Transaction activated successfully");
    }
}
