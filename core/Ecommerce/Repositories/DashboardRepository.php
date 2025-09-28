<?php


namespace Core\Ecommerce\Repositories;

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

use App\Models\Common\Category;
use App\Models\Common\Variant;
use Illuminate\Support\Facades\Storage;
use Core\Ecommerce\Transformer\Admin\TotalTransformer;
use Core\Ecommerce\Model\Product;
use Core\Transaction\Model\Checkout;
use Core\Transaction\Model\Transaction;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Http\Request;

class DashboardRepository
{

    use Asset;

    public function dashboard(Request $request)
    {
        $data = [];
        $currency = 'pen';
        $stock = 10;
        $start = now()->subMonth()->format('Y-m-d');
        $end = now()->format('Y-m-d');

        if ($request->filled('currency')) {
            $currency = $request->currency;
        }

        if ($request->filled('start')) {
            $start = $request->start;
        }


        if ($request->filled('end')) {
            $end = $request->end;
        }

        if ($request->filled('stock')) {
            $stock = $request->stock;
        }

        $request->merge([
            'currency' => $currency,
            'start' => $start . ' 00:00:00',
            'end' => $end . ' 23:59:59',
            'stock' => $stock,
        ]);


        $data['currency'] = strtoupper($currency);
        $data['currency_symbol'] = getCurrencySymbol(strtoupper($currency));

        // Lower products
        $data['products_lower_stock'] = Product::whereHas('variants', function ($query) use ($request) {
            $query->where('stock', '<=', $request->stock);
        })->count();

        // Total stock
        $data['products_stock_total'] = Variant::sum('stock');

        // Transaction by range 
        $data['transactions_total'] = $this->formatMoney(Transaction::query()
            ->whereBetween('created_at', [$request->start, $request->end])
            ->where('currency', $request->currency)->sum('total'));

        // Transaction by day
        $data['transactions_today'] = $this->formatMoney(Transaction::whereBetween('created_at', [
            now()->format('Y-m-d') . ' 00:00:00',
            now()->format('Y-m-d') . ' 23:59:00',
        ])->where('currency', $request->currency)->sum('total'));

        // Product pending
        $data['products_pending'] = Checkout::whereHas(
            'lastTransaction',
            function ($query) {
                $query->where('status', config('billing.status.successful.id'));
            }
        )->count();

        // Filter transaction by date
        $data['transactions'] = $this->filterTransactions($request);

        // Retrieve the 10 last checkouts
        $data['checkouts'] = Checkout::with(['user', 'lastTransaction'])
            ->whereHas('lastTransaction')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->code,
                    'transaction_code' => $item->lastTransaction->code,
                    'customer' => $item->user->name . " " . $item->user->last_name,
                    'total' => getCurrencySymbol(strtoupper($item->lastTransaction->currency)) . " " . $this->formatMoney($item->lastTransaction->total),
                    'status' => $item->lastTransaction->status,
                    'date' => $this->format_date($item->lastTransaction->created_at),
                    'link' => route('ecommerce.admin.orders.complete')
                ];
            });

        // Top Products
        //$data['top_products'] = Product::limit(25)->with(['category', 'orders', 'price'])
        $data['top_products'] = Variant::limit(25)->with([
            'variantable.category',
            'orders',
            'price'
        ])
            ->whereHas(
                'orders',
                function ($q) {
                    $q->whereHas('checkout');
                }
            )->withCount([
                    'orders as orders_with_checkout_count' => function ($q) {
                        $q->whereHas('checkout');
                    }
                ])
            ->orderByDesc('orders_with_checkout_count')
            ->get()->map(function ($query) {

                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'category' => $query->variantable->category->name,
                    'price' => getCurrencySymbol(strtoupper($query->price->currency)) . " " . $this->formatMoney($query->price->amount),
                    'sold' => $query->orders_with_checkout_count,
                    'image' => Storage::url($query->variantable->files[0]->path),
                ];
            });

        return $data;
    }

    /**
     * Filter transactions
     * @param \Illuminate\Http\Request $request
     */
    public function filterTransactions(Request $request)
    {
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        $transactions = Transaction::query();
        $transactions->where('currency', $request->currency);
        $transactions->whereBetween('created_at', [$request->start, $request->end]);

        $transactions = $transactions->selectRaw("TO_CHAR(created_at, '{$time}') as range,  SUM(total) as total")
            ->groupByRaw("TO_CHAR(created_at, '{$time}')")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')")
            ->get();

        return fractal($transactions, TotalTransformer::class)->toArray()['data'];
    }


}