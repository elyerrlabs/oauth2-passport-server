<?php

namespace App\Http\Requests\Client;

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

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'nullable|max:191',
            'redirect' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $urls = explode(',', $value);
                        foreach ($urls as $url) {
                            $url = trim($url);
                            if (!preg_match('/^(https?:\/\/)/i', $url)) {
                                $fail(__(
                                    'Each URL in :attribute must start with http or https. invalid url :value',
                                    ['attribute' => $attribute, 'value' => $url]
                                ));
                                break;
                            }
                        }
                    }
                }
            ],
        ];
    }
}
