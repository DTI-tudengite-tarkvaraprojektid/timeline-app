<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'removed' => null,
    ];
}