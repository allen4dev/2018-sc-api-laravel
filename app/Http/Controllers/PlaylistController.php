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

        return new PlaylistResource($playlist);;
    }

    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);

        $playlist->delete();

        return response()->json([], 204);
    }
}
