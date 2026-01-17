<?php

namespace Core\Transaction\Repositories;

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

use Core\User\Model\User;
use Illuminate\Http\Request;
use Core\Transaction\Model\Plan;
use Core\Transaction\Model\Package;
use Elyerr\ApiResponse\Assets\Asset;
use Core\Transaction\Model\Transaction;
use Elyerr\ApiResponse\Assets\JsonResponser;
use Core\Partner\Transformer\DataTransformer;


class DashboardRepository
{
    use JsonResponser, Asset;


    public function transaction(Request $request)
    {
        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $transactions_by_month = Transaction::query();

        $transactions_by_month->where('status', $request->input('status') ?? 'successful');

        //Apply filter between days
        if ($request->has('start') && $request->has('end')) {
            $request->merge([
                'start' => $request->start . ' 00:00:00',
                'end' => $request->end . ' 23:59:59',
            ]);

            $transactions_by_month->whereBetween('created_at', [$request->start, $request->end]);
        }

        $transactions_by_month = $transactions_by_month->selectRaw("TO_CHAR(created_at, '{$time}') as month, COUNT(id) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')")
            ->get();

        $plans = Plan::count();
        $packages = Package::count();
        $transactions = Transaction::count();

        $data = [
            'transactions_by_month' => $transactions_by_month->toArray(),
            'plans' => $plans,
            'packages' => $packages,
            'transactions' => $transactions
        ];

        return $this->data(["data" => $data]);
    }
}