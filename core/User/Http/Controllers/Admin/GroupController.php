<?php

namespace Core\User\Http\Controllers\Admin;

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


use App\Http\Controllers\WebController;
use Core\User\Http\Requests\GroupStoreRequest;
use Core\User\Services\GroupService;
use Core\User\Transformer\Admin\GroupTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController extends WebController
{
    public function __construct(protected GroupService $groupService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:group:full,administrator:group:view')->only('index');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:create')->only('store');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:update')->only('update');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:destroy')->only('destroy');
    }

    /**
     * index
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->groupService->search($request)->paginate($request->input('per_page', 15));

        return Inertia::render(
            "Admin/Groups/Index",
            [
                'data' => $this->transformCollection($data, GroupTransformer::class),
                'routes' => [
                    'groups' => route('user.admin.groups.index')
                ]
            ]
        );
    }

    /**
     * Store resource
     * @param GroupStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GroupStoreRequest $request)
    {
        $this->groupService->create($request->toArray());

        return redirect()->route('user.admin.groups.index')->with('status', __('Group registed successfully'));
    }

    /**
     * Update resource
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:200'],
        ]);

        $this->groupService->update($id, $request->toArray());

        return redirect()->route('user.admin.groups.index')->with('status', __('Group updated successfully'));
    }

    /**
     * Destroy resource
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $model = $this->groupService->delete($id);

        return redirect()->route('user.admin.groups.index')->with('status', __('Group deleted successfully'));

    }
}
