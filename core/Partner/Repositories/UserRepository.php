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

use Core\Partner\Model\User;

class UserRepository
{
    /**
     * Model
     * @var  User
     */
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<User>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->with([
            'userScopes',
            'userScopes.scope',
            'userScopes.scope.service',
            'userScopes.scope.service.group'
        ]);

        // Only reseller users
        $query->whereHas(
            'userScopes.scope.service.group',
            function ($query) {
                $query->whereRaw('LOWER(slug) = ?', ['reseller']);
            }
        );

        return $query;
    }

    /**
     * Find 
     * @param string $id
     * @return TModel|User|User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function find(string $id)
    {
        return $this->model->find($id);
    }
}
