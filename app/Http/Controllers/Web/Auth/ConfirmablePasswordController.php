<?php

namespace App\Http\Controllers\Web\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Laravel\Fortify\Http\Responses\FailedPasswordConfirmationResponse;
use App\Http\Response\PasswordConfirmedResponse;
use Laravel\Fortify\Actions\ConfirmPassword;
use App\Http\Response\ConfirmPasswordViewResponse;

final class ConfirmablePasswordController extends \Laravel\Fortify\Http\Controllers\ConfirmablePasswordController
{

    /**
     * Show the confirm password view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\ConfirmPasswordViewResponse
     */
    public function show(Request $request)
    {
        return app(ConfirmPasswordViewResponse::class);
    }

    /**
     * Confirm the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function store(Request $request)
    {
        $confirmed = app(ConfirmPassword::class)(
            $this->guard,
            $request->user(),
            $request->input('password')
        );

        if ($confirmed) {
            $request->session()->put('auth.password_confirmed_at', Date::now()->unix());
        }

        return $confirmed
            ? app(PasswordConfirmedResponse::class)
            : app(FailedPasswordConfirmationResponse::class);
    }

}
