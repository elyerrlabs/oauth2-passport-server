<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UndefinedValues implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = strip_tags($value);

        if (in_array(strtolower($value), ['', ' ', 'undefined', 'null'], true)) {
            $fail(__(
                'The :attribute field is required',
                ['attribute' => $attribute]
            ));
        }
    }
}
