<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserCollection;

class ProfileFollowersController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return new UserCollection(
            $user->followers()->paginate(),
            route('me.followers')
        );
    }
}
