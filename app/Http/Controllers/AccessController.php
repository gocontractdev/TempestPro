<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccessController extends Controller
{

    public function assignRole(Request $request)
    {
        $response = new JsonResponse();


        return $response;
    }

    public function assignPermission(Request $request)
    {
        $response = new JsonResponse();


        return $response;
    }
}
