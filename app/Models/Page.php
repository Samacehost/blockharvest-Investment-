<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('display_order');
    }
}
