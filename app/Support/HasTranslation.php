<?php

namespace App\Support;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslation
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }


    /**
     * To array
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();

        foreach ($this->translations as $translation) {
            $attributes["{$translation->attribute}_{$translation->locale}"] = $translation->value;
        }

        return $attributes;
    }

    /**
     * Localize lang
     * @param string $locale
     */
    public function localize(?string $locale = null): static
    {

        $locale ??= app()->getLocale();

        foreach ($this->translations as $translation) {

            if ($translation->locale !== $locale) {
                continue;
            }

            $this->setAttribute(
                $translation->attribute,
                $translation->value
            );
        }

        return $this;
    }

    /**
     * get indetifier class
     */
    public function getMorphClassIdentifier(): string
    {
        $class = static::class;

        foreach (config('morph') as $key => $value) {
            if (app($value) instanceof $class) {
                return $key;
            }
        }

        return $class;
    }

    /**
     * Append translated attributes to the model instance.
     *
     * Each translation is exposed as a dynamic attribute using the
     * "{attribute}_{locale}" format (e.g. name_es, slug_fr).
     */
    public function getAttribute($key)
    {
        if (preg_match('/^(.+)_([a-z]{2})$/', $key, $matches)) {

            [$full, $attribute, $locale] = $matches;

            if (
                in_array($attribute, $this->getTranslatableAttributes())
            ) {

                $translation = $this->translations
                    ->first(
                        fn($item) =>
                        $item->attribute === $attribute &&
                        $item->locale === $locale
                    );

                return $translation?->value ?? parent::getAttribute($attribute);
            }
        }

        return parent::getAttribute($key);
    }
}
