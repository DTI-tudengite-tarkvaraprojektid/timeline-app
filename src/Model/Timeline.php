<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
    
    public function events()
    {
        return $this->hasMany('App\Model\Event');
    }
}