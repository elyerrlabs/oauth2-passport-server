<?php
namespace App\Models\Setting;

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
use App\Transformers\Session\SessionTransformer;
use Elyerr\ApiResponse\Assets\Asset;

class Session extends Master
{
    use Asset;

    public $table = "sessions";

    public $transformer = SessionTransformer::class;

    protected $fillable = [
        //
    ];

    protected $hidden = [
        //  'user_id'
    ];

    public function getLastActivityAttribute($value)
    {
        $date = date('Y-m-d H:i:s', $value);

        return $this->format_date($date);
    }
}
