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

use App\Rules\BooleanRule;
use Core\Transaction\Model\Plan;
use App\Repositories\Traits\Generic;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
{
    use Generic;
    use Asset;

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
