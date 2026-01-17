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

use Core\User\Model\Group;
use Illuminate\Http\Request;
use Core\User\Repositories\GroupRepository;
use Elyerr\ApiResponse\Exceptions\ReportError;

class GroupService
{
    /**
     * Group repository
     * @var GroupRepository
     */
    private $groupRepository;


    public function __construct()
    {
        $this->groupRepository = app(GroupRepository::class);
    }

    /**
     * Search resource
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<Group>
     */
    public function search(Request $request)
    {
        // Prepare query
        $query = $this->groupRepository->query();

        if ($request->disabled_request) {
            return $query;
        }

        if ($request->filled('name')) {
            $query->whereRaw("LOWER(name) like ?", ["%" . strtolower($request->name) . "%"]);
        }

        if ($request->filled('system')) {
            $query->where('system', $request->system);
        }

        return $query;
    }

    /**
     * Create group
     * @param array $data
     */
    public function create(array $data)
    {
        return $this->groupRepository->create([
            'name' => $data['name'],
            'slug' => $data['name'],
            'description' => $data['description'],
            'system' => $data['system'] ?? false,
        ]);
    }

    /**
     * Show detail
     * @param string $id
     * @return \Core\User\Model\Group|\Core\User\Model\Group[]|\Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function detail(string $id)
    {
        return $this->groupRepository->find($id);
    }

    /**
     * Find by slug
     * @param string $slug
     */
    public function findBySlug(string $slug)
    {
        return $this->groupRepository->findBySlug($slug);
    }

    /**
     * Update specific resource
     * @param string $id
     * @param array $data
     * @return 
     */
    public function update(string $id, array $data)
    {
        $model = $this->groupRepository->find($id);
        $model->description = $data["description"];
        $model->push();

        return $model;
    }

    /**
     * Delete resoruce
     * @param string $id
     * @return Group|Group[]|\Core\User\Repositories\TModel|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function delete(string $id)
    {
        $model = $this->groupRepository->find($id);

        throw_if($model->system, new ReportError(
            __("This is a system group and cannot be deleted. If you believe this is an error, please contact the administrator."),
            403
        ));

        if ($model->services()->count() === 0 && $model->users()->count()) {
            new ReportError(__("This action cannot be completed because this group is currently in use by another resource."), 403);
        }

        $model->forceDelete();

        return $model;
    }
}
