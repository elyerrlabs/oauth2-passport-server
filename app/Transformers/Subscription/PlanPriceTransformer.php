<?php
namespace App\Transformers\Subscription;

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
 */

use App\Models\Subscription\Price;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class PlanPriceTransformer extends TransformerAbstract
{
    use Asset;

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
    public function transform(Price $price)
    {
        return [
            'id' => $price->id,
            'billing_period' => $price->billing_period,
            'currency' => $price->currency,
            'amount' => $price->amount,
            'amount_format' => $this->formatMoney($price->amount),
            'expiration' => $this->format_date(billing_get_expiration_date($price->billing_period), 'Y-m-d H:i'),
            'created' => $this->format_date($price->created_at),
            'updated' => $this->format_date($price->updated_at),
            'links' => [
                'destroy' => route('admin.plans.prices.destroy', [
                    'plan' => $price->priceable->id,
                    'price' => $price->id
                ])
            ]
        ];
    }
}
