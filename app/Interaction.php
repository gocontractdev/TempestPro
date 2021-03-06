<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'source_role_id',
        'target_role_id',
        'permission_id',
    ];

}
