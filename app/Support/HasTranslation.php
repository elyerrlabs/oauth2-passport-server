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
     * Boot the translation trait.
     *
     * Automatically appends translated attributes when the model
     * is retrieved from the database.
     */
    protected static function bootHasTranslation(): void
    {
        static::retrieved(function ($model) {
            $model->appendTranslations();
        });
    }

    /**
     * Append translated attributes to the model instance.
     *
     * Each translation is exposed as a dynamic attribute using the
     * "{attribute}_{locale}" format (e.g. name_es, slug_fr).
     */
    protected function appendTranslations(): void
    {
        if (!$this->relationLoaded('translations')) {
            $this->load('translations');
        }

        foreach ($this->translations as $translation) {
            $this->setAttribute(
                "{$translation->attribute}_{$translation->locale}",
                $translation->value
            );
        }
    }
}
