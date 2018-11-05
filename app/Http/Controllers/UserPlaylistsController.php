<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PlaylistResource;

use App\User;

class UserPlaylistsController extends Controller
{
    public function index(User $user)
    {
        return PlaylistResource::collection($user->playlists);
    }
}
