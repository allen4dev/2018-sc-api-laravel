<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PlaylistCollection;

class ProfilePlaylistsController extends Controller
{
    public function index()
    {
        return new PlaylistCollection(auth()->user()->playlists()->paginate(), route('me.playlists'));
    }
}
