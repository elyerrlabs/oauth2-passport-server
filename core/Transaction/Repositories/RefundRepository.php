<?php

namespace Core\Transaction\Repositories;

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

use Core\Transaction\Jobs\ProcessRefundJob;
use Core\Transaction\Model\Refund;
use Illuminate\Http\Request;
use Core\Transaction\Model\Transaction;
use Elyerr\ApiResponse\Exceptions\ReportError;

class RefundRepository
{
    /**
     * Model
     * @var
     */
    private $model;

    /**
     * Construct
     * @param \Core\Transaction\Model\Refund $refund
     */
    public function __construct(Refund $refund)
    {
        $this->model = $refund;
    }

    /**
     * Search data for admins
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Refund>
     */
    public function search(Request $request)
    {
        $query = $this->model->query();

        $query->with(
            [
                'transactions',
                'appeal',
                'refund',
                'customer',
                'handledBy'
            ]
        );

        if ($request->filled('name')) {
            $query->whereHas(
                'customer',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(name) like", ["%" . strtolower($request->name) . "%"]);
                }
            );
        }

        if ($request->filled('email')) {
            $query->whereHas(
                'customer',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(email) like", ["%" . strtolower($request->email) . "%"]);
                }
            );
        }

        // Search by transaction code
        if ($request->filled('code')) {
            $query->whereHas(
                'transaction',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(code) like ?", ["%" . strtolower($request->code) . "%"]);
                }
            );
        }

        return $query;
    }

    /**
     * Search for users
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<Refund>
     */
    public function searchForUser(Request $request)
    {
        $query = $this->model->query();

        $query->where('customer_id', auth()->user()->id);

        $query->with(
            [
                'transactions',
                'appeal',
                'refund',
                'customer',
                'handledBy'
            ]
        );


        // Search by transaction code
        if ($request->filled('code')) {
            $query->whereHas(
                'transaction',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(code) like ?", ["%" . strtolower($request->code) . "%"]);
                }
            );
        }

        return $query;
    }

    /**
     * Find specific resource
     * @param string $id
     * @return Refund
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }

    /**
     * Create
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Refund
     */
    public function createForUser(array $data)
    {
        if (empty($data['transaction_code'])) {
            throw new ReportError(__('Transaction code does not exists'), 404);
        }

        $transaction = Transaction::with(['refund'])
            ->where('code', $data['transaction_code'])
            ->where('user_id', auth()->user()->id)
            ->first();

        if (empty($transaction)) {
            throw new ReportError(__('No transaction found for this refund request.'), 404);
        }

        if (!empty($transaction->refund)) {
            throw new ReportError(__('A refund has already been issued for this transaction.'), 409);
        }

        $data['currency'] = $transaction->currency;

        $data = $transaction->refund()->create($data);

        return $data;
    }

    /**
     * Update refund
     * @param string $id
     * @param array $data
     * @return Refund
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        // Stop updated
        if (in_array($model->status, ["rejected", "canceled"])) {
            throw new ReportError(__('This refund request has already been closed and cannot be modified.'), 409);
        }

        $model->fill($data);

        if ($model->isDirty('status')) {
            $model->handled_id = auth()->user()->id;
            $model->push();
        }

        if ($model->status == 'refunding') {
            ProcessRefundJob::dispatch($model->refundable->code);
        }

        return $model;
    }

    /**
     * Cancel
     * @param string $id
     * @return Refund
     */
    public function cancel(string $id)
    {
        $model = $this->find($id);
        $model->status = "rejected";
        $model->push();

        // send notification

        return $model;
    }

    /**
     * Reject
     * @param string $id
     * @return Refund
     */
    public function reject(string $id)
    {
        $model = $this->find($id);
        $model->status = "rejected";
        $model->push();

        // send notification

        return $model;
    }
}
