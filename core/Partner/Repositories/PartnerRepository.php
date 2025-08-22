<?php
namespace Core\Partner\Repositories;

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

use Illuminate\Support\Str;
use Core\Partner\Model\User;
use Illuminate\Http\Request;
use Core\Partner\Model\Partner;
use Core\Transaction\Model\Transaction;
use App\Repositories\Contracts\Contracts;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Core\Partner\Transformer\PartnerTransformer;

class PartnerRepository implements Contracts
{
    use JsonResponser;

    /**
     * Transformer
     * @var 
     */
    public $transformer = PartnerTransformer::class;

    /**
     * Model
     * @var 
     */
    public $model;

    /**
     * Transaction model
     * @var Transaction
     */
    public $transaction;

    /**
     * Constructor
     * @param Core\Partner\Model\Partner $partner
     */
    public function __construct(Partner $partner, Transaction $transaction)
    {
        $this->model = $partner;
        $this->transaction = $transaction;
    }

    /**
     * Search resources
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function search(Request $request)
    {
        $params = $this->filter_transform($this->transaction->transformer);

        $data = $this->transaction->query();

        $data = $this->transaction->whereHas(
            'partner',
            function ($query) {
                $query->where('code', auth()->user()->partner->code ?? null);
            }
        );

        $data = $this->searchByBuilder($data, $params);

        $data = $this->orderByBuilder($data, $this->transaction->transformer);

        return $this->showAllByBuilder($data, $this->transaction->transformer);
    }

    /**
     * List partner
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<User>
     */
    public function listPartners(Request $request)
    {
        $query = User::query();

        $query->whereHas('partner');

        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($request->name) . '%']);
        }

        if ($request->filled('last_name')) {
            $query->whereRaw("LOWER(last_name) LIKE ?", ['%' . strtolower($request->last_name) . '%']);
        }

        if ($request->filled('email')) {
            $query->whereRaw("LOWER(email) LIKE ?", ['%' . strtolower($request->email) . '%']);
        }

        if ($request->filled('country')) {
            $query->whereRaw("LOWER(country) LIKE ?", ['%' . strtolower($request->country) . '%']);
        }

        if ($request->filled('code')) {
            $query->whereHas('partner', function ($query) use ($request) {
                $query->whereRaw("LOWER(code) LIKE ?", [
                    '%' . strtolower($request->code) . '%'
                ]);
            });
        }

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return void
     */
    public function create(array $data)
    {

    }

    /**
     * Search specific resource
     * @param string $id
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Show partner detail
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function details(string $id)
    {
        $partner = $this->find($id)->partner ?? null;
        return !empty($partner) ? $this->showOne($partner, PartnerTransformer::class) : [];
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data)
    {
        /**
         * Update the commission rate
         */

    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id)
    {

    }

    /**
     * Search partner by code
     * @param string $code
     */
    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }


    /**
     * Generate code
     * @param mixed $prefix
     * @param mixed $length
     * @return string
     */
    public function generateReferralCode($length = 32)
    {
        $prefix = strtoupper(substr(auth()->user()->name, 0, 2));
        $random = strtoupper(Str::random($length));
        return $prefix . "_" . $random;
    }

    /**
     * Update commission rate
     * @param string $id
     * @param mixed $percentage
     */
    public function updateCommissionRate(string $user_id, $percentage)
    {
        $partner = User::find($user_id)->partner;
        $partner->commission_rate = $percentage;
        $partner->push();

        return $partner;
    }

    /**
     * Generate the partner link
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function generateLink()
    {
        $partner = User::find(auth()->user()->id)->partner;

        if (empty($partner)) {
            $partner = $this->model->create([
                'code' => $this->generateReferralCode(),
                'user_id' => auth()->user()->id
            ]);
        }

        $partner["links"] = $partner->referLinks();

        return $this->showOne($partner, PartnerTransformer::class, 201);
    }
}
