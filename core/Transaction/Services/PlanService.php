<?php

namespace Core\Transaction\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Elyerr\ApiResponse\Assets\Asset;
use Stevebauman\Purify\Facades\Purify;
use Core\Transaction\Repositories\PlanRepository;
use Core\Transaction\Transformer\Admin\PlanTransformer;
use Core\Transaction\Transformer\Admin\PlanPriceTransformer;

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

class PlanService
{
    /**
     * Plan repository
     * @var PlanRepository
     */
    private $planRepository;


    public function __construct()
    {
        $this->planRepository = app(PlanRepository::class);
    }


    public function search(Request $request)
    {
        //Prepare query
        $query = $this->planRepository->query();

        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name)", ["%" . strtolower($request->name) . "%"]);
        }

        if ($request->filled('active')) {
            $query->where("bonus_activated", $request->active);
        }

        if ($request->filled('bonus_activated')) {
            $query->where("bonus_activated", $request->bonus_activated);
        }

        if ($request->filled('bonus_duration')) {
            $query->where("bonus_duration", $request->bonus_activated);
        }

        if ($request->filled('trial_enabled')) {
            $query->where("trial_enabled", $request->bonus_activated);
        }

        if ($request->filled('trial_duration')) {
            $query->where("trial_duration", $request->bonus_activated);
        }

        return $query;
    }

    /**
     * Retrieve the plans for guest users
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>
     */
    public function searchPlanForGuest(Request $request)
    {
        $query = $this->planRepository->query();

        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name)", ["%" . strtolower($request->name) . "%"]);
        }

        // Search by billing period
        if ($request->filled('period')) {
            $query->whereHas(
                'prices',
                function ($query) use ($request) {
                    $query->where(
                        'billing_period',
                        $request->period
                    );
                }
            );
        }

        // Search by service like cloud , vpn , etc
        if ($request->filled('service')) {
            $query->whereHas(
                'scopes.service',
                function ($query) use ($request) {
                    $query->whereRaw('LOWER(name) LIKE ?', [
                        '%' . strtolower($request->name) . '%'
                    ]);
                }
            );
        }

        return $query;
    }

    /**
     * Create a new plan
     * @param array $data
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $plan = $this->planRepository->create([
                'name' => $data['name'],
                'slug' => normalizeSlug($data['name']),
                'description' => Purify::clean($data['description']),
                'active' => $data['active'],
                'bonus_enabled' => $data['bonus_enabled'] ?? false,
                'bonus_duration' => $data['bonus_duration'] ?? 0,
                'trial_enabled' => $data['trial_enabled'] ?? false,
                'trial_duration' => $data['trial_duration'] ?? 0
            ]);

            // Add scopes
            $plan->scopes()->attach($data['scopes']);

            // Add prices
            foreach ($data['prices'] as $key => $value) {
                $plan->prices()->create([
                    'amount' => $value['amount'],
                    'billing_period' => $value['billing_period'],
                    'currency' => $value['currency']
                ]);
            }

            return $plan;
        });
    }

    /**
     * Show details
     * @param string $id
     * @return \Core\Transaction\Model\Plan|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>[]|\Illuminate\Database\Eloquent\Collection<int, \Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function details(string $id)
    {
        return $this->planRepository->find($id);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return \Core\Transaction\Model\Plan|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>[]|\Illuminate\Database\Eloquent\Collection<int, \Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $plan = DB::transaction(function () use ($id, $data) {

            $plan = $this->planRepository->update($id, $data);

            if (!empty($data['scopes'])) {
                $plan->scopes()->syncWithoutDetaching($data['scopes']);
            }

            if (!empty($data['prices'])) {
                foreach ($data['prices'] as $key => $value) {

                    $plan->prices()->updateOrCreate(
                        [
                            'billing_period' => strtolower($value['billing_period']),
                        ],
                        [
                            'billing_period' => strtolower($value['billing_period']),
                            'amount' => $value['amount'],
                            'currency' => $value['currency']
                        ]
                    );
                }
            }

            return $plan;
        });

        return $plan;
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return \Core\Transaction\Model\Plan|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>[]|\Illuminate\Database\Eloquent\Collection<int, \Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function delete(string $id)
    {
        $plan = $this->planRepository->find($id);

        DB::transaction(function () use ($plan) {

            $plan->scopes()->detach();

            $plan->prices()->delete();

            $plan->delete();
        });

        return $plan;
    }

    /**
     * Delete price  
     * @param string $id
     * @param string $price_id
     * @return \Core\Transaction\Model\Plan|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>[]|\Illuminate\Database\Eloquent\Collection<int, \Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function deletePrice(string $id, string $price_id)
    {
        $plan = $this->planRepository->find($id);

        $plan->prices()->where('id', $price_id)->delete();

        return $plan;
    }

    /**
     * Delete scopes of the plan
     * @param string $plan_id
     * @param string $scope_id
     * @return \Core\Transaction\Model\Plan|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Plan>[]|\Illuminate\Database\Eloquent\Collection<int, \Core\Transaction\Model\Plan>|\Illuminate\Database\Eloquent\Model|null
     */
    public function deleteScope(string $plan_id, string $scope_id)
    {
        $plan = $this->planRepository->find($plan_id);

        $plan->scopes()->detach($scope_id);

        return $plan;
    }


    /**
     * Process the plan
     * @param string $id
     * @param string $billing_period
     */
    public function processPlan(string $id, string $billing_period)
    {
        // Retrieve the plan by id
        $plan = $this->planRepository->find($id);

        // Retrieve the billing period
        $price = $plan->prices()->where('billing_period', $billing_period)->first();

        // Transform price data
        $price = fractal($price, PlanPriceTransformer::class)->toArray()['data'];

        //Transform data
        $meta = fractal($plan, PlanTransformer::class)->toArray()['data'];

        unset($meta['prices']); //remove prices
        unset($meta['links']); //remove links
        foreach ($meta['scopes'] as &$key) {
            unset($key['links']); //remove links
        }
        unset($price['links']); //remove links
        unset($price['expiration']); //remove links

        //add price to renew plan
        $meta['price'] = $price;

        return $meta;
    }

}
