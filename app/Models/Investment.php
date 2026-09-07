<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'next_payout_at' => 'datetime',
        'matures_at' => 'datetime',
        'completed_at' => 'datetime',
        'capital_return' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_id');
    }

    public function earnings()
    {
        return $this->hasMany(InvestmentEarning::class);
    }
}
