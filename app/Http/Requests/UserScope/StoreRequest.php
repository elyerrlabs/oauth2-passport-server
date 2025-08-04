<?php

namespace App\Http\Requests\UserScope;

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

use App\Models\Subscription\Scope;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'scopes' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        $fail(__("The :attribute must be an array", ['attribute' => $attribute]));
                    }

                    if (is_array($value)) {
                        foreach ($value as $id) {
                            try {
                                $scopes = Scope::find($id);
                                if (!$scopes) {
                                    $fail(__("The :attribute is not valid", ["attribute" => $attribute, "id" => $id]));
                                }
                            } catch (QueryException) {
                                $fail(__(
                                    "The :attribute is not valid",
                                    ["attribute" => $attribute, "id" => $id]
                                ));
                            }
                        }
                    }
                }
            ],
            'end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value && strtotime(now() > strtotime($value))) {
                        $fail(__(
                            "The :attribute must be a date greater than the current date.",
                            ['attribute' => $attribute]
                        ));
                    }
                }
            ]
        ];
    }
}
