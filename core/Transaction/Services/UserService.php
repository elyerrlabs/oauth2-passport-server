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

use Illuminate\Http\Request;
use Core\Transaction\Repositories\UserRepository;

class UserService
{
    /**
     * User Repository
     * @var UserRepository
     */
    private $userRepository;


    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
    }

    /**
     * Search user for transaction
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\User>
     */
    public function listUsersForRefundAssignment(Request $request)
    {
        $query = $this->userRepository->query();

        $query->orderByDesc('created_at');

        // Only user has refund service
        $query->whereHas(
            'userScopes.scope.service',
            function ($query) {
                $query->where("name", "refunds");
            }
        );

        if ($request->filled('name')) {
            $query->whereRaw(
                "LOWER(name) LIKE ?",
                ["%" . strtolower($request->email) . "%"]
            );
        }

        if ($request->filled('email')) {
            $query->whereRaw(
                "LOWER(email) LIKE ?",
                ["%" . strtolower($request->email) . "%"]
            );
        }

        return $query;
    }

}
