<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

use App\User;

class FollowersController extends Controller
{
    public function store(User $user)
    {
        auth()->user()->followers()->attach($user->id);

        return new UserResource($user);
    }
}
