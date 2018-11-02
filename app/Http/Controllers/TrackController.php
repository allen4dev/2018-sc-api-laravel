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
            'title' => 'string|min:2',
        ]);

        $track = auth()->user()->createTrack($request->only('title'));

        return new TrackResource($track);
    }

    public function show(Track $track)
    {
        return new TrackResource($track);
    }

    public function destroy(Track $track)
    {
        $this->authorize('delete', $track);

        $track->delete();

        return response()->json()->setStatusCode(204);
    }
}
