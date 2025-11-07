<?php

namespace Core\User\Transformer\User;

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

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
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
    public function transform($notification)
    {
        $data = json_decode(json_encode($notification->data));

        return [
            'id' => $notification->id,
            "title" => $data->title ?? null,
            "message" => $data->message ?? null,
            "link" => $data->url ?? null,
            "created" => $this->format_date($notification->created_at),
            "read_at" => $this->format_date($notification->read_at),
            'links' => [
                'index' => route('user.notification.index'),
                'destroy_all' => route('user.notification.destroy-all'),
                'mark_all_as_read' => route('user.notification.mark-all-as-read'),
                'unread' => route('user.notification.unread'),
                'show' => route('user.notification.show', ['notification_id' => $notification->id]),
                'mark_as_read' => route('user.notification.mark-as-read', ['notification_id' => $notification->id]),
            ],
        ];

    }
}
