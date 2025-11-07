<?php


namespace Core\Ecommerce\Transformer\Admin;

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
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    use Asset;
    use Scopes;

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {


        $transactions = $user->checkouts->flatMap->transactions;
        $transactionsByCurrency = $transactions
            ->groupBy('currency')
            ->map(function ($group, $currency) {
                return [
                    'currency' => strtoupper($currency),
                    'total' => $this->formatMoney($group->sum('total')),
                ];
            })
            ->values();

        return [
            "id" => $user->id,
            "name" => $user->name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "city" => $user->city,
            "address" => $user->address,
            "full_phone" => $user->dial_code . " " . $user->phone,
            "checkouts_count" => $user->checkouts->count(),
            "checkout" => $transactionsByCurrency
        ];
    }
}
