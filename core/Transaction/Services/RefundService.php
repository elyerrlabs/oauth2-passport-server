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

        // Order by last refund created
        $query->orderByDesc('created_at');

        // Filter by assigned refunds
        if ($request->filled('assigned') && !filter_var($request->assigned, FILTER_VALIDATE_BOOL)) {
            $query->doesntHave('assignedTo');
        }


        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', "=", $request->assigned_to);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->whereRaw(
                'LOWER(type) LIKE ?',
                ["%" . strtolower($request->type) . "%"]
            );
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->whereRaw(
                'LOWER(status) LIKE ?',
                ["%" . strtolower($request->status) . "%"]
            );
        }

        // Filter by owner refunds
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

        // Filter by owner email
        if ($request->filled('email')) {

            $query->whereHas(
                'assignedTo',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(email) LIKE ?",
                        ["%" . strtolower($request->email) . "%"]
                    );
                }
            );
        }

        // Filter by transaction code
        if ($request->filled('code')) {
            $query->whereHas(
                'parentTransaction',
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

        // Retrieve the original transaction by code for the current user who's make the request
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

            // Save image in the temp directory
            $images = $this->fileService->processImage($data['evidence']);

            // Set the currency for the original transaction to request refund
            $data['currency'] = $transaction->currency;

            // Create new refund for the transaction original transaction
            $refund = $transaction->refund()->create([
                'reason' => $data['reason'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'currency' => $data['currency'],
                'type' => $data['type'] ?? 'refund',
                'status' => $data['status'] ?? 'pending',
                'user_id' => $data['user_id'],
            ]);

            // Set the all evidences incoming for the new refund
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
     * 
     * @param string $id
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Core\Transaction\Model\Refund|\Core\Transaction\Repositories\TValue|null
     */
    public function updateStatus(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            // Retrieve the refund by id
            $model = $this->refundRepository->find($id);

            // Stop updated
            if (in_array($model->status, ["rejected", "canceled"])) {
                throw new ReportError(__('This refund request has already been closed and cannot be modified.'), 409);
            }

            // Update status
            $model->status = $data['status'];
            $model->push();

            // Dispatch job to refund to the user if it the refund is accepted 
            if ($model->status == 'refunding') {
                ProcessRefundJob::dispatch($model->parentTransaction->code);
            }
        });
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

    /**
     * Assign to
     * @param string $id
     * @param string $assigned_to
     * @throws ReportError
     * @return \Core\Transaction\Model\Refund|\Core\Transaction\Repositories\TValue|null
     */
    public function assignTo(string $id, string $assigned_to)
    {
        $model = $this->refundRepository->find($id);

        if (empty($model)) {
            throw new ReportError(__('This refund cannot be found.'), 404);
        }

        if (in_array($model->status, ["canceled", "completed", "refunding", "rejected"])) {
            throw new ReportError(__('This refund request cannot be modified.'), 403);
        }

        $model->assigned_to = $assigned_to;
        $model->assigned_by = auth()->user()->id;

        $model->push();

        return $model;
    }
}
