<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notifications\TrackFavorited;

use App\Http\Resources\TrackResource;

use App\Track;

class FavoriteTrackController extends Controller
{
    public function store(Track $track)
    {
        $track->favorite();

        $track->user->notify(new TrackFavorited);
        
        return new TrackResource($track);
    }

    public function destroy(Track $track)
    {
        $track->unfavorite();

        return new TrackResource($track);
    }
}
