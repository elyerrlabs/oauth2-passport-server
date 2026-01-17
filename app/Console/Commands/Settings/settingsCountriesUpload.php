<?php

namespace App\Console\Commands\Settings;

/**
 * OAuth2 Passport Server — a centralized, modular authorization server
 * implementing OAuth 2.0 and OpenID Connect specifications.
 *
 * Copyright (c) 2026 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * Author: Elvis Yerel Roman Concha
 * Contact: yerel9212@yahoo.es
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
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
