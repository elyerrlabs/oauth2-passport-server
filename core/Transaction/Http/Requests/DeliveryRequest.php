<?php

namespace Core\Transaction\Http\Requests;

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

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
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
            'full_name' => ['nullable', 'max:150'],
            'country' => ['required', 'max:150'],
            'state' => ['nullable', 'max:150'],
            'city' => ['required', 'max:150'],
            'district' => ['nullable', 'max:150'],
            'address' => ['required', 'max:150'],
            'address_line_2' => ['nullable', 'max:150'],
            'postal_code' => ['nullable', 'max:150'],
            'phone' => ['required', 'max:150'],
            'secondary_phone' => ['nullable', 'max:150'],
            'references' => ['nullable', 'max:150'],
        ];
    }


}
