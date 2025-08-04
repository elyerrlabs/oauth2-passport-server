<?php
namespace App\Http\Controllers\Web\Admin\Broadcasting;

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
