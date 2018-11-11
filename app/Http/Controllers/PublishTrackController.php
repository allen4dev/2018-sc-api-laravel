<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;

use App\Notifications\ResourcePublished;

use App\Http\Resources\TrackResource;

use App\Track;

class PublishTrackController extends Controller
{
    public function update(Track $track)
    {
        $this->authorize('update', $track);

        $track->update([ 'published' => true ]);
        
        Notification::send(auth()->user()->followers, new ResourcePublished($track));

        return new TrackResource($track);
    }
}
