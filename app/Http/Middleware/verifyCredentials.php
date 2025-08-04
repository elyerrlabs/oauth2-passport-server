<?php

namespace App\Http\Middleware;

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
 
use Closure;
use ErrorException;
use Illuminate\Http\Request;
use App\Models\Setting\Session;
use Illuminate\Cookie\CookieValuePrefix;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;

class verifyCredentials
{

    /**
     * The encrypter instance.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * Create a new CookieGuard instance.
     *
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @return void
     */
    public function __construct(EncrypterContract $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * ignore if the andpoint is 'aouth/token
         */
        $URI = $_SERVER['REQUEST_URI'];

        if (strpos($URI, 'oauth/token')) {

            return $next($request);
        }

        /**
         * check credential cookie and authorization
         */
        if ($request->header('Authorization') || $this->verifyCookie($request)) {

            return $next($request);
        }

        throw new ReportError(__('Unauthenticated'), 401);
    }

    /**
     * Deny cookie if the session has been destroyed
     *
     * @param Request $request
     * @return bool
     */
    public function verifyCookie(Request $request)
    {
        try {

            $user_id = $request->user()->id;
            $session_name = config('session.cookie');
            $session_value = $request->cookie($session_name);
            $decript_session = CookieValuePrefix::remove($this->encrypter->decrypt($session_value, EncryptCookies::serialized($session_name)));
            $session = Session::find($decript_session);
            return $session && $user_id == $session->user_id;

        } catch (ErrorException $e) {

            return strpos($_SERVER['REQUEST_URI'], "gateway/logout") ?: false;
        }
    }

}
