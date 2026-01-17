<?php

namespace Core\User\Services;

use Illuminate\Http\Request;
use Core\User\Repositories\ScopeRepository;

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

class ScopeService
{

    /**
     * Scope repository
     * @var 
     */
    private $scopeRepository;

    /**
     * Construct
     * @param \Core\User\Repositories\ScopeRepository $scopeRepository
     */
    public function __construct(ScopeRepository $scopeRepository)
    {
        $this->scopeRepository = $scopeRepository;
    }

    /**
     * Search for user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\Scope>
     */
    public function searchForUser(Request $request)
    {
        // Create query
        $query = $this->scopeRepository->query();

        $query->where('active', true);

        $query->where('public', false);

        if ($request->disabled_request) {
            return $query;
        }

        // search by role name or slug
        if ($request->has('role')) {
            $query->whereHas(
                'role',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) like ?",
                        ["%" . strtolower($request->role) . "%"]
                    );
                }
            );
        }

        // Search by service name or slug
        if ($request->has('service')) {
            $query->whereHas(
                'service',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) like  ?",
                        ["%" . strtolower($request->service) . "%"]
                    );
                }
            );
        }

        return $query;
    }


    /**
     * Search scope for service
     * @param string $scope_id
     * @param string $service_id
     * @return TModel|TValue|null
     */
    public function searchScopeByService(string $scope_id, string $service_id)
    {
        $query = $this->scopeRepository->query();

        // Search by scope id
        $query->where('id', $scope_id);

        // Search by service id
        $query->whereHas(
            'service',
            function ($query) use ($service_id) {
                $query->where('id', $service_id);
            }
        );

        return $query->first();
    }

}
