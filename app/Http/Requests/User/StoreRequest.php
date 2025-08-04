<?php
namespace App\Http\Requests\User;

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
 */

use App\Models\User\User;
use App\Rules\BooleanRule;
use Illuminate\Validation\Rule;
use App\Models\Subscription\Group;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'country' => ['required', 'max:150'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'max:150'],
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
                Rule::unique('users', 'phone'),
            ],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before: ' . User::setBirthday()],
            'verify_email' => ['nullable', 'boolean'],
        ];
    }
}
