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

use App\Support\CacheKeys;
use Core\Transaction\Transformer\Admin\PackageTransformer;
use Core\Transaction\Transformer\User\UserPackageTransformer;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Core\Transaction\Model\Package;
use Illuminate\Support\Facades\Cache;
use Core\User\Repositories\UserRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Transaction\Repositories\PackageRepository;
use Core\Transaction\Jobs\ProcessRecurringPaymentJob;
use Core\Transaction\Services\Payment\PaymentManager;

final class PackageService
{
    /**
     * Repository
     * @var PackageRepository
     */
    private $packageRepository;

    /**
     * User repository
     * @var UserRepository
     */
    private $userRepository;

    public function __construct()
    {
        $this->packageRepository = app(PackageRepository::class);
        $this->userRepository = app(UserRepository::class);
    }

    /**
     * Search packages for user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Package>
     */
    public function searchForUser(Request $request)
    {
        // Prepare query
        $query = $this->packageRepository->query();

        $query = $query->with([
            'lastTransaction',
            'transactions',
            'user'
        ])->where(
                'user_id',
                auth()->user()->id
            )->orderByDesc('created_at');

        if ($request->filled('transaction_code')) {
            $query->where('transaction_code', $request->transaction_code);
        }

        return $query;
    }

    /**
     * Handled recurring payment
     * @return void
     */
    public function handledDispatchRecurringPayment()
    {
        // Create query
        $query = $this->packageRepository->query();

        // Only retrieve if  is_recurring is true
        $query->where('is_recurring', true);

        // Retrieve the last successfully transaction where the payment intent id is not null  and payment method is not offline
        $query->whereHas('lastTransaction', function ($query) {
            $query->whereNotNull('payment_method_id')
                ->where('payment_method', '!=', config('billing.methods.offline.key'))
                ->where('status', config('billing.status.successful.id')); //Last successful transaction
        });

        // Retrieve the data before ending
        $end = filter_var(config('billing.renew.hours_before', 10), FILTER_VALIDATE_INT);
        $query->whereBetween('end_at', [
            now(),
            now()->addHours($end)
        ])
            ->chunk(
                500, // every five hundred
                function ($packages) {
                    Log::info("package", $packages->toArray());
                    foreach ($packages as $package) {
                        ProcessRecurringPaymentJob::dispatch($package->id);
                    }
                }
            );
    }

    /**
     * Process charge for recurring payment by package
     * @param string $id
     * @return void
     */
    public function processChargeRecurringPayment(string $id)
    {
        $package = $this->packageRepository->find($id);

        $data = $package->meta(PackageTransformer::class);
        $paymentManager = new PaymentManager();

        $paymentManager->chargeRecurringPayment(
            $data['transaction']['payment_method'],
            $data
        );
    }

    /**
     * Create new package
     * @param array $data
     * @return \Core\Transaction\Model\Package
     */
    public function create(array $data)
    {
        return $this->packageRepository->create($data);
    }

    /**
     * Search specific resource by id
     * @param string $id
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Package>>|\Core\Transaction\Model\Package|\Core\Transaction\Repositories\TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->packageRepository->find($id);
    }

    /**
     * Search package by transaction code
     * @param string $transaction_code
     * @return Package|TValue|null
     */
    public function findByTransactionCode(string $transaction_code)
    {
        return $this->packageRepository->findByCodeForUser($transaction_code);
    }

    /**
     * Determine if the package renewal is still possible.
     * @param \Core\Transaction\Model\Package $model
     * @throws  ReportError
     * @return void
     */
    public function lastGracePeriodCheck(Package $model)
    {
        $grace_period_days = intval(config('billing.renew.grace_period_days', 5));

        $last_day = $model->end_at->addDays($grace_period_days);

        if (now() > $last_day) {
            throw new ReportError(__("Renewal Failed: The request cannot be processed because the renewal date has already passed. Please contact support for further assistance."), 400);
        }
    }

    /**
     * Determine the expiration date of the package
     * @param \Core\Transaction\Model\Package $package
     */
    public function getEndDate(Package $package)
    {
        // Retrieve plan metadata
        $meta = $package->meta;

        // Set the initial end date to the existing value or use the current date/time if not set
        $end_date = $package->end_at ?? now();

        // If this is the initial purchase (no end_at set)
        if (empty($package->end_at)) {

            // If a trial is enabled and has a duration, add it to the end date
            if ($meta['trial_enabled'] && $meta['trial_duration']) {
                $end_date->addDays($meta['trial_duration']);
            }

            // If a bonus is enabled and has a positive duration, add it to the end date
            if ($meta['bonus_enabled'] && $meta['bonus_duration'] > 0) {
                $end_date->addDays($meta['bonus_duration']);
            }
        }

        // If the subscription is being renewed (end_at is set)
        if (!empty($package->end_at)) {

            // Calculate the last valid day for renewal (grace period)
            $last_day = $package->end_at->addDays(intval('billing.renew.grace_period_days'));

            // If we're within the renewal grace period and bonuses are enabled
            if (
                $last_day > now() && // still within the renewal window
                config("billing.renew.bonus_enabled") && // bonus on renewals is enabled globally
                $meta['bonus_enabled'] && // bonus is enabled in the plan
                $meta['bonus_duration'] > 0 // bonus duration is a positive number
            ) {
                $end_date->addDays($meta['bonus_duration']);
            }
        }

        // Finally, add the billing period duration to the end date
        $period = config('billing.period.' . $meta['price']['billing_period']);
        $unit = $period['unit']; // e.g., 'days', 'months'
        $interval = $period['interval']; // e.g., 1, 3, 6

        return $end_date->{"add" . ucfirst($unit)}($interval);
    }

    /**
     * Add payments scopes to the user
     * @return void
     */
    public function addOrUpdatedScopeSubscription(Package $package)
    {
        $user = $this->userRepository->find($package->user_id);

        $scopes = $package->meta['scopes'];

        foreach ($scopes as $key => $value) {
            $user->userScopes()->updateOrCreate(
                [
                    'scope_id' => $value['id'],
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                ],
                [
                    'scope_id' => $value['id'],
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'end_date' => $package->end_at,
                ]
            );
        }

        Cache::forget(CacheKeys::userScopes($user->id));
        // Cache::forget(CacheKeys::userScopesApiKey($user->id));
        Cache::forget(CacheKeys::userAdmin($user->id));
        Cache::forget(CacheKeys::userScopeList($user->id));
        Cache::forget(CacheKeys::userAuth($user->id));
        Cache::forget(CacheKeys::userGroups($user->id));
    }

    /**
     * Set payment successfully for the package
     * @param \Core\Transaction\Model\Package $package
     * @return void
     */
    public function paymentSuccessfully(Package $package)
    {
        $package->start_at = now();
        $package->end_at = $this->getEndDate($package);
        $package->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription($package);
    }

    /**
     * Set the renewal successfully for the package
     * @param \Core\Transaction\Model\Package $package
     * @param string $transaction_code
     * @return void
     */
    public function renewSuccessfully(Package $package, string $transaction_code)
    {
        $package->end_at = $this->getEndDate($package);
        $package->transaction_code = $transaction_code;
        $package->push();

        //add payments scopes
        $this->addOrUpdatedScopeSubscription($package);
    }


    /**
     * Enable or disable recurring payment by package
     * @param string $package_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Package|null
     */
    public function recurringPaymentEnableOrDisable(string $package_id)
    {
        $package = $this->packageRepository->findForUser($package_id);

        if (empty($package)) {
            throw new ReportError(__('Resource cannot be found'), 404);
        }

        $package->is_recurring = !$package->is_recurring;

        $package->push();

        return $package;
    }
}
