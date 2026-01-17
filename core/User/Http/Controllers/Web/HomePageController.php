<?php

namespace Core\User\Http\Controllers\Web;

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
use Core\User\Repositories\DashboardRepository;

class HomePageController extends WebController
{
    /**
     * Repository
     * @var \Core\User\Repositories\DashboardRepository
     */
    public $repository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        parent::__construct();
        $this->repository = $dashboardRepository;
    }

    /**
     * Show the dashboard
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function dashboard(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->user($request);
        }

        return Inertia::render("Web/About", [
            'route' => route('user.dashboard'),
        ]);
    }
}
