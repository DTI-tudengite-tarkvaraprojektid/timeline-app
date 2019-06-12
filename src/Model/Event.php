<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    use FullTextSearchTrait;

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function timeline()
    {
        return $this->belongsTo('App\Model\Timeline');
    }

    public function content()
    {
        return $this->hasMany('App\Model\Content');
    }
}