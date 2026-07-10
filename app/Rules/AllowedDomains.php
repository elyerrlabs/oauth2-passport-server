<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class AllowedDomains implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedDomains = explode(",", config('system.registration_allowed_domains', '*'));

        if (in_array('*', $allowedDomains)) {
            return;
        }

        $emailDomainIncomming = substr(strrchr($value, '@'), 1);

        if (in_array($emailDomainIncomming, $allowedDomains)) {
            return;
        }

        $fail(__('validation.allowed_domain'));
    }
}
