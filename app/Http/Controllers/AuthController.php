<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\RegisterRequest;

use App\Http\Responses;

use JWTAuth;
use App\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = $request->registerUser();

        $token = JWTAuth::fromUser($user);

        $type = 'auth';
        $attributes = [ 'id' => (string) $user->id, 'token' => $token ];
        $status = 201;

        return Responses::format($type, $attributes, $status);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only(['email', 'password']);

        // ToDo: Handle invalid credentials
        if (! $token = auth()->attempt($credentials) ) {
            return Responses::format('auth', ['error' => 'handle auth error'], '401');
        }
        

        $type = 'auth';
        $attributes = [ 'id' => (string) auth()->id(), 'token' => $token ];
        $status = 200;

        return Responses::format($type, $attributes, $status);
    }
}
