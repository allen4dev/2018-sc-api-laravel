<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Transformers\IncludeTransformer;

use App\Http\Resources\PlaylistResource;

use App\Playlist;

class PlaylistController extends Controller
{
    public function show(Playlist $playlist)
    {
        IncludeTransformer::loadRelationships($playlist, request('include'));

        return new PlaylistResource($playlist);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:2',
            'photo' => 'required|image',
        ]);

        $playlist = auth()->user()->createPlaylist($validated);

        return new PlaylistResource($playlist);;
    }

    public function update(Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $validated = request()->validate([
            'title' => 'string|min:2',
        ]);

        $playlist->update($validated);

        return new PlaylistResource($playlist);
    }

    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);

        $playlist->delete();

        return response()->json([], 204);
    }
}
