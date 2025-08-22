<?php

namespace Core\Partner\Repositories;

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

use Illuminate\Http\Request;
use Elyerr\ApiResponse\Assets\Asset;
use Core\Transaction\Model\Transaction;
use Core\Partner\Transformer\DataTransformer;
use Elyerr\ApiResponse\Assets\JsonResponser;


class DashboardRepository
{
    use JsonResponser, Asset;

    /**
     * Show data for the dashboard in the partner view
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function partner(Request $request)
    {
        //Retrieve the user partner code
        $partnerCode = auth()->user()->partner->code ?? null;

        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        //Generate a transaction query
        $data = Transaction::query();

        //Retrieve transactions by partner code
        $data->whereHas('partner', function ($query) use ($partnerCode) {
            $query->where('code', $partnerCode);
        });

        //Filter by only status is successfully
        $data->where('status', config('billing.status.successful.name'));

        //Apply filter between days
        if ($request->has('start') && $request->has('end')) {

            $request->merge([
                'start' => $request->start . ' 00:00:00',
                'end' => $request->end . ' 23:59:59',
            ]);

            $data->whereBetween('created_at', [$request->start, $request->end]);
        }

        //Make a query to filter data
        $data->selectRaw("
                TO_CHAR(created_at, '{$time}') as date,  
                COUNT(id) as total,
                currency,
                ROUND(SUM(total * (partner_commission_rate / 100))) as commission
            ")
            ->groupByRaw("TO_CHAR(created_at, '{$time}'), currency")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')");

        //Get all data
        $data = $data->get();
        //Sum all transactions
        $total_sales = $data->sum(function ($item) {
            return $item->total++;
        });

        //Sum commission by currency
        $total_commissions = $data
            ->groupBy('currency')
            ->map(function ($items, $currency) {
                $sum = $items->sum('commission');
                return [
                    'currency' => $currency,
                    'total' => $this->formatMoney($sum),
                ];
            })
            ->values()
            ->toArray();

        //Make output data
        return [
            "data" => fractal($data, DataTransformer::class)->toArray()['data'],
            "total_sales" => $total_sales,
            "total_commission" => $total_commissions
        ];
    }

    /**
     * Show the data for dashboard on the user
     * @param \Illuminate\Http\Request $request
     * @return JsonResponser
     */
    public function home(Request $request)
    {
        $latest = Transaction::whereHas('package', function ($query) {
            $query->where('id', auth()->user()->id);
        });

        return $this->data([
            'data' => [
                'transactions' => $latest->latest()->take(10)->get(),
            ]
        ]);
    }
}