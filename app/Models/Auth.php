<?php

namespace App\Models;

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

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Notifications\Auth\VerifyEmail;
use DateTime;
use DateInterval;
use LogicException;
use App\Support\CacheKeys;
use Core\User\Model\Group;
use Core\User\Model\UserScope;
use Laravel\Passport\HasApiTokens;
use App\Repositories\Traits\Scopes;
use Elyerr\ApiResponse\Assets\Asset;
use App\Repositories\Traits\Standard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth extends Authenticatable implements MustVerifyEmail
{
    use HasUuids, HasApiTokens, HasFactory, TwoFactorAuthenticatable, Notifiable, Scopes, Asset, Standard;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Check the admin user
     * @return bool
     */
    public function isAdmin()
    {
        $user = auth()->user();

        $gsr = Cache::remember(
            CacheKeys::userAdmin($user->id),
            now()->addDays(intval(config('cache.expires', 90))),
            function () use ($user) {

                $scopes = UserScope::where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhere('end_date', '>', now());
                    })->get();

                return count($scopes) ? $scopes->pluck('gsr_id')->toArray() : [];
            }
        );

        return in_array($this->adminScopeName(), $gsr);
    }

    /**
     * Name of admin scope
     * @return string
     */
    public function adminScopeName()
    {
        return "administrator:admin:full";
    }

    /**
     * Determine if the current API token has a given scope
     *
     * @param  string  $scope
     * @return bool
     */
    public function tokenCan($scope)
    {
        $apiKey = $this->accessToken;

        if (isset($apiKey->id) && $apiKey->client->hasGrantType('personal_access')) {
            if (in_array($scope, $apiKey->scopes)) {
                return true;
            }
            return false;
        }

        if (auth()->check()) {
            $userScopes = $this->scopes();
            if (!empty($userScopes) && in_array($scope, $userScopes->pluck('id')->toArray())) {
                return true;
            }
        }

        return false;
    }


    /**
     * Summary of canAny
     * @param array $scopes
     * @return bool
     */
    public function hasAccess(array $scopes)
    {
        if (!auth()->check()) {
            return false;
        }

        if (auth()->user()->isAdmin()) {
            return true;
        }

        $userScopes = $this->scopes(false)->pluck('id') ?? [];

        // Clean spaces
        $scopes = array_map('trim', $scopes);

        if (count($userScopes) && array_intersect($userScopes->toArray(), $scopes)) {
            return true;
        }

        return false;
    }


    /**
     * Relationship with scopes
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userScopes()
    {
        return $this->hasMany(UserScope::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Setting the time to create account
     * @param mixed $years
     * @return string
     */
    public static function setBirthday($years = 13)
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P' . $years . 'Y'));
        return $date->format('Y-m-d');
    }

    /**
     * Groups
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Group, Auth>
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Check if the user has a group
     * @param mixed $group
     */
    public function canAccessMenu($group): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($this->isAdmin()) {
            return true;
        }

        $groups = $this->scopes()
            ->pluck('id')
            ->map(fn($item) => implode(':', array_slice(explode(':', $item), 0, 2)))
            ->toArray();

        return in_array($group, $groups, true);
    }


    /**
     * List the all active groups for user
     */
    public function listUserGroups(): mixed
    {
        if (!auth()->check()) {
            return [];
        }

        $user = auth()->user();

        $cacheKey = CacheKeys::userGroups($user->id);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if ($user->isAdmin()) {
            $groups = Group::get()->unique()->values();

        } else {

            // Filter groups without services 
            $groupsWithoutServices = Group::whereHas(
                'users',
                function ($query) use ($user) {
                    $query->where('id', $user->id);
                }
            )->whereDoesntHave('services')->get();

            // Filter groups by Scopes
            $groupsByScopes = UserScope::with('scope.service.group')
                ->where('user_id', $user->id)
                ->where(function ($query) {
                    $query->whereNull('end_date')
                        ->orWhere('end_date', '>', now());
                })->get()
                ->map(function ($userScope) {
                    return $userScope->scope->service->group;
                })->unique()->values();

            // Join the all groups for user
            $groups = $groupsWithoutServices->concat($groupsByScopes);
        }

        Cache::put(
            $cacheKey,
            $groups,
            now()->addDays(intval(config('cache.expires', 90)))
        );

        return $groups;
    }


    /**
     * Return the groups 
     */
    public function myGroups()
    {
        $groups = $this->listUserGroups();

        if (!count($groups)) {
            return [];
        }

        return $groups->map(fn($group) => [
            'name' => $group->name,
            'slug' => $group->slug,
        ])->toArray();
    }


    /**
     * Retrieve metadata of the model
     * @param array $transformer
     */
    public function meta($transformer = null)
    {
        $data = fractal($this, $transformer ?? $this->transformer)->toArray()['data'];
        unset($data['links']);
        return $data;
    }

    /**
     * Updated the las connection
     * @return void
     */
    public function lastConnected()
    {
        $this->last_connected = now();
        $this->push();
    }

    /**
     * Retrieve the passport providers
     * @throws \LogicException
     * @return string
     */
    public function getProviderName(): string
    {

        $providers = collect(config('auth.guards'))->where('driver', 'oauth2-passport-server')->pluck('provider')->all();

        foreach (config('auth.providers') as $provider => $config) {
            if (in_array($provider, $providers) && $config['driver'] === 'eloquent' && is_a($this, $config['model'])) {
                return $provider;
            }
        }

        throw new LogicException('Unable to determine authentication provider for this model from configuration.');
    }
}
