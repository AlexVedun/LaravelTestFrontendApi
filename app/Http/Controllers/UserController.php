<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private ResponseService $responseService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ResponseService $responseService
    ){
        $this->userRepository = $userRepository;
        $this->responseService = $responseService;
    }

    public function get(): JsonResponse
    {
        $user = Auth::user();

        return response()->json(
            $this->responseService->getOkResponse(
                '',
                UserResource::make($user)
            )
        );
    }

    public function update(UpdateUserRequest $request)
    {
        $userData = $request->only([
            'name',
            'latitude',
            'longitude',
        ]);

        $user = $this->userRepository->updateUser($userData);

        if ($user) {
            return response()->json(
                $this->responseService->getOkResponse(
                    'User profile successfully updated',
                    UserResource::make($user)
                )
            );
        }

        return response()->json(
            $this->responseService->getErrorResponse('Cannot update user profile due to internal error'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function delete(): JsonResponse
    {
        if ($this->userRepository->deleteUser()) {
            return response()->json(
                $this->responseService->getOkResponse('User has been deleted successfully')
            );
        }

        return response()->json(
            $this->responseService->getErrorResponse('Cannot delete user due to internal error!'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
