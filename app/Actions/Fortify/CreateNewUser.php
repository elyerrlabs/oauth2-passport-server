<?php

namespace App\Actions\Fortify;

use Core\User\Services\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Core\User\Model\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $this->recoveryReferralCode(request());

        if (request()->has('referral_code')) {
            $input['referral_code'] = request()->get('referral_code');
        }

        Validator::make($input, [
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:60'],
            'birthday' => [
                Rule::requiredIf(fn() => filter_var(config('system.birthday.active', false), FILTER_VALIDATE_BOOL)),
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) {
                    $activated = filter_var(config('system.birthday.active', false), FILTER_VALIDATE_BOOL);
                    $limit = filter_var(config('system.birthday.limit', 15), FILTER_VALIDATE_INT);

                    if ($activated && $value) {
                        $birthday = Carbon::createFromFormat('Y-m-d', $value);
                        if ($birthday->diffInYears(Carbon::now()) < $limit) {
                            $fail(__('You must be at least :age years old.', ['age' => $limit]));
                        }
                    }
                },
            ],
            'accept_terms' => ['required', 'boolean'],
            'accept_cookies' => ['required', 'boolean'],
            'referral_code' => ['nullable'],
        ])->validate();

        return app(UserService::class)->registerCustomer($input);
    }


    /**
     * Recovery referral code from the redirect_to session
     * This method extracts the referral code from the redirect_to URL if it exists.
     * It checks if the redirect_to session variable is set, parses the URL,
     * and retrieves the referral_code from the query parameters.
     * If a referral code is found, it merges it into the request.
     * @return void
     */
    private function recoveryReferralCode(Request $request): void
    {
        $redirect_to = session()->get('redirect_to');
        $referral_code = null;

        if ($redirect_to) {
            $parsedUrl = parse_url($redirect_to);

            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $query_params);
                $referral_code = $query_params['referral_code'] ?? null;
            }

            if ($referral_code) {
                $request->merge(['referral_code' => $referral_code]);
            }
        }
    }
}
