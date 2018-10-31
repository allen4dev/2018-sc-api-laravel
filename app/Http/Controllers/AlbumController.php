<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Album;

class AlbumController extends Controller
{
    public function show(Album $album)
    {
        return response()->json([
            'data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]
        ], 200);
    }

    public function store()
    {
        request()->validate([
            'title' => 'required|string',
        ]);

        $album = auth()->user()->createAlbum(request()->only('title'));

        return response()->json([
            'data' => [
                'type' => 'albums',
                'id'   => (string) $album->id,
            ]
        ], 201);
    }
}
