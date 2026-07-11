<?php

namespace App\Support;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslation
{
    protected bool $localized = false;

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

        if ($this->localized) {
            return $attributes;
        }

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
        $this->localized = true;

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

        $this->unsetRelation('translations');

        return $this;
    }
}
