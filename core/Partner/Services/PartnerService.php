<?php

namespace Core\Partner\Services;

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

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Elyerr\ApiResponse\Assets\Asset;
use Core\Partner\Repositories\UserRepository;
use Core\Partner\Transformer\DataTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Core\Partner\Repositories\PartnerRepository;
use Core\Transaction\Repositories\TransactionRepository;

class PartnerService
{
    use Asset;

    /**
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Transaction repository
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * Construct
     * @return void
     */
    public function __construct()
    {
        $this->partnerRepository = app(PartnerRepository::class);
        $this->userRepository = app(UserRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
    }

    /**
     * Search transaction for the current user
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Transaction>
     */
    public function search(Request $request)
    {
        // Create transaction query
        $transactionQuery = $this->transactionRepository->query();

        // Get current user
        $partner = $this->userRepository->find(auth()->user()->id)->partner;

        // Filter by partner code
        $transactionQuery->whereHas(
            'partner',
            function ($query) use ($partner) {
                $query->where('code', $partner->code ?? null);
            }
        );

        return $transactionQuery;
    }

    /**
     * List all partners
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder<\Core\User\Model\User>
     */
    public function listPartners(Request $request)
    {
        $userQuery = $this->userRepository->query();

        if ($request->filled('name')) {
            $userQuery->whereRaw(
                "LOWER(name) LIKE ?",
                ['%' . strtolower($request->name) . '%']
            );
        }

        if ($request->filled('last_name')) {
            $userQuery->whereRaw(
                "LOWER(last_name) LIKE ?",
                ['%' . strtolower($request->last_name) . '%']
            );
        }

        if ($request->filled('email')) {
            $userQuery->whereRaw(
                "LOWER(email) LIKE ?",
                ['%' . strtolower($request->email) . '%']
            );
        }

        if ($request->filled('country')) {
            $userQuery->whereRaw(
                "LOWER(country) LIKE ?",
                ['%' . strtolower($request->country) . '%']
            );
        }

        if ($request->filled('code')) {
            $userQuery->whereHas(
                'partner',
                function ($query) use ($request) {
                    $query->whereRaw("LOWER(code) LIKE ?", [
                        '%' . strtolower($request->code) . '%'
                    ]);
                }
            );
        }

        return $userQuery;
    }

    /**
     * Show partner details
     * @param string $user_id
     * @return \Core\Partner\Model\Partner|\Core\Partner\Repositories\TModel|\Illuminate\Database\Eloquent\Model|null
     */
    public function details(string $user_id)
    {
        // Retrieve user with partner have
        $query = $this->userRepository->query();

        // Filter by current user id
        $query->where('id', $user_id);

        // Get the user with partner relationship
        $user = $query->first();

        if (empty($user->partner)) {
            $partner = $this->partnerRepository->create([
                'code' => $this->generateReferralCode(),
                'user_id' => $user_id
            ]);

            return $partner;
        }

        return $user->partner;
    }

    /**
     * Generate code
     * @param mixed $prefix
     * @param mixed $length
     * @return string
     */
    public function generateReferralCode($length = 32)
    {
        $prefix = strtoupper(Str::random(2));
        $random = strtoupper(Str::random($length));
        return $prefix . "_" . $random;
    }


    /**
     * Update commission rate
     * @param string $user_id
     * @param mixed $percentage
     * @throws \Elyerr\ApiResponse\Exceptions\ReportError
     */
    public function updateCommissionRate(string $user_id, $percentage)
    {
        try {
            $user = $this->userRepository->find($user_id);

            // If the use does not have a partner, create one
            if (empty($user->partner)) {

                return $this->partnerRepository->create([
                    'code' => $this->generateReferralCode(),
                    'user_id' => $user->id,
                    'commission_rate' => $percentage
                ]);


            } else {
                return $this->partnerRepository->update($user->partner->id, [
                    'commission_rate' => $percentage
                ]);
            }
        } catch (\Throwable $th) {
            throw new ReportError(__("The specified partner does not exist."), 404);
        }

    }

    /**
     * Generate or retrieve referral link
     * @return mixed
     */
    public function generateLink()
    {
        $query = $this->userRepository->query();

        $query->where('id', auth()->user()->id);

        $user = $query->first();

        if (empty($user)) {
            throw new ReportError(__("You are not yet registered as a partner, so you cannot generate a referral link. If you believe this is an error, please contact the administrator."), 403);
        }

        $partner = $user->partner;

        if (empty($partner)) {
            $partner = $this->partnerRepository->create([
                'code' => $this->generateReferralCode(),
                'user_id' => auth()->user()->id
            ]);
        }

        $partner["links"] = $partner->referLinks();

        return $partner;
    }


    public function partnerDashboard(Request $request)
    {
        //type of filter by day , month or year
        $type = $request->type ?? 'day';
        $time = searchByDate($type);

        //Generate a transaction query
        $transactionQuery = $this->transactionRepository->query();

        //Filter by only status is successfully
        $transactionQuery->where('status', config('billing.status.successful.id'));

        // Only for current user
        $transactionQuery->whereHas(
            'partner',
            function ($query) {
                $query->where('user_id', auth()->user()->id);
            }
        );

        //Apply filter between days
        if ($request->has('start') && $request->has('end')) {

            $request->merge([
                'start' => $request->start . ' 00:00:00',
                'end' => $request->end . ' 23:59:59',
            ]);

            $transactionQuery->whereBetween(
                'created_at',
                [
                    $request->start,
                    $request->end
                ]
            );
        }

        //Make a query to filter data
        $transactionQuery->selectRaw("
                TO_CHAR(created_at, '{$time}') as date,  
                COUNT(id) as total,
                UPPER(currency) as currency,
                ROUND(SUM(total * (partner_commission_rate / 100))) as commission
            ")
            ->groupByRaw("TO_CHAR(created_at, '{$time}'), currency")
            ->orderByRaw("TO_CHAR(created_at, '{$time}')");

        //Get all data
        $data = $transactionQuery->get();

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
     * List transactions for specific user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder<\Core\Transaction\Model\Transaction>
     */
    public function listLastTransactions(Request $request)
    {
        // Get Current user
        $user = $this->userRepository->find(auth()->user()->id);

        if (empty($user->partner)) {
            throw new ReportError(__("You are not registered as a partner."), 403);
        }

        // Create transaction query
        $transactions = $this->transactionRepository->query();

        $transactions->where('status', config('billing.status.successful.id'));

        // Filter by partner id
        $transactions->where('partner_id', $user->partner->id);

        return $transactions;
    }
}
