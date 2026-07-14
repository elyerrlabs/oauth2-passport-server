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
        $query = Translation::query()
            ->where(
                'translatable_type',
                $this->translatable->getMorphClassIdentifier()
            );

        $errors = [];

        $inputs = extractTranslationsFields($this->translatable, request()->toArray());

        foreach ($inputs as $field => $value) {

            [$key, $locale] = explode('_', $field);

            $attr = $query->where('locale', $locale)
                ->whereRaw(
                    "LOWER(attribute) = ?",
                    [strtolower($key)]
                )->first();

            if (!empty($attr)) {
                $errors[] = [$field => $value];
            }
        }


        //   dd($errors);
        // debo buscar en en la tabla 
    }
}
