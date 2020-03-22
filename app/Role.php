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

    public function sources()
    {
        return $this->belongsToMany('App\Role', 'Interactions', 'target_role_id', 'source_role_id');
    }

    public function targets()
    {
        return $this->belongsToMany('App\Role', 'Interactions', 'source_role_id', 'target_role_id');
    }

    public function interactions()
    {
        return $this->hasMany('App\Interaction', 'source_role_id');
    }
}
