<?php

namespace Core\User\Http\Requests;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use Core\User\Model\User;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'last_name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8', 'max:60'],
            'birthday' => [
                Rule::requiredIf(fn () => filter_var(config('system.birthday.active', false), FILTER_VALIDATE_BOOL)),
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
        ];
    }
}
