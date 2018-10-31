<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackResource;

use App\Track;

class PublishTrackController extends Controller
{
    public function update(Track $track)
    {
        $this->authorize('update', $track);

        $track->update([ 'published' => true ]);

        return new TrackResource($track);
    }
}
