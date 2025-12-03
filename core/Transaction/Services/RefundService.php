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

use App\Services\FileService;
use Illuminate\Support\Facades\DB;
use Core\Transaction\Repositories\RefundRepository;
use Core\Transaction\Jobs\ProcessRefundJob;
use Core\Transaction\Repositories\TransactionRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;

class RefundService
{
    /**
     * Refund repository
     * @var RefundRepository
     */
    private $refundRepository;

    /**
     * Transaction repository
     * @var
     */
    private $transactionRepository;

    /**
     * File service
     * @var FileService
     */
    private $fileService;

    public function __construct()
    {
        $this->refundRepository = app(RefundRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
        $this->fileService = app(FileService::class);
    }

    /**
     * Storage to save image
     * @param string $id
     * @return string
     */
    public function getStorage(string $id = '')
    {
        if (empty($id)) {
            return $this->refundRepository->getStorage();
        }
        return $this->refundRepository->getStorage() . '/' . $id;
    }

    /**
     * Search data for admins
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Refund>
     */
    public function search(Request $request)
    {
        $query = $this->refundRepository->query();

        $query->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->whereRaw(
                'LOWER(type) LIKE ?',
                ["%" . strtolower($request->type) . "%"]
            );
        }

        if ($request->filled('status')) {
            $query->whereRaw(
                'LOWER(status) LIKE ?',
                ["%" . strtolower($request->status) . "%"]
            );
        }

        if ($request->filled('name')) {
            $query->whereHas(
                'user',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) LIKE ?",
                        ["%" . strtolower($request->name) . "%"]
                    );
                }
            );
        }

        if ($request->filled('email')) {
            $query->whereHas(
                'user',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(email) LIKE ?",
                        ["%" . strtolower($request->email) . "%"]
                    );
                }
            );
        }

        // Search by transaction code
        if ($request->filled('code')) {
            $query->whereHas(
                'transactions',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(code) LIKE ?",
                        ["%" . strtolower($request->code) . "%"]
                    );
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
        $query = $this->refundRepository->query();

        $query->where('user_id', auth()->user()->id);

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
     * Find refund
     * @param string $id
     * @return \Core\Transaction\Model\Refund|\Core\Transaction\Repositories\TValue|null
     */
    public function find(string $id)
    {
        $model = $this->refundRepository->find($id);

        throw_if(empty($model), new ReportError(__('Refund not be found'), 404));

        return $model;
    }

    /**
     * Create new transaction refund
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Core\Transaction\Model\Refund|\Illuminate\Database\Eloquent\Model
     */
    public function createForUser(array $data)
    {
        if (empty($data['transaction_code'])) {
            throw new ReportError(__('Transaction code does not exists'), 404);
        }

        $transaction = $this->transactionRepository->findByCodeForUser(
            $data['transaction_code'],
            auth()->user()->id
        );

        if (empty($transaction)) {
            throw new ReportError(__('No transaction found for this refund request.'), 404);
        }

        if (!empty($transaction->refund)) {
            throw new ReportError(__('A refund has already been issued for this transaction.'), 409);
        }

        return DB::transaction(function () use ($data, $transaction) {

            $images = $this->fileService->processImage($data['evidence']);

            $data['currency'] = $transaction->currency;

            $refund = $transaction->refund()->create([
                'reason' => $data['reason'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'type' => $data['type'] ?? 'refund',
                'status' => $data['status'] ?? 'pending',
                'user_id' => $data['user_id'],
            ]);

            $this->fileService->saveImage(
                $refund,
                $images,
                $this->getStorage($refund->id),
                'local'
            );

            return $refund;
        });

    }

    /**
     * Update refund
     * @param string $id
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Core\Transaction\Model\Refund|\Core\Transaction\Repositories\TValue|null
     */
    public function updateStatus(string $id, array $data)
    {
        $model = $this->refundRepository->find($id);

        // Stop updated
        if (in_array($model->status, ["rejected", "canceled"])) {
            throw new ReportError(__('This refund request has already been closed and cannot be modified.'), 409);
        }

        $model->fill($data);

        $model->handled_id = auth()->user()->id;
        $model->push();

        // Dispatch job to refund to the user
        if ($model->status == 'refunding') {
            ProcessRefundJob::dispatch($model->refundable->code);
        }

        return $model;
    }

    /**
     * Cancel operation
     * @param string $id
     * @return \Core\Transaction\Model\Refund|\Core\Transaction\Repositories\TValue|null
     */
    public function cancel(string $id)
    {
        $model = $this->refundRepository->find($id);

        if (in_array($model->status, ["canceled", "completed", "refunding", "rejected"])) {
            throw new ReportError(__('This refund request cannot be modified.'), 403);
        }

        $model->status = "canceled";
        $model->push();

        // send notification

        return $model;
    }

    /**
     * Reject refund process
     * @param string $id
     */
    public function reject(string $id)
    {
        $model = $this->refundRepository->find($id);

        if ($model->status == "rejected") {
            throw new ReportError(__('This refund request has already been closed and cannot be modified.'), 409);
        }

        $model->status = "rejected";
        $model->push();

        // send notification

        return $model;
    }
}
