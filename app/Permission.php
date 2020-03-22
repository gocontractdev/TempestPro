<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key',
    ];

    public static $rules = [
        'source_role_id' => 'required',
        'target_role_id' => 'required',
        '*_role_id' => 'distinct|exists:App\Role,id',
        'permission_key' => 'required',
    ];
}
