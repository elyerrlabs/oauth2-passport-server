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

use Core\Transaction\Repositories\TransactionRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RefundStoreRequest extends FormRequest
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
        $repository = app(TransactionRepository::class);

        return [
            'reason' => ['required', 'min:5', 'max:150'],
            'description' => ['required', 'min:5', 'max:1000'],
            'amount' => [
                'required',
                'min:0',
                function ($attribute, $value, $fail) use ($repository) {
                    if (!empty($this->transaction_code)) {
                        $transaction = $repository->findByCode($this->transaction_code);

                        if (!empty($transaction) && (int) $value > $transaction->total) {
                            $fail('The refund amount cannot exceed the total amount of the original transaction.');
                        }
                    }
                }
            ],
            'evidence' => ['required', 'array', 'max:10'],
            'transaction_code' => ['required', 'exists:transactions,code'],
            'evidence.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf,mp3,wav,ogg,mp4,mov,avi,mkv',
                'max:20480',
            ],
        ];
    }
}
