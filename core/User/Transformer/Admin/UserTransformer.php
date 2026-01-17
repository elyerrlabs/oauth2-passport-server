<?php

namespace Core\User\Transformer\Admin;

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
            'lang' => $user->lang,
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
