<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositMethod extends Model
{
    protected $guarded = [];

    protected $casts = [
        'bank_details_json' => 'array',
        'crypto_address_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
