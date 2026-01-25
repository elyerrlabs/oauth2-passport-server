<?php
namespace Core\User\Model;

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

use App\Models\Common\Order;
use Core\Transaction\Model\Refund;
use App\Models\Auth;
use App\Repositories\Contracts\Dynamic;
use Illuminate\Http\Request;
use App\Models\Setting\Terminal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Auth
{
    use SoftDeletes, Dynamic;

    public $tag = "user";

    public $table = "users";

    protected $fillable = [
        "name",
        "last_name",
        "email",
        'password',
        'country',
        'city',
        'address',
        'dial_code',
        'phone',
        'birthday',
        'email_verified_at',
        'partner_id',
        'accept_cookies',
        'accept_terms',
        'lang'
    ];

    /**
     * User scopes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<UserScope, User>
     */
    public function userScopes()
    {
        return $this->hasMany(\Core\User\Model\UserScope::class, 'user_id', 'id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'accept_cookies' => 'boolean',
        'accept_terms' => 'boolean',
    ];

    /**
     * Retrieve a middle name
     * @return string
     */
    public function getMiddleName()
    {
        $data = explode(" ", $this->name);
        unset($data[0]);

        return implode($data);
    }


    /**
     * Verify the correct user and check if they have activated 2FA.
     *
     * @param Request $request
     */
    public static function validate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->m2fa && Hash::check($request->password, $user->password)) {
            return true;
        }

        return false;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all refunds owned by the user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Refund, User>
     */
    public function ownedRefunds()
    {
        return $this->hasMany(Refund::class, 'user_id');
    }

    /**
     * Get all refunds handled or processed by the admin user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Refund, User>
     */
    public function handledRefunds()
    {
        return $this->hasMany(Refund::class, 'handled_id');
    }
}
