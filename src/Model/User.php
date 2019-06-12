<?php

namespace App\Model;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends EloquentUser
{
    use SoftDeletes;
    
    protected $table = 'user';

    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'permissions'
    ];

    protected $loginNames = ['email'];

    public function events()
    {
        return $this->hasMany('App\Model\Event');
    }

    public function timelines()
    {
        return $this->hasMany('App\Model\Timeline');
    }
}
