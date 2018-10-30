<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Track;

class TrackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|min:2',
        ]);

        $track = auth()->user()->createTrack($request);

        return response()->json([
            'data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
            ]
        ], 201);
    }

    public function show(Track $track)
    {
        return response()->json([
            'data' => [
                'type' => 'tracks',
                'id'   => (string) $track->id,
            ]
        ], 200);
    }
}
