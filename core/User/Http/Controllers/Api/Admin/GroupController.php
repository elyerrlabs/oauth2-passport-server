<?php

namespace Core\User\Http\Controllers\Api\Admin;

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

use App\Http\Controllers\ApiController;
use Core\User\Http\Requests\GroupStoreRequest;
use Core\User\Services\GroupService;
use Core\User\Transformer\Admin\GroupTransformer;
use Illuminate\Http\Request;

final class GroupController extends ApiController
{
    /**
     * Construct
     * @param  GroupService $groupService
     */
    public function __construct(protected GroupService $groupService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:group:full,administrator:group:view')->only('index');
        $this->middleware('scope:administrator:group:full,administrator:group:create')->only('store');
        $this->middleware('scope:administrator:group:full,administrator:group:update')->only('update');
        $this->middleware('scope:administrator:group:full,administrator:group:destroy')->only('destroy');
    }

    /**
     * Index
     * @param Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->groupService->search($request);

        return $this->showAllByBuilder($data, GroupTransformer::class);
    }

    /**
     * Store new resource
     * @param \Core\User\Http\Requests\GroupStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(GroupStoreRequest $request)
    {
        $model = $this->groupService->create($request->toArray());

        return $this->showOne($model, GroupTransformer::class, 201);
    }

    /**
     * Update resource
     * @param \Illuminate\Http\Request $request
     * @param \Core\User\Model\Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:200'],
        ]);

        $model =  $this->groupService->update($id, $request->toArray());

        return $this->showOne($model, GroupTransformer::class);
    }

    /**
     * Destroy resources
     * @param string $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $model = $this->groupService->delete($id);

        return $this->showOne($model, GroupTransformer::class);
    }
}
