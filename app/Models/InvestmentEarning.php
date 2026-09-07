<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentEarning extends Model
{
    protected $guarded = [];

    protected $casts = [
        'earned_at' => 'datetime',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
