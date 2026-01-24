<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKeys;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorDisabledResponse;
use Laravel\Fortify\Contracts\TwoFactorEnabledResponse;

final class TwoFactorAuthenticationController extends \Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\EnableTwoFactorAuthentication  $enable
     * @return \Laravel\Fortify\Contracts\TwoFactorEnabledResponse
     */
    public function store(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $user = $request->user();

        $enable($user, $request->boolean('force', false));

        // Retrieve cache key
        $cacheKey = CacheKeys::user($user->id);
        // Clean cache for this user
        Cache::forget($cacheKey);
        Cache::forget(CacheKeys::userAuth($user->id));

        return app(TwoFactorEnabledResponse::class);
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\DisableTwoFactorAuthentication  $disable
     * @return \Laravel\Fortify\Contracts\TwoFactorDisabledResponse
     */
    public function destroy(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $user = $request->user();

        $disable($user);

        // Retrieve cache key
        $cacheKey = CacheKeys::user($user->id);
        // Clean cache for this user
        Cache::forget($cacheKey);
        Cache::forget(CacheKeys::userAuth($user->id));

        return app(TwoFactorDisabledResponse::class);
    }
}
