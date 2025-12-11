<?php

namespace Core\Ecommerce\Services;

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

use App\Models\Common\Variant;
use Core\Transaction\Model\Refund;
use Core\Transaction\Repositories\TransactionRepository;
use Core\Ecommerce\Transformer\Admin\TotalTransformer;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Http\Request;

class DashboardService
{
    use Asset;

    public function dashboard(Request $request)
    {
        $data = [];
        $currency = $request->filled('currency') ? strtolower($request->currency) : 'pen';
        $stock = 10;
        $start = $request->filled('start') ? $request->start . ' 00:00:00' : now()->subMonth()->format('Y-m-d') . ' 00:00:00';
        $end = $request->filled('end') ? $request->end . ' 23:59:59' : now()->format('Y-m-d') . ' 23:59:59';

        if ($request->filled('currency')) {
            $currency = strtolower($request->currency);
        }

        $request->merge([
            'currency' => $currency,
            'start' => $start,
            'end' => $end,
            'stock' => $stock,
        ]);

        // Add Currency and symbol to the data
        $data['currency'] = strtoupper($currency);
        $data['currency_symbol'] = getCurrencySymbol(strtoupper($currency));
        $data['products_available'] = Variant::sum('stock');

        // Filter transaction by date
        $data['successfully_transactions'] = $this->getSuccessfullyTransaction($request);
        $data['pending_refund'] = $this->getPendingRefunding($request);
        $data['successfully_refund'] = $this->getSuccessfullyRefunding($request);

        return $data;
    }

    /**
     * Filter transactions
     * @param \Illuminate\Http\Request $request
     */
    public function getSuccessfullyTransaction(Request $request)
    {
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $query = app(TransactionRepository::class)->query();

        $query->whereDoesntHave('refund')->where('type', '!=', 'refund');
        $query->whereRaw('LOWER(currency) = ?', [$request->currency]);
        $query->whereBetween('created_at', [$request->start, $request->end]);

        $query->selectRaw("TO_CHAR(created_at, '{$time}') as range,  SUM(total) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')");

        return fractal($query->get(), TotalTransformer::class)->toArray()['data'];
    }


    /**
     * Get pending refunding transaction
     * @param mixed $request
     */
    public function getPendingRefunding($request)
    {
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $query = app(TransactionRepository::class)->query();
        $query->whereHas('refund', function ($query) {
            $query->where('status', "=", 'pending');
        });
        $query->whereRaw('LOWER(currency) = ?', [$request->currency]);
        $query->whereBetween('created_at', [$request->start, $request->end]);

        $query->selectRaw("TO_CHAR(created_at, '{$time}') as range,  SUM(total) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')");

        return fractal($query->get(), TotalTransformer::class)->toArray()['data'];
    }

    /**
     * Retrieve successfully refund
     * @param mixed $request
     */
    public function getSuccessfullyRefunding($request)
    {
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $query = app(TransactionRepository::class)->query();
        $query->where('transactionable_type', '=', app(Refund::class)->tag);
        $query->where('status', '=', 'successful');
        $query->whereRaw('LOWER(currency) = ?', [$request->currency]);
        $query->whereBetween('created_at', [$request->start, $request->end]);

        $query->selectRaw("TO_CHAR(created_at, '{$time}') as range,  SUM(total) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')");

        return fractal($query->get(), TotalTransformer::class)->toArray()['data'];
    }
}