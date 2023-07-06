<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
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
        ]);
    }


}
