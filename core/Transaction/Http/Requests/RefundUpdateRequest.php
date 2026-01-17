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
use Core\Transaction\Model\Refund;
use Illuminate\Validation\Rule;

class RefundUpdateRequest extends FormRequest
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
            'status' => [
                'required',
                Rule::in(Refund::statuses()),
                function ($attribute, $value, $fail) {

                    $status = $this->order($this->refund->status);

                    if (!in_array($value, $status)) {
                        $fail(__('There are no transition rules defined for the current status :stat', ['stat' => $value]));
                    }
                }
            ]
        ];
    }


    public function order($index)
    {
        $status = [
            'pending' => [
                'processing'
            ],
            'processing' => [
                'under_review'
            ],
            'under_review' => [
                'approved',
                'rejected',
            ],
            'approved' => [
                'waiting_for_return',
            ],
            'waiting_for_return' => [
                'refunding'
            ],
            'refunding' => [
                'completed'
            ],
        ];

        return $status[$index] ?: false;
    }
}
