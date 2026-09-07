<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'capital_return' => 'boolean',
        'early_termination_allowed' => 'boolean',
        'featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }
}
