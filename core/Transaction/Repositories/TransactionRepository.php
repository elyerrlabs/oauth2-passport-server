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

use Core\Transaction\Model\Transaction;

class TransactionRepository
{
    /**
     * Model
     * @var Transaction
     */
    public $model;


    /**
     * Constructor
     * @param \ Core\Transaction\Model\Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    /**
     * Return the query builder 
     * @return \Illuminate\Database\Eloquent\Builder<Transaction>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'transactionable',
            'activatedBy',
            'user',
            'partner',
            'refund'
        ]);

        return $query;
    }

    /**
     * Retrieve a transaction by code
     * @param string $code
     * @return Transaction|null
     */
    public function findByCode(string $code): Transaction|null
    {
        return $this->query()->where('code', $code)->first();
    }

    /**
     * Retrieve a transaction by id
     * @param string $id
     * @return Transaction|null
     */
    public function find(string $id): Transaction|null
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Search transaction for user 
     * @param string $id
     * @param string $user_id
     * @return Transaction|TValue|null
     */
    public function findForUser(string $id, string $user_id)
    {
        return $this->query()
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->first();
    }

    /**
     * Find transaction for user by code
     * @param string $code
     * @param string $user_id
     * @return Transaction|TValue|null
     */
    public function findByCodeForUser(string $code, string $user_id)
    {
        return $this->query()
            ->where('code', $code)
            ->where('user_id', $user_id)
            ->first();
    }

    /**
     * create query for user
     * @param string $user_id
     * @return \Illuminate\Database\Eloquent\Builder<Transaction>
     */
    public function queryForUser(string $user_id)
    {
        $query = $this->query();

        $query->where('user_id', $user_id);
        $query->orderBy('created_at', 'desc');
        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return TModel
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update new resource by id
     * @param string $id
     * @param array $data
     * @return Transaction
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }

    /**
     * Update new resource by code
     * @param string $code
     * @param array $data
     * @return Transaction|null
     */
    public function updateByCode(string $code, array $data)
    {
        $model = $this->find($code);

        $model->update($data);

        return $model;
    }
}
