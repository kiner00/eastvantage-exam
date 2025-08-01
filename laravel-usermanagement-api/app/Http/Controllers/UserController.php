<?php

namespace App\Http\Controllers;

use App\Exceptions\UserCreationException;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUserWithRoles($request->validated());
            return response()->json(new UserResource($user), Response::HTTP_CREATED);
        } catch (UserCreationException $e) {
            //We can add log to trace the error in the server
            return response()->json([
                'error' => 'User Creation Failed',
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $roleId = $request->query('role_id');
        $perPage = $request->query('per_page', 10);

        $users = $this->userService->getUsersByRole($roleId, $perPage);

        return UserResource::collection($users)->response();
    }
}
