<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\RegisterRequest;

use App\Http\Responses;

use App\Http\Resources\AuthResource;

use JWTAuth;

use App\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = $request->registerUser();

        $token = JWTAuth::fromUser($user);

        return new AuthResource($user, $token);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = auth()->attempt($credentials) ) {
            return Responses::format('auth', ['error' => 'handle auth error'], '401');
        }
        
        return new AuthResource(auth()->user(), $token);
    }
}
