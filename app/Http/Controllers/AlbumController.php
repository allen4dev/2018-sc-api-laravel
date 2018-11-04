<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\AlbumResource;

use App\Album;

class AlbumController extends Controller
{
    public function show(Album $album)
    {
        return new AlbumResource($album);
    }

    public function store()
    {
        request()->validate([
            'title' => 'required|string',
        ]);

        $album = auth()->user()->createAlbum(request()->only('title'));

        return new AlbumResource($album);
    }

    public function update(Album $album)
    {
        $album->update([ 'published' => true ]);

        return new AlbumResource($album);
    }
}
