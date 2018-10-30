<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

use App\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        return new UserResource($user);
    }
}
