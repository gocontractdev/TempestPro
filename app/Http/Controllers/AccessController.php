<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionFormRequest;
use App\Http\Requests\RoleFormRequest;
use App\Interaction;
use App\Permission;
use Illuminate\Http\JsonResponse;


class AccessController extends Controller
{
    public function assignPermission(PermissionFormRequest $request)
    {
        $permission = Permission::firstOrCreate(
            ['key' => $request->get('permission_key'),],
            ['key' => $request->get('permission_key'),]
        );
        $data = [
            'permission_id' => $permission->id,
            'source_role_id' => $request->get('source_role_id'),
            'target_role_id' => $request->get('target_role_id'),
        ];
        $newInteraction = Interaction::firstOrCreate($data, $data);
        return new JsonResponse([
            'status' => true,
            'data' => $newInteraction,
        ]);
    }

    public function assignRole(RoleFormRequest $request)
    {
        $data = $request->all();
        // todo : use laravel-permission
        // OR use model
        // OR plain query DB::table('role_user')->insert($data);
        return new JsonResponse([
            'status' => true,
        ]);
    }

}
