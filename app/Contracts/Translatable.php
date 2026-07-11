<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Translatable
{
    /**
     * set the atributes to transalate 
     * Example : ['about', description]
     * @return array
     */
    public function getTranslatableAttributes(): array;


    /**
     * Hasy morph many translations
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function translations(): MorphMany;
}
