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
use App\Rules\BooleanRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . Request('user')->id],
            'country' => ['nullable', 'max:100', 'exists:countries,name_en'],
            'dial_code' => [
                'nullable',
                'required_with:phone',
                'max:8',
                'exists:countries,dial_code',
            ],
            'phone' => [
                'nullable',
                'required_with:dial_code',
                'max:25',
                'unique:users,phone,' . Request('user')->id
            ],
            'address' => ['nullable', 'max:150'],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
            'verify_email' => ['nullable', 'boolean'],
        ];
    }
}
