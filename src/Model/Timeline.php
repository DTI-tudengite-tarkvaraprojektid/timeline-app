<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
    use SoftDeletes;
    use FullTextSearchTrait;
    
    public static function boot() {
        parent::boot();

        static::deleting(function($timeline) {
            $timeline->events->each(function ($event) {
                $event->delete();
            });
        });
    }

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'timelines.name',
        'timelines.description'
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
    
    public function events()
    {
        return $this->hasMany('App\Model\Event');
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        $columns = implode(',', $this->searchable);

        $columns2 = implode(',', ['events.title', 'events.content']);

        $wildcardedTerm = $this->fullTextWildcards($term);

        return $query
            ->leftJoin('events', 'events.timeline_id', '=', 'timelines.id')
            ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $wildcardedTerm)
            ->orWhereRaw("MATCH ({$columns2}) AGAINST (? IN BOOLEAN MODE)", $wildcardedTerm)
            ->groupBy('timelines.id');
    }
}