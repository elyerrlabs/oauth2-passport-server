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
            //'system' => $data['system'],
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
