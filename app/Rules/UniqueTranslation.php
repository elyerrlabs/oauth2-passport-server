<?php

namespace App\Rules;

use App\Contracts\Translatable;
use App\Models\Translation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueTranslation implements ValidationRule
{

    /**
     * Translatable model
     * @var Translatable
     */
    protected $translatable;

    /**
     * Construct
     * @param App\Contracts\Translatable $translatable
     */
    public function __construct(Translatable $translatable)
    {
        $this->translatable = $translatable;
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fieldsReppited = [];
        $fieldsEmpty = [];

        $inputs = extractTranslationsFields($this->translatable, request()->toArray());
        foreach ($inputs as $field => $value) {

            [$key, $locale] = explode('_', $field);

            if (empty($value)) {
                $fieldsEmpty[] = $field;
            }
        }

        if (!empty($fieldsEmpty)) {
            $fail(
                __('translations.errors.fields_are_empty', [
                    'fields' => implode(', ', $fieldsEmpty),
                ])
            );
        }

        foreach ($inputs as $field => $value) {

            [$key, $locale] = explode('_', $field);

            $attr = Translation::query()
                ->where(
                    'translatable_type',
                    $this->translatable->getMorphClassIdentifier()
                )
                ->where('locale', $locale)
                ->whereRaw(
                    "LOWER(attribute) = ?",
                    [strtolower($key)]
                )->whereRaw(
                    "LOWER(value) = ?",
                    [mb_strtolower($value)]
                )->first();


            if (!empty($attr)) {
                $fieldsReppited[] = $field;
            }
        }

        if (!empty($fieldsReppited)) {
            $fail(
                __('translations.errors.fields_in_use', [
                    'fields' => implode(', ', $fieldsReppited),
                ])
            );
        }
    }
}
