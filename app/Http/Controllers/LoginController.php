<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'status' => 'ok',
                'message' => 'Login successful',
                'data' => UserResource::make(Auth::user()),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'This credentials are not valid!',
            'data' => [],
        ], Response::HTTP_UNAUTHORIZED);
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

            return response()->json([
                'status' => 'ok',
                'message' => 'Registration successful',
                'data' => UserResource::make($user),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Error when creating new user',
            'data' => [],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
