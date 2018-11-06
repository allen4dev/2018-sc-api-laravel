<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserCollection;

use App\User;

class UserFollowingsController extends Controller
{
    public function index(User $user)
    {
        return new UserCollection($user->followings()->paginate(), route('users.following', $user->id));
    }
}
