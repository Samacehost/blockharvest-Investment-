<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depositMethod()
    {
        return $this->belongsTo(DepositMethod::class);
    }

    public function approvedByAdmin()
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }
}
