<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

use App\User;

class FollowersController extends Controller
{
    public function store(User $user)
    {
        $user->follow();

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->unfollow();

        return new UserResource($user);
    }
}
