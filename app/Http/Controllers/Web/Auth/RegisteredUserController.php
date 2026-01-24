<?php

namespace App\Http\Controllers\Web\Auth;
use App\Http\Response\RegisterViewResponse;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Notifications\Member\MemberCreatedAccount;
use App\Http\Response\RegisterResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;

final class RegisteredUserController extends \Laravel\Fortify\Http\Controllers\RegisteredUserController
{
    /**
     * Show the registration view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\RegisterViewResponse
     */
    public function create(Request $request): RegisterViewResponse
    {
        return app(RegisterViewResponse::class);
    }

    /**
     * Create a new registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Laravel\Fortify\Contracts\RegisterResponse
     */
    public function store(Request $request, CreatesNewUsers $creator): RegisterResponse
    {
        if (config('fortify.lowercase_usernames') && $request->has(Fortify::username())) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        event(new Registered($user = $creator->create($request->all())));

        $user->notify(new MemberCreatedAccount());

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return app(RegisterResponse::class)->setUser($user);
    }
}
