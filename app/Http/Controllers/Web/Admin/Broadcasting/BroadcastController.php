<?php
namespace App\Http\Controllers\Web\Admin\Broadcasting;

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
use App\Repositories\BroadcastRepository;
use App\Http\Requests\Broadcast\StoreRequest;

class BroadcastController extends WebController
{

    /**
     * Repository instance
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param \App\Repositories\BroadcastRepository $broadcastRepository
     */
    public function __construct(BroadcastRepository $broadcastRepository)
    {
        parent::__construct();
        $this->repository = $broadcastRepository;
        $this->middleware('userCanAny:administrator:broadcast:full,administrator:broadcast:view')->only('index');
        $this->middleware('userCanAny:administrator:broadcast:full,administrator:broadcast:create')->only('store');
        $this->middleware('userCanAny:administrator:broadcast:full,administrator:broadcast:destroy')->only('destroy');
    }

    /**
     * Show the resource
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Admin/Broadcast/Index");
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\Broadcast\StoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(string $id)
    {
        return $this->repository->delete($id);
    }
}
