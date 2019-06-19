<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use FullTextSearchTrait;

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'content'
    ];

    public function event()
    {
        return $this->belongsTo('App\Model\Event');
    }
}
