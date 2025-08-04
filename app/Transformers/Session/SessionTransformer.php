<?php

namespace App\Transformers\Session;

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

use League\Fractal\TransformerAbstract;

class SessionTransformer extends TransformerAbstract
{
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
    public function transform($session)
    {
        return [
            'id' => $session->id,
            'ip' => $session->ip_address,
            'agent' => $session->user_agent,
            'last_activity' => $session->last_activity,
            'actual' => request()->session()->getId() == $session->id ?: false,
            'links' => [
                'parent' => route('sessions.index'),
                'destroy' => route('sessions.destroy', ['session' => $session->id]),
            ],
        ];
    }
}
