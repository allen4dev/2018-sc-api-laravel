<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Transformers\IncludeTransformer;

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
        IncludeTransformer::loadRelationships($track, request('include'));
        
        $resource = new TrackResource($track);

        if ($track->published) return $resource;

        $this->authorize('view', $track);
        
        return $resource;
    }

    public function destroy(Track $track)
    {
        $this->authorize('delete', $track);

        $track->delete();

        return response()->json()->setStatusCode(204);
    }
}
