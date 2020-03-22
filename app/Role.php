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
        return $this->belongsToMany('App\Roles', 'Interaction', 'target_role_id');
    }

    public function targets()
    {
        return $this->belongsToMany('App\Roles', 'Interaction', 'source_role_id');
    }
}
