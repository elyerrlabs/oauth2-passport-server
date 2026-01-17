<?php

namespace Core\Transaction\Services;

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
