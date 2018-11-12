<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\ResourceShared;

use App\Http\Resources\PlaylistResource;

use App\Playlist;

class SharePlaylistsController extends Controller
{
    public function store(Playlist $playlist)
    {
        $playlist->user->notify(new ResourceShared($playlist));

        return new PlaylistResource($playlist);
    }
}
