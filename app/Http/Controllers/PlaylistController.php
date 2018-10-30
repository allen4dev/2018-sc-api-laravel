<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function store()
    {
        request()->validate([
            'title' => 'required|string|min:2',
        ]);

        $playlist = auth()->user()->playlists()->create([
            'title' => request('title'),
        ]);

        return response()->json([
            'data' => [
                'type' => 'playlists',
                'id'   => (string) $playlist->id,
            ]
        ], 201);
    }
}
