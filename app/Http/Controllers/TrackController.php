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
        $validated = $request->validate([
            'title'  => 'required|string|min:2',
            'photo'  => 'required|image',
            'src'    => 'required|mimes:mpga',
            'tags'   => 'required|array',
            'tags.*' => 'required|integer|distinct|exists:tags,id'
        ]);

        $track = auth()->user()->createTrack($validated);

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

    public function update(Track $track)
    {
        $this->authorize('update', $track);

        $validated = request()->validate([ 'title' => 'string|min:2' ]);

        $track->update($validated);

        return new TrackResource($track);
    }

    public function destroy(Track $track)
    {
        $this->authorize('delete', $track);

        $track->delete();

        return response()->json()->setStatusCode(204);
    }
}
