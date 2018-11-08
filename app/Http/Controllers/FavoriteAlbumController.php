<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\AlbumResource;

use App\Album;

class FavoriteAlbumController extends Controller
{
    public function store(Album $album)
    {
        $album->favorite();

        return new AlbumResource($album);
    }

    public function destroy(Album $album)
    {
        $album->unfavorite();

        return new AlbumResource($album);
    }
}
