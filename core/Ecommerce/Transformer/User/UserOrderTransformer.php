<?php

namespace Core\Ecommerce\Transformer\User;

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


use App\Models\Common\Order;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;
use Core\Ecommerce\Transformer\User\UserFileTransformer;

class UserOrderTransformer extends TransformerAbstract
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
    public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'meta' => $order->meta,
            'images' => fractal($order->orderable->files, UserFileTransformer::class)->toArray()['data'],
            'quantity' => $order->quantity,
            'stock' => $order->orderable->stock,
            'price' => $order->orderable->price->amount,
            'format_price' => $this->formatMoney($order->orderable->price->amount),
            'currency' => getCurrencySymbol($order->orderable->price->currency),
            'user_id' => [
                'id' => $order->user->id,
                'name' => $order->user->name . " " . $order->user->last_name,
                'email' => $order->email
            ],
            'status' => $order->status,
            'links' => [
                'show' => route('ecommerce.products.show', [
                    'category' => $order->meta['category']['slug'],
                    'product' => $order['meta']['slug']
                ]),
                'update' => route('ecommerce.orders.store'),
                'delete' => route('ecommerce.orders.destroy', [
                    'order' => $order->id
                ])
            ]
        ];
    }
}
