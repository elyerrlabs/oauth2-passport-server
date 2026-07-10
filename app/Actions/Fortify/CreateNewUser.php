<?php

namespace App\Actions\Fortify;

use App\Rules\AllowedDomains;
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
        $emailValidation = app()->environment('production')
            ? 'email:rfc,dns'
            : 'email:rfc';



        Validator::make($input, [
            'name' => ['required', 'regex:/^[\p{L}]+(?:[\p{L}\s\'-]*[\p{L}])?$/u', 'min:3', 'max:100'],
            'last_name' => ['required', 'regex:/^[\p{L}]+(?:[\p{L}\s\'-]*[\p{L}])?$/u', 'min:3', 'max:100'],
            'email' => ['required', $emailValidation, 'unique:users,email', new AllowedDomains()],
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
        ], [
            'email.required' => __('The credentials provided are invalid.'),
            'email.email' => __('The credentials provided are invalid.'),
            'email.unique' => __('The credentials provided are invalid.'),
        ])->validate();

        return app(UserService::class)->registerCustomer($input);
    }
}
