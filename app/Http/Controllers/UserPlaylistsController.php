<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PlaylistCollection;

use App\User;

class UserPlaylistsController extends Controller
{
    public function index(User $user)
    {
        return new PlaylistCollection($user->playlists, route('users.playlists', [ 'id' => $user->id ]));
    }
}
