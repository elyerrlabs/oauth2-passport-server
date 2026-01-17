<?php

namespace Core\User\Transformer\User;

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

use Core\User\Model\User;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class AuthTransformer extends TransformerAbstract
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
            'full_phone' => $user->dial_code . " " . $user->phone,
            'm2fa' => $user->m2fa ? true : false,
            'lang' => $user->lang,
            'groups' => $user->myGroups(),
            'verify_email' => $user->verified_at ? true : false,
            'verified' => $this->format_date($user->verified_at),
            'created' => $this->format_date($user->created_at),
            'updated' => $this->format_date($user->updated_at),
            'disabled' => $this->format_date($user->deleted_at),
            'last_connected' => $this->format_date($user->last_connected),
            'links' => [
                'update' => route('user.update'),
                'change_password' => route('user.change.password'),
                'send_verification_email' => route('user.verification.email'),
                'verify_account' => route('user.verify.account'),
                'verified_account' => route('user.verified.account'),
                'check_account' => route('user.check.account'),
                'request_2fa_code' => route('user.2fa.authorize'),
                'f2a_activate' => route('user.2fa.activate'),
                'f2a_login' => route('user.2fa.login'),

            ],
        ];
    }
}
