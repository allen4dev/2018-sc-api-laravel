<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackResource;

use App\User;

class UserTracksController extends Controller
{
    public function index(User $user)
    {
        return TrackResource::collection($user->tracks()->paginate());
    }
}
