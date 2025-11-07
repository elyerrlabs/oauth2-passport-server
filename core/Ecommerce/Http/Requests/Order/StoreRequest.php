<?php

namespace Core\Ecommerce\Http\Requests\Order;

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


use Core\Ecommerce\Model\Product;
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
            'variant_id' => ['required', 'exists:variants,id'],
            'quantity' => [
                'required',
                'min:1',
                'integer',
                function ($attribute, $value, $fail) {
                    $variant = \App\Models\Common\Variant::find($this->variant_id);
                    if ($variant && $value > $variant->stock) {
                        $fail(__("The requested quantity (:requested) exceeds the available stock (:stock).", [
                            'requested' => $value,
                            'stock' => $variant->stock,
                        ]));
                    }
                }
            ],
        ];
    }
}
