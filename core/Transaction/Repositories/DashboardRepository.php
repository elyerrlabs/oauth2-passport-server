<?php

namespace Core\Transaction\Repositories;

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