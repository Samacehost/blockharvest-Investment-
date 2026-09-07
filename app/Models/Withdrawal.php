<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $guarded = [];

    protected $casts = [
        'destination_details_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function withdrawalMethod()
    {
        return $this->belongsTo(WithdrawalMethod::class);
    }

    public function approvedByAdmin()
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }
}
