<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\ResourceShared;

use App\Http\Resources\AlbumResource;

use App\Album;

class ShareAlbumsController extends Controller
{
    public function store(Album $album)
    {
        $album->user->notify(new ResourceShared($album));

        return new AlbumResource($album);
    }
}
