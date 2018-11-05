<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackCollection;

use App\User;

class UserTracksController extends Controller
{
    public function index(User $user)
    {
        $route = route('users.tracks', [ 'id' => $user->id ]);

        return new TrackCollection($user->tracks()->paginate(), $route);
    }
}
