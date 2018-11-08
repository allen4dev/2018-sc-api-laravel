<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PlaylistResource;

use App\Playlist;

class FavoritePlaylistController extends Controller
{
    public function store(Playlist $playlist)
    {
        $playlist->favorite();

        return new PlaylistResource($playlist);
    }

    public function destroy(Playlist $playlist)
    {
        $playlist->unfavorite();

        return new PlaylistResource($playlist);
    }
}
