<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PlaylistResource;

use App\Playlist;

class PlaylistController extends Controller
{
    public function show(Playlist $playlist)
    {
        return new PlaylistResource($playlist);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:2',
        ]);

        $playlist = auth()->user()->createPlaylist($request->only('title'));

        return response()->json([
            'data' => [
                'type' => 'playlists',
                'id'   => (string) $playlist->id,
            ]
        ], 201);
    }
}
