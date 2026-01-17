<?php

namespace Core\Transaction\Repositories;

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
