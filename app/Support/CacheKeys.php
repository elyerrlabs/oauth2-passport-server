<?php
namespace App\Support;

use Illuminate\Support\Facades\Cache;

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

class CacheKeys
{
    /**
     * Cache version
     * @param string $key
     * @return int
     */
    protected static function version(string $key): int
    {
        return Cache::get($key, 1);
    }

    /**
     * Cache default keys
     * @param string $key
     * @return bool
     */
    public static function exceptKeys(string $key)
    {
        return !in_array($key, ['cache.default', 'cache.expires']);
    }

    /**
     * Cache keys user
     * @param string $user_id
     * @return string
     */
    public static function user(string $user_id)
    {
        $version = self::version(CacheVersions::USERS);

        return "v:$version:user:$user_id";
    }

    /**
     * Cache key user scopes
     * @param string $user_id
     * @return string
     */
    public static function userScopes(string $user_id)
    {
        $version = self::version(CacheVersions::SCOPES);

        return "v:$version:user:$user_id:scopes";
    }

    /**
     * Cache keys user scopes api key
     * @param string $user_id
     * @return string
     */
    public static function userScopesApiKey(string $user_id)
    {
        $version = self::version(CacheVersions::SCOPES);

        return "v:$version:user:$user_id:scopes:api_key";
    }

    /**
     * Cache keys user admin
     * @param string $user_id
     * @return string
     */
    public static function userAdmin(string $user_id)
    {
        $version = self::version(CacheVersions::SCOPES);

        return "v:$version:user:$user_id:admin";
    }

    /**
     * Cache keys user gorups
     * @param string $user_id
     * @return string
     */
    public static function userGroups(string $user_id)
    {
        $version = self::version(CacheVersions::USERS);

        return "v:$version:user:$user_id:groups";
    }

    /**
     * Cache key user auth
     * @param string $user_id
     * @return string
     */
    public static function userAuth(string $user_id)
    {
        $version = self::version(CacheVersions::USERS);

        return "v:$version:user:$user_id:auth";
    }

    /**
     * Cache keys user scopes
     * @param string $user_id
     * @return string
     */
    public static function userScopeList(string $user_id)
    {
        $version = self::version(CacheVersions::SCOPES);

        return "v:$version:user:$user_id:scopes:list";
    }

    /**
     * Cache key passport
     * @return string
     */
    public static function passportScopes()
    {
        $version = self::version(CacheVersions::SCOPES);

        return "v:$version:passport:scopes";
    }

    /**
     * Cache keys settings
     * @param string $key
     * @return string
     */
    public static function settings(string $key)
    {
        $version = self::version(CacheVersions::SETTINGS);

        return "v:$version:settings:$key";
    }

    /**
     * Cache keys broadcast
     * @return string
     */
    public static function broadcast()
    {
        $version = self::version(CacheVersions::BROADCAST);

        return "v:$version:broadcast:channels";
    }

    /**
     * Cache config keys
     * @return string
     */
    public static function config()
    {
        $version = self::version(CacheVersions::CONFIG);

        return "v:$version:config:keys";
    }
}
