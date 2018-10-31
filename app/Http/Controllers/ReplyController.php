<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ReplyResource;
use App\Track;

class ReplyController extends Controller
{
    public function store(Track $track)
    {
        request()->validate([
            'body' => 'required|string|min:5',
        ]);

        $reply = $track->reply(request()->only('body'));

        return new ReplyResource($reply);
    }
}
