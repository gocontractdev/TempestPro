<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionFormRequest;
use App\Http\Requests\RoleFormRequest;
use App\Http\Requests\TestFormRequest;
use App\Interaction;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\PermissionHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;


class AccessController extends Controller
{
    public function testPermission(TestFormRequest $request)
    {;
        $data = $request->all();
        $user = $request->user();
        $permission = Permission::where('key', $data['key'])->firstOrFail();
        $targetUser = User::find($data['user_id']);

        $check = (new PermissionHelper($user))->can($targetUser, $permission);

        return new JsonResponse([
            'status' => true,
            'data' => [
                'check' => $check,
            ],
        ]);
    }

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
        $user = User::find($data['user_id']);
        $user->role_id = $data['role_id'];
        $user->save();

        return new JsonResponse([
            'status' => true,
            'data' => $user,
        ]);
    }

}
