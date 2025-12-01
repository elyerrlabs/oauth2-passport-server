<?php

namespace Core\Transaction\Http\Requests;

/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: LicenseRef-NC-Open-Source-Project
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
