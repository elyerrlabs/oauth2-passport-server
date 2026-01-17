<?php

namespace Core\User\Services;

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
use Core\User\Model\Role;
use Illuminate\Http\Request;
use Core\User\Repositories\RoleRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;

class RoleService
{
    /**
     * Role repository
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * Construc
     * @param \Core\User\Repositories\RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Search data
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Role>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->roleRepository->query();

        if ($request->disabled_request) {
            return $query;
        }

        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name) like ?", ["%" . strtolower($request->name) . "%"]);
        }

        if ($request->filled('system')) {
            $query->where('system', $request->system);
        }

        $query->orderByDesc('updated_at');

        return $query;
    }

    /**
     * Create new resource
     * @param array $data
     * @return JsonResponser
     */
    public function create(array $data)
    {
        return $this->roleRepository->create([
            'name' => $data['name'],
            'slug' => $data['name'],
            'description' => $data['description'],
            'system' => $data['system'] ?? false
        ]);
    }


    /**
     * Update resource
     * @param string $id
     * @param array $data
     * @return Role|Role[]|\Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(string $id, array $data)
    {
        return $this->roleRepository->update($id, [
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }


    /**
     * Delete resource
     * @param string $id
     * @return Role
     */
    public function delete(string $id)
    {
        $model = $this->roleRepository->find($id);

        throw_if($model->system, new ReportError(
            __("This is a system role and cannot be deleted. If you believe this is an error, please contact the administrator."),
            403
        ));

        throw_if($model->scopes()->count() > 0, new ReportError(__("This action cannot be completed because this role is currently assigned to one or more scopes and cannot be deleted."), 403));

        $model->delete();

        //send event
        return $model;
    }
}
