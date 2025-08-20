<?php

namespace Core\User\Transformer\Admin;

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

use App\Repositories\Traits\Scopes;
use Core\User\Model\User; 
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    use Asset, Scopes;

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
            'id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'country' => $user->country,
            'city' => $user->city,
            'address' => $user->address,
            'birthday' => $user->birthday,
            'phone' => $user->phone,
            'dial_code' => $user->dial_code,
            'commission_rate' => $user->partner ? $user->partner->commission_rate : 0,
            'full_phone' => $user->dial_code . " " . $user->phone,
            'm2fa' => $user->m2fa,
            'verify_email' => $user->verified_at ? true : false,
            'verified' => $this->format_date($user->verified_at),
            'created' => $this->format_date($user->created_at),
            'last_connected' => $this->format_date($user->last_connected),
            'updated' => $this->format_date($user->updated_at),
            'disabled' => $this->format_date($user->deleted_at),
            'links' => [
                'index' => route('user.admin.users.index'),
                'store' => route('user.admin.users.store'),
                'show' => route('user.admin.users.show', ['user' => $user->id]),
                'update' => route('user.admin.users.update', ['user' => $user->id]),
                'disable' => route('user.admin.users.disable', ['user' => $user->id]),
                'enable' => route('user.admin.users.enable', ['id' => $user->id]),
                'scopes' => route('user.admin.users.scopes.index', ['user' => $user->id]),
                'groups' => route('user.admin.users.groups.index', ['user' => $user->id]),
            ],
        ];
    }

    /**
     * Retrieve the keys available for this model
     * @param mixed $index
     * @return string|null
     */
    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'last_name' => 'last_name',
            'email' => 'email',
            'country' => 'country',
            'city' => 'city',
            'address' => 'address',
            'phone' => 'phone',
            'dial_code' => 'dial_code',
            'birthday' => 'birthday',
            'verified' => 'verified_at',
            'm2fa' => 'm2fa',
            'created' => 'created_at',
            'updated' => 'updated_at',
            'disabled' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
