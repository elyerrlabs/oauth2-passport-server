<?php

namespace Core\User\Services;

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

use App\Support\CacheKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Core\User\Repositories\ScopeRepository;
use Core\User\Repositories\ServiceRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Database\UniqueConstraintViolationException;

class ServiceService
{
    /**
     * Service
     * @var ServiceRepository
     */
    private $serviceRepository;

    /**
     * Scope repository
     * @var ScopeService
     */
    private $scopeService;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->serviceRepository = app(ServiceRepository::class);
        $this->scopeService = app(ScopeService::class);
    }

    /**
     * Search
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\Service>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->serviceRepository->query();

        if ($request->disabled_request) {
            return $query;
        }

        if ($request->filled('group')) {
            $query->whereHas(
                'group',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) LIKE ?",
                        ['%' . strtolower($request->group) . '%']
                    );
                }
            );
        }

        if ($request->filled('name')) {
            $query->whereRaw(
                "LOWER(name) like ?",
                ["%" . strtolower($request->name) . "%"]
            );
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            $query->whereRaw(
                'LOWER(visibility) like ?',
                ["%" . $request->visibility . "%"]
            );
        }


        $query->orderByDesc('updated_at');

        return $query;
    }

    /**
     * Search for user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\Service>
     */
    public function searchForGuest(Request $request)
    {
        // Prepare query
        $query = $this->serviceRepository->query();

        $query->where('visibility', 'public');

        if ($request->filled('group')) {
            $query->whereHas(
                'group',
                function ($query) use ($request) {
                    $query->whereRaw(
                        "LOWER(name) LIKE ?",
                        ['%' . strtolower($request->group) . '%']
                    );
                }
            );
        }

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return \Core\User\Model\Service|\Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        try {
            return $this->serviceRepository->create([
                'name' => $data['name'],
                'slug' => $data['name'],
                'description' => $data['description'],
                'group_id' => $data['group_id'],
                'system' => $data['system'] ?? false,
                'visibility' => $data['visibility']
            ]);

        } catch (UniqueConstraintViolationException $th) {
            throw new ReportError(__("This service cannot be registered, as it is already associated with this group."), 400);
        }
    }

    /**
     * Update service
     * @param string $id
     * @param array $data
     * @return \Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        return $this->serviceRepository->update($id, [
            'description' => $data['description'],
            'name' => $data['name'],
            'visibility' => $data['visibility']
        ]);
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return \Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function delete(string $id)
    {
        $model = $this->serviceRepository->find($id);

        throw_if(
            $model->system,
            new ReportError(__("This action cannot be completed because this service is a system service and cannot be deleted."), 403)
        );

        throw_if(
            count($model->scopes) > 0,
            new ReportError(__("This action can't be done"), 400)
        );

        $model->delete();

        return $model;
    }

    /**
     * Search scope for service
     * @param string $service_id
     * @return array
     */
    public function searchScopes(string $service_id)
    {
        $model = $this->serviceRepository->find($service_id);

        return $model->scopes;
    }

    /**
     * Assign or update scopes for service
     * @param string $service_id
     * @param array $data
     * @return TModel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function assignOrUpdateScopes(string $service_id, array $data)
    {
        $model = $this->serviceRepository->find($service_id)
            ->scopes()->updateOrCreate(
                [
                    'role_id' => $data['role_id'],
                ],
                [
                    'role_id' => $data['role_id'],
                    'public' => $data['public'] ?? false,
                    'active' => $data['active'] ?? false,
                    'api_key' => $data['api_key'] ?? false,
                    'web' => $data['web'] ?? false,
                ]
            );

        Cache::forget(CacheKeys::passportScopes());
        return $model;
    }

    /**
     * Revoke scope for service
     * @param string $service_id
     * @param string $scope_id
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     * @return TModel|TValue|null
     */
    public function revokeScope(string $service_id, string $scope_id)
    {
        $scope = $this->scopeService->searchScopeByService($scope_id, $service_id);

        if (empty($scope)) {
            throw new ReportError(__("This action is invalid"), 400);
        }

        try {
            $scope->delete();
        } catch (\Throwable $th) {
            throw new ReportError(__("This resource cannot be deleted because it is being used by another resource."), 400);
        }

        Cache::forget(CacheKeys::passportScopes());

        return $scope;
    }
}
