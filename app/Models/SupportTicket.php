<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(SupportCategory::class, 'support_category_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class)->orderBy('created_at', 'asc');
    }
}
