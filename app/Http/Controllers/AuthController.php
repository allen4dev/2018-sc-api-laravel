<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "username" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|string|min:6"
        ]);

        $user = User::create([
            "username" => $request->username,
            "email"    => $request->email,
            "password" => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            "data" => [
                "attributes" => [
                    "id" => $user->id,
                    'token' => $token,
                ]
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only(['email', 'password']);

        // ToDo: Handle invalid credentials
        $token = auth()->attempt($credentials);

        return response()->json([
            'data' => [
                'attributes' => [
                    'id' => auth()->id(),
                    'token' => $token,
                ]
            ]
        ], 200);
    }
}
