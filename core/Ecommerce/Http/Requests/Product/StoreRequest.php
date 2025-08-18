<?php

namespace Core\Ecommerce\Http\Requests\Product;

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
use Core\Ecommerce\Model\Product;
use Illuminate\Support\Str;
use App\Rules\UndefinedValues;
use Illuminate\Validation\Rule;
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
            'name' => [
                'required',
                'max:150',
                function ($attribute, $value, $fail) {
                    $slug = Str::slug($value);
                    $currentId = $this->input('id');
                    $searchSlug = Product::where('slug', $slug)->first();

                    if ($searchSlug) {
                        if ($currentId) {
                            $owner = Product::find($currentId);

                            if (
                                $owner &&
                                $searchSlug->id !== $owner->id &&
                                $searchSlug->tag !== $owner->tag
                            ) {
                                $fail("The name has already been registered in another context.");
                            }
                        } else {

                            $fail("The name has already been registered.");
                        }
                    }
                },
                new UndefinedValues()
            ],
            'short_description' => ['required', 'min:0', 'max:1000', new UndefinedValues()],
            'description' => ['required', new UndefinedValues()],
            'category' => ['required', 'max:100', new UndefinedValues()],
            'icon' => ['required', 'max:100', new UndefinedValues()],
            'stock' => [
                Rule::requiredIf(function () {
                    return !$this->filled('id');
                }),
                function ($attribute, $value, $fail) {

                    if (!empty($value)) {

                        $value = str_replace([',', '.'], '', $value);

                        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                            $fail("The stock value is invalid");
                        }
                    }
                }
            ],
            'images' => [
                Rule::requiredIf(function () {
                    return !$this->filled('id');
                }),
                'array'
            ],
            'images.*' => ['image', 'mimes:web,jpg,jpeg,bmp,png', 'max:2048'],
            'attributes' => ['required', 'array'],
            'attributes.*.name' => ['required', 'max:100', 'min:1', new UndefinedValues()],
            'attributes.*.type' => ['required', 'in:string,number,boolean,date', new UndefinedValues()],
            'attributes.*.value' => ['required', 'max:100', 'min:1', new UndefinedValues()],
            'attributes.*.widget' => ['required', 'max:100', 'min:1', new UndefinedValues()],
            'attributes.*.multiple' => ['required', new BooleanRule(), new UndefinedValues()],
            'attributes.*.stock' => [
                'nullable',
                function ($attribute, $value, $fail) {

                    if (!empty($value)) {

                        $value = str_replace([',', '.'], '', $value);

                        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                            $fail("The stock value is invalid");
                        }
                    }
                }
            ],
            'tags' => ['nullable', 'array'],
            'tags.*.name' => ['max:100', 'min:1'],
            'currency' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (empty(billing_get_currency($value))) {
                        $fail("The billing period is invalid");
                    }
                }
            ],
            'price' => [
                'required',
                function ($attribute, $value, $fail) {

                    $price = str_replace('.', '', $value);

                    if (filter_var($price, FILTER_VALIDATE_INT) == false) {
                        $fail("The value is invalid");
                    }
                }
            ],
        ];
    }
}
