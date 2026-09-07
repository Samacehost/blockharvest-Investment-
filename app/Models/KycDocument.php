<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycDocument extends Model
{
    protected $guarded = [];

    public function submission()
    {
        return $this->belongsTo(KycSubmission::class, 'kyc_submission_id');
    }
}
