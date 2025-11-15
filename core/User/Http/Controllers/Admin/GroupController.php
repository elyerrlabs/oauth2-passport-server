<?php
namespace Core\User\Http\Controllers\Admin;

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


use Core\User\Services\GroupService;
use Core\User\Transformer\Admin\GroupTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Inertia\Inertia;
use Core\User\Model\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Core\User\Http\Requests\GroupStoreRequest;

class GroupController extends WebController
{
    use JsonResponser;

    /**
     * Repository
     * @var GroupService
     */
    public $groupService;

    /**
     * Construct
     * @param  GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        parent::__construct();
        $this->groupService = $groupService;
        $this->middleware('userCanAny:administrator:group:full,administrator:group:view')->only('index');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:show')->only('show');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:create')->only('store');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:update')->only('update');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:destroy')->only('destroy');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:enable')->only('enable');
        $this->middleware('userCanAny:administrator:group:full,administrator:group:disable')->only('disable');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        $page = $request->filled('par_page') ? $request->per_page : 15;

        $data = $this->groupService->search($request)->paginate($page);

        return Inertia::render(
            "Core/User/Admin/Groups/Index",
            [
                "data" => fractal($data, GroupTransformer::class)->toArray(),
                "route" => route('user.admin.groups.index'),
                'admin_routes' => resolveInertiaRoutes(config('menus.admin_routes'))
            ]
        );
    }

    /**
     * Store new resource
     * @param \Core\User\Http\Requests\GroupStoreRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(GroupStoreRequest $request)
    {
        $this->groupService->create($request->toArray());

        return redirect()->route('user.admin.groups.index');
    }

    /**
     * Update resource
     * @param \Illuminate\Http\Request $request
     * @param \Core\User\Model\Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Group $group)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:200'],
        ]);

        $this->groupService->update($group->id, $request->toArray());

        return redirect()->route('user.admin.groups.index');
    }

    /**
     *  Destroy resource
     * @param \Core\User\Model\Group $group
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Group $group)
    {
        $this->groupService->delete($group->id);

        return redirect()->route('user.admin.groups.index');
    }
}
