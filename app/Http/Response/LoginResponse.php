<?php

namespace App\Http\Response;

final class LoginResponse implements \Laravel\Fortify\Contracts\LoginResponse
{
    public function toResponse($request)
    {
        // Redirect to
        $intended = session()->get('url.intended');

        // Delete session key

        // Only json request
        if (request()->wantsJson()) {
            // data
            $data = [
                'message' => __("Login into account was successfully"),
            ];

            // add page to redirect
            if (!empty($intended)) {
                $data['redirect_to'] = $intended;
            }
            session()->forget('url.intended');

            return response()->json(['data' => $data]);
        }

        return redirect()->intended(route('user.dashboard'));
    }
}
