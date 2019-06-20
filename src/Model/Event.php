<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    use FullTextSearchTrait;

    public static function boot() {
        parent::boot();

        static::deleting(function($event) {
            $event->contents->each(function ($content) {
                $content->delete();
            });
        });
    }

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

    public function contents()
    {
        return $this->hasMany('App\Model\Content');
    }
}