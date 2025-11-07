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

use Core\Transaction\Model\Package;
use Elyerr\ApiResponse\Exceptions\ReportError;

class PackageRepository
{
    /**
     * Model
     * @var Package
     */
    public $model;

    /**
     * Constructor
     * @param  Package $package
     */
    public function __construct(Package $package)
    {
        $this->model = $package;
    }

    /**
     * Query builder
     * @return \Illuminate\Database\Eloquent\Builder<Package>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'user',
            'transactions',
            'lastTransaction'
        ]);

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return Package
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search specific resource by id
     * @param string $id
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>>|Package|TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Find package for user
     * @param string $id
     * @return Package|null
     */
    public function findForUser(string $id)
    {
        return $this->query()
            ->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();
    }

    /**
     * Find package for user by transaction code
     * @param string $code
     * @return Package|null
     */
    public function findByCodeForUser(string $code)
    {
        return $this->query()
            ->where('user_id', auth()->user()->id)
            ->whereHas(
                'transactions',
                function ($query) use ($code) {
                    $query->Where('code', $code);
                }
            )->first();
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return array<\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>>|Package|TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Package>|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }

    /**
     * Update package for user
     * @param string $id
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return Package|null
     */
    public function updateForUser(string $id, array $data)
    {
        $model = $this->findForUser($id);

        if (empty($model)) {
            throw new ReportError(__('Resource cannot be found'), 404);
        }

        $model->update($data);

        return $model;
    }

    /**
     * Delete specific resource
     * @param string $id 
     * @return void
     */
    public function delete(string $id)
    {
        // pass
    }
}
