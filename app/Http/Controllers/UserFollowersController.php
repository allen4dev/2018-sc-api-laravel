<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

use App\User;

class UserFollowersController extends Controller
{
    public function index(User $user)
    {
        return UserResource::collection($user->followings);
    }
}
