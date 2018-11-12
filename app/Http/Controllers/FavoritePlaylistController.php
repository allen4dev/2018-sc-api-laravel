<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\ResourceFavorited;

use App\Http\Resources\PlaylistResource;

use App\Playlist;

class FavoritePlaylistController extends Controller
{
    public function store(Playlist $playlist)
    {
        $playlist->favorite();

        $playlist->user->notify(new ResourceFavorited($playlist));

        return new PlaylistResource($playlist);
    }

    public function destroy(Playlist $playlist)
    {
        $playlist->unfavorite();

        return new PlaylistResource($playlist);
    }
}
