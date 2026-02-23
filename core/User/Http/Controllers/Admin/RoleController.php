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

use Inertia\Inertia;
use Illuminate\Http\Request;
use Core\User\Services\RoleService;
use App\Http\Controllers\WebController;

class RoleController extends WebController
{
    /**
     * Construct
     * @param $roleService
     */
    public function __construct(protected RoleService $roleService)
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:role:full,administrator:role:view')->only('index');
    }

    /**
     * Show the all resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render("Admin/Role/Index", [
            'menus' => resolveInertiaRoutes(config('menus.admin_routes')),
            'api' => [
                'roles' => route('api.user.admin.roles.index'),
            ]
        ]);
    }
}
