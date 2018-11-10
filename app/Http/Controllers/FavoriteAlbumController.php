<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\ResourceFavorited;

use App\Http\Resources\AlbumResource;

use App\Album;

class FavoriteAlbumController extends Controller
{
    public function store(Album $album)
    {
        $album->favorite();

        $album->user->notify(new ResourceFavorited);

        return new AlbumResource($album);
    }

    public function destroy(Album $album)
    {
        $album->unfavorite();

        return new AlbumResource($album);
    }
}
