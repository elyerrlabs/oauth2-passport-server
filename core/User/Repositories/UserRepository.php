<?php

namespace Core\User\Repositories;

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

use Core\User\Model\User;
use Core\User\Model\UserScope;

class UserRepository
{

    /**
     * User model
     * @var User
     */
    private $model;

    /**
     * User Scope model
     * @var UserScope
     */
    private $userScope;


    /**
     * Construct
     * @param \Core\User\Model\User $user
     * @param \Core\User\Model\UserScope $userScope
     */
    public function __construct(User $user, UserScope $userScope)
    {
        $this->model = $user;
        $this->userScope = $userScope;
    }

    /**
     * Query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<User>
     */
    public function query()
    {
        $query = $this->model->query();

        $query->withTrashed();

        $query->with([
            'userScopes',
            'groups'
        ]);

        return $query;
    }

    /**
     * Create resource
     * @param array $data
     * @return TModel|User|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Search user by id
     * @param string $id
     * @return TModel|TValue|null
     */
    public function find(string $id)
    {
        return $this->query()->where('id', $id)->first();
    }

    /**
     * Find user by email
     * @param string $email
     * @return TModel|TValue|null
     */
    public function findByEmail(string $email)
    {
        return $this->query()->where('email', $email)->first();
    }

    /**
     * Update by id
     * @param string $id
     * @param array $data
     * @return TModel|TValue|null
     */
    public function update(string $id, array $data)
    {
        $model = $this->find($id);

        $model->update($data);

        return $model;
    }

    /**
     * Update by email
     * @param string $email
     * @param array $data
     * @return TModel|TValue|null
     */
    public function updateByEmail(string $email, array $data)
    {
        $model = $this->find($email);

        $model->update($data);

        return $model;
    }

    /**
     * User Scope query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<UserScope>
     */
    public function scopeQuery()
    {
        $query = $this->userScope->query();

        $query->with([
            'scope.service.group',
            'scope',
            'scope.service',
            'scope.role',
            'user'
        ]);

        return $query;
    }
}
