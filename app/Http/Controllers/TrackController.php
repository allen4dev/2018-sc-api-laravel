<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackResource;

use App\Track;

class TrackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|min:2',
        ]);

        $track = auth()->user()->createTrack($request);

        return new TrackResource($track);
    }

    public function show(Track $track)
    {
        return new TrackResource($track);
    }
}
