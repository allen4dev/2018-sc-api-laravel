<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        return response()->json([
            'data' => [
                'type' => 'users', 'id' => (string) $user->id
            ]
        ], 200);
    }
}
