<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Transformers\IncludeTransformer;

use App\Notifications\ResourceReplied;

use App\Http\Resources\ReplyResource;

use App\Reply;

class ReplyRepliesController extends Controller
{

    public function show(Reply $reply)
    {
        IncludeTransformer::loadRelationships($reply, request('include'));

        return new ReplyResource($reply);
    }

    public function store(Reply $reply)
    {
        $values = request()->validate([
            'body' => 'required|string|min:5',
        ]);

        $created = $reply->comment($values);

        $reply->user->notify(new ResourceReplied($reply));

        return new ReplyResource($created);
    }
}
