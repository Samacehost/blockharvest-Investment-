<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'consent_data_json' => 'array',
    ];
}
