<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedByAdmin()
    {
        return $this->belongsTo(User::class, 'processed_by_admin_id');
    }

    public function related()
    {
        return $this->morphTo();
    }
}
