<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description',
        'uuid',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
