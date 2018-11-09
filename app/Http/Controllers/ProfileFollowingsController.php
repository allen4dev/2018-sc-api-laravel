<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserCollection;

class ProfileFollowingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return new UserCollection(
            $user->followings()->paginate(),
            route('me.followings')
        );
    }
}
