<?php
namespace App\Support;

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
