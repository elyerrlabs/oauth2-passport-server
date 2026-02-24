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
use App\Http\Controllers\WebController;

class UserController extends WebController
{

    /**
     * Construct
     *  
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator:user:full,administrator:user:view')->only('index');
    }

    /**
     * Show user resourcesS
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        return Inertia::render("Admin/Users/Index", [
            'menus' => resolveInertiaRoutes(config('menus.admin_routes')),
            'api' => [
                'users' => route('api.user.admin.users.index'),
                'scopes' => route('api.user.admin.scopes.index')
            ]
        ]);
    }
}
