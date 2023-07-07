<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
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

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {

            return response()->json(
                $this->responseService->getOkResponse(
                    'Login successful',
                    [
                        'token' => Auth::user()->createToken('SPA')->plainTextToken,
                        'user' => UserResource::make(Auth::user()),
                    ]
                )
            );
        }

        return response()->json(
            $this->responseService->getErrorResponse('This credentials are not valid!'),
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $request->only([
            'name',
            'email',
        ]);
        $userData['password'] = Hash::make($request->get('password'));

        $user = $this->userRepository->createUser($userData);

        if ($user) {
            Auth::login($user);

            return response()->json(
                $this->responseService->getOkResponse(
                    'Registration successful',
                    [
                        'token' => Auth::user()->createToken('SPA')->plainTextToken,
                        'user' => UserResource::make($user)
                    ]
                )
            );
        }

        return response()->json(
            $this->responseService->getErrorResponse('Error when creating new user'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(
            $this->responseService->getOkResponse('Logout successful')
        );
    }
}
