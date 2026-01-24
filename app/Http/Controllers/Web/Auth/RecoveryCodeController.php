<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKeys;
use Laravel\Fortify\Http\Responses\RecoveryCodesGeneratedResponse;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

final class RecoveryCodeController extends \Laravel\Fortify\Http\Controllers\RecoveryCodeController
{

    /**
     * Generate a fresh set of two factor authentication recovery codes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Actions\GenerateNewRecoveryCodes  $generate
     * @return \Laravel\Fortify\Contracts\RecoveryCodesGeneratedResponse
     */
    public function store(Request $request, GenerateNewRecoveryCodes $generate)
    {
        $user = $request->user();

        $generate($user);

        // Retrieve cache key
        $cacheKey = CacheKeys::user($user->id);
        // Clean cache for this user
        Cache::forget($cacheKey);
        Cache::forget(CacheKeys::userAuth($user->id));

        return app(RecoveryCodesGeneratedResponse::class);
    }
}
