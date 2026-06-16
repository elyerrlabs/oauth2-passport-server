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
use Core\User\Http\Requests\UserStoreRequest;
use Core\User\Http\Requests\UserUpdateRequest;
use Core\User\Services\UserService;
use Core\User\Transformer\Admin\UserTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends WebController
{

    /**
     * Construct
     *  
     */
    public function __construct(protected UserService $userService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:show')->only('show');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:create')->only('store');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:update')->only('update');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:disable')->only('disable');
        $this->middleware('userCanAny:administrator:user:full,administrator:user:enable')->only('enable');
    }

    /**
     * Show user resourcesS
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        $data = $this->userService->search($request)->paginate($request->input('per_page', 15));

        return Inertia::render("Admin/Users/Index", [
            'data' => transformCollection($data, UserTransformer::class),
            'routes' => [
                'users' => route('user.admin.users.index'),
                'create' => route('user.admin.users.create'),
            ],
            'api' => [
                'scopes' => route('api.user.admin.scopes.index')
            ]
        ]);
    }

    /**
     * Show form creation
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create', [
            'routes' => [
                'users' => route('user.admin.users.index'),
            ],
        ]);
    }

    /**
     * Store
     * @param UserStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserStoreRequest $request)
    {
        $data = $this->userService->create($request->toArray());

        return redirect()->route('user.admin.users.show', ['user' => $data->id])->with('status', __('User created successfully'));
    }

    /**
     * Show
     * @param string $id
     * @return \Inertia\Response
     */
    public function show(string $id)
    {
        $data = $this->userService->details($id);

        return Inertia::render('Admin/Users/Show', [
            'data' => transformModel($data, UserTransformer::class),
        ]);
    }

    /**
     * Create
     * @param string $id
     * @return \Inertia\Response
     */
    public function edit(string $id)
    {
        $data = $this->userService->details($id);

        return Inertia::render('Admin/Users/Create', [
            'data' => transformModel($data, UserTransformer::class),
            'routes' => [
                'users' => route('user.admin.users.index'),
            ],
        ]);
    }

    /**
     * Update
     * @param UserUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $data = $this->userService->update($id, $request->toArray());

        return redirect()->route('user.admin.users.show', ['user' => $data->id])->with('status', __('User updated successfully'));
    }

    /**
     * Disable
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disabled(string $id)
    {
        $this->userService->disable($id);

        return redirect()->route('user.admin.users.index')->with('status', __('User disabled successfully'));
    }

    /**
     * Enable
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enabled(string $id)
    {
        $this->userService->enable($id);

        return redirect()->route('user.admin.users.index')->with('status', __('User enabled successfully'));
    }
}
