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
    public function __construct(protected TransactionService $transactionService, protected PackageService $packageService)
    {
        parent::__construct();
    }

    /**
     * Show the package for the user
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->packageService->searchForUser($request)->paginate($request->input('per_page', 10));

        return Inertia::render(
            "Web/Subscription/Index",
            [
                'data' => $this->transformCollection($data, UserPackageTransformer::class),
                'routes' => [
                    'subscriptions' => route('transaction.subscriptions.index'),
                    'plans' => route('transaction.plans.index')
                ]
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

        $payment_methods = collect(billing_methods())->where('enable', true)->all();

        return Inertia::render('Web/Subscription/Detail', [
            'data' => $this->transform($data, UserPackageTransformer::class),
            'payment_methods' => $payment_methods,
            'routes' => [
                'plans' => route('transaction.plans.index'),
                'renew' => route('transaction.subscriptions.renew')
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
            'user_id' => $request->user()?->id,
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
            'user_id' => $request->user()?->id,
        ]);

        return $this->transactionService->renewByUser($request);
    }

    /**
     * Enable or disable recurring payment
     * @param string $package_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recurringPayment(string $package_id)
    {
        $this->packageService->recurringPaymentEnableOrDisable($package_id);

        return redirect()->back()->with('status', __('Recurring payment status has been updated'));
    }
}
