<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Validation\APIAuthException;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthenticateAPIMiddleware;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthJWTController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw new APIAuthException($validator->errors(), 400);
        }

        if (!$token = auth(AuthenticateAPIMiddleware::GUARD)->attempt($validator->validated())) {
            throw new APIAuthException('Invalid credentials');
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|between:2,100',
            'email'    => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            throw new APIAuthException($validator->errors(), 400);
        }

        User::create(array_merge($validator->validated(), ['password' => Hash::make($request->password)]));

        return $this->login($request);
    }

    public function logout(): JsonResponse
    {
        auth(AuthenticateAPIMiddleware::GUARD)->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth(AuthenticateAPIMiddleware::GUARD)->refresh());
    }

    protected function createNewToken(string $token): JsonResponse
    {
        return $this->successResult([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth(AuthenticateAPIMiddleware::GUARD)->factory()->getTTL() * 60,
        ]);
    }
}
