<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
    use SoftDeletes;
    use FullTextSearchTrait;
    
    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'name',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
    
    public function events()
    {
        return $this->hasMany('App\Model\Event');
    }
}