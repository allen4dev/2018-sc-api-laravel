<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackResource;

use App\Playlist;
use App\Track;

class PlaylistTrackController extends Controller
{
    public function store(Playlist $playlist, Track $track)
    {
        $playlist->addTrack($track);

        return new TrackResource($track);
    }

    public function remove(Playlist $playlist, Track $track)
    {
        $playlist->tracks()->detach($track->id);

        $playlist->tags()->detach($track->id);

        return response()->json([], 204);
    }
}
