<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

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
 */


use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Subscription\Group;
use App\Repositories\GroupRepository;
use App\Http\Controllers\WebController;
use App\Http\Requests\Group\StoreRequest;

class GroupController extends WebController
{

    /**
     * Repository
     * @var GroupRepository
     */
    public $repository;

    public function __construct(GroupRepository $groupRepository)
    {
        parent::__construct();
        $this->repository = $groupRepository;
        $this->middleware('userCanAny:administrator:group:full,administrator:group:view')->only('index');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:show')->only('show');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:create')->only('store');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:update')->only('update');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:destroy')->only('destroy');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:enable')->only('enable');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:disable')->only('disable');
        $this->middleware('wants.json')->only('show');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render(
            "Admin/Groups/Index",
            [
                "route" => route('admin.groups.index')
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\Group\StoreRequest $request 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Subscription\Group $group
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Group $group)
    {
        return $this->repository->detail($group->id);
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Group $group
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function update(Request $request, Group $group)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:200'],
        ]);

        return $this->repository->update($group->id, $request->toArray());
    }

    /**
     * Destroy specific resource
     * @param \App\Models\Subscription\Group $group
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(Group $group)
    {
        return $this->repository->delete($group->id);
    }
}
