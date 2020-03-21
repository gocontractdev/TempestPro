<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleResourceCollection;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return RoleResourceCollection
     */
    public function index()
    {
        return new RoleResourceCollection(Role::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RoleResource
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'uuid' => $request->get('uuid'),
        ];
        $newRole = Role::create($data);
        return new RoleResource($newRole);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role)
    {
        //TODO: later
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        //TODO: later
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        //TODO: later
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
        return $response;
    }
}
