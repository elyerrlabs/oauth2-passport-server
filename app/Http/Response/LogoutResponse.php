<?php

namespace App\Http\Response;
use Illuminate\Support\Facades\Route;

final class LogoutResponse implements \Laravel\Fortify\Contracts\LogoutResponse
{
    public function toResponse($request)
    {
        // Added support for OpenID Connect
        if (!empty($request->post_logout_redirect_uri)) {
            return redirect($request->post_logout_redirect_uri);
        }

        return Route::has('welcome') ? redirect()->route('welcome') : redirect()->route('login');
    }
}
