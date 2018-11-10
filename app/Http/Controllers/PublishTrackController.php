<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;

use App\Notifications\TrackPublished;

use App\Http\Resources\TrackResource;

use App\Track;

class PublishTrackController extends Controller
{
    public function update(Track $track)
    {
        $this->authorize('update', $track);

        $track->update([ 'published' => true ]);
        
        // notify authenticated user followers
        Notification::send(auth()->user()->followers, new TrackPublished($track));

        return new TrackResource($track);
    }
}
