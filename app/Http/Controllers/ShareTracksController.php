<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackResource;

use App\Notifications\ResourceShared;

use App\Track;

class ShareTracksController extends Controller
{
    public function store(Track $track)
    {
        $track->share();

        $track->user->notify(new ResourceShared($track));

        return new TrackResource($track);
    }
}
