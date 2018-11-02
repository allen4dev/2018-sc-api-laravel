<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackResource;

use App\Track;

class FavoriteTrackController extends Controller
{
    public function store(Track $track)
    {
        $track->favorite();

        return new TrackResource($track);
    }
}
