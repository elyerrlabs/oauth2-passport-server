<?php

namespace App\Models;

use App\Models\Master;

class Translation extends Master
{
    public $table = "translations";

    protected $fillable = [
        'locale',
        'attribute',
        'value',
    ];

    /**
     * Summary of translatable
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<\Illuminate\Database\Eloquent\Model, Translation>
     */
    public function translatable()
    {
        return $this->morphTo();
    }
}
