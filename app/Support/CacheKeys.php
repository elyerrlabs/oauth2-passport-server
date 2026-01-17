<?php
namespace App\Support;

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
    public static function exceptKeys(string $key)
    {
        return !in_array($key, ['cache.default', 'cache.expires']);
    }
    public static function user(string $user_id)
    {
        return "user:$user_id";
    }

    public static function userScopes(string $user_id)
    {
        return "user:$user_id:scopes";
    }

    public static function userScopesApiKey(string $user_id)
    {
        return "user:$user_id:scopes:api_key";
    }


    public static function userAdmin(string $user_id)
    {
        return "user:$user_id:admin";
    }

    public static function userGroups(string $user_id)
    {
        return "user:$user_id:groups";
    }

    public static function userAuth(string $user_id)
    {
        return "user:$user_id:auth";
    }

    public static function userScopeList(string $user_id)
    {
        return "user:$user_id:scopes:list";
    }

    public static function passportScopes()
    {
        return "passport:scopes";
    }

    public static function settings(string $key)
    {
        return "settings:$key";
    }

    public static function broadcast()
    {
        return "broadcast:channels";
    }
}
