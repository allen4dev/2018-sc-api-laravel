<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackCollection;

use App\User;

class UserTracksController extends Controller
{
    public function index(User $user)
    {
        return new TrackCollection($user->tracks()->paginate());
    }
}
