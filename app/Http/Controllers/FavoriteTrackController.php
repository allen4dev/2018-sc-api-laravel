<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackResource;

use App\Track;

class FavoriteTrackController extends Controller
{
    public function store(Track $track)
    {
        $track->favorites()->create([
            'user_id' => auth()->id(),
            'type' => 'track',        
        ]);

        return new TrackResource($track);
    }
}
