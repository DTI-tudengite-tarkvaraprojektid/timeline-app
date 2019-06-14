<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    public function event()
    {
        return $this->belongsTo('App\Model\Event');
    }
}