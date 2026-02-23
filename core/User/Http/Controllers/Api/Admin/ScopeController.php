<?php

namespace Core\User\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiController;
use Core\User\Services\ScopeService;
use Core\User\Transformer\Admin\ScopeTransformer;
use Illuminate\Http\Request;

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

final class ScopeController extends ApiController
{

    /**
     * construct
     * @param ScopeService $scopeService
     */
    public function __construct(protected ScopeService $scopeService)
    {
        parent::__construct();
        $this->middleware('scope:administrator:scope:full,administrator:scope:view')->only('index');
    }

    /**
     * Show the all scope for user only public and active
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\Scope>
     */
    public function index(Request $request)
    {
        $scopes = $this->scopeService->searchForUser($request)->get();

        return $this->showAll($scopes, ScopeTransformer::class, 200, false);
    }
}
