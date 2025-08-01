<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * We just use Role::all() because we only query
     * Author, Editor, Subscriber, Administrator
     *
     * in the real world scenario we can use cache since this is
     * not changing all the time
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Role::all());
    }
}
