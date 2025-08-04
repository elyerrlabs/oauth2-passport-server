<?php

namespace App\Console\Commands\Settings;

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


use App\Models\Global\Country;
use Illuminate\Console\Command;

class settingsCountriesUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:countries-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upload countries';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("Upload countries");
        $this->upload_countries();
        $this->info("Uploaded successfully");
    }

    /**
     * Upload default countries
     * @return void
     */
    public function upload_countries()
    {
        $countries = Country::defaultCountries();

        foreach ($countries as $country) {
            Country::updateOrcreate(
                ['name_en' => $country->name_en],
                [
                    "name_en" => $country->name_en,
                    "name_es" => $country->name_es,
                    "continent_en" => $country->continent_en,
                    "continent_es" => $country->continent_es,
                    "capital_en" => $country->capital_en,
                    "capital_es" => $country->capital_es,
                    "dial_code" => $country->dial_code,
                    "code_2" => $country->code_2,
                    "code_3" => $country->code_3,
                    "tld" => $country->tld,
                    "km2" => $country->km2,
                    "emoji" => $country->emoji,
                ]
            )->save();
        }
    }
}
