<?php
namespace App\Models\Global;

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
 */

use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Master
{
    use HasFactory;

    public $table = "countries";

    public $timestamps = false;

    protected $fillable = [
        "name_en",
        "name_es",
        "continent_en",
        "continent_es",
        "capital_en",
        "capital_es",
        "dial_code",
        "code_2",
        "code_3",
        "tld",
        "km2",
        "emoji",
    ];

    /**
     * default countries
     * @return mixed
     */
    public static function defaultCountries()
    {
        return json_decode(file_get_contents(base_path('database/extra/countries.json')));
    }
}
