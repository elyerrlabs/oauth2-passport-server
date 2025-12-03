<?php
namespace Core\User\Model;

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
        'verified_at',
        'm2fa',
        'totp',
        'partner_id',
        'accept_cookies',
        'accept_terms',
        'last_connected',
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
        'verified_at' => 'datetime',
        'accept_cookies' => 'boolean',
        'accept_terms' => 'boolean',
        'last_connected' => 'datetime'
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

    /**
     * Terminals
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terminals()
    {
        return $this->hasMany(Terminal::class);
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
