<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ReplyResource;
use App\Http\Resources\ReplyCollection;

use App\Track;
use App\Reply;

class ReplyController extends Controller
{
    public function index(Track $track)
    {
        return new ReplyCollection($track->replies()->paginate(), $track);
    }

    public function show(Reply $reply)
    {
        return new ReplyResource($reply);
    }

    public function store(Track $track)
    {
        request()->validate([
            'body' => 'required|string|min:5',
        ]);

        $reply = $track->reply(request()->only('body'));

        return new ReplyResource($reply);
    }
}