<?php

namespace App\Http\Response;

final class RegisterResponse implements \Laravel\Fortify\Contracts\RegisterResponse
{

    private $user;

    public function __construct()
    {
        $this->user = null;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Email verification settings
        if (!config('system.registration.email.verification', false)) {

            auth()->login($this->user);

            // Redirect to the user profile
            return redirect()->route('user.profile')->with(
                'status',
                __('Your account has been registered successfully')
            );
        }

        return redirect()->route('login')->with(
            'status',
            __('Your account has been registered successfully. A verification email has been sent to your inbox.')
        );
    }
}
