<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ReplyResource;
use App\Http\Resources\ReplyCollection;

use App\Notifications\ResourceReplied;

use App\Track;
use App\Reply;

class ReplyTracksController extends Controller
{
    public function index(Track $track)
    {
        return new ReplyCollection($track->replies()->paginate(), $track);
    }

    public function store(Track $track)
    {
        $values = request()->validate([
            'body' => 'required|string|min:5',
        ]);

        $this->authorize('reply', $track);

        $reply = $track->comment($values);

        $track->user->notify(new ResourceReplied($track));

        return new ReplyResource($reply);
    }
}
