<?php

namespace Core\User\Services;

use Illuminate\Http\Request;
use Core\User\Repositories\ScopeRepository;

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
