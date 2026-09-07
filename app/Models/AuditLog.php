<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'old_values_json' => 'array',
        'new_values_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
