<?php

namespace Core\User\Repositories;

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

use Core\User\Model\Role;
use Core\User\Model\User;
use Core\User\Model\Group;
use Core\User\Model\Service;
use Illuminate\Http\Request;
use Core\Transaction\Model\Plan;
use Elyerr\ApiResponse\Assets\Asset;
use Elyerr\ApiResponse\Assets\JsonResponser;


class DashboardRepository
{
    use JsonResponser, Asset;


    public function user(Request $request)
    {
        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $users_by_month = User::query();

        //Apply filter between days
        if ($request->has('start') && $request->has('end')) {
            $request->merge([
                'start' => $request->start . ' 00:00:00',
                'end' => $request->end . ' 23:59:59',
            ]);

            $users_by_month->whereBetween('created_at', [$request->start, $request->end]);
        }

        $users_by_month = $users_by_month->selectRaw("TO_CHAR(created_at, '{$time}') as month, COUNT(id) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')")
            ->get();

        $last_users = User::latest('created_at')->take(10)->get();

        $groups = Group::count();
        $roles = Role::count();
        $services = Service::count();
        $users = User::count();

        $data = [
            'users_by_month' => $users_by_month->toArray(),
            'last_users' => $last_users,
            'groups' => $groups,
            'roles' => $roles,
            'services' => $services,
            'users' => $users,
        ];

        return $this->data(["data" => $data]);
    }

    public function admin(Request $request)
    {
        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $users_by_month = User::query();

        //Apply filter between days
        if ($request->has('start') && $request->has('end')) {
            $request->merge([
                'start' => $request->start . ' 00:00:00',
                'end' => $request->end . ' 23:59:59',
            ]);

            $users_by_month->whereBetween('created_at', [$request->start, $request->end]);
        }

        $users_by_month = $users_by_month->selectRaw("TO_CHAR(created_at, '{$time}') as month, COUNT(id) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')")
            ->get();

        $last_users = User::latest('created_at')->take(10)->get();

        $groups = Group::count();
        $roles = Role::count();
        $services = Service::count();
        $users = User::count();
        $channels = Broadcast::count();
        $plans = Plan::count();

        $data = [
            'users_by_month' => $users_by_month->toArray(),
            'last_users' => $last_users,
            'groups' => $groups,
            'roles' => $roles,
            'services' => $services,
            'users' => $users,
            'channels' => $channels,
            'plans' => $plans,
        ];

        return $this->data(["data" => $data]);
    }
}