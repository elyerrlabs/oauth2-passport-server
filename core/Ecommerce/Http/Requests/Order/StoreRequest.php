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
        // Retrieve the product with attributes       
        $product = !empty($this->product_id) ? Product::with('attributes')->find($this->product_id) : null;

        return [
            'product_id' => ['required', 'exists:products,id'],
            'attrs' => [
                'array',
                function ($attribute, $value, $fail) use ($product) {
                    if (!empty($product)) {
                        // Get attributes available with enough stock
                        $attributes = $product->attributes->where('pivot.stock', '>=', $this->quantity);

                        $attributes = $attributes
                            ->where('pivot.stock', '>', 0)
                            ->groupBy('name')
                            ->map(function ($items, $key) {
                                return [
                                    'name' => $key,
                                    'slug' => $items->first()['slug'],
                                ];
                            })
                            ->values();

                        if ($attributes->count() > 0) {
                            // Validate that all required attributes are selected
                            if (empty($value) || count($value) < $attributes->count()) {
                                $fail(__("You must select all required attributes for this product"));
                            }
                        }
                    }
                }
            ],
            'quantity' => [
                'required',
                'min:1',
                'integer',
                function ($attribute, $value, $fail) use ($product) {
                    if (!empty($product) && $value > $product->stock) {
                        $fail(__("The requested quantity (:requested) exceeds the available stock (:stock).", [
                            'requested' => $this->quantity,
                            'stock' => $product->stock,
                        ]));
                    }
                }
            ],
        ];
    }
}
