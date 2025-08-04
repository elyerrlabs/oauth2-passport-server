<?php

namespace App\Transformers\User;

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

use App\Models\User\User;
use League\Fractal\TransformerAbstract;

class OpenIDTransformer extends TransformerAbstract
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
    public function transform(User $user)
    {
        return [
            "sub" => $user->id,
            "name" => "{$user->name} {$user->last_name}",
            "given_name" => $user->name,
            "family_name" => $user->last_name,
            "email" => $user->email,
            "updated_at" => $user->updated_at,
            "middle_name" => $user->getMiddleName(),
            "birthdate" => $user->birthday,
            //"nickname" => null,
            //"preferred_username" => null,
            "profile" => route('users.dashboard'),
            //"picture" => null,
            //"website" => null,
            //"gender" => null,
            //"zoneinfo" => null,
            //"locale" => null,
            "email_verified" => $user->verified_at ? true : false,
            "phone_number" => $user->dial_code . " " . $user->phone,
            'groups' => $user->myGroups(),
            'id' => $user->id,
            //"phone_number_verified" => null,
            //"address" => $user->address
        ];
    }
}
