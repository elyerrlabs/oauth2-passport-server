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


use Core\User\Services\GroupService;
use Core\User\Transformer\Admin\GroupTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Elyerr\ApiResponse\Exceptions\ReportError;
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
            "Admin/Groups/Index",
            [
                "data" => fractal($data, GroupTransformer::class)->toArray(),
                "route" => route('user.admin.groups.index'),
                'menus' => resolveInertiaRoutes(config('menus.admin_routes'))
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

        throw_if(
            $group->system,
            new ReportError(
                __("This is a system group and cannot be modified. If you believe this is an error, please contact the administrator."),
                403
            )
        );

        $this->groupService->update($group->id, $request->toArray());

        return redirect()->route('user.admin.groups.index');
    }

    /**
     *  Destroy resource
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->groupService->delete($id);

        return redirect()->route('user.admin.groups.index');
    }
}
